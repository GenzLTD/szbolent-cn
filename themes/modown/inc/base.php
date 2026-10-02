<?php 
/**
* MOBANTU THEME INIT
*/
add_action('after_switch_theme', 'MBTheme_active_theme');
function MBTheme_active_theme($oldthemename){
    global $pagenow, $wpdb;

    require_once get_template_directory() . '/inc/modown-schema.php';
    modown_schema_install_users();

    $update1="ALTER TABLE `".$wpdb->users."` modify column user_login varchar(250), modify column user_nicename varchar(250)";
    $wpdb->query($update1);

    $update2="update `".$wpdb->comments."` set comment_type='comment' where comment_type='anspress' and comment_approved=1";
    $wpdb->query($update2);

    modown_schema_install();

    if( get_option('thumbnail_size_w') < 285 ){
        update_option('thumbnail_size_w', 285);
        update_option('thumbnail_size_h', 180);
    }
    update_option('thumbnail_crop', 1);

    $init_pages = array(
        'template/user.php' => array( __('个人中心','mobantu'), 'user', '' ),
        'template/login.php' => array( __('登录','mobantu'), 'login', '' ),
        'template/vip.php' => array( 'VIP', 'vip', '' ),
        'template/tougao.php' => array( __('投稿','mobantu'), 'tougao', '' ),
        'template/ask.php' => array( __('提问','mobantu'), 'ask', '' ),
        'template/all.php' => array( __('最新发布','mobantu'), 'all', '' ),
        'template/homepage1.php' => array( __('首页模板一','mobantu'), 'homepage1', '[mocat new="1" title="最新发布" link="'.home_url('/all').'"]' )
    );
    foreach ($init_pages as $template => $item) {
        $one_page = array(
          'post_title'  => $item[0],
          'post_name'   => $item[1],
          'post_status' => 'publish',
          'post_type'   => 'page',
          'post_author' => 1,
          'post_content' => $item[2]
        );
        $one_page_check = get_page_by_title( $item[0] );
        if(!isset($one_page_check->ID)){
            $one_page_id = wp_insert_post($one_page);
            update_post_meta($one_page_id, '_wp_page_template', $template);
        }
    }

    if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
        wp_redirect( admin_url( "themes.php?page=options-framework" ) );
        exit;
    }
}


function custom_toolbar_link($wp_admin_bar) {
  if(current_user_can('administrator')){
    $args = array('id' => 'themeset','title' => 'Modown主题设置', 'href' => admin_url("themes.php?page=options-framework"),'meta' => array('class' => 'mobantu-modown-bar', 'title' => 'Modown主题设置'));
    $wp_admin_bar->add_node($args);
  }
}
add_action('admin_bar_menu', 'custom_toolbar_link', 999);

function MBThemes_scripts() {
    global $erphpdown_version;
    $static_url = get_bloginfo('template_url');
    wp_dequeue_style( 'erphpdown' );
    wp_dequeue_style( 'wp-block-library' ); 
    if(_MBT('theme_iconfont_out')){
      wp_enqueue_style('dripicons', '//cdn.staticfile.net/dripicons/2.0.0/webfont.min.css', array(), '2.0.0', 'screen');
      wp_enqueue_style('modown-font', $static_url .'/static/css/iconfont.css', array(), THEME_VER, 'screen');
    }else{
      wp_enqueue_style('modown-font', $static_url .'/static/css/fonts.css', array(), THEME_VER, 'screen');
    }
    wp_enqueue_style('modown-base', $static_url .'/static/css/base.css', array(), THEME_VER, 'screen');
    if(is_page_template("template/user.php")){
      wp_enqueue_style('modown-user', $static_url .'/static/css/user.css', array(), THEME_VER, 'screen');
    }
    if(THEME_URI != STYLESHEET_URI){
      wp_enqueue_style( 'modown-style', get_stylesheet_uri(), array(), THEME_VER, 'screen' );
    }
    wp_enqueue_script("jquery");
    wp_enqueue_script( 'modown-lib', $static_url . '/static/js/lib.js', false, THEME_VER, true);
    if(is_singular() && !is_front_page()){
      wp_enqueue_script( 'modown-fancybox', $static_url . '/static/js/fancybox.min.js', false, '3.3.5', true);
    }
    if(!ERPHPDOWN_IS_ACTIVE){
      wp_enqueue_script( 'modown-layer', $static_url . '/static/js/layer.min.js', false, THEME_VER, true);
    }
    if(file_exists(STYLESHEET_DIR.'/static/js/base.js')){
      wp_enqueue_script( 'modown-base', STYLESHEET_URI . '/static/js/base.js', false, THEME_VER, true);
    }else{
      wp_enqueue_script( 'modown-base', $static_url . '/static/js/base.js', false, THEME_VER, true);
    }
}
add_action('wp_enqueue_scripts', 'MBThemes_scripts', 30, 1);

global $erphpdown_version;
if($erphpdown_version >= 15.0) add_filter('erphpdown_content_show', 'erphpdown_default_show', 10, 2);
function erphpdown_default_show($content, $content2){
  if(is_single() || is_page() || (is_singular() && (get_post_type() == 'blog' || get_post_type() == 'question'))){
    return $content2;
  }else{
    return $content;
  }
}

if (function_exists('register_nav_menus')){
    register_nav_menus( array(
        'main' => '主导航',
        'page' => '菜单页面导航',
        'cat' => '分类导航(仅支持一级菜单)',
        'topbar' => 'TopBar(仅支持一级菜单)'
    ));
}

if (function_exists('register_sidebar')){
    register_sidebar(array(
        'name'          => '首页小工具版',
        'id'            => 'widget_index_cats',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name'          => '文章侧栏',
        'id'            => 'widget_single',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name'          => '文章侧栏购买框上',
        'id'            => 'widget_single_above',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name'          => '分类（博客List样式）侧栏',
        'id'            => 'widget_archive',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name'          => '页面侧栏',
        'id'            => 'widget_page',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    
    register_sidebar(array(
        'name'          => '专题侧栏',
        'id'            => 'widget_topic',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
        'name'          => '博客侧栏',
        'id'            => 'widget_blog',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    register_sidebar(array(
      'name'          => '问答侧栏',
      'id'            => 'widget_question',
      'before_widget' => '<div class="widget %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3>',
      'after_title'   => '</h3>'
    ));

    if(function_exists('QAPress_scripts')){
        register_sidebar(array(
          'name'          => '问答侧栏',
          'id'            => 'widget_qa_post',
          'before_widget' => '<div class="widget %2$s">',
          'after_widget'  => '</div>',
          'before_title'  => '<h3>',
          'after_title'   => '</h3>'
        ));
    }
    register_sidebar(array(
        'name'          => '首页（博客List样式）侧栏',
        'id'            => 'widget_index',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
    
    
    register_sidebar(array(
        'name'          => '底部小工具',
        'id'            => 'widget_bottom',
        'before_widget' => '<div class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
}

if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
  $current_user = wp_get_current_user();
  if($current_user->ID){
    if(_MBT('admin_capability')){
      if(!current_user_can(_MBT('admin_capability'))) {
        wp_safe_redirect( get_permalink(MBThemes_page('template/user.php')) );
        exit();
      }
    }else{
      if(!current_user_can('delete_others_pages')) {//默认只有编辑以上才能进后台
        wp_safe_redirect( get_permalink(MBThemes_page('template/user.php')) );
        exit();
      }
    }
  }else{
    if(!class_exists('\WPS\WPS_Hide_Login\Plugin')){
      wp_safe_redirect( wp_login_url() );
      exit();
    }
  }
}

function MBThemes_thumbnail($width="285", $height="180", $thumb=1){
  global $post;
  $dir = get_bloginfo('template_directory');

  if(_MBT('list_column') == 'four-large'){
    $width="320";
    $height="200";
    if($thumb && _MBT('timthumb_height') && _MBT('timthumb_height') != "200"){
      $height = _MBT('timthumb_height');
    }
  }else{
    if($thumb && _MBT('timthumb_height') && _MBT('timthumb_height') != "180"){
      $height = _MBT('timthumb_height');
    }
  }

  if($thumb){
    $category = MBThemes_youngest_category();
    if($category){
      $cat_ID = $category->term_id;
      $timthumb_height_cat = get_term_meta($cat_ID,'timthumb_height',true);
      if($timthumb_height_cat){
        $height = $timthumb_height_cat;
      }
    }
  }

  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    $src = $thumbnail_ext_url;
    $thumb_low = strtolower($src);
    if (strpos($thumb_low, '.gif') === false) {
      if(_MBT('timthumb_cdn') && !MBTheme_waterfall()){
        $src = $src._MBT('timthumb_cdn_after');
      }elseif(_MBT('bfithumb')){
        if(MBTheme_waterfall())
          $src = bfi_thumb( $src, array("width"=>$width) );
        else
          $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
      }elseif(_MBT('timthumb')){
        if(MBTheme_waterfall())
          $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
        else
          $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
      }
    }
  }else{
    if( has_post_thumbnail() ){
      if(_MBT('timthumb_cdn') && !MBTheme_waterfall()){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          $src = $src._MBT('timthumb_cdn_after');
        }
      }elseif(_MBT('bfithumb')){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if(MBTheme_waterfall())
            $src = bfi_thumb( $src, array("width"=>$width) );
          else
            $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
        }
      }elseif(_MBT('timthumb')){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if(MBTheme_waterfall())
            $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
          else
            $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
        }
      }else{
        $thumbnail_size = _MBT('thumbnail_size')?_MBT('thumbnail_size'):'full';
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),$thumbnail_size);
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
      }
      if(!$src){
        	ob_start();
		      ob_end_clean();
          preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
		      $cnt = count( $matches );
		      if($cnt>0){
		        $src = $matches[0][1];
		        $thumb_low = strtolower($src);
		        if (strpos($thumb_low, '.gif') === false) {
              if(_MBT('timthumb_cdn') && !MBTheme_waterfall()){
                $src = $src._MBT('timthumb_cdn_after');
              }elseif(_MBT('bfithumb')){
                if(MBTheme_waterfall())
                  $src = bfi_thumb( $src, array("width"=>$width) );
                else
                  $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
              }elseif(_MBT('timthumb')){
                if(MBTheme_waterfall())
                  $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
                else
                  $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
              }
		        }
		      }else{
		        $src = _MBT('thumbnail_loading')?_MBT('thumbnail_loading'):"{$dir}/static/img/thumbnail.png";
		        if(_MBT('thumbnail_default'))
		          $src = _MBT('thumbnail_default');
		      }
        }
    }else{
      ob_start();
      ob_end_clean();
      preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        $src = $matches[0][1];
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if(_MBT('timthumb_cdn') && !MBTheme_waterfall()){
            $src = $src._MBT('timthumb_cdn_after');
          }elseif(_MBT('bfithumb')){
            if(MBTheme_waterfall())
              $src = bfi_thumb( $src, array("width"=>$width) );
            else
              $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
          }elseif(_MBT('timthumb')){
            if(MBTheme_waterfall())
              $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
            else
              $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
          }
        }
      }else{
        $src = _MBT('thumbnail_loading')?_MBT('thumbnail_loading'):"{$dir}/static/img/thumbnail.png";
        if(_MBT('thumbnail_default'))
          $src = _MBT('thumbnail_default');
      }
    }
  }
  return $src;
}

function MBThemes_thumbnail_post($post_id, $water=0, $width="285", $height="180"){
  $post = get_post($post_id);
  $dir = get_bloginfo('template_directory');
  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    $src = $thumbnail_ext_url;
    $thumb_low = strtolower($src);
    if (strpos($thumb_low, '.gif') === false) {
      if(_MBT('timthumb_cdn')){
        $src = $src._MBT('timthumb_cdn_after');
      }elseif(_MBT('bfithumb')){
        if($water)
          $src = bfi_thumb( $src, array("width"=>$width) );
        else
          $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
      }elseif(_MBT('timthumb')){
        if($water)
          $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
        else
          $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
      }
    }
  }else{
    if( has_post_thumbnail($post_id) ){
      if(_MBT('timthumb_cdn')){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          $src = $src._MBT('timthumb_cdn_after');
        }
      }elseif(_MBT('bfithumb')){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if($water)
            $src = bfi_thumb( $src, array("width"=>$width) );
          else
            $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
        }
      }elseif(_MBT('timthumb')){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if($water)
            $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
          else
            $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
        }
      }else{
        $thumbnail_size = _MBT('thumbnail_size')?_MBT('thumbnail_size'):'full';
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),$thumbnail_size);
        $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
      }
      if(!$src){
          ob_start();
          ob_end_clean();
          preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
          $cnt = count( $matches );
          if($cnt>0){
            $src = $matches[0][1];
            $thumb_low = strtolower($src);
            if (strpos($thumb_low, '.gif') === false) {
              if(_MBT('timthumb_cdn')){
                $src = $src._MBT('timthumb_cdn_after');
              }elseif(_MBT('bfithumb')){
                if($water)
                  $src = bfi_thumb( $src, array("width"=>$width) );
                else
                  $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
              }elseif(_MBT('timthumb')){
                if($water)
                  $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
                else
                  $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
              }
            }
          }else{
            $src = _MBT('thumbnail_loading')?_MBT('thumbnail_loading'):"{$dir}/static/img/thumbnail.png";
            if(_MBT('thumbnail_default'))
              $src = _MBT('thumbnail_default');
          }
        }
    }else{
      ob_start();
      ob_end_clean();
      preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        $src = $matches[0][1];
        $thumb_low = strtolower($src);
        if (strpos($thumb_low, '.gif') === false) {
          if(_MBT('timthumb_cdn')){
            $src = $src._MBT('timthumb_cdn_after');
          }elseif(_MBT('bfithumb')){
            if($water)
              $src = bfi_thumb( $src, array("width"=>$width) );
            else
              $src = bfi_thumb( $src, array("width"=>$width, "height"=>$height, "crop" => true) );
          }elseif(_MBT('timthumb')){
            if($water)
              $src = "$dir/timthumb.php?src=$src&w=$width&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
            else
              $src = "$dir/timthumb.php?src=$src&w=$width&h=$height&zc=1&q=95&a=".(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
          }
        }
      }else{
        $src = _MBT('thumbnail_loading')?_MBT('thumbnail_loading'):"{$dir}/static/img/thumbnail.png";
        if(_MBT('thumbnail_default'))
          $src = _MBT('thumbnail_default');
      }
    }
  }
  return $src;
}

