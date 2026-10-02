<?php
if (!session_id()) session_start();
require_once(dirname(__FILE__)."/../../../../wp-load.php");
if($_POST['action'] == 'mobantu_captcha' && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	
	$user_email = apply_filters( 'user_registration_email', $_POST['email'] );
	$user_email = esc_sql(trim($user_email));

	if(_MBT('register_email_suffix2') && is_email( $user_email )){
	  	$email_suffixs2=explode("\r\n",trim(strtolower(_MBT('register_email_suffix2'))));
	  	$email_domain = explode('@', strtolower($user_email));
	  	if( !in_array($email_domain[1], $email_suffixs2) ){
	  		echo "3";
	  		exit;
	  	}
	}

	if(_MBT('register_email_suffix') && is_email( $user_email )){
	  	$email_suffixs=explode("\r\n",trim(strtolower(_MBT('register_email_suffix'))));
	  	$email_domain = explode('@', strtolower($user_email));
	  	if( in_array($email_domain[1], $email_suffixs) ){
	  		echo "3";
	  		exit;
	  	}
	}
	
	if ( email_exists( $user_email ) ){
		echo "2";
	}else{
		sessioncode(strtolower($user_email));
		echo "1";
	}
}
exit;

function sessioncode($email){
	$originalcode = '0,1,2,3,4,5,6,7,8,9';
	$originalcode = explode(',',$originalcode);
	$countdistrub = 10;
	$_dscode = "";
	$counts=6;
	for($j=0;$j<$counts;$j++){
		$dscode = $originalcode[rand(0,$countdistrub-1)];
		$_dscode.=$dscode;
	}
	
	$_SESSION['MBT_modown_captcha']=strtolower($_dscode);
	$_SESSION['MBT_modown_captcha_email']=$email;
	$message = __('验证码：','mobantu').$_dscode;   
	wp_mail($email, '['.get_bloginfo('name').']'.__('验证码','mobantu'), $message);    
}