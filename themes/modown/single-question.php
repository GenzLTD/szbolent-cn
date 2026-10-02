<?php get_header();?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php if (!_MBT('post_breadcrumb')) MBThemes_breadcrumbs(); ?>
		<div class="content-wrap">
	    	<div class="content">
	    		<?php MBThemes_ad('ad_post_header');?>
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title"><i class="icon icon-help"></i> <?php the_title(); ?></h1>
		    			<div class="article-meta">
		    				<span class="item"><i class="icon icon-user"></i> <a target="_blank" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' ));?>" class="avatar-link"><?php echo get_the_author(); ?></a></span>
		    				<?php 
						        $question_category = get_the_terms(get_the_ID(),'question_category');
						        if($question_category){
						          echo '<span class="item item-cats"><i class="icon icon-cat"></i> <a href="'.get_term_link($question_category[0]).'" target="_blank" class="question-cat">'.$question_category[0]->name.'</a></span>';
						        }
						    ?>
		    				<span class="item"><i class="icon icon-time"></i> <?php echo MBThemes_timeago( MBThemes_post_date() );?></span>
		    				<span class="item"><i class="icon icon-eye"></i> <?php MBThemes_views() ?></span>
		    				<span class="comments"><i class="icon icon-comment"></i> <?php echo get_comments_number('0', '1', '%');?></span>
		    				<span class="item"><?php edit_post_link('['.__('编辑','mobantu').']'); ?></span>
		    			</div>
		    		</header>
		    		<div class="article-content">
		    			<?php if(_MBT('post_content_all')){?>
		    			<div class="article-content-all"><?php the_content(); ?><div class="article-content-all-more"><span>阅读全文<br><i class="ic icon icon-arrow-double-down"></i></span></div></div>
		    			<?php }else{?>
		    			<?php the_content(); ?>
		    			<?php }?>
		    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
		            </div>
	            </article>
	            <?php endwhile;  ?>
	            <?php MBThemes_ad('ad_post_footer');?>
	            <?php comments_template('', true); ?>
	            <?php MBThemes_ad('ad_post_comment');?>
	    	</div>
	    </div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>