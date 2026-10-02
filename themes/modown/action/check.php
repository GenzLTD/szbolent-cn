<?php 
require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
if($_POST['action']){ 
	$action = $_POST['action'];
	$error = "1";
	if($action == 'name'){
		$sanitized_user_login = sanitize_user( $_POST['user_register'] );
		if(strlen($sanitized_user_login) < 6){
			$error = __('用户名至少6位字符','mobantu');
		}elseif ( $sanitized_user_login == '' ) {
			$error = __('请输入用户名','mobantu');
		  } elseif ( ! validate_username( $sanitized_user_login ) ) {
			$error = __('此用户名包含无效字符，请输入有效的用户名','mobantu');
			$sanitized_user_login = '';
		  } elseif ( username_exists( $sanitized_user_login ) ) {
			$error = __('此用户名已被注册，请换一个','mobantu');
		  }
	  
	}elseif($action == 'email'){
		$user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

		if ( $user_email == '' ) {
			$error = __('请输入邮箱地址','mobantu');
		  } elseif ( ! is_email( $user_email ) ) {
			$error = __('邮箱格式不正确','mobantu');
			$user_email = '';
		  } elseif ( email_exists( $user_email ) ) {
			$error = __('此邮箱已被注册，请换一个','mobantu');
		  }
	}

	echo $error;
}
