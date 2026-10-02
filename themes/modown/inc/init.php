<?php 
/**
 * remove actions from wp_head
 */
remove_action( 'wp_head',   'feed_links_extra', 3 ); 
remove_action( 'wp_head',   'rsd_link' ); 
remove_action( 'wp_head',   'wlwmanifest_link' ); 
remove_action( 'wp_head',   'index_rel_link' ); 
remove_action( 'wp_head',   'start_post_rel_link', 10, 0 ); 
remove_action( 'wp_head',   'wp_generator' ); 
remove_action( 'wp_head',   'rel_canonical' );
remove_action( 'wp_head',   'wp_shortlink_wp_head', 10, 0 );

if(_MBT('wp_category_remove')){
  require_once THEME_DIR . '/inc/plugin/no-category-base.php';
}

if(_MBT('bfithumb')){
  require_once THEME_DIR . '/inc/plugin/BFI_Thumb.php';
}

/**
 * 关闭自动更新
 */
if(_MBT('wp_auto_update')){
  add_filter('automatic_updater_disabled', '__return_true');
  remove_action('init', 'wp_schedule_update_checks');
  wp_clear_scheduled_hook('wp_version_check');
  wp_clear_scheduled_hook('wp_update_plugins');
  wp_clear_scheduled_hook('wp_update_themes'); 
  wp_clear_scheduled_hook('wp_maybe_auto_update'); 
  remove_action( 'admin_init', '_maybe_update_core' ); 
  remove_action( 'load-plugins.php', 'wp_update_plugins' ); 
  remove_action( 'load-update.php', 'wp_update_plugins' );
  remove_action( 'load-update-core.php', 'wp_update_plugins' );
  remove_action( 'admin_init', '_maybe_update_plugins' );
  remove_action( 'load-themes.php', 'wp_update_themes' );
  remove_action( 'load-update.php', 'wp_update_themes' );
  remove_action( 'load-update-core.php', 'wp_update_themes' );
  remove_action( 'admin_init', '_maybe_update_themes' );
}

if(_MBT('wp_classic_widgets')){
  // Disables the block editor from managing widgets in the Gutenberg plugin.
  add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
  // Disables the block editor from managing widgets.
  add_filter( 'use_widgets_block_editor', '__return_false' );
}

if(_MBT('wp_classic_editor')){
  require_once THEME_DIR . '/inc/plugin/classic-editor.php';
}

/**
 * WordPress Emoji Delete
 */
remove_action( 'admin_print_scripts','print_emoji_detection_script');
remove_action( 'admin_print_styles','print_emoji_styles');
remove_action( 'wp_head',  'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles','print_emoji_styles');
remove_filter( 'the_content_feed','wp_staticize_emoji');
remove_filter( 'comment_text_rss','wp_staticize_emoji');
remove_filter( 'wp_mail','wp_staticize_emoji_for_email');

/**
 * wp-json delete
 */
if(_MBT('wp_rest_api')){
  remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
  if( version_compare(get_bloginfo('version'), '4.7', '>=') ) {
    add_filter( 'rest_authentication_errors', function( $result ) {
        return new WP_Error( 'rest_not_available', 'What do you want?', array( 'status' => 401 ) );
    });
  }else{
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
  }
}

if(_MBT('redirect_login')){
  add_action("init", "MBT_login_redirect");
  function MBT_login_redirect(){
    if($GLOBALS['pagenow'] == 'wp-login.php'){
      if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'logout' || $_REQUEST['action'] == 'postpass')){

      }else{
        wp_safe_redirect(get_permalink(MBThemes_page("template/login.php")));
        exit;
      }
    }
  }
}

if(_MBT('hide_author_from')){
  if(!is_admin()){
    add_action( 'init', 'remove_author_from' );
    function remove_author_from() {
      if(isset($_GET['author'])) {
        wp_redirect(home_url());
        exit;
      }
    }
  }
}

function mbt_remove_global_styles(){
  wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'mbt_remove_global_styles' );

add_editor_style();
add_theme_support( 'editor-styles' );
function _mbt_block_editor_styles() {
    wp_enqueue_style( 'mbt-block-editor-style', get_template_directory_uri() . '/static/css/editor-blocks.css', array(), '20190406' );
}
add_action( 'enqueue_block_editor_assets', '_mbt_block_editor_styles' );

/**
 * open-sans delete
 */
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans', '');
}
add_action( 'init', 'remove_open_sans' );

/**
 * Disable embeds
 */
