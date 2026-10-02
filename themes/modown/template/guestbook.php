<?php 
/*
	template name: 留言板
	description: template for mobantu.com modown theme 
*/
get_header();
if(MBThemes_page_role()){
	$vip_see = get_post_meta(get_the_ID(),'vip_see',true);
	echo '<div class="only-erphpdown-vip"><div class="container"><a href="'.get_permalink(MBThemes_page("template/vip.php")).'"><i class="icon icon-crown-s"></i></a><br><p>'.sprintf(__('此内容仅限%s查看','mobantu'), getVipTypeName($vip_see)).'</p></div></div>';
}else{
?>
<div class="banner-page" <?php if(_MBT('banner_page_img')){?> style="background-image: url(<?php echo _MBT('banner_page_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1><br>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap">
	    	<div class="content">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<div class="article-content"><?php the_content(); ?></div>
	            </article>
	            <?php endwhile;  ?>
	            <?php comments_template('', true); ?>
	    	</div>
	    </div>
	</div>
</div>
<?php } get_footer();?>