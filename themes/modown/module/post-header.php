<div class="article-header"><?php $post = get_post();setup_postdata($post);$sign = get_post_meta(get_the_ID(),'sign',true);$sign_color = get_post_meta(get_the_ID(),'sign_color',true);
$sign = $sign?'<span class="item post-sign"'.(($sign_color && $sign_color != '#ff9600')?' style="background:'.$sign_color.'"':'').'>'.$sign.'</span>':'';?>
	<h1 class="article-title"<?php $title_color = get_post_meta(get_the_ID(),'title_color',true);if($title_color && $title_color != "#000000" && $title_color != "#333333") echo ' style="color:'.$title_color.'"';?>><?php the_title(); ?></h1>
	<div class="article-meta">
		<?php echo $sign;?><?php if(_MBT('post_author')){?><span class="item"><i class="icon icon-user"></i> <a target="_blank" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ));?>" class="avatar-link"><?php echo get_the_author(); ?></a></span><?php }?>
		<?php if(_MBT('post_date')){?><span class="item"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() ) ?></span><?php }?>
		<?php if(_MBT('post_cat')){ $categories = get_the_category(); if($categories){?><span class="item item-cats"><i class="icon icon-cat"></i> <?php echo MBThemes_categorys();?></span><?php } }?>
		<?php if(_MBT('post_views')){?><span class="item"><i class="icon icon-eye"></i> <?php MBThemes_views() ?></span><?php }?>
		<?php $downtimes = get_post_meta(get_the_ID(),'down_times',true);
		if(_MBT('post_downloads')) echo '<span class="item"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';?>
		<?php 
			if( (_MBT('admin_capability') && current_user_can(_MBT('admin_capability'))) || current_user_can('delete_others_pages') ){
		        edit_post_link('['.__('编辑','mobantu').']');
		    }elseif(_MBT('post_tougao_edit')){
		    	global $current_user;
		    	if($current_user->ID && $post->post_author == $current_user->ID){
		    		echo '<a href="'.add_query_arg('post_id',$post->ID,get_permalink(MBThemes_page("template/tougao.php"))).'">['.__('编辑','mobantu').']</a>';
		    	}
		    }
		?>
		<?php if(wp_is_erphpdown_active() && _MBT('post_copy_aff')){?><span class="item right"><i class="icon icon-copy"></i> <a href="javascript:;" class="article-aff" <?php if(is_user_logged_in()){global $current_user;?>data-clipboard-text="<?php echo add_query_arg('aff',$current_user->ID,get_permalink());?>"<?php }?>><?php _e('推广','mobantu');?></a></span><?php }?>
	</div>
</div>