if ( !function_exists( 'disable_embeds_init' ) ) :
    function disable_embeds_init(){
        global $wp;
        $wp->public_query_vars = array_diff($wp->public_query_vars, array('embed'));
        remove_action('rest_api_init', 'wp_oembed_register_route');
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
    }
    add_action('init', 'disable_embeds_init', 9999);

    function disable_embeds_tiny_mce_plugin($plugins){
        return array_diff($plugins, array('wpembed'));
    }
    function disable_embeds_rewrites($rules){
        foreach ($rules as $rule => $rewrite) {
            if (false !== strpos($rewrite, 'embed=true')) {
                unset($rules[$rule]);
            }
        }
        return $rules;
    }
    function disable_embeds_remove_rewrite_rules(){
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_activation_hook(__FILE__, 'disable_embeds_remove_rewrite_rules');

    function disable_embeds_flush_rewrite_rules(){
        remove_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_deactivation_hook(__FILE__, 'disable_embeds_flush_rewrite_rules');
endif;

/**
 * 禁用xmlrpc
 */
if(_MBT('wp_xmlrpc')){
  add_filter('xmlrpc_enabled','__return_false');
}

/**
 * hide admin bar
 */
add_filter('show_admin_bar','hide_admin_bar');
function hide_admin_bar($flag) {
    return false;
}

//add_filter( 'wp_title', 'MBThemes_search_title', 20, 3 );
function MBThemes_search_title( $title, $sep, $seplocation) {
  if(is_search()){return '搜索结果';}
  return $title;
}

add_filter('upload_mimes','add_upload_webp');
function add_upload_webp ( $existing_mimes=array() ) {
  $existing_mimes['webp']='image/webp';
  return $existing_mimes;
}

/**
 * add theme thumbnail
 */
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}

/**
 * 禁用图片自适应尺寸
 */
function MBThemes_disable_srcset( $sources ) {
  return false;
}
add_filter( 'wp_calculate_image_srcset', 'MBThemes_disable_srcset' );

function MBThemes_gallery_defaults( $settings ) {
    $settings['galleryDefaults']['columns'] = _MBT('post_gallery_columns')?_MBT('post_gallery_columns'):4;
    $settings['galleryDefaults']['size'] = _MBT('post_gallery_size')?_MBT('post_gallery_size'):'thumbnail';
    $settings['galleryDefaults']['link'] = 'file';
    return $settings;
}
add_filter( 'media_view_settings', 'MBThemes_gallery_defaults' );

add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/**
 * get theme option         
 */
function _MBT( $name, $default = false ) {
    global $modown_option;
    if ( isset( $modown_option[$name] ) ) {
        return $modown_option[$name];
    }
    return $default;
}


add_filter('mce_buttons','MBThemes_add_next_page_button');
function MBThemes_add_next_page_button($mce_buttons) {
  $pos = array_search('wp_more',$mce_buttons,true);
  if ($pos !== false) {
    $tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
    $tmp_buttons[] = 'wp_page';
    $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
  }
  return $mce_buttons;
}

function MBThemes_del_tags($str){
  return trim(strip_tags($str));
}
add_filter('category_description', 'MBThemes_del_tags');

add_filter( 'login_headerurl', 'MBThemes_login_logo_url' );
function MBThemes_login_logo_url($url) {
  return home_url();
}

function MBThemes_login_logo_url_title() {
    return get_bloginfo("name");
}
add_filter( 'login_headertext', 'MBThemes_login_logo_url_title' );

function MBThemes_login_logo() { 
?>
    <style type="text/css">
      body{height: auto !important;}
      #login{border-radius: 16px;background: #fff;padding:30px 30px 40px !important;margin-top: 100px !important;width: 360px !important}
      #login form{border:none;padding:0;box-shadow: none;}
      #login h1 a, .login h1 a {background-image: url(<?php echo _MBT('logo_login');?>);background-size: cover;width:100px;height: 100px;}
      #login h1 a:before, .login h1 a:before {content:none;}
      #login input[type="text"], #login input[type="password"], #login input[type="email"]{border: 1px solid hsla(210,8%,51%,.15);border-radius: 5px;box-shadow: none;padding:5px 10px;}
      .login .button-primary{float: none;width: 100%;display: block; background: #00a0d2 !important;border:none !important;color: #fff;height: 50px;line-height: 50px;font-size: 18px !important;margin-left: 0 !important;margin-right: 0 !important;text-align: center;}
      .login .button-large{float: none;width: 100%;display: block;border:none !important;color: #fff;height: 50px;line-height: 50px !important;font-size: 18px !important;margin-left: 0 !important;margin-right: 0 !important;text-align: center;margin-bottom: 10px !important;}
      #login form p.forgetmenot{margin-bottom: 10px !important;}
      .login #nav{padding:0 !important;text-align: center;}
      .login #nav a{margin:0 5px;}
      .login #backtoblog, .login #reg_passmail{display: none;}
      .login #login_error, .login .message, .login .success{box-shadow: none !important;background: #f3f3f3 !important}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'MBThemes_login_logo' );

function MBThemes_filter_smilies_src($img_src, $img, $siteurl) {
    return THEME_URI . '/static/img/smilies/' . $img;
}
add_filter('smilies_src', 'MBThemes_filter_smilies_src', 1, 10);

function smilies_reset() {
    global $wpsmiliestrans, $wp_smiliessearch, $wp_version;
    if ( !get_option( 'use_smilies' ) || $wp_version < 4.2)
        return;
    $wpsmiliestrans = array(
    ':mrgreen:' => 'mrgreen.png',
    ':exclaim:' => 'exclaim.png',
    ':neutral:' => 'neutral.png',
    ':twisted:' => 'twisted.png',
      ':arrow:' => 'arrow.png',
        ':eek:' => 'eek.png',
      ':smile:' => 'smile.png',
   ':confused:' => 'confused.png',
       ':cool:' => 'cool.png',
       ':evil:' => 'evil.png',
    ':biggrin:' => 'biggrin.png',
       ':idea:' => 'idea.png',
    ':redface:' => 'redface.png',
       ':razz:' => 'razz.png',
   ':rolleyes:' => 'rolleyes.png',
       ':wink:' => 'wink.png',
        ':cry:' => 'cry.png',
        ':lol:' => 'lol.png',
        ':mad:' => 'mad.png',
   ':drooling:' => 'drooling.png',
':persevering:' => 'persevering.png',
    );
}
smilies_reset();

function do_post($url, $data) {
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
  curl_setopt ( $ch, CURLOPT_POST, TRUE );
  curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
  curl_setopt ( $ch, CURLOPT_URL, $url );
  curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  $ret = curl_exec ( $ch );
  curl_close ( $ch );
  return $ret;
}

function get_url_contents($url) {
  $ch = curl_init ();
  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt ( $ch, CURLOPT_URL, $url );
  $result = curl_exec ( $ch );
  curl_close ( $ch );
  return $result;
}

function wp_is_erphpdown_active(){
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  if(!is_plugin_active( 'erphpdown/erphpdown.php' )){
    return 0;
  }else{
    return 1;
  }

}

function wp_menu_none(){
  return '<li><a href="'.admin_url('nav-menus.php').'">'.__('请到后台 外观-菜单 设置此导航','mobantu').'</a></li>';
}


add_action( 'tgmpa_register', 'MBThemes_register_required_plugins' );
function MBThemes_register_required_plugins() {
  $plugins = array(
    array(
      'name'      => '经典编辑器',
      'slug'      => 'classic-editor',
      'required'  => false,
    ),
    array(
      'name'      => '自定义字段',
      'slug'      => 'advanced-custom-fields',
      'required'  => false,
    ),
    array(
      'name'        => 'Erphpdown',
      'slug'        => 'erphpdown',
      'is_callable' => 'erphpdod',
      'source' => '/插件请在模板兔网站购买！https://www.mobantu.com/1780.html',
      'external_url' => 'https://www.mobantu.com/1780.html',
      'required'  => true,
    ),
  );

  $config = array(
    'id'           => 'Mobantu',                
    'default_path' => '',                      
    'menu'         => 'modown-install-plugins', 
    'parent_slug'  => 'themes.php',
    'capability'   => 'edit_theme_options',
    'has_notices'  => true,
    'dismissable'  => true,
    'dismiss_msg'  => '',
    'is_automatic' => false,
    'message'      => ''
  );

  tgmpa( $plugins, $config );
}

add_action('print_media_templates', function(){
  ?>
  <script type="text/html" id="tmpl-my-custom-gallery-setting">
    <h2 style="float: left;width: 100%">预览设置</h2>
    <span class="setting">
      <label for="gallery-settings-preview" class="name">预览数</label>
      <input type="text" id="gallery-settings-preview" name="preview" data-setting="preview" style="float: left;width: 100px" />
    </span>
    <span class="setting">
      <label for="gallery-settings-hide" class="name">隐藏模式</label>
      <select id="gallery-settings-hide" class="hide" name="hide" data-setting="hide">
        <option value="0">毛玻璃</option>
        <option value="1">完全隐藏</option>
      </select>
    </span>
    <?php if(wp_is_erphpdown_active()){?>
    <span class="setting">
      <label for="gallery-settings-vip" class="name">VIP查看全部</label>
      <select id="gallery-settings-vip" class="vip" name="vip" data-setting="vip">
        <option value="0">不启用</option>
        <option value="1">启用</option>
        <option value="7">启用包月</option>
        <option value="8">启用包季</option>
        <option value="9">启用包年</option>
        <option value="10">启用终身</option>
      </select>
    </span>
    <?php }?>
    <span class="setting">
      <label for="gallery-settings-password" class="name">密码</label>
      <input type="text" id="gallery-settings-password" name="password" data-setting="password" style="float: left;width: 100px" />
    </span>
    <span class="setting">
      <label for="gallery-settings-extra_btn" class="name">额外按钮</label>
      <input type="text" id="gallery-settings-extra_btn" name="extra_btn" data-setting="extra_btn" style="float: left;width: 100px" />
    </span>
    <span class="setting">
      <label for="gallery-settings-extra_url" class="name">按钮链接</label>
      <input type="text" id="gallery-settings-extra_url" name="extra_url" data-setting="extra_url" style="float: left;width: 200px" />
    </span>
  </script>
  <script>
    jQuery(document).ready(function(){
      _.extend(wp.media.gallery.defaults, {
        preview: '',
        hide: '',
        vip: '',
        password: '',
        extra_btn: '',
        extra_url: ''
      });
      wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
        template: function(view){
          return wp.media.template('gallery-settings')(view)
               + wp.media.template('my-custom-gallery-setting')(view);
        }
      });
    });
  </script>
  <?php
});

function MBT_get_comment_list_by_user($clauses) {
  if (is_admin()) {
    global $current_user, $wpdb;
    $clauses['where'] .= " AND user_id = ".$current_user->ID;
  };
  return $clauses;
}

if(!current_user_can('edit_others_posts')) {
  add_filter('comments_clauses', 'MBT_get_comment_list_by_user');
}

add_filter('body_class','mbt_body_classes');
function mbt_body_classes($classes) {
  date_default_timezone_set('Asia/Shanghai');

  if(_MBT('nav_position') == '1'){
    $classes[] = 'nv-left';
  }

  if(_MBT('header_topbar')){
    if((wp_is_mobile() || modown_is_mobile()) && _MBT("header_topbar_wap")){

    }else{
      $classes[] = 'has-topbar';
    }
  }

  $list_column = _MBT('list_column');
  if(is_category()){
    $cat_ID = get_query_var('cat');

    $cat_list_column = get_term_meta($cat_ID,'list_column',true);
    if($cat_list_column){
      $list_column = $cat_list_column;
    }else{
      $pcat_list_column = get_term_meta(MBThemes_parent_cid($cat_ID),'list_column',true);
      if($pcat_list_column){
        $list_column = $pcat_list_column;
      }
    }
  }
  if($list_column == 'six-mini'){
    $classes[] = 'gd-mini mini-six';
  }elseif($list_column == 'five-mini'){
    $classes[] = 'gd-mini';
  }elseif($list_column == 'four-large'){
    $classes[] = 'gd-large';
  }

  if(_MBT('list_style') == 'list' || _MBT('list_style') == 'list-title'){
    $classes[] = 'gd-list';
  }else{
    if(is_category()){
      $cat_ID = get_query_var('cat');
      $style_cat_ID = $cat_ID;
      if(isset($_GET['c4']) && $_GET['c4']){
        $style_cat_ID = $_GET['c4'];
      }elseif(isset($_GET['c3']) && $_GET['c3']){
        $style_cat_ID = $_GET['c3'];
      }elseif(isset($_GET['c2']) && $_GET['c2']){
        $style_cat_ID = $_GET['c2'];
      }
      $style = get_term_meta($style_cat_ID,'style',true);
      if($style == 'list' || $style == 'list-title'){
        $classes[] = 'gd-list';
      }elseif($style == 'grid' || $style == 'grid-audio'){
      }else{
        $style = get_term_meta(MBThemes_parent_cid($style_cat_ID),'style',true);
        if($style == 'list' || $style == 'list-title'){
          $classes[] = 'gd-list';
        }
      }
    }elseif(is_tag()){
      $tag_slug = get_query_var('tag');
      $tag = get_term_by('slug',$tag_slug,'post_tag');
      if($tag){
        $style_cat_ID = $tag->term_id;
        $style = get_term_meta($style_cat_ID,'style',true);
        if($style == 'list' || $style == 'list-title'){
          $classes[] = 'gd-list';
        }elseif($style == 'grid' || $style == 'grid-audio'){
        }
      }
    }elseif(is_page_template("template/all.php") || is_page_template("template/all-vip.php") || is_page_template("template/tuan.php") || is_page_template("template/homepage2.php")){
      $style_cat_ID = 0;
      if(isset($_GET['c4']) && $_GET['c4']){
        $style_cat_ID = $_GET['c4'];
      }elseif(isset($_GET['c3']) && $_GET['c3']){
        $style_cat_ID = $_GET['c3'];
      }elseif(isset($_GET['c2']) && $_GET['c2']){
        $style_cat_ID = $_GET['c2'];
      }elseif(isset($_GET['c']) && $_GET['c']){
        $style_cat_ID = $_GET['c'];
      }
      if($style_cat_ID){
        $style = get_term_meta($style_cat_ID,'style',true);
        if($style == 'list' || $style == 'list-title'){
          $classes[] = 'gd-list';
        }elseif($style == 'grid' || $style == 'grid-audio'){
        }else{
          $style = get_term_meta(MBThemes_parent_cid($style_cat_ID),'style',true);
          if($style == 'list' || $style == 'list-title'){
            $classes[] = 'gd-list';
          }
        }
      }
    }elseif(is_tax("blogs") || (get_post_type() == 'blog' && is_archive())){
      $classes[] = 'gd-list';
    }elseif(is_tax("question_category") || (get_post_type() == 'question' && is_archive())){
      $classes[] = 'gd-list';
    }
  }
  
  if(isset($_COOKIE['mbt_theme_night'])){
    if($_COOKIE['mbt_theme_night'] == '1'){
      $classes[] = 'night';
    }
  }elseif(_MBT('theme_night_default')){
    $classes[] = 'night';
  }elseif(_MBT('theme_night_auto')){
    $time = intval(date("Hi"));
    if ($time < 730 || $time > 1930) {
      $classes[] = 'night';
    }
  }

  return $classes;
}

function mbt_comment_body_class($content){ 
  $pattern = "/(.*?)([^>]*)author-([^>]*)(.*?)/i";
  $replacement = '$1$4';
  $content = preg_replace($pattern, $replacement, $content);  
  return $content;
}
add_filter('comment_class', 'mbt_comment_body_class');
add_filter('body_class', 'mbt_comment_body_class');

function mbt_admin_enqueue_scripts( $hook ) {
    if ( 'users.php' === $hook ) {
      wp_enqueue_script( 'mbt_admin_enqueue_layer_scripts', THEME_URI.'/static/js/layer.min.js',
            false, null, true );
      wp_enqueue_script( 'mbt_admin_enqueue_users_scripts', THEME_URI.'/static/admin/users.js',
          false, null, true );
      wp_enqueue_style('mbt_admin_enqueue_layer_style', THEME_URI . '/static/css/layer.min.css', false, null, 'screen');
    }
}
add_action( 'admin_enqueue_scripts', 'mbt_admin_enqueue_scripts' );

function custom_post_actions($actions, $post) {
    $actions['id'] = 'ID：'.$post->ID;
    return $actions;
}
add_filter('post_row_actions', 'custom_post_actions', 10, 2);
add_filter('page_row_actions', 'custom_post_actions', 10, 2);

add_filter('user_row_actions', 'custom_user_actions', 10, 2);
function custom_user_actions($actions, $user) {
  /*if(_MBT('ticket')){
    $actions['ticket'] = '<a href="javascript:;" class="modown-ticket-user-do" data-uid="'.$user->ID.'" style="color:#ff5f33">私信</a>';
  }*/
  $actions['id'] = 'ID：'.$user->ID;
  return $actions;
}

function custom_category_actions($actions, $category) {
    $actions['id'] = 'ID：'.$category->term_id;
    return $actions;
}
add_filter('question_category_row_actions', 'custom_category_actions', 10, 2);
add_filter('blogs_row_actions', 'custom_category_actions', 10, 2);
add_filter('link_category_row_actions', 'custom_category_actions', 10, 2);
add_filter('category_row_actions', 'custom_category_actions', 10, 2);
add_filter("topic_row_actions", 'custom_category_actions', 10, 2);
add_filter("post_tag_row_actions", 'custom_category_actions', 10, 2);

function custom_comment_actions($actions, $comment) {
    $actions['id'] = 'ID：'.$comment->comment_ID;
    return $actions;
}
add_filter('comment_row_actions', 'custom_comment_actions', 10, 2);

class Walker_Tougao_CategoryDropdown extends Walker_CategoryDropdown {
  public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
    $post_tougao_cats = trim(_MBT('post_tougao_cats'));
    $post_tougao_cats_arr = explode(',', str_replace('，', ',', $post_tougao_cats));
    $pad = str_repeat('&nbsp;', $depth * 3);

    $cat_name = apply_filters( 'list_cats', $category->name, $category );

    if ( isset( $args['value_field'] ) && isset( $category->{$args['value_field']} ) ) {
        $value_field = $args['value_field'];
    } else {
        $value_field = 'term_id';
    }

    if(in_array($category->term_id, $post_tougao_cats_arr) || in_array(MBThemes_parent_cid($category->term_id), $post_tougao_cats_arr)){

      $output .= "\t<option class=\"level-$depth\" value=\"" . esc_attr( $category->{$value_field} ) . "\"";

      // Type-juggling causes false matches, so we force everything to a string.
      if ( (string) $category->{$value_field} === (string) $args['selected'] )
          $output .= ' selected="selected"';
       
      //$output .= ' data-uri="'.get_term_link($category).'" '; /* Custom */
       
      $output .= '>';
      $output .= $pad.$cat_name;
      if ( $args['show_count'] )
          $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
      $output .= "</option>\n";
    }
  }
}


add_filter( 'display_post_states', 'MBThemes_add_post_state', 10, 2 );
function MBThemes_add_post_state( $post_states, $post ) {
  if( get_page_template_slug($post->ID) == 'template/user.php' ) {
    $post_states[] = 'Modown User';
  }elseif( get_page_template_slug($post->ID) == 'template/vip.php' ) {
    $post_states[] = 'Modown VIP';
  }elseif( get_page_template_slug($post->ID) == 'template/login.php' ) {
    $post_states[] = 'Modown Login';
  }elseif( get_page_template_slug($post->ID) == 'template/all.php' ) {
    $post_states[] = 'Modown All Posts';
  }elseif( get_page_template_slug($post->ID) == 'template/tougao.php' ) {
    $post_states[] = 'Modown Tougao';
  }
  return $post_states;
}

if ( class_exists( 'WooCommerce', false ) ) {
  add_theme_support('woocommerce');

  add_action('woocommerce_before_main_content', 'MBThemes_woocommerce_wrapper_start', 10);
  add_action('woocommerce_after_main_content', 'MBThemes_woocommerce_wrapper_end', 10);

  function MBThemes_woocommerce_wrapper_start() {
      echo '<div class="container clearfix">';
  }

  function MBThemes_woocommerce_wrapper_end() {
      echo '</div>';
  }

  /*add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
  function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    //unset($fields['billing']['billing_phone']);
    //unset($fields['order']['order_comments']);
    //unset($fields['billing']['billing_email']);
    //unset($fields['account']['account_username']);
    //unset($fields['account']['account_password']);
    //unset($fields['account']['account_password-2']);
    return $fields;
  }*/
}

//add_action('wp_dashboard_setup', 'MBT_thread_modify_dashboard_widgets' );
function MBT_thread_modify_dashboard_widgets() {
  if(current_user_can('manage_options')){
    add_meta_box( 'pending_posts_dashboard_widget', '待审文章', 'pending_posts_dashboard_widget_function','dashboard', 'normal', 'core' );
  }
}

function pending_posts_dashboard_widget_function() {
 global $wpdb;
 $pending_posts = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_status = 'pending' and post_type='post' ORDER BY post_modified DESC");
echo '<ul>';
 foreach ($pending_posts as $pending_post){
 echo '<li><a href="'.admin_url().'post.php?post='.$pending_post->ID.'&action=edit">'.$pending_post->post_title.'</a></li>';
 }
 echo '</ul>';
}

function modown_is_mobile(){  
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
    $mobile_browser = '0';  $mobile_ua = '';
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $clientkeywords = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini','ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung','palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser','up.link', 'blazer', 'helio', 'hosin', 'huawei', 'xiaomi', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource','alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone','iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop','benq', 'haier', '^lct', '320x320', '240x320', '176x220', 'windows phone');
      if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;  
      }
    }

    if(isset($_SERVER['HTTP_USER_AGENT'])){  
      if(preg_match('/(up.browser|up.link|ucweb|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
          $mobile_browser++;  
  }
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
        $mobile_browser++;  
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
        $mobile_browser++;  
    if(isset($_SERVER['HTTP_PROFILE']))  
        $mobile_browser++;  
    if(isset($_SERVER['HTTP_USER_AGENT'])){ 
      $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
  }
    $mobile_agents = array(  
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
        'wapr','webc','winw','winw','xda','xda-' 
        );  
    if($mobile_ua){
      if(in_array($mobile_ua, $mobile_agents))  
          $mobile_browser++;  
  }

    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
        $mobile_browser++;  
    
    if(isset($_SERVER['HTTP_USER_AGENT'])){  
      if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
          $mobile_browser=0;  
  }
    
    if(isset($_SERVER['HTTP_USER_AGENT'])){ 
      if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
          $mobile_browser++;  
  }

    if($mobile_browser>0)  
        return true;  
    else
        return false;   
}

function modown_is_weixin(){ 
  if(isset($_SERVER['HTTP_USER_AGENT'])){
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
      return true;
    }  
  }
  return false;
}

