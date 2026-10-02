<?php 
/*
	template name: 发卡订单查询
	description: template for mobantu.com modown theme 
*/
if (!session_id()) session_start();
get_header();?>
<div class="banner-page" <?php if(_MBT('banner_page_img')){?> style="background-image: url(<?php echo _MBT('banner_page_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container">
		<div class="content-wrap">
	    	<div class="content">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
		    		<div class="article-content clearfix">
		    			<?php the_content(); ?>
		    			<div class="erphp-faka-query-wrap">
		    				<div class="clearfix"><input type="text" id="erphp_faka_order" placeholder="<?php _e('请输入FK开头的订单号或邮箱','mobantu');?>" />
							<a href="javascript:;" class="erphp-faka-query"><?php _e('查询','mobantu');?></a></div>
							<input type="hidden" id="security_nonce" value="<?php $_SESSION['quka_security_nonce'] = wp_generate_password(12, false); echo $_SESSION['quka_security_nonce'];?>">
							<div class="results"></div>
						</div>
		            </div>
		    		<?php endwhile;  ?>
	            </article>
	            <?php comments_template('', true); ?>
	    	</div>
	    </div>
	</div>
</div>
<?php get_footer();?>