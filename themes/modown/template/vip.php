<?php 
/*
	template name: VIP
	description: template for mobantu.com modown theme 
*/
get_header();
$erphp_life_days    = get_option('erphp_life_days');
$erphp_year_days    = get_option('erphp_year_days');
$erphp_quarter_days = get_option('erphp_quarter_days');
$erphp_month_days  = get_option('erphp_month_days');
$erphp_day_days  = get_option('erphp_day_days');
$erphp_quarter_price = get_option('erphp_quarter_price');
$erphp_month_price  = get_option('erphp_month_price');
$erphp_day_price  = get_option('erphp_day_price');
$erphp_year_price    = get_option('erphp_year_price');
$erphp_life_price  = get_option('erphp_life_price');
$erphp_super_price  = get_option('erphp_super_price');
$erphp_super_name    = get_option('erphp_super_name')?get_option('erphp_super_name'):'全站通VIP';
$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
$moneyVipName = get_option('ice_name_alipay');
if(_MBT('vip_only_pay') && _MBT('vip_only_pay_rmb')){
	if($erphp_super_price) $erphp_super_price = $erphp_super_price/get_option('ice_proportion_alipay');
	if($erphp_life_price) $erphp_life_price = $erphp_life_price/get_option('ice_proportion_alipay');
	if($erphp_year_price) $erphp_year_price = $erphp_year_price/get_option('ice_proportion_alipay');
	if($erphp_quarter_price) $erphp_quarter_price = $erphp_quarter_price/get_option('ice_proportion_alipay');
	if($erphp_month_price) $erphp_month_price = $erphp_month_price/get_option('ice_proportion_alipay');
	if($erphp_day_price) $erphp_day_price = $erphp_day_price/get_option('ice_proportion_alipay');
	$moneyVipName = '元';
}
$vip_update_pay = 0;$oldUserType = 0;
if(get_option('vip_update_pay') && is_user_logged_in()){
	$vip_update_pay = 1;
	global $current_user;
	$oldUserType = getUsreMemberTypeById($current_user->ID);
}
?>
<style><?php if(_MBT('vip_bg2')){?>body.page-template-vip .main{background-image: url(<?php echo _MBT('vip_bg2');?>);}<?php }?><?php if(_MBT('vip_why') || _MBT('vip_faq')){?>body.page-template-vip .main{padding-bottom: 0;}<?php }?></style>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container">
		<div class="content-wrap clearfix">
	    	<div class="content">
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article class="single-content">
	    			<header class="article-header">
		    			<h1 class="article-title center"><?php the_title(); ?></h1>
		    			<div class="vip-desc"><?php echo _MBT('vip_desc');?></div>
		    		</header>
		    		<div class="article-content">
		    			<?php the_content();?>
		    		</div>
		    		<div class="vip-content">
		    			<div class="page-vip-content clearfix">
			    			<?php if(_MBT('vip_no')){?>
			                <div class="vip-item item-no">
			                    <h6><?php _e('普通用户','mobantu');?></h6>
			                    <span class="price"><?php _e('免费','mobantu');?></span>
			                    <p class="border-decor no"><span></span></p>
			                    <?php echo _MBT('vip_no');?>
			                	<a href="javascript:;" class="btn btn-small disabled"><?php _e('立即升级','mobantu');?></a>
			                </div>  
			            	<?php }?>

			    			<?php if($erphp_day_price){?>
			                <div class="vip-item item-0">
			                    <h6><?php echo $erphp_day_name;?></h6>
			                    <span class="price"><?php echo $erphp_day_price;?><small><?php echo $moneyVipName;?></small></span>
			                    <p class="border-decor"><span><?php echo sprintf(__('%s天','mobantu'), $erphp_day_days?$erphp_day_days:'1'); ?></span></p>
			                    <?php echo _MBT('vip_day');?>
			                    <?php if(is_user_logged_in() || get_option('erphp_wppay_vip')){?>
			                    <a href="javascript:;" data-type="6" class="btn btn-small btn-vip-action"><?php _e('立即升级','mobantu');?></a>
			                	<?php }else{?>
			                	<a href="javascript:;" class="btn btn-small signin-loader"><?php _e('立即升级','mobantu');?></a>
			                	<?php }?>
			                	<?php if(_MBT('vip_recommend') == '6'){?><div class="vip-tips-box"><span><?php _e('<em>75%</em>的人选择该套餐','mobantu');?></span></div><?php }?>
			                </div>  
			            	<?php }?>

			    			<?php if($erphp_month_price){$canbu = 0;?>
			                <div class="vip-item item-1">
			                    <h6><?php echo $erphp_month_name;?></h6>
			                    <span class="price"><?php
				                    if($vip_update_pay){
				                    	if($oldUserType == 6 && $erphp_day_price){
				                    		$canbu = 1;
				                    		echo '<del>'.$erphp_month_price.'</del>';
				                     		echo $erphp_month_price - $erphp_day_price;
				                     	}else{
				                     		echo $erphp_month_price;
				                     	}
				                    }else{
				                     	echo $erphp_month_price;
				                    }
			             		?><small><?php echo $moneyVipName;?></small></span>
			                    <p class="border-decor"><span><?php echo sprintf(__('%s天','mobantu'), $erphp_month_days?$erphp_month_days:'30'); ?></span></p>
			                    <?php echo _MBT('vip_month');?>
			                    <?php if(is_user_logged_in() || get_option('erphp_wppay_vip')){?>
			                    <a href="javascript:;" data-type="7" class="btn btn-small btn-vip-action"><?php _e('立即升级','mobantu');?></a>
			                	<?php }else{?>
			                	<a href="javascript:;" class="btn btn-small signin-loader"><?php _e('立即升级','mobantu');?></a>
			                	<?php }?>
			                	<?php if($canbu) echo '<span class="buca">'.__('补差价','mobantu').'</span>';?>
			                	<?php if(_MBT('vip_recommend') == '7'){?><div class="vip-tips-box"><span><?php _e('<em>75%</em>的人选择该套餐','mobantu');?></span></div><?php }?>
			                </div>  
			            	<?php }?>

			            	<?php if($erphp_quarter_price){$canbu = 0;?>
			                <div class="vip-item item-2">
			                    <h6><?php echo $erphp_quarter_name;?></h6>
			                    <span class="price"><?php
				                    if($vip_update_pay){
				                    	if($oldUserType == 6 && $erphp_day_price){
				                    		$canbu = 1;
				                    		echo '<del>'.$erphp_quarter_price.'</del>';
				                     		echo $erphp_quarter_price - $erphp_day_price;
				                     	}elseif($oldUserType == 7 && $erphp_month_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_quarter_price.'</del>';
				                     		echo $erphp_quarter_price - $erphp_month_price;
				                     	}else{
				                     		echo $erphp_quarter_price;
				                     	}
				                    }else{
				                     	echo $erphp_quarter_price;
				                    }
			             		?><small><?php echo $moneyVipName;?></small></span>
			                    <p class="border-decor"><span><?php echo sprintf(__('%s个月','mobantu'), $erphp_quarter_days?$erphp_quarter_days:'3'); ?></span></p>
			                    <?php echo _MBT('vip_quarter');?>
			                    <?php if(is_user_logged_in() || get_option('erphp_wppay_vip')){?>
			                    <a href="javascript:;" data-type="8" class="btn btn-small btn-vip-action"><?php _e('立即升级','mobantu');?></a>
			                	<?php }else{?>
			                	<a href="javascript:;" class="btn btn-small signin-loader"><?php _e('立即升级','mobantu');?></a>
			                	<?php }?>
			                	<?php if($canbu) echo '<span class="buca">'.__('补差价','mobantu').'</span>';?>
			                	<?php if(_MBT('vip_recommend') == '8'){?><div class="vip-tips-box"><span><?php _e('<em>75%</em>的人选择该套餐','mobantu');?></span></div><?php }?>
			                </div>  
			            	<?php }?>

			            	<?php if($erphp_year_price){$canbu = 0;?>
			                <div class="vip-item item-3">
			                    <h6><?php echo $erphp_year_name;?></h6>
			                    <span class="price"><?php
				                    if($vip_update_pay){
				                    	if($oldUserType == 6 && $erphp_day_price){
				                    		$canbu = 1;
				                    		echo '<del>'.$erphp_year_price.'</del>';
				                     		echo $erphp_year_price - $erphp_day_price;
				                     	}elseif($oldUserType == 7 && $erphp_month_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_year_price.'</del>';
				                     		echo $erphp_year_price - $erphp_month_price;
				                     	}elseif($oldUserType == 8 && $erphp_quarter_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_year_price.'</del>';
				                     		echo $erphp_year_price - $erphp_quarter_price;
				                     	}else{
				                     		echo $erphp_year_price;
				                     	}
				                    }else{
				                     	echo $erphp_year_price;
				                    }
			             		?><small><?php echo $moneyVipName;?></small></span>
			                    <p class="border-decor"><span><?php echo sprintf(__('%s个月','mobantu'), $erphp_year_days?$erphp_year_days:'12'); ?></span></p>
			                    <?php echo _MBT('vip_year');?>
			                    <?php if(is_user_logged_in() || get_option('erphp_wppay_vip')){?>
			                    <a href="javascript:;" data-type="9" class="btn btn-small btn-vip-action"><?php _e('立即升级','mobantu');?></a>
			                	<?php }else{?>
			                	<a href="javascript:;" class="btn btn-small signin-loader"><?php _e('立即升级','mobantu');?></a>
			                	<?php }?>
			                	<?php if($canbu) echo '<span class="buca">'.__('补差价','mobantu').'</span>';?>
			                	<?php if(_MBT('vip_recommend') == '9'){?><div class="vip-tips-box"><span><?php _e('<em>75%</em>的人选择该套餐','mobantu');?></span></div><?php }?>
			                </div>  
			            	<?php }?>

			            	<?php if($erphp_life_price){$canbu = 0;?>
			                <div class="vip-item item-4">
			                    <h6><?php echo $erphp_life_name;?></h6>
			                    <span class="price"><?php
				                    if($vip_update_pay){
				                    	if($oldUserType == 6 && $erphp_day_price){
				                    		$canbu = 1;
				                    		echo '<del>'.$erphp_life_price.'</del>';
				                     		echo $erphp_life_price - $erphp_day_price;
				                     	}elseif($oldUserType == 7 && $erphp_month_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_life_price.'</del>';
				                     		echo $erphp_life_price - $erphp_month_price;
				                     	}elseif($oldUserType == 8 && $erphp_quarter_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_life_price.'</del>';
				                     		echo $erphp_life_price - $erphp_quarter_price;
				                     	}elseif($oldUserType == 9 && $erphp_year_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_life_price.'</del>';
				                     		echo $erphp_life_price - $erphp_year_price;
				                     	}else{
				                     		echo $erphp_life_price;
				                     	}
				                    }else{
				                     	echo $erphp_life_price;
				                    }
			             		?><small><?php echo $moneyVipName;?></small></span>
			                    <p class="border-decor"><span><?php echo $erphp_life_days?(sprintf(__('%s年','mobantu'), $erphp_life_days)):__('永久','mobantu');?></span></p>
			                    <?php echo _MBT('vip_life');?>
			                    <?php if(is_user_logged_in() || get_option('erphp_wppay_vip')){?>
			                    <a href="javascript:;" data-type="10" class="btn btn-small btn-vip-action"><?php _e('立即升级','mobantu');?></a>
			                	<?php }else{?>
			                	<a href="javascript:;" class="btn btn-small signin-loader"><?php _e('立即升级','mobantu');?></a>
			                	<?php }?>
			                	<?php if($canbu) echo '<span class="buca">'.__('补差价','mobantu').'</span>';?>
			                	<?php if(_MBT('vip_recommend') == '10'){?><div class="vip-tips-box"><span><?php _e('<em>75%</em>的人选择该套餐','mobantu');?></span></div><?php }?>
			                </div>  
			            	<?php }?>

			            	<?php if($erphp_super_price){$canbu = 0;?>
			                <div class="vip-item item-5">
			                    <h6><?php echo $erphp_super_name;?></h6>
			                    <span class="price"><?php
				                    if($vip_update_pay){
				                    	if($oldUserType == 6 && $erphp_day_price){
				                    		$canbu = 1;
				                    		echo '<del>'.$erphp_super_price.'</del>';
				                     		echo $erphp_super_price - $erphp_day_price;
				                     	}elseif($oldUserType == 7 && $erphp_month_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_super_price.'</del>';
				                     		echo $erphp_super_price - $erphp_month_price;
				                     	}elseif($oldUserType == 8 && $erphp_quarter_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_super_price.'</del>';
				                     		echo $erphp_super_price - $erphp_quarter_price;
				                     	}elseif($oldUserType == 9 && $erphp_year_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_super_price.'</del>';
				                     		echo $erphp_super_price - $erphp_year_price;
				                     	}elseif($oldUserType == 10 && $erphp_life_price){
				                     		$canbu = 1;
				                     		echo '<del>'.$erphp_super_price.'</del>';
				                     		echo $erphp_super_price - $erphp_life_price;
				                     	}else{
				                     		echo $erphp_super_price;
				                     	}
				                    }else{
				                     	echo $erphp_super_price;
				                    }
			             		?><small><?php echo $moneyVipName;?></small></span>
			                    <p class="border-decor"><span><?php echo $erphp_life_days?(sprintf(__('%s年','mobantu'), $erphp_life_days)):__('永久','mobantu');?></span></p>
			                    <?php echo _MBT('vip_super');?>
			                    <?php if(is_user_logged_in() || get_option('erphp_wppay_vip')){?>
			                    <a href="javascript:;" data-type="11" class="btn btn-small btn-vip-action"><?php _e('立即升级','mobantu');?></a>
			                	<?php }else{?>
			                	<a href="javascript:;" class="btn btn-small signin-loader"><?php _e('立即升级','mobantu');?></a>
			                	<?php }?>
			                	<?php if($canbu) echo '<span class="buca">'.__('补差价','mobantu').'</span>';?>
			                	<?php if(_MBT('vip_recommend') == '11'){?><div class="vip-tips-box"><span><?php _e('<em>75%</em>的人选择该套餐','mobantu');?></span></div><?php }?>
			                </div>  
			            	<?php }?>
						</div>
		            </div>
		    		<?php endwhile;  ?>

		    		<?php 
		    			if(function_exists('getUsreMemberCat')){
		    				$moneyVipName = get_option('ice_name_alipay');
		    			$cat_vips = get_terms('category', array(
						    'hide_empty' => false,
						    'parent' => '0',
						    'meta_query' => array(
							    array(
							       'key'       => 'cat_vip',
							       'value'     => '1',
							       'compare'   => '='
							    )
							)
						) );
						if ( ! empty( $cat_vips ) && ! is_wp_error( $cat_vips ) ){
					?>
					<h2 class="article-title center"><?php _e('分类VIP','mobantu');?></h2>
		    		<div class="vip-desc"><?php _e('术业有专攻，多种VIP任你选','mobantu');?></div>
		    		<div class="vip-content">
		    			<div class="page-vip-content clearfix">
						<?php
							foreach ( $cat_vips as $cat ) {
								$cat_vip_name = get_term_meta($cat->term_id,'cat_vip_name',true);
								$cat_vip_desc = get_term_meta($cat->term_id,'cat_vip_desc',true);
								$cat_vip_month = get_term_meta($cat->term_id,'cat_vip_month',true);
								$cat_vip_quarter = get_term_meta($cat->term_id,'cat_vip_quarter',true);
								$cat_vip_year = get_term_meta($cat->term_id,'cat_vip_year',true);
								$cat_vip_life = get_term_meta($cat->term_id,'cat_vip_life',true);
						?>
							<div class="vip-item item-0">
			                    <a href="<?php echo get_term_link($cat);?>" target="_blank"><h6><?php echo $cat_vip_name?$cat_vip_name:$cat->name;?></h6></a>
			                    <span class="price"></span>
			                    <p class="border-decor border-decor-cat"><span><a href="<?php echo get_term_link($cat);?>" target="_blank"><i class="icon icon-cat"></i> <?php echo $cat->name;?></a></span></p>
			                    <?php if($cat_vip_desc) echo '<p class="vip-cat-desc">'.$cat_vip_desc.'</p>';?>
			                    <ul>
			                    	<?php 
			                    		if($cat_vip_month){ 
			                    			echo '<li><input type="radio" name="catvip'.$cat->term_id.'" id="catvip'.$cat->term_id.'-month" value="7" checked> <label for="catvip'.$cat->term_id.'-month">'.$erphp_month_name.' - '.$cat_vip_month.$moneyVipName.'</label></li>';
			                    		}
			                    		if($cat_vip_quarter){ 
			                    			echo '<li><input type="radio" name="catvip'.$cat->term_id.'" id="catvip'.$cat->term_id.'-quarter" value="8" checked> <label for="catvip'.$cat->term_id.'-quarter">'.$erphp_quarter_name.' - '.$cat_vip_quarter.$moneyVipName.'</label></li>';
			                    		}
			                    		if($cat_vip_year){ 
			                    			echo '<li><input type="radio" name="catvip'.$cat->term_id.'" id="catvip'.$cat->term_id.'-year" value="9" checked> <label for="catvip'.$cat->term_id.'-year">'.$erphp_year_name.' - '.$cat_vip_year.$moneyVipName.'</label></li>';
			                    		}
			                    		if($cat_vip_life){ 
			                    			echo '<li><input type="radio" name="catvip'.$cat->term_id.'" id="catvip'.$cat->term_id.'-life" value="10" checked> <label for="catvip'.$cat->term_id.'-life">'.$erphp_life_name.' - '.$cat_vip_life.$moneyVipName.'</label></li>';
			                    		}
			                    	?>
			                    </ul>
			                    <?php if(is_user_logged_in()){?>
			                    <a href="javascript:;" data-cat="<?php echo $cat->term_id;?>" class="btn btn-small btn-vipcat-action"><?php _e('立即升级','mobantu');?></a>
			                	<?php }else{?>
			                	<a href="javascript:;" class="btn btn-small signin-loader"><?php _e('立即升级','mobantu');?></a>
			                	<?php }?>
			                </div>
						<?php
							}
						?>
						</div>
		    		</div>
					<?php
						}
						}
		    		?>
	            </article>
	    	</div>
	    	<?php if(_MBT('vip_order_scroll')){?>
	    	<div class="vip-logs">
	    		<ul>
	    			<?php 
	    			$list = $wpdb->get_results("SELECT * FROM $wpdb->vip order by ice_time DESC limit 20");
	    			if($list){
	    				foreach($list as $order){
	    					if($order->ice_user_type == 6) $typeName = $erphp_day_name;
							else {$typeName=$order->ice_user_type==7 ?$erphp_month_name :($order->ice_user_type==8 ?$erphp_quarter_name : ($order->ice_user_type==10 ?$erphp_life_name : $erphp_year_name));}
	    					echo '<li>';
	    					if($order->ice_user_id){
								echo _mbt_substr_cut(get_the_author_meta( 'user_login', $order->ice_user_id ));
							}else{
								echo __('游客','mobantu')._mbt_substr_cut($order->ice_ip);
							}
	    					echo __(' 升级了 ','mobantu').$typeName.'</li>';
	    				}
	    			}
	    			?>
	    		</ul>
	    		<script>
					setInterval(function () {
					   	jQuery(".vip-logs>ul>li").eq(0).fadeOut("slow",function(){
					   		jQuery(this).clone().appendTo(jQuery(this).parent()).fadeIn("slow");
					   		jQuery(this).remove();
					   	});
					}, 3000);
				</script>
	    	</div>
	    	<?php }?>
	    </div>
	</div>
	<?php if(_MBT('vip_why')) get_template_part("module/why");?>
	<?php if(_MBT('vip_faq')){?>
	<div class="vip-faqs">
		<div class="container">
			<h2><span><?php _e('常见问题','mobantu');?></span></h2>
			<div class="items">
				<?php
					$sort = '1 2 3 4 5';
		            $sort = array_unique(explode(' ', trim($sort)));
		            foreach ($sort as $key => $value) {
		                if( _MBT('vip_faq_question'.$value) ){
		                    echo '<div class="item"><h5>'._MBT('vip_faq_question'.$value).'</h5><div class="ans">'._MBT('vip_faq_answer'.$value).'</div></div>';
		                }
		            }
				?>
			</div>
		</div>
	</div>
	<?php }?>
</div>
<?php get_footer();?>