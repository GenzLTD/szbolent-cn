<?php
require_once '../../../../../../wp-load.php';
date_default_timezone_set('Asia/Shanghai');

if(!current_user_can('administrator')){
	exit;
}


function epd_statistics_getDatesOfCurrentMonth() {
    // 获取当前日期
    $currentDate = new DateTime();
    
    // 设置日期为当前月的第一天
    $firstDayOfMonth = clone $currentDate;
    $firstDayOfMonth->modify('first day of this month');
    
    // 设置日期为当前月的最后一天
    $lastDayOfMonth = clone $currentDate;
    $lastDayOfMonth->modify('last day of this month');
    
    // 初始化日期数组
    $dates = [];
    
    // 循环生成每一天的日期
    $currentDay = clone $firstDayOfMonth;
    while ($currentDay <= $lastDayOfMonth) {
        $dates[] = $currentDay->format('Y-m-d');
        $currentDay->modify('+1 day');
    }
    
    return $dates;
}

function epd_statistics_get_day_post_count($post_type = 'post', $meta_query = array(), $day = 1) { 
    $today = getdate(strtotime('-'.$day.' day')); // 获取当前日期 
    $args = array( 
    'post_type' => $post_type, // 文章类型 
    'post_status' => 'publish', // 文章状态 
    'date_query' => array( 
        array( 
        'year' => $today['year'],
            'month' => $today['mon'],
            'day' => $today['mday'],
        ), 
    ), 
    'meta_query' => $meta_query
    ); 
    $query = new WP_Query($args); 
    return $query->found_posts;
} 


function epd_statistics_get_today_post_count($post_type = 'post', $meta_query = array()) { 
    $today = getdate(); // 获取当前日期 
    $args = array( 
    'post_type' => $post_type, // 文章类型 
    'post_status' => 'publish', // 文章状态 
    'date_query' => array( 
        array( 
        'year' => $today['year'],
            'month' => $today['mon'],
            'day' => $today['mday'],
        ), 
    ), 
    'meta_query' => $meta_query
    ); 
    $query = new WP_Query($args); 
    return $query->found_posts;
} 

function epd_statistics_get_month_post_count($post_type = 'post', $meta_query = array()) { 
    $today = getdate(); // 获取当前日期 
    $args = array( 
    'post_type' => $post_type, // 文章类型 
    'post_status' => 'publish', // 文章状态 
    'date_query' => array( 
        array( 
        'year' => $today['year'],
            'month' => $today['mon']
        ), 
    ), 
    'meta_query' => $meta_query
    ); 
    $query = new WP_Query($args); 
    return $query->found_posts;
}

function epd_statistics_get_year_post_count($post_type = 'post', $meta_query = array()) { 
    $today = getdate(); // 获取当前日期 
    $args = array( 
    'post_type' => $post_type, // 文章类型 
    'post_status' => 'publish', // 文章状态 
    'date_query' => array( 
        array( 
        'year' => $today['year']
        ), 
    ), 
    'meta_query' => $meta_query
    ); 
    $query = new WP_Query($args); 
    return $query->found_posts;
}

function epd_statistics_get_all_post_count($post_type = 'post', $meta_query = array()) { 
    $args = array( 
    'post_type' => $post_type, // 文章类型 
    'post_status' => 'publish', // 文章状态 
    'meta_query' => $meta_query
    ); 
    $query = new WP_Query($args); 
    return $query->found_posts;
}

