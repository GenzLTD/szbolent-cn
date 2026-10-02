<?php
/**
 * Options Framework
 * Theme by mobantu.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Don't load if optionsframework_init is already defined
if (is_admin() && ! function_exists( 'optionsframework_init' ) ) :

function optionsframework_init() {

	//  If user can't edit theme options, exit
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// Loads the required Options Framework classes.
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework-admin.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-interface.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-media-uploader.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-options-sanitization.php';

	// Instantiate the options page.
	$options_framework_admin = new Options_Framework_Admin;
	$options_framework_admin->init();

	// Instantiate the media uploader class
	$options_framework_media_uploader = new Options_Framework_Media_Uploader;
	$options_framework_media_uploader->init();

}

add_action( 'init', 'optionsframework_init', 20 );

endif;


/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */
if ( ! function_exists( 'of_get_option' ) ) :
function of_get_option( $name, $default = false ) {

	$option_name = '';

	// Gets option name as defined in the theme
	if ( function_exists( 'optionsframework_option_name' ) ) {
		$option_name = optionsframework_option_name();
	}

	// Fallback option name
	if ( '' == $option_name ) {
		$option_name = get_option( 'stylesheet' );
		$option_name = preg_replace( "/\W/", "_", strtolower( $option_name ) );
	}

	// Get option settings from database
	$options = get_option( $option_name );

	// Return specific option
	if ( isset( $options[$name] ) ) {
		return $options[$name];
	}

	return $default;
}
endif;

function modown_options_check_callback(){
	if(!current_user_can('administrator')){
        exit;
    }

    $v = get_url_contents(base64_decode("aHR0cDovL2FwaS5tb2JhbnR1LmNvbS90aGVtZS9tb2Rvd24ucGhw"));
	if($v > THEME_VER){
		$arr=array(
			"status"=>1
		); 
	}else{
		$arr=array(
			"status"=>0
		);
	}

	header('Content-type: application/json');
	$jarr=json_encode($arr); 
	echo $jarr;
	exit;
}
add_action( 'wp_ajax_modown_options_check', 'modown_options_check_callback');

function modown_options_restart_callback(){
	if(!current_user_can('administrator')){
        exit;
    }

    $otheme = $_POST['theme'];
	delete_option('MBT_'.$otheme.'_token');
	delete_option('MBT_'.$otheme.'_options');
	delete_option('MBT_'.$otheme.'_version');
	$arr=array(
		"status"=>1
	);

	header('Content-type: application/json');
	$jarr=json_encode($arr); 
	echo $jarr;
	exit;
}
add_action( 'wp_ajax_modown_options_restart', 'modown_options_restart_callback');

function modown_options_active_callback(){
	if(!current_user_can('administrator')){
        exit;
    }

    $status = 0;
	$message = '激活失败';
	$username = isset($_POST['username']) ? $_POST['username']:'';
	$token = isset($_POST['token']) ? $_POST['token']:'';
	$home = isset($_POST['home']) ? $_POST['home']:'';
	$theme = isset($_POST['theme']) ? $_POST['theme']:'';
	delete_option('MBT_'.$theme.'_key');
	$domain = parse_url(admin_url(),PHP_URL_HOST);
	$body = array('username'=>$username, 'token'=>$token, 'theme'=>$theme, 'domain'=>$domain, 'key'=>md5($username.$token.$domain), 'ip'=>mbt_get_ip(), 'action'=>'active');
	$result_body = json_decode(mbt_send_request($body));
	if( isset($result_body->status) && $result_body->status=='1' ){
		update_option('MBT_'.$theme.'_user',$username);
		update_option('MBT_'.$theme.'_token',$result_body->token);
		update_option('MBT_'.$theme.'_options',$result_body->ops);
		update_option('MBT_'.$theme.'_version', THEME_VER );
		$status = 1;
		$message = $result_body->message;
	}elseif( isset($result_body->status) && $result_body->status=='0' ){
		$message = $result_body->message;
	}
	$arr=array(
		"status"=>$status,
		"message"=>$message
	); 

	header('Content-type: application/json');
	$jarr=json_encode($arr); 
	echo $jarr;
	exit;
}
add_action( 'wp_ajax_modown_options_active', 'modown_options_active_callback');