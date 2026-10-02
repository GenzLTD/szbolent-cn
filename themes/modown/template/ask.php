<?php 
/*
	template name: 提问页面
	description: template for mobantu.com modown theme 
*/
if (!session_id()) session_start();
if(!is_user_logged_in()){
	wp_redirect(get_permalink(MBThemes_page("template/login.php")));
	exit;
}

if(MBThemes_page_role()){
	$vip_see = get_post_meta(get_the_ID(),'vip_see',true);
	echo '<div class="only-erphpdown-vip"><div class="container"><a href="'.get_permalink(MBThemes_page("template/vip.php")).'"><i class="icon icon-crown-s"></i></a><br><p>'.sprintf(__('此内容仅限%s查看','mobantu'), getVipTypeName($vip_see)).'</p></div></div>';
}else{
date_default_timezone_set('Asia/Shanghai');

global $current_user;
$security_nonce = wp_create_nonce( time().rand(1000,9999) );
$_SESSION['security_nonce'] = $security_nonce;
get_header();
?>
<style>@media (max-width: 1024px){
	.content-wrap{float: none;}
	.sidebar {display: block !important;position: static;float: none;width: 100%;margin-left: 0;}
	.sidebar .theiaStickySidebar{position: static;transform: translateY(0);}
}</style>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container">
		<div class="content-wrap">
	    	<div class="content">
	    		<article class="single-content">
		    		<header class="article-header">
		    			<h1 class="article-title tougao-title"><i class="icon icon-help"></i> <?php the_title(); ?></h1>
		    		</header>
		    		<div class="tougao-content">
		    			<form method="post">
		    				<div class="tougao-item">
		    					<label id="tougao-title-label"><?php _e('标题','mobantu');?> *</label>
		    					<input type="text" name="title" id="post_title" class="tougao-input" required="" />
		    				</div>
		    				<div class="tougao-item">
		    					<label id="tougao-cat-label"><?php _e('分类','mobantu');?> *</label>
		    					<div>
			    					<div class="tougao-select">
		    						<select id="cat" name="cat" class="postform">
		    							<option value="0">选择分类</option>
		    							<?php 
										  	$question_category = get_terms(array(
												'taxonomy' => 'question_category',
												'hide_empty' => false
											));
											if ( $question_category && ! is_wp_error( $question_category ) ){
												foreach ($question_category as $blogc) {
													echo '<option value="'.$blogc->term_id.'">'.$blogc->name.'</option>';
												}
											}
										?>
		    						</select>
			    					</div>
			    				</div>
		    				</div>
		    				<div class="tougao-item">
		    					<label id="tougao-content-label"><?php _e('问题描述','mobantu');?> *</label>
		    					<?php 
			    					wp_editor( '', 'content', MBThemes_editor_settings(array('textarea_name'=>'content', 'height'=>300, 'allow_img'=> 1)));
		    					?>
		    				</div>
		    				<div class="tougao-item" style="text-align: right;">
		    					<button class="tougao-btn question-submit" type="button"><?php _e('提交','mobantu');?></button>
		    					<input type="hidden" name="security_nonce" id="security_nonce" value="<?php echo $security_nonce;?>">
		    				</div>
		    			</form>
		    		</div>
	            </article>
	    	</div>
	    </div>
	    <div class="sidebar"><div class="theiaStickySidebar"><div class="widget"><h3><i class="icon icon-horn"></i> <?php _e('提问说明','mobantu');?></h3><div class="textwidget custom-html-widget"><?php while (have_posts()) : the_post(); the_content(); endwhile;?></div></div></div></div>
	</div>
</div>
<?php } get_footer();?>