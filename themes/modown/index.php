<?php get_header();$style = _MBT('list_style');$cat_class = 'grids'; if($style == 'list') $cat_class = 'lists';elseif($style == 'list2') $cat_class = 'lists cols-two'; elseif($style == 'list3') $cat_class = 'lists cols-three';elseif($style == 'list-title') $cat_class = 'lists cols-title';?>
<?php 
if(_MBT('banner') == '1'){
	get_template_part("module/banner");
}elseif(_MBT('banner') == '2'){ 
	get_template_part("module/slider");
}elseif(_MBT('banner') == '3'){ 
	get_template_part("module/banner");
	get_template_part("module/slider");
}elseif(_MBT('banner') == '4'){ 
	get_template_part("module/banner-mobantu");
}
if(_MBT('banner_bottom_notice')) get_template_part("module/home-notices"); 
if(_MBT('home_service')) get_template_part("module/home-services"); 
if(_MBT('ad_banner_footer_s')) {echo '<div class="banner-bottom'.(_MBT('ad_banner_footer_m')?' modown-ad-mobile-hide':'').'"><div class="container">';MBThemes_ad('ad_banner_footer');echo '</div></div>';}
?>
<div class="main">
	<?php do_action("modown_main");?>
	<?php if(_MBT('home_cats_icon')) get_template_part("module/home-caticons");?>
	<?php if(_MBT('home_cats_thumb')) get_template_part("module/home-cathumbs");?>
	<?php 
		if(_MBT("home_widget_version")){
			echo '<div class="contents">';
			if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_index_cats')) : endif; 
			echo '</div>';
		}elseif(_MBT('home_fast') && _MBT('home_fast_cat')){
			$home_fast_cat = explode(',', str_replace("，",",",_MBT('home_fast_cat')));
			if(is_array($home_fast_cat) && count($home_fast_cat)){
				$fnum = _MBT('home_fast_cat_num')?_MBT('home_fast_cat_num'):8;
				echo '<div class="contents">';
				foreach($home_fast_cat as $fcat){
					$fcat = trim($fcat);
					if($fcat == 0){
						echo do_shortcode('[mocat new="1" title="'.__('最新发布','mobantu').'" orderby="'.(_MBT('home_orderby_modified')?'modified':'date').'" num="'.$fnum.'" link="'.get_permalink(MBThemes_page("template/all.php")).'"]');
					}else{
						echo do_shortcode('[mocat id="'.$fcat.'" num="'.$fnum.'" child="1"]');
					}
				}
				echo '</div>';
			}
		}else{
	?>
	<div class="container clearfix">
		<?php if($style == 'list' || $style == 'list-title') echo '<div class="content-wrap"><div class="content">';?>
		<?php if(_MBT('home_cat')){?>
		<div class="cat-nav-wrap">
			<ul class="cat-nav">
				<?php echo str_replace("</ul></div>", "", preg_replace("{<div[^>]*><ul[^>]*>}", "", wp_nav_menu(array('theme_location' => 'cat', 'echo' => false)) )); ?>
			</ul>
		</div>
		<?php }?>
		<div id="posts" class="posts <?php echo $cat_class;?> <?php if(_MBT('waterfall') && $style != 'list') echo 'waterfall';?> clearfix">
			<?php 
			  	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
				    //'ignore_sticky_posts' => 1,
				    'category__not_in' => explode(',', _MBT('home_cats_exclude')),
				    'orderby' => (_MBT('home_orderby_modified')=='2')?'rand':(_MBT('home_orderby_modified')?'modified':'date'),
				    'paged' => $paged
				);
				query_posts($args);
				$ccc = 'content';if($style == 'list') $ccc = 'content-list';elseif($style == 'grid-audio') $ccc = 'content-audio';elseif($style == 'list-title') $ccc = 'content-list-title';
				if ( have_posts() ){
					while ( have_posts() ) : the_post(); 
					get_template_part( 'module/'.$ccc );
					endwhile; 
					if(!_MBT('home_cats_exclude')){
						wp_reset_query(); 
					}
				}else{
                    get_template_part( 'module/none' );
                }
			?>
		</div>
		<?php MBThemes_paging();?>
		<?php if($style == 'list' || $style == 'list-title') {echo '</div></div>';get_sidebar();}?>
	</div>
	<?php }?>
	<?php if(_MBT('home_blog')) get_template_part("module/home-blogs");?>
	<?php if(_MBT('home_authors')) get_template_part("module/home-authors");?>
	<?php if(ERPHPDOWN_IS_ACTIVE && _MBT('home_vip') && (is_user_logged_in() || !_MBT('hide_user_all'))) get_template_part("module/vip");?>
	<?php if(_MBT('home_why')) get_template_part("module/why");?>
	<?php if(_MBT('home_total')) get_template_part("module/total");?>
	<?php if(_MBT('ad_home_footer_s')) {echo '<div class="container">';MBThemes_ad('ad_home_footer');echo '</div>';}?>
</div>
<?php get_footer();?>