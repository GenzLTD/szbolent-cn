<?php
/**
 * 书店站点身份一次性设置：站名 / 副标题 / 站点图标
 *
 * 用法（在 WP 容器内）：
 *   docker exec bolent_wp php /var/www/html/wp-content/scripts/set-site-identity.php
 *   docker exec bolent_wp php /var/www/html/wp-content/scripts/set-site-identity.php --force
 *   docker exec bolent_wp php /var/www/html/wp-content/scripts/set-site-identity.php /path/to/icon.png
 *
 * 设计：
 *  - 幂等：已是目标值就跳过；--force 时强制改写站名/描述并重建图标附件。
 *  - 不进 deploy.yml：站名/描述归博主，CI 每次部署重写等于夺走后台设置权。
 *    本脚本只在「书店门面首次立起来」时手动跑一次，之后博主在后台改不会被覆盖。
 *  - 站点图标：先把 PNG 放进 uploads（本脚本不下载任何东西），再注册为附件 + site_icon。
 *  - 不依赖 wp-cli（容器没装）。
 */

require '/var/www/html/wp-load.php';

$force = in_array( '--force', $argv, true );
$icon  = '';
foreach ( $argv as $a ) {
	if ( 0 === strpos( $a, '/' ) ) { $icon = $a; }
}
if ( '' === $icon ) {
	$icon = '/var/www/html/wp-content/uploads/2026/10/fangweng-icon.png';
}

$name = '放翁文库';
$desc = '陆游诗词在线文库：诗九千余首、词百余阕，按体裁分栏';

$changed = false;

// 1) 站名
if ( $force || get_option( 'blogname' ) !== $name ) {
	update_option( 'blogname', $name );
	$changed = true;
	echo "✅ 站名 -> {$name}\n";
} else {
	echo "⏭ 站名已为 {$name}，跳过\n";
}

// 2) 副标题（首页 description / title 后缀）
if ( $force || get_option( 'blogdescription' ) !== $desc ) {
	update_option( 'blogdescription', $desc );
	$changed = true;
	echo "✅ 副标题 -> {$desc}\n";
} else {
	echo "⏭ 副标题已是目标值，跳过\n";
}

// 3) 站点图标
$current = (int) get_option( 'site_icon' );
if ( ! file_exists( $icon ) ) {
	echo "⚠️ 图标文件不存在：{$icon}（先把 PNG 放进 uploads 再跑，图标这一步跳过）\n";
} elseif ( ! $force && $current && get_post( $current ) ) {
	echo "⏭ 站点图标已设置（attachment {$current}），跳过（--force 可强制重建）\n";
} else {
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$uploads = wp_upload_dir();
	$rel     = ltrim( str_replace( $uploads['basedir'], '', $icon ), '/' );
	$url     = trailingslashit( $uploads['baseurl'] ) . $rel;

	// 已注册过同名附件就复用，避免重复插入
	$existing = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'meta_key'       => '_wp_attached_file',
		'meta_value'     => $rel,
		'posts_per_page' => 1,
		'fields'         => 'ids',
	) );

	if ( ! empty( $existing ) ) {
		$attach_id = (int) $existing[0];
	} else {
		$attach_id = wp_insert_attachment( array(
			'guid'           => $url,
			'post_mime_type' => 'image/png',
			'post_title'     => $name . ' 站点图标',
			'post_status'    => 'inherit',
		), $icon );
	}

	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		echo "❌ 图标附件写入失败\n";
	} else {
		$meta = wp_generate_attachment_metadata( $attach_id, $icon );
		wp_update_attachment_metadata( $attach_id, $meta );
		update_option( 'site_icon', $attach_id );
		$changed = true;
		echo "✅ 站点图标 -> attachment {$attach_id}（{$url}）\n";
	}
}

echo $changed ? "🎉 站点身份设置完成（本次有变更）\n" : "🎉 站点身份设置完成（无变更，幂等）\n";
