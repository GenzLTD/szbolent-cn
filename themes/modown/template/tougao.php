<?php 
/*
	template name: 投稿
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
$post_tougao_role = _MBT('post_tougao_role')?_MBT('post_tougao_role'):'read';
if(!current_user_can($post_tougao_role)){
	wp_redirect(home_url());
	exit;
}

$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

$start_down = '';$start_down2 = '';$start_see = '';$start_see2 = '';

global $current_user;
$security_nonce = wp_create_nonce( time().rand(1000,9999) );
$_SESSION['security_nonce'] = $security_nonce;
if(isset($_POST['security_nonce']) && $_POST['security_nonce'] && !_MBT('post_tougao_ajax') && is_user_logged_in()){
	
	$post_tougao_time = _MBT('post_tougao_time');
	$last_post = $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_author='".$current_user->ID."' AND post_type = 'post' and (post_status='pending' or post_status='publish') ORDER BY post_date DESC LIMIT 1");
    if ( $post_tougao_time && (time() - strtotime($last_post) < $post_tougao_time*60) ){
        echo '<script>alert("'.__('这也太快了吧，先喝杯咖啡？','mobantu').'");</script>'; 
    }else{
    	if(isset($_POST['pid']) && $_POST['pid'] == $_SESSION['submit_post_id']){
    		if($_POST['pid']){
	    		$thepost = get_post($_POST['pid']);
				$postauthor = $thepost->post_author;
				if($postauthor == $current_user->ID){
					$title =   esc_sql($_POST['title']) ;
					$content =  $_POST['content'] ;
					if($title && $content && $_POST['cat']){
						$cat =  esc_sql($_POST['cat']);
						$post_status = 'pending';
						if(_MBT('post_tougao_submit')){
							$post_status = 'publish';
						}
						$submit = array(
							'ID' => esc_sql($_POST['pid']),
							'post_title' => strip_tags($title),
							'post_author' => $current_user->ID,
							'post_content' => $content,
							'post_category' => array($cat),
							'post_status' => $post_status
						);
						$status = wp_update_post( $submit );
						
						if ($status != 0) {

							$taxonomys = get_term_meta($cat,'taxonomys',true);
							if($taxonomys){
								$post_texonomys = explode('|', $taxonomys);
				                foreach ($post_texonomys as $post_texonomy) { 
				                    $post_texonomy = explode(',', $post_texonomy);
				                    wp_set_object_terms( $status, $_POST[$post_texonomy[1]], $post_texonomy[1] );
				                }
				            }

							if(isset($_POST['image']) &&  $_POST['image']){
								update_post_meta($status,'_thumbnail_ext_url',esc_sql($_POST['image']));
							}

							if(isset($_POST['video']) &&  $_POST['video']){
								update_post_meta($status,'video',esc_sql($_POST['video']));
							}

							if(!_MBT('post_tougao_erphpdown')){
								update_post_meta($status,'erphp_down',$_POST['erphp_down']);
								if(ERPHPDOWN_IS_ACTIVE && $_POST['erphp_down'] == '1'){
									update_post_meta($status,'start_down',"yes");
									update_post_meta($status,'member_down','1');
							        update_post_meta($status,'down_price',esc_sql($_POST['down_price']));
							        update_post_meta($status,'down_url',esc_sql($_POST['down_url']));
							        update_post_meta($status,'hidden_content',esc_sql($_POST['hidden_content'])); 
								}elseif(ERPHPDOWN_IS_ACTIVE && $_POST['erphp_down'] == '2'){
									update_post_meta($status,'start_see',"yes");
									update_post_meta($status,'member_down','1');
							        update_post_meta($status,'down_price',esc_sql($_POST['down_price']));
								}elseif(ERPHPDOWN_IS_ACTIVE && $_POST['erphp_down'] == '3'){
									update_post_meta($status,'start_see2',"yes");
									update_post_meta($status,'member_down','1');
							        update_post_meta($status,'down_price',esc_sql($_POST['down_price']));
								}

								if(isset($_POST['member_down'])){
									update_post_meta($status,'member_down',$_POST['member_down']);
								}
							}

							if(_MBT('post_tougao_submit')){
								wp_redirect(get_permalink($status));
							}else{
								echo '<script>alert("'.__('编辑成功，请等待管理员审核！','mobantu').'");</script>';
							}
						}else{
							echo '<script>alert("'.__('投稿失败，请稍后重试！','mobantu').'");</script>';
						}
					}else{
						echo '<script>alert("'.__('投稿失败，请完善标题、分类、正文！','mobantu').'");</script>';
					}
				}else{
					echo '<script>alert("'.__('提交失败，请稍后重试！','mobantu').'");</script>';
				}
			}else{
				$title =   esc_sql($_POST['title']) ;
				$content =  $_POST['content'] ;

				if($title && $content && $_POST['cat']){
					$cat =  esc_sql($_POST['cat']);
					$post_status = 'pending';
					if(_MBT('post_tougao_submit')){
						$post_status = 'publish';
					}
					$submit = array(
						'post_title' => strip_tags($title),
						'post_author' => $current_user->ID,
						'post_content' => $content,
						'post_category' => array($cat),
						'post_status' => $post_status
					);
					$status = wp_insert_post( $submit );
					
					if ($status != 0) {

						$taxonomys = get_term_meta($cat,'taxonomys',true);
						if($taxonomys){
							$post_texonomys = explode('|', $taxonomys);
			                foreach ($post_texonomys as $post_texonomy) { 
			                    $post_texonomy = explode(',', $post_texonomy);
			                    wp_set_object_terms( $status, $_POST[$post_texonomy[1]], $post_texonomy[1] );
			                }
			            }

						if(isset($_POST['image']) &&  $_POST['image']){
							update_post_meta($status,'_thumbnail_ext_url',esc_sql($_POST['image']));
						}

						if(isset($_POST['video']) &&  $_POST['video']){
							update_post_meta($status,'video',esc_sql($_POST['video']));
						}

						if(!_MBT('post_tougao_erphpdown')){
							update_post_meta($status,'erphp_down',$_POST['erphp_down']);
							if(ERPHPDOWN_IS_ACTIVE && $_POST['erphp_down'] == '1'){
								update_post_meta($status,'start_down',"yes");
								update_post_meta($status,'member_down','1');
						        update_post_meta($status,'down_price',esc_sql($_POST['down_price']));
						        update_post_meta($status,'down_url',esc_sql($_POST['down_url']));
						        update_post_meta($status,'hidden_content',esc_sql($_POST['hidden_content'])); 
							}elseif(ERPHPDOWN_IS_ACTIVE && $_POST['erphp_down'] == '2'){
								update_post_meta($status,'start_see',"yes");
								update_post_meta($status,'member_down','1');
						        update_post_meta($status,'down_price',esc_sql($_POST['down_price']));
							}elseif(ERPHPDOWN_IS_ACTIVE && $_POST['erphp_down'] == '3'){
								update_post_meta($status,'start_see2',"yes");
								update_post_meta($status,'member_down','1');
						        update_post_meta($status,'down_price',esc_sql($_POST['down_price']));
							}

							if(isset($_POST['member_down'])){
								update_post_meta($status,'member_down',$_POST['member_down']);
							}
						}

						if(_MBT('post_tougao_mail')){
							$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
                			wp_mail(get_option('admin_email'), '[' . get_option('blogname') . ']'.__('有新投稿','mobantu'), '[' . get_option('blogname') . ']'.__('有新投稿','mobantu'), $headers);
						}

						if(_MBT('post_tougao_submit')){
							wp_redirect(get_permalink($status));
						}else{
							echo '<script>alert("'.__('投稿成功，请等待管理员审核！','mobantu').'");</script>';
						}
					}else{
						echo '<script>alert("'.__('投稿失败，请稍后重试！','mobantu').'");</script>';
					}
				}else{
					echo '<script>alert("'.__('投稿失败，请完善标题、分类、正文！','mobantu').'");</script>';
				}
			}
		}else{
			echo '<script>alert("'.__('提交失败，请稍后重试！','mobantu').'");</script>';
		}
	}
}

$edit = 0;
$last_draft_post = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_author='".$current_user->ID."' AND post_type = 'post' and post_status='draft' ORDER BY post_date DESC LIMIT 1");

if(((isset($_GET['post_id']) && $_GET['post_id']) || $last_draft_post) && _MBT('post_tougao_edit')){
	if(isset($_GET['post_id']) && $_GET['post_id']){
		$post_id = $_GET['post_id'];
	}else{
		$post_id = $last_draft_post;
	}
	
	$post = get_post($post_id);
	$postauthor = $post->post_author;
	if($postauthor == $current_user->ID){
		$edit = 1;

		if($last_draft_post && !isset($_GET['post_id'])){
			$edit = 2;
		}

		$post_title = $post->post_title;
		$post_content = $post->post_content;
		$p_cat = get_the_category( $post_id );
		$p_image = MBThemes_thumbnail_share($post); 
		$down_price = get_post_meta($post_id, 'down_price', true);
		$erphp_down = get_post_meta($post_id, 'erphp_down', true);
		$start_down = get_post_meta($post_id, 'start_down', true);
		$start_see = get_post_meta($post_id, 'start_see', true);
		$start_see2 = get_post_meta($post_id, 'start_see2', true);
		$member_down = get_post_meta($post_id, 'member_down', true);
		$hidden_content = get_post_meta($post_id, 'hidden_content', true);
		$down_url = get_post_meta($post_id, 'down_url', true);
		$post_video = get_post_meta($post_id, 'video', true);
	}
}

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
		    			<h1 class="article-title tougao-title"><i class="icon icon-edit"></i> <?php if($edit == 1) echo __('编辑','mobantu');elseif($edit == 2) echo __('草稿','mobantu');else the_title(); ?></h1>
		    		</header>
		    		<div class="tougao-content">
		    			<form method="post">
		    				<div class="tougao-item">
		    					<label id="tougao-title-label"><?php _e('标题','mobantu');?> *</label>
		    					<input type="text" name="title" id="post_title" class="tougao-input" value="<?php if($edit) echo $post_title;?>" required="" />
		    				</div>
		    				<div class="tougao-item">
		    					<label id="tougao-cat-label"><?php _e('分类','mobantu');?> *</label>
		    					<div>
			    					<div class="tougao-select">
		    						<?php 
		    							if(!_MBT('post_tougao_cats')){
			    							wp_dropdown_categories('show_option_all='.__('选择分类','mobantu').'&orderby=name&hierarchical=1&depth=0&hide_empty=0&selected='.($edit?$p_cat[0]->term_id:''));
			    						}else{
			    							wp_dropdown_categories(
			    								array(
			    									'show_option_all'=>__('选择分类','mobantu'),
			    									'orderby'=>'name',
			    									'hierarchical'=>1,
			    									'selected'=>$edit?$p_cat[0]->term_id:'-1',
			    									'depth'=>0,
			    									'hide_empty'=>0,
			    									'walker'  => new Walker_Tougao_CategoryDropdown()
			    								)
			    							);
			    						}
		    						?>		
			    					</div> <div class="tougao-tax"></div>
			    				</div>
			    				<?php if($edit){?>
			    				<script>jQuery(function($){
			    					$("#cat").trigger("change");
			    				});</script>
			    				<?php }?>
		    				</div>
		    				<div class="tougao-item">
		    					<label><?php _e('封面图','mobantu');?></label>
		    					<div class="tougao-image-wrap clearfix">
			    					<div class="tougao-image-box tougao-upload" title="<?php _e('上传图片','mobantu');?>">
			    						<?php if($edit && $p_image){?>
			    						<img src="<?php echo $p_image;?>">
			    						<?php }else{?>
			    						<i class="icon icon-plus"></i><br><?php _e('上传图片','mobantu');?>
			    						<?php }?>
			    					</div>
			    					<div class="tougao-image-input">
				    					<input type="url" name="image" id="image" class="tougao-input" placeholder="<?php _e('请输入外链图片地址','mobantu');?>" value="<?php if($edit) echo $p_image;?>" />
				    					<span id="file-progress" class="file-progress"></span>
				    				</div>
			    				</div>
		    				</div>
		    				<div class="tougao-item">
		    					<label><?php _e('视频地址','mobantu');?></label>
		    					<input type="text" name="video" id="video" class="tougao-input" placeholder="选填，mp4视频地址或iframe嵌入播放地址" value="<?php if($edit) echo $post_video;?>" />
		    				</div>
		    				<div class="tougao-item">
		    					<label id="tougao-content-label"><?php _e('正文','mobantu');?> *</label>
		    					<?php 
		    					if($edit){
		    						$post = get_post($post_id);
			    					wp_editor( $post_content, 'content', MBThemes_editor_settings(array('textarea_name'=>'content', 'height'=>300, 'allow_img'=> 1)));
		    					}else{
			    					//require_once( ABSPATH . 'wp-admin/includes/post.php' );
									//$post = get_default_post_to_edit( 'post', true );
									//$post_id = $post->ID;
									$post_id = 0;
			    					wp_editor( '', 'content', MBThemes_editor_settings(array('textarea_name'=>'content', 'height'=>300, 'allow_img'=> 1)));
			    				}
			    				$_SESSION['submit_post_id'] = $post_id;
		    					?>
		    				</div>
		    				<?php if(ERPHPDOWN_IS_ACTIVE && !_MBT('post_tougao_erphpdown')){?>
		    				<div class="tougao-item">
		    					<label><?php _e('收费选项','mobantu');?></label>
		    					<div class="tougao-select">
		    						<input type="radio" name="erphp_down" id="start_down1" value="4" checked> <label for="start_down1"><?php _e('不启用','mobantu');?></label>
		    						<input type="radio" name="erphp_down" id="start_down2" value="1"<?php if($edit && $start_down) echo ' checked';?>> <label for="start_down2"><?php _e('收费下载','mobantu');?></label>
		    						<input type="radio" name="erphp_down" id="start_see1" value="2"<?php if($edit && $start_see) echo ' checked';?>> <label for="start_see1"><?php _e('收费查看全文','mobantu');?></label>
		    						<input type="radio" name="erphp_down" id="start_see2" value="3"<?php if($edit && $start_see2) echo ' checked';?>> <label for="start_see2"><?php _e('收费查看部分','mobantu');?> <span style="font-size: 12px;color: #aaa;"><?php _e('内容里用[erphpdown]隐藏内容[/erphpdown]','mobantu');?></span></label>
		    					</div>
		    					<p><?php _e('售卖总额的','mobantu');?> <?php
		    					$ice_ali_money_author = get_option('ice_ali_money_author');
								$user_ali_money_author = get_user_meta($current_user->ID, 'ice_ali_money_author',true);
								if($user_ali_money_author != '' && ($user_ali_money_author || $user_ali_money_author == 0)){
									$ice_ali_money_author = $user_ali_money_author;
								}
		    					 echo $ice_ali_money_author?$ice_ali_money_author:'100';?>% <?php _e('将直接进入您的网站账户','mobantu');?></p>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown tougao-item-erphpdown-see"<?php if($edit && $start_down || $start_see2 || $start_see) echo ' style="display:block"';?>>
		    					<label><?php _e('VIP优惠','mobantu');?></label>
		    					<div class="tougao-select">
		    						<input type="radio" name="member_down" id="member_down1" value="1" checked> <label for="member_down1"><?php _e('无','mobantu');?></label>
		    						<input type="radio" name="member_down" id="member_down3" value="3"<?php if($edit && $member_down==3) echo ' checked';?>> <label for="member_down3"><?php _e('VIP免费','mobantu');?></label>
		    						<?php if(get_option('erphp_quarter_price')){?><input type="radio" name="member_down" id="member_down16" value="16"<?php if($edit && $member_down==16) echo ' checked';?>> <label for="member_down16"><?php echo sprintf(__('%s免费','mobantu'),$erphp_quarter_name);?></label><?php }?>
		    						<?php if(get_option('erphp_year_price')){?><input type="radio" name="member_down" id="member_down6" value="6"<?php if($edit && $member_down==6) echo ' checked';?>> <label for="member_down6"><?php echo sprintf(__('%s免费','mobantu'),$erphp_year_name);?></label><?php }?>
		    						<?php if(get_option('erphp_life_price')){?><input type="radio" name="member_down" id="member_down7" value="7"<?php if($edit && $member_down==7) echo ' checked';?>> <label for="member_down7"><?php echo sprintf(__('%s免费','mobantu'),$erphp_life_name);?></label><?php }?>
		    					</div>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown tougao-item-erphpdown-see"<?php if($edit && $start_down || $start_see2 || $start_see) echo ' style="display:block"';?>>
		    					<label><?php _e('价格','mobantu');?></label>
		    					<input type="number" name="down_price" id="down_price" class="tougao-input" min="0" step="0.01" value="<?php if($edit) echo $down_price;?>" style="width:150px;"/>
		    					<p><?php _e('留空或0则表示免费','mobantu');?></p>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown"<?php if($edit && $start_down) echo ' style="display:block"';?>>
		    					<label><?php _e('下载地址','mobantu');?></label>
		    					<div class="tougao-file-wrap clearfix">
			    					<div class="tougao-file-box tougao-upload2" title="<?php _e('上传附件','mobantu');?>"><i class="icon icon-plus"></i><br><?php _e('上传附件','mobantu');?><br><font style="font-size: 12px;"><?php _e('支持.zip .rar .7z','mobantu');?></font></div>
			    					<div class="tougao-file-input">
				    					<textarea name="down_url" id="down_url" class="tougao-input" placeholder="<?php _e('请输入附件下载地址，一行一个','mobantu');?>"><?php if($edit) echo $down_url;?></textarea>
				    					<span id="file-progress2" class="file-progress"></span>
				    				</div>
			    				</div>
		    				</div>
		    				<div class="tougao-item tougao-item-erphpdown"<?php if($edit && $start_down) echo ' style="display:block"';?>>
		    					<label><?php _e('提取码','mobantu');?></label>
		    					<input type="text" name="hidden_content" id="hidden_content" value="<?php if($edit) echo $hidden_content;?>" class="tougao-input" style="width:200px;"/>
		    					<p><?php _e('提取码或者解压密码','mobantu');?></p>
		    				</div>
		    				<?php }?>
		    				<div class="tougao-item" style="text-align: right;">
		    					<button class="tougao-btn2 tougao-draft" type="button"><?php _e('保存草稿','mobantu');?></button>
		    					<?php if(_MBT('post_tougao_ajax')){?>
		    					<button class="tougao-btn tougao-submit" type="button"><?php _e('提交','mobantu');?></button>
		    					<?php }else{?>
		    					<button class="tougao-btn" type="submit"><?php _e('提交','mobantu');?></button>
		    					<?php }?>
		    					<input type="hidden" name="security_nonce" id="security_nonce" value="<?php echo $security_nonce;?>">
		    					<input type="hidden" name="pid" id="pid" value="<?php echo $post_id?>" />
		    					<input type="hidden" name="edit" id="edit" value="<?php echo $edit?>" />
		    				</div>
		    			</form>
		    			<?php if(_MBT('tougao_upload')){?>
		    			<form style="display:none" id="imageForm" action="<?php bloginfo("template_url");?>/action/image.php" enctype="multipart/form-data" method="post"><input type="file" id="imageFile" name="imageFile" accept="image/png, image/jpeg"></form>
		    			<form style="display:none" id="fileForm" action="<?php bloginfo("template_url");?>/action/file.php" enctype="multipart/form-data" method="post"><input type="file" id="fileFile" name="fileFile" accept=".zip, .rar, .7z"></form><?php }?>
		    		</div>
	            </article>
	    	</div>
	    </div>
	    <div class="sidebar"><div class="theiaStickySidebar"><div class="widget"><h3><i class="icon icon-horn"></i> <?php _e('投稿说明','mobantu');?></h3><div class="textwidget custom-html-widget"><?php while (have_posts()) : the_post(); the_content(); endwhile;?></div></div></div></div>
	</div>
</div>
<?php } get_footer();?>