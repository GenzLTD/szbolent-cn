<style>
  <?php 
  MBThemes_color(); 
  $list_column = _MBT('list_column');
  $timthumb_height = _MBT('timthumb_height');

  if(is_category()){
    $cat_ID = get_query_var('cat');
    $timthumb_height_cat = get_term_meta($cat_ID,'timthumb_height',true);
    if($timthumb_height_cat){
      $timthumb_height = $timthumb_height_cat;
    }

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
  /*elseif(is_tag()){
    $tag_slug = get_query_var('tag');
    $tagr = get_term_by('slug',$tag_slug,'post_tag');

    $tag_list_column = get_term_meta($tagr->term_id,'list_column',true);
    if($tag_list_column){
      $list_column = $tag_list_column;
    }
  }*/


  if($list_column == 'four-large'){
    if($timthumb_height && $timthumb_height != '200'){
    ?>
    @media (min-width: 1536px){
      .gd-large .grids:not(.relateds) .grid .img{height: <?php echo $timthumb_height;?>px;}
      .widget-postlist .hasimg li{padding-left: calc(<?php echo ($timthumb_height=="320")?"63":(63*320/$timthumb_height);?>px + 10px);}
      .widget-postlist .hasimg li .img{width:<?php echo ($timthumb_height=="320")?"63":(63*320/$timthumb_height);?>px;}
    }
    @media (max-width: 1535px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="320")?"285":(285*$timthumb_height/320);?>px;}
    }
    @media (max-width: 1230px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="320")?"232.5":(232.5*$timthumb_height/320);?>px;}
    }
    @media (max-width: 1024px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="320")?"285":(285*$timthumb_height/320);?>px;}
    }
    @media (max-width: 925px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="320")?"232.5":(232.5*$timthumb_height/320);?>px;}
    }
    @media (max-width: 768px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="320")?"285":(285*$timthumb_height/320);?>px;}
    }
    @media (max-width: 620px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="320")?"232.5":(232.5*$timthumb_height/320);?>px;}
    }
    @media (max-width: 480px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="320")?"180":(180*$timthumb_height/320);?>px;}
    }
    <?php
    }
  }elseif($list_column == 'five-mini'){
    if($timthumb_height && $timthumb_height != '144'){
    ?>
    .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    .gd-mini .widget-postlist .hasimg li{padding-left: calc(<?php echo ($timthumb_height=="228")?"63":(63*228/$timthumb_height);?>px + 10px);}
    .gd-mini .widget-postlist .hasimg li .img{width:<?php echo ($timthumb_height=="228")?"63":(63*228/$timthumb_height);?>px;}
    @media (max-width: 1230px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 1024px){
      .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 925px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 768px){
      .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 620px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 480px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"144":(144*$timthumb_height/228);?>px;}
    }
    <?php
    }
  }elseif($list_column == 'six-mini'){
    if($timthumb_height && $timthumb_height != '118'){
    ?>
    .gd-mini.mini-six .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    .gd-mini.mini-six .widget-postlist .hasimg li{padding-left: calc(<?php echo ($timthumb_height=="188")?"63":(63*188/$timthumb_height);?>px + 10px);}
    .gd-mini.mini-six .widget-postlist .hasimg li .img{width:<?php echo ($timthumb_height=="188")?"63":(63*188/$timthumb_height);?>px;}
    @media (max-width: 1230px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 1024px){
      .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 925px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 768px){
      .gd-mini .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 620px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"236.25":(236.25*$timthumb_height/228);?>px;}
    }
    @media (max-width: 480px){
      .gd-mini .grids .grid .img{height: <?php echo ($timthumb_height=="228")?"118":(118*$timthumb_height/228);?>px;}
    }
    <?php
    }
  }else{
    if($timthumb_height && $timthumb_height != '180'){
    ?>
    .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    .widget-postlist .hasimg li{padding-left: calc(<?php echo ($timthumb_height=="285")?"63":(63*285/$timthumb_height);?>px + 10px);}
    .widget-postlist .hasimg li .img{width:<?php echo ($timthumb_height=="285")?"63":(63*285/$timthumb_height);?>px;}
    @media (max-width: 1230px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
    }
    @media (max-width: 1024px){
      .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 925px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
    }
    @media (max-width: 768px){
      .grids .grid .img{height: <?php echo $timthumb_height;?>px;}
    }
    @media (max-width: 620px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285);?>px;}
    }
    @media (max-width: 480px){
      .grids .grid .img{height: <?php echo ($timthumb_height=="285")?"180":(180*$timthumb_height/285);?>px;}
    }
    <?php
    }
  }

  if(_MBT('thumbnail_auto')){
  ?>
    @media (max-width: 620px){
      .grids .grid .img, .single-related .grids .grid .img {height: auto !important;}
    }
  <?php
  }

  if(_MBT('thumbnail_type') && _MBT('thumbnail_type') != 'cover'){
  ?>
  .grids .grid .thumb, .lists .list .thumb, .mocat .lists .grid .thumb, .home-blogs ul li .thumb{object-fit: <?php echo _MBT('thumbnail_type');?>;}
  <?php
  }

  if(_MBT('post_box_thumbnail_type') && _MBT('post_box_thumbnail_type') != 'cover'){
  ?>
  .header-box-img img{object-fit: <?php echo _MBT('post_box_thumbnail_type');?>;}
  <?php
  }

  if(_MBT('theme_color_bg') != '#ffffff'){
  ?>
  body, .mocat, .mocat:nth-child(2n), .banner-slider{background-color: <?php echo _MBT('theme_color_bg');?> !important;}
  <?php
  }

  if(_MBT('theme_body_bg')){
  ?>
  .mocat, .banner-slider, .home-blogs{background: transparent !important;}
  body{background-image: url(<?php echo _MBT('theme_body_bg');?>);
    <?php if(_MBT('theme_body_bg_style') == 'no-repeat'){?>
      background-repeat: no-repeat;background-size: cover;background-position: center;
    <?php }elseif(_MBT('theme_body_bg_style') == 'top'){?>
      background-repeat: no-repeat;background-size: contain;background-position: top center;
    <?php }elseif(_MBT('theme_body_bg_style') == 'bottom'){?>
      background-repeat: no-repeat;background-size: contain;background-position: bottom center;
    <?php }else{?>
      background-repeat: repeat;
    <?php }?>
  }
  <?php
  }

  if(_MBT('home_cats_bg')){
    echo '.mocat, .home-blogs{padding: 30px 0;}.mocat:nth-child(2n){background: transparent}body.night .mocat:nth-child(2n){background: transparent}.mocat:last-child{padding-bottom: 20px}';
  }

  if(_MBT('header_txtbold')){
    echo '.nav-main{font-weight:600}';
  }

  if(_MBT('header_txtsize') && _MBT('header_txtsize') != '16'){
    echo '.nav-main{font-size:'._MBT('header_txtsize').'px}';
  }

  if(_MBT('header_type') == 'dark'){
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#fff';
  ?>
  .header, .topbar{background: #232323}
  .topbar{border-color: #343434}
  .nav-main > li, .nav-main > li > a, .nav-right a, .topbar, .topbar a{color:<?php echo $header_txtcolor;?>;}
  body.night .nav-main > li, body.night .nav-main > li > a, body.night .nav-right a{color:#999;}
  @media (max-width: 768px){
    .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
  }
  <?php
  }elseif(_MBT('header_type') == 'light'){
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#333';
  ?>
    .nav-main > li, .nav-main > li > a, .nav-right a{color:<?php echo $header_txtcolor;?>;}
    body.night .nav-main > li, body.night .nav-main > li > a, body.night .nav-right a{color:#999;}
    @media (max-width: 768px){
      .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
    }
  <?php
  }elseif(_MBT('header_type') == 'custom'){
    $header_bgcolor = _MBT('header_bgcolor');
    $header_txtcolor = _MBT('header_txtcolor')?_MBT('header_txtcolor'):'#333';

    $theme_color_custom = _MBT('theme_color_custom');
    $theme_color = _MBT('theme_color');
    $color = '';
    if($theme_color && $theme_color != '#ff5f33'){
     $color = $theme_color;
    }
    if($theme_color_custom && $theme_color_custom != '#ff5f33'){
     $color = $theme_color_custom;
    }
  ?>
  .header, .topbar{background: <?php echo $header_bgcolor;?>}
  .nav-main > li, .nav-main > li > a, .nav-right a, .topbar a, .topbar{color:<?php echo $header_txtcolor;?>;}
  body.night .nav-main > li, body.night .nav-main > li > a, body.night .nav-right a{color:#999;}
  <?php if($color == $header_bgcolor){?>
  body.home .header:not(.scrolled) .nav-main > li > a:hover, body.home .header:not(.scrolled) .nav-right > li > a:hover, .nav-main > li > a:hover, .nav-right a:hover, .nav-main > li > a:hover, .nav-left a:hover{color:<?php echo $header_txtcolor;?>;}
  <?php }?>
  @media (max-width: 768px){
    .nav-right .nav-button a {color: <?php echo $header_txtcolor;?>;}
  }
  <?php
  }else{
    if( _MBT('banner') == '1' || _MBT('banner') == '3' || (_MBT('banner') == '2' && _MBT('slider_fullwidth')) ){
      $header_color = _MBT('header_color')?_MBT('header_color'):'#fff';
    ?>
    .banner{margin-top: -70px;}
    .banner-slider{padding-top: 90px;}
    <?php if(_MBT('banner') == '3'){?>
    .banner-slider{padding-top: 30px;}
    <?php }?>
    .banner-slider:after{content: none;}
    body.home .header{background: transparent;box-shadow: none;webkit-box-shadow:none;}
    body.home .header.scrolled{background: #fff;webkit-box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);-ms-box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);box-shadow: 0px 5px 10px 0px rgba(17, 58, 93, 0.1);}

    body.home .header:not(.scrolled) .nav-main > li, body.home .header:not(.scrolled) .nav-main > li > a, body.home .header:not(.scrolled) .nav-right > li > a, body.home .header:not(.scrolled) .nav-left > li > a{color:<?php echo $header_color;?>;}
    body.home .header:not(.scrolled) .nav-line{background: <?php echo $header_color;?>}
    body.home.night .header:not(.scrolled) .nav-main > li, body.home.night .header:not(.scrolled) .nav-main > li > a, body.home.night .header:not(.scrolled) .nav-right > li > a, body.home.night .header:not(.scrolled) .nav-left > li > a{color:#fff;}
    body.home .nav-main li.menu-item-has-children:after{color:#fff;}
    body.home .header.scrolled .nav-main li.menu-item-has-children:after{color:#333;}
    body.home.night .nav-main li.menu-item-has-children:after{color:#fff;}
    body.home.night .header.scrolled .nav-main li.menu-item-has-children:after{color:#bbb;}

    @media (max-width: 925px){
      .banner-slider{padding-top: 85px;}
      <?php if(_MBT('banner') == '3'){?>
      .banner-slider{padding-top: 20px;}
      <?php }?>
    }

    @media (max-width: 768px){
      .banner{margin-top: -60px;}
      .banner-slider{padding-top: 70px;}
      <?php if(_MBT('banner') == '3'){?>
      .banner-slider{padding-top: 20px;}
      <?php }?>
    }
    <?php 
    }
  }

  if(_MBT('header_logo_position')){
  ?>
  @media (max-width: 768px){
    .nav-right .nav-search{display: none;}
    .nav-right > li:last-child{padding-right: 0 !important;}
    .logo{position: absolute;left: 50%;transform: translateX(-50%);}
  }
  <?php
  }

  if(_MBT('banner_dark')){
  ?>
  .banner:after, body.home .swiper-container-fullwidth .swiper-slide:after{content:"";position:absolute;top:0;bottom:0;left:0;right:0;background:linear-gradient(180deg,rgba(0,0,0,.38) 0,rgba(0,0,0,.38) 3.5%,rgba(0,0,0,.379) 7%,rgba(0,0,0,.377) 10.35%,rgba(0,0,0,.375) 13.85%,rgba(0,0,0,.372) 17.35%,rgba(0,0,0,.369) 20.85%,rgba(0,0,0,.366) 24.35%,rgba(0,0,0,.364) 27.85%,rgba(0,0,0,.361) 31.35%,rgba(0,0,0,.358) 34.85%,rgba(0,0,0,.355) 38.35%,rgba(0,0,0,.353) 41.85%,rgba(0,0,0,.351) 45.35%,rgba(0,0,0,.35) 48.85%,rgba(0,0,0,.353) 52.35%,rgba(0,0,0,.36) 55.85%,rgba(0,0,0,.371) 59.35%,rgba(0,0,0,.385) 62.85%,rgba(0,0,0,.402) 66.35%,rgba(0,0,0,.42) 69.85%,rgba(0,0,0,.44) 73.35%,rgba(0,0,0,.46) 76.85%,rgba(0,0,0,.48) 80.35%,rgba(0,0,0,.498) 83.85%,rgba(0,0,0,.515) 87.35%,rgba(0,0,0,.529) 90.85%,rgba(0,0,0,.54) 94.35%,rgba(0,0,0,.547) 97.85%,rgba(0,0,0,.55));z-index:9}
  <?php
  }

  if(_MBT('banner_video') && !modown_is_mobile()){
  ?>
  .banner{background-image: none !important;}
  <?php
  }

  if(_MBT('banner_height') != '' && _MBT('banner_height') != '400'){
  ?>
    .banner{height: <?php echo _MBT('banner_height');?>px;}
  <?php
  }

  if(_MBT('banner_height_mobile') != '' && _MBT('banner_height_mobile') != '300'){
  ?>
  @media (max-width: 768px){
    .banner{height: <?php echo _MBT('banner_height_mobile');?>px !important;}
  }
  <?php
  }

  if(_MBT('banner_archive')){
  ?>
    body.archive .banner-archive{display: none;}
  <?php
  }

  if(_MBT('banner_archive_dark')){
  ?>
    .banner-archive:after, body.home .swiper-container-fullwidth .swiper-slide:after, .mocats .moli .moli-header:after{content:"";position:absolute;top:0;bottom:0;left:0;right:0;background:linear-gradient(180deg,rgba(0,0,0,.38) 0,rgba(0,0,0,.38) 3.5%,rgba(0,0,0,.379) 7%,rgba(0,0,0,.377) 10.35%,rgba(0,0,0,.375) 13.85%,rgba(0,0,0,.372) 17.35%,rgba(0,0,0,.369) 20.85%,rgba(0,0,0,.366) 24.35%,rgba(0,0,0,.364) 27.85%,rgba(0,0,0,.361) 31.35%,rgba(0,0,0,.358) 34.85%,rgba(0,0,0,.355) 38.35%,rgba(0,0,0,.353) 41.85%,rgba(0,0,0,.351) 45.35%,rgba(0,0,0,.35) 48.85%,rgba(0,0,0,.353) 52.35%,rgba(0,0,0,.36) 55.85%,rgba(0,0,0,.371) 59.35%,rgba(0,0,0,.385) 62.85%,rgba(0,0,0,.402) 66.35%,rgba(0,0,0,.42) 69.85%,rgba(0,0,0,.44) 73.35%,rgba(0,0,0,.46) 76.85%,rgba(0,0,0,.48) 80.35%,rgba(0,0,0,.498) 83.85%,rgba(0,0,0,.515) 87.35%,rgba(0,0,0,.529) 90.85%,rgba(0,0,0,.54) 94.35%,rgba(0,0,0,.547) 97.85%,rgba(0,0,0,.55));z-index:9}
  <?php
  }

  if(_MBT('banner_archive_align_left')){
  ?>
    .banner-archive .archive-title, .banner-archive .archive-desc, .banner-archive .category-search-form{text-align: left;}
  <?php
  }

  if(_MBT('banner_page')){
  ?>
    .banner-page{display: none;}
  <?php
  }

  if(_MBT('slider_fullwidth_height_m') != '' && _MBT('slider_fullwidth_height_m') != '250'){
  ?>
  @media (max-width: 768px){
    .banner-slider-fullwidth .swiper-container .swiper-slide{height: <?php echo _MBT('slider_fullwidth_height_m');?>px !important;}
  }
  <?php
  }

  if(_MBT('nav_position') == '1'){
  ?>
  @media (min-width:1536px){.nav-right .nav-search{display: none;}}
  <?php
  }elseif(_MBT('header_search_nav')){
  ?>
  @media (min-width:1025px){.nav-right .nav-search{display: none;}.nav-search-form{display: inline-block;margin-right:13px;margin-left: 0}}
  <?php
  }

  if($list_column == 'six' && _MBT('list_style') != 'list'){
  ?>
    .container{max-width:1810px;}
    <?php if(_MBT('post_width_auto')){?>
    .single .main .container, .single-content > .erphpdown-box.fixed .erphpdown-con{max-width:1810px;}
    <?php }?>
    <?php if(_MBT('slider_right_banner')){?>
    .slider-left2{max-width: 1200px;}
    <?php }?>
    .slider-left{max-width: 1505px;}
    @media (max-width:1840px){
      .container{max-width:1505px;}
      <?php if(_MBT('post_width_auto')){?>
      .single .main .container, .single-content > .erphpdown-box.fixed .erphpdown-con{max-width:1505px;}
      <?php }?>
      .modown-ad .item:nth-child(6){display: none;}
      .slider-left{max-width: 1200px;}
    }
    @media (max-width:1535px){
      .modown-ad .item:nth-child(5){display: none;}
      .slider-left{max-width: 895px;}
    }

    <?php
    if(_MBT('nav_position') == '1'){
    ?>
      body.nv-left .container{max-width:1810px;}
      <?php if(_MBT('slider_right_banner')){?>
      body.nv-left .slider-left2{max-width: 1200px;}
      <?php }?>
      body.nv-left .slider-left{max-width: 1505px;}
      @media (max-width:2080px) and (min-width:1025px){
        body.nv-left .container{max-width:1505px;}
        body.nv-left .slider-left{max-width: 1200px;}
        body.nv-left .modown-ad .item:nth-child(6){display: none;}
      }
      @media (max-width:1775px) and (min-width:1025px){
        body.nv-left .container{max-width: 1200px}
        body.nv-left .slider-left{max-width: 895px;}
        body.nv-left .modown-ad .item:nth-child(5){display: none;}
      }
    <?php
    }

  }

  if($list_column == 'five' && _MBT('list_style') != 'list'){
  ?>
    .container{max-width:1505px;}
    <?php if(_MBT('post_width_auto')){?>
    .single .main .container, .single-content > .erphpdown-box.fixed .erphpdown-con{max-width:1505px;}
    <?php }?>
    <?php if(_MBT('slider_right_banner')){?>
    .slider-left2{max-width: 895px;}
    <?php }?>
    .slider-left{max-width: 1200px;}
    @media (max-width:1535px){
      .modown-ad .item:nth-child(5){display: none;}
      .slider-right2{width: 285px;}
      .slider-right2 .item2{display: none;}
      .slider-left{max-width: 895px;}
    }

    <?php
    if(_MBT('nav_position') == '1'){
    ?>
      body.nv-left .container{max-width:1505px;}
      <?php if(_MBT('slider_right_banner')){?>
      body.nv-left .slider-left2{max-width: 895px;}
      <?php }?>
      body.nv-left .slider-left{max-width: 1200px;}
      @media (max-width:1775px) and (min-width:1025px){
        body.nv-left .container{max-width: 1200px}
        body.nv-left .slider-left{max-width: 895px;}
        body.nv-left .modown-ad .item:nth-child(5){display: none;}
      }
    <?php
    }

  }

  if(_MBT('vip_hidden')){
  ?>
    .widget-erphpdown .vip, .erphpdown-box .vip, .grids .grid > .vip-tag, .nav-right .nav-vip{display: none;}
  <?php
  }

  if(_MBT('vip_bg')){
  ?>
    body.home .vip-content{background-image: url(<?php echo _MBT('vip_bg');?>);}
  <?php
  }

  if(_MBT('post_title')){
  ?>
    .grids .grid h3 a{height: 40px;-webkit-line-clamp:2;}
    .grids .audio .title{line-height: 25px;}
  <?php
  }

  if(_MBT('post_text')){
  ?>
    .grids .grid .con{display: none !important;}
    .grids .grid .img{border-radius: <?php echo _MBT('theme_radius');?>;}
  <?php
  }

  if(_MBT('post_title_bold')){
  ?>
    .grids .grid h3 a, .lists .list h3 a, .lists .grid h3 a, .home-blogs ul li h3 a{font-weight:600;}
  <?php
  }

  if(_MBT('post_price') && _MBT('post_price_cat')){
  ?>
    .grids .grid .cat{padding-right: 60px}
    .grids .grid .cat .price{position: relative;right: -60px}
    .lists .grid .cat .price{display: none;}
  <?php
  }

  if(_MBT('post_metas')){
  ?>
    .post .grid-meta, .post:not(.question) .list-meta{display:none !important;}
  <?php
  }

  if(!_MBT('post_views')){
  ?>
    .mocats .moli ul li{padding-right: 0}
    .mocats .moli ul span{display:none !important;}
  <?php
  }

  if(_MBT('post_excerpt')){
  ?>
    .grids .grid .excerpt{display: -webkit-box;}
  <?php
  }

  if(_MBT('post_vip_free')){
  ?>
    .post > .vip-tag, .post > .free-tag{display:none !important;}
  <?php
  }

  if(_MBT('post_price_color') && _MBT('post_price_color') != '#ababab'){
  ?>
    .grids .grid .grid-meta .price .fee, .lists .grid .grid-meta .price .fee, .lists .list .list-meta .price .fee, .grids .list .list-meta .price .fee, .grids .grid .cat .price .fee{background: <?php echo _MBT('post_price_color');?>;}
  <?php
  }

  if(_MBT('post_vip_jcolor') && _MBT('post_vip_jcolor') != '#e0bf73'){
  ?>
    .grids .grid > .vip-tag{background: <?php echo _MBT('post_vip_jcolor');?>;background-image: none;}
  <?php
  }

  if(_MBT('post_vip_jtcolor') && _MBT('post_vip_jtcolor') != '#4e342e'){
  ?>
    .grids .grid > .vip-tag{color: <?php echo _MBT('post_vip_jtcolor');?>;}
  <?php
  }

  if(_MBT('post_recommend_color')){
  ?>
    .grid > .recommend-tag, .list > .recommend-tag{background: <?php echo _MBT('post_recommend_color');?> !important;}
    .grid > .recommend-tag:before, .list > .recommend-tag:before{border-color: transparent transparent <?php echo _MBT('post_recommend_color');?> transparent !important;}
  <?php
  }

  if(_MBT('post_prices_style')){
  ?>
  .header-box-con .erphpdown .erphpdown-fee .erphpdown-children-nav, .erphpdown-box .erphpdown-con .erphpdown-children-nav{}
  .header-box-con .erphpdown .erphpdown-fee .erphpdown-children-nav a, .erphpdown-box .erphpdown-con .erphpdown-children-nav a{border-radius: 3px;border-bottom: 1px solid #eaeaea;margin-left: 0;margin-right: 10px;margin-bottom:10px;}
  .header-box-con .erphpdown .erphpdown-fee .erphpdown-children-nav a{border-bottom: 1px solid #f5f5f5}
  .header-box-con .erphpdown .erphpdown-fee .erphpdown-children-nav a.active, .erphpdown-box .erphpdown-con .erphpdown-children-nav a.active{color: var(--theme-color);}
  <?php
  }

  if(_MBT('post_paragraph')){
  ?>
    .article-content p{text-indent: 2em;}
    .article-content p img{display: block;}
  <?php
  }

  if(_MBT('post_content_font_size') && _MBT('post_content_font_size') != '16'){
  ?>
    .article-content{font-size: <?php echo _MBT('post_content_font_size');?>px;}
    @media(max-width: 768px){
      .article-content{font-size: <?php echo _MBT('post_content_font_size_m');?>px;}
    }
  <?php
  }

  if(_MBT('post_gallery_square')){
  ?>
    .article-content .gallery-item > a, .article-content .gallery-item .img{width:100%;height:0;position: relative;padding-bottom: 100%;display: block;}
    .article-content .gallery-item img{width:100%;height:100%;position: absolute;}
    .article-content .blocks-gallery-grid .blocks-gallery-item figure{width:100%;height:0;position: relative;padding-bottom: 100%;display: block;}
    .article-content .blocks-gallery-grid .blocks-gallery-item img{width:100%;height:100%;position: absolute;}
  <?php
  }

  if(_MBT('post_align_center')){
  ?>
    .article-content img{display:block;}
  <?php
  }

  if(_MBT('post_tags_color')){
  ?>
    .article-tags a:nth-child(5n) {background-color: #4A4A4A !important;color: #fff !important}
    .article-tags a:nth-child(5n+1) {background-color: #ff5e5c !important;color: #fff !important}
    .article-tags a:nth-child(5n+2) {background-color: #ffbb50 !important;color: #fff !important}
    .article-tags a:nth-child(5n+3) {background-color: #1ac756 !important;color: #fff !important}
    .article-tags a:nth-child(5n+4) {background-color: #19B5FE !important;color: #fff !important}
  <?php
  }

  if(_MBT('theme_sidebar_width') && _MBT('theme_sidebar_width') != 300){
  ?>
    .sidebar{width: <?php echo _MBT('theme_sidebar_width');?>px;margin-left: -<?php echo _MBT('theme_sidebar_width');?>px;}
    .sidebar .widget.affix{width:<?php echo _MBT('theme_sidebar_width');?>px;}
    .single .content, .page-template-default .content, .post-type-archive-blog .content, .page-template-tougao .content, .page-template-ask .content, .page-template-tuan .content, .page-template-all .content, .page-template-all-vip .content, .archive .content, .search .content, .home .content{margin-right: <?php echo _MBT('theme_sidebar_width')+20;?>px;}
    @media (max-width: 1024px){
      .single .content, .page-template-default .content, .post-type-archive-blog .content, .page-template-tougao .content, .page-template-ask .content, .page-template-tuan .content, .page-template-all .content, .page-template-all-vip .content, .archive .content, .search .content, .home .content{margin-right: 0}
    }
  <?php
  }

  $erphp_box_style = get_option('erphp_box_style');
  if($erphp_box_style != '1'){
  ?>
    .erphpdown-box, .erphpdown, .article-content .erphpdown-content-vip{background: transparent !important;border: 2px dashed var(--theme-color);}
    .erphpdown-box .erphpdown-title{display: inline-block;}
  <?php
  }

  if(_MBT('header_fullwidth')){
    echo '.header .container, .topbar .container{max-width:none !important;padding:0 15px;}.search-wrap-container{max-width:none !important;}';
  }

  if(_MBT('logo_width')){
    echo '.logo a img{width:'._MBT('logo_width').'px;height:auto;max-height:100%}';
  }

  if(_MBT('logo_width_wap')){
    echo '@media (max-width: 1024px){.logo a img{width:'._MBT('logo_width_wap').'px;height:auto;max-height:100%}}';
  }

  if(!is_user_logged_in() && (_MBT('hide_user_all') || _MBT('header_login'))){
    echo '.nav-right .nav-search{padding-right: 0}@media (max-width: 768px){.nav-right .nav-search{padding-right: 6px}}';
  }

  if(_MBT('footer_hide')){
    echo '.footer{display: none;}';
  }

  if(_MBT('footer_bgcolor') && _MBT('footer_bgcolor') != '#333' && _MBT('footer_bgcolor') != '#333333'){
    echo '.footer{background-color: '._MBT('footer_bgcolor').'}';
  }

  if(_MBT('footer_bgimg')){
    echo '.footer{background-image: url('._MBT('footer_bgimg').');background-size: cover;background-position: center;background-repeat: no-repeat;}';
  }

  if(_MBT('footer_color') && _MBT('footer_color') != '#a7a7a7'){
    echo '.footer{color: '._MBT('footer_color').'}';
  }

  if(_MBT('footer_acolor') && _MBT('footer_acolor') != '#dcdcdc'){
    echo '.footer a{color: '._MBT('footer_acolor').'}';
  }

  if(_MBT('footer_widget_num') == '4'){
    echo '.footer-widget{width:calc(21% - 20px);}@media (max-width: 768px){.footer-widget{width:calc(50% - 20px);margin-bottom:25px}
  .footer-widget:first-child{width:calc(100% - 20px);padding-right: 0}
  .footer-widget:last-child{width:calc(100% - 20px);padding-left: 0;margin-bottom: 0}}';
  }elseif(_MBT('footer_widget_num') == '3'){
    echo '.footer-widget{width:calc(33.3333% - 20px) !important;padding:0 !important;}@media (max-width: 768px){.footer-widget{width:calc(100% - 20px) !important;}}';
  }elseif(_MBT('footer_widget_num') == '2'){
    echo '.footer-widget{width:calc(50% - 20px) !important;padding:0 !important;}@media (max-width: 768px){.footer-widget{width:calc(100% - 20px) !important;}}';
  }

  if(_MBT('footer_nav_num') == '5'){
    echo '.footer-fixed-nav a{width: 20%}';
  }

  if(_MBT('rollbar_center')){
    echo '.rollbar{top: 50%;transform: translateY(-50%);bottom: inherit;}';
  }

  if(!is_user_logged_in() && _MBT('hide_user_all')){
    echo '.free-tag, .vip-tag, .grid-meta .price, .list-meta .price, .widget .price, .erphpdown{display: none !important}';
  }

  if(_MBT('home_cats_thumb_notit')){
    echo '.home-cathumbs .items .item h4, .home-cathumbs .items .item a:before{display:none}';
  }

  if(_MBT('home_cats_icon_pc')){
    echo '@media(min-width:768px){body.home .home-caticons{display:none}}';
  }

  if(_MBT('theme_night')){
    if(isset($_COOKIE['mbt_theme_night'])){
        if($_COOKIE['mbt_theme_night'] == '1'){
          echo '::-webkit-scrollbar-thumb {background-color: rgb(99 98 98 / 70%);}';
        }
    }elseif(_MBT('theme_night_default')){
        echo '::-webkit-scrollbar-thumb {background-color: rgb(99 98 98 / 70%);}';
    }elseif(_MBT('theme_night_auto')){
        $time = intval(date("Hi"));
        if ($time < 730 || $time > 1930) {
            echo '::-webkit-scrollbar-thumb {background-color: rgb(99 98 98 / 70%);}';
        }
    }
  }

  if(_MBT('user_height')){
    echo '.user-main{min-height: '._MBT('user_height').'px;}';
  }

  echo '@media (max-width: 768px){';
    if(_MBT('header_vip_icon') == '1'){
      echo '.nav-right .nav-vip{padding: 4.5px 3px 5px;}';
    }

    if(_MBT('header_vip_wap')){
      echo '.nav-right .nav-vip{display: none;}';
    }

    if(_MBT('header_tougao_wap')){
      echo '.nav-right .nav-tougao{display: none;}';
    }

    if(_MBT('header_notice_wap')){
      echo '.nav-right .nav-notice{display: none;}';
    }

    if(_MBT('header_cart_wap')){
      echo '.nav-right .nav-cart{display: none;}';
    }

    if(_MBT("header_topbar_wap")){
      echo '.topbar{display:none}body.has-topbar{margin-top:60px}body.has-topbar .header{top:0}body.has-topbar .search-wrap.show{top:60px}';
    }

    if(_MBT('home_cats_bg')){
      echo '.mocat {padding: 20px 0;}.home-cathumbs + .contents .mocat:first-child {margin-top: 0;}';
    }

    if(_MBT('down_position_fixed_wap')){
      echo '.single-content > .erphpdown-box, .single-content > .erphpdown-box.fixed{display:block !important}body.single .footer-fixed-nav a.special:after{display:none}body.single .footer-fixed-nav a.special i{top:6px;}';
    }

    if(_MBT('footer_nav')){
      echo 'body{margin-bottom:50px}.sitetips-default{bottom:100px;}
        .rollbar{display: none;}';
    }

    if(_MBT('rollbar_wap')){
      echo '.rollbar{display: block;}';
    }

    if(_MBT('footer_widget_hide')){
      echo '.footer-widgets{display: none;}';
    }

    if(_MBT('home_why_m')){
      echo '.vip-why{display: none;}';
    }

    if(_MBT('home_total_m')){
      echo '.totals{display: none;}';
    }

    if(_MBT('banner_keywords_m')){
      echo '.search-keywords{display:none}';
    }
  echo '}';

  echo '@media (max-width:480px){';
    if(_MBT('post_views_mobile')){
      echo '.grids .grid .grid-meta .views, .lists:not(.cols-title) .list .list-meta .views, .lists .grid .grid-meta .views{display:inline-block !important}';
    }
    if(_MBT('post_downloads_mobile')){
      echo '.grids .grid .grid-meta .downs, .lists:not(.cols-title) .list .list-meta .downs, .lists .grid .grid-meta .downs{display:inline-block !important}';
    }
  echo '}';

  echo _MBT('css');
  do_action("modown_skin");
  ?>
</style>