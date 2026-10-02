<?php 
// Theme by mobantu.com
if ( !defined( 'THEME_DIR' ) ) {
	define( 'THEME_DIR', get_template_directory() );
}
if ( !defined( 'STYLESHEET_DIR' ) ) {
	define( 'STYLESHEET_DIR', get_stylesheet_directory() );
}
if ( !defined( 'THEME_URI' ) ) {
	define( 'THEME_URI', get_template_directory_uri() );
}
if ( !defined( 'STYLESHEET_URI' ) ) {
	define( 'STYLESHEET_URI', get_stylesheet_directory_uri() );
}
define( 'THEME_VER', '9.8' );

if( is_admin() ){
	require_once THEME_DIR . '/framework/framework.php';
	if(file_exists(STYLESHEET_DIR.'/framework/options.php')){
		require_once STYLESHEET_DIR . '/framework/options.php';
	}else{
		require_once THEME_DIR . '/framework/options.php';
	}
}

$modown_option = get_option('Modown');

require_once THEME_DIR . '/inc/mobantu.php';

define( 'ERPHPDOWN_IS_ACTIVE', wp_is_erphpdown_active());

if(file_exists(STYLESHEET_DIR.'/inc/widgets.php')){
	require_once STYLESHEET_DIR . '/inc/widgets.php';
}else{
	require_once THEME_DIR . '/inc/widgets.php';
}
if(file_exists(STYLESHEET_DIR.'/inc/shortcodes.php')){
	require_once STYLESHEET_DIR . '/inc/shortcodes.php';
}else{
	require_once THEME_DIR . '/inc/shortcodes.php';
}
if(file_exists(STYLESHEET_DIR.'/inc/metabox.php')){
	require_once STYLESHEET_DIR . '/inc/metabox.php';
}else{
	require_once THEME_DIR . '/inc/metabox.php';
}
if(_MBT('oauth_qq')){
	require_once THEME_DIR . '/inc/auth/qq.php';
}
if(_MBT('oauth_weibo')){
	require_once THEME_DIR . '/inc/auth/weibo.php';
}
if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){
	require_once THEME_DIR . '/inc/auth/weixin.php';
}
if(_MBT('oauth_sms')){
	require_once THEME_DIR . '/inc/auth/sms.php';
}
require_once THEME_DIR . '/inc/plugin-activation.php';
if(file_exists(STYLESHEET_DIR.'/inc/post-type.php')){
	require_once STYLESHEET_DIR . '/inc/post-type.php';
}else{
	require_once THEME_DIR . '/inc/post-type.php';
}
if(_MBT('ticket')){
	if(file_exists(STYLESHEET_DIR.'/inc/ticket.php')){
		require_once STYLESHEET_DIR . '/inc/ticket.php';
	}else{
		require_once THEME_DIR . '/inc/ticket.php';
	}
}
if(file_exists(STYLESHEET_DIR.'/erphpdown/mobantu.php')){
	require_once STYLESHEET_DIR . '/erphpdown/mobantu.php';
}else{
	require_once THEME_DIR . '/erphpdown/mobantu.php';
}
if(_MBT('plugin_tuan') && !function_exists('erphpdown_tuan_install')){
	if(file_exists(STYLESHEET_DIR.'/inc/plugin/tuan.php')){
		require_once STYLESHEET_DIR . '/inc/plugin/tuan.php';
	}else{
		require_once THEME_DIR . '/inc/plugin/tuan.php';
	}
}
if(_MBT('plugin_loggedin')){
	if(file_exists(STYLESHEET_DIR.'/inc/plugin/loggedin.php')){
		require_once STYLESHEET_DIR . '/inc/plugin/loggedin.php';
	}else{
		require_once THEME_DIR . '/inc/plugin/loggedin.php';
	}
}
if(_MBT('plugin_draw')){
	if(file_exists(STYLESHEET_DIR.'/inc/plugin/draw.php')){
		require_once STYLESHEET_DIR . '/inc/plugin/draw.php';
	}else{
		require_once THEME_DIR . '/inc/plugin/draw.php';
	}
}
if(_MBT('plugin_sitemap')){
	if(file_exists(STYLESHEET_DIR.'/inc/plugin/sitemap.php')){
		require_once STYLESHEET_DIR . '/inc/plugin/sitemap.php';
	}else{
		require_once THEME_DIR . '/inc/plugin/sitemap.php';
	}
}
if(_MBT('plugin_fanlysubmit')){
	if(file_exists(STYLESHEET_DIR.'/inc/plugin/FanlySubmit.php')){
		require_once STYLESHEET_DIR . '/inc/plugin/FanlySubmit.php';
	}else{
		require_once THEME_DIR . '/inc/plugin/FanlySubmit.php';
	}
}
if(_MBT('plugin_cleanup')){
	if(file_exists(STYLESHEET_DIR.'/inc/plugin/wp-clean-up/wp-clean-up.php')){
		require_once STYLESHEET_DIR . '/inc/plugin/wp-clean-up/wp-clean-up.php';
	}else{
		require_once THEME_DIR . '/inc/plugin/wp-clean-up/wp-clean-up.php';
	}
}
if(_MBT('post_category_sticky')){
	require_once THEME_DIR . '/inc/plugin/sticky-category-posts.php';
}
if(_MBT('wp_collapsible_categories')){
	require_once THEME_DIR . '/inc/plugin/collapsible-categories.php';
}
if(_MBT('plugin_statistics')){
	require_once THEME_DIR . '/inc/plugin/erphpdown-addon-statistics/erphpdown-addon-statistics.php';
}
if(_MBT('plugin_submits')){
	require_once THEME_DIR . '/inc/plugin/erphp-submit-posts/erphp-submit-posts.php';
}

load_theme_textdomain( 'mobantu', get_template_directory() . '/lang' );

$post_target = _MBT('post_target')?'_blank':'';
$vip_jtext = _MBT("post_vip_jtext")?_MBT("post_vip_jtext"):'VIP';

require_once THEME_DIR . '/functions-custom.php';
//你的自定义代码请添加到functions-custom.php里

// 个人主页配置功能
if(file_exists(STYLESHEET_DIR.'/inc/personal-page-config.php')){
	require_once STYLESHEET_DIR . '/inc/personal-page-config.php';
}else{
	require_once THEME_DIR . '/inc/personal-page-config.php';
}
// 个人主页模板用辅助函数（personal_resolve_nav_url、personal_nav_default_title）
if(file_exists(STYLESHEET_DIR.'/inc/personal-page-functions.php')){
	require_once STYLESHEET_DIR . '/inc/personal-page-functions.php';
}else{
	require_once THEME_DIR . '/inc/personal-page-functions.php';
}