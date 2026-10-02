<?php $audio = get_post_meta(get_the_ID(),'audio',true);$audio_time = get_post_meta(get_the_ID(),'audio_time',true);global $post_target;?>
<div class="post grid audio" data-audio="<?php echo $audio;?>" data-id="<?php the_ID();?>">
    <i class="audio-play"></i>
    <div class="info">
        <a class="title" target="<?php echo $post_target;?>" title="<?php the_title();?>" href="<?php the_permalink();?>"<?php $title_color = get_post_meta(get_the_ID(),'title_color',true);if($title_color && $title_color != "#000000" && $title_color != "#333333") echo ' style="color:'.$title_color.'"';?>><?php the_title();?></a>
    </div>
    <audio preload="none" id="audio-<?php the_ID();?>" data-time="<?php echo $audio_time?$audio_time:'0';?>">
        <source src="<?php echo $audio;?>" type="audio/mpeg">
    </audio>
    <span class="star-time">00:00</span>
    <div class="time-bar">
        <span class="progressBar"></span>
        <i class="move-color"></i>
        <p class="timetip"></p>
    </div>
    <span class="end-time"><?php echo mbt_sec_to_time($audio_time);?></span>
</div>