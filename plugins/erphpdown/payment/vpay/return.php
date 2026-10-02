<?php
require_once('../../../../../wp-load.php');
$re = str_replace('#domain#', $_SERVER['HTTP_HOST'], get_option('erphp_url_front_success'));
if(isset($_COOKIE['erphpdown_return']) && $_COOKIE['erphpdown_return']){
    $re = $_COOKIE['erphpdown_return'];
}
if($re)
	wp_redirect($re);
else{
	echo 'success';
	exit;
}