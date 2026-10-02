<?php
/*
Plugin Name: ErphpDown后台统计图表
Plugin URI: http://www.mobantu.com
Description: Erphpdown后台详细统计
Version: 1.0
Author: 模板兔
Author URI: http://www.mobantu.com
*/
add_action('admin_menu', 'modown_statistics_menu');
function modown_statistics_menu() {
	add_management_page('Modown统计', 'Modown统计', 'activate_plugins', 'modown_statistics_dashboard', 'modown_statistics_dashboard');
}

function modown_statistics_assets(){
    if (isset($_GET['page']) && str_starts_with($_GET['page'], 'modown_statistics_dashboard')) {
        wp_enqueue_style('ele', 'https://lib.baomitu.com/element-ui/2.15.14/theme-chalk/index.min.css');
        wp_enqueue_script('vue', THEME_URI.'/inc/plugin/erphpdown-addon-statistics/vue.min.js');
        wp_enqueue_script('ele', 'https://lib.baomitu.com/element-ui/2.15.14/index.min.js');
        wp_enqueue_script('axios', 'https://cdn.bootcdn.net/ajax/libs/axios/1.5.0/axios.min.js');
        wp_enqueue_script('apexcharts', THEME_URI.'/inc/plugin/erphpdown-addon-statistics/apexcharts.min.js');
    }
}
add_action('admin_enqueue_scripts', 'modown_statistics_assets');

function modown_statistics_callback(){
    date_default_timezone_set('Asia/Shanghai');
    if(!current_user_can('administrator')){
        exit;
    }

    global $wpdb;

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
    
    header('Content-type: application/json');
    echo json_encode($data);
    exit;
}
add_action( 'wp_ajax_modown_statistics', 'modown_statistics_callback');


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


