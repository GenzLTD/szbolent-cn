<?php 
date_default_timezone_set('Asia/Shanghai');
if(!is_user_logged_in())
{
	wp_die('请登录系统');
}
global $wpdb;
if(isset($_POST['createerphpcard']) && $_POST['createerphpcard']){
	$price = esc_sql($_POST['erphpcard_price']);
	$num = esc_sql($_POST['erphpcard_num']);
	$days = esc_sql($_POST['erphpcard_days']);
	$cid = esc_sql($_POST['erphpcard_cid']);

	$dd = '';
	if($days){
		$dd = date("Y-m-d",strtotime("+".$days." day"));
	}

	$cat_vip_name = get_term_meta($cid,'cat_vip_name',true);

	$name = '包月VIP';
	if($price == 6) $name = '体验VIP';
	if($price == 8) $name = '包季VIP';
	elseif($price == 9) $name = '包年VIP';
	elseif($price == 10) $name = '终身VIP';

	$i=0;$out = '<br>生成的 <strong>'.$cat_vip_name.' '.$name.'</strong> 激活码如下：<br /><div>';
	for($i=0;$i < $num;$i++){
		$card = create_catvipguid();
		$password = wp_create_nonce(rand(10,1000));
		$result = $wpdb->query("insert into $wpdb->erphpcatvipcard (card,cid,usertype,createtime,endtime) values('".$card."','".$cid."','".$price."','".date("Y-m-d H:i:s")."','".$dd."')");
		$out .= $card.'<br />';
	}
	$out .='</div>';
	echo $out;
}

?>
<script type="text/javascript">
	function checkFm()
	{
		if(document.getElementById("erphpcard_num").value=="")
		{
			alert('请输入个数');
			return false;
		}
		if(document.getElementById("erphpcard_price").value=="")
		{
			alert('请选择VIP类型');
			return false;
		}
		
	}
</script>
<div class="wrap">
<?php if(!empty($text))
{
	echo '<div id="message">'.$text.'</div>';
} ?>
<h2 id="add-new-user"> 添加分类VIP激活码</h2>

<div id="ajax-response"></div>
<form action="" method="post" name="createerphpcard" id="createerphpcard" class="validate" onsubmit="return checkFm();">
<input name="action" type="hidden" value="createerphpcard">
<table class="form-table">
	<tbody>
    <tr class="form-field form-required">
		<th scope="row"><label for="erphpcard_name">个数 </label></th>
		<td><input name="erphpcard_num" type="number" id="erphpcard_num" min="1" step="1" value="1" aria-required="true" ></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row"><label for="erphpcard_name">分类VIP </label></th>
		<td><?php if(function_exists('MBT_erphp_vip_cat')){
			$cat_vips = get_terms('category', array(
			    'hide_empty' => false,
			    'meta_query' => array(
				    array(
				       'key'       => 'cat_vip',
				       'value'     => '1',
				       'compare'   => '='
				    )
				)
			) );
			if ( ! empty( $cat_vips ) && ! is_wp_error( $cat_vips ) ){
				foreach ( $cat_vips as $cat ) {
					$cat_vip_name = get_term_meta($cat->term_id,'cat_vip_name',true);
		?>
		<input type="radio" name="erphpcard_cid" id="erphpcard_cid<?php echo $cat->term_id;?>" value="<?php echo $cat->term_id;?>" checked /><label for="erphpcard_cid<?php echo $cat->term_id;?>"><?php echo $cat_vip_name;?></label>
		<?php } }
		}?></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row"><label for="erphpcard_price">VIP类型 </label></th>
		<td><input type="radio" id="erphpcard_price" name="erphpcard_price" value="10" checked />终身VIP会员 
				<input type="radio" id="erphpcard_price" name="erphpcard_price" value="9" />包年VIP会员 
				<input type="radio" id="erphpcard_price" name="erphpcard_price" value="8" />包季VIP会员 
				<input type="radio" id="erphpcard_price" name="erphpcard_price" value="7" />包月VIP会员
		</td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row"><label for="erphpcard_days">过期天数 </label></th>
		<td><input name="erphpcard_days" type="number" id="erphpcard_days" min="0" step="1" value="0" ><p>0表示不过期</p></td>
	</tr>
	</tbody>
</table>


<p class="submit"><input type="submit" name="createerphpcard" id="createerphpcardsub" class="button button-primary" value="添加"></p>
</form>
</div>
