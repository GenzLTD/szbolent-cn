<?php 
$tj = get_post_meta(get_the_ID(),'down_recommend',true);
$ts = get_post_meta(get_the_ID(),'down_special',true);
$sign = get_post_meta(get_the_ID(),'sign',true);
$video = get_post_meta(get_the_ID(),'video_preview',true);
$down_tuan = get_post_meta(get_the_ID(),'down_tuan',true);
if($sign){
    $sign_color = get_post_meta(get_the_ID(),'sign_color',true);
    $sign = '<span class="post-sign"'.(($sign_color && $sign_color != '#ff9600')?' style="background:'.$sign_color.'"':'').'>'.$sign.'</span>';
}
$lz = (_MBT('lazyload') && !MBTheme_waterfall())?1:0;
$tag_icon = ''; 
global $post_target, $vip_jtext;
?>
<div class="post grid<?php if($ts) echo ' grid-ts'; if($video) echo ' grid-vd'; if(_MBT('post_author')) echo ' grid-zz';?>"<?php if($ts) echo ' style="background-image:url('.MBThemes_thumbnail_full().')"';?> <?php if($video) echo 'data-video="'.$video.'"';?> data-id="<?php the_ID();?>">
    <div class="img">
        <a href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>" rel="bookmark">
        <img <?php if($lz) echo 'src="'.(_MBT('thumbnail_loading')?_MBT('thumbnail_loading'):THEME_URI.'/static/img/thumbnail.png').'"';?> <?php echo $lz?'data-src':'src';?>="<?php echo MBThemes_thumbnail();?>" class="thumb" alt="<?php the_title();?>">
        <?php if($video){ $video_type = get_post_meta(get_the_ID(),'video_type',true);?>
          <?php if($video_type){?>
          <div class="grid-video"><iframe id="video-<?php the_ID();?>" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="allowfullscreen" src="" ></iframe></div><span class="video-icon"><i class="icon icon-play"></i></span>
          <?php }else{?>
          <div class="grid-video"><video id="video-<?php the_ID();?>" autoplay="autoplay" preload="none" poster="<?php echo MBThemes_thumbnail();?>"></video></div><span class="video-icon"><i class="icon icon-play"></i></span>
          <?php }?>
        <?php }?>
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
        <?php if(_MBT('post_cat') && !_MBT('post_cat_lefttop')){
            echo '<div class="cat">';
            if(function_exists('modown_categorys_before')) echo modown_categorys_before(); 
            echo MBThemes_categorys();
            if(_MBT('post_price') && _MBT('post_price_cat')){
                if($down_tuan && function_exists('get_erphpdown_tuan_num')){
                    $down_tuan_price=get_post_meta(get_the_ID(), 'down_tuan_price', true);
                    echo '<span class="price"><span class="fee"><i class="icon icon-money"></i> '.$down_tuan_price.'</span></span>';
                }else{
                    $erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
                    if($erphp_down && $erphp_down != '4'){
                        if(is_user_logged_in() || !_MBT('hide_user_all')){
                            $price=MBThemes_erphpdown_price(get_the_ID());
                            $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
                            echo '<span class="price">';
                            if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
                                if($price){
                                    echo '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                                }
                                if(_MBT('post_vip_free_fs')){
                                    $tag_icon = 'vip';
                                }
                            }elseif($memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
                                echo '<span class="fee vip-tag">VIP</span>';
                                $tag_icon = 'vip';
                            }elseif($price){
                                echo '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                            }else{ 
                                echo '<span class="fee free-tag">'.__('免费','mobantu').'</span>';
                                $tag_icon = 'free';
                            }
                            echo '</span>';
                        }
                    }
                }
            }
            echo '</div>';
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

        <h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>"<?php $title_color = get_post_meta(get_the_ID(),'title_color',true);if($title_color && $title_color != "#000000" && $title_color != "#333333") echo ' style="color:'.$title_color.'"';?>><?php echo $sign;?><?php the_title();?></a></h3>

        <?php //if(function_exists('modown_grid_custom_field')) echo modown_grid_custom_field();?>
        <?php echo '<div class="excerpt">'.MBThemes_get_excerpt(80).'</div>';?>

        <div class="grid-meta">
            <?php 
                if($down_tuan && function_exists('get_erphpdown_tuan_num')){
                    $down_tuan_num=get_post_meta(get_the_ID(), 'down_tuan_num', true);
                    $down_tuan_price=get_post_meta(get_the_ID(), 'down_tuan_price', true);
                    $tnum = get_erphpdown_tuan_num(get_the_ID());
                    $percent = get_erphpdown_tuan_percent(get_the_ID(),$tnum);
                    echo '<div class="erphpdown-tuan-process"><div class="line"><span style="width:'.$percent.'%"></span></div><div class="data">'.$percent.'%</div>'.'</div>';
                    if(!_MBT('post_price')){
                        echo '<span class="price"><span class="fee"><i class="icon icon-money"></i> '.$down_tuan_price.'</span></span>';
                    }
                    $tag_icon = 'tuan';
                }else{
                    if(_MBT('post_date')){?><span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() ) ?></span><?php }?><?php if(_MBT('post_views')){?><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?><?php if(_MBT('post_comments')){?><span class="comments"><i class="icon icon-comment"></i> <?php echo get_comments_number('0', '1', '%');?></span><?php }?><?php if(_MBT('post_downloads')){ $downtimes = get_post_meta(get_the_ID(),'down_times',true); echo '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';}

                    if(ERPHPDOWN_IS_ACTIVE){
                        $erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
                        if($erphp_down && $erphp_down != '4'){
                            $price=MBThemes_erphpdown_price(get_the_ID());
                            $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
                            if(!_MBT('post_price')){
                                echo '<span class="price">';
                          	    if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
                                    if($price){
                                        echo '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                                    }
                                    if(_MBT('post_vip_free_fs')){
                                        $tag_icon = 'vip';
                                    }
                                }elseif($memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
                                    echo '<span class="fee vip-tag">VIP</span>';
                                    $tag_icon = 'vip';
                                }elseif($price){
                                    echo '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                                }else{ 
                                    echo '<span class="fee free-tag">'.__('免费','mobantu').'</span>';
                                    $tag_icon = 'free';
                                }
                                echo '</span>';
                            }else{
                                if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7' || $memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
                                    $tag_icon = 'vip';
                                }elseif(!$price){
                                    $tag_icon = 'free';
                                }
                            }
                      	    
                        }
                    }
                }
            ?>
        </div>

        <?php if(_MBT('post_author')){?>
        <div class="grid-author">
            <a target="_blank" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ));?>"  class="avatar-link"><?php echo get_avatar(get_the_author_meta( 'ID' ));?><span class="author-name"><?php echo get_the_author() ?></span></a>
            <span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() ) ?></span>
        </div>
        <?php }?>
    </div>

    <?php if($tag_icon == 'tuan'){echo '<span class="vip-tag tuan-tag"><i>'.__('拼团','mobantu').'</i></span>';}elseif($tag_icon == 'vip'){echo '<span class="vip-tag"><i>'.$vip_jtext.'</i></span>';}elseif($tag_icon == 'free'){echo '<span class="vip-tag free-tag"><i>'.__('免费','mobantu').'</i></span>';}?>
    <?php if($tj){echo '<span class="recommend-tag">'.__('荐','mobantu').'</span>';} ?>
</div>