function modown_statistics_dashboard(){
    global $wpdb;
	$currencyName = get_option('ice_name_alipay');
?>
<div class="wrap">
    <div id="initLoading"></div>
    <div id="erphpdown-statistics">
        <el-row :gutter="gutter">
            <el-col :span="6">
                <el-card>
                    <el-row>
                        <el-col :span="12">
                            <div class="card-row1-title">今日充值统计</div>
                            <div class="card-row1-number">{{ loadData.row1[0].num4 }} <?php echo $currencyName; ?></div>
                        </el-col>
                        <el-col :span="12">
                            <apex-chart v-if="loadAfter" :options="row1.chart1"></apex-chart>
                        </el-col>
                    </el-row>
                    <el-divider></el-divider>
                    <el-row>
                        <el-col :span="8">
                            <el-statistic title="总订单充值">
                                <template slot="formatter"> {{ loadData.row1[0].num1 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="已支付订单">
                                <template slot="formatter"> {{ loadData.row1[0].num2 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="未支付订单">
                                <template slot="formatter"> {{ loadData.row1[0].num3 }}条 </template>
                            </el-statistic>
                        </el-col>
                    </el-row>
                </el-card>
            </el-col>
            <el-col :span="6">
                <el-card>
                    <el-row>
                        <el-col :span="12">
                            <div class="card-row1-title">今日VIP统计</div>
                            <div class="card-row1-number">{{ loadData.row1[1].num4 }} 位</div>
                        </el-col>
                        <el-col :span="12">
                            <apex-chart v-if="loadAfter" :options="row1.chart2"></apex-chart>
                        </el-col>
                    </el-row>
                    <el-divider></el-divider>
                    <el-row>
                        <el-col :span="8">
                            <el-statistic title="开通总订单">
                                <template slot="formatter"> {{ loadData.row1[1].num1 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="已支付订单">
                                <template slot="formatter"> {{ loadData.row1[1].num2 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="未支付订单">
                                <template slot="formatter"> {{ loadData.row1[1].num3 }}条 </template>
                            </el-statistic>
                        </el-col>
                    </el-row>
                </el-card>
            </el-col>
            <el-col :span="6">
                <el-card>
                    <el-row>
                        <el-col :span="12">
                            <div class="card-row1-title">今日下载统计</div>
                            <div class="card-row1-number">{{ loadData.row1[2].num4 }} 次</div>
                        </el-col>
                        <el-col :span="12">
                            <apex-chart v-if="loadAfter" :options="row1.chart3"></apex-chart>
                        </el-col>
                    </el-row>
                    <el-divider></el-divider>
                    <el-row>
                        <el-col :span="8">
                            <el-statistic title="免费下载">
                                <template slot="formatter"> {{ loadData.row1[2].num1 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="VIP下载">
                                <template slot="formatter"> {{ loadData.row1[2].num2 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="购买下载">
                                <template slot="formatter"> {{ loadData.row1[2].num3 }}条 </template>
                            </el-statistic>
                        </el-col>
                    </el-row>
                </el-card>
            </el-col>
            <el-col :span="6">
                <el-card>
                    <el-row>
                        <el-col :span="12">
                            <div class="card-row1-title">今日提现统计</div>
                            <div class="card-row1-number">{{ loadData.row1[3].num4 }} 元</div>
                        </el-col>
                        <el-col :span="12">
                            <apex-chart v-if="loadAfter" :options="row1.chart4"></apex-chart>
                        </el-col>
                    </el-row>
                    <el-divider></el-divider>
                    <el-row>
                        <el-col :span="8">
                            <el-statistic title="提现订单">
                                <template slot="formatter"> {{ loadData.row1[3].num1 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="现金提现">
                                <template slot="formatter"> {{ loadData.row1[3].num2 }}条 </template>
                            </el-statistic>
                        </el-col>
                        <el-col :span="8">
                            <el-statistic title="货币提现">
                                <template slot="formatter"> {{ loadData.row1[3].num3 }}条 </template>
                            </el-statistic>
                        </el-col>
                    </el-row>
                </el-card>
            </el-col>
        </el-row>
        <el-row :gutter="gutter" :style="{'margin-top': gutter + 'px'}">
            <el-col :span="12">
                <el-card>
                    <div class="card-row2-title">
                        <span>购买资源统计</span>
                        <a class="more" href="<?php echo admin_url('admin.php?page=erphpdown/admin/erphp-orders-list.php') ?>">更多<i class="el-icon-arrow-right"></i></a>
                    </div>
                    <div class="card-row2-content">
                        <el-row>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[0].num1 }}<?php echo $currencyName; ?></div>
                                <div class="s-title">今日销售</div>
                            </el-col>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[0].num2 }}条</div>
                                <div class="s-title">今日购买总订单</div>
                            </el-col>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[0].num3 }}条</div>
                                <div class="s-title">今日已支付订单</div>
                            </el-col>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[0].num4 }}条</div>
                                <div class="s-title">今日未支付订单</div>
                            </el-col>
                        </el-row>
                    </div>
                    <apex-chart v-if="loadAfter" :options="row2.chart1"></apex-chart>
                </el-card>
            </el-col>
            <el-col :span="12">
                <el-card>
                    <div class="card-row2-title">
                        <span>用户统计</span>
                        <a class="more" href="<?php echo admin_url('users.php') ?>">更多<i class="el-icon-arrow-right"></i></a>
                    </div>
                    <div class="card-row2-content">
                        <el-row>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[1].num1 }}位</div>
                                <div class="s-title">今日注册用户</div>
                            </el-col>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[1].num2 }}位</div>
                                <div class="s-title">今日受邀注册用户</div>
                            </el-col>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[1].num3 }}位</div>
                                <div class="s-title">今日登录用户</div>
                            </el-col>
                            <el-col :span="6">
                                <div class="s-number">{{ loadData.row2[1].num4 }}位</div>
                                <div class="s-title"><font color="#ff5f33">至今</font>封号用户</div>
                            </el-col>
                        </el-row>
                    </div>
                    <apex-chart v-if="loadAfter" :options="row2.chart2"></apex-chart>
                </el-card>
            </el-col>
        </el-row>
        <el-row :gutter="gutter" :style="{'margin-top': gutter + 'px'}">
            <el-col :span="8">
                <el-card class="card-row3-wrap">
                    <div class="card-row3-title">
                        <span>用户余额排行</span>
                    </div>
                    <div v-if="loadAfter" class="card-row3-col1-content">
                        <el-row v-for="(user, index) in loadData.row3[0]" :key="user.ID">
                            <el-col :span="12">
                                <div class="user-wrap">
                                    <el-avatar>{{ index + 1 }}</el-avatar>
                                    <span class="username">{{ user.display_name }}</span>
                                </div>
                            </el-col>
                            <el-col :span="12" class="money-wrap">
                                <el-tag type="success">{{ user.balance }} <?php echo $currencyName ?></el-tag>
                            </el-col>
                        </el-row>
                    </div>
                </el-card>
            </el-col>
            
            <el-col :span="8">
                <el-card class="card-row3-wrap">
                    <div class="card-row3-title">
                        <span>站内资源统计</span>
                    </div>
                    <div v-if="loadAfter" class="card-row3-content">
                        <apex-chart :options="row3.chart2"></apex-chart>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #26a0fc;"></i>
                                <span>今日发布资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[2].num1 }}个</span>
                            </el-col>
                        </el-row>
                        <el-divider></el-divider>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #26e7a6;"></i>
                                <span>本月发布资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[2].num2 }}个</span>
                            </el-col>
                        </el-row>
                        <el-divider></el-divider>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #febc3b;"></i>
                                <span>今年发布资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[2].num3 }}个</span>
                            </el-col>
                        </el-row>
                        <el-divider></el-divider>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #ff6178;"></i>
                                <span>全站发布资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[2].num4 }}个</span>
                            </el-col>
                        </el-row>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="8">
                <el-card class="card-row3-wrap">
                    <div class="card-row3-title">
                        <span>资源类型统计</span>
                    </div>
                    <div v-if="loadAfter" class="card-row3-content">
                        <apex-chart :options="row3.chart3"></apex-chart>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #26a0fc;"></i>
                                <span>下载资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[3].num1 }}个</span>
                            </el-col>
                        </el-row>
                        <el-divider></el-divider>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #26e7a6;"></i>
                                <span>查看资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[3].num2 }}个</span>
                            </el-col>
                        </el-row>
                        <el-divider></el-divider>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #febc3b;"></i>
                                <span>卡密资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[3].num3 }}个</span>
                            </el-col>
                        </el-row>
                        <el-divider></el-divider>
                        <el-row>
                            <el-col :span="12">
                                <i class="label-icon" style="background-color: #ff6178;"></i>
                                <span>其他资源</span>
                            </el-col>
                            <el-col :span="12" class="number-wrap">
                                <span>{{ loadData.row3[3].num4 }}个</span>
                            </el-col>
                        </el-row>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
