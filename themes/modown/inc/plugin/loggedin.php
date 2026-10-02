<?php
class Erphp_Loggedin{

	public function __construct() {
		add_filter( 'wp_authenticate_user', array( $this, 'validate' ) );
		add_action( 'wp_login', array($this, 'login') );
		add_action('user_register', array($this, 'register'));
		add_action( 'init', array($this, 'logged') );
	}

	public function logged(){
		if(is_user_logged_in()){
			global $wpdb, $current_user;
			if(!user_can($current_user, 'edit_theme_options')){
				$erphp_loggedin = get_user_meta($current_user->ID,'erphp_loggedin',true);
				if($erphp_loggedin){
					wp_logout();
					wp_safe_redirect( home_url().'?erphp_loggedin='.$erphp_loggedin );
					exit();
				}else{
					$maximum = get_option( 'erphp_loggedin_max' );
					if($maximum){
						$manager = WP_Session_Tokens::get_instance( $current_user->ID );
						$loggedins = $manager->get_all();
						$ip_arr = array();
						foreach($loggedins as $loggedin){
							//$ip_arr[] = $loggedin['ip'];
							$ip = $loggedin['ip'];
							$arr=explode(".",$ip);
							if(count($arr) > 2){
								$ip3=$arr[0].".".$arr[1].".".$arr[2];
								$ip_arr[] = $ip3;
							}else{
								$ip_arr[] = $ip;
							}
						}
						$count = count(array_unique($ip_arr));
						if($count > $maximum){
							update_user_meta($current_user->ID, 'erphp_loggedin', '1');
							wp_logout();
							wp_safe_redirect( home_url().'?erphp_loggedin=1' );
							exit();
						}
					}

					$erphp_loggedin_time = get_option('erphp_loggedin_time');
					$erphp_loggedin_time_max = get_option('erphp_loggedin_time_max');
					if($erphp_loggedin_time && $erphp_loggedin_time_max){

						$loggedins2 = $wpdb->get_results("select DISTINCT logged_ip from ".$wpdb->prefix."erphp_loggedin where user_id='".$current_user->ID."' and logged_at >= SUBDATE(NOW(), INTERVAL ".$erphp_loggedin_time." HOUR)");
						$result = 0;
						if($loggedins2){
							$ip_arr = array();
							foreach($loggedins2 as $loggedin){
								$ip = $loggedin->logged_ip;
								$arr=explode(".",$ip);
								if(count($arr) > 2){
									$ip3=$arr[0].".".$arr[1].".".$arr[2];
									$ip_arr[] = $ip3;
								}else{
									$ip_arr[] = $ip;
								}
							}
							$result = count(array_unique($ip_arr));
						}

						if($result > $erphp_loggedin_time_max){
							update_user_meta($current_user->ID, 'erphp_loggedin', '2');
							wp_logout();
							wp_safe_redirect( home_url().'?erphp_loggedin=2' );
							exit();
						}
					}
					
				}
			}
		}
	}

	public function register($user_id, $password="", $meta=array()){
		$logged_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_sql($_SERVER['REMOTE_ADDR']);
		add_user_meta($user_id, 'erphp_loggedin_ip', $logged_ip);
	}

	public function login($user_login){
		date_default_timezone_set('Asia/Shanghai');
		global $wpdb;
		$userdata = get_user_by('login', $user_login);
        $user_id = ($userdata && $userdata->ID) ? $userdata->ID : 0;
        $logged_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_sql($_SERVER['REMOTE_ADDR']);
        $wpdb->query("insert into ".$wpdb->prefix."erphp_loggedin (user_id,logged_ip,logged_at) values ('".$user_id."', '".$logged_ip."', '".date("Y-m-d H:i:s")."')");
	}