function epd_statistics_get_all_chong_money($date1,$date2,$date3,$date4,$date5,$date6,$date7){
	global $wpdb;
	$ice_note = "ice_note=0";
	if(get_option("erphp_addon_card_total")){
	    $ice_note = "(ice_note=0 or ice_note=6)";
	}

	$re = $wpdb->get_row("select
		    sum(case DATE(ice_time) when '".$date1."'  then ice_money else 0 end) as c1,
		    sum(case DATE(ice_time) when '".$date2."'  then ice_money else 0 end) as c2,
		    sum(case DATE(ice_time) when '".$date3."'  then ice_money else 0 end) as c3,
		    sum(case DATE(ice_time) when '".$date4."'  then ice_money else 0 end) as c4,
		    sum(case DATE(ice_time) when '".$date5."'  then ice_money else 0 end) as c5,
		    sum(case DATE(ice_time) when '".$date6."'  then ice_money else 0 end) as c6,
		    sum(case DATE(ice_time) when '".$date7."'  then ice_money else 0 end) as c7
		from $wpdb->icemoney where ice_success>0 and ".$ice_note." and DATE(ice_time) IN ('".$date1."','".$date2."','".$date3."','".$date4."','".$date5."','".$date6."','".$date7."')");
	return $re;
}

function epd_statistics_get_all_vip_count($date1,$date2,$date3,$date4,$date5,$date6,$date7){
	global $wpdb;
	$re = $wpdb->get_row("select
		    sum(case DATE(ice_time) when '".$date1."'  then 1 else 0 end) as c1,
		    sum(case DATE(ice_time) when '".$date2."'  then 1 else 0 end) as c2,
		    sum(case DATE(ice_time) when '".$date3."'  then 1 else 0 end) as c3,
		    sum(case DATE(ice_time) when '".$date4."'  then 1 else 0 end) as c4,
		    sum(case DATE(ice_time) when '".$date5."'  then 1 else 0 end) as c5,
		    sum(case DATE(ice_time) when '".$date6."'  then 1 else 0 end) as c6,
		    sum(case DATE(ice_time) when '".$date7."'  then 1 else 0 end) as c7
		from $wpdb->vip where DATE(ice_time) IN ('".$date1."','".$date2."','".$date3."','".$date4."','".$date5."','".$date6."','".$date7."')");
	return $re;
}

function epd_statistics_get_all_down_count($date1,$date2,$date3,$date4,$date5,$date6,$date7){
	global $wpdb;
	$re = $wpdb->get_row("select
		    sum(case DATE(ice_time) when '".$date1."'  then 1 else 0 end) as c1,
		    sum(case DATE(ice_time) when '".$date2."'  then 1 else 0 end) as c2,
		    sum(case DATE(ice_time) when '".$date3."'  then 1 else 0 end) as c3,
		    sum(case DATE(ice_time) when '".$date4."'  then 1 else 0 end) as c4,
		    sum(case DATE(ice_time) when '".$date5."'  then 1 else 0 end) as c5,
		    sum(case DATE(ice_time) when '".$date6."'  then 1 else 0 end) as c6,
		    sum(case DATE(ice_time) when '".$date7."'  then 1 else 0 end) as c7
		from $wpdb->down where DATE(ice_time) IN ('".$date1."','".$date2."','".$date3."','".$date4."','".$date5."','".$date6."','".$date7."')");
	return $re;
}

function epd_statistics_get_all_tx_count($date1,$date2,$date3,$date4,$date5,$date6,$date7){
	global $wpdb;
	$re = $wpdb->get_row("select
		    sum(case DATE(ice_time) when '".$date1."'  then ice_money else 0 end) as c1,
		    sum(case DATE(ice_time) when '".$date2."'  then ice_money else 0 end) as c2,
		    sum(case DATE(ice_time) when '".$date3."'  then ice_money else 0 end) as c3,
		    sum(case DATE(ice_time) when '".$date4."'  then ice_money else 0 end) as c4,
		    sum(case DATE(ice_time) when '".$date5."'  then ice_money else 0 end) as c5,
		    sum(case DATE(ice_time) when '".$date6."'  then ice_money else 0 end) as c6,
		    sum(case DATE(ice_time) when '".$date7."'  then ice_money else 0 end) as c7
		from $wpdb->iceget where DATE(ice_time) IN ('".$date1."','".$date2."','".$date3."','".$date4."','".$date5."','".$date6."','".$date7."')");
	return $re;
}

$epd_statistics_get_all_chong_money = epd_statistics_get_all_chong_money(date("Y-m-d",strtotime('-6 day')),date("Y-m-d",strtotime('-5 day')),date("Y-m-d",strtotime('-4 day')),date("Y-m-d",strtotime('-3 day')),date("Y-m-d",strtotime('-2 day')),date("Y-m-d",strtotime('-1 day')),date("Y-m-d"));
$epd_statistics_get_all_vip_count = epd_statistics_get_all_vip_count(date("Y-m-d",strtotime('-6 day')),date("Y-m-d",strtotime('-5 day')),date("Y-m-d",strtotime('-4 day')),date("Y-m-d",strtotime('-3 day')),date("Y-m-d",strtotime('-2 day')),date("Y-m-d",strtotime('-1 day')),date("Y-m-d"));
$epd_statistics_get_all_down_count = epd_statistics_get_all_down_count(date("Y-m-d",strtotime('-6 day')),date("Y-m-d",strtotime('-5 day')),date("Y-m-d",strtotime('-4 day')),date("Y-m-d",strtotime('-3 day')),date("Y-m-d",strtotime('-2 day')),date("Y-m-d",strtotime('-1 day')),date("Y-m-d"));
$epd_statistics_get_all_tx_count = epd_statistics_get_all_tx_count(date("Y-m-d",strtotime('-6 day')),date("Y-m-d",strtotime('-5 day')),date("Y-m-d",strtotime('-4 day')),date("Y-m-d",strtotime('-3 day')),date("Y-m-d",strtotime('-2 day')),date("Y-m-d",strtotime('-1 day')),date("Y-m-d"));


$currencyName = get_option('ice_name_alipay');
$ice_proportion_alipay = get_option('ice_proportion_alipay');
$ice_proportion_alipay = $ice_proportion_alipay?$ice_proportion_alipay:1;

$ice_note = "ice_note=0";
if(get_option("erphp_addon_card_total")){
    $ice_note = "(ice_note=0 or ice_note=6)";
}

$today_chong_money = $wpdb->get_row("SELECT SUM(ice_money) as cm, count(ice_id) as ct FROM $wpdb->icemoney WHERE ice_success>0 and ".$ice_note." and TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");
$today_chong_money_no = $wpdb->get_row("SELECT count(ice_id) as ct FROM $wpdb->icemoney WHERE ice_success=0 and ".$ice_note." and TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");

$today_vip_money_no = $wpdb->get_row("SELECT count(ice_id) as ct FROM $wpdb->icemoney WHERE ice_success=0 and ice_user_type > 0 and TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");
$today_order_money_no = $wpdb->get_row("SELECT count(ice_id) as ct FROM $wpdb->icemoney WHERE ice_success=0 and ice_post_id > 0 and TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");
$today_vip_money = $wpdb->get_row("SELECT SUM(ice_price) as cm, count(ice_id) as ct FROM $wpdb->vip WHERE TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");
$today_order_money = $wpdb->get_row("SELECT SUM(ice_price) as cm, count(ice_id) as ct FROM $wpdb->icealipay WHERE ice_success>0 and TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");

$today_down_log = $wpdb->get_var("SELECT count(ice_id) as ct FROM $wpdb->down where to_days(ice_time) = to_days(now())");
$today_down_log_free   = $wpdb->get_var("SELECT count(ice_id) as ct FROM $wpdb->down where ice_vip=0 and to_days(ice_time) = to_days(now())");
$today_down_log_vip   = $wpdb->get_var("SELECT count(ice_id) as ct FROM $wpdb->down where ice_vip=1 and to_days(ice_time) = to_days(now())");
$today_down_log_buy   = $wpdb->get_var("SELECT count(ice_id) as ct FROM $wpdb->down where ice_vip=2 and to_days(ice_time) = to_days(now())");

$today_tixian_log = $wpdb->get_row("SELECT SUM(ice_money) as cm, count(ice_id) as ct FROM $wpdb->iceget WHERE TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");

$today_user = $wpdb->get_row("SELECT count(ID) as ct FROM $wpdb->users WHERE TO_DAYS(NOW())- TO_DAYS(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = 0");
$today_user_aff = $wpdb->get_row("SELECT count(ID) as ct FROM $wpdb->users WHERE and TO_DAYS(NOW())- TO_DAYS(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = 0 and father_id > 0");
$today_user_logged = $wpdb->get_var("SELECT count(DISTINCT user_id) FROM ".$wpdb->prefix."erphp_loggedin WHERE TO_DAYS(NOW())- TO_DAYS(logged_at) = 0");

$all_user_logged = $wpdb->get_row("SELECT count(DISTINCT user_id) FROM ".$wpdb->prefix."erphp_loggedin WHERE DATE(logged_at) IN ('".date("Y-m-d",strtotime('-6 day'))."','".date("Y-m-d",strtotime('-5 day'))."','".date("Y-m-d",strtotime('-4 day'))."','".date("Y-m-d",strtotime('-3 day'))."','".date("Y-m-d",strtotime('-2 day'))."','".$date6."','".$date7."')");

$all_order_money_all_day_total = $wpdb->get_row("select
		    sum(case day(ice_time) when '1'  then ice_price else 0 end) as one,
		    sum(case day(ice_time) when '2'  then ice_price else 0 end) as two,
		    sum(case day(ice_time) when '3'  then ice_price else 0 end) as three,
		    sum(case day(ice_time) when '4'  then ice_price else 0 end) as four,
		    sum(case day(ice_time) when '5'  then ice_price else 0 end) as five,
		    sum(case day(ice_time) when '6'  then ice_price else 0 end) as six,
		    sum(case day(ice_time) when '7'  then ice_price else 0 end) as seven,
		    sum(case day(ice_time) when '8'  then ice_price else 0 end) as eight,
		    sum(case day(ice_time) when '9'  then ice_price else 0 end) as nine,
		    sum(case day(ice_time) when '10' then ice_price else 0 end) as ten,
		    sum(case day(ice_time) when '11' then ice_price else 0 end) as eleven,
		    sum(case day(ice_time) when '12' then ice_price else 0 end) as twelve,
		    sum(case day(ice_time) when '13' then ice_price else 0 end) as thirteen,
		    sum(case day(ice_time) when '14' then ice_price else 0 end) as fourteen,
		    sum(case day(ice_time) when '15' then ice_price else 0 end) as fifteen,
		    sum(case day(ice_time) when '16' then ice_price else 0 end) as sixteen,
		    sum(case day(ice_time) when '17' then ice_price else 0 end) as seventeen,
		    sum(case day(ice_time) when '18' then ice_price else 0 end) as eighteen,
		    sum(case day(ice_time) when '19' then ice_price else 0 end) as nineteen,
		    sum(case day(ice_time) when '20' then ice_price else 0 end) as twenty,
		    sum(case day(ice_time) when '21' then ice_price else 0 end) as twentyone,
		    sum(case day(ice_time) when '22' then ice_price else 0 end) as twentytwo,
		    sum(case day(ice_time) when '23' then ice_price else 0 end) as twentythree,
		    sum(case day(ice_time) when '24' then ice_price else 0 end) as twentyfour,
		    sum(case day(ice_time) when '25' then ice_price else 0 end) as twentyfive,
		    sum(case day(ice_time) when '26' then ice_price else 0 end) as twentysix,
		    sum(case day(ice_time) when '27' then ice_price else 0 end) as twentyseven,
		    sum(case day(ice_time) when '28' then ice_price else 0 end) as twentyeight,
		    sum(case day(ice_time) when '29' then ice_price else 0 end) as twentynine,
		    sum(case day(ice_time) when '30' then ice_price else 0 end) as thirty,
		    sum(case day(ice_time) when '31' then ice_price else 0 end) as thirtyone
		from $wpdb->icealipay where year(ice_time)='".date("Y")."' and month(ice_time)='".ltrim(date("m"),'0')."' and ice_success>0");

$all_order_money_allno_day_total = $wpdb->get_row("select
		    sum(case day(ice_time) when '1'  then ice_money else 0 end) as one,
		    sum(case day(ice_time) when '2'  then ice_money else 0 end) as two,
		    sum(case day(ice_time) when '3'  then ice_money else 0 end) as three,
		    sum(case day(ice_time) when '4'  then ice_money else 0 end) as four,
		    sum(case day(ice_time) when '5'  then ice_money else 0 end) as five,
		    sum(case day(ice_time) when '6'  then ice_money else 0 end) as six,
		    sum(case day(ice_time) when '7'  then ice_money else 0 end) as seven,
		    sum(case day(ice_time) when '8'  then ice_money else 0 end) as eight,
		    sum(case day(ice_time) when '9'  then ice_money else 0 end) as nine,
		    sum(case day(ice_time) when '10' then ice_money else 0 end) as ten,
		    sum(case day(ice_time) when '11' then ice_money else 0 end) as eleven,
		    sum(case day(ice_time) when '12' then ice_money else 0 end) as twelve,
		    sum(case day(ice_time) when '13' then ice_money else 0 end) as thirteen,
		    sum(case day(ice_time) when '14' then ice_money else 0 end) as fourteen,
		    sum(case day(ice_time) when '15' then ice_money else 0 end) as fifteen,
		    sum(case day(ice_time) when '16' then ice_money else 0 end) as sixteen,
		    sum(case day(ice_time) when '17' then ice_money else 0 end) as seventeen,
		    sum(case day(ice_time) when '18' then ice_money else 0 end) as eighteen,
		    sum(case day(ice_time) when '19' then ice_money else 0 end) as nineteen,
		    sum(case day(ice_time) when '20' then ice_money else 0 end) as twenty,
		    sum(case day(ice_time) when '21' then ice_money else 0 end) as twentyone,
		    sum(case day(ice_time) when '22' then ice_money else 0 end) as twentytwo,
		    sum(case day(ice_time) when '23' then ice_money else 0 end) as twentythree,
		    sum(case day(ice_time) when '24' then ice_money else 0 end) as twentyfour,
		    sum(case day(ice_time) when '25' then ice_money else 0 end) as twentyfive,
		    sum(case day(ice_time) when '26' then ice_money else 0 end) as twentysix,
		    sum(case day(ice_time) when '27' then ice_money else 0 end) as twentyseven,
		    sum(case day(ice_time) when '28' then ice_money else 0 end) as twentyeight,
		    sum(case day(ice_time) when '29' then ice_money else 0 end) as twentynine,
		    sum(case day(ice_time) when '30' then ice_money else 0 end) as thirty,
		    sum(case day(ice_time) when '31' then ice_money else 0 end) as thirtyone
		from $wpdb->icemoney where year(ice_time)='".date("Y")."' and month(ice_time)='".ltrim(date("m"),'0')."' and ice_success=0 and ice_post_id > 0");

$all_order_money_day_total = $wpdb->get_row("select
		    sum(case day(ice_time) when '1'  then ice_price else 0 end) as one,
		    sum(case day(ice_time) when '2'  then ice_price else 0 end) as two,
		    sum(case day(ice_time) when '3'  then ice_price else 0 end) as three,
		    sum(case day(ice_time) when '4'  then ice_price else 0 end) as four,
		    sum(case day(ice_time) when '5'  then ice_price else 0 end) as five,
		    sum(case day(ice_time) when '6'  then ice_price else 0 end) as six,
		    sum(case day(ice_time) when '7'  then ice_price else 0 end) as seven,
		    sum(case day(ice_time) when '8'  then ice_price else 0 end) as eight,
		    sum(case day(ice_time) when '9'  then ice_price else 0 end) as nine,
		    sum(case day(ice_time) when '10' then ice_price else 0 end) as ten,
		    sum(case day(ice_time) when '11' then ice_price else 0 end) as eleven,
		    sum(case day(ice_time) when '12' then ice_price else 0 end) as twelve,
		    sum(case day(ice_time) when '13' then ice_price else 0 end) as thirteen,
		    sum(case day(ice_time) when '14' then ice_price else 0 end) as fourteen,
		    sum(case day(ice_time) when '15' then ice_price else 0 end) as fifteen,
		    sum(case day(ice_time) when '16' then ice_price else 0 end) as sixteen,
		    sum(case day(ice_time) when '17' then ice_price else 0 end) as seventeen,
		    sum(case day(ice_time) when '18' then ice_price else 0 end) as eighteen,
		    sum(case day(ice_time) when '19' then ice_price else 0 end) as nineteen,
		    sum(case day(ice_time) when '20' then ice_price else 0 end) as twenty,
		    sum(case day(ice_time) when '21' then ice_price else 0 end) as twentyone,
		    sum(case day(ice_time) when '22' then ice_price else 0 end) as twentytwo,
		    sum(case day(ice_time) when '23' then ice_price else 0 end) as twentythree,
		    sum(case day(ice_time) when '24' then ice_price else 0 end) as twentyfour,
		    sum(case day(ice_time) when '25' then ice_price else 0 end) as twentyfive,
		    sum(case day(ice_time) when '26' then ice_price else 0 end) as twentysix,
		    sum(case day(ice_time) when '27' then ice_price else 0 end) as twentyseven,
		    sum(case day(ice_time) when '28' then ice_price else 0 end) as twentyeight,
		    sum(case day(ice_time) when '29' then ice_price else 0 end) as twentynine,
		    sum(case day(ice_time) when '30' then ice_price else 0 end) as thirty,
		    sum(case day(ice_time) when '31' then ice_price else 0 end) as thirtyone
		from $wpdb->icealipay where year(ice_time)='".date("Y")."' and month(ice_time)='".ltrim(date("m"),'0')."' and ice_success>0");

$all_order_day_total = $wpdb->get_row("select
		    COUNT(CASE WHEN DAY(ice_time) = '1' THEN ice_id END) AS one,
    		COUNT(CASE WHEN DAY(ice_time) = '2' THEN ice_id END) AS two,
		    COUNT(CASE WHEN DAY(ice_time) = '3' THEN ice_id END) AS  three,
		    COUNT(CASE WHEN DAY(ice_time) = '4' THEN ice_id END) AS  four,
		    COUNT(CASE WHEN DAY(ice_time) = '5' THEN ice_id END) AS  five,
		    COUNT(CASE WHEN DAY(ice_time) = '6' THEN ice_id END) AS  six,
		    COUNT(CASE WHEN DAY(ice_time) = '7' THEN ice_id END) AS seven,
		    COUNT(CASE WHEN DAY(ice_time) = '8' THEN ice_id END) AS eight,
		    COUNT(CASE WHEN DAY(ice_time) = '9' THEN ice_id END) AS nine,
		    COUNT(CASE WHEN DAY(ice_time) = '10' THEN ice_id END) AS ten,
		    COUNT(CASE WHEN DAY(ice_time) = '11' THEN ice_id END) AS eleven,
		    COUNT(CASE WHEN DAY(ice_time) = '12' THEN ice_id END) AS twelve,
		    COUNT(CASE WHEN DAY(ice_time) = '13' THEN ice_id END) AS thirteen,
		    COUNT(CASE WHEN DAY(ice_time) = '14' THEN ice_id END) AS fourteen,
		    COUNT(CASE WHEN DAY(ice_time) = '15' THEN ice_id END) AS fifteen,
		    COUNT(CASE WHEN DAY(ice_time) = '16' THEN ice_id END) AS sixteen,
		    COUNT(CASE WHEN DAY(ice_time) = '17' THEN ice_id END) AS seventeen,
		    COUNT(CASE WHEN DAY(ice_time) = '18' THEN ice_id END) AS eighteen,
		    COUNT(CASE WHEN DAY(ice_time) = '19' THEN ice_id END) AS nineteen,
		    COUNT(CASE WHEN DAY(ice_time) = '20' THEN ice_id END) AS twenty,
		    COUNT(CASE WHEN DAY(ice_time) = '21' THEN ice_id END) AS twentyone,
		    COUNT(CASE WHEN DAY(ice_time) = '22' THEN ice_id END) AS twentytwo,
		    COUNT(CASE WHEN DAY(ice_time) = '23' THEN ice_id END) AS twentythree,
		    COUNT(CASE WHEN DAY(ice_time) = '24' THEN ice_id END) AS twentyfour,
		    COUNT(CASE WHEN DAY(ice_time) = '25' THEN ice_id END) AS twentyfive,
		    COUNT(CASE WHEN DAY(ice_time) = '26' THEN ice_id END) AS twentysix,
		    COUNT(CASE WHEN DAY(ice_time) = '27' THEN ice_id END) AS twentyseven,
		    COUNT(CASE WHEN DAY(ice_time) = '28' THEN ice_id END) AS twentyeight,
		    COUNT(CASE WHEN DAY(ice_time) = '29' THEN ice_id END) AS twentynine,
		    COUNT(CASE WHEN DAY(ice_time) = '30' THEN ice_id END) AS thirty,
		    COUNT(CASE WHEN DAY(ice_time) = '31' THEN ice_id END) AS thirtyone
		from $wpdb->icealipay where year(ice_time)='".date("Y")."' and month(ice_time)='".ltrim(date("m"),'0')."' and ice_success>0");

$all_order_no_day_total = $wpdb->get_row("select
		    COUNT(CASE WHEN DAY(ice_time) = '1' THEN ice_id END) AS one,
    		COUNT(CASE WHEN DAY(ice_time) = '2' THEN ice_id END) AS two,
		    COUNT(CASE WHEN DAY(ice_time) = '3' THEN ice_id END) AS  three,
		    COUNT(CASE WHEN DAY(ice_time) = '4' THEN ice_id END) AS  four,
		    COUNT(CASE WHEN DAY(ice_time) = '5' THEN ice_id END) AS  five,
		    COUNT(CASE WHEN DAY(ice_time) = '6' THEN ice_id END) AS  six,
		    COUNT(CASE WHEN DAY(ice_time) = '7' THEN ice_id END) AS seven,
		    COUNT(CASE WHEN DAY(ice_time) = '8' THEN ice_id END) AS eight,
		    COUNT(CASE WHEN DAY(ice_time) = '9' THEN ice_id END) AS nine,
		    COUNT(CASE WHEN DAY(ice_time) = '10' THEN ice_id END) AS ten,
		    COUNT(CASE WHEN DAY(ice_time) = '11' THEN ice_id END) AS eleven,
		    COUNT(CASE WHEN DAY(ice_time) = '12' THEN ice_id END) AS twelve,
		    COUNT(CASE WHEN DAY(ice_time) = '13' THEN ice_id END) AS thirteen,
		    COUNT(CASE WHEN DAY(ice_time) = '14' THEN ice_id END) AS fourteen,
		    COUNT(CASE WHEN DAY(ice_time) = '15' THEN ice_id END) AS fifteen,
		    COUNT(CASE WHEN DAY(ice_time) = '16' THEN ice_id END) AS sixteen,
		    COUNT(CASE WHEN DAY(ice_time) = '17' THEN ice_id END) AS seventeen,
		    COUNT(CASE WHEN DAY(ice_time) = '18' THEN ice_id END) AS eighteen,
		    COUNT(CASE WHEN DAY(ice_time) = '19' THEN ice_id END) AS nineteen,
		    COUNT(CASE WHEN DAY(ice_time) = '20' THEN ice_id END) AS twenty,
		    COUNT(CASE WHEN DAY(ice_time) = '21' THEN ice_id END) AS twentyone,
		    COUNT(CASE WHEN DAY(ice_time) = '22' THEN ice_id END) AS twentytwo,
		    COUNT(CASE WHEN DAY(ice_time) = '23' THEN ice_id END) AS twentythree,
		    COUNT(CASE WHEN DAY(ice_time) = '24' THEN ice_id END) AS twentyfour,
		    COUNT(CASE WHEN DAY(ice_time) = '25' THEN ice_id END) AS twentyfive,
		    COUNT(CASE WHEN DAY(ice_time) = '26' THEN ice_id END) AS twentysix,
		    COUNT(CASE WHEN DAY(ice_time) = '27' THEN ice_id END) AS twentyseven,
		    COUNT(CASE WHEN DAY(ice_time) = '28' THEN ice_id END) AS twentyeight,
		    COUNT(CASE WHEN DAY(ice_time) = '29' THEN ice_id END) AS twentynine,
		    COUNT(CASE WHEN DAY(ice_time) = '30' THEN ice_id END) AS thirty,
		    COUNT(CASE WHEN DAY(ice_time) = '31' THEN ice_id END) AS thirtyone
		from $wpdb->icemoney where year(ice_time)='".date("Y")."' and month(ice_time)='".ltrim(date("m"),'0')."' and ice_success=0 and ice_post_id > 0");

$all_user_day_total = $wpdb->get_row("select
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '1' THEN ID END) AS one,
    		COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '2' THEN ID END) AS two,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '3' THEN ID END) AS  three,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '4' THEN ID END) AS  four,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '5' THEN ID END) AS  five,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '6' THEN ID END) AS  six,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '7' THEN ID END) AS seven,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '8' THEN ID END) AS eight,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '9' THEN ID END) AS nine,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '10' THEN ID END) AS ten,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '11' THEN ID END) AS eleven,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '12' THEN ID END) AS twelve,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '13' THEN ID END) AS thirteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '14' THEN ID END) AS fourteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '15' THEN ID END) AS fifteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '16' THEN ID END) AS sixteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '17' THEN ID END) AS seventeen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '18' THEN ID END) AS eighteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '19' THEN ID END) AS nineteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '20' THEN ID END) AS twenty,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '21' THEN ID END) AS twentyone,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '22' THEN ID END) AS twentytwo,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '23' THEN ID END) AS twentythree,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '24' THEN ID END) AS twentyfour,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '25' THEN ID END) AS twentyfive,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '26' THEN ID END) AS twentysix,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '27' THEN ID END) AS twentyseven,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '28' THEN ID END) AS twentyeight,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '29' THEN ID END) AS twentynine,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '30' THEN ID END) AS thirty,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '31' THEN ID END) AS thirtyone
		from $wpdb->users where year(CONVERT_TZ(`user_registered`,'+00:00','+08:00'))='".date("Y")."' and month(CONVERT_TZ(`user_registered`,'+00:00','+08:00'))='".ltrim(date("m"),'0')."'");

