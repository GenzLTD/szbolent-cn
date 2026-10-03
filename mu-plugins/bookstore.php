<?php
/**
 * SZBolent Bookstore —— 陆游作品文库（书店）mu-plugin
 *
 * 落地位置：szbolent.cn 根的 WordPress（即 bolent_wp 容器）。
 * 架构（2026-10-02 决策：替换 cn 根为 modown 诗词书店）：
 *   - 书店本体 = cn WP（数据主权：个人博主自行运维 szbolent.cn）。
 *   - 橱窗（szbolent.com.cn 企业站）跨域取数被浏览器禁止，
 *     故 com.cn 宿主 nginx 用 `/shudian/` 服务端反代到本插件的 REST：
 *        com.cn/shudian/feed      → https://szbolent.cn/wp-json/bookstore/v1/feed
 *        com.cn/shudian/poem/<id> → https://szbolent.cn/wp-json/bookstore/v1/poem/<id>
 *     （反代在 com.cn 一侧做，cn 这边只暴露标准 wp-json，无需任何 CORS）。
 *
 * REST 接口（均同源、无需 CORS）：
 *   GET /wp-json/bookstore/v1/feed             节选（id/title/genre/excerpt/url），供橱窗陈列
 *   GET /wp-json/bookstore/v1/poem/<luyou_id>  全文（paragraphs/strains），供橱窗/书店点开阅读
 *
 * 安装：随 szbolent-cn 的 deploy 流程入库到 wp-content/mu-plugins/bookstore.php
 * 校验：php -l bookstore.php
 */

if (!defined('ABSPATH')) exit;

// ---------- 0) CORS：仅放行橱窗两个来源，严禁反射 / 带凭据 ----------
// 书店 feed 被橱窗跨域读取，但核心 rest_send_cors_headers 会无差别回显任意 Origin 并带
// Access-Control-Allow-Credentials: true（任意来源反射 + 凭据，危险）。这里在更高优先级
// 摘掉核心头，仅对白名单来源下发 ACAO，并置 Credentials 为 false。非白名单来源不回 ACAO，
// 浏览器自行拦截跨域读取。原文页是普通链接跳转，不走 CORS，不受影响。
add_filter( 'rest_pre_serve_request', function ( $served, $result, $request, $server ) {
    $route = ltrim( (string) $request->get_route(), '/' );
    if ( 0 !== strpos( $route, 'bookstore/v1' ) ) {
        return $served;
    }
    if ( headers_sent() ) {
        return $served;
    }

    // 摘掉 WordPress 核心对所有 REST 响应无差别回显的 CORS 头。
    header_remove( 'Access-Control-Allow-Origin' );
    header_remove( 'Access-Control-Allow-Credentials' );

    $allowed = array(
        'https://www.szbolent.com.cn',
        'https://szbolent.com.cn',
    );
    $origin = isset( $_SERVER['HTTP_ORIGIN'] ) ? (string) $_SERVER['HTTP_ORIGIN'] : '';
    $origin = untrailingslashit( esc_url_raw( wp_unslash( $origin ) ) );

    if ( in_array( $origin, $allowed, true ) ) {
        header( 'Access-Control-Allow-Origin: ' . $origin, true );
        header( 'Access-Control-Allow-Credentials: false', true );
        header( 'Access-Control-Allow-Methods: GET, OPTIONS', true );
        header( 'Access-Control-Allow-Headers: Content-Type, Accept', true );
    }
    header( 'Cache-Control: no-cache', true );

    return $served;
}, 20, 4 );

// ---------- 1) 自定义文章类型 poem ----------
add_action('init', function () {
    register_post_type('poem', [
        'labels'       => ['name' => '陆游诗词', 'singular_name' => '诗词'],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'poem'],
        'supports'     => ['title', 'editor', 'custom-fields'],
        'show_in_rest' => true,
        'menu_position'=> 20,
        'menu_icon'    => 'dashicons-book-alt',
    ]);
    register_meta('post', 'genre',    ['type' => 'string', 'single' => true, 'show_in_rest' => true]);
    register_meta('post', 'strains',  ['type' => 'string', 'single' => true, 'show_in_rest' => true]);
    register_meta('post', 'luyou_id', ['type' => 'string', 'single' => true, 'show_in_rest' => true]);
});

