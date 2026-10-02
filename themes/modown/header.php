<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
  <meta http-equiv="Cache-Control" content="no-siteapp">
  <?php if(!_MBT('seo')){?>
  <title><?php wp_title( _MBT('delimiter','-'), true, 'right' ); ?></title>
  <?php MBThemes_keywords();?>
  <?php MBThemes_description();?>
  <?php }?>
  <link rel="shortcut icon" href="<?php echo _MBT('favicon')?>">
  <?php wp_head();?>
  <?php echo _MBT('header_code')?>
  <script>var $ = jQuery;</script>
  <?php get_template_part("inc/skin");?>
</head>
<body <?php body_class(); ?>>
<header class="header">
  <div class="container clearfix">

    <?php if(_MBT('header_logo_position')){?>
    <ul class="nav-left">
      <li class="nav-button"><a href="javascript:;" class="nav-loader"><i class="icon icon-menu"></i></a></li>
      <?php if(!_MBT('header_search')){?><li class="nav-search"><a href="javascript:;" class="search-loader" title="<?php _e('搜索','mobantu');?>"><i class="icon icon-search"></i></a></li><?php }?>
    </ul>
    <?php }?>

    <?php 
      if(_MBT('theme_night')){
        $logo = _MBT('logo');
        $logo_mobile = _MBT('logo_mobile');
        if(isset($_COOKIE['mbt_theme_night'])){
          if($_COOKIE['mbt_theme_night'] == '1'){
            $logo = _MBT('logo_night')?_MBT('logo_night'):_MBT('logo');
            $logo_mobile = _MBT('logo_mobile_night')?_MBT('logo_mobile_night'):_MBT('logo_mobile');
          }
        }elseif(_MBT('theme_night_default')){
          $logo = _MBT('logo_night')?_MBT('logo_night'):_MBT('logo');
          $logo_mobile = _MBT('logo_mobile_night')?_MBT('logo_mobile_night'):_MBT('logo_mobile');
        }elseif(_MBT('theme_night_auto')){
          $time = intval(date("Hi"));
          if ($time < 730 || $time > 1930) {
            $logo = _MBT('logo_night')?_MBT('logo_night'):_MBT('logo');
            $logo_mobile = _MBT('logo_mobile_night')?_MBT('logo_mobile_night'):_MBT('logo_mobile');
          }
        }
      }else{
        $logo = _MBT('logo');
        $logo_mobile = _MBT('logo_mobile');
      }
    ?>
    <div class="logo<?php if(_MBT('logo_scan')) echo ' scaning';?>">
      <a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><img src="<?php if(modown_is_mobile() && $logo_mobile){ echo $logo_mobile;}else{ echo $logo?$logo:(THEME_URI.'/static/img/logo.png');}?>" data-light="<?php if(modown_is_mobile() && _MBT('logo_mobile')){ echo _MBT('logo_mobile');}else{ echo _MBT('logo')?_MBT('logo'):(THEME_URI.'/static/img/logo.png');}?>" data-dark="<?php if(modown_is_mobile() && _MBT('logo_mobile_night')){ echo _MBT('logo_mobile_night');}else{ echo _MBT('logo_night')?_MBT('logo_night'):_MBT('logo');}?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" /></a>
    </div>

    <ul class="nav-main">
      <?php echo str_replace("</ul></div>", "", preg_replace("{<div[^>]*><ul[^>]*>}", "", wp_nav_menu(array('theme_location' => 'main', 'echo' => false, 'fallback_cb'=> 'wp_menu_none')) )); ?>
    </ul>

    <?php do_action("modown_header_nav");?>

    <ul class="nav-right">
      <?php do_action("modown_header_nav_right");?>
      <?php if(_MBT('header_vip')){ if(is_user_logged_in() || !_MBT('hide_user_all')){?>
      <li class="nav-vip">
        <a href="<?php echo get_permalink(MBThemes_page("template/vip.php"));?>"><i class="icon <?php if(_MBT('header_vip_icon') == '1') echo 'icon-vip';elseif(_MBT('header_vip_icon') == '2') echo 'icon-vip2';else echo 'icon-vip-s';?>"></i></a>
      </li>
      <?php }}?>
      <?php if(_MBT('header_tougao')){
        if(is_user_logged_in() || !_MBT('hide_user_all')){
      ?>
      <li class="nav-tougao">
        <a href="<?php echo get_permalink(MBThemes_page("template/tougao.php"));?>" title="<?php _e('投稿','mobantu');?>"><i class="icon icon-tougao"></i></a>
      </li>
      <?php }}?>
      <?php if ( class_exists( 'WooCommerce', false ) && _MBT('header_cart')) {global $woocommerce;$items = $woocommerce->cart->get_cart();$wc_count = count($items);?>
      <li class="nav-cart">
        <a href="<?php echo wc_get_cart_url();?>" title="<?php _e('购物车','mobantu');?>"><i class="icon icon-cart"></i><?php if($wc_count){?><span><?php echo $wc_count;?></span><?php }?></a>
      </li>
      <?php }?>
      <?php if(_MBT('header_notice') && is_user_logged_in()){global $current_user;?>
      <li class="nav-notice">
        <a href="javascript:;" class="notice-loader" title="<?php _e('通知','mobantu');?>"><i class="icon icon-bell"></i><?php 
          $notice_read_time = get_user_meta($current_user->ID,'notice_read_time',true);
          $user_registered = get_date_from_gmt($current_user->user_registered);
          if(!$notice_read_time){
            $notice_read_time = $user_registered;
          }
          $last_time = _mbt_last_notice_time($current_user->ID);

          $notices = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."notices WHERE (user_id=".$current_user->ID." or user_id=0) and create_time > '".$user_registered."' order by create_time DESC limit 0,20");

          if( (($notice_read_time && $last_time > $notice_read_time) || !$notice_read_time) && $notices){
            echo '<span class="header__notify"></span>';
          }
        ?></a>
        <div class="sub-menu">
          <div class="header__name"><?php _e('通知','mobantu');?><?php if($notices){?><span class="header__notify_read"><i class="icon icon-clear"></i> <?php _e('全部已读','mobantu');?></span><?php }?></div>
          <div class="header__scroll">
            <div class="notify">
              <?php 
                if($notices){
                  foreach($notices as $notice){
              ?>
              <div class="notify__item<?php if($notice->create_time > $notice_read_time) echo ' noread';?>">
                <div class="notify__icon">
                  <svg ng-switch-default=""><use xlink:href="<?php echo THEME_URI;?>/static/img/sprite.svg#icon-v6-bubble"></use></svg>
                </div>
                <div class="notify__wrap">
                  <div class="notify__content"><?php echo $notice->message;?></div>
                  <div class="notify__time"><?php echo $notice->create_time;?></div>
                </div>
              </div>
              <?php } }?>
            </div>
          </div>
        </div>
      </li>
      <?php }?>
      <?php if(!_MBT('header_search')){?>
      <li class="nav-search">
        <a href="javascript:;" class="search-loader" title="<?php _e('搜索','mobantu');?>"><i class="icon icon-search"></i></a>
      </li>
      <?php }?>
      <?php if(!is_user_logged_in()){ 
        if(!_MBT('hide_user_all') && !_MBT('header_login')){
      ?>
        <li class="nav-login no"><a href="<?php echo add_query_arg('redirect_to',MBThemes_selfURL(),get_permalink(MBThemes_page("template/login.php")));?>" class="signin-loader" rel="nofollow"><i class="icon icon-user"></i><span><?php _e('登录','mobantu');?></span></a><?php if(!_MBT('register')){?><b class="nav-line"></b><a href="<?php echo add_query_arg(array("action"=>"register","redirect_to"=>MBThemes_selfURL()),get_permalink(MBThemes_page("template/login.php")));?>" class="reg signup-loader" rel="nofollow"><span><?php _e('注册','mobantu');?></span></a><?php }?></li>
        <?php }
      }else{ global $current_user;?>
      <li class="nav-login yes"><a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>" title="<?php _e('个人中心','mobantu');?>"><?php echo get_avatar($current_user->ID,36);?><i class="icon icon-user"></i><?php if(ERPHPDOWN_IS_ACTIVE){ if(getUsreMemberTypeById($current_user->ID)) echo '<span class="vip"></span>';elseif(function_exists('getUsreMemberCatStatus') && getUsreMemberCatStatus()) echo '<span class="vip"></span>'; }?></a>
        <ul class="sub-menu">
          <?php if(ERPHPDOWN_IS_ACTIVE){ $okMoney = erphpGetUserOkMoney(); $userTypeId=getUsreMemberType();$erphp_life_days    = get_option('erphp_life_days');
            $userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$current_user->ID);
          ?>
          <li class="first">
            <div class="user-info">
              <a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>" title="<?php _e('个人中心','mobantu');?>"><?php echo get_avatar($current_user->ID,36);?><?php  if(getUsreMemberTypeById($current_user->ID)) echo '<span class="vip"></span>'; elseif(function_exists('getUsreMemberCatStatus') && getUsreMemberCatStatus()) echo '<span class="vip"></span>'; ?></a>
              <div class="name"><?php echo '<div class="nickname">'.$current_user->display_name.'</div>';echo MBThemes_erphpdown_vipname();?></div>
            </div>
            <div class="user-money clearfix">
              <div class="money-left">
                <?php 
                $erphp_aff_money = get_option('erphp_aff_money');
                if($erphp_aff_money && function_exists('erphpGetUserOkAff')){
                  $okAffMoney = erphpGetUserOkAff();
                  echo __('站内余额','mobantu').' '.sprintf("%.2f",$okMoney).' &nbsp;&nbsp;'.__('推广余额','mobantu').' '.sprintf("%.2f",$okAffMoney);
                }else{
                  echo __('余额','mobantu').' '.sprintf("%.2f",$okMoney);
                }
                ?>
                <t></t></div>
            </div>
            <?php if(!_MBT('vip_hidden')){?>
            <div class="user-vip clearfix">
              <div class="vip-left">
              <?php if($userTypeId>0&&$userTypeId<=11){
                MBThemes_erphpdown_viphtml();
                if(function_exists('getUsreMemberCatStatus') && getUsreMemberCatStatus()){
                  $cattype = 0;$cathtml = '';
                  $catlist = $wpdb->get_results("select * from  ".$wpdb->icecat." where userType > 0 and ice_user_id=".$current_user->ID);
                  if($catlist){
                    foreach($catlist as $value){
                      $userCat=getUsreMemberCat($value->ice_cat_id);
                      if($userCat){
                        $cattype = $userCat;
                        $vipname = get_term_meta($value->ice_cat_id,'cat_vip_name',true);
                        if($value->userType >= 10){
                          $cathtml .= $vipname.' ('.__('永久尊享','mobantu').')<br>';
                        }else{
                          $cathtml .= $vipname.' ('.$value->endTime.'到期)<br>';
                        }
                      }
                    }
                    $cathtml = rtrim($cathtml,'<br>');
                  }
                  if($cattype){
                    echo $cathtml;
                  }
                }
              }elseif(function_exists('getUsreMemberCatStatus') && getUsreMemberCatStatus()){
                $cattype = 0;$cathtml = '';
                $catlist = $wpdb->get_results("select * from  ".$wpdb->icecat." where userType > 0 and ice_user_id=".$current_user->ID);
                if($catlist){
                  foreach($catlist as $value){
                    $userCat=getUsreMemberCat($value->ice_cat_id);
                    if($userCat){
                      $cattype = $userCat;
                      $vipname = get_term_meta($value->ice_cat_id,'cat_vip_name',true);
                      if($value->userType >= 10){
                        $cathtml .= $vipname.' (永久)<br>';
                      }else{
                        $cathtml .= $vipname.' ('.$value->endTime.'到期)<br>';
                      }
                    }
                  }
                  $cathtml = rtrim($cathtml,'<br>');
                }
                if($cattype){
                  echo $cathtml;
                }
              }else{
                echo __('普通用户','mobantu').'<v onclick="javascript:location.href=\''.add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php"))).'\';">'.__('升级VIP','mobantu').'</v>';
                if(_MBT('header_vip_desc')) echo '<div class="down-left">'._MBT('header_vip_desc').'</div>';
              }
              ?>
              </div>
            </div>
            <?php }?>
            <?php if ( class_exists( 'WooCommerce', false ) && _MBT('header_cart')) {?>
            <div class="user-cart clearfix">
              <a href="<?php echo wc_get_page_permalink( 'myaccount' );?>" class="cart-left"><i class="icon icon-cart"></i><t><?php _e('我的购物','mobantu');?></t></a>
            </div>
            <?php }?>
          </li>
          <?php }else{?>
          <li class="first">
            <div class="user-info">
              <a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>" title="<?php _e('个人中心','mobantu');?>"><?php echo get_avatar($current_user->ID,36);?></a>
              <div class="name"><?php echo '<div class="nickname">'.$current_user->display_name.'</div><d>'.__('普通用户','mobantu').'</d>';?></div>
            </div>
            <?php if ( class_exists( 'WooCommerce', false ) && _MBT('header_cart')) {?>
            <div class="user-cart clearfix">
              <a href="<?php echo wc_get_page_permalink( 'myaccount' );?>" class="cart-left"><i class="icon icon-cart"></i><t><?php _e('我的购物','mobantu');?></t></a>
            </div>
            <?php }?>
          </li>
          <?php }?>

          <?php if(ERPHPDOWN_IS_ACTIVE){?>
            <?php if(current_user_can(_MBT('admin_capability'))){?>
            <li class="item"><a href="<?php echo admin_url();?>"><i class="icon icon-setting"></i><br><?php _e('后台','mobantu');?></a></li>
            <?php }?>
            <li class="item"><a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>"><i class="icon icon-money"></i><br><?php _e('充值','mobantu');?></a></li>
            <li class="item"><a href="<?php echo add_query_arg('action','order',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-order2"></i><br><?php _e('购买','mobantu');?></a></li>
            <?php if(!current_user_can(_MBT('admin_capability'))){?>
            <li class="item"><a href="<?php echo add_query_arg('action','info',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-info"></i><br><?php _e('资料','mobantu');?></a></li>
            <?php }?>
          <?php }else{?>
            <?php if(current_user_can(_MBT('admin_capability'))){?>
            <li class="item"><a href="<?php echo admin_url();?>"><i class="icon icon-setting"></i><br><?php _e('后台','mobantu');?></a></li>
            <?php }else{?>
            <li class="item"><a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>"><i class="icon icon-info"></i><br><?php _e('资料','mobantu');?></a></li>
            <?php }?>
            <?php if(_MBT('post_collect')){?>
            <li class="item"><a href="<?php echo add_query_arg('action','collect',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-stars"></i><br><?php _e('收藏','mobantu');?></a></li>
            <?php }?>
            <li class="item"><a href="<?php echo add_query_arg('action','comment',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-comments"></i><br><?php _e('评论','mobantu');?></a></li>
          <?php }?>
          <li class="item"><a href="<?php echo add_query_arg('action','info',get_permalink(MBThemes_page("template/user.php")));?>"><i class="icon icon-lock"></i><br><?php _e('密码','mobantu');?></a></li>
          <li class="item"><a href="<?php echo wp_logout_url(MBThemes_selfURL());?>"><i class="icon icon-signout"></i><br><?php _e('退出','mobantu');?></a></li>
        </ul>
      </li>
      <?php }?>
      <?php if(!_MBT('header_logo_position')){?>
      <li class="nav-button"><a href="javascript:;" class="nav-loader"><i class="icon icon-menu"></i></a></li>
      <?php }?>
    </ul>

  </div>
