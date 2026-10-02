<?php
/**
 * Modown 主题数据库表结构统一定义
 * 依据 modown-database-plan.md：表单、字段、权限
 *
 * 提供：
 *   modown_schema_install_users()  — wp_users 扩展列（qqid, sinaid, weixinid, weixin_unionid, mobile）
 *   modown_schema_install()        — 主题自定义表：collects, checkins, tickets, ticket_item, notices,
 *                                    ice_tuan, ice_tuan_order, ice_draws, activitys, erphp_loggedin, search_words
 *
 * 在 after_switch_theme 的 MBTheme_active_theme 中 require 本文件并调用上述函数。
 * 使用 dbDelta 以支持后续增列、修类型的平滑升级。
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * 扩展 wp_users：qqid, sinaid, weixinid, weixin_unionid, mobile
 * 仅在列不存在时 ALTER ADD，避免重复执行报错。
 */
function modown_schema_install_users() {
	global $wpdb;
	$users = $wpdb->users;

	$columns = array(
		'qqid'            => 'varchar(100)',
		'sinaid'          => 'varchar(100)',
		'weixinid'        => 'varchar(100)',
		'weixin_unionid'  => 'varchar(200)',
		'mobile'          => 'varchar(20)',
	);

	foreach ( $columns as $col => $def ) {
		$has = $wpdb->get_results( "SELECT {$col} FROM {$users} LIMIT 0", ARRAY_A );
		// 若列不存在，SELECT 会报错或 get_results 为 null；用 SHOW COLUMNS 更稳
		$show = $wpdb->get_results( "SHOW COLUMNS FROM {$users} LIKE '{$col}'", ARRAY_A );
		if ( empty( $show ) ) {
			$wpdb->query( "ALTER TABLE {$users} ADD {$col} {$def}" );
		}
	}
}

/**
 * 主题自定义表：使用 dbDelta 创建/升级
 * 需先 require ABSPATH . 'wp-admin/includes/upgrade.php'，调用方（base.php）已包含。
 */
function modown_schema_install() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$p   = $wpdb->prefix;
	$chs = $wpdb->get_charset_collate();
	if ( empty( $chs ) ) {
		$chs = 'DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
	}

	$tables = array(

		$p . 'collects' => "CREATE TABLE {$p}collects (
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  post_id bigint(20) NOT NULL,
  create_time datetime NOT NULL,
  PRIMARY KEY  (ID),
  KEY user_id (user_id),
  KEY post_id (post_id)
) {$chs};",

		$p . 'checkins' => "CREATE TABLE {$p}checkins (
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  credit varchar(10) DEFAULT NULL,
  create_time datetime NOT NULL,
  user_ip varchar(50) DEFAULT NULL,
  PRIMARY KEY  (ID),
  KEY user_id (user_id)
) {$chs};",

		$p . 'tickets' => "CREATE TABLE {$p}tickets (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  type int(1) NOT NULL,
  number varchar(50) NOT NULL,
  email varchar(200) DEFAULT NULL,
  status int(1) NOT NULL DEFAULT 0,
  score int(11) DEFAULT NULL,
  note text NOT NULL,
  create_time datetime DEFAULT NULL,
  PRIMARY KEY  (id),
  KEY user_id (user_id)
) {$chs};",

		$p . 'ticket_item' => "CREATE TABLE {$p}ticket_item (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  ticket_id int(11) NOT NULL,
  type int(1) NOT NULL,
  note text NOT NULL,
  image varchar(500) NOT NULL,
  create_time datetime DEFAULT NULL,
  PRIMARY KEY  (id),
  KEY ticket_id (ticket_id)
) {$chs};",

		$p . 'notices' => "CREATE TABLE {$p}notices (
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) NOT NULL,
  post_id bigint(20) DEFAULT NULL,
  title varchar(500) DEFAULT NULL,
  message text NOT NULL,
  type_key varchar(50) DEFAULT NULL,
  type_value varchar(50) DEFAULT NULL,
  create_time datetime NOT NULL,
  PRIMARY KEY  (ID),
  KEY user_id (user_id)
) {$chs};",

		$p . 'ice_tuan' => "CREATE TABLE {$p}ice_tuan (
  ice_id int(11) NOT NULL AUTO_INCREMENT,
  ice_num varchar(50) NOT NULL,
  ice_post int(11) NOT NULL,
  ice_status int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (ice_id),
  KEY ice_post (ice_post)
) {$chs};",

		$p . 'ice_tuan_order' => "CREATE TABLE {$p}ice_tuan_order (
  ice_id int(11) NOT NULL AUTO_INCREMENT,
  ice_num varchar(50) NOT NULL,
  ice_tuan_num varchar(50) NOT NULL,
  ice_price double(10,2) NOT NULL,
  ice_post int(11) NOT NULL,
  ice_user_id int(11) NOT NULL,
  ice_status int(11) NOT NULL DEFAULT 0,
  ice_time datetime NOT NULL,
  PRIMARY KEY  (ice_id),
  KEY ice_user_id (ice_user_id)
) {$chs};",

		$p . 'ice_draws' => "CREATE TABLE {$p}ice_draws (
  ID int(11) NOT NULL AUTO_INCREMENT,
  num varchar(16) DEFAULT NULL,
  user_id bigint(20) NOT NULL,
  price double(10,2) NOT NULL,
  status int(2) NOT NULL DEFAULT 0,
  result varchar(50) DEFAULT NULL,
  create_time datetime NOT NULL,
  PRIMARY KEY  (ID),
  KEY user_id (user_id)
) {$chs};",

		$p . 'activitys' => "CREATE TABLE {$p}activitys (
  ID bigint(20) NOT NULL AUTO_INCREMENT,
  user_id bigint(20) DEFAULT NULL,
  user_activity varchar(20) DEFAULT NULL,
  activity_key varchar(50) DEFAULT NULL,
  activity_value varchar(50) DEFAULT NULL,
  create_time datetime NOT NULL,
  PRIMARY KEY  (ID),
  KEY user_id (user_id)
) {$chs};",

		$p . 'erphp_loggedin' => "CREATE TABLE {$p}erphp_loggedin (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  user_id int(10) NOT NULL,
  logged_ip varchar(100) DEFAULT NULL,
  logged_at datetime NOT NULL,
  PRIMARY KEY  (id),
  KEY user_id (user_id)
) {$chs};",

		$p . 'search_words' => "CREATE TABLE {$p}search_words (
  id int(11) NOT NULL AUTO_INCREMENT,
  keyword varchar(500) DEFAULT NULL,
  create_time datetime NOT NULL,
  user_ip varchar(80) DEFAULT NULL,
  PRIMARY KEY  (id),
  KEY create_time (create_time)
) {$chs};",

	);

	foreach ( $tables as $name => $sql ) {
		dbDelta( $sql );
	}
}
