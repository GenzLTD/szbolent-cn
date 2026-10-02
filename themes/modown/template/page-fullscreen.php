<?php 
/*
	template name: 全屏页面
	description: template for mobantu.com modown theme 
*/
get_header();?>
<style>
	.main{padding: 0;}
	.content-wrap{margin:0;}
	.single-content{padding:0;margin:0;}
	.article-content{margin-bottom: 0;}
</style>
<div class="main clearfix">
	<div class="content-wrap">
    	<div class="content">
    		<?php while (have_posts()) : the_post(); ?>
    		<article class="single-content">
	    		<div class="article-content">
	    			<?php if(wp_is_erphpdown_active()){ if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop') MBThemes_erphpdown_box();}?>
	    			<?php the_content(); ?>
	    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
		    		<?php 
		    		if(wp_is_erphpdown_active()){ 
		    			if(MBThemes_post_down_position() == 'bottom' || MBThemes_post_down_position() == 'sidebottom' || MBThemes_post_down_position() == 'boxbottom' || MBThemes_post_down_position() == 'side') {MBThemes_erphpdown_box();
		    			}else{
		    				if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop'){}
		    				else MBThemes_erphpdown_box(false);
		    			}
		    		}?>
	            </div>
	    		<?php endwhile; ?>
            </article>
    	</div>
    </div>
</div>
<?php get_footer();?>