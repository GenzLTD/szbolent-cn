<?php get_header();
global $wp_query;
$blogs_slug = get_query_var('blogs');
$blogs = get_term_by('slug',$blogs_slug,'blogs');
$blogs_id = $blogs->term_id;
?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php echo $blogs->name;?></h1>
		<p class="archive-desc"><?php echo $blogs->description;?></p>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap">
	    	<div class="content<?php if(_MBT('blog_sidebar_no')) echo ' nosidebar';?>">
				<?php MBThemes_ad('ad_list_header');?>
				<div id="posts" class="lists clearfix">
					<?php 
						if ( have_posts() ){
							while ( have_posts() ) : the_post(); 
							get_template_part( 'module/content-blog' );
							endwhile; wp_reset_query(); 
						}else{
		                    get_template_part( 'module/none' );
		                }
					?>
				</div>
				<?php MBThemes_paging();?>
				<?php MBThemes_ad('ad_list_footer');?>
			</div>
		</div>
		<?php if(!_MBT('blog_sidebar_no')) get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>