function MBThemes_thumbnail_full(){
  global $post;
  $dir = get_bloginfo('template_directory');

  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    $src = $thumbnail_ext_url;
  }else{
    if( has_post_thumbnail() ){
      $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
      $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
      if(!$src){
        ob_start();
	      ob_end_clean();
	      preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
	      $cnt = count( $matches );
	      if($cnt>0){
	        $src = $matches[0][1];
	      }else{
	        $src = _MBT('thumbnail_loading')?_MBT('thumbnail_loading'):"{$dir}/static/img/thumbnail.png";
	        if(_MBT('thumbnail_default'))
	          $src = _MBT('thumbnail_default');
	      }
      }
    }else{
      ob_start();
      ob_end_clean();
      preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        $src = $matches[0][1];
      }else{
        $src = _MBT('thumbnail_loading')?_MBT('thumbnail_loading'):"{$dir}/static/img/thumbnail.png";
        if(_MBT('thumbnail_default'))
          $src = _MBT('thumbnail_default');
      }
    }
  }
  return $src;
}

function MBThemes_thumbnail_share($post){
  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  $src = '';
  if($thumbnail_ext_url){
    $src = $thumbnail_ext_url;
  }else{
    if( has_post_thumbnail($post) ){
      $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
      $src = isset($thumbnail_src[0]) ? $thumbnail_src[0] : '';
      if(!$src){
      	ob_start();
	      ob_end_clean();
	      preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
	      $cnt = count( $matches );
	      if($cnt>0){
	        $src = $matches[0][1];
	      }
      }
    }else{
      ob_start();
      ob_end_clean();
      preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        $src = $matches[0][1];
      }
    }
  }
  return $src;
}

function MBThemes_thumbnail_has(){
  global $post;
  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    return true;
  }else{
    if( has_post_thumbnail() ){
      return true;
    }else{
      ob_start();
      ob_end_clean();
      preg_match_all('/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/i', $post->post_content, $matches, PREG_SET_ORDER);
      $cnt = count( $matches );
      if($cnt>0){
        return true;
      }
    }
  }
  return false;
}

function MBThemes_feature_has(){
  global $post;
  $thumbnail_ext_url = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
  if($thumbnail_ext_url){
    return true;
  }else{
    if( has_post_thumbnail() ){
      return true;
    }
  }
  return false;
}

function MBThemes_paging() {
  $p = 2;
  global $wp_query, $paged;
  $max_page = $wp_query->max_num_pages;
  if ( $max_page > 1 ){
    echo '<div class="pagination"><ul>';
    if ( empty( $paged ) ) $paged = 1;
    echo '<li class="prev-page">'; previous_posts_link(__('上一页','mobantu')); echo '</li>';

    if ( $paged > $p + 1 ) p_link( 1 );
    if ( $paged > $p + 2 ) echo "<li><span>···</span></li>";
    for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { 
      if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : p_link( $i );
    }
    if ( $paged < $max_page - $p - 1 ) echo "<li><span> ... </span></li>";
    if ( $paged < $max_page - $p ) p_link( $max_page );
    echo '<li class="next-page">'; next_posts_link(__('下一页','mobantu')); echo '</li>';
    if($max_page>3){
      echo '<li><input type="number" id="jump_page" min="1" step="1" /><a href="javascript:;" class="page-jump">'.__('跳转','mobantu').'</a></li>';
    }
    echo '</ul></div>';
  }
}

function MBThemes_custom_paging($paged,$max_page) {
  $p = 2;
  if ( $max_page > 1 ){
    echo '<div class="pagination"><ul>';
    if ( empty( $paged ) ) $paged = 1;
    if ( $paged > 1 ) p_link( $paged-1, __('上一页','mobantu'), 'prev-page' );
    if ( $paged > $p + 1 ) p_link( 1 );
    if ( $paged > $p + 2 ) echo "<li><span>···</span></li>";
    for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { 
      if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : p_link( $i );
    }
    if ( $paged < $max_page - $p - 1 ) echo "<li><span> ... </span></li>";
    if ( $paged < $max_page - $p ) p_link( $max_page );
    if ( $paged < $max_page ) p_link( $paged+1, __('下一页','mobantu'), 'next-page' );
    echo '</ul></div>';
  }
}

function p_link( $i, $title = '', $class='' ) {
  if ( $title == '' ) $title = "{$i}";
  echo "<li class='".$class."'><a href='", esc_html( get_pagenum_link( $i ) ), "'>".$title."</a></li>";
}

function p_curr_link( $i) {
  echo '<li><span class="page-numbers current">'.$i.'</span></li>';
}

function MBThemes_post_date(){
  global $post;
  //get_gmt_from_date
  if(_MBT('post_date_update')){
    return get_the_modified_time('Y-m-d G:i:s');
  }else{
    return get_the_time('Y-m-d G:i:s');
  }
}

if(!function_exists("MBThemes_timeago2")){
  function MBThemes_timeago2( $ptime ) {
    date_default_timezone_set('Asia/Shanghai');
    
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return __('刚刚','mobantu');
    if($etime > 30 * 24 * 60 * 60) return date('Y-m-d', $ptime);
    $interval = array (
      12 * 30 * 24 * 60 * 60  =>  __('年前','mobantu'),
      30 * 24 * 60 * 60       =>  __('月前','mobantu'),
      7 * 24 * 60 * 60        =>  __('周前','mobantu'),
      24 * 60 * 60            =>  __('天前','mobantu'),
      60 * 60                 =>  __('小时前','mobantu'),
      60                      =>  __('分钟前','mobantu'),
      1                       =>  __('秒前','mobantu')
    );
    foreach ($interval as $secs => $str) {
      $d = $etime / $secs;
      if ($d >= 1) {
        $r = round($d);
        return $r . $str;
      }
    };
  }
}

function MBThemes_timeago( $ptime ) {
  date_default_timezone_set('Asia/Shanghai');
  if(_MBT('post_date_format')){
    return date("Y-m-d",strtotime($ptime));
  }else{
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return __('刚刚','mobantu');
    if($etime > 30 * 24 * 60 * 60) return date('Y-m-d', $ptime);
    $interval = array (
      12 * 30 * 24 * 60 * 60  =>  __('年前','mobantu'),
      30 * 24 * 60 * 60       =>  __('月前','mobantu'),
      7 * 24 * 60 * 60        =>  __('周前','mobantu'),
      24 * 60 * 60            =>  __('天前','mobantu'),
      60 * 60                 =>  __('小时前','mobantu'),
      60                      =>  __('分钟前','mobantu'),
      1                       =>  __('秒前','mobantu')
    );
    foreach ($interval as $secs => $str) {
      $d = $etime / $secs;
      if ($d >= 1) {
        $r = round($d);
        return $r . $str;
      }
    };
  }
}

if ( ! function_exists( 'MBThemes_views' ) ) :
  function MBThemes_record_visitors(){
    if (is_singular()) {
      global $post;
      $post_ID = $post->ID;
      if($post_ID) {
        $post_views = (int)get_post_meta($post_ID, 'views', true);
        if(!update_post_meta($post_ID, 'views', ($post_views+1))){
          add_post_meta($post_ID, 'views', 1, true);
        }
      }
    }
  }
  add_action('wp_head', 'MBThemes_record_visitors');  

  function MBThemes_views($echo = true, $after=''){
    global $post;
    $post_ID = $post->ID;
    $views = MBThemes_num2tring((int)get_post_meta($post_ID, 'views', true));
    if($echo)
      echo $views.$after;
    else
      return $views.$after;
  }
endif;

add_filter( 'wp_title', 'MBThemes_wp_title', 10, 2 );
function MBThemes_wp_title( $title, $sep ) {
  global $paged, $page, $post;
  if ( is_feed() ){
    return $title;
  }
  $title .= get_bloginfo( 'name' );
  $site_description = get_bloginfo( 'description', 'display' );

  if(is_home() || is_front_page()){
    if ( $site_description ){
      if(_MBT('home_title_exchange')){
        $title = "$site_description $sep $title";
      }else{
        $title = "$title $sep $site_description";
      }
    }
  }elseif ( is_singular() ) {
    $seo_title = get_post_meta($post->ID,"seo_title",true);
    if($seo_title ){
      $title = "$seo_title $sep ".get_bloginfo( 'name' );
    }
    if(_MBT('post_title_site')){
      $title = $post->post_title;
      if($seo_title ){
        $title = $seo_title;
      }
    }
  }elseif(is_category()){
    $cat_ID = get_query_var('cat');
    $seo_title = get_term_meta($cat_ID,'seo-title',true);
    if($seo_title ){
      $title = "$seo_title $sep ".get_bloginfo( 'name' );
    }
  }elseif(is_tag()){
    $tag_slug = get_query_var('tag');
    $tag = get_term_by('slug',$tag_slug,'post_tag');
    $seo_title = get_term_meta($tag->term_id,'seo-title',true);
    if($seo_title ){
      $title = "$seo_title $sep ".get_bloginfo( 'name' );
    }
  }elseif(is_tax('topic')){
    $tag_slug = get_query_var('topic');
    $tag = get_term_by('slug',$tag_slug,'topic');
    $seo_title = '';
    if($tag){
      $seo_title = get_term_meta($tag->term_id,'seo-title',true);
    }
    if($seo_title ){
      $title = "$seo_title $sep ".get_bloginfo( 'name' );
    }
  }

  if ( $paged >= 2 || $page >= 2 ){
    $title = "$title $sep " . sprintf( __( '第%s页', 'mobantu' ), max( $paged, $page ) );
  }
  
  $title = str_replace('&#8211;', '-', $title);
  if(_MBT('delimiter_space')){
    $title = str_replace(' '._MBT('delimiter','-').' ', _MBT('delimiter','-'), $title);
  }
  return $title;
}

function MBThemes_keywords() {
  global $s, $post;
  $keywords = '';
  if ( is_single() ) {
    $seo_keyword = get_post_meta($post->ID,"seo_keyword",true);
    if($seo_keyword){
      $keywords = $seo_keyword;
    }else{
     if ( get_the_tags( $post->ID ) ) {
       foreach ( get_the_tags( $post->ID ) as $tag ) $keywords .= $tag->name . ',';
     }
     foreach ( get_the_category( $post->ID ) as $category ) $keywords .= $category->cat_name . ',';
     $keywords = substr_replace( $keywords , '' , -1);
   }
  } elseif ( is_home () || is_front_page())    { 
    $keywords = _MBT('keywords');
  } elseif ( is_page() ) {
    $seo_keyword = get_post_meta($post->ID,"seo_keyword",true);
    if($seo_keyword){
      $keywords = $seo_keyword;
    }else{
     $keywords = trim( wp_title(',', false, 'right') );
   }
  } elseif ( is_tag() ) { 
    $tag_slug = get_query_var('tag');
    $tag = get_term_by('slug',$tag_slug,'post_tag');
    if($tag){
      $seo_keyword = get_term_meta($tag->term_id,'seo-keyword',true);
    }
    $keywords = single_tag_title('', false);
    if(isset($seo_keyword) && $seo_keyword){
      $keywords = $seo_keyword;
    }
  } elseif ( is_tax('topic') ) { 
    $tag_slug = get_query_var('topic');
    $tag = get_term_by('slug',$tag_slug,'topic');
    $seo_keyword = '';
    if($tag){
      $seo_keyword = get_term_meta($tag->term_id,'seo-keyword',true);
    }
    $keywords = single_tag_title('', false);
    if(isset($seo_keyword) && $seo_keyword){
      $keywords = $seo_keyword;
    }
  } elseif ( is_category() ) {
    $cat_ID = get_query_var('cat');
    $seo_keyword = get_term_meta($cat_ID,'seo-keyword',true);
    $keywords = single_cat_title('', false);
    if($seo_keyword){
      $keywords = $seo_keyword;
    }
  } elseif ( is_search() )   { 
    $keywords = esc_html( $s, 1 );
  } else { 
    $keywords = trim( wp_title(',', false, 'right') );
  }
  if ( $keywords ) {
    echo "<meta name=\"keywords\" content=\"$keywords\">\n";
  }
}

function MBThemes_description() {
  global $s, $post;
  $description = '';
  $blog_name = get_bloginfo('name');
  if ( is_single() ) {
    $seo_desc = get_post_meta($post->ID,"seo_description",true);
    if($seo_desc){
      $description = $seo_desc;
    }else{
     if( !empty( $post->post_excerpt ) ) {
       $text = $post->post_excerpt;
     } else {
       $text = MBThemes_get_excerpt();
     }
     $description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $text ) ) ) );
   }
   if ( !$description ) $description = get_the_title();
  } elseif ( is_home () || is_front_page())    { 
    $description = _MBT('description');
  } elseif ( is_page() ) {
    $seo_desc = get_post_meta($post->ID,"seo_description",true);
    if($seo_desc){
      $description = $seo_desc;
    }else{
     if( !empty( $post->post_excerpt ) ) {
       $text = $post->post_excerpt;
     } else {
       $text = MBThemes_get_excerpt();
     }
     $description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $text ) ) ) );
   }
   if ( !$description ) $description = get_the_title();
  }elseif ( is_tag() )      { 
    $tag_slug = get_query_var('tag');
    $tag = get_term_by('slug',$tag_slug,'post_tag');
    if($tag){
      $seo_description = get_term_meta($tag->term_id,'seo-description',true);
    }
    $description = trim(strip_tags(tag_description()));
    if(isset($seo_description) && $seo_description){
      $description = $seo_description;
    }
    if(!$description){
      $description = sprintf( __("%s上关于%s的内容",'mobantu'), $blog_name, trim($tag->name) );
    }
  } elseif ( is_tax('topic') )      { 
    $tag_slug = get_query_var('topic');
    $tag = get_term_by('slug',$tag_slug,'topic');
    $seo_description = '';
    if($tag){
      $seo_description = get_term_meta($tag->term_id,'seo-description',true);
    }
    $description = trim(strip_tags(tag_description()));
    if(isset($seo_description) && $seo_description){
      $description = $seo_description;
    }
    if(!$description){
      $description = sprintf( __("%s上关于%s的内容",'mobantu'), $blog_name, trim($tag->name) );
    }
  } elseif ( is_category() ) {
    $cat_ID = get_query_var('cat');
    $tag = get_term_by('id',$cat_ID,'category');
    $seo_description = get_term_meta($cat_ID,'seo-description',true);
    $description = trim(strip_tags(category_description()));
    if($seo_description){
      $description = $seo_description;
    }
    if(!$description){
      $description = sprintf( __("%s上关于%s的内容",'mobantu'), $blog_name, trim($tag->name) );
    }
  } elseif ( is_archive() )  { 
    $description = sprintf( __("%s上关于%s的内容",'mobantu'), $blog_name, trim(wp_title('', false)) );
  } elseif ( is_search() )   { 
    $description = $blog_name . "上关于" . esc_html( $s, 1 ) . "的搜索結果";
    $description = sprintf( __("%s上关于%s的搜索結果",'mobantu'), $blog_name, esc_html( $s, 1 ) );
  } else { 
    $description = trim( wp_title('', false) );
  }
  $description = mb_substr( $description, 0, 220, 'utf-8' );
  $description = strip_tags(do_shortcode($description));
  echo "<meta name=\"description\" content=\"$description\">\n";
}