$all_user_aff_day_total = $wpdb->get_row("select
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '1' THEN ID END) AS one,
    		COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '2' THEN ID END) AS two,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '3' THEN ID END) AS  three,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '4' THEN ID END) AS  four,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '5' THEN ID END) AS  five,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '6' THEN ID END) AS  six,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '7' THEN ID END) AS seven,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '8' THEN ID END) AS eight,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '9' THEN ID END) AS nine,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '10' THEN ID END) AS ten,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '11' THEN ID END) AS eleven,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '12' THEN ID END) AS twelve,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '13' THEN ID END) AS thirteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '14' THEN ID END) AS fourteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '15' THEN ID END) AS fifteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '16' THEN ID END) AS sixteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '17' THEN ID END) AS seventeen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '18' THEN ID END) AS eighteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '19' THEN ID END) AS nineteen,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '20' THEN ID END) AS twenty,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '21' THEN ID END) AS twentyone,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '22' THEN ID END) AS twentytwo,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '23' THEN ID END) AS twentythree,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '24' THEN ID END) AS twentyfour,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '25' THEN ID END) AS twentyfive,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '26' THEN ID END) AS twentysix,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '27' THEN ID END) AS twentyseven,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '28' THEN ID END) AS twentyeight,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '29' THEN ID END) AS twentynine,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '30' THEN ID END) AS thirty,
		    COUNT(DISTINCT CASE WHEN DAY(CONVERT_TZ(`user_registered`,'+00:00','+08:00')) = '31' THEN ID END) AS thirtyone
		from $wpdb->users where year(CONVERT_TZ(`user_registered`,'+00:00','+08:00'))='".date("Y")."' and month(CONVERT_TZ(`user_registered`,'+00:00','+08:00'))='".ltrim(date("m"),'0')."' and father_id > 0");

