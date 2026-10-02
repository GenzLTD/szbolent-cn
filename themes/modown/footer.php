<footer class="footer">
	<div class="container">
	    <?php if(!_MBT('footer_widget')){?>
		<div class="footer-widgets">
	    	<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('widget_bottom')) : endif; ?>
	    </div>
	    <?php }?>
	    <?php wp_reset_query(); if(_MBT('friendlink') && (is_home() || is_front_page()) && !is_paged()){?>
	    <div class="footer-links">
	    	<ul><li><?php _e('友情链接：','mobantu');?></li><?php wp_list_bookmarks('title_li=&categorize=0&show_images=0&orderby=rating&order=desc&category='._MBT('friendlink_id')); ?></ul>
	    </div>
	    <?php }?>
	    <div class="copyright"><?php echo _MBT('copyright')?wpautop(_MBT('copyright')):(__('自豪的采用','mobantu').' <a href="http://modown.mobantu.com" target="_blank">Modown</a> '.__('主题','mobantu'));?></div>
	</div>
</footer>

<?php if(_MBT("bought_barrage")){
	$bought_barrage_day = _MBT("bought_barrage_day")?_MBT("bought_barrage_day"):90;
	$bought_barrage_id = $wpdb->get_var("SELECT ice_post FROM $wpdb->icealipay where ice_success=1 and ice_time>DATE_SUB(CURDATE(), INTERVAL ".$bought_barrage_day." DAY) order by rand() limit 1");
	if($bought_barrage_id){
		$bought_barrage_post = get_post($bought_barrage_id);
		if($bought_barrage_post){
			$bought_barrage_time = array("刚刚","1分钟前","3分钟前","5分钟前","6分钟前","8分钟前","9分钟前","10分钟前");
?>
<div class="bought-barrage bought-barrage-fixed">
	<div class="items">
		<div class="pro-item">
			<a href="<?php the_permalink($bought_barrage_id);?>" target="_blank" rel="nofollow"><img src="<?php echo MBThemes_thumbnail_post($bought_barrage_id);?>" alt="<?php echo $bought_barrage_post->post_title?>"></a>
			<div class="tit"><?php echo $bought_barrage_post->post_title?></div>
			<span><?php echo $bought_barrage_time[rand(0,7)];?> 有人购买</span>
			<a href="<?php the_permalink($bought_barrage_id);?>" target="_blank" class="btt">去瞅瞅看</a>
		</div>
	</div>
	<a href="javascript:;" class="close"><i class="icon icon-close"></i></a>
</div>
<script>
	jQuery(function($){
	    setTimeout(function(){
	    	$(".bought-barrage-fixed").css("left","20px")
	    },"5000");
	    setTimeout(function(){
	    	$(".bought-barrage-fixed").css("left","-800px")
	    },"30000");
	    $(".bought-barrage-fixed .close").click(function(){
	        $(".bought-barrage-fixed").css("left","-800px");
	    });
	});
</script>
<?php
		}
	}
}?>