function MBThemes_comments_list($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
  global $wpdb, $post;
  echo '<li '; comment_class(); echo ' id="comment-'.$comment->comment_ID.'">';
  echo '<div class="comt-avatar">';
    MBThemes_avatar($comment->user_id);
    echo '</div>';
    echo '<div class="comt-main" id="div-comment-'.$comment->comment_ID.'">';
    echo convert_smilies(get_comment_text());
    echo '<div class="comt-meta">';
    if ($comment->comment_approved == '0'){
      echo '<span class="comt-approved">'.__('待审核','mobantu').'</span>';
    }
    echo '<span class="comt-author">';
    if($comment->user_id){
      $cuser = get_user_by('ID',$comment->user_id);
      echo $cuser?$cuser->nickname:'';
    }else{
      echo get_comment_author_link(); 
    }

    if(_MBT('post_comment_vip')){
      if(ERPHPDOWN_IS_ACTIVE && MBThemes_check_vip($comment->user_id)){
        echo '<span class="is-vip" title="VIP"><i class="icon icon-crown-s"></i></span>';
      }
    }

    if(_MBT('post_comment_bought')){
      if(ERPHPDOWN_IS_ACTIVE && MBThemes_post_bought($comment->user_id,$post->ID)){
        echo '<span class="comt-bought">'.__('已购买','mobantu').'</span>';
      }
    }

    echo '</span>';
    $_commenttime = strtotime($comment->comment_date); 
    echo '<i class="icon icon-time"></i> '.MBThemes_timeago(date('Y-m-d G:i:s', $_commenttime));

    echo '<a href="javascript:;" class="comt-zan" data-id="'.$comment->comment_ID.'"><i class="icon icon-zan"></i> <span>'.MBThemes_get_comt_zans($comment->comment_ID).'</span></a>';

    if ($comment->comment_approved !== '0'){
      $replyText = get_comment_reply_link( array_merge( $args, array('add_below' => 'div-comment', 'reply_text' => '<i class="icon icon-reply"></i> '.__('回复','mobantu'), 'login_text' => '<i class="icon icon-reply"></i> '.__('回复','mobantu'), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
      echo preg_replace('# href=[\s\S]*? onclick=#', ' href="javascript:;" onclick=', $replyText );
    }
    echo '</div>';
  echo '</div>';
}

add_filter('get_avatar', 'MBT_get_avatar', 10, 3);
function MBT_get_avatar($avatar, $id_or_email, $size = 50){
  $user_avatar = '';
  $default_avatar = get_bloginfo('template_url').'/static/img/avatar.png';
  if(_MBT('user_default_avatar')){
    $default_avatar = _MBT('user_default_avatar');
  }
  if(is_object($id_or_email)) {
    if($id_or_email->user_id != 0) {
      $email = $id_or_email->user_id;
      $user_avatar = get_user_meta($id_or_email->user_id, 'photo', true);
      if($user_avatar){
        $user_avatar = str_replace('http://', '//', $user_avatar);
      }elseif(!_MBT('user_default_avatar') && _MBT('user_random_avatar')){
        update_user_meta($id_or_email->user_id,'photo',get_bloginfo('template_url').'/static/avatar/'.rand(1,10).'.png');
        $user_avatar = get_user_meta($id_or_email->user_id, 'photo', true);
      }

      if($user_avatar)
        return '<img src="'.$user_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      else
        return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      
    }elseif(!empty($id_or_email->comment_author_email)) {
      return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }
  }else{
    if(is_numeric($id_or_email) && $id_or_email > 0){
      $user_avatar = get_user_meta($id_or_email, 'photo', true);
      if($user_avatar){
        $user_avatar = str_replace('http://', '//', $user_avatar);
      }elseif(!_MBT('user_default_avatar') && _MBT('user_random_avatar')){
        update_user_meta($id_or_email,'photo',get_bloginfo('template_url').'/static/avatar/'.rand(1,10).'.png');
        $user_avatar = get_user_meta($id_or_email, 'photo', true);
      }

      if($user_avatar)
        return '<img src="'.$user_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      else
        return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }elseif(is_email($id_or_email)){
      $user = get_user_by('email',$id_or_email);
      if($user){
        $user_avatar = get_user_meta($user->ID, 'photo', true);
      }elseif(!_MBT('user_default_avatar') && _MBT('user_random_avatar')){
        update_user_meta($user->ID,'photo',get_bloginfo('template_url').'/static/avatar/'.rand(1,10).'.png');
        $user_avatar = get_user_meta($user->ID, 'photo', true);
      }

      if($user_avatar){
        $user_avatar = str_replace('http://', '//', $user_avatar);
      }
      if($user_avatar)
        return '<img src="'.$user_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
      else
        return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }else{
      return '<img src="'.$default_avatar.'" class="avatar avatar-'.$size.' photo" width="'.$size.'" height="'.$size.'" />';
    }
  }
  return $avatar;
}

function MBThemes_get_avatar($uid){
  $photo = get_user_meta($uid, 'photo', true);
  if($photo){ 
    return $photo;
  }else{
    $default_avatar = get_bloginfo('template_url').'/static/img/avatar.png';
    if(_MBT('user_default_avatar')){
      $default_avatar = _MBT('user_default_avatar');
    }elseif(!_MBT('user_default_avatar') && _MBT('user_random_avatar')){
      update_user_meta($uid,'photo',get_bloginfo('template_url').'/static/avatar/'.rand(1,10).'.png');
      $default_avatar = get_user_meta($uid, 'photo', true);
    }
    return $default_avatar;
  }
}

function MBThemes_avatar($id=0,$size='50',$class=''){
  $photo = get_user_meta($id, 'photo', true);
  if($photo){ 
    $photo = str_replace('http://', '//', $photo);
    echo '<img class="avatar '.$class.'" src="'.$photo.'" width="'.$size.'" height="'.$size.'" />';
  }
  else echo get_avatar($id,$size);
}

function MBThemes_get_excerpt($limit = 220, $after = "..."){
  global $post;
  if(has_excerpt($post->ID)) {
    return get_the_excerpt();
  }else {
    return wp_trim_words( MBThemes_strip_tags( strip_shortcodes($post->post_content) ), $limit, $after);
  }
}

function MBThemes_get_zans($pid){
  $result = get_post_meta($pid,"zan",true);
  return $result ? $result : "0";
}

function MBThemes_get_comt_zans($cid){
  $result = get_comment_meta($cid,"zan",true);
  return $result ? $result : "0";
}

function MBThemes_do_card($card){
  date_default_timezone_set('Asia/Shanghai');
  if(is_user_logged_in()){
    global $wpdb, $current_user;
    $card = trim($card);
    $result = $wpdb->get_row( $wpdb->prepare(
                "SELECT * FROM $wpdb->erphpcard WHERE card = %s",
                esc_sql($card) ) );
    if($result->status == '0'){
      $ss = $wpdb->query("update $wpdb->erphpcard set status=1,uid='".$current_user->ID."',usetime='".date("Y-m-d H:i:s")."' where card='".esc_sql($card)."'");
      if($ss){
        $alipay_no = date("ymdhis").mt_rand(100, 999).mt_rand(100,999);
        $sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay,ice_data) VALUES ('".$result->price*get_option('ice_proportion_alipay')."','$alipay_no','".$current_user->ID."','".date("Y-m-d H:i:s")."',1,'6','".date("Y-m-d H:i:s")."','','".esc_sql($card)."')";
        $a=$wpdb->query($sql);
        if($a){
            addUserMoney($current_user->ID, $result->price*get_option('ice_proportion_alipay'));
            if(function_exists('addUserMoneyLog')){
              addUserMoneyLog($current_user->ID, $result->price*get_option('ice_proportion_alipay'), '充值卡充值');
            }
            return '1';
        }else{
            return '4';
        }
      }else{
          return '4';
      }
    }elseif($result->status == '1'){
      return '0';
    }else{
      return '5';
    }
  }else{
    return '4';
  }
}

function MBThemes_check_vip($uid){
    global $wpdb;
    if($uid){
      $userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$uid);
      if($userTypeInfo){
          return $userTypeInfo->userType;
      }
    }
    return '0';
}

function MBThemes_check_collect_user($pid,$uid){
  global $wpdb;
  if($uid){
    $result = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM ".$wpdb->prefix ."collects WHERE user_id = %d AND post_id = %d",
            esc_sql($uid), esc_sql($pid) ) );
    return $result;
  }else{
    return false;
  }
}

function MBThemes_check_collect($pid){
  global $wpdb;
  if(is_user_logged_in()){
    global $current_user;
    $result = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM ".$wpdb->prefix ."collects WHERE user_id = %d AND post_id = %d",
            $current_user->ID, $pid ) );
    return $result;
  }else{
    return false;
  }
}

function MBThemes_get_collects($pid){
  global $wpdb;
  $result = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM ".$wpdb->prefix ."collects WHERE post_id = %d",
            esc_sql($pid) ) );
  return $result;
}

function MBThemes_post_bought($uid,$pid){
    global $wpdb;
    $down_info = 0;
    $days=get_post_meta($pid, 'down_days', true);
    $down_tuan=get_post_meta($pid, 'down_tuan', true);
    if($down_tuan){
      $yituan = $wpdb->get_var("select ice_id from $wpdb->tuanorder where ice_user_id=".$uid." and ice_post=".esc_sql($pid)." and ice_status=2");
      if($yituan){
          $down_info = 1;
      }
    }else{
      $down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".esc_sql($pid)."' and ice_success=1 and ice_user_id=".$uid." order by ice_time desc");
      if($down_info && $days > 0){
          $lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
          $nowDate = date('Y-m-d H:i:s');
          if(strtotime($nowDate) > strtotime($lastDownDate)){
            $down_info = 0;
          }
      }
    }
    if($down_info){ 
      return '1';
    }
    return '0';
}

