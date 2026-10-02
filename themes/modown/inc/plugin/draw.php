<?php
add_action('admin_menu', 'erphp_draw_menu');
function erphp_draw_menu() {
	add_menu_page('抽奖', '抽奖', 'activate_plugins', 'erphp_draw_setting', 'erphp_draw_setting','dashicons-buddicons-tracking');
	add_submenu_page('erphp_draw_setting', '抽奖设置', '设置', 'activate_plugins', 'erphp_draw_setting','erphp_draw_setting');
	add_submenu_page('erphp_draw_setting', '抽奖记录', '记录', 'activate_plugins', 'erphp_draw_orders','erphp_draw_orders');
}

function erphp_draw_setting(){
	$erphpname = get_option('ice_name_alipay')?get_option('ice_name_alipay'):'货币';

	if(isset($_POST['draw'])){
		update_option('erphpdown_draw_price', trim($_POST['erphpdown_draw_price']));
		update_option('erphpdown_draw_percent', trim($_POST['erphpdown_draw_percent']));
		update_option('erphpdown_draw_one', trim($_POST['erphpdown_draw_one']));
		update_option('erphpdown_draw_one_gift', trim($_POST['erphpdown_draw_one_gift']));
		update_option('erphpdown_draw_two', trim($_POST['erphpdown_draw_two']));
		update_option('erphpdown_draw_two_gift', trim($_POST['erphpdown_draw_two_gift']));
		update_option('erphpdown_draw_three', trim($_POST['erphpdown_draw_three']));
		update_option('erphpdown_draw_three_gift', trim($_POST['erphpdown_draw_three_gift']));
		echo'<div class="updated settings-error"><p>设置成功！</p></div>';
	}

	$erphpdown_draw_price = get_option("erphpdown_draw_price");
	$erphpdown_draw_percent = get_option("erphpdown_draw_percent");
	$erphpdown_draw_one = get_option("erphpdown_draw_one");
	$erphpdown_draw_one_gift = get_option("erphpdown_draw_one_gift");
	$erphpdown_draw_two = get_option("erphpdown_draw_two");
	$erphpdown_draw_two_gift = get_option("erphpdown_draw_two_gift");
	$erphpdown_draw_three = get_option("erphpdown_draw_three");
	$erphpdown_draw_three_gift = get_option("erphpdown_draw_three_gift");
?>
	<div class="wrap">
 	<h1>Erphpdown抽奖设置</h1>
 	<form method="post" action="<?php echo admin_url('admin.php?page=erphp_draw_setting');?>">
 		<table class="form-table">
 			<tr>
 				<th valign="top">每次抽奖扣除<?php echo $erphpname;?></th>
 				<td>
 					<input type="text" id="erphpdown_draw_price" name="erphpdown_draw_price" value="<?php echo $erphpdown_draw_price;?>" class="regular-text" required=""/> 
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">中奖概率</th>
 				<td>
 					3/<input type="number" step="1" min="4" id="erphpdown_draw_percent" name="erphpdown_draw_percent" value="<?php echo $erphpdown_draw_percent;?>" class="regular-text" required="" style="width:80px;"/>
 					<p>请输入一个整数，例如：100，说明中奖概率是3/100，也就是3%</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">一等奖</th>
 				<td>
 					<select name="erphpdown_draw_one">
 						<option value="vip">奖励VIP</option>
 						<option value="money"<?php if($erphpdown_draw_one == 'money') echo ' selected';?>>奖励<?php echo $erphpname;?></option>
 					</select>
 					<input type="number" min="1" step="1" id="erphpdown_draw_one_gift" name="erphpdown_draw_one_gift" value="<?php echo $erphpdown_draw_one_gift;?>" class="regular-text" required=""/> 
 					<p>奖励VIP：体验VIP填6，包月VIP填7，包季VIP填8，包年VIP填9，终身VIP填10；<br>奖励<?php echo $erphpname;?>：填<?php echo $erphpname;?>数量即可</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">二等奖</th>
 				<td>
 					<select name="erphpdown_draw_two">
 						<option value="vip">奖励VIP</option>
 						<option value="money"<?php if($erphpdown_draw_two == 'money') echo ' selected';?>>奖励<?php echo $erphpname;?></option>
 					</select>
 					<input type="number" min="1" step="1" id="erphpdown_draw_two_gift" name="erphpdown_draw_two_gift" value="<?php echo $erphpdown_draw_two_gift;?>" class="regular-text" required=""/> 
 					<p>奖励VIP：体验VIP填6，包月VIP填7，包季VIP填8，包年VIP填9，终身VIP填10；<br>奖励<?php echo $erphpname;?>：填<?php echo $erphpname;?>数量即可</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">三等奖</th>
 				<td>
 					<select name="erphpdown_draw_three">
 						<option value="vip">奖励VIP</option>
 						<option value="money"<?php if($erphpdown_draw_three == 'money') echo ' selected';?>>奖励<?php echo $erphpname;?></option>
 					</select>
 					<input type="number" min="1" step="1" id="erphpdown_draw_three_gift" name="erphpdown_draw_three_gift" value="<?php echo $erphpdown_draw_three_gift;?>" class="regular-text" required=""/> 
 					<p>奖励VIP：体验VIP填6，包月VIP填7，包季VIP填8，包年VIP填9，终身VIP填10；<br>奖励<?php echo $erphpname;?>：填<?php echo $erphpname;?>数量即可</p>
 				</td>
 			</tr>
 		</table>
 		<p class="submit">
 			<input type="submit" name="draw" value="保存设置" class="button-primary"/>
 		</p>  
 	</form>
</div>
<?php
}

