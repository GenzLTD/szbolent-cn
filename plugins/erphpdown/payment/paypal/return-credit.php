<?php
session_start();
require_once('../../../../../wp-config.php');

if(isset($_GET['token']) && $_GET['token'] && get_option('ice_payapl_api_uid')){
	if(get_option('ice_payapl_rest_sandbox')){
		$url = "https://api.sandbox.paypal.com/v2/checkout/orders/".$_GET['token']."/capture";
	}else{
		$url = "https://api.paypal.com/v2/checkout/orders/".$_GET['token']."/capture";
	}
	
	$Token = $_SESSION['paypal_rest_api_token'];
	$ch = curl_init ();
	  curl_setopt ( $ch, CURLOPT_URL, $url );
	  curl_setopt ( $ch, CURLOPT_HEADER, false );
	  curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
	  curl_setopt ( $ch, CURLOPT_POST, true );
	  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Authorization: Bearer ' . $Token,
	    'Content-Type: application/json'
	));
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, '{}' );
	$result = curl_exec($ch);
	curl_close($ch);
	//echo $result;exit;

	$resArray = json_decode($result, true);
	if(is_array($resArray) && isset($resArray['status']) && $resArray['status'] == 'COMPLETED'){
		$paypal_order_id = $resArray['id'];
		$paypal_user_email = $resArray['payer']['email_address'];
		$trade_order_id = $resArray['purchase_units'][0]['payments']['captures'][0]['custom_id']; //$_SESSION['paypal_rest_api_order']
		if(strstr($trade_order_id,'MD') || strstr($trade_order_id,'FK')){
			epd_set_wppay_success($trade_order_id,$total_fee,'paypal',$paypal_user_email.'|'.$paypal_order_id);
		}else{
			epd_set_order_success($trade_order_id,$total_fee,'paypal',$paypal_user_email.'|'.$paypal_order_id);
		}
		unset($_SESSION['paypal_rest_api_token']);
		echo json_encode(array("status"=>200));
		exit;
	}else{
		echo 'error';
	}
}