</header>

<?php if(_MBT("header_topbar") && !((wp_is_mobile() || modown_is_mobile()) && _MBT("header_topbar_wap"))){?>
<div class="topbar">
  <div class="container clearfix">
    <nav class="topbar-nav">
      <?php $menuParameters = array('theme_location' => 'topbar',  'container' => false,'echo' => false,'items_wrap' => '%3$s','depth' => 0,'fallback_cb'=> 'wp_menu_none'); echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );?>
    </nav>
    <?php if(_MBT("header_topbar_activitys")) get_template_part("module/activity");?>
  </div>
</div>
<?php }?>

<div class="search-wrap">
  <div class="container search-wrap-container">
    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form" method="get">
      <?php 
      if(_MBT('banner_cats')){
        $cats = explode(',', trim(_MBT('banner_cats')));
        if(count($cats)){
          echo '<div class="search-cat">'.__('所有分类','mobantu').'</div>';
        }
      }
      ?>
      <input autocomplete="off" class="search-input" name="s" placeholder="<?php _e('输入关键字回车','mobantu');?>" type="text">
      <button class="search-btn" type="submit"><i class="icon icon-search"></i><?php _e('搜索','mobantu');?></button>
      <i class="icon icon-close"></i>
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
    </form>
  </div>
</div>