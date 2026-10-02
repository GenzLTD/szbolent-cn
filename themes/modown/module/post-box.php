<?php 
	$erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
	$start_down=get_post_meta(get_the_ID(), 'start_down', true);
	$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
	if((ERPHPDOWN_IS_ACTIVE && _MBT('post_box_erphpdown') && ($start_down || $start_down2)) || !_MBT('post_box_erphpdown')){
?>
<div class="article-header-box clearfix">
	<div class="header-box-img">
		<img <?php if(_MBT('lazyload')) echo 'src="'.(_MBT('thumbnail_loading')?_MBT('thumbnail_loading'):THEME_URI.'/static/img/thumbnail.png').'"';?> <?php echo _MBT('lazyload')?'data-src':'src';?>="<?php if(_MBT('post_box_thumbnail_type') == 'scale-down'){
			echo MBThemes_thumbnail_full();
		}else{echo MBThemes_thumbnail("400","255");}?>" class="thumb" alt="<?php the_title();?>">
	</div>
	<div class="header-box-con">
		<?php if(!_MBT('post_box_leftside')) get_template_part('module/post-header');?>
		<?php MBThemes_ad('ad_post_box','','15');?>
		<?php
			if(function_exists('get_field_objects')){
                $fields = get_field_objects();
                if( $fields ){
                	uasort($fields,'mbt_compare_field');
                	echo '<div class="custom-metas">';
                    foreach( $fields as $field_name => $field ){
                    	if($field['value']){
	                        echo '<div class="meta">';
	                            echo '<span>' . $field['label'] . '：</span>';
	                            if(is_array($field['value'])){
	                            	if($field['type'] == 'link'){
	                            		echo '<a href="'.$field['value']['url'].'" target="'.$field['value']['target'].'">'.$field['value']['title'].'</a>';
	                            	}elseif($field['type'] == 'taxonomy'){
	                            		$tax_html = '';
	                            		foreach ($field['value'] as $tax) {
	                            			$term = get_term_by('term_id',$tax,$field['taxonomy']);
	                            			$tax_html .= '<a href="'.get_term_link($tax).'" target="_blank">'.$term->name.'</a>, ';
	                            		}
	                            		echo rtrim($tax_html, ', ');
	                            	}elseif($field['type'] == 'relationship' || $field['type'] == 'post_object'){
	                            		foreach ($field['value'] as $postr) {
											$field_value = mbt_object_to_array($postr);
		                            		echo '<a href="'.get_permalink($field_value['ID']).'" target="_blank">'.$field_value['post_title'].'</a> ';
		                            	}
		                            }else{
										echo implode(',', $field['value']);
									}
								}elseif(is_object($field['value'])){
									if($field['type'] == 'post_object'){
										$field_value = mbt_object_to_array($field['value']);
	                            		echo '<a href="'.get_permalink($field_value['ID']).'" target="_blank">'.$field_value['post_title'].'</a>';
	                            	}
								}else{
									if($field['type'] == 'radio'){
										$vv = $field['value'];
										echo $field['choices'][$vv];
									}else{
										if(isset($field['append'])){
													echo $field['value'].$field['append'];
												}else{
													echo $field['value'];
												}
									}
								}
	                        echo '</div>';
	                    }
                    }
                    echo '</div>';
                }else{
                	if(_MBT('post_box_excerpt')) echo '<div class="excerpt">'.MBThemes_get_excerpt(250).'</div>';
                }
            }else{
            	if(_MBT('post_box_excerpt')) echo '<div class="excerpt">'.MBThemes_get_excerpt(250).'</div>';
            }
		?>
		<?php
		if(ERPHPDOWN_IS_ACTIVE && (is_user_logged_in() || !_MBT('hide_user_all'))){
			if(MBThemes_check_reply()){
				$erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
				$start_down=get_post_meta(get_the_ID(), 'start_down', true);
				$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
				$days=get_post_meta(get_the_ID(), 'down_days', true);
				$price=get_post_meta(get_the_ID(), 'down_price', true);
				$price_type=get_post_meta(get_the_ID(), 'down_price_type', true);
				$url=get_post_meta(get_the_ID(), 'down_url', true);
				$urls=get_post_meta(get_the_ID(), 'down_urls', true);
				$url_free=get_post_meta(get_the_ID(), 'down_url_free', true);
				$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
				$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
				$down_activation=get_post_meta(get_the_ID(), 'down_activation', true);
				$demo=get_post_meta(get_the_ID(), 'demo', true);
				$demo_title=get_post_meta(get_the_ID(), 'demo_title', true);
				$demo_title = $demo_title?$demo_title:__("在线演示","mobantu");
				$userType=getUsreMemberType();

				if(function_exists('getUsreMemberCat')){
					if(is_single()){
						$categories = get_the_category();
						if ( !empty($categories) ) {
							$userCat=getUsreMemberCat(MBThemes_parent_cid($categories[0]->term_id));
							if(!$userType){
								if($userCat){
									$userType = $userCat;
								}
							}else{
								if($userCat){
									if($userCat > $userType){
										$userType = $userCat;
									}
								}
							}
						}
					}
				}
					
				$down_info = null;$downMsgFree = '';$yituan = '';$down_tuan=0;$down_demo = '';$down_repeat=0;$down_checkpan='';$down_info_repeat=null;$iframe='';$downclass='';$downMsg='';
				$erphp_addon_activation_vip = get_option('erphp_addon_activation_vip');

				$erphp_popdown = get_option('erphp_popdown');
				if($erphp_popdown){
					$downclass = ' erphpdown-down-layui';
					$iframe = '&iframe=1';
				}

				if(function_exists('erphpdown_tuan_install')){
					$down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
				}

				$down_repeat = get_post_meta(get_the_ID(), 'down_repeat', true);
				$down_user_ones=get_post_meta(get_the_ID(), 'down_user_ones',true);
				if($down_user_ones){
					if(is_user_logged_in()){
						global $current_user;
						$down_user_ones_arr = explode(',', str_replace('，', ',', $down_user_ones));
						if(is_array($down_user_ones_arr) && count($down_user_ones_arr) && in_array($current_user->user_login, $down_user_ones_arr)){
							$down_repeat = 1;
						}
					}
				}

				if($demo){
					$down_demo = '<a href="'.$demo.'" target="_blank" rel="nofollow" class="erphpdown-btn-large demo-demo">'.$demo_title.'</a>';
				}

				$erphp_super_name    = get_option('erphp_super_name')?get_option('erphp_super_name'):'全站通VIP';
				$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
				$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
				$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
				$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
				$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
				$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

				$erphp_life_discount    = get_option('erphp_life_discount');
				$erphp_year_discount    = get_option('erphp_year_discount');
				$erphp_quarter_discount = get_option('erphp_quarter_discount');
				$erphp_month_discount  = get_option('erphp_month_discount');
				$erphp_day_discount  = get_option('erphp_day_discount');

				$erphp_vip_discounts_tips = '<div class="vip-discounts-tips">';
				if($erphp_day_discount){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_day_name,$erphp_day_discount).'<br>';
				}
				if($erphp_month_discount){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_month_name,$erphp_month_discount).'<br>';
				}
				if($erphp_quarter_discount){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_quarter_name,$erphp_quarter_discount).'<br>';
				}
				if($erphp_year_discount){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_year_name,$erphp_year_discount).'<br>';
				}
				if($erphp_life_discount && $memberDown != 20){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_life_name,$erphp_life_discount).'<br>';
				}

				$erphp_vip_discounts_tips = rtrim($erphp_vip_discounts_tips,'<br>').'</div>';

				$erphp_vip_discounts = '<div class="vip-discounts">'.sprintf(__('%s折扣','mobantu'),$erphp_vip_name).$erphp_vip_discounts_tips.'</div>';
				
				$money_name = get_option("ice_name_alipay");
				
				$erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));
				if(get_option('erphp_url_front_vip')){
					$erphp_url_front_vip = get_option('erphp_url_front_vip');
				}
				$erphp_url_front_login = get_permalink(MBThemes_page("template/login.php"));

				$erphp_blank_domains = get_option('erphp_blank_domains')?get_option('erphp_blank_domains'):'pan.baidu.com';
				$erphp_colon_domains = get_option('erphp_colon_domains')?get_option('erphp_colon_domains'):'pan.baidu.com';

				if($down_tuan && is_user_logged_in()){
					global $current_user;
					$yituan = $wpdb->get_var("select ice_status from $wpdb->tuanorder where ice_user_id=".$current_user->ID." and ice_post=".get_the_ID()." and ice_status>0");
				}

				$content = '';

				if($url_free){
					$downMsgFree .= '<div class="erphpdown-child erphpdown-free">';
					$downList=explode("\r\n",$url_free);
					foreach ($downList as $k=>$v){
						$filepath = $downList[$k];
						if($filepath){

							if($erphp_colon_domains){
								$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
								foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
									if(strpos($filepath, $erphp_colon_domain)){
										$filepath = str_replace('：', ': ', $filepath);
										break;
									}
								}
							}

							$erphp_blank_domain_is = 0;
							if($erphp_blank_domains){
								$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
								foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
									if(strpos($filepath, $erphp_blank_domain)){
										$erphp_blank_domain_is = 1;
										break;
									}
								}
							}
							if(strpos($filepath,',')){
								$filearr = explode(',',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsgFree.="<div class='item2'>".__('下载地址','mobantu').($k+1)."<a href='".$filepath."' target='_blank' rel='nofollow' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
								}elseif($arrlength == 2){
									$downMsgFree.="<div class='item2'>".$filearr[0]."<a href='".$filearr[1]."' rel='nofollow' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
								}elseif($arrlength == 3){
									$filearr2 = str_replace('：', ': ', $filearr[2]);
									$downMsgFree.="<div class='item2'>".$filearr[0]."<a href='".$filearr[1]."' rel='nofollow' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a>".$filearr2."<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>".__('复制','mobantu')."</a></div>";
								}
							}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
								$filearr = explode('  ',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsgFree.="<div class='item2'>".__('下载地址','mobantu').($k+1)."<a href='".$filepath."' target='_blank' rel='nofollow' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
								}elseif($arrlength >= 2){
									$filearr2 = explode(':',$filearr[0]);
									$filearr3 = explode(':',$filearr[1]);
									$downMsgFree.="<div class='item2'>".$filearr2[0]."<a href='".trim($filearr2[1].':'.$filearr2[2])."' target='_blank' rel='nofollow' class='erphpdown-down'>".__('立即下载','mobantu')."</a>".__('提取码：','mobantu').trim($filearr3[1])."<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>".__('复制','mobantu')."</a></div>";
								}
							}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
								$filearr = explode(' ',$filepath);
								$arrlength = count($filearr);
								if($arrlength == 1){
									$downMsgFree.="<div class='item2'>".__('下载地址','mobantu').($k+1)."<a href='".$filepath."' target='_blank' rel='nofollow' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
								}elseif($arrlength == 2){
									$downMsgFree.="<div class='item2'>".$filearr[0]."<a href='".$filearr[1]."' rel='nofollow' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
								}elseif($arrlength >= 3){
									$downMsgFree.="<div class='item2'>".str_replace(':', '', $filearr[0])."<a href='".$filearr[1]."' target='_blank' rel='nofollow' class='erphpdown-down'>".__('立即下载','mobantu')."</a>".$filearr[2].' '.$filearr[3]."<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>".__('复制','mobantu')."</a></div>";
								}
							}else{
								$downMsgFree.="<div class='item2'>".__('下载地址','mobantu').($k+1)."<a href='".$filepath."' rel='nofollow' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
							}
						}
					}
					if(get_option('ice_tips_free')) $downMsgFree.='<div class="erphpdown-tips erphpdown-tips-free">'.get_option('ice_tips_free').'</div>';
					$downMsgFree .= '</div>';
					
				}
				
				if($start_down2){
					if($url){
						if(function_exists('epd_check_pan_callback')){
							if((function_exists('epd_check_pan_is') && epd_check_pan_is($url)) || strpos($url,'pan.baidu.com') !== false || strpos($url,'123865.com') !== false || strpos($url,'123684.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
								$down_checkpan = '<a class="erphpdown-btn-large erphpdown-buy erphpdown-checkpan2" href="javascript:;" data-id="'.get_the_ID().'" data-post="'.get_the_ID().'">'.__('点击检测网盘有效后购买','mobantu').'</a>';
							}
						}
						
						$content.='<div class="erphpdown" id="erphpdown" style="display:block"><span class="erphpdown-title">'.__('资源下载','mobantu').'</span>'.$downMsgFree;
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						if($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || !$price || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10)){
							$content .= '<div class="erphpdown-child"><span class="erphpdown-child-title">'.__('资源下载','mobantu').'</span>';
							$downList=explode("\r\n",trim($url));
							foreach ($downList as $k=>$v){
								$filepath = trim($downList[$k]);
								if($filepath){

									if($erphp_colon_domains){
										$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
										foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
											if(strpos($filepath, $erphp_colon_domain)){
												$filepath = str_replace('：', ': ', $filepath);
												break;
											}
										}
									}

									$erphp_blank_domain_is = 0;
									if($erphp_blank_domains){
										$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
										foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
											if(strpos($filepath, $erphp_blank_domain)){
												$erphp_blank_domain_is = 1;
												break;
											}
										}
									}
									if(strpos($filepath,',')){
										$filearr = explode(',',$filepath);
										$arrlength = count($filearr);
										if($arrlength == 1){
											$downMsg.="<div class='item2'><t>".__('下载地址','mobantu').($k+1)."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
										}elseif($arrlength == 2){
											$downMsg.="<div class='item2'><t>".$filearr[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
										}elseif($arrlength == 3){
											$filearr2 = str_replace('：', ': ', $filearr[2]);
											$downMsg.="<div class='item2'><t>".$filearr[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a>（".$filearr2."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>".__('复制','mobantu')."</a></div>";
										}
									}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
										$filearr = explode('  ',$filepath);
										$arrlength = count($filearr);
										if($arrlength == 1){
											$downMsg.="<div class='item2'><t>".__('下载地址','mobantu').($k+1)."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
										}elseif($arrlength >= 2){
											$filearr2 = explode(':',$filearr[0]);
											$filearr3 = explode(':',$filearr[1]);
											$downMsg.="<div class='item2'><t>".$filearr2[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a>（".__('提取码','mobantu').": ".trim($filearr3[1])."）<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>".__('复制','mobantu')."</a></div>";
										}
									}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
										$filearr = explode(' ',$filepath);
										$arrlength = count($filearr);
										if($arrlength == 1){
											$downMsg.="<div class='item2'><t>".__('下载地址','mobantu').($k+1)."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
										}elseif($arrlength == 2){
											$downMsg.="<div class='item2'><t>".$filearr[0]."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
										}elseif($arrlength >= 3){
											$downMsg.="<div class='item2'><t>".str_replace(':', '', $filearr[0])."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
										}
									}else{
										$downMsg.="<div class='item2'><t>".__('下载地址','mobantu').($k+1)."</t><a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>".__('立即下载','mobantu')."</a></div>";
									}
								}
							}
							$content .= $downMsg;	
							if($hidden){
								$content .= '<div class="erphpdown-item">'.__('提取码：','mobantu').$hidden.' <a class="erphpdown-copy" data-clipboard-text="'.$hidden.'" href="javascript:;">'.__('复制','mobantu').'</a></div>';
							}
							$content .= '</div>';
						}else{
							$content .= '<div class="erphpdown-box-tips">';
							if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
								$wppay_vip_name = $erphp_vip_name;
								if($memberDown == 16){
									$wppay_vip_name = $erphp_quarter_name;
								}elseif($memberDown == 6){
									$wppay_vip_name = $erphp_year_name;
								}elseif($memberDown == 7){
									$wppay_vip_name = $erphp_life_name;
								}
								$content .= __('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.__('元','mobantu').'，'.sprintf(__('%s免费','mobantu'),$wppay_vip_name).'<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$wppay_vip_name).'</a>';
							}else{
								$content .= __('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.__('元','mobantu');	
							}
							$content .= '</div>';
							if($down_checkpan){
								$content .= $down_demo.$down_checkpan;
							}else{
								$content .= $down_demo.'<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy erphpdown-btn-large" data-post="'.get_the_ID().'">'.__('立即购买','mobantu').'</a>';
							}
						}
						
						if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
						$content.='</div>';
					}

				}elseif($start_down){
					$content.='<div class="erphpdown erphpdown-header-box" id="erphpdown" style="display:block"><span class="erphpdown-title">'.__('资源下载','mobantu').'</span>'.$downMsgFree;

					if(_MBT('post_price_logged') && !is_user_logged_in() && ( !(!$price && $memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9) || (!$price && $memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9 && get_option('erphp_free_login')) ) ){
						$content .= "<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__('立即下载','mobantu')."</a>";
					}else{

						if($down_tuan == '2' && function_exists('erphpdown_tuan_install')){
							$tuanHtml = erphpdown_tuan_modown_html3();
							$content.= $tuanHtml;
						}else{
							$content .= '<div class="erphpdown-fee">';

							if($price_type){
								if($urls){
									$cnt = count($urls['index']);
					    			if($cnt){
					    				$content .= '<div class="erphpdown-children-nav">';
					    				for($i=0; $i<$cnt;$i++){
					    					$content .= '<a href="javascript:;" data-index="'.$i.'" class="'.($i==0?'active':'').'">'.$urls['name'][$i].'</a>';
					    				}
					    				$content .= '</div>';
					    			}
					    		}
					    	}

							if($price_type){
								if($urls){
									$cnt = count($urls['index']);
					    			if($cnt){
					    				for($i=0; $i<$cnt;$i++){
					    					$index = $urls['index'][$i];
					    					$index_name = $urls['name'][$i];
					    					$price = $urls['price'][$i];
					    					$index_url = $urls['url'][$i];
					    					$index_vip = $urls['vip'][$i];
					    					$indexMemberDown = $memberDown;
			            					if($index_vip){
			            						$indexMemberDown = $index_vip;
			            					}

			            					$down_checkpan = '';
			            					if(function_exists('epd_check_pan_callback')){
												if((function_exists('epd_check_pan_is') && epd_check_pan_is($index_url)) || strpos($index_url,'pan.baidu.com') !== false || strpos($index_url,'123865.com') !== false || strpos($index_url,'123684.com') !== false || (strpos($index_url,'lanzou') !== false && strpos($index_url,'.com') !== false) || strpos($index_url,'cloud.189.cn') !== false){
													$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan erphpdown-btn-large" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'">'.__('点击检测网盘有效后购买','mobantu').'</a>';
												}
											}
			            					
					    					$content .= '<div class="erphpdown-child erphpdown-child'.$i.'" style="'.($i==0?'display:block':'').'"><span class="erphpdown-child-title">'.$index_name.'</span>';
					    					if(is_user_logged_in() || ( ($userType && ($indexMemberDown==3 || $indexMemberDown==4)) || (($indexMemberDown==15 || $indexMemberDown==16) && $userType >= 8) || (($indexMemberDown==6 || $indexMemberDown==8) && $userType >= 9) || (($indexMemberDown==7 || $indexMemberDown==9 || $indexMemberDown==13 || $indexMemberDown==14 || $indexMemberDown==20) && $userType >= 10) )){
					    						$content .= '<div class="erphpdown-box-tips">';
												if($price){
													if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9)
														$content.=__('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.$money_name;
												}else{
													if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9)
														$content.=__('仅限注册用户下载','mobantu');
												}

												if($price || $indexMemberDown == 4 || $indexMemberDown == 15 || $indexMemberDown == 8 || $indexMemberDown == 9){
													global $wpdb;
													$user_info=wp_get_current_user();
													if($user_info->ID){
														$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_index='".$index."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
														if($days > 0 && $down_info){
															$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
															$nowDate = date('Y-m-d H:i:s');
															if(strtotime($nowDate) > strtotime($lastDownDate)){
																$down_info = null;
															}
														}

														if($down_repeat){
															$down_info_repeat = $down_info;
															$down_info = null;
														}
													}

													if($down_info){
														$downclass .= ' bought';
													}

													$buyText = __('立即购买','mobantu');
													if($down_repeat && $down_info_repeat && !$down_info){
														$buyText = __('再次购买','mobantu');
													}

													if( ($userType && ($indexMemberDown==3 || $indexMemberDown==4)) || $down_info || (($indexMemberDown==15 || $indexMemberDown==16) && $userType >= 8) || (($indexMemberDown==6 || $indexMemberDown==8) && $userType >= 9) || (($indexMemberDown==7 || $indexMemberDown==9 || $indexMemberDown==13 || $indexMemberDown==14 || $indexMemberDown==20) && $userType >= 10) || (!$price && $indexMemberDown!=4 && $indexMemberDown!=15 && $indexMemberDown!=8 && $indexMemberDown!=9)){

														if($indexMemberDown==3){
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_vip_name);
														}elseif($indexMemberDown==2){
															$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name);
														}elseif($indexMemberDown==13){
															$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
														}elseif($indexMemberDown==5){
															$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name);
														}elseif($indexMemberDown==14){
															$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
														}elseif($indexMemberDown==20){
															$content .= '，'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
														}elseif($indexMemberDown==21){
															$content .= '，'.$erphp_vip_discounts;
														}elseif($indexMemberDown==16){
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name);
														}elseif($indexMemberDown==6){
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_year_name);
														}elseif($indexMemberDown==7){
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
														}elseif($indexMemberDown==4){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_vip_name);
														}elseif($indexMemberDown == 15){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_quarter_name);
														}elseif($indexMemberDown == 8){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_year_name);
														}elseif($indexMemberDown == 9){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_life_name);
														}elseif ($indexMemberDown==10){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name);
														}elseif ($indexMemberDown==17){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_quarter_name);
														}elseif ($indexMemberDown==18){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_year_name);
														}elseif ($indexMemberDown==19){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_life_name);
														}elseif ($indexMemberDown==11){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name);
														}elseif ($indexMemberDown==12){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name);
														}

														$content .= '</div><a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="erphpdown-down'.$downclass.' erphpdown-btn-large">'.__('立即下载','mobantu').'</a>';
													}else{

														$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_vip_name).'</a>';
														if($userType){
															$vipText = '';
															if(($indexMemberDown == 13 || $indexMemberDown == 14) && $userType < 10){
																$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
															}
														}

														if($indexMemberDown==3){
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).$vipText;
														}elseif ($indexMemberDown==2){
															$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).$vipText;
														}elseif ($indexMemberDown==13){
															$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
														}elseif ($indexMemberDown==14){
															$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
														}elseif($indexMemberDown==20){
															$content .= '，'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
														}elseif($indexMemberDown==21){
															$content .= '，'.$erphp_vip_discounts.$vipText;
														}elseif ($indexMemberDown==5){
															$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).$vipText;
														}elseif ($indexMemberDown==16){
															if($userType < 8){
																$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_quarter_name).'</a>';
															}
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).$vipText;
														}elseif ($indexMemberDown==6){
															if($userType < 9){
																$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
															}
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vipText;
														}elseif ($indexMemberDown==7){
															if($userType < 10){
																$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
															}
															$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
														}

														if($indexMemberDown==4){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_vip_name).'</div>';
															$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_vip_name)."</a>";
														}elseif($indexMemberDown==15){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_quarter_name).'</div>';
															$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_quarter_name)."</a>";
														}elseif($indexMemberDown==8){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_year_name).'</div>';
															$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_year_name)."</a>";
														}elseif($indexMemberDown==9){
															$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_life_name).'</div>';
															$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_life_name)."</a>";
														}elseif($indexMemberDown==10){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'</div>';
															if($userType) {
																if($down_checkpan){
																	$content.= $down_checkpan;
																}else{
																	$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'" target="_blank">'.$buyText.'</a>';
																}
															}else{
																$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_vip_name)."</a>";
															}
														}elseif($indexMemberDown==17){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_quarter_name).'</div>';
															if($userType >= 8) {
																if($down_checkpan){
																	$content.= $down_checkpan;
																}else{
																	$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'" target="_blank">'.$buyText.'</a>';
																}
															}else{
																$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_quarter_name)."</a>";
															}
														}elseif($indexMemberDown==18){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_year_name).'</div>';
															if($userType >= 9) {
																if($down_checkpan){
																	$content.= $down_checkpan;
																}else{
																	$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'" target="_blank">'.$buyText.'</a>';
																}
															}else{
																$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_year_name)."</a>";
															}
														}elseif($indexMemberDown==19){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_life_name).'</div>';
															if($userType >= 10) {
																if($down_checkpan){
																	$content.= $down_checkpan;
																}else{
																	$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'" target="_blank">'.$buyText.'</a>';
																}
															}else{
																$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_life_name)."</a>";
															}
														}elseif($indexMemberDown==11){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).'</div>';
															if($userType) {
																if($down_checkpan){
																	$content.= $down_checkpan;
																}else{
																	$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'" target="_blank">'.$buyText.'</a>';
																}
															}else{
																$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_vip_name)."</a>";
															}
														}elseif($indexMemberDown==12){
															$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).'</div>';
															if ($userType) {
																if($down_checkpan){
																	$content.= $down_checkpan;
																}else{
																	$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'" target="_blank">'.$buyText.'</a>';
																}
															}else{
																$content.="<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>".sprintf(__('升级%s','mobantu'),$erphp_vip_name)."</a>";
															}
														}else{
															$content .= '</div>';
															if($down_checkpan){
																$content.= $down_checkpan;
															}else{
																$content.='<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'" target="_blank">'.$buyText.'</a>';
															}
														}

														if($userType == 11){
															$content .= '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="erphpdown-down erphpdown-down-super'.$downclass.' erphpdown-btn-large">'.$erphp_super_name.__('免费下载','mobantu').'</a>';
														}
													}
												}else{
													$content .= '</div>';
													$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down".$downclass." erphpdown-btn-large' target='_blank'>".__("立即下载","mobantu")."</a>";
												}
												
											}else{
												$content .= '<div class="erphpdown-box-tips">';
												if($indexMemberDown == 4 || $indexMemberDown == 15 || $indexMemberDown == 8 || $indexMemberDown == 9){
													$content.='仅限'.$erphp_vip_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
												}else{
													if($price){
														$content.=__('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
													}else{
														$content.='免费资源，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
													}
												}
												$content .= '</div>';
												$content .= "<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
											}

											if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
												$content.='<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' class="erphpdown-down'.$downclass.' erphpdown-btn-large bought" target="_blank">'.__('立即下载','mobantu').'</a>';
											}

					    					$content .= '</div>';
					    				}
					    			}
								}
							}else{
								if(function_exists('erphpdown_tuan_install')){
									$content.= erphpdown_tuan_modown_html3();
								}

								if(function_exists('epd_check_pan_callback')){
									if((function_exists('epd_check_pan_is') && epd_check_pan_is($url)) || strpos($url,'pan.baidu.com') !== false || strpos($url,'123865.com') !== false || strpos($url,'123684.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
										$down_checkpan = $down_demo.'<a class="erphpdown-buy erphpdown-checkpan erphpdown-btn-large" href="javascript:;" data-id="'.get_the_ID().'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'">点击检测网盘有效后购买</a>';
									}
								}

								if(is_user_logged_in() || ( ($userType && ($memberDown==3 || $memberDown==4)) || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType >= 10) )){
									$content .= '<div class="erphpdown-box-tips">';
									if($price){
										if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
											$content.=__('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.$money_name;
									}else{
										if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
											$content.=__('免费资源','mobantu');
									}

									if($price || $memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9){
										global $wpdb;
										$user_info=wp_get_current_user();
										if($user_info->ID){
											$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
											if($days > 0 && $down_info){
												$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
												$nowDate = date('Y-m-d H:i:s');
												if(strtotime($nowDate) > strtotime($lastDownDate)){
													$down_info = null;
												}
											}

											if($down_repeat){
												$down_info_repeat = $down_info;
												$down_info = null;
											}
										}

										if($down_info){
											$downclass .= ' bought';
										}

										$buyText = __('立即购买','mobantu');
										if($down_repeat && $down_info_repeat && !$down_info){
											$buyText = __('再次购买','mobantu');
										}

										$user_id = $user_info->ID;
										$wppay = new EPD(get_the_ID(), $user_id);

										if( ($userType && ($memberDown==3 || $memberDown==4)) || (($wppay->isWppayPaid() || $wppay->isWppayPaidNew()) && !$down_repeat) || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType >= 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){

											if($memberDown==3){
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_vip_name);
											}elseif($memberDown==2){
												$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name);
											}elseif($memberDown==13){
												$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
											}elseif($memberDown==5){
												$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name);
											}elseif($memberDown==14){
												$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
											}elseif($memberDown==20){
												$content .= '，'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
											}elseif($memberDown==21){
												$content .= '，'.$erphp_vip_discounts;
											}elseif($memberDown==16){
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name);
											}elseif($memberDown==6){
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_year_name);
											}elseif($memberDown==7){
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_life_name);
											}elseif($memberDown==4){
												$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_vip_name);
											}elseif($memberDown == 15){
												$content .= sprintf(__('仅限%s下载','mobantu'),$erphp_quarter_name);
											}elseif($memberDown == 8){
												$content .= sprintf(__('仅限%s下载','mobantu'),$erphp_year_name);
											}elseif($memberDown == 9){
												$content .= sprintf(__('仅限%s下载','mobantu'),$erphp_life_name);
											}elseif ($memberDown==10){
												$content .= '，'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
											}elseif ($memberDown==17){
												$content .= '，'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
											}elseif ($memberDown==18){
												$content .= '，'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
											}elseif ($memberDown==19){
												$content .= '，'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
											}elseif ($memberDown==11){
												$content .= '，'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name);
											}elseif ($memberDown==12){
												$content .= '，'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name);
											}

											$down_activation=get_post_meta(get_the_ID(), 'down_activation', true);
											if($down_activation && !($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info) && !(!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9) && $erphp_addon_activation_vip){
												$content.='</div>'.$down_demo.'<a href="javascript:;" data-id="'.get_the_ID().'" class="erphpdown-down erphpdown-activation-vip erphpdown-btn-large">'.__('免费获取','mobantu').'</a>';
											}else{
												$content.='</div>'.$down_demo.'<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down'.$downclass.' erphpdown-btn-large" target="_blank">'.__('立即下载','mobantu').'</a>';
											}
										}else{
										
											$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_vip_name).'</a>';
											if($userType){
												$vipText = '';
												if(($memberDown == 13 || $memberDown == 14 || $memberDown == 20) && $userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
												}
											}

											if($memberDown==3){
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).$vipText;
											}elseif ($memberDown==2){
												$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).$vipText;
											}elseif ($memberDown==13){
												$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
											}elseif ($memberDown==14){
												$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
											}elseif ($memberDown==20){
												$content .= '，'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
											}elseif ($memberDown==21){
												$content .= '，'.$erphp_vip_discounts.$vipText;
											}elseif ($memberDown==5){
												$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).$vipText;
											}elseif ($memberDown==16){
												if($userType < 8){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">升级'.$erphp_quarter_name.'</a>';
												}
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).$vipText;
											}elseif ($memberDown==6){
												if($userType < 9){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">升级'.$erphp_year_name.'</a>';
												}
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vipText;
											}elseif ($memberDown==7){
												if($userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">升级'.$erphp_life_name.'</a>';
												}
												$content .= '，'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vipText;
											}
											
											if($memberDown==4){
												$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_vip_name).'</div>';
												$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_vip_name."</a>";
											}elseif($memberDown==15){
												$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_quarter_name).'</div>';
												$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_quarter_name."</a>";
											}elseif($memberDown==8){
												$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_year_name).'</div>';
												$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_year_name."</a>";
											}elseif($memberDown==9){
												$content .= sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_life_name).'</div>';
												$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_life_name."</a>";
											}elseif($memberDown==10){
												$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'</div>';
												if($userType){
													if($down_checkpan){
														$content .= $down_checkpan;
													}else{
														$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.$buyText.'</a>';
													}
												}else{
													$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_vip_name."</a>";
												}
											}elseif($memberDown==17){
												$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_quarter_name).'</div>';
												if($userType >= 8){
													if($down_checkpan){
														$content .= $down_checkpan;
													}else{
														$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.$buyText.'</a>';
													}
												}else{
													$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_quarter_name."</a>";
												}
											}elseif($memberDown==18){
												$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_year_name).'</div>';
												if($userType >= 9){
													if($down_checkpan){
														$content .= $down_checkpan;
													}else{
														$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.$buyText.'</a>';
													}
												}else{
													$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_year_name."</a>";
												}
											}elseif($memberDown==19){
												$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_life_name).'</div>';
												if($userType >= 10){
													if($down_checkpan){
														$content .= $down_checkpan;
													}else{
														$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.$buyText.'</a>';
													}
												}else{
													$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_life_name."</a>";
												}
											}elseif($memberDown==11){
												$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).'</div>';
												if($userType){
													if($down_checkpan){
														$content .= $down_checkpan;
													}else{
														$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.$buyText.'</a>';
													}
												}else{
													$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_vip_name."</a>";
												}
											}elseif($memberDown==12){
												$content .= '，'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).'</div>';
												if($userType){
													if($down_checkpan){
														$content .= $down_checkpan;
													}else{
														$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.$buyText.'</a>';
													}
												}else{
													$content.=$down_demo."<a href='".$erphp_url_front_vip."' class='erphpdown-down erphpdown-btn-large vip erphpdown-vip-loader' target='_blank'>升级".$erphp_vip_name."</a>";
												}
											}else{
												$content .= '</div>';
												if($down_checkpan){
													$content .= $down_checkpan;
												}else{
													$content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.$buyText.'</a>';
												}
											}

											if($userType == 11){
												$content .= '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="erphpdown-down erphpdown-down-super'.$downclass.' erphpdown-btn-large">'.$erphp_super_name.__('免费下载','mobantu').'</a>';
											}
										}
										
									}else{
										$content .= '</div>';
										$content.=$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$downclass." erphpdown-btn-large' target='_blank'>".__("立即下载","mobantu")."</a>";
									}
									
								}else {

									$content .= '<div class="erphpdown-box-tips">';
									if($memberDown == 4){
										$content.='仅限'.$erphp_vip_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 15){
										$content.='仅限'.$erphp_quarter_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 8){
										$content.='仅限'.$erphp_year_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 9){
										$content.='仅限'.$erphp_life_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 10){
										$content.='仅限'.$erphp_vip_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 17){
										$content.='仅限'.$erphp_quarter_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 18){
										$content.='仅限'.$erphp_year_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 19){
										$content.='仅限'.$erphp_life_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 11){
										$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}elseif($memberDown == 12){
										$content.='仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
										$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
									}else{
										$vip_content = '';
										if($memberDown==3){
											$vip_content.='，'.$erphp_vip_name.'免费';
										}elseif($memberDown==2){
											$vip_content.='，'.$erphp_vip_name.' 5折';
										}elseif($memberDown==13){
											$vip_content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
										}elseif($memberDown==5){
											$vip_content.='，'.$erphp_vip_name.' 8折';
										}elseif($memberDown==14){
											$vip_content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
										}elseif($memberDown==20){
											$vip_content.='，'.$erphp_vip_discounts.'、'.$erphp_life_name.'免费';
										}elseif($memberDown==21){
											$vip_content.='，'.$erphp_vip_discounts;
										}elseif($memberDown==16){
											$vip_content .= '，'.$erphp_quarter_name.'免费';
										}elseif($memberDown==6){
											$vip_content .= '，'.$erphp_year_name.'免费';
										}elseif($memberDown==7){
											$vip_content .= '，'.$erphp_life_name.'免费';
										}

										if(get_option('erphp_wppay_down')){
											$user_id = 0;
											$wppay = new EPD(get_the_ID(), $user_id);
											if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
												if($price){
													$content.=__('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.$money_name;
												}
												$content.='</div>'.$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$downclass." erphpdown-btn-large' target='_blank'>".__("立即下载","mobantu")."</a>";
											}else{
												if($price){
													$content.=__('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
													$content .= ($vip_content?($vip_content.'<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">升级'.$erphp_vip_name.'</a>'):'').'</div>';

													if($down_checkpan) $content .= $down_checkpan;
													else $content.=$down_demo.'<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' target="_blank">'.__('立即购买','mobantu').'</a>';

												}else{
													if(!get_option('erphp_free_login')){
														$content.=__('免费资源','mobantu').'</div>'.$down_demo."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$downclass." erphpdown-btn-large' target='_blank'>".__("立即下载","mobantu")."</a>";
													}else{
														$content.='仅限注册用户下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a></div>';
														$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
													}
												}
											}
										}else{
											if($price){
												$content.=__('下载价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').$vip_content.'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
											}else{
												$content.='仅限注册用户下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
											}
											$content .= '</div>';
											$content .= $down_demo."<a href='javascript:;' class='erphpdown-down erphpdown-btn-large signin-loader'>".__("立即下载","mobantu")."</a>";
										}
									}

								}
							}

							if($down_activation && function_exists('getErphpActLeft')) $content.='&nbsp;&nbsp;库存：'.getErphpActLeft(get_the_ID());

							if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
								$content.='<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' class="erphpdown-down'.$downclass.' erphpdown-btn-large bought" target="_blank">'.__('立即下载','mobantu').'</a>';
							}

							$content .= '</div>';

							if($days){
								$content .= '<div class="erphpdown-tips">此资源购买后'.$days.'天内可下载。';
								if(get_option('ice_tips')){
								 	$content .= get_option('ice_tips');
								}
								$content .= '</div>';
							}else{
								if(get_option('ice_tips')){
								 	$content .= '<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
								}
							}

						}
					}
					$content.='</div>';
					
				}elseif($erphp_down == 6){
					$erphp_box_faka_title = get_option("erphp_box_faka_title");
					$content.='<div class="erphpdown" id="erphpdown" style="display:block"><span class="erphpdown-title">'.($erphp_box_faka_title?$erphp_box_faka_title:__('自动发卡','mobantu')).'</span>';
					$content.='<div class="erphpdown-fee"><div class="erphpdown-box-tips">'.__('卡密价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.$money_name;
					if ($memberDown==2){
						$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name);
					}elseif ($memberDown==5){
						$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name);
					}elseif ($memberDown==21){
						$content .= '，'.$erphp_vip_discounts;
					}
					if(function_exists('getErphpActLeft')) $content.='，库存：'.getErphpActLeft(get_the_ID());
					$content.='</div>'.$down_demo;
					if(get_option('erphp_faka_login') && !is_user_logged_in()){
						$content.='<a class="signin-loader erphpdown-buy erphpdown-btn-large" href="javascript:;">'.__('立即购买','mobantu').'</a></div>';
					}else{
						$content.= '<a class="erphpdown-iframe erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">'.__('立即购买','mobantu').'</a></div>';
					}
					if(get_option('ice_tips_faka')){
					 	$content .= '<div class="erphpdown-tips">'.get_option('ice_tips_faka').'</div>';
					}
					$content.='</div>';
				}elseif($erphp_down == 7){
					$content.='<div class="erphpdown" id="erphpdown" style="display:block"><span class="erphpdown-title">'.__('实物商品','mobantu').'</span>';
					$content.='<div class="erphpdown-fee"><div class="erphpdown-box-tips">'.__('商品价格','mobantu').'<span class="erphpdown-price">'.$price.'</span>'.$money_name;
					if ($memberDown==2){
						$content .= '，'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name);
					}elseif ($memberDown==5){
						$content .= '，'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name);
					}elseif ($memberDown==21){
						$content .= '，'.$erphp_vip_discounts;
					}
					$content.='</div>'.$down_demo;
					if(is_user_logged_in()){
						$content.='<a class="erphpdown-iframe erphpdown-iframe-large erphpdown-buy erphpdown-btn-large" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" target="_blank">'.__('立即购买','mobantu').'</a>';
					}else{
						$content.='<a class="signin-loader erphpdown-buy erphpdown-btn-large" href="javascript:;">'.__('立即购买','mobantu').'</a>';
					}
					
					$content.='</div></div>';
				}else{
					if($downMsgFree) $content.='<div class="erphpdown" id="erphpdown" style="display:block"><span class="erphpdown-title">'.__('资源下载','mobantu').'</span>'.$downMsgFree.'</div>';
				}
				echo $content;
			}else{
				echo '<div class="modown-reply">'.__('此隐藏内容 <a href="javascript:scrollToTop(\'#respond\',-120);">评论</a> 本文后<span>刷新页面</span>可见！','mobantu').'</div>';
			}
		}
		?>
	</div>
</div>
<?php }?>