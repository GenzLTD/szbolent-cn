<?php 
/*
	template name: 首页模板三
	description: template for mobantu.com modown theme 
*/
get_header();$style = _MBT('list_style');$cat_class = 'grids'; if($style == 'list') $cat_class = 'lists';elseif($style == 'list2') $cat_class = 'lists cols-two'; elseif($style == 'list3') $cat_class = 'lists cols-three';elseif($style == 'list-title') $cat_class = 'lists cols-title';?>
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
	<div class="contents">
		<div class="mocat" id="mocat-new">
			<div class="container">
				<h2><span><?php _e('最新发布','mobantu');?><i>NEW</i></span></h2>
				<div class="<?php echo $cat_class;?> clearfix">
					<?php 
				  	if(is_front_page()){
	                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
	                }else{
	                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	                }

				  	$args = array(
	                    'post_type' => 'post',
	                    'category__not_in' => explode(',', _MBT('home_cats_exclude')),
					    //'ignore_sticky_posts' => 1,
					    'paged' => $paged
					);
	                    
				  	//$arms = array_merge($args, $wp_query->query);
					query_posts($args);
	                $ccc = 'content';if($style == 'list') $ccc = 'content-list';elseif($style == 'grid-audio') $ccc = 'content-audio';elseif($style == 'list-title') $ccc = 'content-list-title';
	                if ( have_posts() ){
						while ( have_posts() ) : the_post(); 
						get_template_part( 'module/'.$ccc );
						endwhile; //wp_reset_query(); 
					}else{
	                    get_template_part( 'module/none' );
	                }
				?>
				</div>
				<?php MBThemes_paging();?>
			</div>
		</div>
		<?php wp_reset_query(); while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>
		<?php endwhile;  ?>
	</div>
	<?php if(_MBT('home_blog')) get_template_part("module/home-blogs");?>
	<?php if(_MBT('home_authors')) get_template_part("module/home-authors");?>
	<?php if(ERPHPDOWN_IS_ACTIVE && _MBT('home_vip') && (is_user_logged_in() || !_MBT('hide_user_all'))) get_template_part("module/vip");?>
	<?php if(_MBT('home_why')) get_template_part("module/why");?>
	<?php if(_MBT('home_total')) get_template_part("module/total");?>
	<?php if(_MBT('ad_home_footer_s')) {echo '<div class="container">';MBThemes_ad('ad_home_footer');echo '</div>';}?>
</div>
<?php get_footer();?>