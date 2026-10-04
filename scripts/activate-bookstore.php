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

// 1) 激活主题：优先子主题 modown-child（承载放翁文库国风改版），缺失时退回 modown
$desired_child  = 'modown-child';
$desired_parent = 'modown';
$current_stylesheet = wp_get_theme()->get_stylesheet();
$child  = wp_get_theme( $desired_child );
$parent = wp_get_theme( $desired_parent );

if ( $child->exists() && $parent->exists() ) {
    $target = $desired_child;
} elseif ( $parent->exists() ) {
    $target = $desired_parent;
} else {
    $target = null;
}

if ( $target && $current_stylesheet !== $target ) {
    if ( function_exists( 'switch_theme' ) ) {
        switch_theme( $target );
    } else {
        // 兜底：直接写主题选项（switch_theme 不可用时）
        if ( $target === $desired_child ) {
            update_option( 'template', 'modown' );
            update_option( 'stylesheet', 'modown-child' );
            update_option( 'current_theme', '放翁文库 (Modown Child)' );
        } else {
            update_option( 'template', 'modown' );
            update_option( 'stylesheet', 'modown' );
            update_option( 'current_theme', 'Modown' );
        }
    }
    $changed = true;
    echo "✅ 已切换主题 -> " . ( $target === $desired_child ? 'Modown Child (放翁文库)' : 'Modown' ) . "\n";
} elseif ( $target ) {
    echo "⏭ 主题已为 " . ( $target === $desired_child ? 'Modown Child' : 'Modown' ) . "，跳过\n";
} else {
    echo "⚠️ modown 主题不存在，跳过切换（请检查 Phase B 是否注入主题文件）\n";
}

// 1.5) 切换主题后，若子主题函数已就位，则在 CLI 内显式跑一遍初始化（CLI 上下文无前端超时压力）
//      注意：wp-load 时 init 已触发，子主题 functions.php 尚未加载，故需手动注册并调用。
if ( $target === $desired_child ) {
    $child_func = get_stylesheet_directory() . '/functions.php';
    if ( ! function_exists( 'fw_register_poem_taxonomies' ) && file_exists( $child_func ) ) {
        require_once $child_func;
    }
    if ( function_exists( 'fw_register_poem_taxonomies' ) ) {
        fw_register_poem_taxonomies();   // 注册 poem_genre / poem_cipai 分类法
        flush_rewrite_rules( false );    // 软刷新重写规则，使 /poem-genre/ /poem-cipai/ 生效（不动 .htaccess）
        // 先回填，使 诗/词/词牌 术语就位，菜单与前端链接才能取到真实 term link
        $guard = 0;
        while ( get_transient( 'fw_backfill_page' ) !== -1 && $guard < 300 ) {
            fw_maybe_backfill_taxonomies();
            $guard ++;
        }
        fw_ensure_main_menu();           // 创建并指派主导航（修复菜单警告；此时 fw_term_link 可解析真实术语链接）
        echo "✅ 诗词分类法回填完成\n";
    } else {
        echo "⚠️ 子主题 functions.php 未找到或未定义初始化函数，跳过回填（前台访问时将自动续跑）\n";
    }
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
