<?php 
	$erphp_quarter_price = get_option('erphp_quarter_price');
	$erphp_month_price  = get_option('erphp_month_price');
	$erphp_day_price  = get_option('erphp_day_price');
	$erphp_year_price    = get_option('erphp_year_price');
	$erphp_life_price  = get_option('erphp_life_price');
    $erphp_super_price  = get_option('erphp_super_price');
    $erphp_life_days    = get_option('erphp_life_days');
    $erphp_year_days    = get_option('erphp_year_days');
    $erphp_quarter_days = get_option('erphp_quarter_days');
    $erphp_month_days  = get_option('erphp_month_days');
    $erphp_day_days  = get_option('erphp_day_days');
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
<div class="vip-content">
    <div class="container">
        <h2><span><?php echo _MBT("home_vip_title","关于VIP");?></span></h2>
        <div class="home-vip-content clearfix">
            <?php if(_MBT('vip_no')){?>
            <div class="vip-item item-no">
                <h6><?php _e('普通用户','mobantu');?></h6>
                <span class="price"><?php _e('免费','mobantu');?></small></span>
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
</div>