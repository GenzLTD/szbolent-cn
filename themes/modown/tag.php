<?php 
get_header();
$tag_slug = get_query_var('tag');
$tag = get_term_by('slug',$tag_slug,'post_tag');
$tag_class = 'grids';
if($tag){
	$style = get_term_meta($tag->term_id,'style',true);
	if($style == 'list') $tag_class = 'lists';
	elseif($style == 'grid') $tag_class = 'grids';
	elseif($style == 'grid-audio') $tag_class = 'grids';
	elseif($style == 'list2') $tag_class = 'lists cols-two';
	elseif($style == 'list3') $tag_class = 'lists cols-three';
	elseif($style == 'list-title') $tag_class = 'lists cols-title';
	else{
	    $style = _MBT('list_style');if($style == 'list') $tag_class = 'lists';elseif($style == 'list2') $tag_class = 'lists cols-two'; elseif($style == 'list3') $tag_class = 'lists cols-three';elseif($style == 'list-title') $tag_class = 'lists cols-title';
	}
}else{
	$style = _MBT('list_style');if($style == 'list') $tag_class = 'lists';elseif($style == 'list2') $tag_class = 'lists cols-two'; elseif($style == 'list3') $tag_class = 'lists cols-three';elseif($style == 'list-title') $tag_class = 'lists cols-title';
}
?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php single_tag_title();if($tag && _MBT('post_category_nums')) echo '<span>'.MBThemes_term_post_count('post_tag', $tag->term_id).__('篇','mobantu').'</span>'; ?></h1>
		<p class="archive-desc"><?php echo trim(strip_tags(tag_description()));?></p>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php if($style == 'list' || $style == 'list-title') echo '<div class="content-wrap"><div class="content">';?>
		<?php MBThemes_ad('ad_list_header');?>
		<div id="posts" class="posts <?php echo $tag_class;?> <?php if(MBTheme_waterfall() && ($style != 'list' && $style != 'list2' && $style != 'list3' && $style != 'list-title')) echo 'waterfall';?> clearfix">
			<?php 
				$ccc = 'content';if($style == 'list') $ccc = 'content-list';elseif($style == 'grid-audio') $ccc = 'content-audio';elseif($style == 'list-title') $ccc = 'content-list-title';
				if ( have_posts() ){
					while ( have_posts() ) : the_post(); 
					get_template_part( 'module/'.$ccc );
					endwhile; wp_reset_query(); 
				}else{
                    get_template_part( 'module/none' );
                }
			?>
		</div>
		<?php MBThemes_paging();?>
		<?php MBThemes_ad('ad_list_footer');?>
		<?php if($style == 'list' || $style == 'list-title') {echo '</div></div>';get_sidebar();}?>
	</div>
</div>
<?php get_footer();?>