	public function validate( $user ) {
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		if(!user_can($user, 'edit_theme_options')){
			$erphp_loggedin = get_user_meta($user->ID,'erphp_loggedin',true);
			if($erphp_loggedin){
				return new WP_Error( 'loggedin_reached_limit', get_option("erphp_loggedin_msg") );
			}else{
				if ( $this->reached_limit( $user->ID ) ) {
					update_user_meta($user->ID, 'erphp_loggedin', '1');
					return new WP_Error( 'loggedin_reached_limit', get_option("erphp_loggedin_msg") );
					// Sessions token instance.
					//$manager = WP_Session_Tokens::get_instance( $user->ID );
					// Destroy all others.
					//$manager->destroy_all();
				}elseif($this->reached_limit_time($user->ID)){
					update_user_meta($user->ID, 'erphp_loggedin', '2');
					return new WP_Error( 'loggedin_reached_limit', get_option("erphp_loggedin_msg") );
				}
			}
		}
		

		return $user;
	}

	private function reached_limit_time( $user_id ){
		date_default_timezone_set('Asia/Shanghai');
		global $wpdb;
		$logged_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_sql($_SERVER['REMOTE_ADDR']);
		$erphp_loggedin_time = get_option('erphp_loggedin_time');
		$erphp_loggedin_time_max = get_option('erphp_loggedin_time_max');
		if($erphp_loggedin_time && $erphp_loggedin_time_max){
			$loggedins = $wpdb->get_results("select DISTINCT logged_ip from ".$wpdb->prefix."erphp_loggedin where user_id='".$user_id."' and logged_at >= SUBDATE(NOW(), INTERVAL ".$erphp_loggedin_time." HOUR)");
			$result = 0;
			if($loggedins){
				$ip_arr = array();
				foreach($loggedins as $loggedin){
					$ip = $loggedin->logged_ip;
					$arr=explode(".",$ip);
					$ip3=$arr[0].".".$arr[1].".".$arr[2];
					$ip_arr[] = $ip3;
				}
				$result = count(array_unique($ip_arr));
			}

			if($result > $erphp_loggedin_time_max){
				return true;
			}elseif($result == $erphp_loggedin_time_max){
				$check_current = $wpdb->get_var("select id from ".$wpdb->prefix."erphp_loggedin where user_id='".$user_id."' and logged_ip='".$logged_ip."' and logged_at >= SUBDATE(NOW(), INTERVAL ".$erphp_loggedin_time." HOUR)");
				//if(!$check_current){
				//	return true;
				//}
			}
		}
		return false;
	}

	private function reached_limit( $user_id ) {
		//$logged_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_sql($_SERVER['REMOTE_ADDR']);
		$maximum = get_option( 'erphp_loggedin_max' );
		if($maximum){
			$manager = WP_Session_Tokens::get_instance( $user_id );
			$loggedins = $manager->get_all();
			$ip_arr = array();
			foreach($loggedins as $loggedin){
				//$ip_arr[] = $loggedin['ip'];
				$ip = $loggedin['ip'];
				$arr=explode(".",$ip);
				$ip3=$arr[0].".".$arr[1].".".$arr[2];
				$ip_arr[] = $ip3;
			}
			$count = count(array_unique($ip_arr));

			return $count > $maximum;
		}
		return false;
	}

}

new Erphp_Loggedin();

