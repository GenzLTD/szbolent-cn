<?php
/**
 * SZBolent Bookstore —— 阅读站呈现层（书店自己的门面）
 *
 * 与 bookstore.php 的分工：
 *   bookstore.php        = 数据 / API（poem 类型、feed、全文 REST）—— 契约已定，本轮不改动
 *   bookstore-front.php  = 呈现（标题、canonical、首页分栏、体裁归档）
 *
 * 立这一刀的缘由（2026-10-04）：
 *   书店此前只有数据、没有自己的门面 —— 首页是 WP 默认的博文列表，站点名是 "Blog"，
 *   没有描述、没有站点图标、没有 canonical，同名词牌（最多 30 首同名）在浏览器标题里
 *   完全分不出来。橱窗那边读 feed 一切正常，读者点进来却认不出这是哪一家书店、哪一首。
 *
 * 约束（本轮边界，勿越）：
 *   - 不碰主题文件：皮肤是博主的 modown，首页模板复用 get_header()/get_footer()
 *   - 不碰 feed 契约：字段、节选两行、只含已发布、CORS 白名单都不变，橱窗照旧在读
 *   - 固定链接继续 /poem/<slug>/，不新增加入点、不改 rewrite
 *   - 收款 / 多租户不在本轮
 *   - 站点身份（站名、描述、站点图标）由 scripts/set-site-identity.php 一次性设置，
 *     这里不写死站名，一律取 get_bloginfo()，博主在后台改名照样生效
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/** 体裁顺序：首页按「词、诗」分栏（与 feed 的 genre 字段同源） */
function sb_genres() {
	return array( '词', '诗' );
}

/** 首页每栏条数（9417 篇不能一次列完，全量交给体裁归档页分页） */
function sb_home_per_genre() {
	return 20;
}

/** 体裁归档每页条数 */
function sb_archive_per_page() {
	return 40;
}

/** 取正文首段（首句），已去标签 */
function sb_first_line( $post ) {
	$text = wp_strip_all_tags( isset( $post->post_content ) ? $post->post_content : '' );
	$paras = array_values( array_filter( array_map( 'trim', explode( "\n", $text ) ) ) );
	return $paras ? $paras[0] : '';
}

/** 首句截断（按字符数，不是字节数） */
function sb_clip( $line, $n = 12 ) {
	$line = trim( preg_replace( '/\s+/u', ' ', (string) $line ) );
	if ( '' === $line ) return '';
	$cut = function_exists( 'mb_substr' ) ? mb_substr( $line, 0, $n ) : substr( $line, 0, $n * 3 );
	return $cut === $line ? $line : $cut;
}

/** 当前是否为某个体裁的归档（/poem/?genre=词） */
function sb_current_genre() {
	if ( ! is_post_type_archive( 'poem' ) ) return '';
	$g = get_query_var( 'genre', '' );
	$g = is_string( $g ) ? trim( $g ) : '';
	return in_array( $g, sb_genres(), true ) ? $g : '';
}

/** 体裁归档链接 */
function sb_genre_archive_url( $genre ) {
	return add_query_arg( 'genre', rawurlencode( $genre ), get_post_type_archive_link( 'poem' ) );
}

/** 按体裁取一批诗词（首页分栏 / 归档共用） */
function sb_query_poems( $genre, $per_page, $paged = 1 ) {
	$args = array(
		'post_type'      => 'poem',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => max( 1, (int) $paged ),
		'meta_key'       => 'luyou_id',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
	);
	if ( $genre ) {
		$args['meta_query'] = array( array( 'key' => 'genre', 'value' => $genre ) );
	}
	return new WP_Query( $args );
}

// ---------- 1) 体裁归档：?genre= 过滤 + 每页条数 ----------
add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'genre';
	return $vars;
} );