$all_user_logged_day_total = $wpdb->get_row("select
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '1' THEN user_id END) AS one,
    		COUNT(DISTINCT CASE WHEN DAY(logged_at) = '2' THEN user_id END) AS two,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '3' THEN user_id END) AS  three,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '4' THEN user_id END) AS  four,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '5' THEN user_id END) AS  five,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '6' THEN user_id END) AS  six,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '7' THEN user_id END) AS seven,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '8' THEN user_id END) AS eight,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '9' THEN user_id END) AS nine,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '10' THEN user_id END) AS ten,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '11' THEN user_id END) AS eleven,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '12' THEN user_id END) AS twelve,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '13' THEN user_id END) AS thirteen,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '14' THEN user_id END) AS fourteen,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '15' THEN user_id END) AS fifteen,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '16' THEN user_id END) AS sixteen,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '17' THEN user_id END) AS seventeen,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '18' THEN user_id END) AS eighteen,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '19' THEN user_id END) AS nineteen,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '20' THEN user_id END) AS twenty,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '21' THEN user_id END) AS twentyone,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '22' THEN user_id END) AS twentytwo,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '23' THEN user_id END) AS twentythree,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '24' THEN user_id END) AS twentyfour,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '25' THEN user_id END) AS twentyfive,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '26' THEN user_id END) AS twentysix,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '27' THEN user_id END) AS twentyseven,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '28' THEN user_id END) AS twentyeight,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '29' THEN user_id END) AS twentynine,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '30' THEN user_id END) AS thirty,
		    COUNT(DISTINCT CASE WHEN DAY(logged_at) = '31' THEN user_id END) AS thirtyone
		from ".$wpdb->prefix."erphp_loggedin where year(logged_at)='".date("Y")."' and month(logged_at)='".ltrim(date("m"),'0')."'");