function erphp_draw_orders(){
	global $wpdb;
	if(isset($_POST['action'])){
		if($_POST['action'] == '7'){
			$wpdb->query("delete from ".$wpdb->prefix . "ice_draws WHERE status=0 and create_time < DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '3'){
			$wpdb->query("delete from ".$wpdb->prefix . "ice_draws WHERE status=0 and create_time < DATE_SUB(CURDATE(), INTERVAL 3 DAY)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '30'){
			$wpdb->query("delete from ".$wpdb->prefix . "ice_draws WHERE status=0 and create_time < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '180'){
			$wpdb->query("delete from ".$wpdb->prefix . "ice_draws WHERE status=0 and create_time < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '365'){
			$wpdb->query("delete from ".$wpdb->prefix . "ice_draws WHERE status=0 and create_time < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}
	}

	if(isset($_POST['delid']) && $_POST['delid'] > 0){
		$wpdb->query("delete from ".$wpdb->prefix . "ice_draws where ID=".$_POST['delid']);
	}
	$total   = $wpdb->get_var("SELECT COUNT(ID) FROM ".$wpdb->prefix."ice_draws");
	$perpage = 20;
	$pages = ceil($total / $perpage);
	$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
	$offset = $perpage*($page-1);
	$list = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ice_draws ORDER BY create_time DESC limit $offset,$perpage");
?>
<style>
.wp-list-table td{display: table-cell !important;word-break: break-all;}
@media (max-width: 768px){
	.wp-list-table td.pc, .wp-list-table th.pc{display: none !important;}
}
</style>
<div class="wrap">
	<h2>抽奖列表</h2>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>订单号</th>
				<th class="pc">用户ID</th>
				<th><?php echo get_option('ice_name_alipay');?></th>	
				<th class="pc">结果</th>
				<th>时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
	<?php
		if($list) {
			foreach($list as $value){
				echo "<tr id='order".$value->ID."'>\n";
				echo "<td>$value->num</td>";
				echo "<td class='pc'>".get_user_by("id",$value->user_id)->user_login."</td>\n";
				echo "<td>$value->price</td>";
				echo "<td class='pc'>";
				if($value->status == 1){
					echo '<span style="color:red">一等奖</span>';
				}elseif($value->status == 2){
					echo '<span style="color:red">二等奖</span>';
				}elseif($value->status == 3){
					echo '<span style="color:red">三等奖</span>';
				}else{
					echo '未中奖';
				}
				echo "</td>";
				echo "<td>$value->create_time</td>";
				echo '<td><form method="post"><input type="hidden" name="delid" value="'.$value->ID.'"><input type="submit" class="button" value="'.__('删除','mobantu').'"></form></td>';
				echo "</tr>";
			}
		}
		else{
			echo '<tr><td colspan="5" align="center"><strong>暂无记录</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php erphp_draw_pagination($total,$perpage);?>
    <form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="3"  />
		<input type="submit" value="清理三天之前所有未中奖记录" class="button-primary">
	</form>
    <form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="7"  />
		<input type="submit" value="清理一周之前所有未中奖记录" class="button-primary">
	</form>
	<form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="30"  />
		<input type="submit" value="清理一月之前所有未中奖记录" class="button-primary">
	</form>
	<form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="180"  />
		<input type="submit" value="清理半年之前所有未中奖记录" class="button-primary">
	</form>
	<form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="365"  />
		<input type="submit" value="清理一年之前所有未中奖记录" class="button-primary">
	</form>
</div>
<?php
}

function erphpdown_draw_install(){
	global $wpdb;
	$plate_table_name = $wpdb->prefix . "ice_draws";
	$plate_sql = "CREATE TABLE $plate_table_name (
	   ID int(11) NOT NULL auto_increment,
	   num varchar(16),
	   user_id int(11) NOT NULL,
	   price double(10,2) NOT NULL,
	   status int(2) default 0,
	   result varchar(50),
	   create_time datetime NOT NULL,
	   PRIMARY KEY (ID)
	);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($plate_sql);
}

add_shortcode('erphpdown_draw','erphpdown_shortcode_draw');
function erphpdown_shortcode_draw(){
	global $wpdb;
	$erphpdown_draw_one = get_option("erphpdown_draw_one");
	$erphpdown_draw_one_gift = get_option("erphpdown_draw_one_gift");
	$erphpdown_draw_two = get_option("erphpdown_draw_two");
	$erphpdown_draw_two_gift = get_option("erphpdown_draw_two_gift");
	$erphpdown_draw_three = get_option("erphpdown_draw_three");
	$erphpdown_draw_three_gift = get_option("erphpdown_draw_three_gift");

	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';

	$tip = '';$html = '';
	if($erphpdown_draw_one == 'vip'){
		if($erphpdown_draw_one_gift == 6) $typeName = $erphp_day_name;
		else {$typeName=$erphpdown_draw_one_gift==7 ?$erphp_month_name :($erphpdown_draw_one_gift==8 ?$erphp_quarter_name : ($erphpdown_draw_one_gift==10 ?$erphp_life_name : $erphp_year_name));}
		$tip .= '<i class="icon icon-gift"></i>'.__('一等奖：','mobantu').$typeName.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}elseif($erphpdown_draw_one == 'money'){
		$tip .= '<i class="icon icon-gift"></i>'.__('一等奖：','mobantu').$erphpdown_draw_one_gift.get_option('ice_name_alipay').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}

	if($erphpdown_draw_two == 'vip'){
		if($erphpdown_draw_two_gift == 6) $typeName = $erphp_day_name;
		else {$typeName=$erphpdown_draw_two_gift==7 ?$erphp_month_name :($erphpdown_draw_two_gift==8 ?$erphp_quarter_name : ($erphpdown_draw_two_gift==10 ?$erphp_life_name : $erphp_year_name));}
		$tip .= '<i class="icon icon-gift"></i>'.__('二等奖：','mobantu').$typeName.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}elseif($erphpdown_draw_two == 'money'){
		$tip .= '<i class="icon icon-gift"></i>'.__('二等奖：','mobantu').$erphpdown_draw_two_gift.get_option('ice_name_alipay').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}

	if($erphpdown_draw_three == 'vip'){
		if($erphpdown_draw_three_gift == 6) $typeName = $erphp_day_name;
		else {$typeName=$erphpdown_draw_three_gift==7 ?$erphp_month_name :($erphpdown_draw_three_gift==8 ?$erphp_quarter_name : ($erphpdown_draw_three_gift==10 ?$erphp_life_name : $erphp_year_name));}
		$tip .= '<i class="icon icon-gift"></i>'.__('三等奖：','mobantu').$typeName;
	}elseif($erphpdown_draw_three == 'money'){
		$tip .= '<i class="icon icon-gift"></i>'.__('三等奖：','mobantu').$erphpdown_draw_three_gift.get_option('ice_name_alipay');
	}

	$html = '
		<style>
			.banner-page{display:none}
			.single-content{background:transparent !important;}
			.erphpdown-draw{margin:0 auto}
			.rotate-title{font-size:19px;font-weight:600;text-align:center;margin-bottom:0;letter-spacing: 1px;}
			.rotate-desc{font-size:14px;text-align:center;opacity:.8}
			.ly-plate{position:relative;width:469px;height:469px;margin: 0 auto 10px;}
			.rotate-bg{width: 469px;height: 469px;background: url('.THEME_URI.'/static/img/ly-plate.png);background-size: cover;position: absolute;top: 0;left: 0;margin-top: 0%;}
			.ly-plate div.lottery-star{width:204px;height:204px;position:absolute;top:135px;left:132px;outline:none}
			.ly-plate div.lottery-star #lotteryBtn{cursor: pointer;position: absolute;top:0;left:0;*left:-107px}
			.rotate-tips{text-align:center;opacity:.8;font-size:13px;margin-top:20px;}
			@media (max-width: 640px){
				.ly-plate, .rotate-bg{width:309px;height:309px;}
				.ly-plate div.lottery-star{width:124px;height:124px;left:92.5px;top:95px;}
			}
		</style>
		<div class="erphpdown-draw">
			<div class="ly-plate">
				<div class="rotate-bg"></div>
				<div class="lottery-star"><img src="'.THEME_URI.'/static/img/rotate-static.png" id="lotteryBtn"></div>
			</div>
			<div class="rotate-title">'.get_option('erphpdown_draw_price',5).get_option('ice_name_alipay').__('一次','mobantu').'</div>
			<div class="rotate-tips">'.$tip.'</div>'; 
			$list = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ice_draws where status > 0 order by create_time DESC limit 20");
			if($list){
				$html .= '<div class="vip-logs" style="margin-bottom:0"><ul style="margin:0">';
				foreach($list as $order){
					if($order->status == 1) $typeName = __('一等奖','mobantu');
					else {$typeName=$order->status==2 ?__('二等奖','mobantu') : __('三等奖','mobantu');}
					$html .= '<li>'._mbt_substr_cut(get_the_author_meta( 'user_login', $order->user_id ));
					$html .= ' 刚刚抽中了 <i class="icon icon-gift"></i>'.$typeName.'</li>';
				}
				$html .= '</ul>
		    		<script>
						setInterval(function () {
						   	jQuery(".vip-logs>ul>li").eq(0).fadeOut("slow",function(){
						   		jQuery(this).clone().appendTo(jQuery(this).parent()).fadeIn("slow");
						   		jQuery(this).remove();
						   	});
						}, 3000);
					</script>
		    	</div>';
			}
	    		
		$html .= '</div>';
	return $html;
}

function epd_plate_callback(){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb, $current_user;
	$error = 0;$msg = '';$num='';
	$okMoney = erphpGetUserOkMoney();
	$price = get_option('erphpdown_draw_price')?get_option('erphpdown_draw_price'):5;
	$erphpdown_draw_percent = get_option("erphpdown_draw_percent")?get_option("erphpdown_draw_percent"):60;

	$status = 0;
	$rand_num = 0;

	if(_MBT('plugin_draw')){
		$rand_num = rand(1,$erphpdown_draw_percent);
		if($rand_num < 4){
			$status = $rand_num;
		}

		if($okMoney < $price){
			$error = 1;
			$msg = __('余额不足','mobantu');
		}else{
			addUserMoney($current_user->ID, '-'.$price);
			if(function_exists('addUserMoneyLog')){
	            addUserMoneyLog($current_user->ID, '-'.$price, __('抽奖扣除','mobantu'));
	        }
			$date = date("Y-m-d H:i:s");
			$num = date("ymdhis").mt_rand(1000,9999);
			$wpdb->query("insert into ".$wpdb->prefix."ice_draws (num,user_id,price,status,create_time) values('".$num."',".$current_user->ID.",'".$price."','".esc_sql($status)."','".$date."')");

			$erphpdown_draw_one = get_option("erphpdown_draw_one");
			$erphpdown_draw_one_gift = get_option("erphpdown_draw_one_gift");
			$erphpdown_draw_two = get_option("erphpdown_draw_two");
			$erphpdown_draw_two_gift = get_option("erphpdown_draw_two_gift");
			$erphpdown_draw_three = get_option("erphpdown_draw_three");
			$erphpdown_draw_three_gift = get_option("erphpdown_draw_three_gift");

			if($status){
				if($status == 1){
					addUserMoney($current_user->ID,'0');
					if($erphpdown_draw_one == 'vip'){
						if(userSetMemberSetData($erphpdown_draw_one_gift,$current_user->ID)){
							addVipLogByAdmin(0, $erphpdown_draw_one_gift, $current_user->ID);
						}
					}elseif($erphpdown_draw_one == 'money'){
						addUserMoney($current_user->ID, $erphpdown_draw_one_gift);
						if(function_exists('addUserMoneyLog')){
				            addUserMoneyLog($current_user->ID, $erphpdown_draw_one_gift, __('抽奖奖励','mobantu'));
				        }
					}
				}elseif($status == 2){
					addUserMoney($current_user->ID,'0');
					if($erphpdown_draw_two == 'vip'){
						if(userSetMemberSetData($erphpdown_draw_two_gift,$current_user->ID)){
							addVipLogByAdmin(0, $erphpdown_draw_two_gift, $current_user->ID);
						}
					}elseif($erphpdown_draw_two == 'money'){
						addUserMoney($current_user->ID, $erphpdown_draw_two_gift);
						if(function_exists('addUserMoneyLog')){
				            addUserMoneyLog($current_user->ID, $erphpdown_draw_two_gift, __('抽奖奖励','mobantu'));
				        }
					}
				}elseif($status == 3){
					addUserMoney($current_user->ID,'0');
					if($erphpdown_draw_three == 'vip'){
						if(userSetMemberSetData($erphpdown_draw_three_gift,$current_user->ID)){
							addVipLogByAdmin(0, $erphpdown_draw_three_gift, $current_user->ID);
						}
					}elseif($erphpdown_draw_three == 'money'){
						addUserMoney($current_user->ID, $erphpdown_draw_three_gift);
						if(function_exists('addUserMoneyLog')){
				            addUserMoneyLog($current_user->ID, $erphpdown_draw_three_gift, __('抽奖奖励','mobantu'));
				        }
					}
				}	
			}
		}
	}

	$arr=array(
		"error"=>$error, 
		"msg"=>$msg,
		"num"=>$num,
		"rand"=>$rand_num
	);
	$jarr=json_encode($arr); 

	echo $jarr;
	exit;
}
add_action( 'wp_ajax_epd_plate', 'epd_plate_callback');

function erphp_draw_pagination($total_count, $number_per_page=15){

	$current_page = isset($_GET['paged'])?$_GET['paged']:1;

	if(isset($_GET['paged'])){
		unset($_GET['paged']);
	}

	$base_url = add_query_arg($_GET,admin_url('admin.php'));

	$total_pages	= ceil($total_count/$number_per_page);

	$first_page_url	= $base_url.'&amp;paged=1';
	$last_page_url	= $base_url.'&amp;paged='.$total_pages;
	
	if($current_page > 1 && $current_page < $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;

		$next_page		= $current_page+1;
		$next_page_url	= $base_url.'&amp;paged='.$next_page;
	}elseif($current_page == 1){
		$prev_page_url	= '#';
		$first_page_url	= '#';
		if($total_pages > 1){
			$next_page		= $current_page+1;
			$next_page_url	= $base_url.'&amp;paged='.$next_page;
		}else{
			$next_page_url	= '#';
		}
	}elseif($current_page == $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;
		$next_page_url	= '#';
		$last_page_url	= '#';
	}
	?>
	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="displaying-num">每页 <?php echo $number_per_page;?> 共 <?php echo $total_count;?></span>
			<span class="pagination-links">
				<a class="first-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
				<a class="prev-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
				<span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
				<a class="next-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
				<a class="last-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
			</span>
		</div>
		<br class="clear">
	</div>
	<?php
}