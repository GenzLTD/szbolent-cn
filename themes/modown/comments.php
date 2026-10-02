<?php
if(!is_user_logged_in() && _MBT('hide_user_all')) return;

if ( get_post_type() != 'question' && get_option('default_comment_status') !== 'open' ) return;
defined('ABSPATH') or die('This file can not be loaded directly.');

if (!comments_open()) return;

$tit = __('评论','mobantu');
if(is_page_template('template/guestbook.php')){
	$tit = __('留言','mobantu');
}elseif(get_post_type() == 'question'){
	$tit = __('回答','mobantu');
}

$count_t = $post->comment_count;

date_default_timezone_set('PRC');
$closeTimer = (strtotime(date('Y-m-d G:i:s'))-strtotime(get_the_time('Y-m-d G:i:s')))/86400;
?>
<div class="single-comment">
	<h3 class="comments-title" id="comments">
		<i class="icon icon-comments"></i> <?php echo $tit;?><small><?php echo $count_t ? $count_t : '0'; ?></small>
	</h3>
	<div id="respond" class="comments-respond no_webshot">
		<?php if ( get_option('comment_registration') && !is_user_logged_in() ) { ?>
		<div class="comment-signarea"><?php _e('请先','mobantu');?> <a href="javascript:;" class="signin-loader"><?php _e('登录','mobantu');?></a></div>
		<?php }elseif( get_option('close_comments_for_old_posts') && $closeTimer > get_option('close_comments_days_old') ) { ?>
		<h3><?php echo $tit;?><?php _e('评论已关闭！','mobantu');?></h3>
		<?php }else{ ?>
		
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
			<div class="comt">
				<div class="comt-title">
					<?php 
						if ( is_user_logged_in() ) {
							global $current_user;
							MBThemes_avatar($current_user->ID);
							echo '<p>'.$user_identity.'</p>';
						}else{
							
							MBThemes_avatar();
							
							if ( !empty($comment_author) ){
								echo '<p>'.$comment_author.'</p>';
								echo '<p><a href="javascript:;" class="comment-user-change">'.__('更换','mobantu').'</a></p>';
							}
						}
					?>
					<p><a id="cancel-comment-reply-link" href="javascript:;"><?php _e('取消','mobantu');?></a></p>
				</div>
				<?php if ( !is_user_logged_in() ) { ?>
					<?php if( get_option('require_name_email') ){ ?>
						<div class="comt-comterinfo" id="comment-author-info" <?php if ( !empty($comment_author) ) echo 'style="display:none"'; ?>>
							<ul>
								<li><input class="ipt" type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="2" placeholder="<?php _e('昵称','mobantu');?>"></li>
								<li><input class="ipt" type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="3" placeholder="<?php _e('邮箱','mobantu');?>"></li>
								<li style="display: none"><input class="ipt" type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="4" placeholder="<?php _e('网址','mobantu');?>"></li>
							</ul>
						</div>
					<?php } ?>
				<?php } ?>
				<div class="comt-box">
					<textarea placeholder="<?php _e('写点什么...','mobantu');?>" class="comt-area" name="comment" id="comment" cols="100%" rows="3" tabindex="1" onkeydown="if(event.ctrlKey&amp;&amp;event.keyCode==13){document.getElementById('submit').click();return false};"></textarea>
				</div>
				<div class="comt-ctrl">
					<?php if(_MBT('post_comment_smile')){?>
					<a class="comt-add-btn" href="javascript:;" id="addsmile"><i class="icon icon-smile"></i></a>
					<div class="smile"> <div class="clearfix"> <a href="javascript:grin(':razz:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/razz.png" class="d-block"></a><a href="javascript:grin(':evil:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/evil.png" class="d-block"></a><a href="javascript:grin(':exclaim:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/exclaim.png" class="d-block"></a><a href="javascript:grin(':smile:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/smile.png" class="d-block"></a><a href="javascript:grin(':redface:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/redface.png" class="d-block"></a><a href="javascript:grin(':biggrin:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/biggrin.png" class="d-block"></a><a href="javascript:grin(':eek:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/eek.png" class="d-block"></a><a href="javascript:grin(':confused:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/confused.png" class="d-block"></a><a href="javascript:grin(':idea:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/idea.png" class="d-block"></a><a href="javascript:grin(':lol:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/lol.png" class="d-block"></a><a href="javascript:grin(':mad:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/mad.png" class="d-block"></a><a href="javascript:grin(':twisted:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/twisted.png" class="d-block"></a><a href="javascript:grin(':rolleyes:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/rolleyes.png" class="d-block"></a><a href="javascript:grin(':wink:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/wink.png" class="d-block"></a><a href="javascript:grin(':cool:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/cool.png" class="d-block"></a><a href="javascript:grin(':arrow:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/arrow.png" class="d-block"></a><a href="javascript:grin(':neutral:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/neutral.png" class="d-block"></a><a href="javascript:grin(':cry:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/cry.png" class="d-block"></a><a href="javascript:grin(':mrgreen:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/mrgreen.png" class="d-block"></a><a href="javascript:grin(':drooling:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/drooling.png" class="d-block"></a><a href="javascript:grin(':persevering:')"><img src="<?php bloginfo('template_url');?>/static/img/smilies/persevering.png" class="d-block"></a> </div> </div>
					<?php }?>
					<div class="comt-tips"></div>
					<?php comment_id_fields(); do_action('comment_form', $post->ID); ?>
					<button class="comt-submit" type="submit" name="submit" id="submit" tabindex="5"><?php _e('提交','mobantu');?></button>
				</div>

				
			</div>

		</form>
		<?php } ?>
	</div>
	<?php  
	if ( have_comments() ) { 
		?>
		<div id="postcomments" class="postcomments">
			<ol class="commentlist">
				<?php wp_list_comments('type=comment&callback=MBThemes_comments_list'); ?>
			</ol>
			<div class="comments-pagination">
				<?php paginate_comments_links('prev_next=0');?>
			</div>
		</div>
		<?php 
	}?>
</div>