function modown_is_qq(){
  if(isset($_SERVER['HTTP_USER_AGENT'])){
    if ( strpos($_SERVER['HTTP_USER_AGENT'], ' QQ') !== false ) {
      return true;
    }

    if (preg_match('/QQBrowser/i',$_SERVER['HTTP_USER_AGENT' ])){
      return true;
    }
  }

  return false;
}

function modown_is_current_theme(){
  return true;
}

function mbt_sec_to_time($seconds){  
  $time = '00:00';  
  if($seconds > 0){
   if ($seconds >3600){
       $hours =intval($seconds/3600);
       $minutes = $seconds % 3600;
       $time = $hours.":".gmstrftime('%M:%S',$minutes);
   }else{
       $time = gmstrftime('%M:%S',$seconds);
   }
  }
  return $time;  
}

if(_MBT('login')){
  add_action( 'template_redirect', 'mbt_force_login' );
  add_filter( 'rest_authentication_errors', 'mbt_force_rest_access', 99 );
}

function mbt_force_login() {
  if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    return;
  }

  if( !is_user_logged_in()) {
    $schema = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';
    $url = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if ( preg_replace( '/\?.*/', '', $url ) !== preg_replace( '/\?.*/', '', wp_login_url() ) && preg_replace( '/\?.*/', '', $url ) !== preg_replace( '/\?.*/', '', get_permalink(MBThemes_page("template/login.php")) ) ) {
      $redirect_url = apply_filters( 'mbt_force_login', $url );
      nocache_headers();
      wp_safe_redirect(add_query_arg('redirect_to',$url,get_permalink(MBThemes_page("template/login.php"))), 302);
      exit;
    }
  }
}

