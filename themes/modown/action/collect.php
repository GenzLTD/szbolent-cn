<?php 
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
		header('Allow: POST');
		header('HTTP/1.1 405 Method Not Allowed');
		header('Content-Type: text/plain');
		exit;
	}

	require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
	date_default_timezone_set('Asia/Shanghai');

	if(_MBT("post_collect") && is_user_logged_in()){
		global $wpdb;
		$userdata = wp_get_current_user();
		if(isset($_POST['id']) && $_POST['id'] && is_numeric($_POST['id'])){
			if(MBThemes_check_collect(esc_sql($_POST['id']))){
				$wpdb->delete( $wpdb->prefix ."collects", array('user_id'=>$userdata->ID, 'post_id'=>esc_sql($_POST['id'])) ); 
				$printr["result"] = "2";
			}else{
				$result = $wpdb->insert( $wpdb->prefix ."collects", array(
	                'user_id' => $userdata->ID,
	                'post_id' => esc_sql($_POST['id']),
	                'create_time' => date("Y-m-d H:i:s")
	            ) );
				$printr["result"] = "1";
			}	
		}
	}
	
	echo json_encode($printr);