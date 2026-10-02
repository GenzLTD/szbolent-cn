<?php
/**
 * import-poems.php — 在 bolent_wp 容器内运行，把 9,417 首陆游作品导入 poem CPT。
 *
 * 触发方式（deploy.yml 内已自动执行；也可手动）：
 *   docker exec -i bolent_wp php /var/www/html/wp-content/scripts/import-poems.php [limit]
 *     limit 省略 = 全量；limit=30 = 仅前 30 篇试点
 *
 * 依赖：bookstore.php mu-plugin 已被 WP 自动加载（szbolent_import_poems 可用）。
 * 幂等：按 luyou_id 去重，重复跑只更新不新增。
 */
if (php_sapi_name() !== 'cli') exit;

define('WP_USE_THEMES', false);
require_once '/var/www/html/wp-load.php';

$limit = isset($argv[1]) ? (int) $argv[1] : 0;
$json  = '/var/www/html/wp-content/uploads/bookstore/luyou.json';

if (!function_exists('szbolent_import_poems')) {
    // 兜底：mu-plugin 未自动加载时显式引入
    if (file_exists('/var/www/html/wp-content/mu-plugins/bookstore.php')) {
        require_once '/var/www/html/wp-content/mu-plugins/bookstore.php';
    }
}

if (!function_exists('szbolent_import_poems')) {
    fwrite(STDERR, "❌ 找不到 szbolent_import_poems：请确认 bookstore.php 已注入 mu-plugins\n");
    exit(1);
}
if (!file_exists($json)) {
    fwrite(STDERR, "❌ 找不到 luyou.json：$json\n");
    exit(1);
}

echo szbolent_import_poems($json, $limit) . PHP_EOL;
