<?php 
	$erphpdown_downkey = get_option('erphpdown_downkey');
	$dv = 0;
	if(function_exists('getPlayVipAdPv')){
		$vod_id = get_post_meta(get_the_ID(),'vod_id',true);
		$vod_ids = get_post_meta(get_the_ID(),'vod_ids',true);
		if($vod_ids){
			$video_index = 1;
			if(isset($_GET['vindex']) && $_GET['vindex']>0){
				$video_index = $_GET['vindex'];
			}
			$vod_id = $vod_ids['src'][$video_index-1];
		}
		if($vod_id){
			echo '<div class="article-video">'.do_shortcode('[erphpvideo]').'</div>';
		}else{
			$dv = 1;
		}
	}else{
		$dv = 1;
	}
	
	if($dv){
		$videos = get_post_meta(get_the_ID(),'videos',true);
		$video = get_post_meta(get_the_ID(),'video',true);
		$video_preview = get_post_meta(get_the_ID(),'video_preview',true);
		$vfree = 0;
		if($video || $videos){
			global $post;
			if(_mbt('post_video_feature') && MBThemes_thumbnail_share($post)){
				echo '<style>.modown-erphpdown-video{background-image:url('.MBThemes_thumbnail_share($post).');background-position:center center;background-size:cover;background-repeat:no-repeat;}</style>';
			}
			$player = _MBT('post_video_player')?_MBT('post_video_player'):'ckplayer';
			$player_dash = 0;
			if($video && strpos( strtolower($video),'.mpd') !== false){
				$player = 'dplayer';
				$player_dash = 1;
				echo '<script>var dplayer_dash = 1;</script>';
			}elseif($videos){
				$video_index = 1;
				if(isset($_GET['vindex']) && $_GET['vindex']>0){
					$video_index = $_GET['vindex'];
				}
				if(strpos( strtolower($videos['src'][$video_index-1]),'.mpd') !== false){
					$player = 'dplayer';
					$player_dash = 1;
					echo '<script>var dplayer_dash = 1;</script>';
				}
				if(isset($videos['free'][$video_index-1]) && $videos['free'][$video_index-1]){
					$vfree = 1;
				}
			}

			if($player == 'dplayer'){
				echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/hls.min.js"></script>';
				echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/flv.min.js"></script>';
				if($player_dash) echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/dash.min.js"></script>';
				echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/'.$player.'.min.js"></script>';
			}elseif($player == 'ckplayer'){
				echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/hls.js/hls.min.js"></script>';
				//echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/flv.js/flv.min.js"></script>';
				echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_url').'/module/'.$player.'/css/ckplayer.css">';
				echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/js/'.$player.'.min.js"></script>';
			}else{
				echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/'.$player.'.min.js"></script>';
			}

			$video_type = get_post_meta(get_the_ID(),'video_type',true);
			if(wp_is_erphpdown_active()){
				$video_erphpdown = get_post_meta(get_the_ID(),'video_erphpdown',true);
				$memberDown=get_post_meta(get_the_ID(), 'member_down',true);
				$days=get_post_meta(get_the_ID(), 'down_days', true);
				$price=get_post_meta(get_the_ID(), 'down_price', true);
				$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
				$erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
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
			
				$video_menu_html = '';
				$video_price_text = __('此视频观看价格为','mobantu');

				$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
				$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
				$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
				$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

				$erphp_vip_discounts = sprintf(__('%s折扣','mobantu'),$erphp_vip_name);

				if($videos){
					$videos_tv = get_post_meta(get_the_ID(),'videos_tv',true);
					$video_index = 1;
					if(isset($_GET['vindex']) && $_GET['vindex']>0){
						$video_index = $_GET['vindex'];
					}
					$video = $videos['src'][$video_index-1];

					$cnt = count($videos['src']);
					if($cnt > 1){
						$video_menu_html .= '<div class="videos-menu'.($videos_tv?' videos-menu-tv':'').'">';
						$video_menu_html .= '<h4>'.sprintf(__('共 %s 节','mobantu'), $cnt).'</h4>';
						if($videos_tv){
							for($i=0; $i<$cnt;$i++){
								$alt = $videos['alt'][$i];
			                	$class = ""; if($video_index == $i+1) $class="active";
			                	$video_menu_html .= '<div class="item item-tv"><a href="'.add_query_arg("vindex",$i+1,get_permalink()).'" rel="nofollow" class="'.$class.'">'.($alt?$alt:('<t>第</t>'.($i+1).'<t>集</t>')).'</a></div>';
			                }
						}else{
			                for($i=0; $i<$cnt;$i++){
			                	$alt = $videos['alt'][$i];
			                	$time = $videos['time'][$i];
			                	$class = ""; if($video_index == $i+1) $class="active";
			                	$video_menu_html .= '<div class="item"><a href="'.add_query_arg("vindex",$i+1,get_permalink()).'" rel="nofollow" class="'.$class.'">· '.($alt?$alt:('<t>第</t>'.($i+1).'<t>集</t>')).'</a><span>'.$time.'</span></div>';
			                }
			            }
		                $video_menu_html .= '</div>';
		                $video_menu_html .= '<a href="javascript:;" class="vmenu-trigger"><i class="icon icon-arrow-left"></i></a>';
		            }
		            $video_price_text = __('全套视频观看价格合计为','mobantu');
				}

				if($video){
					if($video_erphpdown){
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						$erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));
						if(get_option('erphp_url_front_vip')){
							$erphp_url_front_vip = get_option('erphp_url_front_vip');
						}

						$erphp_url_front_login = get_permalink(MBThemes_page("template/login.php"));

						if($start_down2){
							if($vfree || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || !$price || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10)){
								if($video_type){
			    					echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
			    				}else{
				    				$nonce = wp_create_nonce(rand(10,1000));
				    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>'.$video_menu_html.'</div>';
								}
							}else{
								$video_content = '';
								if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
									$wppay_vip_name = $erphp_vip_name;
									if($memberDown == 16){
										$wppay_vip_name = $erphp_quarter_name;
									}elseif($memberDown == 6){
										$wppay_vip_name = $erphp_year_name;
									}elseif($memberDown == 7){
										$wppay_vip_name = $erphp_life_name;
									}
									$video_content .= $video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.__('元','mobantu').'<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">'.__('立即购买','mobantu').'</a>&nbsp;&nbsp;<b>'.__('或','mobantu').'</b>&nbsp;&nbsp;'.sprintf( __('升级%s后免费','mobantu'), $wppay_vip_name).'<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $wppay_vip_name).'</a>';
								}else{
									$video_content .= $video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.__('元','mobantu').'<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">'.__('立即购买','mobantu').'</a>';	
								}

								echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$video_content.'</div></div>'.$video_menu_html.'</div>';
							}
						}elseif($erphp_down == 1 || $erphp_down == 2 || $erphp_down == 3){
							if(is_user_logged_in() || ( (($memberDown==3 || $memberDown==4) && $userType) || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType == 10) )){
								$user_info=wp_get_current_user();
								if($user_info->ID){
									$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
									if($days > 0 && $down_info){
										$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
										$nowDate = date('Y-m-d H:i:s');
										if(strtotime($nowDate) > strtotime($lastDownDate)){
											$down_info = null;
										}
									}
								}

								if( (($memberDown==3 || $memberDown==4) && $userType) || $vfree || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType == 10) ){

									if($memberDown==3 || $memberDown==4){
										$data_vip = 1;
									}elseif($memberDown==15 || $memberDown==16){
										$data_vip = 8;
									}elseif($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24){
										$data_vip = 9;
									}elseif($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20){
										$data_vip = 10;
									}else{
										$data_vip = 0;
									}

									if(!$wppay->isWppayPaid() && !$wppay->isWppayPaidNew() && !$down_info && !$vfree){
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

										if(function_exists('checkSeeHas')){
								            $cc = checkSeeHas($user_info->ID,get_the_ID());
								        }else{
								            $cc = checkDownHas($user_info->ID,get_the_ID());
								        }

										if($cc){
											if($video_type){
						    					echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
						    				}else{
							    				$nonce = wp_create_nonce(rand(10,1000));
							    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>'.$video_menu_html.'</div>';
											}
										}else{
											if($userType == 6 && $erphp_day_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_day_times,erphpGetIP()) ){
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 7 && $erphp_month_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_month_times,erphpGetIP()) ){
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 8 && $erphp_quarter_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_quarter_times,erphpGetIP()) ){
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 9 && $erphp_year_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_year_times,erphpGetIP()) ){
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 10 && $erphp_life_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_life_times,erphpGetIP()) ){
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}else{
												if($video_type){
							    					echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
							    				}else{
								    				$nonce = wp_create_nonce(rand(10,1000));
								    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>'.$video_menu_html.'</div>';
												}
											}
										}
									}else{
					    				if($video_type){
					    					echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
					    				}else{
						    				$nonce = wp_create_nonce(rand(10,1000));
						    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>'.$video_menu_html.'</div>';
										}
									}
								}else{
									$video_content = '';
									if($price){
										if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
											$video_content.=$video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
									}else{
										if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
											if($video_type){
						    					echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
						    				}else{
							    				$nonce = wp_create_nonce(rand(10,1000));
							    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>'.$video_menu_html.'</div>';
											}
										}	
									}
									
									if($price || $memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9){
										
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
										if($userType){
											$vipText = '';
											if(($memberDown == 13 || $memberDown == 14 || $memberDown == 20) && $userType < 10){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
											}
										}
										if($memberDown==3){
											$video_content.='（'.$erphp_vip_name.'免费）'.$vipText;
										}elseif ($memberDown==2){
											$video_content.='（'.$erphp_vip_name.' 5折）'.$vipText;
										}elseif ($memberDown==5){
											$video_content.='（'.$erphp_vip_name.' 8折）'.$vipText;
										}elseif ($memberDown==23){
											$video_content.='（'.$erphp_vip_name.' 5折、'.$erphp_year_name.'免费）'.$vipText;
										}elseif ($memberDown==24){
											$video_content.='（'.$erphp_vip_name.' 8折、'.$erphp_year_name.'免费）'.$vipText;
										}elseif ($memberDown==13){
											$video_content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）'.$vipText;
										}elseif ($memberDown==14){
											$video_content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）'.$vipText;
										}elseif ($memberDown==20){
											$video_content.='（'.$erphp_vip_discounts.'、'.$erphp_life_name.'免费）'.$vipText;
										}elseif ($memberDown==21){
											$video_content.='（'.$erphp_vip_discounts.'）'.$vipText;
										}elseif ($memberDown==16){
											if($userType < 8){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_quarter_name).'</a>';
											}
											$video_content.='（'.$erphp_quarter_name.'免费）'.$vipText;
										}elseif ($memberDown==6){
											if($userType < 9){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_year_name).'</a>';
											}
											$video_content.='（'.$erphp_year_name.'免费）'.$vipText;
										}elseif ($memberDown==7){
											if($userType < 10){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
											}
											$video_content.='（'.$erphp_life_name.'免费）'.$vipText;
										}elseif ($memberDown==4){
											if($userType){
												
											}
										}
										

										if($memberDown==4){
											$video_content.='此视频仅限'.$erphp_vip_name.'观看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
										}elseif($memberDown==15){
											$video_content.='此视频仅限'.$erphp_quarter_name.'观看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_quarter_name).'</a>';
										}elseif($memberDown==8){
											$video_content.='此视频仅限'.$erphp_year_name.'观看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_year_name).'</a>';
										}elseif($memberDown==9){
											$video_content.='此视频仅限'.$erphp_life_name.'观看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
										}else{
											if($memberDown==10){
												if($userType){
													$video_content.='（仅限'.$erphp_vip_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
												}else{
													$video_content.='（仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
												}
											}elseif($memberDown==17){
												if($userType >= 8){
													$video_content.='（仅限'.$erphp_quarter_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
												}else{
													$video_content.='（仅限'.$erphp_quarter_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_quarter_name).'</a>';
												}
											}elseif($memberDown==18){
												if($userType >= 9){
													$video_content.='（仅限'.$erphp_year_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
												}else{
													$video_content.='（仅限'.$erphp_year_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_year_name).'</a>';
												}
											}elseif($memberDown==19){
												if($userType == 10){
													$video_content.='（仅限'.$erphp_life_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
												}else{
													$video_content.='（仅限'.$erphp_life_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
												}
											}elseif($memberDown==11){
												if($userType){
													$video_content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）';
													$video_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
												}else{
													$video_content.='（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
												}
											}elseif($memberDown==12){
												if($userType){
													$video_content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）';
													$video_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
												}else{
													$video_content.='（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
												}
											}else{
												$video_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
											}
										}
										echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$video_content.'</div></div>'.$video_menu_html.'</div>';
									}
								}
							}elseif($vfree || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || (!$price && $memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9 && get_option('erphp_wppay_down') && !get_option('erphp_free_login'))){
								if($video_type){
			    					echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
			    				}else{
				    				$nonce = wp_create_nonce(rand(10,1000));
				    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>'.$video_menu_html.'</div>';
								}
							}else{
								$video_content = '';
								if($memberDown == 4){
									$video_content.='此视频仅限'.$erphp_vip_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 15){
									$video_content.='此视频仅限'.$erphp_quarter_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 8){
									$video_content.='此视频仅限'.$erphp_year_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 9){
									$video_content.='此视频仅限'.$erphp_life_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 10){
									$video_content.='此视频仅限'.$erphp_vip_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 17){
									$video_content.='此视频仅限'.$erphp_quarter_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 18){
									$video_content.='此视频仅限'.$erphp_year_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 19){
									$video_content.='此视频仅限'.$erphp_life_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 11){
									$video_content.='此视频仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}elseif($memberDown == 12){
									$video_content.='此视频仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}else{
									$vip_content = '';
									if($memberDown==3){
										$vip_content.=$erphp_vip_name.'免费';
									}elseif($memberDown==2){
										$vip_content.=$erphp_vip_name.' 5折';
									}elseif($memberDown==23){
										$vip_content.=$erphp_vip_name.' 5折、'.$erphp_year_name.'免费';
									}elseif($memberDown==24){
										$vip_content.=$erphp_vip_name.' 8折、'.$erphp_year_name.'免费';
									}elseif($memberDown==13){
										$vip_content.=$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
									}elseif($memberDown==5){
										$vip_content.=$erphp_vip_name.' 8折';
									}elseif($memberDown==14){
										$vip_content.=$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
									}elseif($memberDown==20){
										$vip_content.=$erphp_vip_discounts.'、'.$erphp_life_name.'免费';
									}elseif($memberDown==21){
										$vip_content.=$erphp_vip_discounts;
									}elseif($memberDown==16){
										$vip_content .= $erphp_quarter_name.'免费';
									}elseif($memberDown==6){
										$vip_content .= $erphp_year_name.'免费';
									}elseif($memberDown==7){
										$vip_content .= $erphp_life_name.'免费';
									}

									if(get_option('erphp_wppay_down')){
										if($price){
											$video_content.=$video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');

											$video_content .= $vip_content?('（'.$vip_content.'）<a href="'.$erphp_url_front_login.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>'):'';

											$video_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
										}else{
											//if(!get_option('erphp_free_login')){}else{
												$video_content.=__('此视频仅限注册用户观看，请先','mobantu').'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
											//}
										}
										
									}else{
										if($price){
											$video_content.=$video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').($vip_content?('（'.$vip_content.'）'):'').__('，请先','mobantu').'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
										}else{
											$video_content.=__('此视频仅限注册用户观看，请先','mobantu').'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
										}
									}
								}

								echo '<div class="article-video"><div class="'.$player.'-video '.$player.'-erphpdown-video modown-erphpdown-video"><div class="playicon'.($video_preview?' pre':'').'"'.($video_preview?' data-video="'.$video_preview.'"':'').'><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$video_content.'</div></div>'.$video_menu_html.'</div>';
							}
						}
					}else{
		            	if($video_type){
	    					echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
	    				}else{
		    				$nonce = wp_create_nonce(rand(10,1000));
		    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>'.$video_menu_html.'</div>';
						} 
					}
				}
			}else{
				if($videos){
					$videos_tv = get_post_meta(get_the_ID(),'videos_tv',true);
					$video_index = 1;
					if(isset($_GET['vindex']) && $_GET['vindex']>0){
						$video_index = $_GET['vindex'];
					}
					$video = $videos['src'][$video_index-1];
					echo '<div class="article-video">';
					if($video_type){
						echo '<iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>';
					}else{
	    				$nonce = wp_create_nonce(rand(10,1000));
	    				echo '<div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div>';
					}
					$cnt = count($videos['src']);
					if($cnt > 1){
						echo '<div class="videos-menu'.($videos_tv?' videos-menu-tv':'').'">';
						echo '<h4>'.sprintf(__('共 %s 节','mobantu'), $cnt).'</h4>';
	                    if($videos_tv){
							for($i=0; $i<$cnt;$i++){
								$alt = $videos['alt'][$i];
			                	$class = ""; if($video_index == $i+1) $class="active";
			                	echo '<div class="item item-tv"><a href="'.add_query_arg("vindex",$i+1,get_permalink()).'" rel="nofollow" class="'.$class.'">'.($alt?$alt:('<t>第</t>'.($i+1).'<t>集</t>')).'</a></div>';
			                }
						}else{
			                for($i=0; $i<$cnt;$i++){
			                	$alt = $videos['alt'][$i];
			                	$time = $videos['time'][$i];
			                	$class = ""; if($video_index == $i+1) $class="active";
			                	echo '<div class="item"><a href="'.add_query_arg("vindex",$i+1,get_permalink()).'" rel="nofollow" class="'.$class.'">· '.($alt?$alt:('<t>第</t>'.($i+1).'<t>集</t>')).'</a><span>'.$time.'</span></div>';
			                }
			            }
	                    echo '</div>';
	                    echo '<a href="javascript:;" class="vmenu-trigger"><i class="icon icon-arrow-left"></i></a>';
	                }
	                echo '</div>';
				}elseif($video){
					if($video_type){
						echo '<div class="article-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe></div>';
					}else{
	    				$nonce = wp_create_nonce(rand(10,1000));
	    				echo '<div class="article-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($video)).'"></div></div>';
					}
				}
			}
		}
	}
?>