// ---------- 2) REST：节选 feed + 全文 poem ----------
add_action('rest_api_init', function () {
    // 节选 feed（橱窗陈列用，仅前两行）
    register_rest_route('bookstore/v1', '/feed', [
        'methods'             => 'GET',
        'permission_callback' => '__return_true',
        'args'                => ['limit' => ['type' => 'integer', 'default' => 50, 'sanitize_callback' => 'absint']],
        'callback'            => function ($req) {
            $limit = min(200, max(1, (int) $req['limit']));
            $q = new WP_Query([
                'post_type'      => 'poem', 'posts_per_page' => $limit,
                'orderby' => 'meta_value', 'meta_key' => 'luyou_id', 'order' => 'ASC',
                'post_status' => 'publish',
            ]);
            $items = [];
            foreach ($q->posts as $p) {
                $paras   = array_filter(explode("\n", wp_strip_all_tags($p->post_content)));
                $excerpt = array_slice($paras, 0, 2);
                $items[] = [
                    'id'     => get_post_meta($p->ID, 'luyou_id', true) ?: (string) $p->ID,
                    'title'  => $p->post_title,
                    'genre'  => get_post_meta($p->ID, 'genre', true),
                    'excerpt'=> array_values($excerpt),
                    'url'    => get_permalink($p->ID),
                ];
            }
            return ['count' => count($items), 'items' => $items];
        },
    ]);

    // 全文（橱窗/书店点开阅读用，同源拉取，不跳 WP 页面）
    register_rest_route('bookstore/v1', '/poem/(?P<id>[^/]+)', [
        'methods'             => 'GET',
        'permission_callback' => '__return_true',
        'args'                => ['id' => ['required' => true, 'sanitize_callback' => 'sanitize_text_field']],
        'callback'            => function ($req) {
            $posts = get_posts([
                'post_type' => 'poem', 'meta_key' => 'luyou_id',
                'meta_value' => $req['id'], 'posts_per_page' => 1,
            ]);
            if (empty($posts)) return new WP_Error('not_found', 'poem not found', ['status' => 404]);
            $p = $posts[0];
            return [
                'id'        => $req['id'],
                'title'     => $p->post_title,
                'genre'     => get_post_meta($p->ID, 'genre', true),
                'paragraphs'=> array_values(array_filter(explode("\n", wp_strip_all_tags($p->post_content)))),
                'strains'   => array_values(array_filter(explode("\n", (string) get_post_meta($p->ID, 'strains', true)))),
                'url'       => get_permalink($p->ID),
            ];
        },
    ]);
});

// ---------- 3) 一次性导入（幂等，按 luyou_id 去重） ----------
// 用法（在 WP 环境内，见 scripts/import-poems.php）：
//   php import-poems.php           全量（9,417 篇）
//   php import-poems.php 30        仅前 30 篇（试点）
// luyou.json 由 myblog-v1/showcase/scripts/build_data.py 生成，结构：
//   {"works":[{"id":"luyou-shi:00001","g":"诗","t":"标题","p":["段1","段2"],"s":["韵律"]}]}
function szbolent_import_poems($json_path, $limit = 0) {
    if (!file_exists($json_path)) return 'file not found: ' . $json_path;
    $raw = file_get_contents($json_path);
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) return 'json decode error: ' . json_last_error_msg();
    $works = $data['works'] ?? [];
    if (empty($works)) return 'no works in json';
    if ($limit > 0) $works = array_slice($works, 0, $limit);
    $n = 0;
    $skipped = 0;
    foreach ($works as $w) {
        if (empty($w['id']) || empty($w['t'])) { $skipped++; continue; }
        $existing = get_posts([
            'post_type' => 'poem', 'meta_key' => 'luyou_id',
            'meta_value' => $w['id'], 'posts_per_page' => 1, 'fields' => 'ids',
        ]);
        $content = is_array($w['p']) ? implode("\n", $w['p']) : '';
        $postarr = [
            'post_title' => $w['t'], 'post_content' => $content,
            'post_type' => 'poem', 'post_status' => 'publish',
        ];
        if (!empty($existing)) $postarr['ID'] = $existing[0];
        $id = wp_insert_post($postarr);
        if (!is_wp_error($id)) {
            update_post_meta($id, 'genre',    $w['g'] ?? '');
            update_post_meta($id, 'luyou_id', $w['id']);
            update_post_meta($id, 'strains',  is_array($w['s']) ? implode("\n", $w['s']) : '');
            $n++;
        }
    }
    return "imported/updated $n poems (skipped $skipped invalid)";
}
