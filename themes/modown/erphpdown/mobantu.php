<?php
//by mobantu.com
if(!function_exists('MBThemes_erphpdown_box')){
	function MBThemes_erphpdown_box($boxshow = true){
		if(!$boxshow){
			echo '<style>.erphpdown-box, .erphpdown-box + .article-custom-metas{display:none;}</style>';
		}
		global $post, $wpdb;
		
		if(is_user_logged_in() || !_MBT('hide_user_all')){
			if(MBThemes_check_reply()){
				$erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
				$start_down=get_post_meta(get_the_ID(), 'start_down', true);
				$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
				$start_see=get_post_meta(get_the_ID(), 'start_see', true);
				$start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
				$days=get_post_meta(get_the_ID(), 'down_days', true);
				$price=get_post_meta(get_the_ID(), 'down_price', true);
				$price_type=get_post_meta(get_the_ID(), 'down_price_type', true);
				$url=get_post_meta(get_the_ID(), 'down_url', true);
				$urls=get_post_meta(get_the_ID(), 'down_urls', true);
				$url_free=get_post_meta(get_the_ID(), 'down_url_free', true);
				$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
				$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
				$down_activation=get_post_meta(get_the_ID(), 'down_activation', true);
				$member_zdiscount = get_post_meta(get_the_ID(), 'member_zdiscount', true);
				$nosidebar = get_post_meta(get_the_ID(),'nosidebar',true);
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
				
				$vip = '';$vip2 = '';$vip3 = '';$vip4 = '';$downMsg = '';$downclass = '';$hasfree = 0;$iframe = '';$downMsgFree = '';$yituan = '';$down_tuan=0;$down_repeat=0;$down_checkpan='';$down_info_repeat=null;$down_info = null;
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

				$erphp_see2_style = get_option('erphp_see2_style');
				$erphp_super_name    = get_option('erphp_super_name')?get_option('erphp_super_name'):'全站通VIP';
				$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
				$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
				$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
				$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
				$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
				$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

				$money_name = get_option("ice_name_alipay");
				$erphp_box_down_title = get_option('erphp_box_down_title')?get_option('erphp_box_down_title'):__('资源下载','mobantu');
				$erphp_box_see_title = get_option('erphp_box_see_title')?get_option('erphp_box_see_title'):__('内容查看','mobantu');
				$erphp_box_faka_title = get_option('erphp_box_faka_title')?get_option('erphp_box_faka_title'):__('自动发卡','mobantu');

				$erphp_life_discount    = get_option('erphp_life_discount');
				$erphp_year_discount    = get_option('erphp_year_discount');
				$erphp_quarter_discount = get_option('erphp_quarter_discount');
				$erphp_month_discount  = get_option('erphp_month_discount');
				$erphp_day_discount  = get_option('erphp_day_discount');

				$erphp_vip_discounts_tips = '<div class="vip-discounts-tips">';
				if($erphp_day_discount && $memberDown != 17 && $memberDown != 18 && $memberDown != 19){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_day_name,$erphp_day_discount).'<br>';
				}
				if($erphp_month_discount && $memberDown != 17 && $memberDown != 18 && $memberDown != 19){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_month_name,$erphp_month_discount).'<br>';
				}
				if($erphp_quarter_discount && $memberDown != 18 && $memberDown != 19){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_quarter_name,$erphp_quarter_discount).'<br>';
				}
				if($erphp_year_discount && $memberDown != 19){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_year_name,$erphp_year_discount).'<br>';
				}
				if($erphp_life_discount && $memberDown != 20){
					$erphp_vip_discounts_tips .= sprintf(__('%s %s折','mobantu'),$erphp_life_name,$erphp_life_discount).'<br>';
				}

				$erphp_vip_discounts_tips = rtrim($erphp_vip_discounts_tips,'<br>').'</div>';

				$erphp_vip_discounts = '<div class="vip-discounts">'.sprintf(__('%s折扣','mobantu'),$erphp_vip_name).$erphp_vip_discounts_tips.'</div>';

				$erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));
				if(get_option('erphp_url_front_vip')){
					$erphp_url_front_vip = get_option('erphp_url_front_vip');
				}

				$erphp_blank_domains = get_option('erphp_blank_domains')?get_option('erphp_blank_domains'):'pan.baidu.com';
				$erphp_colon_domains = get_option('erphp_colon_domains')?get_option('erphp_colon_domains'):'pan.baidu.com';

				if($down_tuan && is_user_logged_in()){
					global $current_user;
					$yituan = $wpdb->get_var("select ice_status from $wpdb->tuanorder where ice_user_id=".$current_user->ID." and ice_post=".get_the_ID()." and ice_status>0");
				}

				if($nosidebar || MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop' || MBThemes_post_down_position() == 'bottom' || MBThemes_post_down_position() == 'sidebottom' || MBThemes_post_down_position() == 'boxbottom') echo '<style>.erphpdown-box, .erphpdown-box + .article-custom-metas{display:block;}</style>';

				if($url_free){
					$hasfree = 1;
					echo '<div class="erphpdown-box erphpdown-box2 erphpdown-free-box clearfix">';
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
					echo $downMsgFree;

					if(get_option('ice_tips_free')) echo '<div class="tips2">'.get_option('ice_tips_free').'</div>';

					echo '</div>';

					if($erphp_down == '4' || !$erphp_down){
						if(function_exists('get_field_objects') && !_MBT('post_meta_top')){
				            $fields = get_field_objects();
				            if( $fields ){
				            	uasort($fields,'mbt_compare_field');
				            	echo '<div class="article-custom-metas clearfix">';
				                foreach( $fields as $field_name => $field ){
				                	if($field['value']){
				                        echo '<div class="meta">';
			                            echo '<t>' . $field['label'] . '：</t>';
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
				            }
				        }
					}
				}

				if($start_down){
					echo '<div class="erphpdown-box"><span class="erphpdown-title">'.$erphp_box_down_title.'</span>';

					if(_MBT('post_price_logged') && !is_user_logged_in() && ( !(!$price && $memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9) || (!$price && $memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9 && get_option('erphp_free_login')) ) ){
						echo '<div class="erphpdown-con clearfix"><div class="erphpdown-price">'.$erphp_box_down_title.'</div><div class="erphpdown-cart"><a href="javascript:;" class="down signin-loader">'.__('立即下载','mobantu').'</a></div></div>';
					}else{

						if($down_tuan == '2' && function_exists('erphpdown_tuan_install')){
							$tuanHtml = erphpdown_tuan_modown_html2();
							echo '<div class="erphpdown-con clearfix">'.$tuanHtml.'</div>';
						}else{
							echo '<div class="erphpdown-con clearfix">';

							if($price_type){
								if($urls){
									$cnt = count($urls['index']);
					    			if($cnt){
					    				echo '<div class="erphpdown-children-nav">';
					    				for($i=0; $i<$cnt;$i++){
					    					echo '<a href="javascript:;" data-index="'.$i.'" class="'.($i==0?'active':'').'">'.$urls['name'][$i].'</a>';
					    				}
					    				echo '</div>';
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
													$down_checkpan = '<a class="down erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.'">'.__('点击检测网盘有效后购买','mobantu').'</a>';
												}
											}

					    					echo '<div class="erphpdown-child erphpdown-child'.$i.' clearfix" style="'.($i==0?'display:block':'').'"><span class="erphpdown-child-title">'.$index_name.'</span>';
					    					if($price){
												if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9){
													echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.$price.'</span> '.$money_name.'</div>';
												}else{
													if($indexMemberDown == 4){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_vip_name).'</div>';
													}elseif($indexMemberDown == 15){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_quarter_name).'</div>';
													}elseif($indexMemberDown == 8){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_year_name).'</div>';
													}elseif($indexMemberDown == 9){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_life_name).'</div>';
													}
												}
											}else{
												if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9){
													echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.__('免费','mobantu').'</span></div>';
												}else{
													if($indexMemberDown == 4){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.$erphp_vip_name.'</span>专享</div>';
													}elseif($indexMemberDown == 15){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.$erphp_quarter_name.'</span>专享</div>';
													}elseif($indexMemberDown == 8){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.$erphp_year_name.'</span>专享</div>';
													}elseif($indexMemberDown == 9){
														echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.$erphp_life_name.'</span>专享</div>';
													}
												}
											}

											echo '<div class="erphpdown-cart">';
											if($price || $indexMemberDown == 4 || $indexMemberDown == 15 || $indexMemberDown == 8 || $indexMemberDown == 9){
												if(is_user_logged_in() || ( ($userType && ($indexMemberDown==3 || $indexMemberDown==4)) || (($indexMemberDown==15 || $indexMemberDown==16) && $userType >= 8) || (($indexMemberDown==6 || $indexMemberDown==8 || $indexMemberDown==23 || $indexMemberDown==24) && $userType >= 9) || (($indexMemberDown==7 || $indexMemberDown==9 || $indexMemberDown==13 || $indexMemberDown==14 || $indexMemberDown==20) && $userType >= 10) )){
													$user_info=wp_get_current_user();
													if($user_info->ID){
														$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_index='".$index."' and ice_user_id=".$user_info->ID." order by ice_time desc");
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

													$buyText = __('立即购买','mobantu');
													if($down_repeat && $down_info_repeat && !$down_info){
														$buyText = __('再次购买','mobantu');
													}

													if(!$down_info){
														if(!$userType){
															$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_vip_name).'</a>';
														}else{
															if(($indexMemberDown == 23 || $indexMemberDown == 24) && $userType < 9){
																$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
															}
															elseif(($indexMemberDown == 13 || $indexMemberDown == 14 || $indexMemberDown == 20) && $userType < 10){
																$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
															}
														}
														if($userType < 8){
															$vip4 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_quarter_name).'</a>';
														}
														if($userType < 9){
															$vip2 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
														}
														if($userType < 10){
															$vip3 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
														}
													}else{
														$downclass .= ' bought';
													}

													if( ($userType && ($indexMemberDown==3 || $indexMemberDown==4)) || $down_info || (($indexMemberDown==15 || $indexMemberDown==16) && $userType >= 8) || (($indexMemberDown==6 || $indexMemberDown==8 || $indexMemberDown==23 || $indexMemberDown==24) && $userType >= 9) || (($indexMemberDown==7 || $indexMemberDown==9 || $indexMemberDown==13 || $indexMemberDown==14 || $indexMemberDown==20) && $userType >= 10) || (!$price && $indexMemberDown!=4 && $indexMemberDown!=15 && $indexMemberDown!=8 && $indexMemberDown!=9)){

														if($indexMemberDown==3){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).'</div>';
														}elseif($indexMemberDown==2){
															echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'</div>';
														}elseif($indexMemberDown==13){
															echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
														}elseif($indexMemberDown==23){
															echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
														}elseif($indexMemberDown==5){
															echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'</div>';
														}elseif($indexMemberDown==14){
															echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
														}elseif($indexMemberDown==24){
															echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
														}elseif($indexMemberDown==20){
															echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
														}elseif($indexMemberDown==21){
															echo '<div class="vip">'.$erphp_vip_discounts.'</div>';
														}elseif($indexMemberDown==16){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).'</div>';
														}elseif($indexMemberDown==6){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
														}elseif($indexMemberDown==7){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
														}elseif($indexMemberDown==4){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_vip_name).'</div>';
														}elseif($indexMemberDown==15){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_quarter_name).'</div>';
														}elseif($indexMemberDown == 8){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_year_name).'</div>';
														}elseif($indexMemberDown == 9){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s下载', 'mobantu' ), $erphp_life_name).'</div>';
														}elseif ($indexMemberDown==10){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo '</div>';
														}elseif ($indexMemberDown==17){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_quarter_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo '</div>';
														}elseif ($indexMemberDown==18){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_year_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo '</div>';
														}elseif ($indexMemberDown==19){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_life_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo '</div>';
														}elseif ($indexMemberDown==11){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).'</div>';
														}elseif ($indexMemberDown==12){
															echo '<div class="vip vip-only">'.sprintf(__( '仅限%s购买', 'mobantu' ), $erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).'</div>';
														}

														echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down'.$downclass.'">'.__('立即下载','mobantu').'</a>';
													}else{
														if($indexMemberDown==3){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).$vip.'</div>';
														}elseif($indexMemberDown==2){
															echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).$vip.'</div>';
														}elseif($indexMemberDown==13){
															echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
														}elseif($indexMemberDown==23){
															echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip.'</div>';
														}elseif($indexMemberDown==5){
															echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).$vip.'</div>';
														}elseif($indexMemberDown==14){
															echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
														}elseif($indexMemberDown==24){
															echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip.'</div>';
														}elseif($indexMemberDown==20){
															echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
														}elseif($indexMemberDown==21){
															echo '<div class="vip">'.$erphp_vip_discounts.$vip.'</div>';
														}elseif($indexMemberDown==16){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).$vip4.'</div>';
														}elseif($indexMemberDown==6){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip2.'</div>';
														}elseif($indexMemberDown==7){
															echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip3.'</div>';
														}

														if($indexMemberDown==4){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_vip_name).$vip.'</div>';
														}elseif($indexMemberDown==15){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_quarter_name).$vip4.'</div>';
														}elseif($indexMemberDown==8){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_year_name).$vip2.'</div>';
														}elseif($indexMemberDown==9){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_life_name).$vip3.'</div>';
														}elseif($indexMemberDown==10){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo $vip.'</div>';
															if($userType){
																if($down_checkpan) echo $down_checkpan;
																else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.' class="down erphpdown-iframe">'.$buyText.'</a>';
															}
														}elseif($indexMemberDown==17){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo $vip4.'</div>';
															if($userType >= 8){
																if($down_checkpan) echo $down_checkpan;
																else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.' class="down erphpdown-iframe">'.$buyText.'</a>';
															}
														}elseif($indexMemberDown==18){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo $vip2.'</div>';
															if($userType >= 9){
																if($down_checkpan) echo $down_checkpan;
																else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.' class="down erphpdown-iframe">'.$buyText.'</a>';
															}
														}elseif($indexMemberDown==19){
															echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
															if($member_zdiscount){
																echo '、'.$erphp_vip_discounts;
															}
															echo $vip3.'</div>';
															if($userType >= 10){
																if($down_checkpan) echo $down_checkpan;
																else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.' class="down erphpdown-iframe">'.$buyText.'</a>';
															}
														}elseif($indexMemberDown==11){
															if($userType){
																echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买、%s 5折','mobantu'),$erphp_vip_name,$erphp_year_name).$vip.'</div>';
																if($down_checkpan) echo $down_checkpan;
																else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.' class="down erphpdown-iframe">'.$buyText.'</a>';
															}else{
																echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买、%s 5折','mobantu'),$erphp_vip_name,$erphp_year_name).$vip.'</div>';
															}
														}elseif($indexMemberDown==12){
															if($userType){
																echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买、%s 8折','mobantu'),$erphp_vip_name,$erphp_year_name).$vip.'</div>';
																if($down_checkpan) echo $down_checkpan;
																else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.' class="down erphpdown-iframe">'.$buyText.'</a>';
															}else{
																echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买、%s 8折','mobantu'),$erphp_vip_name,$erphp_year_name).$vip.'</div>';
															}
														}else{
															if($down_checkpan) echo $down_checkpan;
															else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'&index='.$index.' class="down erphpdown-iframe">'.$buyText.'</a>';
														}

														if($userType == 11){
															echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down down-super'.$downclass.'">'.$erphp_super_name.__('免费下载','mobantu').'</a>';
														}
													}	

												}else{
													if($indexMemberDown==3){
														echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).'</div>';
													}elseif($indexMemberDown==2){
														echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'</div>';
													}elseif($indexMemberDown==13){
														echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
													}elseif($indexMemberDown==23){
														echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
													}elseif($indexMemberDown==5){
														echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'</div>';
													}elseif($indexMemberDown==14){
														echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
													}elseif($indexMemberDown==24){
														echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
													}elseif($indexMemberDown==20){
														echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
													}elseif($indexMemberDown==21){
														echo '<div class="vip">'.$erphp_vip_discounts.'</div>';
													}elseif($indexMemberDown==16){
														echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).'</div>';
													}elseif($indexMemberDown==6){
														echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
													}elseif($indexMemberDown==7){
														echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
													}elseif($indexMemberDown==4){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_vip_name).'</div>';
													}elseif($indexMemberDown == 15){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_quarter_name).'</div>';
													}elseif($indexMemberDown == 8){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_year_name).'</div>';
													}elseif($indexMemberDown == 9){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_life_name).'</div>';
													}elseif ($indexMemberDown==10){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
														if($member_zdiscount){
															echo '、'.$erphp_vip_discounts;
														}
														echo '</div>';
													}elseif ($indexMemberDown==17){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
														if($member_zdiscount){
															echo '、'.$erphp_vip_discounts;
														}
														echo '</div>';
													}elseif ($indexMemberDown==18){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
														if($member_zdiscount){
															echo '、'.$erphp_vip_discounts;
														}
														echo '</div>';
													}elseif ($indexMemberDown==19){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
														if($member_zdiscount){
															echo '、'.$erphp_vip_discounts;
														}
														echo '</div>';
													}elseif ($indexMemberDown==11){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买、%s 5折','mobantu'),$erphp_vip_name,$erphp_year_name).'</div>';
													}elseif ($indexMemberDown==12){
														echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买、%s 8折','mobantu'),$erphp_vip_name,$erphp_year_name).'</div>';
													}
													echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
												}
											}else{
												if(is_user_logged_in()){
													if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9){
														echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down'.$downclass.'">'.__('立即下载','mobantu').'</a>';
													}
												}else{
													echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
												}
											}

											if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().'&index='.$index.$iframe.' target="_blank" class="down down2 bought'.$downclass.'">'.__('立即下载','mobantu').'</a>';
											}

											echo '</div>';
					    					echo '</div>';
					    				}
					    			}
					    		}
							}else{
								if(function_exists('epd_check_pan_callback')){
									if((function_exists('epd_check_pan_is') && epd_check_pan_is($url)) || strpos($url,'pan.baidu.com') !== false || strpos($url,'123865.com') !== false || strpos($url,'123684.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
										$down_checkpan = '<a class="down erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'">'.__('点击检测网盘有效后购买','mobantu').'</a>';
									}
								}

								if($price){
									if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
										echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.$price.'</span> '.$money_name.'</div>';
									}else{
										if($memberDown == 4){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_vip_name).'</div>';
										}elseif($memberDown == 15){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_quarter_name).'</div>';
										}elseif($memberDown == 8){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_year_name).'</div>';
										}elseif($memberDown == 9){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_life_name).'</div>';
										}
									}
								}else{
									if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
										echo '<div class="erphpdown-price">'.__('下载价格','mobantu').'<span>'.__('免费','mobantu').'</span></div>';
									}else{
										if($memberDown == 4){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_vip_name).'</div>';
										}elseif($memberDown == 15){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_quarter_name).'</div>';
										}elseif($memberDown == 8){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_year_name).'</div>';
										}elseif($memberDown == 9){
											echo '<div class="erphpdown-price">'.__('下载价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_life_name).'</div>';
										}
									}
								}

								echo '<div class="erphpdown-cart">';
								if($down_activation && function_exists('getErphpActLeft')) echo '<div class="vip">（'.__('库存：','mobantu').getErphpActLeft(get_the_ID()).'）</div>';
								if($price || $memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9){
									if(is_user_logged_in() || ( ($userType && ($memberDown==3 || $memberDown==4)) || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType >= 10) )){
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

										$buyText = __('立即购买','mobantu');
										if($down_repeat && $down_info_repeat && !$down_info){
											$buyText = __('再次购买','mobantu');
										}

										if($down_info){
											$downclass .= ' bought';
										}else{
											if(!$userType){
												$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_vip_name).'</a>';
											}else{
												if(($memberDown == 23 || $memberDown == 24) && $userType < 9){
													$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
												}
												elseif(($memberDown == 13 || $memberDown == 14 || $memberDown == 20) && $userType < 10){
													$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
												}
											}
											if($userType < 8){
												$vip4 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_quarter_name).'</a>';
											}
											if($userType < 9){
												$vip2 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
											}
											if($userType < 10){
												$vip3 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
											}
										}

										$user_id = $user_info->ID;
										$wppay = new EPD(get_the_ID(), $user_id);

										if( ($userType && ($memberDown==3 || $memberDown==4)) || (($wppay->isWppayPaid() || $wppay->isWppayPaidNew()) && !$down_repeat) || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType >= 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){

											if($memberDown==3){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).'</div>';
											}elseif($memberDown==2){
												echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'</div>';
											}elseif($memberDown==13){
												echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
											}elseif($memberDown==23){
												echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
											}elseif($memberDown==5){
												echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'</div>';
											}elseif($memberDown==14){
												echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
											}elseif($memberDown==24){
												echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
											}elseif($memberDown==20){
												echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
											}elseif($memberDown==21){
												echo '<div class="vip">'.$erphp_vip_discounts.'</div>';
											}elseif($memberDown==16){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).'</div>';
											}elseif($memberDown==6){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
											}elseif($memberDown==7){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
											}elseif($memberDown==4){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_vip_name).'</div>';
											}elseif($memberDown == 15){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_quarter_name).'</div>';
											}elseif($memberDown == 8){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_year_name).'</div>';
											}elseif($memberDown == 9){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_life_name).'</div>';
											}elseif ($memberDown==10){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo '</div>';
											}elseif ($memberDown==17){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo '</div>';
											}elseif ($memberDown==18){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo '</div>';
											}elseif ($memberDown==19){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo '</div>';
											}elseif ($memberDown==11){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).'</div>';
											}elseif ($memberDown==12){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).'</div>';
											}

											$down_activation=get_post_meta(get_the_ID(), 'down_activation', true);
											if($down_activation && !($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info) && !(!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9) && $erphp_addon_activation_vip){
												echo '<a href="javascript:;" data-id="'.get_the_ID().'" class="down erphpdown-activation-vip">'.__('免费获取','mobantu').'</a>';
											}else{
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">'.__('立即下载','mobantu').'</a>';
											}
										}else{
											if($memberDown==3){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).$vip.'</div>';
											}elseif($memberDown==2){
												echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).$vip.'</div>';
											}elseif($memberDown==13){
												echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
											}elseif($memberDown==23){
												echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip.'</div>';
											}elseif($memberDown==5){
												echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).$vip.'</div>';
											}elseif($memberDown==14){
												echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
											}elseif($memberDown==24){
												echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip.'</div>';
											}elseif($memberDown==20){
												echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
											}elseif($memberDown==21){
												echo '<div class="vip">'.$erphp_vip_discounts.$vip.'</div>';
											}elseif($memberDown==16){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).$vip4.'</div>';
											}elseif($memberDown==6){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip2.'</div>';
											}elseif($memberDown==7){
												echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip3.'</div>';
											}

											if($memberDown==4){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_vip_name).$vip.'</div>';
											}elseif($memberDown==15){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_quarter_name).$vip4.'</div>';
											}elseif($memberDown==8){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_year_name).$vip2.'</div>';
											}elseif($memberDown==9){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_life_name).$vip3.'</div>';
											}elseif($memberDown==10){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo $vip.'</div>';
												if($userType){
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
												}
											}elseif($memberDown==17){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo $vip4.'</div>';
												if($userType >= 8){
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
												}
											}elseif($memberDown==18){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo $vip2.'</div>';
												if($userType >= 9){
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
												}
											}elseif($memberDown==19){
												echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
												if($member_zdiscount){
													echo '、'.$erphp_vip_discounts;
												}
												echo $vip3.'</div>';
												if($userType >= 10){
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
												}
											}elseif($memberDown==11){
												if($userType){
													echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).$vip.'</div>';
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
												}else{
													echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).$vip.'</div>';
												}
											}elseif($memberDown==12){
												if($userType){
													echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).$vip.'</div>';
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
												}else{
													echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).$vip.'</div>';
												}
											}else{
												if($down_checkpan) echo $down_checkpan;
												else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
											}

											if($userType == 11){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down down-super'.$downclass.'">'.$erphp_super_name.__('免费下载','mobantu').'</a>';
											}
										}	
									}else{
										$isWppayPaid = 0;
										if(get_option('erphp_wppay_down')){
											$user_id = 0;
											$wppay = new EPD(get_the_ID(), $user_id);
											if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
												$isWppayPaid = 1;
											}else{
												$isWppayPaid = 2;
											}
										}
										$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_vip_name).'</a>';
										$vip4 = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_quarter_name).'</a>';
										$vip2 = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
										$vip3 = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';

										if($memberDown==3){
											echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==2){
											echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==13){
											echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==23){
											echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==5){
											echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==14){
											echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==24){
											echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==20){
											echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==21){
											echo '<div class="vip">'.$erphp_vip_discounts.($isWppayPaid == 2?$vip:'').'</div>';
										}elseif($memberDown==16){
											echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).($isWppayPaid == 2?$vip4:'').'</div>';
										}elseif($memberDown==6){
											echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).($isWppayPaid == 2?$vip2:'').'</div>';
										}elseif($memberDown==7){
											echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip3:'').'</div>';
										}elseif($memberDown==4){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_vip_name).$vip.'</div>';
										}elseif($memberDown == 15){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_quarter_name).$vip4.'</div>';
										}elseif($memberDown == 8){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_year_name).$vip2.'</div>';
										}elseif($memberDown == 9){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s下载','mobantu'),$erphp_life_name).$vip3.'</div>';
										}elseif ($memberDown==10){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
											if($member_zdiscount){
												echo '、'.$erphp_vip_discounts;
											}
											echo $vip.'</div>';
										}elseif ($memberDown==17){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
											if($member_zdiscount){
												echo '、'.$erphp_vip_discounts;
											}
											echo $vip4.'</div>';
										}elseif ($memberDown==18){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
											if($member_zdiscount){
												echo '、'.$erphp_vip_discounts;
											}
											echo $vip2.'</div>';
										}elseif ($memberDown==19){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
											if($member_zdiscount){
												echo '、'.$erphp_vip_discounts;
											}
											echo $vip3.'</div>';
										}elseif ($memberDown==11){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).$vip.'</div>';
										}elseif ($memberDown==12){
											echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).$vip.'</div>';
										}

										if(get_option('erphp_wppay_down')){
											if($isWppayPaid == 1){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">'.__('立即下载','mobantu').'</a>';
											}else{
												if($memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9 || $memberDown == 10 || $memberDown == 11 || $memberDown == 12){
													//echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
												}else{
													if($down_checkpan) echo $down_checkpan;
													else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.__('立即购买','mobantu').'</a>';
												}
											}
										}else{
											echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
										}
									}
								}else{
									if(is_user_logged_in()){
										if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
											echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">'.__('立即下载','mobantu').'</a>';
										}
									}else{
										if(get_option('erphp_wppay_down') && !get_option('erphp_free_login')){
											if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
												echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down'.$downclass.'">'.__('立即下载','mobantu').'</a>';
											}
										}else{
											echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
										}
									}
								}

								if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
									echo '<a href='.constant("erphpdown").'download.php?postid='.get_the_ID().$iframe.' target="_blank" class="down down2 bought'.$downclass.'">'.__('立即下载','mobantu').'</a>';
								}

								echo '</div>';
							}	
							echo '</div>';

							if($days){
								echo '<div class="tips2">'.sprintf(__('此资源购买后%s天内可下载。','mobantu'), $days);
								if(get_option('ice_tips')){
								 	echo get_option('ice_tips');
								}
								echo '</div>';
							}else{
								if(get_option('ice_tips')){
								 	echo '<div class="tips2">'.get_option('ice_tips').'</div>';
								}
							}

							if(function_exists('erphpdown_tuan_install')){
								echo erphpdown_tuan_modown_html2();
							}
						}
					}
					echo '</div>';
					if(function_exists('get_field_objects') && !_MBT('post_meta_top')){
			            $fields = get_field_objects();
			            if( $fields ){
			            	uasort($fields,'mbt_compare_field');
			            	echo '<div class="article-custom-metas clearfix">';
			                foreach( $fields as $field_name => $field ){
			                	if($field['value']){
			                        echo '<div class="meta">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
			            }
			        }
				}elseif($start_down2){
					if($url){
						if(function_exists('epd_check_pan_callback')){
							if((function_exists('epd_check_pan_is') && epd_check_pan_is($url)) || strpos($url,'pan.baidu.com') !== false || strpos($url,'123865.com') !== false || strpos($url,'123684.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
								$down_checkpan = '<a class="down erphpdown-checkpan2" href="javascript:;" data-id="'.get_the_ID().'" data-post="'.get_the_ID().'">'.__('点击检测网盘有效后购买','mobantu').'</a>';
							}
						}

						echo '<div class="erphpdown-box erphpdown-box2"><span class="erphpdown-title">'.__('资源下载','mobantu').'</span>';
						echo '<div class="erphpdown-con clearfix">';
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						if($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || !$price || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10)){
							if($url){
								$downList=explode("\r\n",$url);
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
								echo $downMsg;
								if($hidden){
									echo '<div class="item2">'.__('提取码：','mobantu').$hidden.' <a class="erphpdown-copy" data-clipboard-text="'.$hidden.'" href="javascript:;">'.__('复制','mobantu').'</a></div>';
								}
							}else{
								echo '<style>#erphpdown{display:none !important;}</style>';
							}
						}else{
							if($url){
								$tname = __('下载','mobantu');
							}else{
								$tname = __('查看','mobantu');
							}
							if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
								$wppay_vip_name = $erphp_vip_name;
								if($memberDown == 16){
									$wppay_vip_name = $erphp_quarter_name;
								}elseif($memberDown == 6){
									$wppay_vip_name = $erphp_year_name;
								}elseif($memberDown == 7){
									$wppay_vip_name = $erphp_life_name;
								}
								if($down_checkpan){
									echo '<div class="erphpdown-price">'.sprintf(__('%s价格','mobantu'),$tname).'<span>'.$price.'</span> '.__('元','mobantu').'</div><div class="erphpdown-cart"><div class="vip">'.sprintf(__('%s免费','mobantu'),$wppay_vip_name).'<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$wppay_vip_name).'</a></div>'.$down_checkpan.'</div>';
								}else{
									echo '<div class="erphpdown-price">'.sprintf(__('%s价格','mobantu'),$tname).'<span>'.$price.'</span> '.__('元','mobantu').'</div><div class="erphpdown-cart"><div class="vip">'.sprintf(__('%s免费','mobantu'),$wppay_vip_name).'<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$wppay_vip_name).'</a></div><a href="javascript:;" class="down erphp-wppay-loader" data-post="'.get_the_ID().'">'.__('立即购买','mobantu').'</a></div>';
								}
							}else{
								if($down_checkpan){
									echo '<div class="erphpdown-price">'.sprintf(__('%s价格','mobantu'),$tname).'<span>'.$price.'</span> '.__('元','mobantu').'</div><div class="erphpdown-cart">'.$down_checkpan.'</div>';
								}else{
									echo '<div class="erphpdown-price">'.sprintf(__('%s价格','mobantu'),$tname).'<span>'.$price.'</span> '.__('元','mobantu').'</div><div class="erphpdown-cart"><a href="javascript:;" class="down erphp-wppay-loader" data-post="'.get_the_ID().'">'.__('立即购买','mobantu').'</a></div>';
								}
							}
						}
						echo '</div>';
						
						if(get_option('ice_tips')) echo '<div class="tips2">'.get_option('ice_tips').'</div>';
						echo '</div>';
					}

					if(function_exists('get_field_objects') && !_MBT('post_meta_top')){
			            $fields = get_field_objects();
			            if( $fields ){
			            	uasort($fields,'mbt_compare_field');
			            	echo '<div class="article-custom-metas clearfix">';
			                foreach( $fields as $field_name => $field ){
			                	if($field['value']){
			                        echo '<div class="meta">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
			            }
			        }
				}elseif($start_see || ($start_see2 && $erphp_see2_style)){
					echo '<div class="erphpdown-box"><span class="erphpdown-title">'.__('内容查看','mobantu').'</span>';
					
					echo '<div class="erphpdown-con clearfix">';

					if($price){
						if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
							echo '<div class="erphpdown-price">'.__('查看价格','mobantu').'<span>'.$price.'</span> '.$money_name.'</div>';
						}else{
							if($memberDown == 4){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_vip_name).'</div>';
							}elseif($memberDown == 15){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_quarter_name).'</div>';
							}elseif($memberDown == 8){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_year_name).'</div>';
							}elseif($memberDown == 9){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_life_name).'</div>';
							}
						}
					}else{
						if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
							echo '<div class="erphpdown-price">'.__('查看价格','mobantu').'<span>'.__('免费','mobantu').'</span></div>';
						}else{
							if($memberDown == 4){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_vip_name).'</div>';
							}elseif($memberDown == 15){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_quarter_name).'</div>';
							}elseif($memberDown == 8){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_year_name).'</div>';
							}elseif($memberDown == 9){
								echo '<div class="erphpdown-price">'.__('查看价格','mobantu').sprintf(__('<span>%s</span>专享','mobantu'),$erphp_life_name).'</div>';
							}
						}
					}

					echo '<div class="erphpdown-cart">';
					if($price || $memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9){
						if(is_user_logged_in() || ( ($userType && ($memberDown==3 || $memberDown==4)) || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType >= 10) )){
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

							$buyText = __('立即购买','mobantu');
							if($down_repeat && $down_info_repeat && !$down_info){
								$buyText = __('再次购买','mobantu');
							}

							if(!$down_info){
								if(!$userType){
									$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_vip_name).'</a>';
								}else{
									if(($memberDown == 23 || $memberDown == 24) && $userType < 9){
										$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
									}
									elseif(($memberDown == 13 || $memberDown == 14 || $memberDown == 20) && $userType < 10){
										$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
									}
								}
								if($userType < 8){
									$vip4 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_quarter_name).'</a>';
								}
								if($userType < 9){
									$vip2 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
								}
								if($userType < 10){
									$vip3 = '<a href="'.$erphp_url_front_vip.'" target="_blank">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';
								}
							}

							$user_id = $user_info->ID;
							$wppay = new EPD(get_the_ID(), $user_id);

							if( ($userType && ($memberDown==3 || $memberDown==4)) || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType >= 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){

								echo '<style>.erphpdown-box, .erphpdown-box + .article-custom-metas{display:none}</style>';

								if($memberDown==3){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).'</div>';
								}elseif($memberDown==2){
									echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'</div>';
								}elseif($memberDown==13){
									echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
								}elseif($memberDown==23){
									echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
								}elseif($memberDown==5){
									echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'</div>';
								}elseif($memberDown==14){
									echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
								}elseif($memberDown==24){
									echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
								}elseif($memberDown==20){
									echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
								}elseif($memberDown==21){
									echo '<div class="vip">'.$erphp_vip_discounts.'</div>';
								}elseif($memberDown==16){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).'</div>';
								}elseif($memberDown==6){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).'</div>';
								}elseif($memberDown==7){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).'</div>';
								}elseif($memberDown==4){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_vip_name).'</div>';
								}elseif($memberDown == 15){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_quarter_name).'</div>';
								}elseif($memberDown == 8){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_year_name).'</div>';
								}elseif($memberDown == 9){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_life_name).'</div>';
								}elseif ($memberDown==10){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo '</div>';
								}elseif ($memberDown==17){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo '</div>';
								}elseif ($memberDown==18){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo '</div>';
								}elseif ($memberDown==19){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo '</div>';
								}elseif ($memberDown==11){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).'</div>';
								}elseif ($memberDown==12){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).'</div>';
								}

							}else{
								if($memberDown==3){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).$vip.'</div>';
								}elseif($memberDown==2){
									echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).$vip.'</div>';
								}elseif($memberDown==13){
									echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
								}elseif($memberDown==23){
									echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip.'</div>';
								}elseif($memberDown==5){
									echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).$vip.'</div>';
								}elseif($memberDown==14){
									echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
								}elseif($memberDown==24){
									echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip.'</div>';
								}elseif($memberDown==20){
									echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip.'</div>';
								}elseif($memberDown==21){
									echo '<div class="vip">'.$erphp_vip_discounts.$vip.'</div>';
								}elseif($memberDown==16){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).$vip4.'</div>';
								}elseif($memberDown==6){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).$vip2.'</div>';
								}elseif($memberDown==7){
									echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).$vip3.'</div>';
								}

								if($memberDown==4){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_vip_name).$vip.'</div>';
								}elseif($memberDown==15){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_quarter_name).$vip4.'</div>';
								}elseif($memberDown==8){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_year_name).$vip2.'</div>';
								}elseif($memberDown==9){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_life_name).$vip3.'</div>';
								}elseif($memberDown==10){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo $vip.'</div>';
									if($userType){
										if($down_checkpan) echo $down_checkpan;
										else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
									}
								}elseif($memberDown==17){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo $vip4.'</div>';
									if($userType >= 8){
										if($down_checkpan) echo $down_checkpan;
										else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
									}
								}elseif($memberDown==18){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo $vip2.'</div>';
									if($userType >= 9){
										if($down_checkpan) echo $down_checkpan;
										else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
									}
								}elseif($memberDown==19){
									echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
									if($member_zdiscount){
										echo '、'.$erphp_vip_discounts;
									}
									echo $vip3.'</div>';
									if($userType >= 10){
										if($down_checkpan) echo $down_checkpan;
										else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
									}
								}elseif($memberDown==11){
									if($userType){
										echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).$vip.'</div>';
										if($down_checkpan) echo $down_checkpan;
										else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
									}else{
										echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).$vip.'</div>';
									}
								}elseif($memberDown==12){
									if($userType){
										echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).$vip.'</div>';
										if($down_checkpan) echo $down_checkpan;
										else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
									}else{
										echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).$vip.'</div>';
									}
								}else{
									if($down_checkpan) echo $down_checkpan;
									else echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
								}
							}	
						}else{
							$buyText = __('立即购买','mobantu');
							$isWppayPaid = 0;
							if(get_option('erphp_wppay_down')){
								$user_id = 0;
								$wppay = new EPD(get_the_ID(), $user_id);
								if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
									$isWppayPaid = 1;
								}else{
									$isWppayPaid = 2;
								}
							}
							$vip = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_vip_name).'</a>';
							$vip4 = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_quarter_name).'</a>';
							$vip2 = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_year_name).'</a>';
							$vip3 = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip-loader">'.sprintf(__('升级%s','mobantu'),$erphp_life_name).'</a>';

							if($memberDown==3){
								echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_vip_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==2){
								echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==13){
								echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==23){
								echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==5){
								echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==14){
								echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==24){
								echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s免费','mobantu'),$erphp_year_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==20){
								echo '<div class="vip">'.$erphp_vip_discounts.'、'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==21){
								echo '<div class="vip">'.$erphp_vip_discounts.($isWppayPaid == 2?$vip:'').'</div>';
							}elseif($memberDown==16){
								echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_quarter_name).($isWppayPaid == 2?$vip4:'').'</div>';
							}elseif($memberDown==6){
								echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_year_name).($isWppayPaid == 2?$vip2:'').'</div>';
							}elseif($memberDown==7){
								echo '<div class="vip">'.sprintf(__('%s免费','mobantu'),$erphp_life_name).($isWppayPaid == 2?$vip3:'').'</div>';
							}elseif($memberDown==4){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_vip_name).$vip.'</div>';
							}elseif($memberDown == 15){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_quarter_name).$vip4.'</div>';
							}elseif($memberDown == 8){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_year_name).$vip2.'</div>';
							}elseif($memberDown == 9){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s查看','mobantu'),$erphp_life_name).$vip3.'</div>';
							}elseif ($memberDown==10){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name);
								if($member_zdiscount){
									echo '、'.$erphp_vip_discounts;
								}
								echo $vip.'</div>';
							}elseif ($memberDown==17){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_quarter_name);
								if($member_zdiscount){
									echo '、'.$erphp_vip_discounts;
								}
								echo $vip4.'</div>';
							}elseif ($memberDown==18){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_year_name);
								if($member_zdiscount){
									echo '、'.$erphp_vip_discounts;
								}
								echo $vip2.'</div>';
							}elseif ($memberDown==19){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_life_name);
								if($member_zdiscount){
									echo '、'.$erphp_vip_discounts;
								}
								echo $vip3.'</div>';
							}elseif ($memberDown==11){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 5折','mobantu'),$erphp_year_name).$vip.'</div>';
							}elseif ($memberDown==12){
								echo '<div class="vip vip-only">'.sprintf(__('仅限%s购买','mobantu'),$erphp_vip_name).'、'.sprintf(__('%s 8折','mobantu'),$erphp_year_name).$vip.'</div>';
							}

							if(get_option('erphp_wppay_down')){
								if($isWppayPaid == 1){
									echo '<style>.erphpdown-box, .erphpdown-box + .article-custom-metas{display:none}</style>';
								}else{
									if($memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9 || $memberDown == 10 || $memberDown == 11 || $memberDown == 12){
										//echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
									}else{
										echo '<a href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().' class="down erphpdown-iframe">'.$buyText.'</a>';
									}
								}
							}else{
								echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
							}

						}
					}else{
						if(is_user_logged_in()){
							if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
								echo '<style>.erphpdown-box, .erphpdown-box + .article-custom-metas{display:none}</style>';
							}
						}else{
							if(get_option('erphp_wppay_down') && !get_option('erphp_free_login')){
								if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
									echo '<style>.erphpdown-box, .erphpdown-box + .article-custom-metas{display:none}</style>';
								}
							}else{
								echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
							}
						}
					}

					echo '</div>';
						
					echo '</div>';

					if($days){
						echo '<div class="tips2">'.sprintf(__('此内容购买后%s天内可查看。','mobantu'), $days);
						if(get_option('ice_tips_see')){
						 	echo get_option('ice_tips_see');
						}
						echo '</div>';
					}else{
						if(get_option('ice_tips_see')){
						 	echo '<div class="tips2">'.get_option('ice_tips_see').'</div>';
						}
					}

					
					echo '</div>';
					if(function_exists('get_field_objects') && !_MBT('post_meta_top')){
			            $fields = get_field_objects();
			            if( $fields ){
			            	uasort($fields,'mbt_compare_field');
			            	echo '<div class="article-custom-metas clearfix">';
			                foreach( $fields as $field_name => $field ){
			                	if($field['value']){
			                        echo '<div class="meta">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
			            }
			        }
				}elseif($erphp_down == 6){
					echo '<div class="erphpdown-box"><span class="erphpdown-title">'.$erphp_box_faka_title.'</span><div class="erphpdown-con clearfix"><div class="erphpdown-price">'.__('卡密价格','mobantu').'<span>'.$price.'</span> '.$money_name.'</div><div class="erphpdown-cart">';

					if($memberDown==2){
						echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'</div>';
					}elseif($memberDown==5){
						echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'</div>';
					}elseif($memberDown==21){
						echo '<div class="vip">'.$erphp_vip_discounts.'</div>';
					}

					if(function_exists('getErphpActLeft')) echo '<div class="vip left">'.__('库存：','mobantu').getErphpActLeft(get_the_ID()).'</div>';

					if(get_option('erphp_faka_login') && !is_user_logged_in()){
						echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a></div></div>';
					}else{
						echo '<a href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" class="down erphpdown-iframe">'.__('立即购买','mobantu').'</a></div></div>';
					}

					if(get_option('ice_tips_faka')){
					 	echo '<div class="tips2">'.get_option('ice_tips_faka').'</div>';
					}

					echo '</div>';

					if(function_exists('get_field_objects') && !_MBT('post_meta_top')){
			            $fields = get_field_objects();
			            if( $fields ){
			            	uasort($fields,'mbt_compare_field');
			            	echo '<div class="article-custom-metas clearfix">';
			                foreach( $fields as $field_name => $field ){
			                	if($field['value']){
			                        echo '<div class="meta">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
			            }
			        }
				}elseif($erphp_down == 7){
					echo '<div class="erphpdown-box"><span class="erphpdown-title">'.__('实物商品','mobantu').'</span><div class="erphpdown-con clearfix"><div class="erphpdown-price">'.__('商品价格','mobantu').'<span>'.$price.'</span> '.$money_name.'</div><div class="erphpdown-cart">';

					if($memberDown==2){
						echo '<div class="vip">'.sprintf(__('%s 5折','mobantu'),$erphp_vip_name).'</div>';
					}elseif($memberDown==5){
						echo '<div class="vip">'.sprintf(__('%s 8折','mobantu'),$erphp_vip_name).'</div>';
					}elseif($memberDown==21){
						echo '<div class="vip">'.$erphp_vip_discounts.'</div>';
					}

					if(is_user_logged_in()){
						echo '<a href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&timestamp='.time().'" class="down erphpdown-iframe erphpdown-iframe-large">'.__('立即购买','mobantu').'</a>';
					}else{
						echo '<a href="javascript:;" class="down signin-loader">'.__('立即购买','mobantu').'</a>';
					}

					echo '</div></div></div>';

					if(function_exists('get_field_objects') && !_MBT('post_meta_top')){
			            $fields = get_field_objects();
			            if( $fields ){
			            	uasort($fields,'mbt_compare_field');
			            	echo '<div class="article-custom-metas clearfix">';
			                foreach( $fields as $field_name => $field ){
			                	if($field['value']){
			                        echo '<div class="meta">';
		                            echo '<t>' . $field['label'] . '：</t>';
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
			            }
			        }
				}
			}else{
				echo '<div class="modown-reply">'.__('此隐藏内容 <a href="javascript:scrollToTop(\'#respond\',-120);">评论</a> 本文后<span>刷新页面</span>可见！','mobantu').'</div>';
			}
		}
	}
}


if(!function_exists('MBThemes_erphpdown_download')){
	function MBThemes_erphpdown_download($msg,$pid){
		get_header();
	?>
		<link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css" type="text/css" />
		<style>
		body{background: #f9f9f9}
		.banner-page{display: block;background-color: <?php echo _MBT('banner_download_color_bg');?>;<?php if(_MBT('banner_download_img')){?>background-image: url(<?php echo _MBT('banner_download_img');?>);<?php }?>}
		.archive-title{margin-bottom:0}
		.content-wrap{margin-bottom: 20px;text-align: center;}
		.content{display: inline-block;max-width: 580px;text-align: left;width:100%}
		.single-content{padding:50px 30px;margin-bottom: 0;border-radius:0;box-shadow: inset 0px 15px 10px -15px #d4d4d4;}
		.article-content{margin-bottom: 0;font-size: 15px;}
		.article-content p{text-indent:0}
		
		body.night .single-content{box-shadow: inset 0px 15px 10px -15px #070808;}
		
		@media (max-width:620px){
			.single-content{padding:30px 20px;}
			.modown-erphpdown-bottom{padding:20px;}
		}
		</style>
		<div class="banner-page">
			<div class="container">
				<h1 class="archive-title"><?php _e('下载','mobantu');?> <?php
				$index=isset($_GET['index']) ? $_GET['index'] : '';
				$index_name = '';
				if($index){
					$urls = get_post_meta($pid, 'down_urls', true);
					if($urls){
						$cnt = count($urls['index']);
						if($cnt){
							for($i=0; $i<$cnt;$i++){
								if($urls['index'][$i] == $index){
			    					$index_name = ' - '.$urls['name'][$i];
			    					break;
			    				}
							}
						}
					}
				}
				echo get_the_title($pid).$index_name;?></h1>
			</div>
		</div>
		<div class="main main-download">
			<div class="container clearfix">
				<div class="content-wrap clearfix">
			    	<div class="content">
			    		<article class="single-content">
			    			<span class="mbt-down-top"></span>
				    		<div class="article-content">
				    			<div class="erphpdown-msg">
				    				<?php echo $msg;?>
				    			</div>
				            </div>
			            </article>
			            <div class="modown-erphpdown-bottom">
			            	<span class="line"></span>
			            	<?php if(_MBT('ad_erphpdown_s')){?>
			    			<div class="erphpdown-ad<?php echo (_MBT('ad_erphpdown_m')?' modown-ad-mobile-hide':'');?>"><?php echo _MBT('ad_erphpdown');?></div>
			    			<?php }?>
			            </div>
			    	</div>
			    </div>
			</div>
		</div>
	<?php
		get_footer();
	?>
	<script>
		new Clipboard(".erphpdown-down-btn");
		jQuery(".erphpdown-down-btn").click(function(){
			layer.msg("<?php _e('已复制提取码','mobantu');?>");
		});
	</script>
	<?php
		exit;
	}
}

if(!function_exists('MBThemes_erphpdown_vipname')){
	function MBThemes_erphpdown_vipname(){
		$erphp_super_name    = get_option('erphp_super_name')?get_option('erphp_super_name'):'全站通VIP';
		$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
		$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
		$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
		$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
		$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
		$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

		$userTypeId=getUsreMemberType();
	    if($userTypeId==6){
	        echo '<t>'.$erphp_day_name.'</t>';
	    }elseif($userTypeId==7){
	        echo '<t>'.$erphp_month_name.'</t>';
	    }elseif ($userTypeId==8){
	        echo '<t>'.$erphp_quarter_name.'</t>';
	    }elseif ($userTypeId==9){
	        echo '<t>'.$erphp_year_name.'</t>';
	    }elseif ($userTypeId==10){
	        echo '<t>'.$erphp_life_name.'</t>';
	    }elseif ($userTypeId==11){
	        echo '<t>'.$erphp_super_name.'</t>';
	    }elseif(function_exists('getUsreMemberCatStatus') && getUsreMemberCatStatus()){
	    	echo '<t>'.__('分类VIP','mobantu').'</t>';
	    }else {
	        echo '<d>'.__('普通用户','mobantu').'</d>';
	    }
	}
}

if(!function_exists('MBThemes_erphpdown_viphtml')){
	function MBThemes_erphpdown_viphtml(){
		global $current_user;
		$erphp_super_name    = get_option('erphp_super_name')?get_option('erphp_super_name'):'全站通VIP';
		$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
		$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
		$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
		$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
		$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
		$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

		$erphp_life_days    = get_option('erphp_life_days');

		$userTypeId=getUsreMemberType();
	    if($userTypeId==6){
	        echo $erphp_day_name;
	    }elseif($userTypeId==7){
	        echo $erphp_month_name;
	    }elseif ($userTypeId==8){
	        echo $erphp_quarter_name;
	    }elseif ($userTypeId==9){
	        echo $erphp_year_name;
	    }elseif ($userTypeId==10){
	        echo $erphp_life_name;
	    }elseif ($userTypeId==11){
	        echo $erphp_super_name;
	    }else {
	        echo __('普通用户','mobantu');
	    }

	    if($erphp_life_days){
	    	echo ($userTypeId>0) ?'<t>'.getUsreMemberTypeEndTime().__('到期','mobantu').'</t>':'';
	    }else{
		    echo ($userTypeId>0&&$userTypeId<10) ?'<t>'.getUsreMemberTypeEndTime().__('到期','mobantu').'</t>':'';
		    echo ($userTypeId >= 10) ?'<t>'.__('永久尊享','mobantu').'</t>':'';
		}

	    if($userTypeId){
		    $erphp_life_times    = get_option('erphp_life_times');
			$erphp_year_times    = get_option('erphp_year_times');
			$erphp_quarter_times = get_option('erphp_quarter_times');
			$erphp_month_times  = get_option('erphp_month_times');
			$erphp_day_times  = get_option('erphp_day_times');

			$erphp_life_ytimes    = get_option('erphp_life_ytimes');
			$erphp_year_ytimes    = get_option('erphp_year_ytimes');
			$erphp_quarter_ytimes = get_option('erphp_quarter_ytimes');
			$erphp_month_ytimes  = get_option('erphp_month_ytimes');
			$erphp_day_ytimes  = get_option('erphp_day_ytimes');

			$vip_times_see = get_option('vip_times_see');
			$erphp_life_times2    = get_option('erphp_life_times2');
			$erphp_year_times2    = get_option('erphp_year_times2');
			$erphp_quarter_times2 = get_option('erphp_quarter_times2');
			$erphp_month_times2  = get_option('erphp_month_times2');
			$erphp_day_times2  = get_option('erphp_day_times2');

			$erphp_life_ytimes2    = get_option('erphp_life_ytimes2');
			$erphp_year_ytimes2    = get_option('erphp_year_ytimes2');
			$erphp_quarter_ytimes2 = get_option('erphp_quarter_ytimes2');
			$erphp_month_ytimes2  = get_option('erphp_month_ytimes2');
			$erphp_day_ytimes2  = get_option('erphp_day_ytimes2');

			$life_times_includes_free    = get_option('erphp_life_times_free');
			$year_times_includes_free    = get_option('erphp_year_times_free');
			$quarter_times_includes_free = get_option('erphp_quarter_times_free');
			$month_times_includes_free  = get_option('erphp_month_times_free');
			$day_times_includes_free  = get_option('erphp_day_times_free');

			$cc = getSeeCount($current_user->ID);
			if(function_exists('getDownCount')){
				$cc = getDownCount($current_user->ID);
			}

			if($userTypeId == 6 && $erphp_day_times > 0){
				if($day_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_day_times-getSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_day_times-$cc)).'</div>';
				}
			}elseif($userTypeId == 7 && $erphp_month_times > 0){
				if($month_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_month_times-getSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_month_times-$cc)).'</div>';
				}
			}elseif($userTypeId == 8 && $erphp_quarter_times > 0){
				if($quarter_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_quarter_times-getSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_quarter_times-$cc)).'</div>';
				}
			}elseif($userTypeId == 9 && $erphp_year_times > 0){
				if($year_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_year_times-getSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_year_times-$cc)).'</div>';
				}
			}elseif($userTypeId >= 10 && $erphp_life_times > 0){
				if($life_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_life_times-getSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今日剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_life_times-$cc)).'</div>';
				}
			}

			if($vip_times_see){
				if($userTypeId == 6 && $erphp_day_times2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今日剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_day_times2-getSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId == 7 && $erphp_month_times2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今日剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_month_times2-getSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId == 8 && $erphp_quarter_times2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今日剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_quarter_times2-getSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId == 9 && $erphp_year_times2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今日剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_year_times2-getSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId >= 10 && $erphp_life_times2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今日剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_life_times2-getSeeCount($current_user->ID))).'</div>';
					
				}
			}

			if(function_exists('getYearSeeCount')){
				$cc = getYearSeeCount($current_user->ID);
			}
			if(function_exists('getYearDownCount')){
				$cc = getYearDownCount($current_user->ID);
			}

			if($userTypeId == 6 && $erphp_day_ytimes > 0){
				if($day_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_day_ytimes-getYearSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_day_ytimes-$cc)).'</div>';
				}
			}elseif($userTypeId == 7 && $erphp_month_ytimes > 0){
				if($month_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_month_ytimes-getYearSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_month_ytimes-$cc)).'</div>';
				}
			}elseif($userTypeId == 8 && $erphp_quarter_ytimes > 0){
				if($quarter_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_quarter_ytimes-getYearSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_quarter_ytimes-$cc)).'</div>';
				}
			}elseif($userTypeId == 9 && $erphp_year_ytimes > 0){
				if($year_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_year_ytimes-getYearSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_year_ytimes-$cc)).'</div>';
				}
			}elseif($userTypeId >= 10 && $erphp_life_ytimes > 0){
				if($life_times_includes_free){
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_life_ytimes-getYearSeeCountNoVip($current_user->ID))).'</div>';
				}else{
					echo '<div class="down-left">'.sprintf(__('今年剩余%s免费下载数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_life_ytimes-$cc)).'</div>';
				}
			}

			if($vip_times_see){
				if($userTypeId == 6 && $erphp_day_ytimes2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今年剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_day_ytimes2-getYearSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId == 7 && $erphp_month_ytimes2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今年剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_month_ytimes2-getYearSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId == 8 && $erphp_quarter_ytimes2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今年剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_quarter_ytimes2-getYearSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId == 9 && $erphp_year_ytimes2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今年剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_year_ytimes2-getYearSeeCount($current_user->ID))).'</div>';
					
				}elseif($userTypeId >= 10 && $erphp_life_ytimes2 > 0){
					
						echo '<div class="down-left">'.sprintf(__('今年剩余%s免费查看数：<b>%s</b>','mobantu'),$erphp_vip_name,($erphp_life_ytimes2-getYearSeeCount($current_user->ID))).'</div>';
					
				}
			}

		}
	}
}