$all_jf_users = get_users(array('meta_query' => array(array('key'     => 'erphp_loggedin','value'   => 0,'compare' => '>'))));
$all_jf_count = count($all_jf_users);

$all_top_money = array();
$all_user_info = $wpdb->get_results("SELECT ice_user_id, (ice_have_money - ice_get_money) AS ice_diff FROM $wpdb->iceinfo ORDER BY ice_diff DESC limit 0,10");
if($all_user_info){
	foreach ($all_user_info as $key => $value) {
		$ccu = get_user_by("ID",$value->ice_user_id);
		if($ccu){
			$all_top_money[] = array("ID"=> $value->ice_user_id, "display_name"=>$ccu->user_login, "balance"=>$value->ice_diff);
		}
	}
}

/*
$count_posts = wp_count_posts('post');
$data_row3_1_num1 = epd_statistics_get_today_post_count();
$data_row3_1_num2 = epd_statistics_get_month_post_count();
$data_row3_1_num3 = epd_statistics_get_year_post_count();
$data_row3_1_num4 = $count_posts->publish;*/

$data_row3_2_num1 = epd_statistics_get_today_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')));
$data_row3_2_num2 = epd_statistics_get_month_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')));
$data_row3_2_num3 = epd_statistics_get_year_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')));
$data_row3_2_num4 = epd_statistics_get_all_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')));

