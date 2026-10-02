<?php
/**
 * 个人主页模板用辅助函数
 * 供 template/page-personal.php 使用
 */

/**
 * 解析 personal_nav_items 项的链接。支持 type: vip/charge/user/aff/custom。
 * type 为 vip/charge/user/aff 时可省略 url，由 modown/erphpdown 生成。
 *
 * @param array $item 导航项
 * @return string 解析后的 URL，空串表示该项不显示
 */
function personal_resolve_nav_url($item) {
    $type = isset($item['type']) ? $item['type'] : 'custom';
    if (!empty($item['url'])) {
        return $item['url'];
    }
    if ($type === 'custom') {
        return '';
    }

    $user_id = function_exists('MBThemes_page') ? MBThemes_page('template/user.php') : 0;
    if (!$user_id) {
        return '';
    }
    $base = get_permalink($user_id);

    switch ($type) {
        case 'user':
            return $base;
        case 'vip':
            if (!(defined('ERPHPDOWN_IS_ACTIVE') && ERPHPDOWN_IS_ACTIVE)) {
                return '';
            }
            if (function_exists('_MBT') && _MBT('vip_hidden')) {
                return '';
            }
            $vip_url = get_option('erphp_url_front_vip');
            return $vip_url ? $vip_url : add_query_arg('action', 'vip', $base);
        case 'charge':
            if (!(defined('ERPHPDOWN_IS_ACTIVE') && ERPHPDOWN_IS_ACTIVE)) {
                return '';
            }
            return add_query_arg('action', 'charge', $base);
        case 'aff':
            if (!(defined('ERPHPDOWN_IS_ACTIVE') && ERPHPDOWN_IS_ACTIVE)) {
                return '';
            }
            if (function_exists('_MBT') && _MBT('user_aff')) {
                return '';
            }
            return add_query_arg('action', 'aff', $base);
        default:
            return '';
    }
}

/**
 * 预设 type 的默认标题
 *
 * @param string $type vip|charge|user|aff
 * @return string
 */
function personal_nav_default_title($type) {
    $titles = array('vip' => '升级VIP', 'charge' => '在线充值', 'user' => '用户中心', 'aff' => '我的推广');
    return isset($titles[$type]) ? $titles[$type] : '';
}