if(!function_exists('MBThemes_aff_money')){
	function MBThemes_aff_money($uid){
		global $wpdb;
		$money = 0;
		$ice_ali_money_ref = get_option('ice_ali_money_ref')?get_option('ice_ali_money_ref'):0;
		$ice_ali_money_ref = $ice_ali_money_ref*0.01;
		$ice_ali_money_ref2 = get_option('ice_ali_money_ref2')?get_option('ice_ali_money_ref2'):0;
		$ice_ali_money_ref2 = $ice_ali_money_ref2*0.01;
		if($ice_ali_money_ref){
			$money1 = 0;$money2 = 0;
			$list = $wpdb->get_results("SELECT ID FROM $wpdb->users WHERE father_id=".$uid);
			if($list){
				foreach($list as $value){
					$money1 += erphpGetUserAllXiaofei($value->ID);
					if($ice_ali_money_ref2){
						$list2 = $wpdb->get_results("SELECT ID FROM $wpdb->users WHERE father_id=".$value->ID);
						if($list2){
							foreach($list2 as $value){
								$money2 += erphpGetUserAllXiaofei($value->ID);
							}
						}
					}
				}
			}
			$money = $money1*$ice_ali_money_ref + $money2*$ice_ali_money_ref2;
		}
		return sprintf("%.2f",$money);
	}
}

