<?php if(_MBT('slider_fullwidth')){?>
  <div class="banner-slider-fullwidth">
    <style>
    <?php if(_MBT('header_type') == 'default'){?>
    body.home .swiper-container{margin-top: -70px;}
    <?php }elseif(_MBT('slider_fullwidth3') || _MBT('slider_fullwidth33')){?>
      <?php if(!(wp_is_mobile() || modown_is_mobile())){?>
      .banner-slider-fullwidth{padding-top:25px;padding-bottom: 25px}
      .swiper-container .swiper-slide{border-radius: var(--theme-radius);}
      <?php }?>
      <?php if(_MBT('slider_fullwidth33')){?>
      .swiper-container .swiper-slide{width:100%;max-width: 1200px}
      <?php }?>
    <?php }?>
    .swiper-container{border-radius: 0}
    .swiper-container .swiper-slide{height: <?php echo _MBT('slider_fullwidth_height')?_MBT('slider_fullwidth_height'):'400';?>px !important;padding-top:110px;background-image:url(../img/banner.jpg);background-position:center center;background-size:cover;background-repeat:no-repeat;position:relative;text-align: center;}
    .swiper-container .swiper-slide .container{z-index:10;position: relative;<?php if(_MBT('slider_fullwidth_title')) echo 'display:none;';?>}
    .swiper-container .swiper-slide h2{font-size:35px;font-weight:600;margin-bottom:10px;color:#fff;}
    .swiper-container .swiper-slide p{font-size:18px;color: #fff}
    .swiper-container .swiper-slide .swiper-slide-btn{border:1px solid #fff;color:#1d1d1d;background:#fff;font-size:18px;border-radius:30px;display:inline-block;padding:10px 40px;margin-top:40px;width:auto;}
    .swiper-container .swiper-slide img{width:100%;height: auto;}
    .swiper-container .swiper-slide .swiper-slide-link{position: absolute;top:0;bottom: 0;right: 0;left: 0}
    @media (max-width: 768px){
      <?php if(_MBT('header_type') == 'default'){?>
      body.home .swiper-container{margin-top: -60px;}
      <?php }?>
      .swiper-container .swiper-slide{padding-top: 70px;height: 250px !important;}
      .swiper-container .swiper-slide h2{font-size:24px;margin-bottom: 5px}
      .swiper-container .swiper-slide p{font-size: 16px;margin-top: 0}
      .swiper-container .swiper-slide .swiper-slide-btn{margin-top: 20px;padding:5px 24px;font-size: 16px}
    }
    </style>
    <div class="swiper-container">
        <div class="swiper-wrapper">
          <?php
            if(_MBT('slider_post')){
              $i = 5;
              $args = array(
                'meta_query' => array(array('key'=>'down_slider','value'=>'1')),
                'showposts'        => '5',
                'ignore_sticky_posts' => 1
              );
              query_posts($args);
              while (have_posts()) : the_post(); 
                $image_slider = get_post_meta(get_the_ID(),'image_slider',true);
                if($image_slider){
                  $img = $image_slider;
                }else{
                  $img = MBThemes_thumbnail_full();
                }
            ?>
              <div class="swiper-slide" style="background-image: url(<?php echo $img;?>);">
                <div class="container">
                  <h2><?php the_title();?></h2>
                  <a href="<?php the_permalink();?>" target="_blank" class="swiper-slide-btn"><?php _e("查看详情","mobantu");?></a>
                </div>
                <a href="<?php the_permalink();?>" target="_blank" class="swiper-slide-link"></a>
              </div>
            <?php
              endwhile; wp_reset_query();
            }else{
              $sort = '1 2 3 4 5';
              $sort = array_unique(explode(' ', trim($sort)));
              $i = 0;
              foreach ($sort as $key => $value) {
                  if( _MBT('slider_img'.$value) ){
                    $i++;
            ?>
              <div class="swiper-slide" style="background-image: url(<?php echo _MBT('slider_img'.$value);?>);">
                <div class="container">
                  <?php if(_MBT('slider_title'.$value)){?><h2><?php echo _MBT('slider_title'.$value);?></h2><?php }?>
                  <?php if(_MBT('slider_desc'.$value)){?><p><?php echo _MBT('slider_desc'.$value);?></p><?php }?>
                  <?php if(_MBT('slider_btn'.$value)){?><a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank" class="swiper-slide-btn"><?php echo _MBT('slider_btn'.$value);?></a><?php }?>
                </div>
                <?php if(_MBT('slider_link'.$value)){?><a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank" class="swiper-slide-link"></a><?php }?>
              </div>
          <?php }}
            }
          ?>
        </div>
        <div class="swiper-pagination"<?php if($i <= 1) echo ' style="display:none"';?>></div>
    </div>
    <script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
    <script>
      <?php if(_MBT('slider_fullwidth3') || _MBT('slider_fullwidth33')){?>
        var swiper = new Swiper('.swiper-container', {
          slidesPerView: '<?php if(wp_is_mobile() || modown_is_mobile()){ echo '1';}else{if(_MBT('slider_fullwidth33')) echo 'auto'; else echo '3';}?>',
          spaceBetween: <?php if(wp_is_mobile() || modown_is_mobile()) echo 0;else echo 20;?>,
          loop: true,
          centeredSlides: true,
          autoplay: {
            delay: 4000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.swiper-pagination',
            dynamicBullets: false,
            clickable: true,
          },
        });
      <?php }else{?>
        var swiper = new Swiper('.swiper-container', {
          slidesPerView: '1',
          autoplay: {
            delay: 4000,
            disableOnInteraction: false,
          },
          pagination: {
            el: '.swiper-pagination',
            dynamicBullets: false,
            clickable: true,
          },
        });
      <?php }?>
    </script>
  </div>
<?php }else{?>
  <div class="banner-slider<?php if(_MBT('slider_background')) echo ' bg';?>">
    <div class="container">
      <div class="<?php if(_MBT('slider_right')){ if(_MBT('slider_right_banner') || _MBT('slider_right_post')) echo 'slider-left2'; else echo 'slider-left'; }else echo 'slider-full';?>">
        <div class="swiper-container">
            <div class="swiper-wrapper">
              <?php 
                if(_MBT('slider_post')){
                  $i = 0;
                  $args = array(
                    'meta_query' => array(array('key'=>'down_slider','value'=>'1')),
                    'showposts'        => '5',
                    'ignore_sticky_posts' => 1
                  );
                  query_posts($args);
                  while (have_posts()) : the_post(); 
                    $image_slider = get_post_meta(get_the_ID(),'image_slider',true);
                    if($image_slider){
                      $img = $image_slider;
                    }else{
                      $img = MBThemes_thumbnail_full();
                    }
                ?>
                  <div class="swiper-slide">
                  <a href="<?php the_permalink();?>" target="_blank">
                    <img src="<?php echo $img;?>" alt="<?php the_title();?>" title="<?php the_title();?>">
                    <h3><?php the_title();?></h3>
                  </a>
                </div>
                <?php
                  endwhile; wp_reset_query();
                }else{
                $sort = '1 2 3 4 5';
                $sort = array_unique(explode(' ', trim($sort)));
                $i = 0;
                foreach ($sort as $key => $value) {
                    if( _MBT('slider_img'.$value) ){
                      $i ++;
              ?>
                <div class="swiper-slide">
                  <a href="<?php echo _MBT('slider_link'.$value);?>" target="_blank">
                    <img src="<?php echo _MBT('slider_img'.$value);?>" alt="<?php echo _MBT('slider_title'.$value);?>" title="<?php echo _MBT('slider_title'.$value);?>">
                    <?php if(_MBT('slider_title'.$value)) echo '<h3>'._MBT('slider_title'.$value).'</h3>';?>
                  </a>
                </div>
              <?php }}}?>
            </div>
            <div class="swiper-pagination"<?php if($i <= 1) echo ' style="display:none"';?>></div>
        </div>
        <script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
        <script>
            var swiper = new Swiper('.swiper-container', {
              slidesPerView: '1',
              autoplay: {
                delay: 4000,
                disableOnInteraction: false,
              },
              pagination: {
                el: '.swiper-pagination',
                dynamicBullets: false,
                clickable: true,
              },
            });
        </script>
      </div>
      <?php if(_MBT('slider_right')){?>
      <div class="<?php if(_MBT('slider_right_banner') || _MBT('slider_right_post')) echo 'slider-right2'; else echo 'slider-right'; if(_MBT('slider_right_post')) echo ' slider-right22';?>">
        <?php if(_MBT('slider_right_post')){
          $args = array(
            'meta_query' => array(array('key'=>'down_recommend','value'=>'1')),
            'showposts'        => '4',
            'orderby' => _MBT('slider_right_post_radom')?'rand':'date',
            'ignore_sticky_posts' => 1
          );
          query_posts($args);
          while (have_posts()) : the_post(); 
            $image_recommend = get_post_meta(get_the_ID(),'image_recommend',true);
            if($image_recommend){
              $img = $image_recommend;
            }else{
              $img = MBThemes_thumbnail();
            }
        ?>
          <div class="item">
            <a href="<?php the_permalink();?>" target="_blank"><img src="<?php echo $img;?>" alt="<?php the_title();?>" /><h3><?php the_title();?></h3></a>
          </div>
        <?php endwhile; wp_reset_query();
          }else{?>
          <?php if(_MBT('slider_right_banner')){?>
          <div class="item2">
            <a href="<?php echo _MBT("slider_right_banner_link");?>" target="_blank"><img src="<?php echo _MBT("slider_right_banner_img");?>"></a>
          </div>
          <?php }?>
          <div class="item">
            <a href="<?php echo _MBT("slider_right_link1");?>" target="_blank"><img src="<?php echo _MBT("slider_right_img1");?>"></a>
          </div>
          <div class="item">
            <a href="<?php echo _MBT("slider_right_link2");?>" target="_blank"><img src="<?php echo _MBT("slider_right_img2");?>"></a>
          </div>
        <?php }?>
      </div>
      <?php }?>
      <?php MBThemes_ad('ad_banner_inner');?>
    </div>
  </div>
<?php }?>
<?php 
  if(_MBT('banner_bottom_search')){
?>
<div class="slider-bottom-search">
  <div class="search-form container">
  <form method="get" class="site-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
    <?php 
    if(_MBT('banner_cats')){
        $cats = explode(',', trim(_MBT('banner_cats')));
        if(count($cats)){
          echo '<div class="search-cat">'.__('所有分类','mobantu').'</div>';
        }
    }
    ?>
    <input class="search-input" name="s" type="text" placeholder="<?php _e('搜索一下','mobantu');?>" autocomplete="off">
    <input type="hidden" name="cat" class="search-cat-val">
    <button class="search-btn" type="submit"><i class="icon icon-search"></i></button>
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
<?php
  }
?>