function MBThemes_page($template) {
  global $wpdb;
  $page_id = $wpdb->get_var($wpdb->prepare("SELECT `post_id` 
    FROM `$wpdb->postmeta`, `$wpdb->posts`
    WHERE `post_id` = `ID`
    AND `post_status` = 'publish'
    AND `meta_key` = '_wp_page_template'
    AND `meta_value` = %s
    LIMIT 1;", $template));
  return $page_id;
}

function MBThemes_selfURL(){  
  $pageURL = 'http';
  $pageURL .= (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")?"s":"";
  $pageURL .= "://";
  $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  return $pageURL;  
}

function MBThemes_color(){
  $theme_color_custom = _MBT('theme_color_custom');
  $theme_color_custom2 = _MBT('theme_color_custom2');
  $theme_color = _MBT('theme_color');
  $color = '#ff5f33';
  if($theme_color && $theme_color != '#ff5f33'){
    $color = $theme_color;
  }
  if($theme_color_custom && $theme_color_custom != '#ff5f33'){
    $color = $theme_color_custom;
  }

  $theme_radius = _MBT('theme_radius')?_MBT('theme_radius'):'5px';
  echo ":root{--theme-color: ".$color.";--theme-color2: ".($theme_color_custom2?$theme_color_custom2:$color).";--theme-radius: ".$theme_radius.";}";
  do_action("modown_color");
}

function MBThemes_ad($pos,$mt='',$mb=''){
  if(_MBT($pos.'_s')){

    if(_MBT($pos.'_m') && (wp_is_mobile() || modown_is_mobile())){

    }else{
      $style= 'style="';
      if($mt != ''){
        $style .= 'margin-top:'.$mt.'px; ';
      }
      if($mb != ''){
        $style .= 'margin-bottom:'.$mb.'px; ';
      }
      $style .='"';
      if($style == 'style=""') $style='';

      $class='';
      if($pos == 'ad_banner_footer' || $pos == 'ad_banner_inner' || $pos == 'ad_home_footer'){
        $class = " modown-ad-items";
        if(_MBT('list_column') == 'five-mini'){
          $class = " modown-ad-items-five";
        }
      }

      /*if(_MBT($pos.'_m')){
        $class .= " modown-ad-mobile-hide";
      }*/
      
      if((wp_is_mobile() || modown_is_mobile()) && _MBT($pos.'_wap')){
        echo '<div class="modown-ad'.$class.'" '.$style.'>'._MBT($pos.'_wap').'</div>';
      }else{
        echo '<div class="modown-ad'.$class.'" '.$style.'>'._MBT($pos).'</div>';
      }
    }
  }
}

function MBThemes_breadcrumbs() {
  /* === OPTIONS === */
  $text['home'] = __('首页','mobantu'); 
  $text['category'] = '%s'; 
  $text['search'] = __('搜索结果：%s','mobantu'); 
  $text['tag'] = __('标签：%s','mobantu'); 
  $text['author'] = __('作者：%s','mobantu'); 
  $text['404'] = '404'; 
  $text['page'] = '%s'; 
  $text['cpage'] = '%s'; 
  $wrap_before = '<div class="breadcrumbs">'.__('当前位置：','mobantu'); 
  $wrap_after = '</div>'; 
  $sep = '<i class="dripicons dripicons-chevron-right"></i>'; 
  $sep_before = '<span class="sep">'; 
  $sep_after = '</span>'; 
  $show_home_link = 1; 
  $show_on_home = 0; 
  $show_current = 1;
  $before = '<span class="current">'; 
  $after = '</span>';
  /* === END OF OPTIONS === */
  global $post;
  $home_link = home_url('/');
  $link_before = '<span>';
  $link_after = '</span>';
  $link_attr = ' itemprop="url"';
  $link_in_before = '<span itemprop="title">';
  $link_in_after = '</span>';
  $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
  $frontpage_id = get_option('page_on_front');
  $parent_id = $post->post_parent;
  $sep = ' ' . $sep_before . $sep . $sep_after . ' ';
  if (is_home() || is_front_page()) {
   if ($show_on_home) echo $wrap_before . '<a href="' . $home_link . '">' . $text['home'] . '</a>' . $wrap_after;
 } else {
   echo $wrap_before;
   if ($show_home_link) echo sprintf($link, $home_link, $text['home']);
   if ( is_category() ) {
     $cat = get_category(get_query_var('cat'), false);
     if ($cat->parent != 0) {
       $cats = get_category_parents($cat->parent, TRUE, $sep);
       $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
       $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
       if ($show_home_link) echo $sep;
       echo $cats;
     }
     if ( get_query_var('paged') ) {
       $cat = $cat->cat_ID;
       echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_current) echo $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
     }
   } elseif ( is_search() ) {
     if (have_posts()) {
       if ($show_home_link && $show_current) echo $sep;
       if ($show_current) echo $before . sprintf($text['search'], get_search_query()) . $after;
     } else {
       if ($show_home_link) echo $sep;
       echo $before . sprintf($text['search'], get_search_query()) . $after;
     }
   } elseif ( is_day() ) {
     if ($show_home_link) echo $sep;
     echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
     echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
     if ($show_current) echo $sep . $before . get_the_time('d') . $after;
   } elseif ( is_month() ) {
     if ($show_home_link) echo $sep;
     echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
     if ($show_current) echo $sep . $before . get_the_time('F') . $after;
   } elseif ( is_year() ) {
     if ($show_home_link && $show_current) echo $sep;
     if ($show_current) echo $before . get_the_time('Y') . $after;
   } elseif ( is_single() && !is_attachment() ) {
     if ($show_home_link) echo $sep;
     if ( get_post_type() != 'post' ) {
      if(get_post_type() == 'blog'){
        $blogs = wp_get_post_terms(get_the_ID(),'blogs');
        if( $blogs ){
          $blogs = $blogs[0];
          echo get_term_parents_list($blogs->term_id, 'blogs',array("separator"=>""));
        }else{
          $post_type = get_post_type_object(get_post_type());
          $slug = $post_type->rewrite;
          printf($link, get_post_type_archive_link($post_type->name) . '/', $post_type->labels->name);
        }
      }else{
       $post_type = get_post_type_object(get_post_type());
       $slug = $post_type->rewrite;
       printf($link, get_post_type_archive_link($post_type->name) . '/', $post_type->labels->name);
      }
       if ($show_current) echo $sep . $before . get_the_title() . $after;
     } else {
       $cat = get_the_category(); 
       if($cat){
       $cat = $cat[0];
       $cats = get_category_parents($cat, TRUE, $sep);
       if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
       $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
        echo $cats;
      }
       if ( get_query_var('cpage') ) {
         echo $sep . sprintf($link, get_permalink(), __('正文','mobantu')) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
       } else {
         if ($show_current) echo $before . __('正文','mobantu') . $after;
       }
     }
   } elseif ( is_singular() && !is_page() && get_post_type() != 'post' && !is_404() ) {
     $post_type = get_post_type_object(get_post_type());
     if ( get_query_var('paged') ) {
       echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_current) echo $sep . $before . $post_type->label . $after;
     }
   } elseif ( is_attachment() ) {
     if ($show_home_link) echo $sep;
     $parent = get_post($parent_id);
     $cat = get_the_category($parent->ID); $cat = $cat[0];
     if ($cat) {
       $cats = get_category_parents($cat, TRUE, $sep);
       $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
       echo $cats;
     }
     printf($link, get_permalink($parent), $parent->post_title);
     if ($show_current) echo $sep . $before . get_the_title() . $after;
   } elseif ( is_page() && !$parent_id ) {
     if ($show_current) echo $sep . $before . get_the_title() . $after;
   } elseif ( is_page() && $parent_id ) {
     if ($show_home_link) echo $sep;
     if ($parent_id != $frontpage_id) {
       $breadcrumbs = array();
       while ($parent_id) {
         $page = get_page($parent_id);
         if ($parent_id != $frontpage_id) {
           $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
         }
         $parent_id = $page->post_parent;
       }
       $breadcrumbs = array_reverse($breadcrumbs);
       for ($i = 0; $i < count($breadcrumbs); $i++) {
         echo $breadcrumbs[$i];
         if ($i != count($breadcrumbs)-1) echo $sep;
       }
     }
     if ($show_current) echo $sep . $before . get_the_title() . $after;
   } elseif ( is_tag() ) {
     if ( get_query_var('paged') ) {
       $tag_id = get_queried_object_id();
       $tag = get_tag($tag_id);
       echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
     }
   } elseif ( is_author() ) {
     global $author;
     $author = get_userdata($author);
     if ( get_query_var('paged') ) {
       if ($show_home_link) echo $sep;
       echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
     } else {
       if ($show_home_link && $show_current) echo $sep;
       if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
     }
   } elseif ( is_404() ) {
     if ($show_home_link && $show_current) echo $sep;
     if ($show_current) echo $before . $text['404'] . $after;
   } elseif ( has_post_format() && !is_singular() ) {
     if ($show_home_link) echo $sep;
     echo get_post_format_string( get_post_format() );
   }
   echo $wrap_after;
 }
}

if(_MBT('file_rename')){
  add_filter('wp_handle_upload_prefilter', 'MBThemes_wp_handle_upload_prefilter'); 
  function MBThemes_wp_handle_upload_prefilter($file){  
    $time=date("dHis");  
    $file['name'] = $time.mt_rand(100,999).".".pathinfo($file['name'] , PATHINFO_EXTENSION);  
    return $file;  
  } 
}

if(_MBT('thumbnail')){
  add_filter( 'intermediate_image_sizes_advanced', 'MBThemes_remove_image_sizes' );
  function MBThemes_remove_image_sizes( $sizes ) {
    $list_thumbnails = get_intermediate_image_sizes();
    foreach ( $list_thumbnails as $value ) {
      unset( $sizes[ $value ] );
    }
    return $sizes;
  }
}


add_action('init', 'MBThemes_allow_contributor_uploads');
function MBThemes_allow_contributor_uploads() {
  $user = wp_get_current_user();
  if( isset($user->roles) && $user->roles && ($user->roles[0] == 'contributor' || $user->roles[0] == 'subscriber')){
    $allow = _MBT('tougao_upload');
    $can_upload = isset($user->allcaps['upload_files']) ? $user->allcaps['upload_files'] : 0;

    if ( $allow && !$can_upload ) {
      $contributor = get_role('contributor');
      $contributor->add_cap('upload_files');

      $subscriber = get_role('subscriber');
      $subscriber->add_cap('upload_files');
      $subscriber->add_cap('edit_posts');
    } else if(!$allow && $can_upload){
      $contributor = get_role('contributor');
      $contributor->remove_cap('upload_files');

      $subscriber = get_role('subscriber');
      $subscriber->remove_cap('upload_files');
      $subscriber->remove_cap('edit_posts');
    }
  }
}

add_action('pre_get_posts','MBThemes_restrict_media_library');
function MBThemes_restrict_media_library( $wp_query_obj ) {
  global $current_user, $pagenow;
  if( ! $current_user instanceof WP_User )
    return;
  if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
    return;
  if( !current_user_can('edit_others_posts') )
    $wp_query_obj->set('author', $current_user->ID );
  return;
}

function MBThemes_sanitize_user ($username, $raw_username, $strict) {
  $username = wp_strip_all_tags( $raw_username );
  $username = remove_accents( $username );
  $username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
  $username = preg_replace( '/&.+?;/', '', $username );
  if ($strict) {
    $username = preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
  }
  $username = trim( $username );
  $username = preg_replace( '|\s+|', ' ', $username );
  return $username;
}
add_filter ('sanitize_user', 'MBThemes_sanitize_user', 10, 3);

function MBThemes_check_checkin($uid){
  return erphpdown_check_checkin($uid);
}

function MBThemes_categorys_fids(){
  $categories = get_the_category();
  $cat = '';$catid = '';
  if($categories) {
    foreach($categories as $cate){
      $parent = get_category_parents( $cate->term_id , FALSE, ':', TRUE);
      $top_cat = explode(':',rtrim($parent,':'));
      $cat .= $top_cat[0].',';
    }
  }
  $cat = rtrim($cat,',');
  $cat_arr = array_unique(explode(',', $cat));
  foreach ($cat_arr as $cat_slug) {
    $cata = get_category_by_slug($cat_slug);
    $catid .= $cata->term_id.',';
  }
  $catid = rtrim($catid,',');
  return $catid;
}

function MBThemes_categorys($sp = ' · '){
  $categories = get_the_category();
  $cat = '';$cat_link = '';
  if(_MBT('post_cat_all')){
    if($categories) {
      foreach($categories as $cate){
        $cat .= $cate->slug.',';
      }
      $cat = rtrim($cat,',');
      $cat_arr = array_unique(explode(',', $cat));
      foreach ($cat_arr as $cat_slug) {
        $cata = get_category_by_slug($cat_slug);
        $cat_link .= '<a href="'.get_category_link($cata).'" id="img-cat-'.$cata->term_id.'">'.$cata->name.'</a>'.$sp;
      }
    }
  }elseif(_MBT('post_cat_f')){
    if($categories) {
      foreach($categories as $cate){
        $parent = get_category_parents( $cate->term_id , FALSE, ':', TRUE);
        $top_cat = explode(':',rtrim($parent,':'));
        $cat .= $top_cat[0].',';
      }
      $cat = rtrim($cat,',');
      $cat_arr = array_unique(explode(',', $cat));
      foreach ($cat_arr as $cat_slug) {
        $cata = get_category_by_slug($cat_slug);
        $cat_link .= '<a href="'.get_category_link($cata).'" id="img-cat-'.$cata->term_id.'">'.$cata->name.'</a>'.$sp;
      }
    }
  }else{
    if($categories){
      $cat = $categories[0];
      foreach($categories as $cate){
        $children = get_term_children( $cate->term_id , 'category');
        if ( count($children) == '0') {
          $cat = $cate;
          break;
        }
      }
      $cat_link = '<a href="'.get_category_link($cat).'" id="img-cat-'.$cat->term_id.'">'.$cat->name.'</a>';
    }
  }
  return rtrim($cat_link,$sp);
}

function MBThemes_youngest_category(){
  $categories = get_the_category();
  $cat = '';
  if($categories) {
    $cat = $categories[0];
    foreach($categories as $cate){
      $children = get_term_children( $cate->term_id , 'category');
      if ( count($children) == '0') {
        $cat = $cate;
        break;
      }
    }
  }
  return $cat;
}

function MBThemes_youngest_category_ids(){
  $categories = get_the_category();
  $cat = '';
  if($categories) {
    foreach($categories as $cate){
      $children = get_term_children( $cate->term_id , 'category');
      if ( count($children) == '0') {
        $cat .= $cate->term_id.',';
      }
    }
  }
  return $cat;
}

function MBThemes_related_tags($cat_ID){
  $all_tags_arr=array();
  $args = array('cat' => $cat_ID,'posts_per_page'=>'-1');
  query_posts($args);
  if (have_posts()){
    while (have_posts()){
      the_post();
      $posttags = get_the_tags();
      if ($posttags) {
        foreach($posttags as $tag) {
          $all_tags_arr[] = array('id'=>$tag->term_id, 'num'=>1);
        }
      }
    }
    wp_reset_query();
  }

  /*global $wpdb;
  $all_tags_arr = $wpdb->get_results("
    SELECT DISTINCT wp_terms.term_id
    FROM {$wpdb->terms} AS wp_terms
    INNER JOIN {$wpdb->term_taxonomy} AS wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
    INNER JOIN {$wpdb->term_relationships} AS wp_term_relationships ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
    INNER JOIN {$wpdb->posts} AS wp_posts ON wp_term_relationships.object_id = wp_posts.ID
    WHERE wp_term_taxonomy.taxonomy = 'post_tag'
    AND wp_term_taxonomy.term_id IN (
        SELECT term_id FROM {$wpdb->term_taxonomy} WHERE parent = ".esc_sql($cat_ID)." OR term_id = ".esc_sql($cat_ID)."
    )
    AND wp_posts.post_status = 'publish'
  ");

  var_dump($all_tags_arr);*/
  
  if(count($all_tags_arr)){
    $tags = array();
    foreach($all_tags_arr as $item){
      if(! isset($tags[$item['id']])){
        $tags[$item['id']] = $item;
      }else {
        $tags[$item['id']]['num'] += $item['num'];
      }
    }
    $nums = array_column($tags,'num');
    array_multisort($nums,SORT_DESC,$tags);
    $i = 0;$is = _MBT('filter_tags_auto_count',20);
    $all_tags = '';
    foreach ($tags as $tag) {
      if($i == $is){ 
        break;
      }
      $all_tags .= $tag['id'].',';
      $i++;
    }
    return rtrim($all_tags,',');
  }else{
    return '';
  }
}

function MBThemes_current_user_role($user_id){
  $nick = get_user_meta($user_id,'nick',true);
  if($nick) return $nick;
  else{
    if(user_can($user_id,'install_plugins')){return __('管理员','mobantu');}   
    elseif(user_can($user_id,'edit_others_posts')){return __('编辑','mobantu');}
    elseif(user_can($user_id,'publish_posts')){return __('作者','mobantu');}
    elseif(user_can($user_id,'delete_posts')){return __('投稿者','mobantu');}
    elseif(user_can($user_id,'read')){return __('订阅者','mobantu');}  
    else{return __('火星人','mobantu');} 
  }
}

function MBThemes_yestoday_post_count(){
  global $wpdb;
  $args = array(
    'date_query' => array(
      array(
        'year'  => date("Y",strtotime("-1 day")),
        'month' => date("m",strtotime("-1 day")),
        'day'   => date("d",strtotime("-1 day")),
      ),
    ),
  );
  $query = new WP_Query( $args );
  return $query->found_posts;
}

function MBThemes_yestoday_user_count(){
  global $wpdb;
  $result = $wpdb->get_var("select count(ID) from $wpdb->users where TO_DAYS(NOW())- TO_DAYS(user_registered) = 1 ");
  return $result;
}

function MBThemes_count_vip_posts(){
  $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(4,3,8,9,6,7), 'compare' => 'IN'));
  $args['posts_per_page'] = '-1';
  $args['post_status'] = 'publish';
  $results = query_posts($args);
  $count = count($results);
  wp_reset_query(); 
  return $count;
}