if(!function_exists('MBThemes_aff_money2')){
	function MBThemes_aff_money2($uid){
		global $wpdb;
		$money = 0;

		//一级推广
		$ice_ali_money_ref = get_option('ice_ali_money_ref')?get_option('ice_ali_money_ref'):0;
		
		if(function_exists("erphpdown_get_vip_ref")){
			$erphpdown_get_vip_ref = erphpdown_get_vip_ref($uid);
			if($erphpdown_get_vip_ref){
				$ice_ali_money_ref = $erphpdown_get_vip_ref;
			}
		}

		$ice_ali_money_refu = get_user_meta($uid,'ice_ali_money_ref',true);
		if($ice_ali_money_refu || $ice_ali_money_refu == '0'){
			$ice_ali_money_ref = $ice_ali_money_refu;
		}

		$ice_ali_money_ref = $ice_ali_money_ref*0.01;

		//二级推广
		$ice_ali_money_ref2 = get_option('ice_ali_money_ref2')?get_option('ice_ali_money_ref2'):0;

		$ice_ali_money_refu2 = get_user_meta($uid,'ice_ali_money_ref2',true);
		if($ice_ali_money_refu2 || $ice_ali_money_refu2 == '0'){
			$ice_ali_money_ref2 = $ice_ali_money_refu2;
		}

		$ice_ali_money_ref2 = $ice_ali_money_ref2*0.01;

		//计算总推广
		if($ice_ali_money_ref){
			$money1 = 0;$money11 = 0;
			$money1 = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id in (SELECT ID FROM $wpdb->users WHERE father_id=".$uid.")");
			$money11 = $wpdb->get_var("SELECT sum(ice_price) FROM $wpdb->vip where ice_user_id in (SELECT ID FROM $wpdb->users WHERE father_id=".$uid.")");

			$money = $money + $money1*$ice_ali_money_ref + $money11*$ice_ali_money_ref;
		}
		
		if($ice_ali_money_ref2){
			$money2 = 0;$money22 = 0;
			$money2 = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id in (SELECT ID FROM $wpdb->users WHERE father_id in (SELECT ID FROM $wpdb->users WHERE father_id=".$uid."))");
			$money22 = $wpdb->get_var("SELECT sum(ice_price) FROM $wpdb->vip where ice_user_id in (SELECT ID FROM $wpdb->users WHERE father_id in (SELECT ID FROM $wpdb->users WHERE father_id=".$uid."))");

			$money = $money + $money2*$ice_ali_money_ref2 + $money22*$ice_ali_money_ref2;
		}

		return sprintf("%.2f",$money);
	}
}


