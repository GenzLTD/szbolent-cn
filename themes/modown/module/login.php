<div class="sign">			
	<div class="sign-mask"></div>			
	<div class="sign-box<?php if(_MBT('login_image')) echo ' has-left';?>">	
		<?php if(_MBT('login_image')){echo '<div class="loginimage"><img src="'._MBT('login_image').'" /></div>';}?>	
		<div class="sign-wrap">	
			<div class="sign-tips"></div>			
			<form id="sign-in">  
			    <div class="form-item" style="text-align:center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo('name');?>"></a></div>
				<div class="form-item"><input type="text" name="user_login" class="form-control" id="user_login" placeholder="<?php _e('用户名/邮箱','mobantu');?>"><i class="icon icon-user"></i></div>			
				<div class="form-item"><input type="password" name="password" class="form-control" id="user_pass" placeholder="<?php _e('密码','mobantu');?>"><i class="icon icon-lock"></i></div>		
				<?php if(_MBT('captcha_login') == 'slide' && !modown_is_mobile()){?>
				<div class="form-item form-slide-item" id="form-slide-item2">
					<div id="captcha-slide2" class="captcha-slide2"><i class="icon icon-arrow-long-right"></i></div>
					<span id="verification-status2"><?php echo __('请拖动滑块到最右边','mobantu');?></span>
					<input type="hidden" id="user_captcha" name="user_captcha">
				</div>
				<?php }elseif(_MBT('captcha_login')){?>
				<div class="form-item">
					<input type="text" class="form-control" id="user_captcha" name="user_captcha" placeholder="<?php _e('验证码','mobantu');?>"><span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span>
					<i class="icon icon-safe"></i>
				</div>
				<?php }?>	
				<div class="sign-submit">			
					<input type="button" class="btn signinsubmit-loader" name="submit" value="<?php _e('登录','mobantu');?>">  			
					<input type="hidden" name="action" value="signin">			
				</div>			
				<div class="sign-trans"><?php if(!_MBT('register')){?><?php _e('没有账号？','mobantu');?><a href="javascript:;" class="erphp-reg-must"><?php _e('注册','mobantu');?></a>&nbsp;&nbsp;<?php }?><a href="<?php echo add_query_arg(array("action"=>"password","redirect_to"=>MBThemes_selfURL()),get_permalink(MBThemes_page("template/login.php")));?>" rel="nofollow" target="_blank"><?php _e('忘记密码？','mobantu');?></a><?php if(_MBT('oauth_sms')){?><a href="javascript:;" class="signsms-loader" style="float:right;position: relative;top: -3px;"><i class="icon icon-mobile" style="top:1.5px"></i><?php _e('手机号登录','mobantu');?></a><?php }?></div>		
				<?php echo MBThemes_login_module();?>
				<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
			    <div class="expend-container">
		            <a href="javascript:;" title="<?php _e('扫码登录','mobantu');?>" class="signmp-loader"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
		        </div>
		    	<?php }?>	
			</form>	
			<?php if(!_MBT('register')){?>		
			<form id="sign-up"<?php if(_MBT('register_social_only')) echo ' class="signup-social-only"';?> style="display: none;"> 	
			    <div class="form-item" style="text-align:center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo('name');?>"></a></div>			
			    <?php if(!_MBT('register_social_only')){?>	
				<div class="form-item"><input type="text" name="name" class="form-control" id="user_register" placeholder="<?php _e('用户名','mobantu');?>"><i class="icon icon-user"></i></div>			
				<div class="form-item"><input type="email" name="email" class="form-control" id="user_email" placeholder="<?php _e('邮箱','mobantu');?>"><i class="icon icon-mail"></i></div>		
				<div class="form-item"><input type="password" name="password2" class="form-control" id="user_pass2" placeholder="<?php _e('密码','mobantu');?>"><i class="icon icon-lock"></i></div>
				<?php if(_MBT('captcha') == 'email'){?>	
				<div class="form-item">
					<input type="text" class="form-control" id="captcha" name="captcha" placeholder="<?php _e('验证码','mobantu');?>"><span class="captcha-clk"><?php _e('获取验证码','mobantu');?></span>
					<i class="icon icon-safe"></i>
				</div>	
				<?php }elseif(_MBT('captcha') == 'image'){?>
				<div class="form-item">
					<input type="text" class="form-control" id="captcha" name="captcha" placeholder="<?php _e('验证码','mobantu');?>"><span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span>
					<i class="icon icon-safe"></i>
				</div>
				<?php }elseif(_MBT('captcha') == 'slide'){?>
					<?php if(!modown_is_mobile()){?>
					<div class="form-item form-slide-item" id="form-slide-item">
						<div id="captcha-slide" class="captcha-slide"><i class="icon icon-arrow-long-right"></i></div>
						<span id="verification-status"><?php echo __('请拖动滑块到最右边','mobantu');?></span>
						<input type="hidden" id="captcha" name="captcha">
					</div>
					<?php }else{?>
					<div class="form-item">
						<input type="text" class="form-control" id="captcha" name="captcha" placeholder="<?php _e('验证码','mobantu');?>"><span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span>
						<i class="icon icon-safe"></i>
					</div>
					<?php }?>
				<?php }elseif(_MBT('captcha') == 'invitation' && function_exists('ashuwp_check_invitation_code')){?>	
				<div class="form-item">
					<input type="text" name="captcha" class="form-control" id="captcha" placeholder="<?php _e('邀请码','mobantu');?>">
					<i class="icon icon-safe"></i>
					<?php if(_MBT('invitation_buy') && function_exists('erphpdown_invatation_do')){?>
					<a href="<?php echo add_query_arg('action','invitation',get_permalink(MBThemes_page('template/login.php')));?>" target="_blank" rel="nofollow" class="invitation-link"><?php _e('购买邀请码','mobantu');?></a>
					<?php }elseif(_MBT('invitation_link')){?><a href="<?php echo _MBT('invitation_link');?>" target="_blank" rel="nofollow" class="invitation-link"><?php _e('获取邀请码','mobantu');?></a><?php }?>
				</div>
				<?php }?>	
				<div class="sign-submit">			
					<input type="button" class="btn signupsubmit-loader" name="submit" value="<?php _e('注册','mobantu');?>">  			
					<input type="hidden" name="action" value="signup">  	
					<?php if(_MBT('register_policy')){?>
					<div class="form-policy"><input type="checkbox" id="policy_reg" name="policy_reg" value="1" checked> <label for="policy_reg"><?php echo sprintf( __('我已阅读并同意《<a href="%s" target="_blank">用户注册协议</a>》','mobantu'), _MBT('register_policy'));?></label></div>
					<?php }?>			
				</div>			
				<div class="sign-trans"><?php _e('已有账号？','mobantu');?> <a href="javascript:;" class="modown-login-must"><?php _e('登录','mobantu');?></a><?php if(_MBT('oauth_sms')){?>&nbsp;&nbsp;<a href="javascript:;" class="signsms-loader" style="float:right;position: relative;top: -3px;"><i class="icon icon-mobile" style="top:1.5px"></i><?php _e('手机号登录','mobantu');?></a><?php }?></div>	
				<?php }?>	
				<?php echo MBThemes_login_module();?>
				<?php if(_MBT('register_social_only')){?>	
				<div class="sign-trans"><?php _e('已有账号？','mobantu');?> <a href="javascript:;" class="modown-login-must"><?php _e('登录','mobantu');?></a><?php if(_MBT('oauth_sms')){?>&nbsp;&nbsp;<a href="javascript:;" class="signsms-loader" style="float:right;position: relative;top: -3px;"><i class="icon icon-mobile" style="top:1.5px"></i><?php _e('手机号登录','mobantu');?></a><?php }?></div>	
				<?php }?>
				<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
			    <div class="expend-container">
		            <a href="javascript:;" title="<?php _e('扫码登录','mobantu');?>" class="signmp-loader"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
		        </div>
		    	<?php }?>	
			</form>	
			<?php }?>	
			<?php if(_MBT('oauth_sms')){?>
			<form id="sign-sms" style="display: none;">  
			    <div class="form-item" style="text-align:center"><a href="<?php echo home_url();?>"><img class="logo-login" src="<?php echo _MBT('logo_login')?_MBT('logo_login'):THEME_URI.'/static/img/logo.png';?>" alt="<?php bloginfo('name');?>"></a></div>
				<div class="form-item"><input type="text" name="user_mobile" class="form-control" id="user_mobile" placeholder="<?php _e('手机号','mobantu');?>"><i class="icon icon-mobile"></i></div>		
				<div class="form-item">
					<input type="text" class="form-control" id="user_captcha22" name="user_captcha22" placeholder="<?php _e('请先输入图形验证码','mobantu');?>"><span class="captcha-clk2"><?php _e('显示验证码','mobantu');?></span>
					<i class="icon icon-image"></i>
				</div>	
				<div class="form-item"><input type="text" name="user_mobile_captcha" class="form-control" id="user_mobile_captcha" placeholder="<?php _e('验证码','mobantu');?>"><span class="captcha-sms-clk"><?php _e('获取验证码','mobantu');?></span><i class="icon icon-safe"></i></div>		
				<div class="sign-submit">			
					<input type="button" class="btn signsmssubmit-loader" name="submit" value="<?php _e('登录','mobantu');?>">  			
					<input type="hidden" name="action" value="signsms">		
					<?php if(_MBT('register_policy')){?>
					<div class="form-policy"><input type="checkbox" name="policy_sms" id="policy_sms" value="1" checked> <label for="policy_sms"><?php echo sprintf( __('我已阅读并同意《<a href="%s" target="_blank">用户注册协议</a>》','mobantu'), _MBT('register_policy'));?></label></div>
					<?php }?>	
				</div>			
				<div class="sign-trans"><?php _e('手机不在身边？','mobantu');?> <a href="javascript:;" class="modown-login-must"><?php _e('账号登录','mobantu');?></a></div>	
				<?php echo MBThemes_login_module();?>
				<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
			    <div class="expend-container">
		            <a href="javascript:;" title="<?php _e('扫码登录','mobantu');?>" class="signmp-loader"><svg class="icon toggle" style="width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="6487"><path d="M540.9 866h59v59h-59v-59zM422.8 423.1V98.4H98.1v324.8h59v59h59v-59h206.7z m-265.7-59V157.4h206.7v206.7H157.1z m0 0M216.2 216.4h88.6V305h-88.6v-88.6zM600 98.4v324.8h324.8V98.4H600z m265.7 265.7H659V157.4h206.7v206.7z m0 0M718.1 216.4h88.6V305h-88.6v-88.6zM216.2 718.3h88.6v88.6h-88.6v-88.6zM98.1 482.2h59v59h-59v-59z m118.1 0h59.1v59h-59.1v-59z m0 0M275.2 600.2H98.1V925h324.8V600.2h-88.6v-59h-59v59z m88.6 59.1V866H157.1V659.3h206.7z m118.1-531.4h59v88.6h-59v-88.6z m0 147.6h59v59h-59v-59zM659 482.2H540.9v-88.6h-59v88.6H334.3v59H600v59h59v-118z m0 118h59.1v59H659v-59z m-177.1 0h59v88.6h-59v-88.6z m0 147.7h59V866h-59V747.9zM600 688.8h59V866h-59V688.8z m177.1-88.6h147.6v59H777.1v-59z m88.6-118h59v59h-59v-59z m-147.6 0h118.1v59H718.1v-59z m0 206.6h59v59h-59v-59z m147.6 59.1h-29.5v59h59v-59h29.5v-59h-59v59z m-147.6 59h59V866h-59v-59.1z m59 59.1h147.6v59H777.1v-59z m0 0" p-id="6488"></path></svg></a>
		        </div>
		    	<?php }?>	
			</form>
			<?php }?>
			<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
			<form id="sign-mp">
				<div class="form-item">
					<?php echo do_shortcode('[erphp_weixin_scan type=1]');?>
				</div>					
				<div class="sign-trans" style="text-align:center"><a href="javascript:;" class="modown-login-must"><?php _e('使用其他方式登录/注册','mobantu');?></a></div>
				<?php if(_MBT('oauth_weixin_mp') && function_exists('ews_login')){?>
			    <div class="expend-container">
		            <a href="javascript:;" title="<?php _e('账号登录','mobantu');?>" class="modown-login-must"><svg class="icon toggle" hidden style="padding:0.5rem;width: 4em; height: 4em;vertical-align: middle;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1166" data-spm-anchor-id="a313x.7781069.0.i0"><path d="M192 960h640v64H192v-64z" p-id="1167"></path><path d="M384 768h256v256H384v-256zM960 0H64a64 64 0 0 0-64 64v640a64 64 0 0 0 64 64h896a64 64 0 0 0 64-64V64a64 64 0 0 0-64-64z m0 704H64V64h896v640z" p-id="1168"></path><path d="M128 128h768v512H128V128z" p-id="1169"></path></svg></a>
		        </div>
		    	<?php }?>
			</form>
			<?php }?>	
			<div class="sign-notice"><?php echo _MBT('login_notice');?></div>
		</div>
	</div>			
</div>