$data_row3_3_num1 = epd_statistics_get_all_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,5), 'compare' => 'IN')));
$data_row3_3_num2 = epd_statistics_get_all_post_count('post',array(array('key' => 'erphp_down', 'value' => array(2,3), 'compare' => 'IN')));
$data_row3_3_num3 = epd_statistics_get_all_post_count('post',array(array('key' => 'erphp_down', 'value' => array(6), 'compare' => 'IN')));

$data = array(
	"data" => array(
		"row1" => array(
			array(
				"num1" => ($today_chong_money?$today_chong_money->ct:0) + ($today_chong_money_no?$today_chong_money_no->ct:0),
				"num2" => $today_chong_money?$today_chong_money->ct:0,
				"num3" => $today_chong_money_no?$today_chong_money_no->ct:0,
				"num4" => $today_chong_money?($today_chong_money->cm?$today_chong_money->cm:0):0,
				"chart1" => array(date("M-d",strtotime('-6 day')),date("M-d",strtotime('-5 day')),date("M-d",strtotime('-4 day')),date("M-d",strtotime('-3 day')),date("M-d",strtotime('-2 day')),date("M-d",strtotime('-1 day')),date("M-d")),
				"chart2" => array($epd_statistics_get_all_chong_money->c1,$epd_statistics_get_all_chong_money->c2,$epd_statistics_get_all_chong_money->c3,$epd_statistics_get_all_chong_money->c4,$epd_statistics_get_all_chong_money->c5,$epd_statistics_get_all_chong_money->c6,$epd_statistics_get_all_chong_money->c7)
			),
			array(
				"num1" => ($today_vip_money?$today_vip_money->ct:0) + ($today_vip_money_no?$today_vip_money_no->ct:0),
				"num2" => $today_vip_money?$today_vip_money->ct:'0',
				"num3" => $today_vip_money_no?$today_vip_money_no->ct:'0',
				"num4" => $today_vip_money?$today_vip_money->ct:'0',
				"chart1" => array(date("M-d",strtotime('-6 day')),date("M-d",strtotime('-5 day')),date("M-d",strtotime('-4 day')),date("M-d",strtotime('-3 day')),date("M-d",strtotime('-2 day')),date("M-d",strtotime('-1 day')),date("M-d")),
				"chart2" => array($epd_statistics_get_all_vip_count->c1,$epd_statistics_get_all_vip_count->c2,$epd_statistics_get_all_vip_count->c3,$epd_statistics_get_all_vip_count->c4,$epd_statistics_get_all_vip_count->c5,$epd_statistics_get_all_vip_count->c6,$epd_statistics_get_all_vip_count->c7)
			),
			array(
				"num1" => $today_down_log_free?$today_down_log_free:'0',
				"num2" => $today_down_log_vip?$today_down_log_vip:'0',
				"num3" => $today_down_log_buy?$today_down_log_buy:'0',
				"num4" => $today_down_log?$today_down_log:'0',
				"chart1" => array(date("M-d",strtotime('-6 day')),date("M-d",strtotime('-5 day')),date("M-d",strtotime('-4 day')),date("M-d",strtotime('-3 day')),date("M-d",strtotime('-2 day')),date("M-d",strtotime('-1 day')),date("M-d")),
				"chart2" => array($epd_statistics_get_all_down_count->c1,$epd_statistics_get_all_down_count->c2,$epd_statistics_get_all_down_count->c3,$epd_statistics_get_all_down_count->c4,$epd_statistics_get_all_down_count->c5,$epd_statistics_get_all_down_count->c6,$epd_statistics_get_all_down_count->c7)
			),
			array(
				"num1" => $today_tixian_log->ct?$today_tixian_log->ct:'0',
				"num2" => $today_tixian_log->ct?$today_tixian_log->ct:'0',
				"num3" => 0,
				"num4" => ($today_tixian_log->cm?$today_tixian_log->cm:'0')/$ice_proportion_alipay,
				"chart1" => array(date("M-d",strtotime('-6 day')),date("M-d",strtotime('-5 day')),date("M-d",strtotime('-4 day')),date("M-d",strtotime('-3 day')),date("M-d",strtotime('-2 day')),date("M-d",strtotime('-1 day')),date("M-d")),
				"chart2" => array($epd_statistics_get_all_tx_count->c1/$ice_proportion_alipay,$epd_statistics_get_all_tx_count->c2/$ice_proportion_alipay,$epd_statistics_get_all_tx_count->c3/$ice_proportion_alipay,$epd_statistics_get_all_tx_count->c4/$ice_proportion_alipay,$epd_statistics_get_all_tx_count->c5/$ice_proportion_alipay,$epd_statistics_get_all_tx_count->c6/$ice_proportion_alipay,$epd_statistics_get_all_tx_count->c7/$ice_proportion_alipay)
			)
		),
		"row2" => array(
			array(
				"num1" => $today_order_money?($today_order_money->cm?$today_order_money->cm:0):0,
				"num2" => ($today_order_money?$today_order_money->ct:0) + ($today_order_money_no?$today_order_money_no->ct:0),
				"num3" => $today_order_money?$today_order_money->ct:0,
				"num4" => $today_order_money_no?$today_order_money_no->ct:0,
				"chart1" => epd_statistics_getDatesOfCurrentMonth(),
				"chart2" => array($all_order_money_all_day_total->one+$all_order_money_allno_day_total->one,$all_order_money_all_day_total->two+$all_order_money_allno_day_total->two,$all_order_money_all_day_total->three+$all_order_money_allno_day_total->three,$all_order_money_all_day_total->four+$all_order_money_allno_day_total->four,$all_order_money_all_day_total->five+$all_order_money_allno_day_total->five,$all_order_money_all_day_total->six+$all_order_money_allno_day_total->six,$all_order_money_all_day_total->seven+$all_order_money_allno_day_total->seven,$all_order_money_all_day_total->eight+$all_order_money_allno_day_total->eight,$all_order_money_all_day_total->nine+$all_order_money_allno_day_total->nine,$all_order_money_all_day_total->ten+$all_order_money_allno_day_total->ten,$all_order_money_all_day_total->eleven+$all_order_money_allno_day_total->eleven,$all_order_money_all_day_total->twelve+$all_order_money_allno_day_total->twelve,$all_order_money_all_day_total->thirteen+$all_order_money_allno_day_total->thirteen,$all_order_money_all_day_total->fourteen+$all_order_money_allno_day_total->fourteen,$all_order_money_all_day_total->fifteen+$all_order_money_allno_day_total->fifteen,$all_order_money_all_day_total->sixteen+$all_order_money_allno_day_total->sixteen,$all_order_money_all_day_total->seventeen+$all_order_money_allno_day_total->seventeen,$all_order_money_all_day_total->eighteen+$all_order_money_allno_day_total->eighteen,$all_order_money_all_day_total->nineteen+$all_order_money_allno_day_total->nineteen,$all_order_money_all_day_total->twenty+$all_order_money_allno_day_total->twenty,$all_order_money_all_day_total->twentyone+$all_order_money_allno_day_total->twentyone,$all_order_money_all_day_total->twentytwo+$all_order_money_allno_day_total->twentytwo,$all_order_money_all_day_total->twentythree+$all_order_money_allno_day_total->twentythree,$all_order_money_all_day_total->twentyfour+$all_order_money_allno_day_total->twentyfour,$all_order_money_all_day_total->twentyfive+$all_order_money_allno_day_total->twentyfive,$all_order_money_all_day_total->twentysix+$all_order_money_allno_day_total->twentysix,$all_order_money_all_day_total->twentyseven+$all_order_money_allno_day_total->twentyseven,$all_order_money_all_day_total->twentyeight+$all_order_money_allno_day_total->twentyeight,$all_order_money_all_day_total->twentynine+$all_order_money_allno_day_total->twentynine,$all_order_money_all_day_total->thirty+$all_order_money_allno_day_total->thirty,$all_order_money_all_day_total->thirtyone+$all_order_money_allno_day_total->thirtyone),
				"chart3" => array($all_order_money_day_total->one,$all_order_money_day_total->two,$all_order_money_day_total->three,$all_order_money_day_total->four,$all_order_money_day_total->five,$all_order_money_day_total->six,$all_order_money_day_total->seven,$all_order_money_day_total->eight,$all_order_money_day_total->nine,$all_order_money_day_total->ten,$all_order_money_day_total->eleven,$all_order_money_day_total->twelve,$all_order_money_day_total->thirteen,$all_order_money_day_total->fourteen,$all_order_money_day_total->fifteen,$all_order_money_day_total->sixteen,$all_order_money_day_total->seventeen,$all_order_money_day_total->eighteen,$all_order_money_day_total->nineteen,$all_order_money_day_total->twenty,$all_order_money_day_total->twentyone,$all_order_money_day_total->twentytwo,$all_order_money_day_total->twentythree,$all_order_money_day_total->twentyfour,$all_order_money_day_total->twentyfive,$all_order_money_day_total->twentysix,$all_order_money_day_total->twentyseven,$all_order_money_day_total->twentyeight,$all_order_money_day_total->twentynine,$all_order_money_day_total->thirty,$all_order_money_day_total->thirtyone),
				"chart4" => array($all_order_day_total->one+$all_order_no_day_total->one,$all_order_day_total->two+$all_order_no_day_total->two,$all_order_day_total->three+$all_order_no_day_total->three,$all_order_day_total->four+$all_order_no_day_total->four,$all_order_day_total->five+$all_order_no_day_total->five,$all_order_day_total->six+$all_order_no_day_total->six,$all_order_day_total->seven+$all_order_no_day_total->seven,$all_order_day_total->eight+$all_order_no_day_total->eight,$all_order_day_total->nine+$all_order_no_day_total->nine,$all_order_day_total->ten+$all_order_no_day_total->ten,$all_order_day_total->eleven+$all_order_no_day_total->eleven,$all_order_day_total->twelve+$all_order_no_day_total->twelve,$all_order_day_total->thirteen+$all_order_no_day_total->thirteen,$all_order_day_total->fourteen+$all_order_no_day_total->fourteen,$all_order_day_total->fifteen+$all_order_no_day_total->fifteen,$all_order_day_total->sixteen+$all_order_no_day_total->sixteen,$all_order_day_total->seventeen+$all_order_no_day_total->seventeen,$all_order_day_total->eighteen+$all_order_no_day_total->eighteen,$all_order_day_total->nineteen+$all_order_no_day_total->nineteen,$all_order_day_total->twenty+$all_order_no_day_total->twenty,$all_order_day_total->twentyone+$all_order_no_day_total->twentyone,$all_order_day_total->twentytwo+$all_order_no_day_total->twentytwo,$all_order_day_total->twentythree+$all_order_no_day_total->twentythree,$all_order_day_total->twentyfour+$all_order_no_day_total->twentyfour,$all_order_day_total->twentyfive+$all_order_no_day_total->twentyfive,$all_order_day_total->twentysix+$all_order_no_day_total->twentysix,$all_order_day_total->twentyseven+$all_order_no_day_total->twentyseven,$all_order_day_total->twentyeight+$all_order_no_day_total->twentyeight,$all_order_day_total->twentynine+$all_order_no_day_total->twentynine,$all_order_day_total->thirty+$all_order_no_day_total->thirty,$all_order_day_total->thirtyone+$all_order_no_day_total->thirtyone),
				"chart5" => array($all_order_day_total->one,$all_order_day_total->two,$all_order_day_total->three,$all_order_day_total->four,$all_order_day_total->five,$all_order_day_total->six,$all_order_day_total->seven,$all_order_day_total->eight,$all_order_day_total->nine,$all_order_day_total->ten,$all_order_day_total->eleven,$all_order_day_total->twelve,$all_order_day_total->thirteen,$all_order_day_total->fourteen,$all_order_day_total->fifteen,$all_order_day_total->sixteen,$all_order_day_total->seventeen,$all_order_day_total->eighteen,$all_order_day_total->nineteen,$all_order_day_total->twenty,$all_order_day_total->twentyone,$all_order_day_total->twentytwo,$all_order_day_total->twentythree,$all_order_day_total->twentyfour,$all_order_day_total->twentyfive,$all_order_day_total->twentysix,$all_order_day_total->twentyseven,$all_order_day_total->twentyeight,$all_order_day_total->twentynine,$all_order_day_total->thirty,$all_order_day_total->thirtyone)
			),
			array(
				"num1" => $today_user?$today_user->ct:0,
				"num2" => $today_user_aff?$today_user_aff->ct:0,
				"num3" => $today_user_logged?$today_user_logged:0,
				"num4" => $all_jf_count,
				"chart1" => epd_statistics_getDatesOfCurrentMonth(),
				"chart2" => array($all_user_day_total->one,$all_user_day_total->two,$all_user_day_total->three,$all_user_day_total->four,$all_user_day_total->five,$all_user_day_total->six,$all_user_day_total->seven,$all_user_day_total->eight,$all_user_day_total->nine,$all_user_day_total->ten,$all_user_day_total->eleven,$all_user_day_total->twelve,$all_user_day_total->thirteen,$all_user_day_total->fourteen,$all_user_day_total->fifteen,$all_user_day_total->sixteen,$all_user_day_total->seventeen,$all_user_day_total->eighteen,$all_user_day_total->nineteen,$all_user_day_total->twenty,$all_user_day_total->twentyone,$all_user_day_total->twentytwo,$all_user_day_total->twentythree,$all_user_day_total->twentyfour,$all_user_day_total->twentyfive,$all_user_day_total->twentysix,$all_user_day_total->twentyseven,$all_user_day_total->twentyeight,$all_user_day_total->twentynine,$all_user_day_total->thirty,$all_user_day_total->thirtyone),
				"chart3" => array($all_user_aff_day_total->one,$all_user_aff_day_total->two,$all_user_aff_day_total->three,$all_user_aff_day_total->four,$all_user_aff_day_total->five,$all_user_aff_day_total->six,$all_user_aff_day_total->seven,$all_user_aff_day_total->eight,$all_user_aff_day_total->nine,$all_user_aff_day_total->ten,$all_user_aff_day_total->eleven,$all_user_aff_day_total->twelve,$all_user_aff_day_total->thirteen,$all_user_aff_day_total->fourteen,$all_user_aff_day_total->fifteen,$all_user_aff_day_total->sixteen,$all_user_aff_day_total->seventeen,$all_user_aff_day_total->eighteen,$all_user_aff_day_total->nineteen,$all_user_aff_day_total->twenty,$all_user_aff_day_total->twentyone,$all_user_aff_day_total->twentytwo,$all_user_aff_day_total->twentythree,$all_user_aff_day_total->twentyfour,$all_user_aff_day_total->twentyfive,$all_user_aff_day_total->twentysix,$all_user_aff_day_total->twentyseven,$all_user_aff_day_total->twentyeight,$all_user_aff_day_total->twentynine,$all_user_aff_day_total->thirty,$all_user_aff_day_total->thirtyone),
				"chart4" => array($all_user_logged_day_total->one,$all_user_logged_day_total->two,$all_user_logged_day_total->three,$all_user_logged_day_total->four,$all_user_logged_day_total->five,$all_user_logged_day_total->six,$all_user_logged_day_total->seven,$all_user_logged_day_total->eight,$all_user_logged_day_total->nine,$all_user_logged_day_total->ten,$all_user_logged_day_total->eleven,$all_user_logged_day_total->twelve,$all_user_logged_day_total->thirteen,$all_user_logged_day_total->fourteen,$all_user_logged_day_total->fifteen,$all_user_logged_day_total->sixteen,$all_user_logged_day_total->seventeen,$all_user_logged_day_total->eighteen,$all_user_logged_day_total->nineteen,$all_user_logged_day_total->twenty,$all_user_logged_day_total->twentyone,$all_user_logged_day_total->twentytwo,$all_user_logged_day_total->twentythree,$all_user_logged_day_total->twentyfour,$all_user_logged_day_total->twentyfive,$all_user_logged_day_total->twentysix,$all_user_logged_day_total->twentyseven,$all_user_logged_day_total->twentyeight,$all_user_logged_day_total->twentynine,$all_user_logged_day_total->thirty,$all_user_logged_day_total->thirtyone),
				"chart5" => array()
			)
		),
		"row3" => array(
			$all_top_money,
			array(
				"num1" => 0,
				"num2" => 0,
				"num3" => 0,
				"num4" => 0,
				"chart1" => array(),
				"chart2" => array()
			),
			array(
				"num1" => $data_row3_2_num1,
				"num2" => $data_row3_2_num2,
				"num3" => $data_row3_2_num3,
				"num4" => $data_row3_2_num4,
				"chart1" => array(date("M-d",strtotime('-6 day')),date("M-d",strtotime('-5 day')),date("M-d",strtotime('-4 day')),date("M-d",strtotime('-3 day')),date("M-d",strtotime('-2 day')),date("M-d",strtotime('-1 day')),date("M-d")),
				"chart2" => array(epd_statistics_get_day_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')),6),epd_statistics_get_day_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')),5),epd_statistics_get_day_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')),4),epd_statistics_get_day_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')),3),epd_statistics_get_day_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN')),2),epd_statistics_get_day_post_count('post',array(array('key' => 'erphp_down', 'value' => array(1,2,3,5,6), 'compare' => 'IN'))),$data_row3_2_num1)
			),
			array(
				"num1" => $data_row3_3_num1,
				"num2" => $data_row3_3_num2,
				"num3" => $data_row3_3_num3,
				"num4" => 0,
				"chart1" => array('下载资源', '查看资源', '卡密资源', '其他资源'),
				"chart2" => array($data_row3_3_num1, $data_row3_3_num2, $data_row3_3_num3, 0)
			)
		)
	)
	
);

echo json_encode($data);
exit;