function MBThemes_count_vip_users(){
  global $wpdb;
  $count  = $wpdb->get_var("select count(ice_id) from  ".$wpdb->iceinfo." where userType > 0");
  return $count;
}

function MBThemes_image_to_base64( $image ){
  $upload_info = wp_upload_dir();
  $upload_url = $upload_info['baseurl'];
  if ( false === strpos( $image, $upload_url ) ) {
    $http_options = array(
      'httpversion' => '1.0',
      'timeout' => 20,
      'redirection' => 20,
      'sslverify' => FALSE,
      'user-agent' => 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; MALC)'
    );

    $get = wp_remote_get($image, $http_options);
    if (!is_wp_error($get) && 200 === $get ['response'] ['code']) {
      $img_base64 = 'data:image/' . $get['headers']['content-type'] . ';base64,' . base64_encode($get ['body']);
      return $img_base64;
    }else{
      return $image;
    }
  }else{
    return $image;
  }
}

function MBThemes_num2tring($num) {
  if ($num >= 100000) {
    $num = '10w+';
  } elseif ($num >= 10000) {
        $num = round($num / 10000 * 100) / 100 .'w';
    } elseif($num >= 1000) {
        $num = round($num / 1000 * 100) / 100 . 'k';
    } else {
        $num = $num;
    }
    return $num;
}

add_action( 'template_redirect', 'MBThemes_force_email' );
function MBThemes_force_email() {
  if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    return;
  }
  if( is_user_logged_in() ) {
    global $current_user;
    if(_MBT('user_force_email') && !$current_user->user_email){
      $schema = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';
      $url = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      if ( preg_replace( '/\?.*/', '', $url ) !== preg_replace( '/\?.*/', '', get_permalink(MBThemes_page("template/user.php")) ) ) {
        $redirect_url = apply_filters( 'MBThemes_force_email', $url );
        nocache_headers();
        wp_safe_redirect(add_query_arg('action','info',get_permalink(MBThemes_page("template/user.php"))), 302);
        exit;
      }
    }
  }
}

add_action( 'template_redirect', 'MBThemes_force_mobile' );
function MBThemes_force_mobile() {
  if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    return;
  }
  if( is_user_logged_in() ) {
    global $current_user, $wpdb;
    $mobile = $wpdb->get_var("select mobile from $wpdb->users where ID=".$current_user->ID);
    if(_MBT('user_force_mobile') && _MBT('oauth_sms') && !$mobile){
      $schema = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';
      $url = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      if ( preg_replace( '/\?.*/', '', $url ) !== preg_replace( '/\?.*/', '', get_permalink(MBThemes_page("template/user.php")) ) ) {
        $redirect_url = apply_filters( 'MBThemes_force_mobile', $url );
        nocache_headers();
        wp_safe_redirect(add_query_arg('action','info',get_permalink(MBThemes_page("template/user.php"))), 302);
        exit;
      }
    }
  }
}

function MBThemes_is_phone($mobile) {
  if (!is_numeric($mobile)) {
    return false;
  }
  return preg_match("/^1[3456789]{1}\d{9}$/", $mobile) ? true : false;
}

function MBThemes_check_reply(){
  global $post, $wpdb;
  $reply_see = 1;$email = '';
  $down_reply=get_post_meta($post->ID, 'down_reply', true);
  if($down_reply){
    $reply_see = 0;
    $user_ID = (int) wp_get_current_user()->ID;   
    if ($user_ID > 0) {   
      $email = get_userdata($user_ID)->user_email;   
      $admin_email = get_option('admin_email');  
      if ($email == $admin_email) {   
        $reply_see = 1;
      }   
    } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {   
      $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);   
    } else {   
      $reply_see = 0;
    } 

    if (empty($email)) {   
      $reply_see = 0;
    } 

    if(!$reply_see){
      $post_id = $post->ID;   
      $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";   
      if ($wpdb->get_results($query)) {   
          $reply_see = 1;
      } else {
        if($user_ID){
          $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `user_id`='{$user_ID}' LIMIT 1";   
          if ($wpdb->get_results($query)) {   
            $reply_see = 1;
          }else{
            $reply_see = 0;
          }
        }else{
          $reply_see = 0;
        } 
      } 
    }

  }
  return $reply_see;
}

function MBThemes_parent_cid($catid){
  $catParent = $catid;
  while ($catid) {
    $cat = get_category($catid);
    if($cat){
      $catid = $cat->category_parent;
      $catParent = $cat->cat_ID;
    }else{
      $catid = 0;
    }
  }
  return $catParent;
}

function MBThemes_strip_tags($content){
  if($content){
    $content = preg_replace("/\[.*?\].*?\[\/.*?\]/is", "", $content);
  }
  return strip_tags($content);
}

function _mbt_add_activity($user_id, $activity, $activity_key='', $activity_value=''){
  date_default_timezone_set('Asia/Shanghai');
  global $wpdb;
  if($wpdb->get_var("show tables like '".$wpdb->prefix."activitys'")){
    $result = $wpdb->query("insert into ".$wpdb->prefix."activitys (user_id,user_activity,activity_key,activity_value,create_time) values (".$user_id.",'".$activity."','".$activity_key."','".$activity_value."','".date("Y-m-d H:i:s")."')");
    return $result;
  }else{
    return false;
  }
}

add_action('comment_post', '_mbt_comment_notice', 10, 2);
function _mbt_comment_notice($comment_id, $comment_approved){
  if( '1' == $comment_approved ){
    $comment = get_comment($comment_id);
    $parent_id = $comment->comment_parent;
    if($parent_id){
      $comment_p = get_comment($parent_id);
      if($comment_p->user_id){
        _mbt_add_notice($comment_p->user_id, sprintf(__('您好，您发表在《%s》上的评论有新回复。','mobantu'), get_post($comment_p->comment_post_ID)->post_title).'<a class="notify__link" href="'.get_comment_link($comment_id).'" target="_blank">'.__('查看详情','mobantu').'</a>', 'comment_reply', $comment_id);
      }
    }else{
      $comment_post = get_post($comment->comment_post_ID);
      _mbt_add_notice($comment_post->post_author, sprintf(__('您好，您发表的文章《%s》有新评论。','mobantu'), $comment_post->post_title).'<a class="notify__link" href="'.get_comment_link($comment_id).'" target="_blank">'.__('查看详情','mobantu').'</a>', 'comment_post', $comment_id);
    }
  }
}

function _mbt_add_notice($user_id, $message, $type_key='', $type_value=''){
  date_default_timezone_set('Asia/Shanghai');
  global $wpdb;
  $result = $wpdb->query("insert into ".$wpdb->prefix."notices (user_id,message,type_key,type_value,create_time) values (".$user_id.",'".$message."','".$type_key."','".$type_value."','".date("Y-m-d H:i:s")."')");
  return $result;
}

function _mbt_last_notice_time($user_id){
  date_default_timezone_set('Asia/Shanghai');
  global $wpdb;
  $result = $wpdb->get_var("select create_time from ".$wpdb->prefix."notices where user_id=".$user_id." or user_id=0 order by create_time desc limit 0,1");
  return $result;
}

add_action('admin_menu', '_mbt_activity_menu');
function _mbt_activity_menu() {
    add_management_page('用户动态', '用户动态', 'activate_plugins', 'mbt_activity_list', 'mbt_activity_list');
}

