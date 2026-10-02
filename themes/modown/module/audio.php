<?php
$erphpdown_downkey = get_option('erphpdown_downkey'); 
$audio = get_post_meta(get_the_ID(),'audio_url',true);
$audio_time = get_post_meta(get_the_ID(),'audio_url_time',true);
$audio_preview = get_post_meta(get_the_ID(),'audio',true);
$audio_preview_time = get_post_meta(get_the_ID(),'audio_time',true);
if($audio){
	$audio_prev_html = '<center><div class="article-audio-free">
		    <i class="guxz"></i>
		    <i class="dy"></i>
		    <i class="xy"></i>
		    <i class="gp audio-stick"></i>
		</div></center>';

	$audio_preview_html = '';
	if($audio_preview){
		$audio_preview_html = '<div class="audio-player"><div class="audio" style="margin-bottom:10px">
		    <i class="audio-play"><t>试听</t></i>
		    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_preview_time?$audio_preview_time:'0').'">
		        <source src="'.$audio_preview.'" type="audio/mpeg">
		    </audio>
		    <span class="star-time">00:00</span>
		    <div class="time-bar">
		        <span class="progressBar"></span>
		        <i class="move-color"></i>
		        <p class="timetip"></p>
		    </div>
		    <span class="end-time">'.mbt_sec_to_time($audio_preview_time).'</span>
		    <p class="timeTip"></p><p class="timeTip"></p>
		</div></div>';
	}
	global $post;
	if(wp_is_erphpdown_active()){
		$audio_erphpdown = get_post_meta(get_the_ID(),'audio_erphpdown',true);
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
			
		$audio_price_text = __('此音频播放价格为','mobantu');

		$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
		$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
		$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
		$erphp_month_name = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
		$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

		$erphp_vip_discounts = sprintf(__('%s折扣','mobantu'),$erphp_vip_name);

		if($audio){
			if($audio_erphpdown){
				$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
				$wppay = new EPD(get_the_ID(), $user_id);
				$erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));
				if(get_option('erphp_url_front_vip')){
					$erphp_url_front_vip = get_option('erphp_url_front_vip');
				}

				$erphp_url_front_login = get_permalink(MBThemes_page("template/login.php"));

				if($start_down2){
					if($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || !$price || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10)){
						echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
									    <i class="audio-play"></i>
									    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
									        <source src="'.$audio.'" type="audio/mpeg">
									    </audio>
									    <span class="star-time">00:00</span>
									    <div class="time-bar">
									        <span class="progressBar"></span>
									        <i class="move-color"></i>
									        <p class="timetip"></p>
									    </div>
									    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
									    <p class="timeTip"></p><p class="timeTip"></p>
									</div></div></div>';
					}else{
						$audio_content = '';
						if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
							$wppay_vip_name = $erphp_vip_name;
							if($memberDown == 16){
								$wppay_vip_name = $erphp_quarter_name;
							}elseif($memberDown == 6){
								$wppay_vip_name = $erphp_year_name;
							}elseif($memberDown == 7){
								$wppay_vip_name = $erphp_life_name;
							}
							$audio_content .= $audio_price_text.'<span class="erphpdown-price">'.$price.'</span>'.__('元','mobantu').'<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">'.__('立即购买','mobantu').'</a>&nbsp;&nbsp;<b>'.__('或','mobantu').'</b>&nbsp;&nbsp;'.sprintf( __('升级%s后免费','mobantu'), $wppay_vip_name).'<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $wppay_vip_name).'</a>';
						}else{
							$audio_content .= $audio_price_text.'<span class="erphpdown-price">'.$price.'</span>'.__('元','mobantu').'<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">'.__('立即购买','mobantu').'</a>';	
						}

						echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$audio_content.'</div></div>';
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

						if( (($memberDown==3 || $memberDown==4) && $userType) || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8 || $memberDown==23 || $memberDown==24) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14 || $memberDown==20) && $userType == 10) ){

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

							if(!$wppay->isWppayPaid() && !$wppay->isWppayPaidNew() && !$down_info){
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
		    						echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
									    <i class="audio-play"></i>
									    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
									        <source src="'.$audio.'" type="audio/mpeg">
									    </audio>
									    <span class="star-time">00:00</span>
									    <div class="time-bar">
									        <span class="progressBar"></span>
									        <i class="move-color"></i>
									        <p class="timetip"></p>
									    </div>
									    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
									    <p class="timeTip"></p><p class="timeTip"></p>
									</div></div></div>';
								}else{
									if($userType == 6 && $erphp_day_times > 0){
										if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_day_times,erphpGetIP()) ){
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费播放此音频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即播放</a>（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}else{
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权播放此音频，请明天再来！（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}
									}elseif($userType == 7 && $erphp_month_times > 0){
										if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_month_times,erphpGetIP()) ){
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费播放此音频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即播放</a>（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}else{
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权播放此音频，请明天再来！（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}
									}elseif($userType == 8 && $erphp_quarter_times > 0){
										if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_quarter_times,erphpGetIP()) ){
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费播放此音频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即播放</a>（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}else{
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权播放此音频，请明天再来！（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}
									}elseif($userType == 9 && $erphp_year_times > 0){
										if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_year_times,erphpGetIP()) ){
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费播放此音频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即播放</a>（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}else{
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权播放此音频，请明天再来！（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}
									}elseif($userType == 10 && $erphp_life_times > 0){
										if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_life_times,erphpGetIP()) ){
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费播放此音频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'" data-vip="'.$data_vip.'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.$data_vip).'">立即播放</a>（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}else{
											echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权播放此音频，请明天再来！（今日已播放'.getSeeCount($user_info->ID).'个，还可播放'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</div></div>';
										}
									}else{
										echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
										    <i class="audio-play"></i>
										    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
										        <source src="'.$audio.'" type="audio/mpeg">
										    </audio>
										    <span class="star-time">00:00</span>
										    <div class="time-bar">
										        <span class="progressBar"></span>
										        <i class="move-color"></i>
										        <p class="timetip"></p>
										    </div>
										    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
										    <p class="timeTip"></p><p class="timeTip"></p>
										</div></div></div>';
									}
								}
							}else{
			    				echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
									    <i class="audio-play"></i>
									    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
									        <source src="'.$audio.'" type="audio/mpeg">
									    </audio>
									    <span class="star-time">00:00</span>
									    <div class="time-bar">
									        <span class="progressBar"></span>
									        <i class="move-color"></i>
									        <p class="timetip"></p>
									    </div>
									    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
									    <p class="timeTip"></p><p class="timeTip"></p>
									</div></div></div>';
							}
						}else{
							$audio_content = '';
							if($price){
								if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
									$audio_content.=$audio_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
							}else{
								if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
									$erphp_reg_times = get_option("erphp_reg_times");
									if($erphp_reg_times && !$userType){
										if(checkDownHas($user_info->ID,get_the_ID(),0)){
											echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
											    <i class="audio-play"></i>
											    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
											        <source src="'.$audio.'" type="audio/mpeg">
											    </audio>
											    <span class="star-time">00:00</span>
											    <div class="time-bar">
											        <span class="progressBar"></span>
											        <i class="move-color"></i>
											        <p class="timetip"></p>
											    </div>
											    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
											    <p class="timeTip"></p><p class="timeTip"></p>
											</div></div></div>';
										}else{
											if(checkSeeLog($user_info->ID,get_the_ID(),$erphp_reg_times,erphpGetIP(),0)){
												echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费播放此音频<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-vip="free" data-post="'.get_the_ID().'" data-token="'.md5($erphpdown_downkey.get_the_ID().$erphpdown_downkey.'free').'">立即播放</a>（今日已播放'.getSeeCount($user_info->ID,0).'个，还可播放'.($erphp_reg_times-getSeeCount($user_info->ID,0)).'个）<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">升级VIP 无限播放免费音频</a></div></div>';
											}else{
												echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权播放此音频，请明天再来！（今日已播放'.getSeeCount($user_info->ID,0).'个，还可播放'.($erphp_reg_times-getSeeCount($user_info->ID,0)).'个）<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">升级VIP 无限播放免费音频</a></div></div>';
											}
										}
									}else{
										echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
										    <i class="audio-play"></i>
										    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
										        <source src="'.$audio.'" type="audio/mpeg">
										    </audio>
										    <span class="star-time">00:00</span>
										    <div class="time-bar">
										        <span class="progressBar"></span>
										        <i class="move-color"></i>
										        <p class="timetip"></p>
										    </div>
										    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
										    <p class="timeTip"></p><p class="timeTip"></p>
										</div></div></div>';
									}
								}	
							}
							
							if($price || $memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9){
								
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">升级'.$erphp_vip_name.'</a>';
								if($userType){
									$vipText = '';
									if(($memberDown == 13 || $memberDown == 14 || $memberDown == 20) && $userType < 10){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
									}
								}
								if($memberDown==3){
									$audio_content.='（'.$erphp_vip_name.'免费）'.$vipText;
								}elseif ($memberDown==2){
									$audio_content.='（'.$erphp_vip_name.' 5折）'.$vipText;
								}elseif ($memberDown==5){
									$audio_content.='（'.$erphp_vip_name.' 8折）'.$vipText;
								}elseif ($memberDown==23){
									$audio_content.='（'.$erphp_vip_name.' 5折、'.$erphp_year_name.'免费）'.$vipText;
								}elseif ($memberDown==24){
									$audio_content.='（'.$erphp_vip_name.' 8折、'.$erphp_year_name.'免费）'.$vipText;
								}elseif ($memberDown==13){
									$audio_content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）'.$vipText;
								}elseif ($memberDown==14){
									$audio_content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）'.$vipText;
								}elseif ($memberDown==20){
									$audio_content.='（'.$erphp_vip_discounts.'、'.$erphp_life_name.'免费）'.$vipText;
								}elseif ($memberDown==21){
									$audio_content.='（'.$erphp_vip_discounts.'）'.$vipText;
								}elseif ($memberDown==16){
									if($userType < 8){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_quarter_name).'</a>';
									}
									$audio_content.='（'.$erphp_quarter_name.'免费）'.$vipText;
								}elseif ($memberDown==6){
									if($userType < 9){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_year_name).'</a>';
									}
									$audio_content.='（'.$erphp_year_name.'免费）'.$vipText;
								}elseif ($memberDown==7){
									if($userType < 10){
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
									}
									$audio_content.='（'.$erphp_life_name.'免费）'.$vipText;
								}elseif ($memberDown==4){
									if($userType){
										
									}
								}
								

								if($memberDown==4){
									$audio_content.='此音频仅限'.$erphp_vip_name.'播放<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
								}elseif($memberDown==15){
									$audio_content.='此音频仅限'.$erphp_quarter_name.'播放<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_quarter_name).'</a>';
								}elseif($memberDown==8){
									$audio_content.='此音频仅限'.$erphp_year_name.'播放<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_year_name).'</a>';
								}elseif($memberDown==9){
									$audio_content.='此音频仅限'.$erphp_life_name.'播放<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
								}else{
									if($memberDown==10){
										if($userType){
											$audio_content.='（仅限'.$erphp_vip_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
										}else{
											$audio_content.='（仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
										}
									}elseif($memberDown==17){
										if($userType >= 8){
											$audio_content.='（仅限'.$erphp_quarter_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
										}else{
											$audio_content.='（仅限'.$erphp_quarter_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_quarter_name).'</a>';
										}
									}elseif($memberDown==18){
										if($userType >= 9){
											$audio_content.='（仅限'.$erphp_year_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
										}else{
											$audio_content.='（仅限'.$erphp_year_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_year_name).'</a>';
										}
									}elseif($memberDown==19){
										if($userType == 10){
											$audio_content.='（仅限'.$erphp_life_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
										}else{
											$audio_content.='（仅限'.$erphp_life_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_life_name).'</a>';
										}
									}elseif($memberDown==11){
										if($userType){
											$audio_content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）';
											$audio_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
										}else{
											$audio_content.='（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
										}
									}elseif($memberDown==12){
										if($userType){
											$audio_content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）';
											$audio_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
										}else{
											$audio_content.='（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip erphpdown-vip-loader" target="_blank">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>';
										}
									}else{
										$audio_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
									}
								}
								echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$audio_content.'</div></div>';
							}
						}
					}elseif($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || (!$price && $memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9 && get_option('erphp_wppay_down') && !get_option('erphp_free_login'))){
						echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
							    <i class="audio-play"></i>
							    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
							        <source src="'.$audio.'" type="audio/mpeg">
							    </audio>
							    <span class="star-time">00:00</span>
							    <div class="time-bar">
							        <span class="progressBar"></span>
							        <i class="move-color"></i>
							        <p class="timetip"></p>
							    </div>
							    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
							    <p class="timeTip"></p><p class="timeTip"></p>
							</div></div></div>';
					}else{
						$audio_content = '';
						if($memberDown == 4){
							$audio_content.='此音频仅限'.$erphp_vip_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 15){
							$audio_content.='此音频仅限'.$erphp_quarter_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 8){
							$audio_content.='此音频仅限'.$erphp_year_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 9){
							$audio_content.='此音频仅限'.$erphp_life_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 10){
							$audio_content.='此音频仅限'.$erphp_vip_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 17){
							$audio_content.='此音频仅限'.$erphp_quarter_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 18){
							$audio_content.='此音频仅限'.$erphp_year_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 19){
							$audio_content.='此音频仅限'.$erphp_life_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 11){
							$audio_content.='此音频仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
						}elseif($memberDown == 12){
							$audio_content.='此音频仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
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
									$audio_content.=$audio_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');

									$audio_content .= $vip_content?('（'.$vip_content.'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphpdown-vip erphpdown-vip-loader">'.sprintf( __('升级%s','mobantu'), $erphp_vip_name).'</a>）'):'';

									$audio_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.__('立即购买','mobantu').'</a>';
								}else{
									//if(!get_option('erphp_free_login')){}else{
										$audio_content.=__('此音频仅限注册用户播放，请先','mobantu').'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
									//}
								}
								
							}else{
								if($price){
									$audio_content.=$audio_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').($vip_content?('（'.$vip_content.'）'):'').__('，请先','mobantu').'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}else{
									$audio_content.=__('此音频仅限注册用户播放，请先','mobantu').'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">'.__('登录','mobantu').'</a>';
								}
							}
						}

						echo '<div class="article-audio">'.$audio_prev_html.$audio_preview_html.'<div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$audio_content.'</div></div>';
					}
				}
			}else{
            	echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
				    <i class="audio-play"></i>
				    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
				        <source src="'.$audio.'" type="audio/mpeg">
				    </audio>
				    <span class="star-time">00:00</span>
				    <div class="time-bar">
				        <span class="progressBar"></span>
				        <i class="move-color"></i>
				        <p class="timetip"></p>
				    </div>
				    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
				    <p class="timeTip"></p><p class="timeTip"></p>
				</div></div></div>';
			}
		}
	}else{
		echo '<div class="article-audio">'.$audio_prev_html.'<div class="audio-player"><div class="audio">
		    <i class="audio-play"></i>
		    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
		        <source src="'.$audio.'" type="audio/mpeg">
		    </audio>
		    <span class="star-time">00:00</span>
		    <div class="time-bar">
		        <span class="progressBar"></span>
		        <i class="move-color"></i>
		        <p class="timetip"></p>
		    </div>
		    <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
		    <p class="timeTip"></p><p class="timeTip"></p>
		</div></div></div>';
	}
}elseif($audio_preview){
	echo '<div class="article-audio"><center><div class="article-audio-free">
		    <i class="guxz"></i>
		    <i class="dy"></i>
		    <i class="xy"></i>
		    <i class="gp audio-stick"></i>
		</div></center><div class="audio-player"><div class="audio">
		    <i class="audio-play"></i>
		    <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_preview_time?$audio_preview_time:'0').'">
		        <source src="'.$audio_preview.'" type="audio/mpeg">
		    </audio>
		    <span class="star-time">00:00</span>
		    <div class="time-bar">
		        <span class="progressBar"></span>
		        <i class="move-color"></i>
		        <p class="timetip"></p>
		    </div>
		    <span class="end-time">'.mbt_sec_to_time($audio_preview_time).'</span>
		    <p class="timeTip"></p><p class="timeTip"></p>
		</div></div></div>';
}