function erphp_loggedin_install(){
	global $wpdb;
	$table = $wpdb->prefix."erphp_loggedin";

	if( $wpdb->get_var("show tables like '{$table}'") != $table ) {
		$wpdb->query("CREATE TABLE {$table} (
			id      BIGINT(20) NOT NULL AUTO_INCREMENT,
			user_id int(10) NOT NULL,
			logged_ip VARCHAR(100),
			logged_at datetime NOT NULL,
			UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
	}
}

add_action('admin_menu', 'erphp_loggedin_menu');
function erphp_loggedin_menu() {
	add_menu_page('Loggedin', 'Loggedin', 'activate_plugins', 'erphp_loggedin_setting', 'erphp_loggedin_setting','dashicons-lock');
	add_submenu_page('erphp_loggedin_setting', 'Loggedin设置', '设置', 'activate_plugins', 'erphp_loggedin_setting','erphp_loggedin_setting');
	add_submenu_page('erphp_loggedin_setting', 'Loggedin记录', '记录', 'activate_plugins', 'erphp_loggedin_logs','erphp_loggedin_logs');
}

function erphp_loggedin_setting(){
    if(isset($_POST['loggedin'])){
		update_option('erphp_loggedin_max', trim($_POST['erphp_loggedin_max']));
		update_option('erphp_loggedin_time', trim($_POST['erphp_loggedin_time']));
		update_option('erphp_loggedin_time_max', trim($_POST['erphp_loggedin_time_max']));
		update_option('erphp_loggedin_msg', trim($_POST['erphp_loggedin_msg']));
		update_option('erphp_loggedin_tips', $_POST['erphp_loggedin_tips']);
		echo'<div class="updated settings-error"><p>更新成功！</p></div>';
	}
	if(isset($_POST['del_loggedin'])){
		global $wpdb;
		$wpdb->query("delete from $wpdb->usermeta where meta_key='erphp_loggedin'");
		echo'<div class="updated settings-error"><p>原谅成功！</p></div>';
	}
	$erphp_loggedin_max = get_option("erphp_loggedin_max");
	$erphp_loggedin_time = get_option("erphp_loggedin_time");
	$erphp_loggedin_time_max = get_option("erphp_loggedin_time_max");
	$erphp_loggedin_msg = get_option("erphp_loggedin_msg");
	$erphp_loggedin_tips = get_option("erphp_loggedin_tips");

?>
<div class="wrap">
 	<h1>Loggedin设置</h1>
 	<form method="post" action="<?php echo admin_url('admin.php?page=erphp_loggedin_setting');?>">
 		<table class="form-table">
 			<tr>
 				<th valign="top">同时登录在线IP数 *</th>
 				<td>
 					<input type="number" step="1" min="0" id="erphp_loggedin_max" name="erphp_loggedin_max" value="<?php echo $erphp_loggedin_max?$erphp_loggedin_max:0;?>" class="regular-text" required=""/> 
 					<p class="description">超过此数立即禁封，0表示不限制，建议填5以上，一般的用户都会多点登录</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">限定时间内登录IP数</th>
 				<td>
 					<input type="number" id="erphp_loggedin_time" name="erphp_loggedin_time" value="<?php echo $erphp_loggedin_time;?>" class="regular-text" style="width:150px"/> 小时内最多
 					<input type="number" id="erphp_loggedin_time_max" name="erphp_loggedin_time_max" value="<?php echo $erphp_loggedin_time_max;?>" class="regular-text" style="width:150px"/> 个IP请求登录成功
 					<p class="description">超过此数立即禁封，留空表示不限制，此功能的兼容性可能因主题而异</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">禁封提示语 *</th>
 				<td>
 					<input type="text" id="erphp_loggedin_msg" name="erphp_loggedin_msg" value="<?php echo $erphp_loggedin_msg;?>" class="regular-text" required=""/> 
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">登录提醒</th>
 				<td>
 					<input type="checkbox" id="erphp_loggedin_tips" name="erphp_loggedin_tips" value="1" <?php if($erphp_loggedin_tips) echo 'checked';?>/> 
 					<p class="description">用户每次登录后会提示本次与上次登录的地区，提醒用户是否有异常</p>
 				</td>
 			</tr>
 		</table>
 		<p class="submit">
 			<input type="submit" name="loggedin" value="保存设置" class="button-primary"/>
 		</p>  
 	</form>

 	<form method="post" action="<?php echo admin_url('admin.php?page=erphp_loggedin_setting');?>">
 		<p class="submit">
 			<input type="submit" name="del_loggedin" value="解封原谅所有人" class="button" onclick="return confirm('确定原谅所有人？');" />
 		</p>  
 	</form>
</div>
<?php
}

function erphp_loggedin_logs(){
    global $wpdb;

    if(isset($_POST['action'])){
		if($_POST['action'] == '7'){
			$wpdb->query("delete from ".$wpdb->prefix."erphp_loggedin WHERE logged_at < DATE_SUB(CURDATE(), INTERVAL 1 WEEK)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '3'){
			$wpdb->query("delete from ".$wpdb->prefix."erphp_loggedin WHERE logged_at < DATE_SUB(CURDATE(), INTERVAL 3 DAY)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '30'){
			$wpdb->query("delete from ".$wpdb->prefix."erphp_loggedin WHERE logged_at < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '180'){
			$wpdb->query("delete from ".$wpdb->prefix."erphp_loggedin WHERE logged_at < DATE_SUB(CURDATE(), INTERVAL 6 MONTH)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}elseif($_POST['action'] == '365'){
			$wpdb->query("delete from ".$wpdb->prefix."erphp_loggedin WHERE logged_at < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)");
			echo '<div class="updated settings-error"><p>清理成功！</p></div>';
		}
	}

	$issearch = 0;
	if(isset($_GET['username']) && $_GET['username']){
		$user = get_user_by('login',$_GET['username']);
		if($user){
			$suid = $user->ID;
			$issearch = 1;
		}else{
			echo '<div class="error settings-error"><p>用户不存在！</p></div>';
		}
	}
	if($issearch){
		$total   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix."erphp_loggedin where user_id=".$suid);
	}else{
		$total   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix."erphp_loggedin");
	}
	$perpage = 20;
	$pages = ceil($total / $perpage);
	$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
	$offset = $perpage*($page-1);
	if($issearch){
		$list = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."erphp_loggedin where user_id=".$suid." ORDER BY logged_at DESC limit $offset,$perpage");
	}else{
		$list = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."erphp_loggedin ORDER BY logged_at DESC limit $offset,$perpage");
	}
?>
<div class="wrap">
	<h2>所有登录记录</h2>
	<form method="get" action="<?php echo admin_url();?>admin.php">用户ID：<input type="text" name="username" placeholder="例如：admin" value="<?php if(isset($_GET['username'])) echo $_GET['username'];?>"><input type="submit" value="查询" class="button"><input type="hidden" name="page" value="erphp_loggedin_logs"></form><br>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>用户ID</th>
				<th>登录IP</th>
				<th>登录时间</th>	
			</tr>
		</thead>
		<tbody>
	<?php
		if($list) {
			foreach($list as $value){
				echo "<tr>\n";
				echo "<td>".get_user_by('id',$value->user_id)->user_login."</td>\n";
				echo "<td>$value->logged_ip</td>";
				echo "<td>$value->logged_at</td>";
				echo "</tr>";
			}
		}
		else{
			echo '<tr><td colspan="3" align="center"><strong>暂无记录</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php erphp_loggedin_pagination($total,$perpage);?>
    <form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="3"  />
		<input type="submit" value="清理三天之前所有记录" class="button-primary">
	</form>
    <form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="7"  />
		<input type="submit" value="清理一周之前所有记录" class="button-primary">
	</form>
	<form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="30"  />
		<input type="submit" value="清理一月之前所有记录" class="button-primary">
	</form>
	<form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="180"  />
		<input type="submit" value="清理半年之前所有记录" class="button-primary">
	</form>
	<form action="" method="post" style="display:inline-block;">
		<input type="hidden" name="action" value="365"  />
		<input type="submit" value="清理一年之前所有记录" class="button-primary">
	</form>
</div>
<?php
}

function erphp_loggedin_pagination($total_count, $number_per_page=15){

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

function erphp_loggedin_modify_user_table( $column ) {
    $column['erphp-loggedin-ip'] = '登录注册IP';
    //$column['erphp-loggedin-last'] = '上次登录IP';
    $column['erphp-loggedin-current'] = '当前在线IP';
    $column['erphp-loggedin'] = '禁封';
    return $column;
}
add_filter( 'manage_users_columns', 'erphp_loggedin_modify_user_table' , 20, 1);

function erphp_loggedin_modify_user_table_row( $val, $column_name, $user_id ) {
	global $wpdb;
    switch ($column_name) {
        case 'erphp-loggedin' :
        	$loggedin = get_user_meta($user_id,'erphp_loggedin',true);
        	if($loggedin){
        		return '<font color="red">已禁封</font>';
        	}else{
        		return '—';
        	}
        break;
        case 'erphp-loggedin-ip' :
        	$iph = '';
        	$loggedin_ip = get_user_meta($user_id,'erphp_loggedin_ip',true);
        	if($loggedin_ip){
	        	$iph .= '注册IP<br>'.$loggedin_ip.'<br>';
	        }else{
	        	$iph .= '注册IP<br>—<br>';
	        }

	        $loggedin_last = $wpdb->get_var("SELECT logged_ip FROM ".$wpdb->prefix."erphp_loggedin where user_id=".$user_id." order by logged_at desc");
        	if($loggedin_last){
	        	$iph .= '登录IP<br>'.$loggedin_last;
	        }else{
	        	$iph .= '登录IP<br>—';
	        }
	        return $iph;
        break;
        case 'erphp-loggedin-current' :
        	$ips = '';
        	$manager = WP_Session_Tokens::get_instance( $user_id );
			$loggedins = $manager->get_all();
			if(is_array($loggedins)){
				//$loggedins = array_unique($loggedins);
				foreach($loggedins as $loggedin){
					$ips .= $loggedin['ip'].'<br>';
				}
			}
			
        	$ips = rtrim($ips,'<br>');
        	if($ips){
        		return $ips;
        	}else{
        		return '—';
        	}
        break;
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'erphp_loggedin_modify_user_table_row', 50, 3);

function erphp_loggedin_column_width() {
    echo '<style type="text/css">';
    echo '.column-erphp-loggedin{width:74px;}.column-erphp-loggedin-ip , .column-erphp-loggedin-last{width:90px;}';
    echo '</style>';
}
//add_action('admin_head', 'erphp_loggedin_column_width');


add_filter( 'views_users', 'erphp_loggedin_custom_user_views' );
function erphp_loggedin_custom_user_views( $views ) {
    global $wpdb;
    date_default_timezone_set('Asia/Shanghai');
    if( is_admin() ) {
        $count_loggedin = $wpdb->get_var("select count(u.ID) from $wpdb->users as u join $wpdb->usermeta as um on u.ID = um.user_id and um.meta_key = 'erphp_loggedin' where um.meta_value = 1 or um.meta_value = 2");
        $class_loggedin = (isset($_GET['user_status']) && $_GET['user_status'] == 'loggedin') ? ' class="current"' : '';
        $views['user_status'] = sprintf('<a href="%s"'. $class_loggedin .'>'. '禁封' .' <span class="count">（%d）</span></a>', admin_url('users.php?user_status=loggedin'), $count_loggedin); 
    }
    return $views;
}

add_filter( 'pre_user_query', 'erphp_loggedin_users_filter' );
function erphp_loggedin_users_filter( $query ){
    global $wpdb,$pagenow;
    date_default_timezone_set('Asia/Shanghai');
    if ( is_admin() && $pagenow=='users.php' && isset($_GET['user_status']) && $_GET['user_status'] == 'loggedin') {
        $query->query_from .= " join $wpdb->usermeta as um on $wpdb->users.ID = um.user_id and um.meta_key = 'erphp_loggedin'";
        $query->query_where .= " and (um.meta_value = 1 or um.meta_value = 2)";
    }
}

add_filter( 'bulk_actions-users', 'erphp_loggedin_user_bulk_actions' );
function erphp_loggedin_user_bulk_actions($bulk_actions) {
    $bulk_actions['add_loggedin'] = '禁封';
    $bulk_actions['del_loggedin'] = '原谅';
    return $bulk_actions;
}

add_filter( 'handle_bulk_actions-users', 'erphp_loggedin_user_bulk_action_handler', 10, 3 );
function erphp_loggedin_user_bulk_action_handler( $redirect_to, $doaction, $user_ids ) {
    if ( $doaction !== 'add_loggedin' &&  $doaction !== 'del_loggedin') {
        return $redirect_to;
    }
    if($doaction == 'add_loggedin'){
	    foreach ( $user_ids as $user_id ) {
	        update_user_meta($user_id, 'erphp_loggedin', '1');
	    }
	}else{
		foreach ( $user_ids as $user_id ) {
	        delete_user_meta($user_id, 'erphp_loggedin');
	    }
	}
    $redirect_to = add_query_arg( 'bulk_erphp_loggedin_user', count( $user_ids ), $redirect_to );
    return $redirect_to;
}

add_action( 'admin_notices', 'erphp_loggedin_action_admin_notice' );
function erphp_loggedin_action_admin_notice() {
    if ( ! empty( $_REQUEST['bulk_erphp_loggedin_user'] ) ) {
        $user_count = intval( $_REQUEST['bulk_erphp_loggedin_user'] );
        printf( '<div id="message" class="updated settings-error fade"><p>成功处理 '.$user_count.' 个用户。</p></div>', $user_count );
    }
}

function erphp_login_tip_footer(){
	$erphp_loggedin_tips = get_option("erphp_loggedin_tips");
    if(is_user_logged_in() && $erphp_loggedin_tips){
        if(isset($_COOKIE['erphp_login_tips']) && $_COOKIE['erphp_login_tips']){
            global $current_user;
            $erphp_login_tip_current_time = get_user_meta($current_user->ID,'erphp_login_tip_current_time',true);
            $erphp_login_tip_current_ip = get_user_meta($current_user->ID,'erphp_login_tip_current_ip',true);
            $erphp_login_tip_last_time = get_user_meta($current_user->ID,'erphp_login_tip_last_time',true);
            $erphp_login_tip_last_ip = get_user_meta($current_user->ID,'erphp_login_tip_last_ip',true);
            echo '<div class="erphp-login-tips">
                <div class="erphp-login-tips-welcome">'.__('欢迎回来，','mobantu').'<span>'.$current_user->display_name.'</span>！</div>
                <div class="erphp-login-tips-current">'.__('本次登录：','mobantu').'<span>'.$erphp_login_tip_current_time.'</span><br>'.__('本次地区：','mobantu').'<span class="ip">'.$erphp_login_tip_current_ip.'</span></div>
                <div class="erphp-login-tips-last">'.__('上次登录：','mobantu').'<span>'.$erphp_login_tip_last_time.'</span><br>'.__('上次地区：','mobantu').'<span class="ip">'.$erphp_login_tip_last_ip.'</span></div>
                <div class="erphp-login-tips-bug">'.__('如发现登录异常，请及时修改密码！','mobantu').'</div>
                <a href="javascript:;" class="close">×</a>
            </div>';
        }
    }
}
add_action( 'wp_footer', 'erphp_login_tip_footer' );

add_action( 'wp_login', 'erphp_login_tip_login_lasted' );
function erphp_login_tip_login_lasted($user_login){
    date_default_timezone_set('Asia/Shanghai');
    $user = get_user_by('login', $user_login);
    $logged_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_sql($_SERVER['REMOTE_ADDR']);
    setcookie('erphp_login_tips',1,0,'/');
    $erphp_login_tip_current_time = get_user_meta($user->ID,'erphp_login_tip_current_time',true);
    $erphp_login_tip_current_ip = get_user_meta($user->ID,'erphp_login_tip_current_ip',true);
    if($erphp_login_tip_current_time){
        update_user_meta($user->ID,'erphp_login_tip_current_time', date('Y-m-d H:i:s'));
        update_user_meta($user->ID,'erphp_login_tip_current_ip', $logged_ip);
        update_user_meta($user->ID,'erphp_login_tip_last_time', $erphp_login_tip_current_time);
        update_user_meta($user->ID,'erphp_login_tip_last_ip', $erphp_login_tip_current_ip);
    }else{
        update_user_meta($user->ID,'erphp_login_tip_current_time', date('Y-m-d H:i:s'));
        update_user_meta($user->ID,'erphp_login_tip_current_ip', $logged_ip);
        update_user_meta($user->ID,'erphp_login_tip_last_time', date('Y-m-d H:i:s'));
        update_user_meta($user->ID,'erphp_login_tip_last_ip', $logged_ip);
    }
}

function erphp_login_tips_callback(){
    $ip = $_POST['ip'];
    $api = "http://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=".$ip."&co=&resource_id=6006";
    //$api = "https://sp1.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php?query=".$ip."&co=&resource_id=5809";
    $result = erphp_login_tips_get_url_contents($api);
    $result= iconv('GB2312', 'UTF-8', $result);
    $result = json_decode($result,true);
    $loct = '';
    if($result['status'] == '0'){
        $loct = $result['data'][0]['location'];
    }

    if($loct){
        $status = 200;
        $location = $loct;
    }else{
        $status = 500;
        $location = '';
    }

    $result = array(
        'status' => $status,
        'location' => $location
    );

    header('Content-type: application/json');
    echo json_encode($result);
    exit;
}
add_action( 'wp_ajax_erphp_login_tips', 'erphp_login_tips_callback');


function erphp_login_tips_get_url_contents($url) {
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt ( $ch, CURLOPT_URL, $url );
    $result = curl_exec ( $ch );
    curl_close ( $ch );
    return $result;
}