function mbt_activity_list(){
  global $wpdb;
  if(isset($_POST['action'])){
    if($_POST['action'] == '7'){
      $wpdb->query("delete from ".$wpdb->prefix . "activitys WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '3'){
      $wpdb->query("delete from ".$wpdb->prefix . "activitys WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 3 DAY)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '30'){
      $wpdb->query("delete from ".$wpdb->prefix . "activitys WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '180'){
      $wpdb->query("delete from ".$wpdb->prefix . "activitys WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '365'){
      $wpdb->query("delete from ".$wpdb->prefix . "activitys WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }
  }

  if(isset($_POST['delid']) && $_POST['delid'] > 0){
    $wpdb->query("delete from ".$wpdb->prefix . "activitys where ID=".$_POST['delid']);
  }
  $total   = $wpdb->get_var("SELECT COUNT(ID) FROM ".$wpdb->prefix."activitys");
  $perpage = 20;
  $pages = ceil($total / $perpage);
  $page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
  $offset = $perpage*($page-1);
  $list = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."activitys ORDER BY create_time DESC limit $offset,$perpage");
?>
<style>
.wp-list-table td{display: table-cell !important;word-break: break-all;}
@media (max-width: 768px){
  .wp-list-table td.pc, .wp-list-table th.pc{display: none !important;}
}
</style>
<div class="wrap">
  <h2>用户动态</h2>
  <table class="wp-list-table widefat fixed striped posts">
    <thead>
        <th class="pc">用户ID</th>
        <th>动态</th>  
        <th>时间</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
  <?php
    if($list) {
      foreach($list as $value){
        echo "<tr id='order".$value->ID."'>\n";
        echo "<td class='pc'>";
        if($value->user_id){
            echo get_the_author_meta( 'user_login', $value->user_id );
        }else{
            echo __('游客','mobantu');
        }
        echo "</td>\n";
        echo "<td>";
        if($value->user_activity == 'checkin'){
            echo '签到打卡，获得'.$value->activity_value.get_option('ice_name_alipay').'奖励';
        }elseif($value->user_activity == 'login'){
            echo '登录了本站';
        }elseif($value->user_activity == 'register'){
            echo '加入了本站';
        }elseif($value->user_activity == 'buy'){
          $pp = get_post($value->activity_value);
            echo '购买了资源 <b>'.($pp?$pp->post_title:'').'</b>';
        }elseif($value->user_activity == 'vip'){
            echo '开通了VIP';
        }elseif($value->user_activity == 'download'){
          $pp = get_post($value->activity_value);
            echo '下载了资源 <b>'.($pp?$pp->post_title:'').'</b>';
        }else{
            echo $value->user_activity;
        }
        echo "<td>$value->create_time</td>";
        echo '<td><form method="post"><input type="hidden" name="delid" value="'.$value->ID.'"><input type="submit" class="button" value="'.__('删除','mobantu').'"></form></td>';
        echo "</tr>";
      }
    }
    else{
      echo '<tr><td colspan="4" align="center"><strong>暂无记录</strong></td></tr>';
    }
  ?>
  </tbody>
  </table>
    <?php mbt_pagination($total,$perpage);?>
    <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="3"  />
    <input type="submit" value="清理三天之前所有动态" class="button-primary">
  </form>
    <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="7"  />
    <input type="submit" value="清理一周之前所有动态" class="button-primary">
  </form>
  <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="30"  />
    <input type="submit" value="清理一月之前所有动态" class="button-primary">
  </form>
  <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="180"  />
    <input type="submit" value="清理半年之前所有动态" class="button-primary">
  </form>
  <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="365"  />
    <input type="submit" value="清理一年之前所有动态" class="button-primary">
  </form>
</div>
<?php
}

add_action('admin_menu', '_mbt_notice_menu');
function _mbt_notice_menu() {
    add_management_page('群发通知', '群发通知', 'activate_plugins', 'mbt_notice_add', 'mbt_notice_add');
}

function mbt_notice_add(){
  global $wpdb;
  if(isset($_POST['notice_content']) && $_POST['notice_content'] != ''){
    if(isset($_POST['notice_user']) && $_POST['notice_user'] != ''){
      $nu = get_user_by('login',$_POST['notice_user']);
      if($nu){
        $result = _mbt_add_notice($nu->ID,$_POST['notice_content']);
      }else{
        echo'<div class="error settings-error"><p>用户名不存在！</p></div>';
      }
    }else{
      $result = _mbt_add_notice(0,$_POST['notice_content']);
    }
    if($result){
      echo'<div class="updated settings-error"><p>通知成功！</p></div>';
    }else{
      echo'<div class="error settings-error"><p>通知失败，请检查人品后重试！</p></div>';
    }
  }

  if(isset($_POST['delid']) && $_POST['delid']){
    $wpdb->query("delete from ".$wpdb->prefix."notices where ID=".$_POST['delid']);
    echo'<div class="updated settings-error"><p>删除成功！</p></div>';
  }
?>
<div class="wrap">
    <h1>群发通知</h1>
    <form method="post" action="<?php echo admin_url('tools.php?page=mbt_notice_add');?>" onsubmit = "return confirm('确认群发？群发后耶稣都撤回不了哦~');">
        <table class="form-table">
            <tr>
                <th valign="top">内容</th>
                <td>
                    <textarea id="notice_content" name="notice_content" class="regular-text" style="height:150px" required /></textarea>
                    <p>换行用<code>&lt;br&gt;</code></p>
                </td>
            </tr>
            <tr>
                <th valign="top">用户名</th>
                <td>
                    <input type="text" id="notice_user" name="notice_user" class="regular-text"/>
                    <p>留空则群发所有用户</p>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" value="群发通知" class="button-primary" />
        </p>  
    </form>
</div>
<?php

  $total_trade   = $wpdb->get_var("SELECT COUNT(ID) FROM ".$wpdb->prefix."notices WHERE user_id=0");
  $ice_perpage = 10;
  $pages = ceil($total_trade / $ice_perpage);
  $page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
  $offset = $ice_perpage*($page-1);

?>
<div class="wrap">
  <?php
  $adds=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."notices where user_id=0 order by create_time DESC limit $offset,$ice_perpage");
  ?>
  <h2>群发记录</h2>
  <table class="widefat fixed striped posts">

    <thead>
      <tr>
        <th>时间</th>
        <th>内容</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if($adds) {
        foreach($adds as $value)
        {
          echo "<tr>\n";
          echo "<td>$value->create_time</td>";
          echo "<td>$value->message</td>\n";
          echo "<td><form method='post'><input type='hidden' name='delid' value='".$value->ID."'><button type='submit' class='button'>删除</button></form></td>\n";
          echo "</tr>";
        }
      }
      else
      {
        echo '<tr><td colspan="3" align="center"><strong>你居然还没发过通知</strong></td></tr>';
      }
      ?>
    </tbody>
  </table>
  <?php echo erphp_admin_pagenavi($total_trade,$ice_perpage);?>　    
</div>
<?php
}

add_action('admin_menu', '_mbt_qmail_menu');
function _mbt_qmail_menu() {
    add_management_page('群发邮件', '群发邮件', 'activate_plugins', 'mbt_qmail_add', 'mbt_qmail_add');
}

function mbt_qmail_add(){
  if(isset($_POST['qmail_title'])){
    if(isset($_POST['qmail_title'])) update_option('qmail_title', trim($_POST['qmail_title']));
    if(isset($_POST['qmail_content'])) update_option('qmail_content', $_POST['qmail_content']);
    echo'<div class="updated settings-error"><p>保存成功！</p></div>';
  }
  $qmail_title = get_option("qmail_title");
  $qmail_content = get_option("qmail_content");
?>
<div class="wrap">
    <h1>群发邮件设置</h1>
    <form method="post">
        <table class="form-table">
            
            <tr>
                <th valign="top">标题</th>
                <td>
                    <input type="text" id="qmail_title" name="qmail_title" value="<?php echo $qmail_title;?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th valign="top">内容</th>
                <td>
                    <textarea id="qmail_content" name="qmail_content" class="regular-text" style="height:250px" /><?php echo $qmail_content;?></textarea>
                    <p>换行用<code>&lt;br&gt;</code>，超链接用<code>&lt;a href=链接地址&gt;链接文字&lt;/a&gt;</code></p>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" value="保存设置" class="button-primary" />
        </p>  
    </form>
    <p>先设置群发的标题与内容，保存后在 <a href="<?php echo admin_url("users.php");?>">用户列表</a> 里进行批量操作。<br>注意：建议一次性群发人数不要太多，几个即可，否则系统可能会卡死。</p>
</div>
<?php
}


add_filter( 'bulk_actions-users', 'mbt_qmail_user_bulk_actions' );
function mbt_qmail_user_bulk_actions($bulk_actions) {
    $bulk_actions['mbt_qmail'] = '群发邮件';
    //$bulk_actions['mbt_qmsg'] = '发私信（工单）';
    return $bulk_actions;
}

add_filter( 'handle_bulk_actions-users', 'mbt_qmail_user_bulk_action_handler', 10, 3 );
function mbt_qmail_user_bulk_action_handler( $redirect_to, $doaction, $user_ids ) {
    if ( $doaction !== 'mbt_qmail') {
        return $redirect_to;
    }
    if($doaction == 'mbt_qmail'){
      $i = 0;
      $qmail_title = get_option("qmail_title");
      $qmail_content = get_option("qmail_content");
      if($qmail_title && $qmail_content){
        $headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
        foreach ( $user_ids as $user_id ) {
          $cuser = get_user_by('id',$user_id);
          if($cuser->user_email){
            wp_mail($cuser->user_email, $qmail_title, $qmail_content, $headers);
            $i ++;
          }
        }
      }
    }
    $redirect_to = add_query_arg( 'bulk_mbt_qmail_user', $i, $redirect_to );
    return $redirect_to;
}

add_action( 'admin_notices', 'mbt_qmail_action_admin_notice' );
function mbt_qmail_action_admin_notice() {
    if ( ! empty( $_REQUEST['bulk_mbt_qmail_user'] ) ) {
        $user_count = intval( $_REQUEST['bulk_mbt_qmail_user'] );
        printf( '<div id="message" class="updated settings-error fade"><p>成功群发给 '.$user_count.' 个用户。</p></div>', $user_count );
    }
}


add_action('admin_menu', '_mbt_content_domain_menu');
function _mbt_content_domain_menu() {
    add_management_page('内容替换域名', '内容替换域名', 'activate_plugins', 'mbt_content_domain_replace', 'mbt_content_domain_replace');
}

function mbt_content_domain_replace(){
  global $wpdb;
  if(isset($_POST['old_domain']) && isset($_POST['new_domain'])){

    $wpdb->query("UPDATE $wpdb->posts SET post_content = replace(post_content,'".esc_sql($_POST['old_domain'])."', '".esc_sql($_POST['new_domain'])."')");
    $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = replace(meta_value,'".esc_sql($_POST['old_domain'])."', '".esc_sql($_POST['new_domain'])."') where meta_key != 'down_urls'");
    $wpdb->query("UPDATE $wpdb->usermeta SET meta_value = replace(meta_value,'".esc_sql($_POST['old_domain'])."', '".esc_sql($_POST['new_domain'])."')");
    $wpdb->query("UPDATE $wpdb->termmeta SET meta_value = replace(meta_value,'".esc_sql($_POST['old_domain'])."', '".esc_sql($_POST['new_domain'])."')");
    
    echo'<div class="updated settings-error"><p>替换成功！</p></div>';
  }
?>
<div class="wrap">
    <h1>内容替换域名</h1>
    <form method="post">
        <table class="form-table">
            <tr>
                <th valign="top">旧域名</th>
                <td>
                    <input type="text" id="qmail_title" name="old_domain" class="regular-text" required />
                    <p>一般不需要加http://或https://，除非你需要带上一起替换。例如：old.mobantu.com</p>
                </td>
            </tr>
            <tr>
                <th valign="top">新域名</th>
                <td>
                    <input type="text" id="qmail_title" name="new_domain" class="regular-text" required />
                    <p>一般不需要加http://或https://，除非你需要带上一起替换。例如：new.mobantu.com</p>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" value="提交替换" class="button-primary" />
        </p>  
    </form>
    <p><font color="red">务必先备份数据库！</font>替换文章正文、文章自定义字段、用户自定义字段、分类法自定义字段里的域名；多价格的下载地址替换不了。</p>
</div>
<?php
}

function MBThemes_modify_user_table( $column ) {
  if(_MBT('oauth_sms')){
    $column['mobile'] = '手机号';
  }
  if(_MBT('user_idcard_verify')){
    $column['verify_user'] = '实名';
  }
  return $column;
}
add_filter( 'manage_users_columns', 'MBThemes_modify_user_table' , 10, 1);

function MBThemes_modify_user_table_row( $val, $column_name, $user_id ) {
  global $wpdb;
  switch ($column_name) {
    case 'mobile' :
      if(_MBT('oauth_sms')){
        $mobile = $wpdb->get_var("select mobile from $wpdb->users where ID=".$user_id);
        if($mobile){
          return $mobile;
        }else{
          return '—';
        }
      }else{
        return '';
      }
    break;
    case 'verify_user' :
      if(_MBT('user_idcard_verify')){
        $verify_user = get_user_meta($user_id,'verify_user',true);
        if($verify_user){
          return '<font color="green">已实名</font>';
        }else{
          return '—';
        }
      }else{
        return '';
      }
    break;
  }
  return $val;
}
add_filter( 'manage_users_custom_column', 'MBThemes_modify_user_table_row', 10, 3 );

function MBTheme_waterfall(){
  $waterfall = '';
  if(is_category()){
    $cat_ID = get_query_var('cat');
    $waterfall = get_term_meta($cat_ID,'waterfall',true);
  }elseif(is_tag()){
    $tag_slug = get_query_var('tag');
    $tag = get_term_by('slug',$tag_slug,'post_tag');
    if($tag){
      $waterfall = get_term_meta($tag->term_id,'waterfall',true);
    }
  }elseif(is_tax('topic')){
    $tag_slug = get_query_var('topic');
    $tag = get_term_by('slug',$tag_slug,'topic');
    if($tag){
      $waterfall = get_term_meta($tag->term_id,'waterfall',true);
    }
  }elseif(is_search()){
    if(_MBT('waterfall') || _MBT('search_waterfall')){
      return '1';
    }else{
      return '0';
    }
  }

  if($waterfall == '1'){
    return '1';
  }elseif($waterfall == '' || $waterfall == 'default'){
    if(_MBT('waterfall')){
      return '1';
    }else{
      return '0';
    }
  }elseif($waterfall == '0'){
    return '0';
  }
}

if(_MBT('nav_position') == '1'){
  add_action("modown_header_nav", "MBTheme_header_nav", 10);
}elseif(_MBT('header_search_nav')){
  add_action("modown_header_nav_right", "MBTheme_header_nav", 10);
}
function MBTheme_header_nav(){
?>
  <div class="nav-search-form">
    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" method="get">
      <?php 
      if(_MBT('banner_cats')){
          $cats = explode(',', trim(_MBT('banner_cats')));
          if(count($cats)){
            echo '<div class="search-cat">'.__('所有分类','mobantu').'</div>';
          }
      }
      ?>
      <input autocomplete="off" class="search-input" name="s" placeholder="<?php echo __('搜索一下','mobantu'); ?>" type="text">
      <input type="hidden" name="cat" class="search-cat-val">
      <?php
        if(_MBT('banner_cats')){
          echo '<div class="search-cats"><ul>';
          echo '<li data-id="">'.__('所有分类','mobantu').'</li>';
          foreach ($cats as $cat) {
              echo '<li data-id="'.$cat.'">'.get_category($cat)->name.'</li>';
          }
          echo '</ul></div>';
        }
      ?>
      <button class="search-btn"><i class="icon icon-search"></i></button>
    </form>
  </div>
<?php
}

add_filter( 'bulk_actions-edit-post', 'mbt_recommend_bulk_actions' );
function mbt_recommend_bulk_actions($bulk_actions) {
    $bulk_actions['modown_recommend'] = '推荐';
    $bulk_actions['modown_unrecommend'] = '取消推荐';
    return $bulk_actions;
}

add_filter( 'handle_bulk_actions-edit-post', 'mbt_recommend_bulk_action_handler', 10, 3 );
function mbt_recommend_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
    if ( $doaction !== 'modown_recommend' ) {
        return $redirect_to;
    }
    foreach ( $post_ids as $post_id ) {
        update_post_meta($post_id,'down_recommend',1);
    }

    $redirect_to = add_query_arg( 'bulk_mbt_recommend_posts', count( $post_ids ), $redirect_to );
    return $redirect_to;
}

add_filter( 'handle_bulk_actions-edit-post', 'mbt_unrecommend_bulk_action_handler', 10, 3 );
function mbt_unrecommend_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
    if ( $doaction !== 'modown_unrecommend' ) {
        return $redirect_to;
    }
    foreach ( $post_ids as $post_id ) {
        delete_post_meta($post_id,'down_recommend');
    }

    $redirect_to = add_query_arg( 'bulk_mbt_unrecommend_posts', count( $post_ids ), $redirect_to );
    return $redirect_to;
}

add_action( 'admin_notices', 'mbt_recommend_bulk_action_admin_notice' );
function mbt_recommend_bulk_action_admin_notice() {
    if ( ! empty( $_REQUEST['bulk_mbt_recommend_posts'] ) ) {
        $posts_count = intval( $_REQUEST['bulk_mbt_recommend_posts'] );
        printf( '<div id="message" class="updated settings-error fade"><p>成功推荐 '.$posts_count.' 篇文章。</p></div>', $posts_count );
    }
}

add_action( 'admin_notices', 'mbt_unrecommend_bulk_action_admin_notice' );
function mbt_unrecommend_bulk_action_admin_notice() {
    if ( ! empty( $_REQUEST['bulk_mbt_unrecommend_posts'] ) ) {
        $posts_count = intval( $_REQUEST['bulk_mbt_unrecommend_posts'] );
        printf( '<div id="message" class="updated settings-error fade"><p>成功取消推荐 '.$posts_count.' 篇文章。</p></div>', $posts_count );
    }
}

add_action('publish_post', 'mbt_publish_post_gift' );
function mbt_publish_post_gift($post_ID){
  $ppost = get_post($post_ID);
  $gift = _MBT('post_tougao_gift');
  $tougao_gift = get_post_meta($post_ID,'tougao_gift',true);
  if(wp_is_erphpdown_active() && $ppost->post_status == 'publish' && $gift && !$tougao_gift){
    if($ppost->post_author){
      update_post_meta($post_ID,'tougao_gift',1);
      $erphp_aff_money = get_option('erphp_aff_money');
      if($erphp_aff_money && function_exists('addUserAff')){
        addUserAff($ppost->post_author, $gift);
      }else{
        addUserMoney($ppost->post_author, $gift);
      }
      if(function_exists('addUserMoneyLog')){
        addUserMoneyLog($ppost->post_author, $gift, __('投稿奖励','mobantu'));
      }
    }
  }
}

add_filter('views_edit-post', 'MBThemes_custom_post_views');
function MBThemes_custom_post_views($views) {
    if( is_admin() ) {
        $query_recommend = array(
            'post_type'   => 'post',
            'meta_key' => 'down_recommend',
            'meta_value' => '1'
        );
        $result_recommend = new WP_Query($query_recommend);
        $class_recommend = (isset($_GET['down_recommend']) && $_GET['down_recommend'] == '0') ? ' class="current"' : '';
        $views['down_recommend'] = sprintf('<a href="%s"'. $class_recommend .'>'. '推荐' .' <span class="count">（%d）</span></a>', admin_url('edit.php?post_status=all&post_type=post&down_recommend=1'), $result_recommend->found_posts); 
    }
    return $views;
}

add_filter( 'parse_query', 'MBThemes_posts_filter' );
function MBThemes_posts_filter( $query ){
    global $pagenow;
    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if ( 'post' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['down_recommend']) && $_GET['down_recommend'] != '') {
        $query->query_vars['meta_key'] = 'down_recommend';
        $query->query_vars['meta_value'] = $_GET['down_recommend'];
    }
}

function erphp_faka_query_callback(){
  session_start();
    global $wpdb;
    $status = 0; $faka = '';
    if(isset($_POST['nonce']) && $_POST['nonce'] && $_POST['nonce'] == $_SESSION['quka_security_nonce']){
      $ice_num = esc_sql($_POST['order']);
      if(is_email($ice_num)){
          $results2 = $wpdb->get_results("select ice_num from $wpdb->icemoney where ice_data like '".esc_sql($ice_num)."|%' and ice_success=1 order by ice_time desc");
          if($results2){
              $faka .= '<h3>'.__('您查询的卡密','mobantu').'</h3>';
              foreach($results2 as $result2){
                  $results = $wpdb->get_results("select * from $wpdb->erphpact where ice_num = '".$result2->ice_num."'");
                  if($results){
                      foreach($results as $result){
                          $pp = get_post($result->pid);
                          $faka .= '<div class="result"><a href="'.get_permalink($pp->ID).'" target="_blank">'.$pp->post_title.'</a>：'.$result->num.'</div>';
                      }
                  }
              }
              $status = 200;
          }
      }else{
          $results = $wpdb->get_results("select * from $wpdb->erphpact where ice_num = '".esc_sql($ice_num)."'");
          if($results){
              $faka .= '<h3>'.__('您查询的卡密','mobantu').'</h3>';
              foreach($results as $result){
                  $faka .= '<div class="result">'.$result->num.'</div>';
              }
              $status = 200;
          }
      }
    }else{
        unset($_SESSION['quka_security_nonce']);
    }

    $result = array(
        'status' => $status,
        'faka' => $faka
    );

    header('Content-type: application/json');
    echo json_encode($result);
    exit;
}
add_action( 'wp_ajax_erphp_faka_query', 'erphp_faka_query_callback');
add_action( 'wp_ajax_nopriv_erphp_faka_query', 'erphp_faka_query_callback');

function modown_gallery_pass_callback(){
  $status = 0;
  $key = esc_sql($_POST['pk']);
  $pass = esc_sql($_POST['pass']);
  $post_id = intval($_POST['post_id']);

  $erphpdown_downkey = get_option('erphpdown_downkey');
  $erphpdown_private_downkey = get_option('erphpdown_private_downkey');
  if(!$erphpdown_private_downkey){
    update_option('erphpdown_private_downkey',wp_generate_password(64, true));
    $erphpdown_private_downkey = get_option('erphpdown_private_downkey');
  }
  $cypher = new ErphpCrypt(ErphpCrypt::CRYPT_MODE_HEXADECIMAL, ErphpCrypt::CRYPT_HASH_SHA1);
  $cypher->Key = md5($erphpdown_private_downkey.$erphpdown_downkey);
  $ps = $cypher->decrypt($key);

  if($ps == $pass){
    $_COOKIE['post_gallery_'.$post_id] = md5($erphpdown_private_downkey.$post_id.$erphpdown_downkey.$ps);
    setcookie('post_gallery_'.$post_id,md5($erphpdown_private_downkey.$post_id.$erphpdown_downkey.$ps),0,'/');
    $status = 200;
  }

  $result = array(
    'status' => $status
  );

  header('Content-type: application/json');
  echo json_encode($result);
  exit;
}
add_action( 'wp_ajax_gallery_pass', 'modown_gallery_pass_callback');
add_action( 'wp_ajax_nopriv_gallery_pass', 'modown_gallery_pass_callback');


function MBThemes_editor_settings($args = array()){
  if(_MBT('tougao_upload')){
    $allow_img = isset($args['allow_img']) && $args['allow_img'] ? 1 : 0;
  }else{
    $allow_img = 0;
  }
  return array(
      'textarea_name' => $args['textarea_name'],
      'media_buttons' => false,
      'quicktags' => true,
      'tinymce' => array(
          'statusbar' => false,
          'height'        => isset($args['height']) ? $args['height'] : 120,
          'toolbar1' => 'formatselect,bold,italic,underline,blockquote,forecolor,alignleft,aligncenter,alignright,link,unlink,bullist,numlist'.($allow_img?',TougaoImg':',image').',media,button_erphpdown',
          'toolbar2' => '',
          'toolbar3' => ''
      )
  );
}

add_filter( 'mce_external_plugins', 'MBThemes_tougao_mce_plugin');
function MBThemes_tougao_mce_plugin($plugin_array){
    $plugin_array['TougaoImg'] = THEME_URI . '/static/js/tougaoImg.js';
    return $plugin_array;
}

add_action('wp_ajax_tougao_img_upload', 'MBThemes_tougao_img_upload');
function MBThemes_tougao_img_upload(){
    $res = array();
    
    $user =  wp_get_current_user();
    if($user->ID){
        $upfile = $_FILES['upfile'];
        $upload_overrides = array('test_form' => false);
        $file_return = wp_handle_upload($upfile, $upload_overrides);

        if ($file_return && !isset($file_return['error'])) {
            // 保存到媒体库
            $attachment = array(
                'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $file_return['file'] ) ),
                'post_mime_type' => $file_return['type'],
            );
            $attach_id = wp_insert_attachment($attachment, $file_return['file']);
            $attach_data = MBThemes_generate_attachment_metadata($attach_id, $file_return['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);
            $res['result'] = 0;
            $file_return['alt'] = preg_replace( '/\.[^.]+$/', '', basename( $file_return['file'] ) );
            $res['image'] = $file_return;
        } else {
            $res['result'] = 1;
        }
    } else {
        $res['result'] = 2;
    }
    echo json_encode($res);
    exit;
}

function MBThemes_generate_attachment_metadata($attachment_id, $file) {
    $attachment = get_post ( $attachment_id );
    $metadata = array ();
    if (!function_exists('file_is_displayable_image')) include( ABSPATH . 'wp-admin/includes/image.php' );

    if (preg_match ( '!^image/!', get_post_mime_type ( $attachment ) ) && file_is_displayable_image ( $file )) {
        $imagesize = getimagesize ( $file );
        $metadata ['width'] = $imagesize [0];
        $metadata ['height'] = $imagesize [1];
        list ( $uwidth, $uheight ) = wp_constrain_dimensions ( $metadata ['width'], $metadata ['height'], 128, 96 );
        $metadata ['hwstring_small'] = "height='$uheight' width='$uwidth'";

        // Make the file path relative to the upload dir
        $metadata ['file'] = _wp_relative_upload_path ( $file );
        // work with some watermark plugin
        $metadata = apply_filters ( 'wp_generate_attachment_metadata', $metadata, $attachment_id );
    }
    return $metadata;
}

function MBThemes_login_page(){
    $html = '';$redirect_to = home_url();
    if(isset($_GET['redirect_to']) && $_GET['redirect_to']){
        $redirect_to = $_GET['redirect_to'];
    }
    if(_MBT('oauth_socialogin_weixin') || _MBT('oauth_socialogin_weibo') || _MBT('oauth_socialogin_qq') || _MBT('oauth_twitter') || _MBT('oauth_facebook') || _MBT('oauth_google') || _MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){
        $html .= '<div class="social-login sign-social"><div class="social-title"><span>'.__('社交账号快速登录','mobantu').'</span></div>';
        if(_MBT('oauth_socialogin_qq')){
            $html .= '<a href="'.home_url().'/oauth/socialogin?act=login&type=qq&rurl='.$redirect_to.'" rel="nofollow" class="login-qq"><i class="icon icon-qq"></i></a>';
        }
        if(_MBT('oauth_socialogin_weibo')){
            $html .= '<a href="'.home_url().'/oauth/socialogin?act=login&type=sina&rurl='.$redirect_to.'" rel="nofollow" class="login-weibo"><i class="icon icon-weibo"></i></a>';
        }
        if(_MBT('oauth_socialogin_weixin')){
            $html .= '<a href="'.home_url().'/oauth/socialogin?act=login&type=wx&rurl='.$redirect_to.'" rel="nofollow" class="login-weixin"><i class="icon icon-weixin"></i></a>';
        }
        if(_MBT('oauth_qq')){
            $html .='<a class="login-qq" href="'.home_url().'/oauth/qq?rurl='.$redirect_to.'" rel="nofollow"><i class="icon icon-qq"></i></a>';
        }
        if(_MBT('oauth_weibo')){
            $html .= '<a class="login-weibo" href="'.home_url().'/oauth/weibo?rurl='.$redirect_to.'" rel="nofollow"><i class="icon icon-weibo"></i></a>';
        }
        if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){
            if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){
                $html .= '<a class="login-weixin erphplogin-weixin-loader" data-return="'.$redirect_to.'" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='._MBT('oauth_weixinid_mobile').'&redirect_uri='.home_url().'/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>';
            }elseif(!modown_is_mobile() && _MBT('oauth_weixin')){
                $html .= '<a class="login-weixin erphplogin-weixin-loader" data-return="'.$redirect_to.'" href="https://open.weixin.qq.com/connect/qrconnect?appid='._MBT('oauth_weixinid').'&redirect_uri='.home_url().'/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>';
            }
        }
        if(_MBT('oauth_weixin_mp') && function_exists('ews_login') && (!modown_is_mobile() || (modown_is_mobile() && !_MBT('oauth_weixin_mobile')))){
            $html .= '<a class="login-weixin" href="'.add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php'))).'"><i class="icon icon-weixin"></i></a>';
        }
        if(_MBT('oauth_google')){
            $html .= '<a href="'.wp_login_url().'?loginSocial=google&redirect='.$redirect_to.'" rel="nofollow" class="login-google"><i class="icon icon-google"></i></a>';
        }
        if(_MBT('oauth_facebook')){
            $html .= '<a href="'.wp_login_url().'?loginSocial=facebook&redirect='.$redirect_to.'" rel="nofollow" class="login-facebook"><i class="icon icon-facebook"></i></a>';
        }
        if(_MBT('oauth_twitter')){
            $html .= '<a href="'.wp_login_url().'?loginSocial=twitter&redirect='.$redirect_to.'" rel="nofollow" class="login-twitter"><i class="icon icon-twitter"></i></a>';
        }
        $html .= '</div>';
    }
    return apply_filters('modown_login_page', $html);
}

function MBThemes_login_module(){
    $html = '';$redirect_to = MBThemes_selfURL();
    if(_MBT('oauth_socialogin_weixin') || _MBT('oauth_socialogin_weibo') || _MBT('oauth_socialogin_qq') || _MBT('oauth_twitter') || _MBT('oauth_facebook') || _MBT('oauth_google') || _MBT('oauth_qq') || _MBT('oauth_weibo') || (_MBT('oauth_weixin') || (_MBT('oauth_weixin_mobile') && modown_is_mobile())) || (_MBT('oauth_weixin_mp') && function_exists('ews_login'))){
        $html .= '<div class="sign-social"><h2>'.__('社交账号快速登录','mobantu').'</h2>';
        if(_MBT('oauth_socialogin_qq')){
            $html .= '<a href="'.home_url().'/oauth/socialogin?act=login&type=qq&rurl='.$redirect_to.'" rel="nofollow" class="login-qq"><i class="icon icon-qq"></i></a>';
        }
        if(_MBT('oauth_socialogin_weibo')){
            $html .= '<a href="'.home_url().'/oauth/socialogin?act=login&type=sina&rurl='.$redirect_to.'" rel="nofollow" class="login-weibo"><i class="icon icon-weibo"></i></a>';
        }
        if(_MBT('oauth_socialogin_weixin')){
            $html .= '<a href="'.home_url().'/oauth/socialogin?act=login&type=wx&rurl='.$redirect_to.'" rel="nofollow" class="login-weixin"><i class="icon icon-weixin"></i></a>';
        }
        if(_MBT('oauth_qq')){
            $html .='<a class="login-qq" href="'.home_url().'/oauth/qq?rurl='.$redirect_to.'" rel="nofollow"><i class="icon icon-qq"></i></a>';
        }
        if(_MBT('oauth_weibo')){
            $html .= '<a class="login-weibo" href="'.home_url().'/oauth/weibo?rurl='.$redirect_to.'" rel="nofollow"><i class="icon icon-weibo"></i></a>';
        }
        if(_MBT('oauth_weixin') || _MBT('oauth_weixin_mobile')){
            if(modown_is_mobile() && _MBT('oauth_weixin_mobile')){
                $html .= '<a class="login-weixin erphplogin-weixin-loader" data-return="'.$redirect_to.'" href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='._MBT('oauth_weixinid_mobile').'&redirect_uri='.home_url().'/oauth/weixin/&response_type=code&scope=snsapi_userinfo&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>';
            }elseif(!modown_is_mobile() && _MBT('oauth_weixin')){
                $html .= '<a class="login-weixin erphplogin-weixin-loader" data-return="'.$redirect_to.'" href="https://open.weixin.qq.com/connect/qrconnect?appid='._MBT('oauth_weixinid').'&redirect_uri='.home_url().'/oauth/weixin/&response_type=code&scope=snsapi_login&state=MBT_weixin_login#wechat_redirect" rel="nofollow"><i class="icon icon-weixin"></i></a>';
            }
        }
        if(_MBT('oauth_weixin_mp') && function_exists('ews_login') && (!modown_is_mobile() || (modown_is_mobile() && !_MBT('oauth_weixin_mobile')))){
            $html .= '<a class="login-weixin signmp-loader" href="javascript:;"><i class="icon icon-weixin"></i></a>';
        }
        if(_MBT('oauth_google')){
            $html .= '<a href="'.wp_login_url().'?loginSocial=google&redirect='.$redirect_to.'" rel="nofollow" class="login-google"><i class="icon icon-google"></i></a>';
        }
        if(_MBT('oauth_facebook')){
            $html .= '<a href="'.wp_login_url().'?loginSocial=facebook&redirect='.$redirect_to.'" rel="nofollow" class="login-facebook"><i class="icon icon-facebook"></i></a>';
        }
        if(_MBT('oauth_twitter')){
            $html .= '<a href="'.wp_login_url().'?loginSocial=twitter&redirect='.$redirect_to.'" rel="nofollow" class="login-twitter"><i class="icon icon-twitter"></i></a>';
        }
        $html .= '</div>';
    }
    return apply_filters('modown_login_module', $html);
}

function MBThemes_term_post_count( $taxonomy = 'category', $term = '', $args = [] ){
    if ( !$term )
        return false;

    if ( $term !== 'all' ) {
        if ( !is_array( $term ) ) {
            $term = filter_var(       $term, FILTER_VALIDATE_INT );
        } else {
            $term = filter_var_array( $term, FILTER_VALIDATE_INT );
        }
    }

    if ( $taxonomy !== 'category' ) {
        $taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
        if ( !taxonomy_exists( $taxonomy ) )
            return false;
    }

    if ( $args ) {
        if ( !is_array ) 
            return false;
    }

    $defaults = [
        'posts_per_page' => 1,
        'fields'         => 'ids'
    ];

    if ( $term !== 'all' ) {
        $defaults['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'terms'    => $term
            ]
        ];
    }
    $combined_args = wp_parse_args( $args, $defaults );
    $q = new WP_Query( $combined_args );

    return $q->found_posts;
}

