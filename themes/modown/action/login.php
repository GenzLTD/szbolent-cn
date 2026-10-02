<?php
// CORS：当从 127.0.0.1 / localhost / genz.ltd 跨源 XHR 时允许访问（站点 siteurl 与页面访问域名不一致时避免同源策略拦截）
if (isset($_SERVER['HTTP_ORIGIN'])) {
    $o = $_SERVER['HTTP_ORIGIN'];
    if (preg_match('#^https?://(127\.0\.0\.1|localhost|genz\.ltd)(:\d+)?$#', $o)) {
        header('Access-Control-Allow-Origin: ' . $o);
    }
}
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

if (!session_id()) session_start();
require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
require THEME_DIR.'/inc/sms/index.php';
use Qcloud\Sms\SmsSingleSender;
date_default_timezone_set('Asia/Shanghai');
if(isset($_POST['action'])){ 
	$action = $_POST['action'];
	if($action == 'mobantu_login'){
		$username = esc_sql($_POST['log']);   
    	$password = $_POST['pwd']; 


    	if(_MBT('captcha_login') == 'slide' && !modown_is_mobile()){
			if(empty($_POST['cpt']) || empty($_SESSION['MBT_modown_captcha']) || trim(strtolower($_POST['cpt'])) != $_SESSION['MBT_modown_captcha']){
				unset($_SESSION['MBT_modown_captcha']);
			  	echo __('请拖动滑块到最右边','mobantu');
			  	exit;
			}
		}elseif(_MBT('captcha_login')){
    		if(empty($_POST['cpt']) || empty($_SESSION['MBT_modown_captcha']) || trim(strtolower($_POST['cpt'])) != $_SESSION['MBT_modown_captcha']){
    			unset($_SESSION['MBT_modown_captcha']);
			  	echo __('验证码错误，请重新刷新验证码','mobantu');
			  	exit;
			}
    	}  

    	if(is_email(strtolower($username))) {
    		$username = strtolower($username);
			$user_data = get_user_by('email',$username);
			if(!$user_data){
				echo __('未知邮箱，请检查或使用用户名','mobantu');  
				exit;  
			}else{
				$username = $user_data->user_login;
			}
		}

		$login_data = array();   
		$login_data['user_login'] = $username;   
		$login_data['user_password'] = $password;   
		$login_data['remember'] = true; 
		$user_verify = wp_signon( $login_data ,is_ssl()); 
		if(_MBT('captcha_login')){
			unset($_SESSION['MBT_modown_captcha']);   
		} 
		if ( is_wp_error($user_verify) ) {   
			echo $user_verify->get_error_message();    
		} else { 
			_mbt_add_activity($user_verify->ID,'login');
			echo "1";
		}  
	}elseif($action == 'mobantu_register' && !_MBT('register') && !_MBT('register_social_only')){
		$sanitized_user_login = sanitize_user( $_POST['user_register'] );
    	$user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );
    	$user_id = 0;
    	$error = '';

    	$user_email = strtolower($user_email);
		
		if ( $sanitized_user_login == '' ) {
			$error = __('请输入用户名','mobantu');
		} elseif ( ! validate_username( $sanitized_user_login ) ) {
			$error = __('此用户名包含无效字符，请输入有效的用户名','mobantu');
			$sanitized_user_login = '';
		} elseif ( username_exists( $sanitized_user_login ) ) {
			$error = __('此用户名已被注册，请换一个','mobantu');
		}

		if(_MBT('register_email_suffix') && is_email( $user_email )){
		  	$email_suffixs=explode("\r\n",trim(strtolower(_MBT('register_email_suffix'))));
		  	$email_domain = explode('@', strtolower($user_email));
		  	if( in_array($email_domain[1], $email_suffixs) ){
		  		$error = __('暂不支持此域名邮箱后缀','mobantu');
		  	}
		}

		if(_MBT('register_email_suffix2') && is_email( $user_email )){
		  	$email_suffixs2=explode("\r\n",trim(strtolower(_MBT('register_email_suffix2'))));
		  	$email_domain = explode('@', strtolower($user_email));
		  	if( !in_array($email_domain[1], $email_suffixs2) ){
		  		$error = __('暂不支持此域名邮箱后缀','mobantu');
		  	}
		}
		
		if ( $user_email == '' ) {
			$error = __('请输入邮箱地址','mobantu');
		} elseif ( ! is_email( $user_email ) ) {
			$error = __('邮箱格式不正确','mobantu');
			$user_email = '';
		} elseif ( email_exists( $user_email ) ) {
			$error = __('此邮箱已被注册，请换一个','mobantu');
		}
		  
		if($_POST['password'] == '') $error = __('请输入密码','mobantu');
		elseif(strlen($_POST['password']) < 6) $error = __('密码长度不得小于6位','mobantu');

		if(_MBT('captcha') == 'email' || _MBT('captcha') == 'image' || (_MBT('captcha') == 'slide' && modown_is_mobile())){
			if(empty($_POST['captcha']) || empty($_SESSION['MBT_modown_captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['MBT_modown_captcha']){
				unset($_SESSION['MBT_modown_captcha']);
			  	$error = __('验证码错误，请重新刷新验证码','mobantu');
			}
		}

		if(_MBT('captcha') == 'email'){
			if($_SESSION['MBT_modown_captcha_email'] != $user_email){
			  	$error = __('验证码与邮箱不对应','mobantu');
			}
		}

		if(_MBT('captcha') == 'slide' && !modown_is_mobile()){
			if(empty($_POST['captcha']) || empty($_SESSION['MBT_modown_captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['MBT_modown_captcha']){
				unset($_SESSION['MBT_modown_captcha']);
			  	$error = __('请拖动滑块到最右边','mobantu');
			}
		}

		if(_MBT('captcha') == 'invitation' && function_exists('ashuwp_check_invitation_code')){
	  		if(empty($_POST['captcha'])){
	  			$error = __('请填写邀请码','mobantu');
	  		}

	  		$invitation_status = ashuwp_check_invitation_code(esc_sql($_POST['captcha']));

			if(!$invitation_status){
			    $error = __('无效的邀请码','mobantu');
			}elseif($invitation_status=='disabled'){
			    $error = __('无效的邀请码','mobantu');
			}elseif($invitation_status=='finish'){
			    $error = __('邀请码已用完','mobantu');
			}elseif($invitation_status=='expired'){
			    $error = __('邀请码已过期','mobantu');
			}
		}
		  
		if($error){ 
			echo $error;
		}else{
		  	if(_MBT('captcha') == 'email' || _MBT('captcha') == 'image'){
			  	unset($_SESSION['MBT_modown_captcha']);
				unset($_SESSION['MBT_modown_captcha_email']);
			}

		  	$new_password = $_POST['password'];
		  	$userdata=array(
			  'ID' => '',
			  'user_login' => $sanitized_user_login,
			  'user_pass' => $new_password,
			  'user_email' => $user_email
			);
			$user_id = wp_insert_user( $userdata );
			if ( is_wp_error( $user_id ) ) {
				echo __("系统超时，请稍后重试",'mobantu');
			}else{
				if($user_id){
					_mbt_add_activity($user_id,'register');
					wp_set_auth_cookie($user_id,true,is_ssl());
					wp_signon( array(), is_ssl() );

					if(_MBT('captcha') == 'invitation' && function_exists('ashuwp_check_invitation_code')){
						$result = ashuwp_get_invitation_code_by_code(esc_sql($_POST['captcha']));
						if(!empty($result)){
					      	$code_users = array();
					      	$code_id = $result['id'];
					      	$code_users = explode( ',', $result['users'] );
					      	$code_users[] = $user_id;
					      	$code_users = array_filter($code_users);
					      	$new_users = implode(',',$code_users);
					      
					      	ashuwp_update_invitation_code( $code_id, 'users', $new_users );
					      	ashuwp_update_invitation_status( $code_id );
					      	add_user_meta( $user_id, 'invitation_code', $result['code'], true );
					    }
					}

					//wp_new_user_notification($user_id, null, 'both');
					echo "1";
				}else{
					echo __("系统超时，请稍后重试",'mobantu');
				}
			}
		}
	}elseif($action == 'password'){
		if(empty($_POST['security']) || empty($_SESSION['MBT_modown_security']) || $_POST['security'] != $_SESSION['MBT_modown_security']){
			unset($_SESSION['MBT_modown_security']);
			echo __("非法请求",'mobantu');
		}else{
			$passname = esc_sql($_POST['passname']);
			$user_login = '';
			$user_email = '';
			$error = '';
			if(!is_email($passname)) {
				$user_login = $passname;
				if(!username_exists( $user_login )){
					$error = __("用户名不存在",'mobantu');
				}else{
					$user_data = get_userdatabylogin($passname);
					$user_email = $user_data->user_email;
					if(empty($user_email)){
						$error = __("用户未设定邮箱",'mobantu');
					}
				}
			}else{
				$user_email = $passname;
				if(!email_exists( $user_email )){
					$error = __("邮箱不存在",'mobantu');
				}else{
					$user_data = get_user_by_email($passname);
					$user_login = $user_data->user_login;
				}
			}
			
			
			
			if($error){ echo $error;}
			else{
				$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login)); 
				if(empty($key)) {
					$key = wp_generate_password(64, true); 
					$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login)); 
				}   
				$md5_str = _MBT('login_password_key')?_MBT('login_password_key'):'M2O7B4A8N5T9U0';
				$verify_url = add_query_arg(array("action"=>"reset_password","key"=>md5($md5_str.$key),"login"=>rawurlencode($user_login)),get_permalink(MBThemes_page('template/login.php')));
				
				
				$subject = '[' . get_option('blogname') . ']'.__("重置密码",'mobantu');
				$message = '<table cellpadding="0" cellspacing="0" align="center" style="text-align:left;font-family:Microsoft Yahei,arial;" width="742"><tbody><tr><td><table cellpadding="0" cellspacing="0" style="text-align:left;border:1px solid #000;color:#fff;font-size:18px;" width="740"><tbody><tr height="45" style="background-color:#000;"><td style="padding-left:15px;font-family:Microsoft Yahei,arial;font-size:24px;">'.get_bloginfo("name").' </td></tr></tbody></table><table cellpadding="0" cellspacing="0" style="text-align:left;border:1px solid #f0f0f0;border-top:none;color:#585858;background-color:#fafafa;font-size:14px;" width="740"><tbody><tr height="25"><td></td></tr><tr height="40"><td style="padding-left:25px;padding-right:25px;font-size:18px;font-family:Microsoft Yahei,arial;">Hi, '.$user_login.'： </td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;">'.__("您刚刚发起了重置密码请求，请点击下面的链接重置密码：",'mobantu').'</td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;"><a href="'.$verify_url.'" target="_blank">'.$verify_url.'</a> </td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;">'.__("若无法直接点击打开链接，请手动复制链接然后粘贴到浏览器。",'mobantu').'</td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;">'.__("如果不是您本人操作，请忽略此邮件。",'mobantu').'</td></tr><tr height="20"><td></td></tr><tr><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;"> '.__("此致",'mobantu').'<br>'.get_bloginfo("name").'</td></tr><tr height="50"><td></td></tr></tbody></table><table cellpadding="0" cellspacing="0" style="color:#969696;font-size:12px;vertical-align:middle;text-align:center;" width="740"><tbody><tr height="5"><td></td></tr><tr height="20"><td width="680" style="text-align:left;font-family:Microsoft Yahei,arial"> '.date("Y").' <span>©</span> <a href="'.get_bloginfo("url").'" target="_blank" style="text-decoration:none;color:#969696;padding-left:5px;" title="'.get_bloginfo("name").'">'.get_bloginfo("url").'</a> </td><td width="30" style="text-align:right;font-family:Microsoft Yahei,arial"></td><td width="30" style="text-align:right;font-family:Microsoft Yahei,arial"></td></tr></tbody></table></td></tr></tbody></table>';   
				$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
				
				
				if(  wp_mail($user_email, $subject, $message, $headers)   ){
					echo "1";
				}else{
					echo __("邮件发送失败，请稍后重试",'mobantu');
				}
			}
		}
	}elseif($action == 'reset'){
		if(empty($_POST['security']) || empty($_SESSION['MBT_modown_reset_security']) || $_POST['security'] != $_SESSION['MBT_modown_reset_security']){
			unset($_SESSION['MBT_modown_reset_security']);
			echo __("非法请求",'mobantu');
		}else{
			$reset_key = $_POST['key']; 
			$user_login = esc_sql($_POST['username']); 
			$newpass = $_POST['resetpass']; 
			$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email, user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));   
			$user_login = $user_data->user_login;   
			$user_email = $user_data->user_email;  
			$md5_str = _MBT('login_password_key')?_MBT('login_password_key'):'M2O7B4A8N5T9U0'; 
			if(!empty($reset_key) && !empty($user_data) && md5($md5_str.$user_data->user_activation_key) == $reset_key) {  
				wp_set_password( $newpass, $user_data->ID ); 
				$key = wp_generate_password(64, true); 
				$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login)); 
	 
				$verify_url = get_permalink(MBThemes_page('template/login.php'));   
				
				$subject = '[' . get_option('blogname') . ']'.__("密码修改成功",'mobantu');
				$message = '<table cellpadding="0" cellspacing="0" align="center" style="text-align:left;font-family:Microsoft Yahei,arial;" width="742"><tbody><tr><td><table cellpadding="0" cellspacing="0" style="text-align:left;border:1px solid #000;color:#fff;font-size:18px;" width="740"><tbody><tr height="45" style="background-color:#000;"><td style="padding-left:15px;font-family:Microsoft Yahei,arial;font-size:24px;">'.get_bloginfo("name").' </td></tr></tbody></table><table cellpadding="0" cellspacing="0" style="text-align:left;border:1px solid #f0f0f0;border-top:none;color:#585858;background-color:#fafafa;font-size:14px;" width="740"><tbody><tr height="25"><td></td></tr><tr height="40"><td style="padding-left:25px;padding-right:25px;font-size:18px;font-family:Microsoft Yahei,arial;">Hi, '.$user_login.'： </td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;">'.__("您的密码修改成功，用户名：",'mobantu').$user_login.'&nbsp;&nbsp;&nbsp;&nbsp;新密码：'.$newpass.'</td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;">'.__("请牢记密码，您可以点击下面的链接登录：",'mobantu').'</td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;"><a href="'.$verify_url.'" target="_blank">'.$verify_url.'</a> </td></tr><tr height="15"><td></td></tr><tr height="30"><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;line-height:20px;">'.__("若无法直接点击打开链接，请手动复制链接然后粘贴到浏览器。",'mobantu').'</td></tr><tr height="20"><td></td></tr><tr><td style="padding-left:55px;padding-right:55px;font-family:Microsoft Yahei,arial;font-size:14px;"> '.__("此致",'mobantu').'<br>'.get_bloginfo("name").'</td></tr><tr height="50"><td></td></tr></tbody></table><table cellpadding="0" cellspacing="0" style="color:#969696;font-size:12px;vertical-align:middle;text-align:center;" width="740"><tbody><tr height="5"><td></td></tr><tr height="20"><td width="680" style="text-align:left;font-family:Microsoft Yahei,arial"> '.date("Y").' <span>©</span> <a href="'.get_bloginfo("url").'" target="_blank" style="text-decoration:none;color:#969696;padding-left:5px;" title="'.get_bloginfo("name").'">'.get_bloginfo("url").'</a> </td><td width="30" style="text-align:right;font-family:Microsoft Yahei,arial"></td><td width="30" style="text-align:right;font-family:Microsoft Yahei,arial"></td></tr></tbody></table></td></tr></tbody></table>';   
				$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
				wp_mail($user_email, $subject, $message, $headers);
				
				
				echo "1";
			}else{
				echo __("非法请求",'mobantu');
			}
		}
	}elseif($action == 'mobantu_captcha_sms' && _MBT('oauth_sms')){

		if(empty($_POST['cpt']) || empty($_SESSION['MBT_modown_captcha']) || trim(strtolower($_POST['cpt'])) != $_SESSION['MBT_modown_captcha']){
			unset($_SESSION['MBT_modown_captcha']);
		  	echo __('图形验证码错误','mobantu');
		  	exit;
		}else{
		    unset($_SESSION['MBT_modown_captcha']);
		}
			
		$mobile = esc_sql($_POST['mobile']); 
		if(MBThemes_is_phone($mobile)){
            $code = rand(1000, 9999);    

            if(_MBT('oauth_sms_select') == 'juhe'){
            	$code = rand(100000, 999999);
				$apiUrl = 'http://v.juhe.cn/sms/send?';
				$params = [
				    'tpl_id' => _MBT('oauth_juhe_sms_temp'),
				    'key' => _MBT('oauth_juhe_sms_key'),
				    'mobile' => $mobile,
				    'vars' => '{"code":'.$code.'}'
				    //'tpl_value' => urlencode('#code#='.$code),
				];
				$paramsString = http_build_query($params);

				// 发起接口网络请求
				$response = null;
				try {
				    $response = juheHttpRequest($apiUrl, $paramsString, 1);
				} catch (Exception $e) {
				    var_dump($e);
				    //此处根据自己的需求进行自身的异常处理
				}
				if (!$response) {
				    echo "请求异常" . PHP_EOL;
				}
				$result = json_decode($response, true);
				if (!$result) {
				    echo "请求异常" . PHP_EOL;
				}
				$errorCode = $result['error_code'];
				if ($errorCode === 0) {
				    $_SESSION['MBT_mobile_captcha']=$code;
	                $_SESSION['MBT_captcha_mobile']=$mobile;
	                $_SESSION['MBT_captcha_mobile_time']=strtotime("+5 minutes");
	                echo "1";
	                //$data = $result['result'];
				    //echo "请求唯一标示：{$data["sid"]}" . PHP_EOL;
				    //echo "请求消耗次数：{$data["fee"]}" . PHP_EOL;
				} else {
				    // 请求异常
				    echo "请求异常:{$errorCode}_{$result["reason"]}" . PHP_EOL;
				}
            }elseif(_MBT('oauth_sms_select') == 'qcloud'){
				$phoneNumbers = [$mobile];

				try {
				    $ssender = new SmsSingleSender(_MBT('oauth_qcloud_access_id'), _MBT('oauth_qcloud_access_secret'));
				    $params = [$code];
				    $result = $ssender->sendWithParam("86", $phoneNumbers[0], _MBT('oauth_qcloud_sms_temp'),
				        $params, _MBT('oauth_qcloud_sms_sign'), "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
				    $rsp = json_decode($result, true);

				    if($rsp['result'] == '0'){
					    $_SESSION['MBT_mobile_captcha']=$code;
			            $_SESSION['MBT_captcha_mobile']=$mobile;
			            $_SESSION['MBT_captcha_mobile_time']=strtotime("+5 minutes");
			            echo "1";
			        }else{
			        	echo $rsp['errmsg'];
			        }
				    
				} catch(\Exception $e) {
				    //echo var_dump($e);
				    echo '发送失败，请稍后重试~';
				}
            }else{
            	$config = [
	                'accessKeyId' => _MBT('oauth_aliyun_access_id'),                
	                'accessKeySecret' => _MBT('oauth_aliyun_access_secret'),           
	                'signName' => _MBT('oauth_aliyun_sms_sign'), 
	                'templateCode' => _MBT('oauth_aliyun_sms_temp')            
	            ];
	            $sms = new \Sms($config);
	            $status = $sms->send_verify($mobile, $code);  
	            if (!$status) {
	                echo $sms->error;
	            } else {
	                $_SESSION['MBT_mobile_captcha']=$code;
	                $_SESSION['MBT_captcha_mobile']=$mobile;
	                $_SESSION['MBT_captcha_mobile_time']=strtotime("+5 minutes");
	                echo "1";
	            }
	        }
		}
	}elseif($action == 'mobantu_mobile_login' && _MBT('oauth_sms')){
		$mobile = esc_sql($_POST['mobile']); 
		$captcha = esc_sql($_POST['captcha']); 
		if(MBThemes_is_phone($mobile)){
			if(empty($captcha) || empty($_SESSION['MBT_mobile_captcha']) || trim(strtolower($captcha)) != $_SESSION['MBT_mobile_captcha'] || $mobile != $_SESSION['MBT_captcha_mobile'] || time() > $_SESSION['MBT_captcha_mobile_time']){
			  	echo __('验证码错误','mobantu');
			}else{
				$exist = $wpdb->get_var( $wpdb->prepare(
		            "SELECT ID FROM $wpdb->users WHERE mobile = %s",
		            $mobile ) );
				if($exist){
					unset($_SESSION['MBT_mobile_captcha']);
					unset($_SESSION['MBT_captcha_mobile']);
					unset($_SESSION['MBT_captcha_mobile_time']);
					wp_set_auth_cookie($exist,true,is_ssl());
					wp_signon( array(), is_ssl() );
					_mbt_add_activity($exist,'login');
					echo "1";
				}else{
					$pass = wp_generate_password(16, false);
					$login_name = "u".mt_rand(1000,9999).mt_rand(1000,9999).mt_rand(1000,9999);
					$userdata=array(
					  'user_login' => $login_name,
					  'user_pass' => $pass
					);
					$user_id = wp_insert_user( $userdata );
					if ( is_wp_error( $user_id ) ) {
						echo $user_id->get_error_message();
					}else{
						_mbt_add_activity($user_id,'register');
						$ff = $wpdb->update( $wpdb->users, array('mobile' => $mobile), array("ID"=>$user_id) );
						if($ff){
							unset($_SESSION['MBT_mobile_captcha']);
							unset($_SESSION['MBT_captcha_mobile']);
							unset($_SESSION['MBT_captcha_mobile_time']);
							wp_set_auth_cookie($user_id,true,is_ssl());
							wp_signon( array(), is_ssl() );
							echo "1";
						}else{
							wp_delete_user($user_id);
							echo __("系统超时，请稍后重试",'mobantu');
						}
					}
				}
			}
		}else{
			echo __("手机号格式错误",'mobantu');
		}
	}elseif($action == 'mobantu_return'){
		$url = $_POST['url'];
		if($url){
			$_SESSION['Erphplogin_return'] = $url;
		}
	}
	
}
exit;