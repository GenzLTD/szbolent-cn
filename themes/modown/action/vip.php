<?php 
if (!session_id()) session_start();
require( dirname(__FILE__) . '/../../../../wp-load.php' );
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $current_user;
if($_POST['action']=='user.vip' && get_option('erphp_wppay_vip')){
	$error = 0;$msg = '';$link = get_permalink(MBThemes_page("template/user.php"));$payment = '';
	$userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;

	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
	
	$oldUserType = getUsreMemberTypeById($current_user->ID);
	if($oldUserType == '10' && !get_option('erphp_life_days')){
		$error = 1;$msg = sprintf( __('您已经是%s，请勿重复升级','mobantu'), $erphp_life_name);
	}else{
		if($userType >5 && $userType < 11){

			$priceArr=array('6'=>'erphp_day_price','7'=>'erphp_month_price','8'=>'erphp_quarter_price','9'=>'erphp_year_price','10'=>'erphp_life_price');
			$priceType=$priceArr[$userType];
			$price=get_option($priceType);

			$vip_update_pay = get_option('vip_update_pay');
			if($vip_update_pay){
				$erphp_quarter_price = get_option('erphp_quarter_price');
				$erphp_month_price  = get_option('erphp_month_price');
				$erphp_day_price  = get_option('erphp_day_price');
				$erphp_year_price    = get_option('erphp_year_price');
				$erphp_life_price  = get_option('erphp_life_price');

				if($userType == 7){
					if($oldUserType == 6){
                 		$price = $erphp_month_price - $erphp_day_price;
                 	}
				}elseif($userType == 8){
					if($oldUserType == 6){
                 		$price = $erphp_quarter_price - $erphp_day_price;
                 	}elseif($oldUserType == 7){
                 		$price = $erphp_quarter_price - $erphp_month_price;
                 	}
				}elseif($userType == 9){
					if($oldUserType == 6){
                 		$price = $erphp_year_price - $erphp_day_price;
                 	}elseif($oldUserType == 7){
                 		$price = $erphp_year_price - $erphp_month_price;
                 	}elseif($oldUserType == 8){
                 		$price = $erphp_year_price - $erphp_quarter_price;
                 	}
				}elseif($userType == 10){
					if($oldUserType == 6){
                 		$price = $erphp_life_price - $erphp_day_price;
                 	}elseif($oldUserType == 7){
                 		$price = $erphp_life_price - $erphp_month_price;
                 	}elseif($oldUserType == 8){
                 		$price = $erphp_life_price - $erphp_quarter_price;
                 	}elseif($oldUserType == 9){
                 		$price = $erphp_life_price - $erphp_year_price;
                 	}
				}
			}

			
			if($price <= 0){
				$error = 1;$msg = '会员价格错误';
			}else{
				$userTypeName = $erphp_vip_name;
				if($userType == 6){
					$userTypeName = $erphp_day_name;
				}elseif($userType == 7){
					$userTypeName = $erphp_month_name;
				}elseif($userType == 8){
					$userTypeName = $erphp_quarter_name;
				}elseif($userType == 9){
					$userTypeName = $erphp_year_name;
				}elseif($userType == 10){
					$userTypeName = $erphp_life_name;
				}
					
				$error = 3;$msg = '请选择支付方式';
				//if(_MBT('vip_just_pay') || _MBT('vip_only_pay')){
					$payment .= '<div class="erphpdown-type-desc"><div class="type-name">'.$userTypeName.'</div><div class="type-price"><span>'.$price.'</span>'.get_option('ice_name_alipay').'</div></div>';
					$payment .= '<div class="erphpdown-type-payment">';
					if(get_option('ice_weixin_mchid')){
						if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
							$payment .= '<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_type%3D'.$userType.'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}else{
							$payment .= '<a href="'.constant("erphpdown").'payment/weixin.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}
					}
					if(get_option('ice_ali_partner') || get_option('ice_ali_app_id')){
						$payment .= '<a href="'.constant("erphpdown").'payment/alipay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
					}
					if(get_option('erphpdown_f2fpay_id') && !get_option('erphpdown_f2fpay_alipay')){
						$payment .= '<a href="'.constant("erphpdown").'payment/f2fpay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
					}
					if(get_option('erphpdown_payjs_appid')){
						if(!get_option('erphpdown_payjs_wxpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/payjs.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}
						if(!get_option('erphpdown_payjs_alipay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/payjs.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
						}
					}
					if(get_option('erphpdown_xhpay_appid32')){
						$payment .= '<a href="'.constant("erphpdown").'payment/xhpay3.php?ice_type='.$userType.'&type=1" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
					}
					if(get_option('erphpdown_xhpay_appid31')){
						$payment .= '<a href="'.constant("erphpdown").'payment/xhpay3.php?ice_type='.$userType.'&type=2" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
					}
					if(get_option('erphpdown_codepay_appid')){
						if(!get_option('erphpdown_codepay_alipay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=1" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
						}
						if(!get_option('erphpdown_codepay_wxpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=3" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}
						if(!get_option('erphpdown_codepay_qqpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/codepay.php?ice_type='.$userType.'&type=2" class="erphpdown-type-link erphpdown-type-qqpay" target="_blank"><i class="icon icon-qq"></i> '.__('QQ钱包','mobantu').'</a>';
						}
					}
					if(get_option('erphpdown_paypy_key')){
						if(!get_option('erphpdown_paypy_wxpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/paypy.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}
						if(!get_option('erphpdown_paypy_alipay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/paypy.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
						}
					}
					if(get_option('erphpdown_epay_id')){
						if(!get_option('erphpdown_epay_wxpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/epay.php?ice_type='.$userType.'&type=wxpay" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}
						if(!get_option('erphpdown_epay_alipay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/epay.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
						}
						if(!get_option('erphpdown_epay_qqpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/epay.php?ice_type='.$userType.'&type=qqpay" class="erphpdown-type-link erphpdown-type-qqpay" target="_blank"><i class="icon icon-qq"></i> '.__('QQ钱包','mobantu').'</a>';
						}
					}
					if(get_option('erphpdown_easepay_id')){
						if(!get_option('erphpdown_easepay_wxpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/easepay.php?ice_type='.$userType.'&type=wxpay" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}
						if(!get_option('erphpdown_easepay_alipay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/easepay.php?ice_type='.$userType.'&type=alipay" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
						}
						if(!get_option('erphpdown_easepay_usdt')){
							$payment .= '<a href="'.constant("erphpdown").'payment/easepay.php?ice_type='.$userType.'&type=usdt" class="erphpdown-type-link erphpdown-type-ut" target="_blank"><i class="icon icon-usdt"></i> '.__('USDT','mobantu').'</a>';
						}
					}
					if(get_option('erphpdown_vpay_key')){
						if(!get_option('erphpdown_vpay_wxpay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/vpay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-wxpay" target="_blank"><i class="icon icon-wxpay-color"></i> '.__('微信支付','mobantu').'</a>';
						}
						if(!get_option('erphpdown_vpay_alipay')){
							$payment .= '<a href="'.constant("erphpdown").'payment/vpay.php?ice_type='.$userType.'&type=2" class="erphpdown-type-link erphpdown-type-alipay" target="_blank"><i class="icon icon-alipay-color"></i> '.__('支付宝','mobantu').'</a>';
						}
					}
					if(get_option('erphpdown_stripe_pk')){
						$payment .= '<a href="'.home_url('?epd_v64='.base64_encode('stripe-'.$userType.'-'.time())).'" class="erphpdown-type-link erphpdown-type-stripe" target="_blank"><i class="icon icon-credit-card"></i> '.__('信用卡','mobantu').'</a>';
					}
					if(get_option('ice_payapl_api_uid')){
						$payment .= '<a href="'.constant("erphpdown").'payment/paypal.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-paypal" target="_blank"><i class="icon icon-paypal"></i> Paypal</a>';
					}	
					if(function_exists('plugin_check_ecpay') && plugin_check_ecpay() && get_option('erphpdown_ecpay_MerchantID')){
						$payment .= '<a href="'.ERPHPDOWN_ECPAY_URL.'/ecpay.php?ice_type='.$userType.'" class="erphpdown-type-link erphpdown-type-ecpay" target="_blank"><i class="icon icon-ecpay"></i> '.__('新台币','mobantu').'</a>';
					}
					if(get_option('erphpdown_usdt_address')){
						$payment .= '<a href="'.home_url('?epd_v64='.base64_encode('usdt-'.$userType.'-'.time())).'" class="erphpdown-type-link erphpdown-type-ut" target="_blank"><i class="icon icon-usdt"></i> USDT</a>';
					}
					$payment .= '</div>';
				//}
				
				if(function_exists('erphpdown_vipcard_install')){
					$payment .= '<a href="javascript:;" class="erphpdown-type-link erphpdown-type-card" data-type="'.$userType.'"><i class="icon icon-wallet"></i> '.__('激活码升级','mobantu').'</a>';
				}

				if(get_option('erphp_promo')){
					$payment .= '<div class="erphpdown-promo">'.__('优惠码：','mobantu').'<input type="text" id="erphp_promo" /> <a href="javascript:;" class="erphp-promo-do erphp-promo-do-vip">'.__('使用','mobantu').'</a></div>';
				}

				$payment .= '<div class="erphpdown-vip-login">'.__('未登录说明：VIP状态以您的IP为准，请勿改变IP','mobantu').'</div>';
			}
			
		}else{
			$error = 1;$msg = __('升级失败','mobantu');
		}
	}
	

	$arr=array(
		"error"=>$error, 
		"msg"=>$msg,
		"link"=>$link,
		"payment"=>$payment
	); 
	
	$jarr=json_encode($arr); 
	echo $jarr; 
}elseif($_POST['action'] == 'user.vip.card' && get_option('erphp_wppay_vip')){
	$error = 0;$msg = '';
	$num = esc_sql(trim($_POST['num']));
	$result = checkDoVipCardResult($num);
	if($result == '2'){
		$error = 1;
		$msg = __('激活码已过期','mobantu');
	}elseif($result == '0'){
		$error = 1;
		$msg = __('激活码已被使用，请换一个试试','mobantu');
	}elseif($result == '3'){
		$error = 1;
		$msg = __('激活码不存在','mobantu');
	}elseif($result == '1'){
		
	}else{
		$error = 1;
		$msg = __("系统超时，请稍后重试",'mobantu');
	}
	$arr=array(
		"error"=>$error, 
		"msg"=>$msg
	);

	$jarr=json_encode($arr); 
	echo $jarr; 
}
exit;