function mbt_force_rest_access( $result ) {
  if ( null === $result && ! is_user_logged_in() ) {
    return new WP_Error( 'rest_unauthorized', __('请先登录','mobantu'), array( 'status' => rest_authorization_required_code() ) );
  }
  return $result;
}

function mbt_get_ip(){
    if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  elseif (@$_SERVER["HTTP_CLIENT_IP"])
    $ip = $_SERVER["HTTP_CLIENT_IP"];
  elseif (@$_SERVER["REMOTE_ADDR"])
    $ip = $_SERVER["REMOTE_ADDR"];
  elseif (@getenv("HTTP_X_FORWARDED_FOR"))
    $ip = getenv("HTTP_X_FORWARDED_FOR");
  elseif (@getenv("HTTP_CLIENT_IP"))
    $ip = getenv("HTTP_CLIENT_IP");
  elseif (@getenv("REMOTE_ADDR"))
    $ip = getenv("REMOTE_ADDR");
  else
    $ip = "";
  return $ip;
}

function mbt_object_to_array($obj){
    $_arr=is_object($obj)?get_object_vars($obj):$obj;
    $arr = null;
    foreach($_arr as $key=>$val){
        $val=(is_array($val))||is_object($val)?$this->object_to_array($val):$val;
        $arr[$key]=$val;
    }
    return $arr;
}

