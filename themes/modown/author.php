<?php get_header();global $wp_query;$curauth = $wp_query->get_queried_object();$style = _MBT('list_style');$cat_class = 'grids'; if($style == 'list') $cat_class = 'lists';elseif($style == 'list2') $cat_class = 'lists cols-two'; elseif($style == 'list3') $cat_class = 'lists cols-three';elseif($style == 'list-title') $cat_class = 'lists cols-title';?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<div class="archive-avatar">
		<?php echo get_avatar($curauth->ID,100);?>
		<?php if(ERPHPDOWN_IS_ACTIVE){ 
			if(getUsreMemberTypeById($curauth->ID)) echo '<span class="vip"></span>'; 
		}?>
		</div>
		<h1 class="archive-title"><?php echo $curauth->display_name;?></h1>
		<p><?php echo $curauth->description;?></p>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php if($style == 'list') echo '<div class="content-wrap"><div class="content">';?>
		<?php MBThemes_ad('ad_list_header');?>
		<div id="posts" class="posts <?php echo $cat_class;?> <?php if(_MBT('waterfall') && $style != 'list') echo 'waterfall';?> clearfix">
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
		<?php if($style == 'list') {echo '</div></div>';get_sidebar();}?>
	</div>
</div>
<?php get_footer();?>