<?php 
/*
	template name: 分类图标
	description: template for mobantu.com modown theme 
*/
get_header();
if(MBThemes_page_role()){
	$vip_see = get_post_meta(get_the_ID(),'vip_see',true);
	echo '<div class="only-erphpdown-vip"><div class="container"><a href="'.get_permalink(MBThemes_page("template/vip.php")).'"><i class="icon icon-crown-s"></i></a><br><p>'.sprintf(__('此内容仅限%s查看','mobantu'), getVipTypeName($vip_see)).'</p></div></div>';
}else{
?>
<style>
.home-caticons{margin:0}
</style>
<div class="banner-page" <?php if(_MBT('banner_page_img')){?> style="background-image: url(<?php echo _MBT('banner_page_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap">
	    	<div class="content">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		
		    			<?php 
		    				$cats = get_post_meta(get_the_ID(),'cats',true);
							$home_cats_icon = explode(',', $cats);
							if(is_array($home_cats_icon) && count($home_cats_icon)){
								echo '<div class="home-caticons"><div class="items clearfix">';
								foreach($home_cats_icon as $cat){
									if($cat == '0'){
										
									}else{
										$term = get_term_by('id',$cat,'category');
										if($term){
											$thumb_img = get_term_meta($cat,'thumb_icon',true);
											echo '<div class="item"><a href="'.get_category_link($cat).'"><img src="'.$thumb_img.'" alt="'.$term->name.'"><h4>'.$term->name.'</h4></a></div>';
										}
									}
								}
								echo '</div></div>';
							}
						?>
		            
		    		<?php endwhile;  ?>
	            </article>
	            <?php comments_template('', true); ?>
	    	</div>
	    </div>
	</div>
</div>
<?php } get_footer();?>