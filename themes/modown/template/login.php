<?php 
/*
	template name: 登录
	description: template for mobantu.com modown theme 
*/
if (!session_id()) session_start();
if(isset($_GET["redirect_to"]) && $_GET["redirect_to"]){
	$_COOKIE['login_redirect_to'] = urldecode($_GET['redirect_to']);
  setcookie('login_redirect_to',urldecode($_GET['redirect_to']),0,'/');
}

if(is_user_logged_in()){
	if(isset($_GET["redirect_to"]) && $_GET["redirect_to"]){
		$_COOKIE['login_redirect_to'] = '';
    setcookie('login_redirect_to','',0,'/');
		header("Location:".$_GET["redirect_to"]);
	}elseif(isset($_COOKIE['login_redirect_to']) && $_COOKIE['login_redirect_to']){
		$login_redirect_to = $_COOKIE['login_redirect_to'];
		$_COOKIE['login_redirect_to'] = '';
    setcookie('login_redirect_to','',0,'/');
		header("Location:".$login_redirect_to);
	}else{
		if(current_user_can('administrator')){
			header("Location:".admin_url());
		}else{
			header("Location:".get_permalink(MBThemes_page('template/user.php')));
		}
	}
}else{
	if(isset($_POST['action']) && $_POST['action'] == 'invitation'){
		if(isset($_POST['paytype']) && $_POST['paytype']){
			$paytype=intval($_POST['paytype']);
			$email = $_POST['email'];
			
			if(isset($_POST['paytype']) && $paytype==1)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/alipay.php?email=".$email;
			}
			elseif(isset($_POST['paytype']) && $paytype==2)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/f2fpay.php?email=".$email;
			}
			elseif(isset($_POST['paytype']) && $paytype==3)
			{
				if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
					$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(ERPHPDOWN_INVITATION_URL).'%2Fpayment%2Fweixin.php%3Femail%3D'.$email.'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
				}else{
					$url=ERPHPDOWN_INVITATION_URL."/payment/weixin.php?email=".$email;
				}
			}
			elseif(isset($_POST['paytype']) && $paytype==4)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/paypal.php?email=".$email;
			}
			elseif(isset($_POST['paytype']) && $paytype==52)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/paypy.php?email=".$email;
			}
			elseif(isset($_POST['paytype']) && $paytype==51)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/paypy.php?email=".$email."&type=alipay";
			}
			elseif(isset($_POST['paytype']) && $paytype==61)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/xhpay3.php?email=".$email."&type=2";
			}
			elseif(isset($_POST['paytype']) && $paytype==62)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/xhpay3.php?email=".$email."&type=1";
			}elseif(isset($_POST['paytype']) && $paytype==71)
		    {
		        $url=ERPHPDOWN_INVITATION_URL."/payment/codepay.php?email=".$email."&type=1";
		    }elseif(isset($_POST['paytype']) && $paytype==72)
		    {
		        $url=ERPHPDOWN_INVITATION_URL."/payment/codepay.php?email=".$email."&type=3";
		    }elseif(isset($_POST['paytype']) && $paytype==73)
		    {
		        $url=ERPHPDOWN_INVITATION_URL."/payment/codepay.php?email=".$email."&type=2";
		    }elseif(isset($_POST['paytype']) && $paytype==81)
			{
				$url=ERPHPDOWN_INVITATION_URL."/epay.php?email=".$email."&type=alipay";
			}elseif(isset($_POST['paytype']) && $paytype==82)
			{
				$url=ERPHPDOWN_INVITATION_URL."/epay.php?email=".$email."&type=wxpay";
			}
			elseif(isset($_POST['paytype']) && $paytype==92)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/payjs.php?email=".$email;
			}
			elseif(isset($_POST['paytype']) && $paytype==91)
			{
				$url=ERPHPDOWN_INVITATION_URL."/payment/payjs.php?email=".$email."&type=alipay";
			}
			else{
				
			}
			header("Location:".$url);
			exit;
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <title><?php _e('登录','mobantu');?> - <?php bloginfo("name");?></title>
  <link rel="shortcut icon" href="<?php echo _MBT('favicon')?>">
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/base.css?ver=<?php echo THEME_VER;?>" type="text/css" media="screen" />
  <?php if(_MBT('theme_iconfont_out')){?>
  <link rel="stylesheet"  href="//cdn.staticfile.net/dripicons/2.0.0/webfont.min.css" type="text/css" media="screen" />
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/iconfont.css?ver=<?php echo THEME_VER;?>" type="text/css" media="screen" />
  <?php }else{?>
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/fonts.css?ver=<?php echo THEME_VER;?>" type="text/css" media="screen" />
  <?php }?>
  <link rel="stylesheet"  href="<?php bloginfo("template_url");?>/static/css/login.css?ver=<?php echo THEME_VER;?>" type="text/css" media="screen" />
  <script type="text/javascript" src="<?php bloginfo("template_url");?>/static/js/jquery.min.js"></script>
  <!--[if lt IE 9]><script src="<?php bloginfo("template_url");?>/static/js/html5.min.js"></script><![endif]-->
  <script>window._MBT = {uri: '<?php bloginfo('template_url') ?>', child: '<?php echo STYLESHEET_URI;?>', urg: '<?php if(file_exists(STYLESHEET_DIR.'/action/login.php')) echo '1';else echo '0'; ?>', url:'<?php bloginfo('url');?>'}</script>
  <style><?php 
  $theme_color_custom = _MBT('theme_color_custom');
  $theme_color = _MBT('theme_color');
  $color = '#ff5f33';
  if($theme_color && $theme_color != '#ff5f33'){
   $color = $theme_color;
  }
  if($theme_color_custom && $theme_color_custom != '#ff5f33'){
   $color = $theme_color_custom;
  }

  echo ":root{--theme-color: ".$color.";}";
  if(_MBT('login_bg')){
  	echo 'body{background-image: url('._MBT('login_bg').');background-repeat: no-repeat;background-size: cover;background-position: center center;background-attachment: fixed;}';
	}
	echo _MBT('css');
	?></style>
