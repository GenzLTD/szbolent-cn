<?php 
/*
	template name: 全宽页面
	description: template for mobantu.com modown theme 
*/
get_header();
if(MBThemes_page_role()){
	$vip_see = get_post_meta(get_the_ID(),'vip_see',true);
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
	if($vip_see == '6'){
		$vip_name = $erphp_vip_name;
	}else{
		$vip_name = getVipTypeName($vip_see);
	}
	echo '<div class="only-erphpdown-vip"><div class="container"><a href="'.get_permalink(MBThemes_page("template/vip.php")).'"><i class="icon icon-crown-s"></i></a><br><p>'.sprintf(__('此内容仅限%s查看','mobantu'), $vip_name).'</p></div></div>';
}else{
?>
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
		    		<div class="article-content clearfix">
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
		    		<?php endwhile;  ?>
	            </article>
	            <?php comments_template('', true); ?>
	    	</div>
	    </div>
	</div>
</div>
<?php } get_footer();?>