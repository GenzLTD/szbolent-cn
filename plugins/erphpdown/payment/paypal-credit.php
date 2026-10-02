<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------
session_start();
if(isset($_GET['redirect_url'])){
    $_COOKIE['erphpdown_return'] = urldecode($_GET['redirect_url']);
    setcookie('erphpdown_return',urldecode($_GET['redirect_url']),0,'/');
}else{
    $_COOKIE['erphpdown_return'] = '';
    setcookie('erphpdown_return','',0,'/');
}
header("Content-type:text/html;character=utf-8");
require_once('../../../../wp-load.php');
date_default_timezone_set('Asia/Shanghai');

$ice_payapl_rest_api = get_option('ice_payapl_rest_api');
if($ice_payapl_rest_api){
	$epd_order = _epd_create_page_order('paypal');
	$price = $epd_order['price'];
	$trade_order_id = $epd_order['trade_order_id'];

	$ice_payapl_api_rmb = get_option('ice_payapl_api_rmb')?get_option('ice_payapl_api_rmb'):1;//汇率
	$price = $price / $ice_payapl_api_rmb;
	$price = sprintf("%.2f",$price);

	$ice_payapl_api_uid    = get_option('ice_payapl_api_uid');
 	$ice_payapl_api_pwd    = get_option('ice_payapl_api_pwd');

 	if(get_option('ice_payapl_rest_sandbox')){
 		$url = "https://api.sandbox.paypal.com/v1/oauth2/token";
 	}else{
 		$url = "https://api.paypal.com/v1/oauth2/token";
 	}
 	
	$clientId = $ice_payapl_api_uid;
	$clientSecret = $ice_payapl_api_pwd;
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_HEADER, false );
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, array(
	  "Content-Type: application/json",
	  "Accept-Language: en_US"
	));
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt ( $ch, CURLOPT_POST, true );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials" );
	$result = curl_exec($ch);
	curl_close($ch);
	//echo $result;exit;

	$resArray = json_decode($result, true);
	if(is_array($resArray) && isset($resArray['access_token']) && $resArray['access_token']){

		$_SESSION['paypal_rest_api_token'] = $resArray['access_token'];
		//$_SESSION['paypal_rest_api_order'] = $trade_order_id;
?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
		    <meta charset="UTF-8">
		    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		    <title>信用卡支付</title>
		    <link rel='stylesheet'  href='../static/erphpdown.css' type='text/css' media='all' />
		    <style>
		    	#paypal-button-container{margin: 20px auto;text-align: center;}
		    	#paypal-button-container iframe{position: static !important;width: auto !important;}
		    </style>
		    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $clientId;?>&currency=USD&enable-funding=card"></script>
		</head>
		<body>

			<div class="wppay-custom-modal-box mobantu-wppay erphpdown-custom-modal-box">
				<section class="wppay-modal">
		                    
		            <section class="erphp-wppay-qrcode mobantu-wppay">
		                <section class="tab">
		                    <a href="javascript:;" class="active"><div class="payment"><img src="<?php echo constant("erphpdown");?>static/images/payment-unionpay.jpg"></div>$<?php echo sprintf("%.2f",$price);?></a>
		                    <div class="warning">点击下方黑色按钮完成支付</div>
		                </section>
		                <div id="paypal-button-container"></div>
		            </section>
		        
		    	</section>
		    </div>
		    
		    <script>
		        paypal.Buttons({
		        	fundingSource: paypal.FUNDING.CARD,
		        	expandCardForm: true,
		            createOrder: function(data, actions) {
		                return actions.order.create({
		                    purchase_units: [{
		                        amount: {
		                            value: '<?php echo $price;?>'
		                        },
		                        custom_id: '<?php echo $trade_order_id;?>'
		                    }],
		                    application_context: {
		                        shipping_preference: 'NO_SHIPPING'
		                    }
		                });
		            },
		            onApprove: function(data, actions) {
		                
	                    return fetch('<?php echo constant("erphpdown").'payment/paypal/return-credit.php';?>?token='+data.orderID, {
	                        method: 'get',
	                        headers: {
	                            'content-type': 'application/json'
	                        }
	                    }).then(function(info) {
	                        if(info.status == 200){
	                        	<?php if(isset($_COOKIE['erphpdown_return']) && $_COOKIE['erphpdown_return']){?>
	                            location.href="<?php echo $_COOKIE['erphpdown_return'];?>";
	    	                    <?php }elseif(get_option('erphp_url_front_success')){?>
	    	                    location.href="<?php echo str_replace('#domain#', $_SERVER['HTTP_HOST'], get_option('erphp_url_front_success'));?>";
	    	                    <?php }else{?>
	    	                    window.close();
	    	                	<?php }?>
	                        }
	                    });
			            
		            }
		        }).render('#paypal-button-container');
		    </script>
		</body>
		</html>
<?php
	}

}