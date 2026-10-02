<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------

if ( !defined('ABSPATH') ) {exit;}
date_default_timezone_set('Asia/Shanghai');
$total_money = 0; $issearch = 0;

if(isset($_GET['sort_user']) && $_GET['sort_user'] == 1){
	$total   = $wpdb->get_var("SELECT count(DISTINCT ice_user_id) FROM $wpdb->icealipay");

	$ice_perpage = 30;
	$pages = ceil($total / $ice_perpage);
	$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
	$offset = $ice_perpage*($page-1);

	$where = ' 1=1';
	if(isset($_GET['date_start']) && $_GET['date_start']){
		$where .= " and unix_timestamp(ice_time) >= unix_timestamp('".$_GET['date_start']."')";
	}
	if(isset($_GET['date_over']) && $_GET['date_over']){
		$where .= " and unix_timestamp(ice_time) <= unix_timestamp('".$_GET['date_over']."')";
	}

	$results = $wpdb->get_results("SELECT ice_user_id, COUNT(ice_id) AS purchase_count FROM $wpdb->icealipay WHERE $where GROUP BY ice_user_id ORDER BY purchase_count DESC limit $offset,$ice_perpage");
}else{
	$issearch = 0;
	if(isset($_GET['username']) && $_GET['username']){
		$user = get_user_by('login',$_GET['username']);
		if($user){
			$suid = $user->ID;
			$issearch = 1;
		}else{
			$suid = 0;
			echo '<div class="error settings-error"><p>用户不存在！</p></div>';
		}
		$total   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_user_id=".$suid." and ice_success=1");
		$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_user_id=".$suid." and ice_success=1");
	}elseif(isset($_GET['order']) && $_GET['order']){
		$issearch = 2;
		$ice_num = $_GET['order'];
		$total   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_num='".$ice_num."' and ice_success=1");
		$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_num='".$ice_num."' and ice_success=1");
	}elseif(isset($_GET['post']) && $_GET['post']){
		$issearch = 3;
		$ice_post = $_GET['post'];
		$total   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_post='".$ice_post."' and ice_success=1");
		$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_post='".$ice_post."' and ice_success=1");
	}else{
		$total   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success=1");
		$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success=1");
	}

	$ice_perpage = 30;
	$pages = ceil($total / $ice_perpage);
	$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
	$offset = $ice_perpage*($page-1);
	if($issearch == 1){
		$results = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_user_id=".$suid." and ice_success=1 order by ice_time DESC limit $offset,$ice_perpage");
	}elseif($issearch == 2){
		$results = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_num='".$ice_num."' and ice_success=1 order by ice_time DESC limit $offset,$ice_perpage");
	}elseif($issearch == 3){
		$results = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_post='".$ice_post."' and ice_success=1 order by ice_time DESC limit $offset,$ice_perpage");
	}else{
		$results = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 order by ice_time DESC limit $offset,$ice_perpage");
	}
}
?>
<div class="wrap">
	<h2>购买统计</h2>
	<p>所有购买文章的订单，未支付的订单在充值统计里的未支付订单里。<?php echo '合计 <strong>'.$total.'</strong> 单 '; printf(('<strong>%s</strong>'), $total_money); echo ' '.get_option('ice_name_alipay'); ?></p>
	<div class="tablenav top">
		<form method="get" style="float: left"><input type="hidden" name="page" value="erphpdown/admin/erphp-orders-list.php"><input type="text" name="username" placeholder="登录名，例如：admin" value="<?php if($issearch == 1) echo $_GET['username'];?>"><input type="text" name="post" placeholder="文章ID，例如：1" value="<?php if($issearch == 3) echo $_GET['post'];?>"><input type="text" name="order" placeholder="订单号" value="<?php if($issearch == 2) echo $_GET['order'];?>"><input type="submit" value="查询" class="button"></form>

		<form method="get" style="float: right;"><input type="hidden" name="page" value="erphpdown/admin/erphp-orders-list.php"><input type="hidden" name="sort_user" value="1"><input type="date" name="date_start" style="float: left;" placeholder="开始日期" value="<?php if(isset($_GET['date_start']) && $_GET['date_start']) echo $_GET['date_start'];?>"><input type="date" name="date_over" style="float: left;" placeholder="结束日期" value="<?php if(isset($_GET['date_over']) && $_GET['date_over']) echo $_GET['date_over'];?>"><input type="submit" value="查询用户购买量排行" class="button"></form>
	</div>
	<table class="widefat fixed striped posts">
		<?php if(isset($_GET['sort_user']) && $_GET['sort_user'] == 1){?>
		<thead>
			<tr>
				<th>用户ID</th>
				<th>购买次数</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if($results) {
				foreach($results as $value)
				{
					echo "<tr>\n";
					if($value->ice_user_id){
						$cu = get_user_by('id',$value->ice_user_id);
						echo "<td>".$cu->user_login."<span style='font-size:12px;color:#999'>（昵称：".$cu->nickname."）</span></td>";
					}else{
						echo "<td></td>";
					}
					echo "<td>$value->purchase_count</td>\n";
					echo "</tr>";
				}
			}
			?>
		</tbody>
		<?php }else{?>
		<thead>
			<tr>
				<th>商品名称</th>
				<th>用户ID</th>
				<th>订单号</th>
				<th>价格(<?php echo get_option('ice_name_alipay');?>)</th>
				<th>交易时间</th>	
				<th>管理</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if($results) {
				foreach($results as $value)
				{
					echo "<tr>\n";
					echo "<td><a target=_blank href='".get_permalink($value->ice_post)."'>".get_post($value->ice_post)->post_title;
					if($value->ice_index){
						$urls = get_post_meta($value->ice_post, 'down_urls', true);
				        if($urls){
				            $cnt = count($urls['index']);
				            if($cnt){
				                for($i=0; $i<$cnt;$i++){
				                    if($urls['index'][$i] == $value->ice_index){
				                        echo ' - '.$urls['name'][$i];
				                        break;
				                    }
				                }
				            }
				        }
					}
					echo "</a></td>\n";
					if($value->ice_user_id){
						$cu = get_user_by('id',$value->ice_user_id);
						echo "<td>".$cu->user_login."<span style='font-size:12px;color:#999'>（昵称：".$cu->nickname."）</span></td>";
					}else{
						echo "<td>游客<span style='font-size:12px;color:#999'>（IP：".$value->ice_ip."）</span></td>";
					}
					echo "<td>$value->ice_num";
					if(stripos($value->ice_num, 'FK') !== false){
						$ice_data=$wpdb->get_var("select ice_data from $wpdb->icemoney where ice_num='".$value->ice_num."'");
						if($ice_data){
							$ice_data = explode('|', trim($ice_data));
			    			$email = $ice_data[0];
			    			$num = $ice_data[1];
							echo '<span style="color:#ff5f33"><br>邮箱：'.$email.'<br>数量：'.$num.'</span>';
						}
					}elseif(stripos($value->ice_num, 'SW') !== false){
						$ice_data=$value->ice_note_other;
						if(!$ice_data){
							$ice_data=$wpdb->get_var("select ice_data from $wpdb->icemoney where ice_num='".$value->ice_num."'");
						}

						if($ice_data){
							$ice_data = explode('|', trim($ice_data));
			    			$addr = $ice_data[0];
			    			$num = $ice_data[1];
							echo '<span style="color:#ff5f33"><br>收件：'.$addr.'<br>数量：'.$num.'</span>';
						}
						if(isset($value->ice_note) && $value->ice_note){
							echo '<span style="color:blue"><br>快递：'.$value->ice_note.' <a href="javascript:;" class="swsend" data-id="'.$value->ice_id.'">修改快递</a></span>';
						}else{
							echo '<span style="color:#ff5f33"><br><a href="javascript:;" class="swsend" data-id="'.$value->ice_id.'">发快递</a></span>';
						}
					}
					echo "</td>";
					echo "<td>$value->ice_price</td>\n";
					echo "<td>$value->ice_time</td>\n";
					echo '<td>';
					echo '<a href="javascript:;" class="delorder" data-id="'.$value->ice_id.'">删除</a>';
					$down_activation = get_post_meta($value->ice_post, 'down_activation', true);
					if($down_activation && function_exists('doErphpAct') && !$value->ice_data){
						echo '&nbsp;&nbsp;<a href="javascript:;" class="fkorder" data-id="'.$value->ice_id.'" data-pid="'.$value->ice_post.'" data-uid="'.$value->ice_user_id.'">补发</a>';
					}
					echo '</td>';
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="5" align="center"><strong>没有交易记录</strong></td></tr>';
			}
			?>
		</tbody>
		<?php }?>
	</table>
	<?php echo erphp_admin_pagenavi($total,$ice_perpage);?>

</div>

<script>
	jQuery(".swsend").click(function(){
		var that = jQuery(this);
		var cid = jQuery(this).data("id");
	    var ptext = prompt('请输入快递名称+单号');
    	if(ptext){
    		that.text('发货中...');
		  	jQuery.post(
			ajaxurl,
			{
				note: ptext,
				cid: cid,
				action: "epd_order_sw_send"
			},
			function (data) {
				if(data.status == '1'){
					that.text('发货成功');
				}else{
					that.text('发快递');
				}
			},'json');
		}
	});
	

	jQuery(".delorder").click(function(){
		if(confirm('确定删除？')){
			var that = jQuery(this);
			that.text("删除中...");
			jQuery.ajax({
				type: "post",
				url: "<?php echo constant("erphpdown");?>admin/action/order.php",
				data: "do=delorder&id=" + jQuery(this).data("id"),
				dataType: "html",
				success: function (data) {
					if(jQuery.trim(data) == '1'){
						that.parent().parent().remove();
					}
				},
				error: function (request) {
					that.text("删除");
					alert("删除失败");
				}
			});
		}
	});

	jQuery(".fkorder").click(function(){
		if(confirm('确定给这单补发激活码/卡密？请确认这单确实是自动发卡失败！')){
			var that = jQuery(this);
			that.text("发补中...");
			jQuery.ajax({
				type: "post",
				url: "<?php echo constant("erphpdown");?>admin/action/order.php",
				data: "do=fkorder&id=" + jQuery(this).data("id")+"&pid=" + jQuery(this).data("pid")+"&uid=" + jQuery(this).data("uid"),
				dataType: "html",
				success: function (data) {
					if(jQuery.trim(data) == '1'){
						alert("补发成功");
						location.reload();
					}else{
						that.text("发补");
						alert(data);
					}
				},
				error: function (request) {
					that.text("发补");
					alert("发补失败");
				}
			});
		}
		return false;
	});
</script>
