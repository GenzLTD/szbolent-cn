<?php
function optionsframework_option_name() {
	return 'Modown';
}

function optionsframework_option_true() {
	return true;
}

function optionsframework_options() {
	$typography_options = array(
		'sizes' => false
	);

	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	$typography_defaults = array(
		'face' => 'yahei',
		'style' => 'normal',
		'color' => '#383121' );

	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );
		
	$typography_content = array(
		'size' => '13px',
		'face' => 'yahei',
		'style' => 'normal',
		'color' => '#000000' );

	$domain = parse_url(admin_url(),PHP_URL_HOST);
	$otheme = optionsframework_option_name();
	$ops = base64_decode(get_option('MBT_'.$otheme.'_options'));
    $token = get_option('MBT_'.$otheme.'_token');
    $ops = str_replace( 'c'.md5($token.$domain.'MO0H3BTN2K3TU'.strtolower($otheme)).md5($token.'adc'.$domain).'2f'.md5('d9x0l').'i', '', $ops );
    $ops = base64_decode( str_replace( 'c'.md5($token).'a', '', $ops ) );
    $ops = str_replace($domain,'',$ops);
    return json_decode($ops,TRUE);
}

function optionsframework_active_options(){
	$options = array();
	$options[] = array('name' => '模板兔','type' => 'heading');
	$options[] = array('name' => '用户名','id' => 'mbt_modown_username','desc' => 'mobantu.com的用户名（个人中心 - 我的资料）','type' => 'text');
	$options[] = array('name' => '激活码','id' => 'mbt_modown_token','desc' => 'mobantu.com的下载标识码（个人中心 - 下载清单）','type' => 'text');
	return $options;
}