<?php if(_MBT('rollbar')){?>
<div class="rollbar">
	<ul>
		<?php if(_MBT('kefu_qq')){?><li class="qq-li"><a href="<?php echo _MBT('kefu_qq');?>" target="_blank" rel="nofollow"><i class="icon icon-guru2"></i></a><h6><?php _e('在线客服','mobantu');?><i></i></h6></li><?php }?>
		<?php if(_MBT('kefu_weixin')){?><li class="wx-li"><a href="javascript:;" class="kefu_weixin"><i class="icon icon-weixin"></i><img src="<?php echo _MBT('kefu_weixin');?>"></a></li><?php }?>
		<?php if(!_MBT('vip_hidden') && (is_user_logged_in() || !_MBT('hide_user_all'))){?><li class="vip-li"><a href="<?php echo get_permalink(MBThemes_page("template/vip.php"));?>"><i class="icon icon-crown-s"></i></a><h6><?php _e('升级','mobantu');?><?php $erphp_vip_name = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP'; echo $erphp_vip_name;?><i></i></h6></li><?php }?>
		<?php 
			if(ERPHPDOWN_IS_ACTIVE && get_option('ice_ali_money_checkin')) {
		?>
		<?php if(is_user_logged_in()){global $current_user;?>
			<?php if(erphpdown_check_checkin($current_user->ID)){?>
			<li><a href="javascript:;" class="day-checkin active"><i class="icon icon-calendar"></i></a><h6><?php _e('每日签到','mobantu');?><i></i></h6></li>
			<?php }else{?>
			<li><a href="javascript:;" class="day-checkin"><i class="icon icon-calendar"></i></a><h6><?php _e('每日签到','mobantu');?><i></i></h6></li>
			<?php }?>
		<?php }else{?>
			<li><a href="javascript:;" class="signin-loader"><i class="icon icon-calendar"></i></a><h6><?php _e('每日签到','mobantu');?><i></i></h6></li>
		<?php }?>
		<?php
			}
		?>
		<?php if(_MBT('fullscreen')){?><li><a href="javascript:;" class="fullscreen"><i class="icon icon-fullscreen"></i></a><h6><?php _e('全屏浏览','mobantu');?><i></i></h6></li><?php }?>
		<?php if(_MBT('theme_night')){
			$night_class = '';
			if(isset($_COOKIE['mbt_theme_night'])){
			    if($_COOKIE['mbt_theme_night'] == '1'){
			      	$night_class = ' active';
			    }
			}elseif(_MBT('theme_night_default')){
			    $night_class = ' active';
			}elseif(_MBT('theme_night_auto')){
			    $time = intval(date("Hi"));
			    if ($time < 730 || $time > 1930) {
			      	$night_class = ' active';
			    }
			}
		?><li><a href="javascript:;" class="theme_night<?php echo $night_class;?>"><i class="icon icon-moon" style="top:0"></i></a><h6><?php _e('夜间模式','mobantu');?><i></i></h6></li><?php }?>
		<?php if(_MBT('theme_fan')){?><li><a href="javascript:zh_tran2();" class="zh_click"><i class="icon icon-fan" style="top:0"></i></a><h6><?php _e('繁简切换','mobantu');?><i></i></h6></li><?php }?>
		<li class="totop-li"><a href="javascript:;" class="totop"><i class="icon icon-arrow-up"></i></a><h6><?php _e('返回顶部','mobantu');?><i></i></h6></li>    
	</ul>
</div>
<?php } ?>

<?php if(_MBT('footer_nav')){
	$footer_nav_style = '';
	$footer_nav_class = '';
	if(isset($_COOKIE['mbt_footer_nav']) && $_COOKIE['mbt_footer_nav'] == '1'){
		$footer_nav_style = ' style="height:0px;"';
		$footer_nav_class = ' active';
	}
?>
<div class="footer-fixed-nav footer-fixed-nav<?php echo _MBT('footer_nav_num');?> clearfix"<?php echo $footer_nav_style;?>>
	<?php if(_MBT('footer_nav_html')){
		echo _MBT('footer_nav_html');
	}else{?>
		<a href="<?php echo home_url();?>"><i class="icon icon-home2<?php if(is_home() || is_front_page()) echo '-s';?>"></i><span><?php _e('首页','mobantu');?></span></a>
		<a href="<?php echo get_permalink(MBThemes_page("template/all.php"));?>"><i class="icon icon-find<?php if(is_page_template("template/all.php")) echo '-s';?>"></i><span><?php _e('发现','mobantu');?></span></a>
		<a href="<?php echo get_permalink(MBThemes_page("template/vip.php"));?>" class="special"><i class="icon icon-crown<?php if(is_page_template("template/vip.php")) echo '-s';?>"></i><span>VIP</span></a>
		<?php if(_MBT('footer_nav_num') == '5'){?><a href="<?php echo _MBT('kefu_qq');?>" target="_blank" rel="nofollow"><i class="icon icon-guru2"></i><span><?php _e('客服','mobantu');?></span></a><?php }?>
	<?php }?>
	<?php if(is_user_logged_in()){?>
	<a href="<?php echo get_permalink(MBThemes_page("template/user.php"));?>" class="footer-fixed-nav-user"><i class="icon icon-discuz<?php if(is_page_template("template/user.php")) echo '-s';?>"></i><span><?php _e('我的','mobantu');?></span></a>
	<?php }else{?>
	<a href="<?php echo get_permalink(MBThemes_page("template/login.php"));?>" class="footer-fixed-nav-user signin-loader"><i class="icon icon-discuz"></i><span><?php _e('我的','mobantu');?></span></a>
	<?php }?>
</div>
<?php }?>

<?php if(_MBT('site_tips')){?>
<?php if(_MBT('site_tips_style')){?>
<div class="sitetips sitetips-pop">
	<div class="sitetips-header"><h3><?php _e('站点提示','mobantu');?></h3></div>
	<div class="sitetips-main"><?php echo do_shortcode(_MBT('site_tips'));?></div>
	<a href="javascript:;" class="close"><i class="icon icon-close"></i></a>
</div>
<div class="sitetips-pop-shadow"></div>
<?php }else{?>
<div class="sitetips sitetips-default">
	<i class="icon icon-horn"></i> <?php echo do_shortcode(_MBT('site_tips'));?><a href="javascript:;" class="close"><i class="icon icon-close"></i></a>
</div>
<?php }?>
<?php }?>

<?php if(_MBT('ad_fixed_top_s')) {echo '<div class="modown-fixed-da'.(_MBT('ad_fixed_top_m')?' modown-ad-mobile-hide':'').'">';MBThemes_ad('ad_fixed_top');echo '<i class="icon icon-close" title="'.__('关闭','mobantu').'"></i></div>';}?>

<?php if(!is_user_logged_in()) get_template_part('module/login');?>

<?php if(_MBT('theme_fan')){if(_MBT('theme_fan_default')){?><script>var zh_autoLang_t=true;var zh_autoLang_s=false;</script><script src="<?php echo THEME_URI;?>/static/js/chinese.js"></script><?php }else{?><script>var zh_autoLang_t=false;var zh_autoLang_s=true;</script><script src="<?php echo THEME_URI;?>/static/js/chinese.js"></script><?php }}?>
<script>
	window._MBT = {uri: '<?php echo THEME_URI; ?>', child: '<?php echo STYLESHEET_URI;?>', urc: '<?php if(file_exists(STYLESHEET_DIR.'/action/mocat.php')) echo '1';else echo '0'; ?>', uru: '<?php if(file_exists(STYLESHEET_DIR.'/action/user.php')) echo '1';else echo '0'; ?>', url:'<?php bloginfo('url');?>', urg: '<?php if(file_exists(STYLESHEET_DIR.'/action/login.php')) echo '1';else echo '0'; ?>', usr: '<?php echo get_permalink(MBThemes_page("template/user.php"));?>', roll: [<?php echo _MBT('sidebar_fixed');?>], admin_ajax: '<?php echo admin_url('admin-ajax.php');?>', erphpdown: '<?php if(defined("erphpdown")) echo constant("erphpdown");?>', image: '<?php $default_width = 285;if(_MBT('list_column') == 'five-mini'){$default_width = 228;}elseif(_MBT('list_column') == 'six-mini'){$default_width = 188;}elseif(_MBT('list_column') == 'four-large'){$default_width = 320;} if(_MBT('timthumb_height')) echo round(_MBT('timthumb_height')/$default_width,4);else echo '0.6316';?>', hanimated: '<?php if(_MBT('header_animated')) echo '1';else echo '0';?>', fancybox: '<?php if(_MBT('post_fancybox')) echo '1';else echo '0';?>', anchor: '<?php if(_MBT('home_anchor')) echo '1';else echo '0';?>', loading: '<?php echo _MBT('thumbnail_loading');?>', nav: '<?php echo _MBT('nav_position');?>', iframe: '<?php if(_MBT('post_iframe_fullwidth')) echo '1';else echo '0';?>', video_full: '<?php if(_MBT('post_video_fullwidth2')) echo '1';else echo '0';?>',tuan: '<?php if(_MBT('plugin_tuan')) echo '1';else echo '0';?>',down_fixed: '<?php if(_MBT("down_position_fixed")) echo '1'; else echo '0';?>', post_h3_nav: '<?php if(_MBT("post_h3_nav")) echo '1'; else echo '0';?>'};</script>
<?php wp_footer();?>
<script>MOBANTU.init({ias: <?php echo _MBT('ajax_list_load')?'1':'0';?>, lazy: <?php echo _MBT('lazyload')?'1':'0';?>, plazy: <?php echo _MBT('post_lazyload')?'1':'0';?>, water: <?php echo MBTheme_waterfall();?>, mbf: <?php if(_MBT('oauth_sms') && _MBT('oauth_sms_first')) echo '1'; else echo '0';?>, mpf: <?php if(_MBT('oauth_weixin_mp') && _MBT('oauth_weixin_mp_first') && function_exists('ews_login')) echo '1'; else echo '0';?>, mpfp: <?php if(get_option("ews_pro") && function_exists('ews_login')) echo '1'; else echo '0';?>});<?php if(_MBT('frontend_copy') && !current_user_can('administrator')){?>document.oncontextmenu = new Function("return false;");<?php }?></script>
<?php echo _MBT('js');?>
<div class="analysis"><?php echo _MBT('analysis');?></div>
</body></html>