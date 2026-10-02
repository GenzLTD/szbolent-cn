<?php
/**
 * 幂等激活 modown 主题 + erphpdown 插件（书店上线收尾）
 *
 * 由 .github/workflows/deploy.yml 的 "Activate modown theme + erphpdown plugin" 步
 * 在 Phase B（docker cp themes/plugins 之后）调用：
 *   docker exec bolent_wp php /var/www/html/wp-content/scripts/activate-bookstore.php
 *
 * 设计目标：
 *  - 幂等：主题已为 modown / 插件已启用时直接跳过，重跑无副作用。
 *  - 容错：modown 主题文件缺失时不切换（避免 switch_theme 把站点搞崩），仅告警。
 *  - 不依赖 wp-cli（生产容器未装），纯靠 wp-load.php + 选项 API。
 */

require '/var/www/html/wp-load.php';

$changed = false;

// 1) 激活 modown 主题（若当前不是 modown，且 modown 主题文件确实存在）
$stylesheet = get_option( 'stylesheet' );
if ( $stylesheet !== 'modown' ) {
    $modown = wp_get_theme( 'modown' );
    if ( $modown->exists() ) {
        if ( function_exists( 'switch_theme' ) ) {
            switch_theme( 'modown' );
        } else {
            // 兜底：直接写主题选项（switch_theme 不可用时）
            update_option( 'template', 'modown' );
            update_option( 'stylesheet', 'modown' );
            update_option( 'current_theme', 'Modown' );
        }
        $changed = true;
        echo "✅ 已切换主题 -> Modown\n";
    } else {
        echo "⚠️ modown 主题不存在，跳过切换（请检查 Phase B 是否注入主题文件）\n";
    }
} else {
    echo "⏭ 主题已为 Modown，跳过\n";
}

// 2) 启用 erphpdown 插件（若未启用）
$plugin = 'erphpdown/erphpdown.php';
$active = get_option( 'active_plugins', array() );
if ( ! in_array( $plugin, $active, true ) ) {
    $active[] = $plugin;
    update_option( 'active_plugins', $active );
    $changed = true;
    echo "✅ 已启用插件 erphpdown\n";
} else {
    echo "⏭ erphpdown 已启用，跳过\n";
}

echo $changed
    ? "🎉 书店激活完成（本次有变更）\n"
    : "🎉 书店激活完成（无变更，幂等）\n";
