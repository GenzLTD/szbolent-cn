<?php 
session_start();
get_header();?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php if (!_MBT('post_breadcrumb')) MBThemes_breadcrumbs(); ?>
		<div class="content-wrap">
	    	<div class="content<?php if(_MBT('blog_sidebar_no')) echo ' nosidebar';?>">
	    		<?php MBThemes_ad('ad_post_header');?>
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title"><?php the_title(); ?></h1>
		    			<div class="article-meta">
		    				<?php if(_MBT('post_date')){?><span class="item"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() );?></span><?php }?>
		    				<?php if(_MBT('post_views')){?><span class="item"><i class="icon icon-eye"></i> <?php MBThemes_views() ?></span><?php }?>
		    				<span class="item"><?php edit_post_link('['.__('编辑','mobantu').']'); ?></span>
		    			</div>
		    		</header>
		    		<div class="article-content">
		    			<?php if(ERPHPDOWN_IS_ACTIVE){ if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop') MBThemes_erphpdown_box();}?>
		    			<?php if(_MBT('post_content_all')){?>
		    			<div class="article-content-all"><?php the_content(); ?><div class="article-content-all-more"><span>阅读全文<br><i class="ic icon icon-arrow-double-down"></i></span></div></div>
		    			<?php }else{?>
		    			<?php the_content(); ?>
		    			<?php }?>
		    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
		    			<?php 
			    		if(ERPHPDOWN_IS_ACTIVE){ 
			    			if(MBThemes_post_down_position() == 'bottom' || MBThemes_post_down_position() == 'sidebottom' || MBThemes_post_down_position() == 'boxbottom' || MBThemes_post_down_position() == 'side') {MBThemes_erphpdown_box();
			    			}else{
			    				if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop'){}
			    				else MBThemes_erphpdown_box(false);
			    			}
			    		}?>
		    			<?php if(_MBT('post_copyright')){?>
		    			<p class="article-copyright"><?php if(_MBT('post_copyright_custom')){
		    					echo str_replace('%post%', '<a href="'.get_permalink(get_the_ID()).'">'.get_permalink(get_the_ID()).'</a>', _MBT('post_copyright_custom'));
		    				}else{?>
		    				<?php _e('本文链接：','mobantu')?><a href="<?php the_permalink();?>"><?php the_permalink();?></a><?php _e('，转载请注明出处。','mobantu')?>
		    				<?php }?>
		    			</p><?php }?>
		            </div>
		            <?php get_template_part('module/act');?>
		            <?php 
					  	$blogs = get_the_terms(get_the_ID(),'blogs');
						if ( $blogs && ! is_wp_error( $blogs ) ){
							echo '<div class="article-tags">';
							foreach ($blogs as $blogc) {
								echo '<a href="'.get_term_link($blogc).'" rel="tag">'.$blogc->name.'</a>';
							}
							echo '</div>';
						}
					?>
		    		<?php if(_MBT('post_share')) get_template_part('module/share');?>
	            </article>
	            <?php endwhile;  ?>
	            <?php if(_MBT('post_nav')){?>
                <nav class="article-nav">
                    <span class="article-nav-prev"><?php previous_post_link(__('上一篇','mobantu').'<br>%link'); ?></span>
                    <span class="article-nav-next"><?php next_post_link(__('下一篇','mobantu').'<br>%link'); ?></span>
                </nav>
                <?php }?>
	            <?php MBThemes_ad('ad_post_footer');?>
	            <?php comments_template('', true); ?>
	            <?php MBThemes_ad('ad_post_comment');?>
	    	</div>
	    </div>
		<?php if(!_MBT('blog_sidebar_no')) get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>