function MBThemes_post_down_position(){
  global $post;
  $category = get_the_category();
  $dp = _MBT('down_position');
  foreach ($category as $cat) {
    $down_position = get_term_meta($cat->term_id,'down_position',true);
    if($down_position && $down_position != 'default' ){
        $dp = $down_position;
        break;
    }
  }
  return $dp;
}

function MBThemes_erphpdown_price($pid){
  $price = get_post_meta($pid, 'down_price', true);
  $price_type=get_post_meta($pid, 'down_price_type', true);
  $down_tuan=get_post_meta($pid, 'down_tuan', true);
  $down_tuan_price=get_post_meta($pid, 'down_tuan_price', true);
  if($down_tuan && function_exists('erphpdown_tuan_install')){
    $price = $down_tuan_price;
  }else{
    if($price_type){
      $urls=get_post_meta($pid, 'down_urls', true);
      if($urls && is_array($urls)){
        $price_arr=array();
        $cnt = count($urls['index']);
        if($cnt){
          for($i=0; $i<$cnt;$i++){
            if(isset($urls['price'][$i]) && $urls['price'][$i] > 0){
              $price_arr[] = $urls['price'][$i];
            }
          }
          sort($price_arr);
          if(count($price_arr) >= 1){
            $price = $price_arr[0];
          }else{
            $price = 0;
          }
        }
        
      }
    }
  }
  return $price;
}