</head>
<?php 
$night_class = '';
if(_MBT('theme_night')){
	if(isset($_COOKIE['mbt_theme_night'])){
	    if($_COOKIE['mbt_theme_night'] == '1'){
	      	$night_class = 'night';
	    }
	}elseif(_MBT('theme_night_default')){
	    $night_class = 'night';
	}elseif(_MBT('theme_night_auto')){
	    $time = intval(date("Hi"));
	    if ($time < 730 || $time > 1930) {
	      	$night_class = 'night';
	    }
	}
}
?>
<body class="<?php echo $night_class;?>">
	<div id="loginbox" class="loginbox<?php if(_MBT('login_image')) echo ' has-left';?>">	
		<?php if(_MBT('login_image')){echo '<div class="loginimage"><img src="'._MBT('login_image').'" /></div>';}?>
		<div class="sign-wrap">
	    	<?php if(isset($_GET['action']) && $_GET['action'] == 'register' && !_MBT('register')){?>
		    <div class="part regPart<?php if(_MBT('register_social_only')) echo ' signup-social-only';?>">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="regform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;" autocomplete="off">
		        	<?php if(!_MBT('register_social_only')){?>
		            <p class="input-item">
		                <input class="input-control" id="regname" type="text" placeholder="<?php _e('用户名','mobantu');?>" name="regname" required="" ><i class="icon icon-user"></i>
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="regemail" type="email" placeholder="<?php _e('邮箱','mobantu');?>" name="regemail" required="" ><i class="icon icon-mail"></i>
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="regpass" type="password" placeholder="<?php _e('密码','mobantu');?>" name="regpass" required=""><i class="icon icon-lock"></i>
		            </p>
		            <?php if(_MBT('captcha') == 'email'){?>
		            <p class="input-item">
		                <input class="input-control" id="captcha" type="text" placeholder="<?php _e('验证码','mobantu');?>" name="captcha" required="">
		                <span class="captcha-clk"><?php _e('获取验证码','mobantu');?></span><i class="icon icon-safe"></i>
		            </p>
		            <?php }elseif(_MBT('captcha') == 'image'){?>
		            <p class="input-item">
		            	<input class="input-control" id="captcha" type="text" placeholder="<?php _e('验证码','mobantu');?>" name="captcha" required="">
		                <span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span><i class="icon icon-safe"></i>
		            </p>
		            <?php }elseif(_MBT('captcha') == 'slide'){?>
		            	<?php if(!modown_is_mobile()){?>
			            <p class="input-item form-slide-item" id="form-slide-item">
										<span id="captcha-slide" class="captcha-slide"><i class="icon icon-arrow-long-right"></i></span>
										<span id="verification-status"><?php echo __('请拖动滑块到最右边','mobantu');?></span>
										<input type="hidden" id="captcha" name="captcha">
									</p>
									<?php }else{?>
									<p class="input-item">
			            	<input class="input-control" id="captcha" type="text" placeholder="<?php _e('验证码','mobantu');?>" name="captcha" required="">
			                <span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span><i class="icon icon-safe"></i>
			            </p>
									<?php }?>
		            <?php }elseif(_MBT('captcha') == 'invitation' && function_exists('ashuwp_check_invitation_code')){?>
		            <p class="input-item">
		            	<input class="input-control" id="captcha" type="text" placeholder="<?php _e('邀请码','mobantu');?>" name="captcha" required="">
		                <i class="icon icon-safe"></i>
		                <?php if(_MBT('invitation_buy') && function_exists('erphpdown_invatation_do')){?><a href="<?php echo add_query_arg('action','invitation',get_permalink(MBThemes_page('template/login.php')));?>" rel="nofollow" class="invitation-link"><?php _e('购买邀请码','mobantu');?></a><?php }elseif(_MBT('invitation_link')){?><a href="<?php echo _MBT('invitation_link');?>" target="_blank" rel="nofollow" class="invitation-link"><?php _e('获取邀请码','mobantu');?></a><?php }?>
		            </p>
		            <?php }?>
		            <p class="sign-tips2"></p>
		            <p class="input-submit">
		                <input class="submit register-loader btn" type="submit" value="<?php _e('注册','mobantu');?>">
		                <input type="hidden" name="action" value="register">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
	        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
	        			<?php if(_MBT('register_policy')){?>
						<div class="form-policy"><input type="checkbox" id="policy_reg" name="policy_reg" value="1" checked> <label for="policy_reg"><?php echo sprintf( __('我已阅读并同意《<a href="%s" target="_blank">用户注册协议</a>》','mobantu'), _MBT('register_policy'));?></label></div>
						<?php }?>
		            </p>
		            <p class="safe">
		            	<?php if(_MBT('oauth_sms')){?><a href="<?php echo add_query_arg('action','sms',get_permalink(MBThemes_page('template/login.php')));?>" class="signsms-loader"><?php _e('手机号登录','mobantu');?></a><?php }?>
		                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('返回登录','mobantu');?></a>
		            </p>
		        	<?php }?>
		            <?php echo MBThemes_login_page();?>
		            <?php if(_MBT('register_social_only')){?>
		            <p class="safe">
		            	<?php if(_MBT('oauth_sms')){?><a href="<?php echo add_query_arg('action','sms',get_permalink(MBThemes_page('template/login.php')));?>" class="signsms-loader"><?php _e('手机号登录','mobantu');?></a><?php }?>
		                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('返回登录','mobantu');?></a>
		            </p>
		            <?php }?>
		        </form>
		    </div>
		    <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" title="<?php _e('扫码登录','mobantu');?>"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
	        </div>
	    	<?php }?>
		    <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'password'){
		    	$security = wp_generate_password(16, false);
				$_SESSION['MBT_modown_security'] = $security;
		    ?>
		    <div class="part passPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="passform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
		            <p class="input-item">
		                <input class="input-control" id="passname" type="text" placeholder="<?php _e('用户名/邮箱','mobantu');?>" name="passname" required=""><i class="icon icon-user"></i>
		            </p>
		            <p class="sign-tips2"></p>
		            <p class="input-submit">
		                <input class="submit pass-loader btn" type="submit" value="<?php _e('找回密码','mobantu');?>">
		                <input type="hidden" name="action" value="password">
		                <input type="hidden" id="security" name="security" value="<?php echo $security;?>">
	        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
		            </p>
		            <p class="safe">
		                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('返回登录','mobantu');?></a>
		            </p> 
		        </form>
		    </div>
	        <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'mp'){?>
	        <div class="part mpPart">
	            <form class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
	                <p class="input-item">
	                    <?php echo do_shortcode('[erphp_weixin_scan]');?>
	                </p>
	                <p class="sign-tips2"></p>
	                <p class="safe" style="text-align:center;margin-bottom: 10px">
	                    <a class="signin-loader" style="float:none" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('使用其他方式登录/注册','mobantu');?></a>
	                </p> 
	            </form>
	        </div>
	        <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>" title="<?php _e('账号','mobantu');?>"><svg class="icon toggle" hidden style="padding:0.5rem;width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1166" data-spm-anchor-id="a313x.7781069.0.i0"><path d="M192 960h640v64H192v-64z" p-id="1167"></path><path d="M384 768h256v256H384v-256zM960 0H64a64 64 0 0 0-64 64v640a64 64 0 0 0 64 64h896a64 64 0 0 0 64-64V64a64 64 0 0 0-64-64z m0 704H64V64h896v640z" p-id="1168"></path><path d="M128 128h768v512H128V128z" p-id="1169"></path></svg></a>
	        </div>
	    	<?php }?>
		    <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'reset_password'){?>
		    <div class="part resetPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo');?>" alt="<?php bloginfo("name");?>"></a></h2>
		    <?php
		    	$reset_key = $_GET['key']; 
				$user_login = esc_sql($_GET['login']); 
				$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email, user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));   
				$user_login = $user_data->user_login;   
				$user_email = $user_data->user_email; 
				$md5_str = _MBT('login_password_key')?_MBT('login_password_key'):'M2O7B4A8N5T9U0';
				if(!empty($reset_key) && !empty($user_data) && md5($md5_str.$user_data->user_activation_key) == $reset_key) {   
					$security = wp_generate_password(16, false);
					$_SESSION['MBT_modown_reset_security'] = $security;
		    ?>

		        <form id="resetform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
		            <p class="input-item">
	                    <input class="input-control" id="resetpass" type="password" placeholder="<?php _e('新密码','mobantu');?>" name="resetpass"><i class="icon icon-lock"></i>
	                </p>
	                <p class="input-item">
	                    <input class="input-control" id="resetpass2" type="password" placeholder="<?php _e('重复新密码','mobantu');?>" name="resetpass2"><i class="icon icon-lock"></i>
	                </p>
	                <p class="sign-tips2"></p>
	                <p class="input-submit">
	                    <input class="submit reset-loader btn" type="button" value="<?php _e('修改密码','mobantu');?>">
	                    <input type="hidden" name="action" value="reset">
	                    <input type="hidden" name="key" id="resetkey" value="<?php echo $reset_key;?>">
	                    <input type="hidden" name="user_login" id="user_login" value="<?php echo $user_login;?>">
	                    <input type="hidden" id="security" name="security" value="<?php echo $security;?>">
	    				<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
	                </p>
		        </form>
		        <?php }else{?>
		        	<div class=regSuccess><?php _e('错误的请求，请查看邮箱里的重置密码链接。','mobantu');?></div>
		        <?php }?>
		    </div>
			<?php }elseif(isset($_GET['action']) && $_GET['action'] == 'sms'){?>
			<div class="part smsPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="smsform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;" autocomplete="off">
		            <p class="input-item">
		                <input class="input-control" id="regmobile" type="text" placeholder="<?php _e('手机号','mobantu');?>" name="regmobile" required="" ><i class="icon icon-mobile"></i>
		            </p>
		            <p class="input-item">
		            	<input class="input-control" id="user_captcha22" type="text" placeholder="<?php _e('请先输入图形验证码','mobantu');?>" name="user_captcha22" required="">
		                <span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span><i class="icon icon-image"></i>
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="captcha" type="text" placeholder="<?php _e('验证码','mobantu');?>" name="captcha" required="">
		                <span class="captcha-sms-clk"><?php _e('获取验证码','mobantu');?></span><i class="icon icon-safe"></i>
		            </p>
		            <p class="sign-tips2"></p>
		            <p class="input-submit">
		                <input class="submit mobile-loader btn" type="submit" value="<?php _e('登录','mobantu');?>">
		                <input type="hidden" name="action" value="register-mobile">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
	        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
	        			<?php if(_MBT('register_policy')){?>
						<div class="form-policy"><input type="checkbox" id="policy_reg" name="policy_reg" value="1" checked> <label for="policy_reg"><?php echo sprintf( __('我已阅读并同意《<a href="%s" target="_blank">用户注册协议</a>》','mobantu'), _MBT('register_policy'));?></label></div>
						<?php }?>
		            </p>
		            <p class="safe">
		            	<?php if(!_MBT('register')){?><a class="signup-loader right" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>">&nbsp;&nbsp;<?php _e('注册账号','mobantu');?></a><?php }?>
		                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('账号登录','mobantu');?></a>
		            </p>
		            <?php echo MBThemes_login_page();?>
		        </form>
		    </div>
		    <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" title="<?php _e('扫码登录','mobantu');?>"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
	        </div>
	    	<?php }}elseif(isset($_GET['action']) && $_GET['action'] == 'invitation' && function_exists('erphpdown_invatation_do')){?>
	    	<div class="part invitationPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="invitationform" class="loginform" method="post">
		        	<h3><?php _e('购买邀请码','mobantu');?><?php echo ' ￥'._MBT('invitation_price');?></h3>
		            <p class="input-item">
		                <input class="input-control" id="email" type="text" placeholder="<?php _e('邮箱','mobantu');?>" name="email" required=""><i class="icon icon-mail"></i>
		            </p>
		            <p class="input-item payment-radios">
		                <?php if(get_option('ice_payapl_api_uid')){?> 
	                    <input type="radio" id="paytype4" class="paytype" checked name="paytype" value="4" /> <label for="paytype4" class="payment-label payment-paypal-label"><i class="icon icon-paypal"></i></label>
	                    <?php }?>
	                    <?php if(get_option('ice_weixin_mchid')){?> 
	                    <input type="radio" id="paytype3" class="paytype" checked name="paytype" value="3" /> <label for="paytype3" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>
	                    <?php }?>
	                    <?php if(get_option('ice_ali_partner')){?> 
	                    <input type="radio" id="paytype1" class="paytype" checked name="paytype" value="1" /> <label for="paytype1" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
	                    <?php }?>
	                    <?php if(get_option('erphpdown_f2fpay_id') && !get_option('erphpdown_f2fpay_alipay')){?> 
	                    <input type="radio" id="paytype2" class="paytype" checked name="paytype" value="2" /> <label for="paytype2" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
	                    <?php }?>
		                <?php if(get_option('erphpdown_xhpay_appid32')){?> 
		                <input type="radio" id="paytype62" class="paytype" name="paytype" value="62" checked /> <label for="paytype62" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label> 
		                <?php }?>
		                <?php if(get_option('erphpdown_xhpay_appid31')){?> 
		                <input type="radio" id="paytype61" class="paytype" name="paytype" value="61" checked /> <label for="paytype61" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>   
		                <?php }?>
		                <?php if(get_option('erphpdown_payjs_appid')){?>
		                <input type="radio" id="paytype91" class="paytype" name="paytype" value="91" checked /><label for="paytype91" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>
										<input type="radio" id="paytype92" class="paytype" name="paytype" value="92" /><label for="paytype92" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label>
		                <?php }?>
		                <?php if(get_option('erphpdown_codepay_appid')){?> 
		                <?php if(!get_option('erphpdown_codepay_alipay')){?>
		                <input type="radio" id="paytype71" class="paytype" name="paytype" value="71" checked /> <label for="paytype71" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label><?php }?>
		                <input type="radio" id="paytype72" class="paytype" name="paytype" value="72" /> <label for="paytype72" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label>
		                <?php if(!get_option('erphpdown_codepay_qqpay')){?>
		                <input type="radio" id="paytype73" class="paytype" name="paytype" value="73" /> <label for="paytype73" class="payment-label payment-qqpay-label"><i class="icon icon-qq"></i></label>    
		            	<?php }?>
		                <?php }?>
		                <?php if(get_option('erphpdown_paypy_key')){?> 
		                <?php if(!get_option('erphpdown_paypy_alipay')){?><input type="radio" id="paytype51" class="paytype" name="paytype" value="51" checked /> <label for="paytype51" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label><?php }?>
		                <?php if(!get_option('erphpdown_paypy_wxpay')){?><input type="radio" id="paytype52" class="paytype" name="paytype" value="52" checked /> <label for="paytype52" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label><?php }?>
		                <?php }?>
		                <?php if(function_exists('plugin_check_epay')){ if(plugin_check_epay() && get_option('erphpdown_epay_id')){?>
		                <?php if(!get_option('erphpdown_epay_alipay')){?><input type="radio" id="paytype81" class="paytype" name="paytype" value="81" checked /> <label for="paytype81" class="payment-label payment-alipay-label"><i class="icon icon-alipay-color"></i></label><?php }?>
		                <?php if(!get_option('erphpdown_epay_wxpay')){?><input type="radio" id="paytype82" class="paytype" name="paytype" value="82" /> <label for="paytype82" class="payment-label payment-wxpay-label"><i class="icon icon-wxpay-color"></i></label><?php }?>
		                <?php }}?>
		            </p>
		            <p class="sign-tips2"></p>
		            <p class="input-submit">
		                <input class="submit invitation-loader btn" type="submit" value="<?php _e('立即支付','mobantu');?>">
		                <input type="hidden" name="action" value="invitation">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
	        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
		            </p>
		            <p class="safe">
		                <a class="signin-loader" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('返回注册','mobantu');?></a>
		            </p> 
		        </form>
		    </div>
		    <?php }elseif(isset($_GET['action']) && $_GET['action'] == 'login'){?>
		    <div class="part loginPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="loginform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
		            <p class="input-item">
		                <input class="input-control" id="username" type="text" placeholder="<?php _e('用户名/邮箱','mobantu');?>" name="username" required="" aria-required="true"><i class="icon icon-user"></i>
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="password" type="password" placeholder="<?php _e('密码','mobantu');?>" name="password" required="" aria-required="true"><i class="icon icon-lock"></i>
		            </p>
		            <?php if(_MBT('captcha_login') == 'slide' && !modown_is_mobile()){?>
		            <p class="input-item form-slide-item" id="form-slide-item">
									<span id="captcha-slide" class="captcha-slide"><i class="icon icon-arrow-long-right"></i></span>
									<span id="verification-status"><?php echo __('请拖动滑块到最右边','mobantu');?></span>
									<input type="hidden" id="captcha" name="captcha">
								</p>
		            <?php }elseif(_MBT('captcha_login')){?>
		            <p class="input-item">
		            	<input class="input-control" id="captcha" type="text" placeholder="<?php _e('验证码','mobantu');?>" name="captcha" required="">
		                <span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span><i class="icon icon-safe"></i>
		            </p>
		        	<?php }?>
		            <p class="sign-tips2"></p>
		            <p class="input-submit">
		                <input class="submit login-loader btn" type="submit" value="<?php _e('登录','mobantu');?>">
		                <input type="hidden" name="action" value="login">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
	        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
	        			<input type="hidden" id="redirect_to" value="<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];?>">
		            </p>
		            <p class="safe">
		                <a class="lostpwd-loader left" href="<?php echo add_query_arg('action','password',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('忘记密码？','mobantu');?></a>
		                <?php if(!_MBT('register')){?><a class="signup-loader right" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>">&nbsp;&nbsp;<?php _e('注册账号','mobantu');?></a><?php }?>
		                <?php if(_MBT('oauth_sms')){?><a href="<?php echo add_query_arg('action','sms',get_permalink(MBThemes_page('template/login.php')));?>" class="signsms-loader"><?php _e('手机号登录','mobantu');?></a><?php }?>
		            </p>
		            <?php echo MBThemes_login_page();?>
		        </form>
		    </div>
		    <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" title="<?php _e('扫码登录','mobantu');?>"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
	        </div>
	    	<?php }?>
		    <?php }else{?>
		    	<?php if(_MBT('oauth_sms_first')){?>
		    	<div class="part smsPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="smsform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;" autocomplete="off">
		            <p class="input-item">
		                <input class="input-control" id="regmobile" type="text" placeholder="<?php _e('手机号','mobantu');?>" name="regmobile" required="" ><i class="icon icon-mobile"></i>
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="captcha" type="text" placeholder="<?php _e('验证码','mobantu');?>" name="captcha" required="">
		                <span class="captcha-sms-clk"><?php _e('获取验证码','mobantu');?></span><i class="icon icon-safe"></i>
		            </p>
		            <p class="sign-tips2"></p>
		            <p class="input-submit">
		                <input class="submit mobile-loader btn" type="submit" value="<?php _e('登录','mobantu');?>">
		                <input type="hidden" name="action" value="register-mobile">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
	        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
	        			<?php if(_MBT('register_policy')){?>
						<div class="form-policy"><input type="checkbox" id="policy_reg" name="policy_reg" value="1" checked> <label for="policy_reg"><?php echo sprintf( __('我已阅读并同意《<a href="%s" target="_blank">用户注册协议</a>》','mobantu'), _MBT('register_policy'));?></label></div>
						<?php }?>
		            </p>
		            <p class="safe">
		            	<?php if(!_MBT('register')){?><a class="signup-loader right" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>">&nbsp;&nbsp;<?php _e('注册账号','mobantu');?></a><?php }?>
		                <a class="signin-loader" href="<?php echo add_query_arg('action','login',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('账号登录','mobantu');?></a>
		            </p>
		            <?php echo MBThemes_login_page();?>
		        </form>
		    </div>
		    	<?php }else{?>
		    	<div class="part loginPart">
		    	<h2><a href="<?php bloginfo("url");?>"><img src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo("name");?>"></a></h2>
		        <form id="loginform" class="loginform" method="post" novalidate="novalidate" onSubmit="return false;">
		            <p class="input-item">
		                <input class="input-control" id="username" type="text" placeholder="<?php _e('用户名/邮箱','mobantu');?>" name="username" required="" aria-required="true"><i class="icon icon-user"></i>
		            </p>
		            <p class="input-item">
		                <input class="input-control" id="password" type="password" placeholder="<?php _e('密码','mobantu');?>" name="password" required="" aria-required="true"><i class="icon icon-lock"></i>
		            </p>
		            <?php if(_MBT('captcha_login') == 'slide' && !modown_is_mobile()){?>
		            <p class="input-item form-slide-item" id="form-slide-item">
									<span id="captcha-slide" class="captcha-slide"><i class="icon icon-arrow-long-right"></i></span>
									<span id="verification-status"><?php echo __('请拖动滑块到最右边','mobantu');?></span>
									<input type="hidden" id="captcha" name="captcha">
								</p>
		            <?php }elseif(_MBT('captcha_login')){?>
		            <p class="input-item">
		            	<input class="input-control" id="captcha" type="text" placeholder="<?php _e('验证码','mobantu');?>" name="captcha" required="">
		                <span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span><i class="icon icon-safe"></i>
		            </p>
		        	<?php }?>
		            <p class="sign-tips2"></p>
		            <p class="input-submit">
		                <input class="submit login-loader btn" type="submit" value="<?php _e('登录','mobantu');?>">
		                <input type="hidden" name="action" value="login">
		                <input type="hidden" id="security" name="security" value="<?php echo  wp_create_nonce( 'security_nonce' );?>">
	        			<input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
	        			<input type="hidden" id="redirect_to" value="<?php if(isset($_GET['redirect_to'])) echo $_GET['redirect_to'];?>">
		            </p>
		            <p class="safe">
		                <a class="lostpwd-loader left" href="<?php echo add_query_arg('action','password',get_permalink(MBThemes_page('template/login.php')));?>"><?php _e('忘记密码？','mobantu');?></a>
		                <?php if(!_MBT('register')){?><a class="signup-loader right" href="<?php echo add_query_arg('action','register',get_permalink(MBThemes_page('template/login.php')));?>">&nbsp;&nbsp;<?php _e('注册账号','mobantu');?></a><?php }?>
		                <?php if(_MBT('oauth_sms')){?><a href="<?php echo add_query_arg('action','sms',get_permalink(MBThemes_page('template/login.php')));?>" class="signsms-loader"><?php _e('手机号登录','mobantu');?></a><?php }?>
		            </p>
		            <?php echo MBThemes_login_page();?>
		        </form>
		    </div>
		  	<?php }?>
		    <?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
		    <div class="expend-container">
	            <a href="<?php echo add_query_arg('action','mp',get_permalink(MBThemes_page('template/login.php')));?>" title="<?php _e('扫码登录','mobantu');?>"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
	        </div>
	    	<?php }?>
		    <?php }?>
		    <div class="sign-notice"><?php echo _MBT('login_notice');?></div>
		</div>
	</div>
	<a href="<?php echo home_url();?>" class="return-home" title="<?php _e('返回首页','mobantu');?>"><i class="icon icon-home-s"></i></a>

	<script><?php if(_MBT('theme_fan')){if(_MBT('theme_fan_default')){?>var zh_autoLang_t=true;var zh_autoLang_s=false;<?php }else{?>var zh_autoLang_t=false;var zh_autoLang_s=true;<?php }}else{?>var zh_autoLang_t=false;var zh_autoLang_s=true;<?php }?></script>
	<?php if(file_exists(STYLESHEET_DIR.'/static/js/login.js')){?>
	<script type="text/javascript" src="<?php echo STYLESHEET_URI;?>/static/js/login.js?ver=<?php echo THEME_VER;?>"></script>
	<?php }else{?>
	<script type="text/javascript" src="<?php bloginfo("template_url");?>/static/js/login.js?ver=<?php echo THEME_VER;?>"></script>
	<?php }?>
  <?php if(function_exists('ews_login')){?>
  <script>var ews_ajax_url = "<?php echo admin_url("admin-ajax.php");?>";window._EWS = {"ajaxurl":"<?php echo admin_url("admin-ajax.php");?>"}</script>
  <script type="text/javascript" src="<?php echo EWS_URL;?>/assets/ews.js?ver=<?php echo EWS_VERSION;?>"></script>
  <?php }?>
  
</body>
</html>