function mbt_compare_field($elem1, $elem2) {
  return strcmp($elem1['menu_order'], $elem2['menu_order']);
}

function mbt_search_captcha( $query, $error = true ) {
  if (is_search() && !is_admin()) {
    if(!isset($_COOKIE['esc_search_captcha'])){
      $query->is_search = false;
      $query->query_vars['s'] = false;
      $query->query['s'] = false;
      if ( $error == true ){
        //$query->is_404 = true;
        if(isset($_POST['result'])){
          if($_POST['result'] == $_COOKIE['esc_search_result']){
            $_COOKIE['esc_search_captcha'] = 1;
            setcookie('esc_search_captcha',1,0,'/');
            echo '<script>location.reload();</script>';
          }
        }

        $num1 = rand(10,50);
        $num2 = rand(10,50);
        $result = $num1+$num2;
        $_COOKIE['esc_search_result'] = $result;
        setcookie('esc_search_result',urldecode($result),0,'/');
?>
  <html>
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
      <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
      <title><?php _e('人机验证','mobantu');?></title>
      <style>
        body{color: #333;text-align: center;font-size: 16px;background: #f9f9f9;}
        *{box-sizing: border-box;}
        .erphp-search-captcha{position: relative;margin: 0 auto;top: 50%;transform: translateY(-50%);width: 95%;max-width: 400px;width: 100%;padding: 60px 20px;background: #fff;text-align: center;border-radius: 5px;}
        .erphp-search-captcha img{height: 100px;width: auto;display: inline-block;margin-bottom: 40px;}
        .erphp-search-captcha form{margin: 0 0 80px;font-size: 18px;}
        .erphp-search-captcha input{border: none;border-bottom: 1px solid #666;width: 50px;text-align: center;font-size: 18px;}
        .erphp-search-captcha input:focus{outline: none;}
        .erphp-search-captcha button{border: none;background: transparent;color: #ff5f33;cursor: pointer;font-size: 18px;}
        .erphp-search-captcha button:focus{outline: none;}
        .erphp-search-captcha a{color: #000;font-size: 13px;text-decoration: none;}
      </style>
    </head>
    <body>
      <div class="erphp-search-captcha">
          <a href="<?php echo home_url();?>"><img src="<?php echo _MBT('logo');?>"></a>
          <form action="" method="post"><?php echo $num1;?> + <?php echo $num2;?> = <input type="text" name="result" required /> <button type="submit"><?php _e('验证','mobantu');?></button></form>
          <a href="<?php echo home_url();?>"><?php _e('返回首页','mobantu');?></a>
      </div>
    </body>
  </html>
<?php
        exit;
      }
    }
  }
}
if(_MBT('search_captcha')){
  add_action( 'parse_query', 'mbt_search_captcha' );
}

if(_MBT('maintenance')){
  add_filter('wp', 'mbt_jump_maintenance');
}
function mbt_jump_maintenance(){
  if(!is_admin() && !current_user_can('administrator') && !is_page_template("template/login.php")){
    echo '<!DOCTYPE HTML>
        <html>
        <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
          <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/><title>';
          wp_title( _MBT('delimiter','-'), true, 'right' );
          echo '</title>';
          MBThemes_keywords();MBThemes_description();
          echo '<style>p{margin:0 0 20px}</style></head><body style="margin:10px 0;padding:0"><div style="margin:100px auto 30px;text-align:center;font-size:80px;">'.__('网站维护中','mobantu').'</div><div style="margin:0 auto 50px;text-align:center;font-size:40px;">'.(_MBT('maintenance_tips')?wpautop(_MBT('maintenance_tips')):__('稍后就会恢复正常，请耐心等待~','mobantu')).'</div></body></html>';
      exit;
  }
}

if(_MBT('tencent_block')){
  add_filter('wp', 'mbt_jump_browser');
}
function mbt_jump_browser(){
  if(!is_admin()){
    if(modown_is_qq() || modown_is_weixin()){
      echo '<!DOCTYPE HTML>
        <html>
        <head>
          <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
          <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/><title>'.__('友情提示','mobantu').'</title></head><body style="margin:10px 0;padding:0">';
      if(wp_is_mobile()){
        echo '<img src="'.get_bloginfo('template_url').'/static/img/browser.gif" style="width:100%;height:auto"/>';
        echo '<div style="margin:100px auto 0;text-align:center;font-size:18px;padding:0 30px;">'.__('请点击右上角的选项图标（...），选择自带默认浏览器打开','mobantu').'</div>';
      }else{
        echo '<div style="margin:100px auto 0;text-align:center;font-size:18px;padding:0 30px;">'.__('当前浏览器不支持访问，请用其他浏览器打开','mobantu').'</div>';
      }
      echo '</body></html>';
      exit;
    }
  }
}

if(_MBT('smtp')){
  add_action('phpmailer_init', 'MBT_wp_smtp');
  add_filter( 'wp_mail_from', 'MBT_mail_from_account' );
  add_filter( 'wp_mail_from_name', 'MBT_mail_from_name' );
}
function MBT_wp_smtp( $phpmailer ) {
 $phpmailer->IsSMTP();
 $phpmailer->SMTPAuth = true;//启用SMTPAuth服务
 $phpmailer->Port = _MBT('smtp_port');//MTP邮件发送端口，这个和下面的对应，如果这里填写25，则下面为空白
 $phpmailer->SMTPSecure = _MBT('smtp_ssl');//是否验证 ssl，这个和上面的对应，如果不填写，则上面的端口须为25
 $phpmailer->Host = _MBT('smtp_server');//邮箱的SMTP服务器地址，如果是QQ的则为：smtp.exmail.qq.com
 $phpmailer->Username = _MBT('smtp_account');//你的邮箱地址
 $phpmailer->Password = _MBT('smtp_pass');//你的邮箱登录密码
}

function MBT_mail_from_account( $original_email_address ) {
  return _MBT('smtp_account');
}

function MBT_mail_from_name( $from_name ) {
  return _MBT('smtp_from');
}

function MBT_erphp_vip_cat(){
  return 1;
}

add_action('admin_menu', 'modown_import_menu');
function modown_import_menu() {
  add_management_page('Modown导入导出', 'Modown导入导出', 'edit_themes', 'modown-import', 'MBThemes_import_page');  
}

function MBThemes_import_page(){
?>
  <div class="wrap">
    <div id="icon-tools" class="icon32"><br /></div>
    <h2>导入Modown设置</h2>
    <?php
      if (isset($_FILES['import']) && check_admin_referer('modown-import')) {
        if ($_FILES['import']['error'] > 0) 
          wp_die("Error happens");    
        else {
          $file_name = $_FILES['import']['name'];
          $file_ext = strtolower(end(explode(".", $file_name)));
          $file_size = $_FILES['import']['size'];
          if (($file_ext == "json") && ($file_size < 500000)) {
            $encode_options = file_get_contents($_FILES['import']['tmp_name']);
            $options = json_decode( base64_decode($encode_options), true);
            
            if(is_array($options)){
              foreach($options as $key => $value){
                update_option($key, $value);
              }
              echo "<div class='updated'><p>主题设置或小工具设置导入成功！</p></div>";
            }else{
              echo "<div class='error'><p>此json文件不是Modown主题的导入文件，请联系模板兔获取！</p></div>";
            }
          }else {
            echo "<div class='error'><p>文件类型无效或文件太大！</p></div>";
          }
        }
      }
    ?>
    <p>您好！请选择您要导入的 json 文件，我们将把其中的主题设置、小工具导入到您的站点中。</p>
    <p>选择一个 .json 文件，然后点击上传进行导入。</p>
    <form method='post' enctype='multipart/form-data'>
      <p><label for="import">从您的计算机上选择一个文件：</label> (最大大小：0.5MB) <input type='file' name='import' /></p>
      <p><input type='submit' name='submit' class="button" value='上传文件并导入'/></p>
      <?php wp_nonce_field('modown-import'); ?>
    </form>
    <div id="icon-tools" class="icon32"><br /></div>
    <h2>导出Modown设置</h2>
    <p>在您点击下面的按钮后，WordPress会创建一个json文件，供您保存到计算机中。</p>
    <p>保存完下载的文件后，便可以在其它WordPress站点中进行内容导入。</p>
    <h3>选择导出的内容</h3>
    <form method='post' action='<?php echo THEME_URI;?>/inc/includes/export.php'>
      <p><label><input type="radio" name="content" value="all" checked="checked"> 所有内容</label></p>
      <p class="description">选择此项，则将包含Modown主题设置和小工具设置。</p>
      <p><label><input type="radio" name="content" value="theme"> 主题设置</label></p>
      <p><label><input type="radio" name="content" value="widget"> 小工具设置</label></p>          
      <p class="submit">
        <?php wp_nonce_field('ie-export'); ?>
        <input type='submit' name='export' class='button' value='下载导出的文件'/>
      </p>
    </form>
  </div>
<?php 
}

function _mbt_substr_cut($user_name) {
  if ($user_name) {
    $strlen = mb_strlen($user_name, 'utf-8');
    $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr = mb_substr($user_name, -1, 1, 'utf-8');
    
    if ($strlen == 1) {
        return $firstStr; 
    } elseif ($strlen > 1) {
        $maskedLength = $strlen - 2;
        return $firstStr . str_repeat('*', $maskedLength) . $lastStr;
    }
  }
  return '';
}

function _mbt_search_url_rewrite() {
  if ( is_search() && ! empty( $_GET['s'] ) ) {
    $url = home_url( "/search/" ) . urldecode( get_query_var( 's' ) );
    if(isset($_GET['cat'])){
      $url = add_query_arg('cat',$_GET['cat'],$url);
    }
    if(isset($_GET['post_type'])){
      $url = add_query_arg('post_type',$_GET['post_type'],$url);
    }
    wp_redirect( $url );
    exit();
  }
}
if(_MBT('search_rewrite')){
  add_action( 'template_redirect', '_mbt_search_url_rewrite' );
}


function mbt_pagination($total_count, $number_per_page=15){

  $current_page = isset($_GET['paged'])?$_GET['paged']:1;

  if(isset($_GET['paged'])){
    unset($_GET['paged']);
  }

  $base_url = add_query_arg($_GET,admin_url('admin.php'));

  $total_pages  = ceil($total_count/$number_per_page);

  $first_page_url = $base_url.'&amp;paged=1';
  $last_page_url  = $base_url.'&amp;paged='.$total_pages;
  
  if($current_page > 1 && $current_page < $total_pages){
    $prev_page    = $current_page-1;
    $prev_page_url  = $base_url.'&amp;paged='.$prev_page;

    $next_page    = $current_page+1;
    $next_page_url  = $base_url.'&amp;paged='.$next_page;
  }elseif($current_page == 1){
    $prev_page_url  = '#';
    $first_page_url = '#';
    if($total_pages > 1){
      $next_page    = $current_page+1;
      $next_page_url  = $base_url.'&amp;paged='.$next_page;
    }else{
      $next_page_url  = '#';
    }
  }elseif($current_page == $total_pages){
    $prev_page    = $current_page-1;
    $prev_page_url  = $base_url.'&amp;paged='.$prev_page;
    $next_page_url  = '#';
    $last_page_url  = '#';
  }
  ?>
  <div class="tablenav bottom">
    <div class="tablenav-pages">
      <span class="displaying-num">每页 <?php echo $number_per_page;?> 共 <?php echo $total_count;?></span>
      <span class="pagination-links">
        <a class="first-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
        <a class="prev-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
        <span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
        <a class="next-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
        <a class="last-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
      </span>
    </div>
    <br class="clear">
  </div>
  <?php
}