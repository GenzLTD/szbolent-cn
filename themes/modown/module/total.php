<div class="totals">
	<?php 
		if(!_MBT('home_total_no')){
			$count_posts = wp_count_posts('post');
			$count_users = count_users();
		}
	?>
	<div class="container clearfix">
		<div class="item">
			<i class="icon icon-source"></i>
			<p><span class="counter animateNum" data-animatetarget="<?php if(_MBT('home_total_no')) echo _MBT('total_posts')?_MBT('total_posts'):0;else echo ((int)_MBT('total_posts')+$count_posts->publish);?>"><?php if(_MBT('home_total_no')) echo _MBT('total_posts')?_MBT('total_posts'):0;else echo ((int)_MBT('total_posts')+$count_posts->publish);?></span></p>
			<h4><?php _e('总资源数','mobantu');?></h4>
		</div>
		<div class="item">
			<i class="icon icon-source-vip"></i>
			<p><span class="counter animateNum" data-animatetarget="<?php if(_MBT('home_total_no')) echo _MBT('total_vip_posts')?_MBT('total_vip_posts'):0;else echo ((int)_MBT('total_vip_posts')+MBThemes_count_vip_posts());?>"><?php if(_MBT('home_total_no')) echo _MBT('total_vip_posts')?_MBT('total_vip_posts'):0;else echo ((int)_MBT('total_vip_posts')+MBThemes_count_vip_posts());?></span></p>
			<h4><?php _e('VIP资源数','mobantu');?></h4>
		</div>
		<div class="item">
			<i class="icon icon-user"></i>
			<p><span class="counter animateNum" data-animatetarget="<?php if(_MBT('home_total_no')) echo _MBT('total_users')?_MBT('total_users'):0;else echo ((int)_MBT('total_users')+$count_users['total_users']);?>"><?php if(_MBT('home_total_no')) echo _MBT('total_users')?_MBT('total_users'):0;else echo ((int)_MBT('total_users')+$count_users['total_users']);?></span></p>
			<h4><?php _e('总用户数','mobantu');?></h4>
		</div>
		<div class="item">
			<i class="icon icon-user-follow"></i>
			<p><span class="counter animateNum" data-animatetarget="<?php if(_MBT('home_total_no')) echo _MBT('total_vip_users')?_MBT('total_vip_users'):0;else echo ((int)_MBT('total_vip_users')+MBThemes_count_vip_users());?>"><?php if(_MBT('home_total_no')) echo _MBT('total_vip_users')?_MBT('total_vip_users'):0;else echo ((int)_MBT('total_vip_users')+MBThemes_count_vip_users());?></span></p>
			<h4><?php _e('VIP用户数','mobantu');?></h4>
		</div>
	</div>
</div>