<?php
/**
 * 放翁文库 (Modown Child) — 功能注入
 *  - 国风样式加载
 *  - 注册 poem_genre / poem_cipai 分类法，并从元字段/标题回填（一次性、幂等）
 *  - 修复主导航（创建并指派 main 菜单，消除 “Please go to the background Appearance-Menu” 警告）
 *  - 提供每日一诗、体裁/词牌分面等所需钩子
 */

if ( ! defined( 'FW_LOOMA_URL' ) ) {
	define( 'FW_LOOMA_URL', 'https://szbolent.com.cn/' ); // Looma 对话入口（待接入真实 API 时替换）
}

// 1) 加载国风样式 + 给 body 加标记
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'fw-fangweng', get_stylesheet_directory_uri() . '/style-fangweng.css', array(), '1.0.0' );
} );
add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'fw-themed';
	return $classes;
} );

// 2) 注册诗词分类法（体裁 / 词牌）
add_action( 'init', 'fw_register_poem_taxonomies', 5 );
function fw_register_poem_taxonomies() {
	if ( ! post_type_exists( 'poem' ) ) {
		return;
	}
	register_taxonomy( 'poem_genre', array( 'poem' ), array(
		'label'             => '体裁',
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'rewrite'           => array( 'slug' => 'poem-genre', 'with_front' => false ),
		'hierarchical'      => false,
	) );
	register_taxonomy( 'poem_cipai', array( 'poem' ), array(
		'label'             => '词牌',
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'rewrite'           => array( 'slug' => 'poem-cipai', 'with_front' => false ),
		'hierarchical'      => false,
	) );
}

// 3) 回填分类法（从 meta.genre 与标题解析词牌）：幂等 + 可断点续跑
//    进度存于 transient fw_backfill_page（1..N 为下一待处理页，-1 表示已完成）。
//    前台 init 每请求处理一批；部署脚本可一次性跑完（CLI 无超时压力）。
add_action( 'init', 'fw_maybe_backfill_taxonomies', 20 );
function fw_maybe_backfill_taxonomies() {
	if ( ! taxonomy_exists( 'poem_genre' ) || ! taxonomy_exists( 'poem_cipai' ) ) {
		return;
	}
	$raw = get_transient( 'fw_backfill_page' );
	if ( $raw === -1 ) {
		return; // 已完成
	}
	$page = $raw ? (int) $raw : 1;

	$posts = get_posts( array(
		'post_type'      => 'poem',
		'post_status'    => 'publish',
		'posts_per_page' => 150,
		'paged'          => $page,
		'fields'         => 'ids',
		'orderby'        => 'ID',
		'order'          => 'ASC',
	) );
	if ( empty( $posts ) ) {
		set_transient( 'fw_backfill_page', -1, MONTH_IN_SECONDS );
		return;
	}
	foreach ( $posts as $pid ) {
		$genre = get_post_meta( $pid, 'genre', true );
		if ( $genre ) {
			wp_set_object_terms( $pid, $genre, 'poem_genre', true );
		}
		// 词牌：标题形如 “赤壁词・念奴娇” 或 “X・念奴娇（…）”，取 “・” 之后、括号之前
		$title = get_the_title( $pid );
		if ( preg_match( '/・(.+?)(?:（|\()/u', $title, $m ) || preg_match( '/・(.+)$/u', $title, $m ) ) {
			$cipai = trim( $m[1] );
			if ( $cipai ) {
				wp_set_object_terms( $pid, $cipai, 'poem_cipai', true );
			}
		}
	}
	set_transient( 'fw_backfill_page', $page + 1, MONTH_IN_SECONDS );
}

// 3.5) 安全的分类法链接（CJK 词牌/体裁 slug 不确定时使用，避免硬编码 404）
function fw_term_link( $name, $tax, $fallback ) {
	$t = get_term_by( 'name', $name, $tax );
	if ( $t && ! is_wp_error( $t ) ) {
		$l = get_term_link( $t );
		if ( ! is_wp_error( $l ) ) {
			return $l;
		}
	}
	return $fallback;
}

// 4) 修复主导航：建一个菜单并指派到 main 位置
add_action( 'init', 'fw_ensure_main_menu', 30 );
add_action( 'after_switch_theme', 'fw_ensure_main_menu' );
function fw_ensure_main_menu() {
	// wp_create_nav_menu / wp_update_nav_menu_item 位于 wp-admin 专有文件，需手动加载
	if ( ! function_exists( 'wp_create_nav_menu' ) ) {
		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
	}
	if ( ! function_exists( 'wp_get_nav_menus' ) ) {
		return;
	}
	$menus = wp_get_nav_menus( array( 'slug' => 'fw-main' ) );
	if ( empty( $menus ) ) {
		$menu_id = wp_create_nav_menu( '主导航' );
		wp_update_nav_menu_object( $menu_id, array( 'slug' => 'fw-main' ) );
		$items = array(
			array( 'title' => '首页', 'url' => home_url( '/' ) ),
			array( 'title' => '诗词', 'url' => home_url( '/poem/' ) ),
			array( 'title' => '诗', 'url' => fw_term_link( '诗', 'poem_genre', home_url( '/poem-genre/%e8%af%97/' ) ) ),
			array( 'title' => '词', 'url' => fw_term_link( '词', 'poem_genre', home_url( '/poem-genre/%e8%af%8d/' ) ) ),
			array( 'title' => '与陆游对话', 'url' => FW_LOOMA_URL ),
		);
		foreach ( $items as $it ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => $it['title'],
				'menu-item-url'    => $it['url'],
				'menu-item-status' => 'publish',
			) );
		}
	} else {
		$menu_id = $menus[0]->term_id;
	}
	// 指派到 main 位置（修复占位警告）
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['main'] ) || (int) $locations['main'] !== (int) $menu_id ) {
		$locations['main'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

// 5) 订阅每日一诗：admin-post 处理器（未登录用户亦可提交）
add_action( 'admin_post_nopriv_fw_subscribe', 'fw_handle_subscribe' );
add_action( 'admin_post_fw_subscribe', 'fw_handle_subscribe' );
function fw_handle_subscribe() {
	$redirect = wp_get_referer() ?: home_url( '/' );
	if ( empty( $_POST['fw_sub_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fw_sub_nonce'] ) ), 'fw_subscribe' ) ) {
		wp_safe_redirect( add_query_arg( 'fw_sub', 'invalid', $redirect ) );
		exit;
	}
	$email = isset( $_POST['fw_email'] ) ? sanitize_email( wp_unslash( $_POST['fw_email'] ) ) : '';
	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'fw_sub', 'invalid', $redirect ) );
		exit;
	}
	$email = strtolower( $email );
	$subs  = get_option( 'fw_subscribers', array() );
	if ( ! is_array( $subs ) ) {
		$subs = array();
	}
	if ( in_array( $email, $subs, true ) ) {
		wp_safe_redirect( add_query_arg( 'fw_sub', 'exists', $redirect ) );
		exit;
	}
	$subs[] = $email;
	// 不自动加载，避免 option 随每次请求反序列化膨胀
	update_option( 'fw_subscribers', $subs, 'no' );
	wp_safe_redirect( add_query_arg( 'fw_sub', 'ok', $redirect ) );
	exit;
}
