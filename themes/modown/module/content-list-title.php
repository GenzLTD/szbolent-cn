<?php 
$sign = get_post_meta(get_the_ID(),'sign',true);
$sign_color = get_post_meta(get_the_ID(),'sign_color',true);
$sign = $sign?'<span class="post-sign"'.(($sign_color && $sign_color != '#ff9600')?' style="background:'.$sign_color.'"':'').'>'.$sign.'</span>':'';
global $post_target;
?>
<div class="post list noimg">
  <div class="con">
    <h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>"<?php $title_color = get_post_meta(get_the_ID(),'title_color',true);if($title_color && $title_color != "#000000" && $title_color != "#333333") echo ' style="color:'.$title_color.'"';?>><?php echo $sign;?><?php the_title();?></a></h3>
    
    <div class="list-meta clearfix">
      <?php if(_MBT('post_cat')){ $categories = get_the_category(); if($categories){?><span class="item item-cats"><i class="icon icon-cat"></i> <?php echo MBThemes_categorys();?></span><?php } }?>
      <?php if(_MBT('post_author')){?><span class="author"><i class="icon icon-user"></i> <a target="_blank" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ));?>"  class="avatar-link"><?php echo get_the_author() ?></a></span><?php }?>
      <?php if(_MBT('post_date')){?><span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() ) ?></span><?php }?><?php if(_MBT('post_views')){?><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?><?php if(_MBT('post_comments')){?><span class="comments"><i class="icon icon-comment"></i> <?php echo get_comments_number('0', '1', '%');?></span><?php }?><?php if(_MBT('post_downloads')){ $downtimes = get_post_meta(get_the_ID(),'down_times',true); echo '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';}?>
      <?php 
        if(ERPHPDOWN_IS_ACTIVE){
          $erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
          $price=MBThemes_erphpdown_price(get_the_ID());
          $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
          if($erphp_down && $erphp_down != '4'){
            if(!_MBT('post_price') && (is_user_logged_in() || !_MBT('hide_user_all'))){
              echo '<span class="price">';
              $down_tuan = '';
              if(function_exists('erphpdown_tuan_install')){
                $down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
              }
              if($down_tuan) echo '<span class="vip-tag tuan-tag"><i>'.__('拼团','mobantu').'</i></span>';
        	    elseif($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
                  if($price){
                      echo '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                  }
              }elseif($memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
                  echo '<span class="fee vip-tag">VIP</span>';
              }
        	    elseif($price) echo '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
        	    else echo '<span class="vip-tag free-tag"><i>'.__('免费','mobantu').'</i></span>';
              echo '</span>';
        	  }
          }
        }
      ?>
    </div>
  </div>
</div>