function MBThemes_category_role(){
  $cat_ID = get_query_var('cat');
  $vip_see = get_term_meta($cat_ID,'vip_see',true);
  $userType = 0;

  if($vip_see){
    if(function_exists('getUsreMemberType')){
      $userType=getUsreMemberType();
    }
    if(!$userType){
      return true;
    }
  }
  return false;
}

function MBThemes_page_role(){
  global $post;
  $vip_see = get_post_meta($post->ID,'vip_see',true);
  $userType = 0;
  if(function_exists('getUsreMemberType')){
    $userType=getUsreMemberType();
  }
  if($vip_see){
    if($userType){
      if($userType < $vip_see){
        return true;
      }
    }else{
      return true;
    }
  }
  return false;
}

function MBThemes_single_role(){
  global $post;
  $categories = get_the_category();
  $vip_see = 0;
  $userType = 0;

  if($categories) {
    foreach($categories as $cate){
      $vip_see = get_term_meta($cate->term_id,'vip_see',true);
      if($vip_see){
        break;
      }
    }
  }

  if($vip_see){
    if(function_exists('getUsreMemberType')){
      $userType=getUsreMemberType();
    }
    if(!$userType){
      return true;
    }
  }

  return false;
}

function MBThemes_single_sidebar(){
  global $post;
  $nosidebar = get_post_meta($post->ID,'nosidebar',true);
  if($nosidebar){
    return '1';
  }else{
    $categories = get_the_category();
    $cnosidebar = 0;
    if($categories) {
      foreach($categories as $cate){
        $cnosidebar = get_term_meta($cate->term_id,'nosidebar',true);
        if($cnosidebar){
          break;
        }
      }
    }
    return $cnosidebar;
  }
  return '0';
}

function MBThemes_search_word_do(){
  global $wpdb;
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $sss = get_query_var('s');
  if($paged == '1' && $sss){
    $wpdb->insert( $wpdb->prefix ."search_words", array(
        'keyword' => esc_sql($sss),
        'user_ip' => mbt_get_ip(),
        'create_time' => date("Y-m-d H:i:s")
    ) );
  }
}

//add_action('admin_menu', '_mbt_search_word_menu');
function _mbt_search_word_menu() {
    add_management_page('搜索记录', '搜索记录', 'activate_plugins', 'mbt_search_word_list', 'mbt_search_word_list');
}

function mbt_search_word_list(){
  global $wpdb;
  if(isset($_POST['action'])){
    if($_POST['action'] == '7'){
      $wpdb->query("delete from ".$wpdb->prefix . "search_words WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '1'){
      $wpdb->query("delete from ".$wpdb->prefix . "search_words WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '3'){
      $wpdb->query("delete from ".$wpdb->prefix . "search_words WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 3 DAY)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '30'){
      $wpdb->query("delete from ".$wpdb->prefix . "search_words WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '180'){
      $wpdb->query("delete from ".$wpdb->prefix . "search_words WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == '365'){
      $wpdb->query("delete from ".$wpdb->prefix . "search_words WHERE create_time < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }elseif($_POST['action'] == 'all'){
      $wpdb->query("delete from ".$wpdb->prefix . "search_words");
      echo '<div class="updated settings-error"><p>清理成功！</p></div>';
    }
  }

  if(isset($_POST['delid']) && $_POST['delid'] > 0){
    $wpdb->query("delete from ".$wpdb->prefix . "search_words where id=".$_POST['delid']);
  }
  $total   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix."search_words");
  $perpage = 20;
  $pages = ceil($total / $perpage);
  $page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
  $offset = $perpage*($page-1);
  $list = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."search_words ORDER BY create_time DESC limit $offset,$perpage");
?>
<style>
.wp-list-table td{display: table-cell !important;word-break: break-all;}
@media (max-width: 768px){
  .wp-list-table td.pc, .wp-list-table th.pc{display: none !important;}
}
</style>
<div class="wrap">
  <h2>搜索记录</h2>
  <table class="wp-list-table widefat fixed striped posts">
    <thead>
        <th>搜索词</th>
        <th>时间</th>  
        <th class="pc">IP</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
  <?php
    if($list) {
      foreach($list as $value){
        echo "<tr id='order".$value->id."'>";
        echo "<td>$value->keyword</td>";
        echo "<td>$value->create_time</td>";
        echo "<td>$value->user_ip</td>";
        echo '<td><form method="post"><input type="hidden" name="delid" value="'.$value->id.'"><input type="submit" class="button" value="'.__('删除','mobantu').'"></form></td>';
        echo "</tr>";
      }
    }
    else{
      echo '<tr><td colspan="4" align="center"><strong>暂无记录</strong></td></tr>';
    }
  ?>
  </tbody>
  </table>
    <?php mbt_pagination($total,$perpage);?>
    <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="1"  />
    <input type="submit" value="清理一天之前所有记录" class="button-primary">
  </form>
    <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="3"  />
    <input type="submit" value="清理三天之前所有记录" class="button-primary">
  </form>
    <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="7"  />
    <input type="submit" value="清理一周之前所有记录" class="button-primary">
  </form>
  <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="30"  />
    <input type="submit" value="清理一月之前所有记录" class="button-primary">
  </form>
  <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="180"  />
    <input type="submit" value="清理半年之前所有记录" class="button-primary">
  </form>
  <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="365"  />
    <input type="submit" value="清理一年之前所有记录" class="button-primary">
  </form>
  <form action="" method="post" style="display:inline-block;">
    <input type="hidden" name="action" value="all"  />
    <input type="submit" value="清理所有记录" class="button-primary">
  </form>
</div>
<?php
}


function admin_ticket_user_callback(){
  date_default_timezone_set('Asia/Shanghai'); 
  $error = 1;$msg = '发送失败';

  if(current_user_can('administrator') && _MBT('ticket')){
    if(isset($_POST['uid'])){
      $uid = esc_sql($_POST['uid']);
      $content = esc_sql($_POST['content']);
      $number = createTicketNum();
      $email = get_user_by("ID",$uid)->user_email;
      createNewTicketByAdmin($uid,'11',$number,wp_trim_words( MBThemes_strip_tags( $content ), "50","..."),$content,'',$email);
      _mbt_add_notice($uid, __('您好，您有一条管理员发来的私信。','mobantu').'<a href="' .add_query_arg(array("action"=>"ticket","id"=>$number),get_permalink(MBThemes_page("template/user.php"))) . '" target="_blank">'.__('查看详情','mobantu').'</a>', 'ticket_msg', $number);
      if($email){
        $to = $email;
        $subject = '[' . get_option('blogname') . ']'.__('您有一条管理员发来的私信','mobantu');
        $message = '' . "\r\n" . '    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"><tbody><tr><td><table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse"><tbody><tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td width="73" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:5px 0 0 0"></td><td valign="top" style="border-top:1px solid #d9d9d9"><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="font-size:18px;line-height:18px;color:#444;font-family:Microsoft Yahei">Hi, '.get_user_by("ID",$uid)->user_login.'<br><br><br></div><div style="font-size:14px;line-height:22px;color:#444;font-weight:bold;font-family:Microsoft Yahei">'.__('您有一条管理员发来的私信','mobantu').'</div><div style="font-size:14px;line-height:10px"><br><br></div><div style="font-size:14px;line-height:22px;color:#5DB408;font-weight:bold;font-family:Microsoft Yahei">'.__('私信ID：','mobantu').'</div><div style="font-size:14px;line-height:10px"><br></div><div style="font-size:14px;line-height:22px;color:#666;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp; ' . $number . '</div><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="text-align:center"><a href="' .add_query_arg(array("action"=>"ticket","id"=>$number),get_permalink(MBThemes_page("template/user.php"))) . '" target="_blank" style="text-decoration:none;color:#fff;display:inline-block;line-height:44px;font-size:18px;background-color:#ff5f33;border-radius:3px;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp;'.__('查看私信','mobantu').'&nbsp; &nbsp;&nbsp; &nbsp;</a><br><br></div></td><td width="65" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-radius:0 5px 0 0"></td></tr><tr><td style="border-left:1px solid #d9d9d9">&nbsp;</td><td align="left" valign="top" style="color:#999"><div style="font-size:8px;line-height:14px"><br><br></div><div style="min-height:1px;font-size:1px;line-height:1px;background-color:#e0e0e0">&nbsp;</div><div style="font-size:12px;line-height:20px;width:425px;font-family:Microsoft Yahei"><br>'.__('此邮件由系统自动发出，请勿回复！','mobantu').'</div></td><td style="border-right:1px solid #d9d9d9">&nbsp;</td></tr><tr><td colspan="3" style="border-bottom:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:0 0 5px 5px"><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr><tr><td><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr></tbody></table>';
        $headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
      }
      $error = 0;$msg = '发送成功';
    }elseif(isset($_POST['uids'])){
      $uids = esc_sql($_POST['uids']);
      $content = esc_sql($_POST['content']);

      foreach(explode(',',$uids) as $uid){
        $number = createTicketNum();
        $email = get_user_by("ID",$uid)->user_email;
        createNewTicketByAdmin($uid,'11',$number,wp_trim_words( MBThemes_strip_tags( $content ), "50","..."),$content,'',$email);
        _mbt_add_notice($uid, __('您好，您有一条管理员发来的私信。','mobantu').'<a href="' .add_query_arg(array("action"=>"ticket","id"=>$number),get_permalink(MBThemes_page("template/user.php"))) . '" target="_blank">'.__('查看详情','mobantu').'</a>', 'ticket_msg', $number);
        if($email){
          $to = $email;
          $subject = '[' . get_option('blogname') . ']'.__('您有一条管理员发来的私信','mobantu');
          $message = '' . "\r\n" . '    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"><tbody><tr><td><table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse"><tbody><tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td width="73" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:5px 0 0 0"></td><td valign="top" style="border-top:1px solid #d9d9d9"><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="font-size:18px;line-height:18px;color:#444;font-family:Microsoft Yahei">Hi, '.get_user_by("ID",$uid)->user_login.'<br><br><br></div><div style="font-size:14px;line-height:22px;color:#444;font-weight:bold;font-family:Microsoft Yahei">'.__('您有一条管理员发来的私信','mobantu').'</div><div style="font-size:14px;line-height:10px"><br><br></div><div style="font-size:14px;line-height:22px;color:#5DB408;font-weight:bold;font-family:Microsoft Yahei">'.__('私信ID：','mobantu').'</div><div style="font-size:14px;line-height:10px"><br></div><div style="font-size:14px;line-height:22px;color:#666;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp; ' . $number . '</div><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="text-align:center"><a href="' .add_query_arg(array("action"=>"ticket","id"=>$number),get_permalink(MBThemes_page("template/user.php"))) . '" target="_blank" style="text-decoration:none;color:#fff;display:inline-block;line-height:44px;font-size:18px;background-color:#ff5f33;border-radius:3px;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp;'.__('查看私信','mobantu').'&nbsp; &nbsp;&nbsp; &nbsp;</a><br><br></div></td><td width="65" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-radius:0 5px 0 0"></td></tr><tr><td style="border-left:1px solid #d9d9d9">&nbsp;</td><td align="left" valign="top" style="color:#999"><div style="font-size:8px;line-height:14px"><br><br></div><div style="min-height:1px;font-size:1px;line-height:1px;background-color:#e0e0e0">&nbsp;</div><div style="font-size:12px;line-height:20px;width:425px;font-family:Microsoft Yahei"><br>'.__('此邮件由系统自动发出，请勿回复！','mobantu').'</div></td><td style="border-right:1px solid #d9d9d9">&nbsp;</td></tr><tr><td colspan="3" style="border-bottom:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:0 0 5px 5px"><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr><tr><td><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr></tbody></table>';
          $headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
          wp_mail($to, $subject, $message, $headers);
        }
      }
      $error = 0;$msg = '发送成功';
    }
  }else{
    $msg = '权限不足';
  }

  $result = array(
    'msg' => $msg,
    'error' => $error
  );

  header('Content-type: application/json');
  echo json_encode($result);
  exit;
}
add_action( 'wp_ajax_admin_ticket_user', 'admin_ticket_user_callback');