add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() || ! $q->is_main_query() || ! $q->is_post_type_archive( 'poem' ) ) return;
	$q->set( 'posts_per_page', sb_archive_per_page() );
	$q->set( 'meta_key', 'luyou_id' );
	$q->set( 'orderby', 'meta_value' );
	$q->set( 'order', 'ASC' );
	$g = get_query_var( 'genre', '' );
	$g = is_string( $g ) ? trim( $g ) : '';
	if ( in_array( $g, sb_genres(), true ) ) {
		$q->set( 'meta_query', array( array( 'key' => 'genre', 'value' => $g ) ) );
	}
} );

// ---------- 2) 标题：词牌页「词牌名（首句…）— 站名」----------
// 同名作品靠首句区分（同名词牌最多 30 首，浏览器标题里必须能一眼分辨）。
//
// 口径：博主的 modown 主题用的是旧 API wp_title()，不是 title-tag，
// 所以 document_title_parts 挂了也不生效 —— 两个入口都要给：
//   wp_title             → 本主题实际走这条
//   document_title_parts → 换主题 / 启用 title-tag 后仍然成立
// 只改诗词相关页，其它页面不夺主题的设定。

/** 取诗词相关页的标题主体，非相关页返回 null */
function sb_poem_title() {
	if ( is_singular( 'poem' ) ) {
		$post = get_queried_object();
		if ( ! $post ) return null;
		$line = sb_clip( sb_first_line( $post ) );
		return $line ? $post->post_title . '（' . $line . '…）' : $post->post_title;
	}
	$g = sb_current_genre();
	if ( $g ) return $g;
	if ( is_post_type_archive( 'poem' ) ) return '诗词';
	return null;
}

add_filter( 'wp_title', function ( $title, $sep, $seplocation ) {
	$t = sb_poem_title();
	if ( null === $t ) return $title;
	// 只给标题主体：站名由 wp_title() 自己按 seplocation 追加（实测它会再拼一次站名，
	// 这里再写就变成「…放翁文库放翁文库」）。分隔符用「—」，尾随空格对齐追加结果。
	return $t . ' — ';
}, 10, 3 );

add_filter( 'document_title_separator', function ( $sep ) {
	return ( null !== sb_poem_title() ) ? '—' : $sep;
} );

add_filter( 'document_title_parts', function ( $parts ) {
	$t = sb_poem_title();
	if ( null === $t ) return $parts;
	$parts['title'] = $t;
	unset( $parts['tagline'] );
	return $parts;
} );

// ---------- 3) canonical ----------
// 站点此前 canonical 完全没输出（首页、词牌页都没有）。这里接管：核心的 rel_canonical
// 摘掉，改由本插件按页面类型下发，避免主题/核心都不输出导致搜索引擎各抓各的。
remove_action( 'wp_head', 'rel_canonical' );
add_action( 'wp_head', function () {
	$link = '';
	if ( is_singular( 'poem' ) ) {
		$link = get_permalink();
	} elseif ( is_front_page() || is_home() ) {
		$link = home_url( '/' );
	} elseif ( sb_current_genre() ) {
		// 归档分页时 canonical 指向第一页，不带 page/2
		$link = sb_genre_archive_url( sb_current_genre() );
	} elseif ( is_post_type_archive( 'poem' ) ) {
		$link = get_post_type_archive_link( 'poem' );
	}
	if ( ! $link ) return;
	echo '<link rel="canonical" href="' . esc_url( $link ) . '">' . "\n";
}, 1 );

// ---------- 4) 首页：按体裁分栏，点进去就是原文页 ----------
// 用 template_include 接管，模板里 get_header()/get_footer() 照旧 —— 博主皮肤原样保留，
// 中间换成书店分栏。不改主题文件（themes/modown 由 CI 同步，改了会被覆盖）。
add_filter( 'template_include', function ( $template ) {
	if ( ! ( is_front_page() || is_home() ) ) return $template;
	$home = __DIR__ . '/templates/bookstore-home.php';
	return file_exists( $home ) ? $home : $template;
}, 99 );