function epd_see2_callback(){
	date_default_timezone_set('Asia/Shanghai'); 
	global $wpdb;
	$post_id = esc_sql(intval($_POST['post_id']));
	$post_vip = isset($_POST['vip'])?$_POST['vip']:'1';
	$token = $_POST['token'];
	$user_info=wp_get_current_user();
	$userType=getUsreMemberType();
	$status = 0;$vip = 1;

	$erphp_life_times    = get_option('erphp_life_times');
	$erphp_year_times    = get_option('erphp_year_times');
	$erphp_quarter_times = get_option('erphp_quarter_times');
	$erphp_month_times  = get_option('erphp_month_times');
	$erphp_day_times  = get_option('erphp_day_times');

	if(get_option('vip_times_see')){
		$erphp_life_times    = get_option('erphp_life_times2');
		$erphp_year_times    = get_option('erphp_year_times2');
		$erphp_quarter_times = get_option('erphp_quarter_times2');
		$erphp_month_times  = get_option('erphp_month_times2');
		$erphp_day_times  = get_option('erphp_day_times2');
	}

	$erphpdown_downkey = get_option('erphpdown_downkey');

	if($token == md5($erphpdown_downkey.$post_id.$erphpdown_downkey.$post_vip) && $userType && $userType >= $post_vip){
		if($userType == 6 && $erphp_day_times > 0){
			if( checkSeeLog($user_info->ID,$post_id,$erphp_day_times,erphpGetIP(),$vip) ){
				if(get_option('vip_times_see')){
					addSeeLog($user_info->ID,$post_id,erphpGetIP(),$vip,1);
				}else{
					addDownLog($user_info->ID,$post_id,erphpGetIP(),$vip);
				}
				$status = 200;
			}else{
				$status = 201;
			}
		}elseif($userType == 7 && $erphp_month_times > 0){
			if( checkSeeLog($user_info->ID,$post_id,$erphp_month_times,erphpGetIP(),$vip) ){
				if(get_option('vip_times_see')){
					addSeeLog($user_info->ID,$post_id,erphpGetIP(),$vip,1);
				}else{
					addDownLog($user_info->ID,$post_id,erphpGetIP(),$vip);
				}
				$status = 200;
			}else{
				$status = 201;
			}
		}elseif($userType == 8 && $erphp_quarter_times > 0){
			if( checkSeeLog($user_info->ID,$post_id,$erphp_quarter_times,erphpGetIP(),$vip) ){
				if(get_option('vip_times_see')){
					addSeeLog($user_info->ID,$post_id,erphpGetIP(),$vip,1);
				}else{
					addDownLog($user_info->ID,$post_id,erphpGetIP(),$vip);
				}
				$status = 200;
			}else{
				$status = 201;
			}
		}elseif($userType == 9 && $erphp_year_times > 0){
			if( checkSeeLog($user_info->ID,$post_id,$erphp_year_times,erphpGetIP(),$vip) ){
				if(get_option('vip_times_see')){
					addSeeLog($user_info->ID,$post_id,erphpGetIP(),$vip,1);
				}else{
					addDownLog($user_info->ID,$post_id,erphpGetIP(),$vip);
				}
				$status = 200;
			}else{
				$status = 201;
			}
		}elseif($userType == 10 && $erphp_life_times > 0){
			if( checkSeeLog($user_info->ID,$post_id,$erphp_life_times,erphpGetIP(),$vip) ){
				if(get_option('vip_times_see')){
					addSeeLog($user_info->ID,$post_id,erphpGetIP(),$vip,1);
				}else{
					addDownLog($user_info->ID,$post_id,erphpGetIP(),$vip);
				}
				$status = 200;
			}else{
				$status = 201;
			}
		}
	}else{
		$status = 202;
	}

	$result = array(
		'status' => $status
	);

	header('Content-type: application/json');
	echo json_encode($result);
	exit;
}
add_action( 'wp_ajax_epd_see2', 'epd_see2_callback');
add_action( 'wp_ajax_nopriv_epd_see2', 'epd_see2_callback');