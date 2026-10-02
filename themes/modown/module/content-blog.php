<?php global $post_target;?><div class="post list<?php if(_MBT('post_list_img') && !MBThemes_thumbnail_has()) echo ' noimg';?>"><?php global $post_target;$lz = _MBT('lazyload')?1:0;?>
  <div class="img"><a href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>" rel="bookmark">
    <img <?php if($lz) echo 'src="'.(_MBT('thumbnail_loading')?_MBT('thumbnail_loading'):THEME_URI.'/static/img/thumbnail.png').'"';?> <?php echo ($lz)?'data-src':'src';?>="<?php echo MBThemes_thumbnail();?>" class="thumb" alt="<?php the_title();?>">
  </a></div>
  <div class="con">
	  <h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="<?php the_permalink();?>" title="<?php the_title();?>" target="<?php echo $post_target;?>"><?php the_title();?></a></h3>
	  <?php 
	  	$blogs = get_the_terms(get_the_ID(),'blogs');
			if ( $blogs && ! is_wp_error( $blogs ) ){
				$blogs_html = '';
				$blogs_html .= '<div class="cat">';
				foreach ($blogs as $blogc) {
					$blogs_html .= '<a href="'.get_term_link($blogc).'" rel="tag">'.$blogc->name.'</a>、';
				}
				$blogs_html = rtrim($blogs_html, "、");
				$blogs_html .= '</div>';
				echo $blogs_html;
			}
	  ?>
	  <div class="excerpt"><?php echo MBThemes_get_excerpt(180);?></div>
	  <div class="list-meta clearfix">
	    <?php if(_MBT('post_date')){?><span class="time"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() ); ?></span><?php }?><?php if(_MBT('post_views')){?><span class="views"><i class="icon icon-eye"></i> <?php MBThemes_views();?></span><?php }?><?php if(_MBT('post_comments')){?><span class="comments"><i class="icon icon-comment"></i> <?php echo get_comments_number('0', '1', '%');?></span><?php }?>
	  </div>
  </div>
</div>