<?php
function create_catvipguid($namespace = '') {
    static $guid = '';
    $uid = uniqid("", true);
    $data = $namespace;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    //$data .= $_SERVER['LOCAL_ADDR'];
    //$data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    $guid = substr($hash, 0, 4).'-'.substr($hash, 8, 4).'-'.substr($hash, 12, 4).'-'.substr($hash, 16, 4).'-'.substr($hash, 20, 4);
    return $guid;
}


function erphpdown_catvipcard_install(){
	return true;
}

function isErphpCatVipCardUsed($id){
	global $wpdb;
	$result = $wpdb->get_row("select * from $wpdb->erphpcatvipcard where id = '".$id."'");
	if(!$result->status) return '否';
	else return '是 [使用者：'.($result->uid?get_the_author_meta( 'user_login', $result->uid ):'游客').'，时间：'.$result->usetime.']';
}

function checkDoCatVipCardResult($card){
	date_default_timezone_set('Asia/Shanghai');
	if(is_user_logged_in()){
		global $wpdb, $current_user;

		$result = $wpdb->get_row( $wpdb->prepare(
                "SELECT * FROM $wpdb->erphpcatvipcard WHERE card = %s",
                esc_sql($card) ) );
		if($result->status == '0'){
			$user_type = $result->usertype;
			$cid = $result->cid;
			$endtime = $result->endtime;
			if(time() > strtotime($endtime) && $endtime != '0000-00-00 00:00:00'){
				return '2';//过期
			}else{
				$ss = $wpdb->query("update $wpdb->erphpcatvipcard set status=1,uid='".$current_user->ID."',usetime='".date("Y-m-d H:i:s")."' where card='".esc_sql($card)."'");
				if($ss){
					addUserMoney($current_user->ID,'0');
					if(userSetCatSetData($user_type,$cid,$current_user->ID) ){
						addVipCatLogByAdmin('0', $user_type,$cid, $current_user->ID);

						if(function_exists('_mbt_add_notice')){
							_mbt_add_notice($current_user->ID, '您好，您已成功升级成为分类VIP。', 'vip', $user_type);
						}

						if(get_option('erphp_addon_catvipcard_aff')){
							$priceArr=array('7'=>'cat_vip_month','8'=>'cat_vip_quarter','9'=>'cat_vip_year','10'=>'cat_vip_life');
							$priceType=$priceArr[$user_type];
							$price=get_term_meta($cid,$priceType,true);
							if(!get_option('erphp_vip_ref_no')){
								$EPD = new EPD();
								$EPD->doAff($price, $current_user->ID);
							}
						}
						return '1'; //成功
					}
				}else{
					return '4'; //系统错误
				}
			}
			
		}elseif($result->status == '1'){
			return '0';  //已被使用过
		}else{
			return '3'; //不存在
		}
	}else{
		return '4';
	}
}

function getCatVipCardTypeLeft($type){
	global $wpdb;
	$result = $wpdb->get_var("select count(id) from $wpdb->erphpcatvipcard where status = 0 and usertype = '".$type."'");
	return $result;
}