<?php
if (!session_id()) session_start();
$code = rand(100000,999999);
$_SESSION['MBT_modown_captcha']=$code;

$result = array("code"=>$code);
echo json_encode($result);