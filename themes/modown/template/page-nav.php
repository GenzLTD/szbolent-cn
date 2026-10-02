<?php 
/*
	template name: 菜单页面
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
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap content-nav">
			<div class="pageside">
				<div class="theiaStickySidebar">
					<a href="javascript:;" class="pagemenu-trigger"><i class="icon icon-menu"></i></a>
				    <div class="pagemenus">
				    	<ul class="pagemenu">
				        <?php echo str_replace("</ul></div>", "", preg_replace("{<div[^>]*><ul[^>]*>}", "", wp_nav_menu(array('theme_location' => 'page', 'echo' => false, 'fallback_cb'=> 'wp_menu_none')) )); ?>
				    	</ul>
				    </div>
				</div>
			</div>
	    	<div class="content" style="min-height: 500px;">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title center"><?php the_title(); ?></h1>
		    		</header>
		    		<div class="article-content">
		    			<?php the_content(); ?>
		            </div>
		    		<?php endwhile;  ?>
	            </article>
	            <?php comments_template('', true); ?>
	    	</div>
	    </div>
	</div>
</div>
<?php } get_footer();?>