<?php 
$tj = get_post_meta(get_the_ID(),'down_recommend',true);
$sign = get_post_meta(get_the_ID(),'sign',true);
$sign_color = get_post_meta(get_the_ID(),'sign_color',true);
$sign = $sign?'<span class="post-sign"'.(($sign_color && $sign_color != '#ff9600')?' style="background:'.$sign_color.'"':'').'>'.$sign.'</span>':'';
$lz = _MBT('lazyload')?1:0;
global $post_target;
?>
<div class="post list<?php if(_MBT('post_list_img') && !MBThemes_thumbnail_has()) echo ' noimg';?>">
  <div class="img">
    <a href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>" rel="bookmark">
      <img <?php if($lz) echo 'src="'.(_MBT('thumbnail_loading')?_MBT('thumbnail_loading'):THEME_URI.'/static/img/thumbnail.png').'"';?> <?php echo ($lz)?'data-src':'src';?>="<?php echo MBThemes_thumbnail();?>" class="thumb" alt="<?php the_title();?>">
    </a>
    <?php if(_MBT('post_cat') && _MBT('post_cat_lefttop')){
        echo '<div class="img-cat">';
        if(function_exists('modown_categorys_before')) echo modown_categorys_before(); 
        echo MBThemes_categorys();
        echo '</div>';
    }
    ?>
  </div>
  <div class="con">
    <h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>"<?php $title_color = get_post_meta(get_the_ID(),'title_color',true);if($title_color && $title_color != "#000000" && $title_color != "#333333") echo ' style="color:'.$title_color.'"';?>><?php echo $sign;?><?php the_title();?></a></h3>

    <?php if(_MBT('post_cat') && !_MBT('post_cat_lefttop')){ 
      echo '<div class="cat">';
      if(function_exists('modown_categorys_before')) echo modown_categorys_before(); 
      echo MBThemes_categorys().'</div>';
    }?>

    <?php if(_MBT('post_tag')){
        echo '<div class="tag">';
        $posttags = get_the_tags();
        if ($posttags) {
            $i = 1;
            foreach($posttags as $tag) {
                if($i <= 3){
                    echo '<a href="'.esc_attr( get_tag_link( $tag->term_id ) ).'" target="_blank">'.$tag->name . '</a>'; 
                    $i ++;
                }else{
                    break;
                }
            }
        }
        echo '</div>';
    }?>
    <div class="excerpt"><?php echo MBThemes_get_excerpt(180);?></div>
    <div class="list-meta">
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
        	    else echo '<span class="fee free-tag"><i>'.__('免费','mobantu').'</i></span>';
              echo '</span>';
        	  }
          }
        }
      ?>
    </div>
  </div>
  <?php if($tj){echo '<span class="recommend-tag">'.__('荐','mobantu').'</span>';} ?>
</div>