</div>

<script>
    Vue.component('apex-chart', {
        props: ['options'],
        mounted() {
            this.initChart();
        },
        methods: {
            initChart() {
                const chart = new ApexCharts(this.$el, this.options);
                chart.render();
            }
        },
        template: `<div></div>`
    });

    var zhCN = {
        "name": "zh-cn",
        "options": {
            "months": [
                "一月",
                "二月",
                "三月",
                "四月",
                "五月",
                "六月",
                "七月",
                "八月",
                "九月",
                "十月",
                "十一月",
                "十二月"
            ],
            "shortMonths": [
                "一月",
                "二月",
                "三月",
                "四月",
                "五月",
                "六月",
                "七月",
                "八月",
                "九月",
                "十月",
                "十一月",
                "十二月"
            ],
            "days": [
                "星期天",
                "星期一",
                "星期二",
                "星期三",
                "星期四",
                "星期五",
                "星期六"
            ],
            "shortDays": [
                "周日",
                "周一",
                "周二",
                "周三",
                "周四",
                "周五",
                "周六"
            ],
            "toolbar": {
                "exportToSVG": "下载 SVG",
                "exportToPNG": "下载 PNG",
                "exportToCSV": "下载 CSV",
                "menu": "菜单",
                "selection": "选择",
                "selectionZoom": "选择缩放",
                "zoomIn": "放大",
                "zoomOut": "缩小",
                "pan": "平移",
                "reset": "重置缩放"
            }
        }
    }


    new Vue({
        el: '#erphpdown-statistics',
        data: {
            zhCN: zhCN,
            gutter: 15,
            loading: false,
            loadAfter: false,
            loadData: {
                row1: [{
                    num1: '0',
                    num2: '0',
                    num3: '0',
                    num4: '0',
                }, {
                    num1: '0',
                    num2: '0',
                    num3: '0',
                    num4: '0',
                }, {
                    num1: '0',
                    num2: '0',
                    num3: '0',
                    num4: '0',
                }, {
                    num1: '0',
                    num2: '0',
                    num3: '0',
                    num4: '0',
                }],
                row2: [{
                    num1: '0',
                    num2: '0',
                    num3: '0',
                    num4: '0',
                }, {
                    num1: '0',
                    num2: '0',
                    num3: '0',
                    num4: '0',
                }],
                row3: [{},
                    {
                        num1: '0',
                        num2: '0',
                        num3: '0',
                        num4: '0',
                    }, {
                        num1: '0',
                        num2: '0',
                        num3: '0',
                        num4: '0',
                    },
                    {
                        num1: '0',
                        num2: '0',
                        num3: '0',
                        num4: '0',
                    }
                ],
            },
            row1: {
                chart1: {
                    chart: {
                        type: 'line',
                        height: 80,
                        sparkline: {
                            enabled: true
                        },
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    series: [{
                        name: '充值统计',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value.toFixed(2);
                            }
                        },
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#26a0fc']
                },
                chart2: {
                    chart: {
                        type: 'line',
                        height: 80,
                        sparkline: {
                            enabled: true
                        },
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    series: [{
                        name: 'VIP统计',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value.toFixed(0);
                            }
                        },
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#26e7a6']
                },
                chart3: {
                    chart: {
                        type: 'line',
                        height: 80,
                        sparkline: {
                            enabled: true
                        },
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    series: [{
                        name: '下载统计',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value.toFixed(0);
                            }
                        },
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#febc3b']
                },
                chart4: {
                    chart: {
                        type: 'line',
                        height: 80,
                        sparkline: {
                            enabled: true
                        },
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    series: [{
                        name: '提现统计',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value.toFixed(2);
                            }
                        },
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#ff6178']
                },
            },
            row2: {
                chart1: {
                    series: [{
                        name: '总订单金额',
                        data: [],
                    }, {
                        name: '已付款金额',
                        data: [],
                    }, {
                        name: '总订单数量',
                        data: [],
                    }, {
                        name: '已付款订单数量',
                        data: [],
                    }],
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                            show: false
                        },
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: [],
                        labels: {
                            datetimeFormatter: {
                                day: 'MM-dd',
                            }
                        },
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            }
                        },
                    },
                    tooltip: {
                        x: {
                            format: 'MM-dd'
                        },
                    },
                },
                chart2: {
                    series: [{
                            name: '注册用户',
                            type: 'column',
                            data: [],
                        }, {
                            name: '受邀注册用户',
                            type: 'area',
                            data: [],
                        }, {
                            name: '成功登录用户',
                            type: 'area',
                            data: [],
                        }
                        /*{
                            name: '封号用户',
                            type: 'area',
                            data: [],
                        }*/
                    ],
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                            show: false
                        },
                        stacked: false,
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: [],
                        labels: {
                            datetimeFormatter: {
                                day: 'MM-dd',
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            }
                        },
                    },
                    tooltip: {
                        x: {
                            format: 'MM-dd'
                        },
                    },
                },
            },
            row3: {
                chart1: {
                    series: [{
                        name: '发布文章',
                        data: [],
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: [],
                        labels: {
                            datetimeFormatter: {
                                day: 'MM-dd',
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            }
                        },
                    },
                    tooltip: {
                        x: {
                            format: 'MM-dd'
                        },
                    },
                    legend: {
                        show: false,
                    }
                },
                chart2: {
                    series: [{
                        name: '发布资源',
                        data: []
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: [],
                        labels: {
                            datetimeFormatter: {
                                day: 'MM-dd',
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            }
                        },
                    },
                    tooltip: {
                        x: {
                            format: 'MM-dd'
                        },
                    },
                    legend: {
                        show: false,
                    }
                },
                chart3: {
                    series: [],
                    chart: {
                        type: 'donut',
                        height: 335,
                        locales: [zhCN],
                        defaultLocale: 'zh-cn',
                    },
                    labels: ['下载资源', '查看资源', '卡密资源', '其他资源'],
                    legend: {
                        show: false,
                    }
                },
            }
        },
        beforeMount() {
            document.getElementById('initLoading').style.display = 'none'
        },
        mounted() {
            this.load()
        },
        methods: {
            load() {
                this.loading = true //'<?php echo THEME_URI.'/inc/plugin/erphpdown-addon-statistics/data.php';?>'
                let _this = this
                axios.get(ajaxurl+'?action=modown_statistics')
                    .then(function(res) {
                        _this.loadData = res.data.data
                        // row1
                        _this.row1.chart1.xaxis.categories = res.data.data.row1[0].chart1
                        _this.row1.chart1.series[0].data = res.data.data.row1[0].chart2
                        _this.row1.chart2.xaxis.categories = res.data.data.row1[1].chart1
                        _this.row1.chart2.series[0].data = res.data.data.row1[1].chart2
                        _this.row1.chart3.xaxis.categories = res.data.data.row1[2].chart1
                        _this.row1.chart3.series[0].data = res.data.data.row1[2].chart2
                        _this.row1.chart4.xaxis.categories = res.data.data.row1[3].chart1
                        _this.row1.chart4.series[0].data = res.data.data.row1[3].chart2

                        // row2
                        _this.row2.chart1.xaxis.categories = res.data.data.row2[0].chart1
                        _this.row2.chart1.series[0].data = res.data.data.row2[0].chart2
                        _this.row2.chart1.series[1].data = res.data.data.row2[0].chart3
                        _this.row2.chart1.series[2].data = res.data.data.row2[0].chart4
                        _this.row2.chart1.series[3].data = res.data.data.row2[0].chart5
                        _this.row2.chart2.xaxis.categories = res.data.data.row2[1].chart1
                        _this.row2.chart2.series[0].data = res.data.data.row2[1].chart2
                        _this.row2.chart2.series[1].data = res.data.data.row2[1].chart3
                        _this.row2.chart2.series[2].data = res.data.data.row2[1].chart4
                        //_this.row2.chart2.series[3].data = res.data.data.row2[1].chart5

                        // row3
                        _this.row3.chart1.xaxis.categories = res.data.data.row3[1].chart1
                        _this.row3.chart1.series[0].data = res.data.data.row3[1].chart2
                        _this.row3.chart2.xaxis.categories = res.data.data.row3[2].chart1
                        _this.row3.chart2.series[0].data = res.data.data.row3[2].chart2
                        _this.row3.chart3.labels = res.data.data.row3[3].chart1
                        _this.row3.chart3.series = res.data.data.row3[3].chart2
                    })
                    .catch(function(err) {
                        console.log(err)
                    })
                    .then(function() {
                        _this.loadAfter = true
                        _this.loading = false
                    });
            },
        }
    })
</script>

<style scoped>
    .card-row1-title {
        font-size: 16px;
        margin: 10px 0;
        text-align: left;
    }

    .card-row1-number {
        margin-top: 30px;
        font-size: 20px;
        font-weight: 600;
        text-align: left;
    }

    .card-row2-title {
        margin-bottom: 40px;
    }

    .card-row2-title span {
        font-size: 16px;
    }

    .card-row2-title a {
        font-size: 14px;
        text-decoration: none;
        float: right;
        color: #999;
    }

    .card-row2-content {
        text-align: center;
    }

    .s-number {
        font-size: 18px;
        font-weight: 600;
    }

    .s-title {
        font-size: 14px;
        margin-top: 15px;
    }

    .label-icon {
        display: inline-block;
        width: 10px;
        height: 10px;
    }

    /* row3 */
    .card-row3-wrap {
        height: 600px;
    }

    .card-row3-title {
        margin-bottom: 40px;
    }

    .card-row3-title span {
        font-size: 16px;
    }

    .card-row3-col1-content .el-row {
        margin-bottom: 10px;
        height: 40px;
        line-height: 40px;
    }

    .card-row3-col1-content .user-wrap {
        display: flex;
    }

    .card-row3-col1-content .username {
        margin-left: 20px;
    }

    .card-row3-col1-content .money-wrap {
        text-align: right;
    }

    .card-row3-content .el-row {
        margin-bottom: 5px;
        height: 40px;
        line-height: 40px;
        font-size: 14px;
    }

    .card-row3-content .el-divider {
        margin: 0;
    }

    .card-row3-content .number-wrap {
        text-align: right;
    }
</style>
<?php
}