<?php 
/*
	Template Name: 个人主页展示
	Description: 基于 xygrzy-master 的个人主页展示模板，支持个人信息、技能、音乐、主题切换等功能
*/
?>
<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
  <meta http-equiv="Cache-Control" content="no-siteapp">
  <title><?php echo esc_html(get_post_meta(get_the_ID(), 'personal_name', true) ?: get_bloginfo('name')); ?> - <?php bloginfo('name'); ?></title>
  <?php
  $pn = get_post_meta(get_the_ID(), 'personal_name', true) ?: get_bloginfo('name');
  $pdesc = get_post_meta(get_the_ID(), 'personal_description', true);
  $pkw = get_post_meta(get_the_ID(), 'personal_keywords', true);
  $pavatar = get_post_meta(get_the_ID(), 'personal_avatar', true);
  ?>
  <meta name="description" content="<?php echo esc_attr($pdesc ?: get_bloginfo('description')); ?>">
  <?php if ($pkw): ?><meta name="keywords" content="<?php echo esc_attr($pkw); ?>"><?php endif; ?>
  <meta name="author" content="<?php echo esc_attr($pn); ?>">
  <meta property="og:image" content="<?php echo esc_url($pavatar ?: get_site_icon_url(512)); ?>">
  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/static/fonts/xwzk.woff2" as="font" type="font/woff2" crossorigin>
  <?php /* Font Awesome：add_filter('modown_personal_font_awesome_url', fn($u)=>'https://cdn.jsdelivr.net/npm/font-awesome@6.0.0/css/all.min.css') 可换 jsDelivr 或本地 */ $fa_url = apply_filters('modown_personal_font_awesome_url', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'); ?>
  <link rel="stylesheet" href="<?php echo esc_url($fa_url); ?>" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()); ?>/static/css/page-personal.css">
  <?php wp_head(); ?>
  <style>
  body.dark-mode {
    background: url('<?php echo esc_url(get_post_meta(get_the_ID(), 'dark_bg_image', true) ?: get_template_directory_uri() . '/static/img/xk.jpg'); ?>') no-repeat center center fixed;
    background-size: cover;
  }
  </style>

</head>
<body>
<?php
// 获取自定义字段数据，如果没有则使用默认值
$default_avatar = get_template_directory_uri() . '/static/img/avatar.png';
$personal_avatar = get_post_meta(get_the_ID(), 'personal_avatar', true) ?: $default_avatar;

$personal_name = get_post_meta(get_the_ID(), 'personal_name', true);
if (!$personal_name) {
    $personal_name = get_bloginfo('name') ?: '新生代集群社区';
}

$personal_slogan = get_post_meta(get_the_ID(), 'personal_slogan', true);
if (!$personal_slogan) {
    $personal_slogan = '欢迎来到我的主页';
}

$personal_location = get_post_meta(get_the_ID(), 'personal_location', true) ?: '';
$personal_gender = get_post_meta(get_the_ID(), 'personal_gender', true) ?: '';
$personal_age = get_post_meta(get_the_ID(), 'personal_age', true) ?: '';

// 站点导航 - 始终显示，有默认值
$nav_items = get_post_meta(get_the_ID(), 'personal_nav_items', true);
if ($nav_items) {
    $nav_items = json_decode($nav_items, true);
}
if (!$nav_items || !is_array($nav_items)) {
    // 默认导航链接
    $nav_items = array(
        array('title' => '首页', 'url' => home_url()),
        array('title' => '博客', 'url' => home_url('/blog')),
    );
}

// 技能数据 - 始终显示，有默认值
$skills_data = get_post_meta(get_the_ID(), 'personal_skills', true);
if ($skills_data) {
    $skills_data = json_decode($skills_data, true);
}
if (!$skills_data || !is_array($skills_data)) {
    // 默认技能数据
    $skills_data = array(
        array('name' => 'HTML', 'icon' => 'fa-brands fa-html5', 'percentage' => 85, 'color' => 'linear-gradient(90deg, #e34f26, #f06529)'),
        array('name' => 'CSS', 'icon' => 'fa-brands fa-css3-alt', 'percentage' => 70, 'color' => 'linear-gradient(90deg, #264de4, #2965f1)'),
        array('name' => 'JavaScript', 'icon' => 'fa-brands fa-js', 'percentage' => 65, 'color' => 'linear-gradient(90deg, #f0db4f, #f7df1e)'),
        array('name' => 'PHP', 'icon' => 'fa-brands fa-php', 'percentage' => 75, 'color' => 'linear-gradient(90deg, #777bb3, #8993be)'),
    );
}

// 音乐设置
$music_title = get_post_meta(get_the_ID(), 'music_title', true);
if (!$music_title) {
    $music_title = '像我这样的人';
}

$music_artist = get_post_meta(get_the_ID(), 'music_artist', true);
if (!$music_artist) {
    $music_artist = '毛不易';
}

// 对标 xygrzy：无配置时使用主题自带默认音乐
$music_url = get_post_meta(get_the_ID(), 'music_url', true) ?: (get_template_directory_uri() . '/static/audio/xwzydr.mp3');
$personal_icp = get_post_meta(get_the_ID(), 'personal_icp', true) ?: '';
?>
    <div class="personal-container">
        <div class="header">
            <img src="<?php echo esc_url($personal_avatar); ?>" alt="<?php echo esc_attr($personal_name); ?>" class="avatar" onerror="this.onerror=null;this.src='<?php echo esc_url($default_avatar); ?>'">
            <h1 class="name"><?php echo esc_html($personal_name); ?></h1>
            <p class="slogan"><?php echo esc_html($personal_slogan); ?></p>
            
            <div class="info-section">
                <?php if ($personal_location): ?>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo esc_html($personal_location); ?>
                </div>
                <?php endif; ?>
                <?php if ($personal_gender): ?>
                <div class="info-item">
                    <i class="fas fa-male"></i>
                    <?php echo esc_html($personal_gender); ?>
                </div>
                <?php endif; ?>
                <?php if ($personal_age): ?>
                <div class="info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo esc_html($personal_age); ?>
                </div>
                <?php endif; ?>
                <button class="theme-toggle" id="themeToggle">
                    <i class="fas fa-moon"></i> 黑夜
                </button>
            </div>
        </div>
        
        <!-- 站点导航 - 始终显示 -->
        <div class="nav-section">
            <h2 class="section-title">我的站点</h2>
            <div class="nav-menu">
                <?php foreach ($nav_items as $item): 
                    $href = personal_resolve_nav_url($item);
                    if ( $href === '' ) continue;
                    $type = isset($item['type']) ? $item['type'] : 'custom';
                    $title = ! empty($item['title']) ? $item['title'] : personal_nav_default_title($type);
                    if ( $title === '' ) $title = ! empty($item['url']) ? __('链接', 'modown') : '';
                    if ( $title === '' ) continue;
                    $cls = 'nav-item' . ( ! empty($item['highlight']) ? ' nav-item--highlight' : '' );
                    $target = in_array($type, array('vip','charge','user','aff'), true) ? '_self' : '_blank';
                ?>
                    <a href="<?php echo esc_url($href); ?>" class="<?php echo esc_attr($cls); ?>" target="<?php echo esc_attr($target); ?>"><?php if ( ! empty($item['icon']) ): ?><i class="fas <?php echo esc_attr($item['icon']); ?>"></i> <?php endif; ?><?php echo esc_html($title); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php
        // Lyanna 2.3：推广 CTA（在「我的站点」与 erphp 四块之间）
        $personal_promo_raw = get_post_meta(get_the_ID(), 'personal_promo', true);
        $promo = $personal_promo_raw ? json_decode($personal_promo_raw, true) : null;
        if ($promo && is_array($promo) && (!empty($promo['title']) || !empty($promo['buttons']))):
            $promo_title = isset($promo['title']) ? $promo['title'] : '';
            $promo_btns = isset($promo['buttons']) && is_array($promo['buttons']) ? $promo['buttons'] : array();
        ?>
        <div class="erphp-block">
            <?php if ($promo_title): ?><h2 class="section-title"><?php echo esc_html($promo_title); ?></h2><?php endif; ?>
            <div style="display:flex; flex-wrap:wrap; gap:10px;">
                <?php foreach ($promo_btns as $b): if (empty($b['url'])) continue; ?>
                <a href="<?php echo esc_url($b['url']); ?>" class="erphp-btn" target="_blank" rel="noopener"><?php if (!empty($b['icon'])): ?><i class="fas <?php echo esc_attr($b['icon']); ?>"></i> <?php endif; ?><?php echo esc_html(isset($b['text']) ? $b['text'] : $b['url']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php
        $user_id = function_exists('MBThemes_page') ? MBThemes_page('template/user.php') : 0;
        $user_url = $user_id ? get_permalink($user_id) : '';
        $erphp_active = defined('ERPHPDOWN_IS_ACTIVE') && ERPHPDOWN_IS_ACTIVE;
        $vip_hidden = function_exists('_MBT') && _MBT('vip_hidden');
        $user_aff = function_exists('_MBT') && _MBT('user_aff');
        $vip_url = '';
        if ( $erphp_active && $user_id && ! $vip_hidden ) {
            $vip_url = get_option('erphp_url_front_vip');
            if ( ! $vip_url ) $vip_url = add_query_arg('action', 'vip', $user_url);
        }
        $charge_url = ( $erphp_active && $user_id ) ? add_query_arg('action', 'charge', $user_url) : '';
        $aff_url = ( $erphp_active && $user_id && ! $user_aff ) ? add_query_arg('action', 'aff', $user_url) : '';
        $show_vip = ( get_post_meta(get_the_ID(), 'personal_show_vip', true) === '1' ) && $erphp_active && ! $vip_hidden && $vip_url;
        $show_charge = ( get_post_meta(get_the_ID(), 'personal_show_charge', true) === '1' ) && $erphp_active && $user_id;
        $show_aff = ( get_post_meta(get_the_ID(), 'personal_show_aff', true) === '1' ) && $erphp_active && ! $user_aff && $user_id && get_option('erphp_aff_money');
        $show_uc = ( get_post_meta(get_the_ID(), 'personal_show_user_center', true) === '1' ) && $user_id;
        ?>
        <?php if ( $show_vip ): 
            $vip_title = get_post_meta(get_the_ID(), 'personal_vip_block_title', true) ?: ( get_option('erphp_vip_name') ?: '升级VIP' );
            $vip_desc = get_post_meta(get_the_ID(), 'personal_vip_block_desc', true) ?: ( function_exists('_MBT') ? _MBT('vip_desc') : '' );
        ?>
        <div class="erphp-block">
            <h2 class="section-title"><?php echo esc_html($vip_title); ?></h2>
            <?php if ( $vip_desc ): ?><p class="erphp-desc"><?php echo esc_html($vip_desc); ?></p><?php endif; ?>
            <a href="<?php echo esc_url($vip_url); ?>" class="erphp-btn" target="_self">立即升级</a>
        </div>
        <?php endif; ?>
        <?php if ( $show_charge ): 
            $ch_title = get_post_meta(get_the_ID(), 'personal_charge_block_title', true) ?: '在线充值';
            $prop = get_option('ice_proportion_alipay');
            $ice_name = get_option('ice_name_alipay') ?: '积分';
            $ch_desc = ( function_exists('_MBT') && _MBT('recharge_default') ) ? '' : ( $prop ? sprintf('1 元 = %s %s', $prop, $ice_name) : '' );
        ?>
        <div class="erphp-block">
            <h2 class="section-title"><?php echo esc_html($ch_title); ?></h2>
            <?php if ( $ch_desc ): ?><p class="erphp-desc"><?php echo esc_html($ch_desc); ?></p><?php endif; ?>
            <a href="<?php echo esc_url($charge_url); ?>" class="erphp-btn" target="_self">立即充值</a>
        </div>
        <?php endif; ?>
        <?php if ( $show_aff ): 
            $aff_title = get_post_meta(get_the_ID(), 'personal_aff_block_title', true) ?: '我的推广';
            $aff_desc = get_post_meta(get_the_ID(), 'personal_aff_block_desc', true);
        ?>
        <div class="erphp-block">
            <h2 class="section-title"><?php echo esc_html($aff_title); ?></h2>
            <?php if ( $aff_desc ): ?><p class="erphp-desc"><?php echo esc_html($aff_desc); ?></p><?php endif; ?>
            <a href="<?php echo esc_url($aff_url); ?>" class="erphp-btn" target="_self">推广详情</a>
        </div>
        <?php endif; ?>
        <?php if ( $show_uc ): ?>
        <div class="erphp-block">
            <h2 class="section-title">用户中心</h2>
            <a href="<?php echo esc_url($user_url); ?>" class="erphp-btn" target="_self"><?php echo is_user_logged_in() ? '进入用户中心' : '登录/注册'; ?></a>
        </div>
        <?php endif; ?>
        
        <!-- 技能展示 - 始终显示 -->
        <div class="skills-section">
            <h2 class="section-title">我的技能</h2>
            <div class="skills-grid">
                <?php foreach ($skills_data as $skill): ?>
                <div class="skill-item">
                    <div class="skill-header">
                        <div class="skill-name">
                            <i class="<?php echo esc_attr($skill['icon']); ?>"></i> <?php echo esc_html($skill['name']); ?>
                        </div>
                        <span class="skill-percentage"><?php echo esc_html($skill['percentage']); ?>%</span>
                    </div>
                    <div class="skill-bar">
                        <div class="skill-progress" data-percentage="<?php echo esc_attr($skill['percentage']); ?>" style="background: <?php echo esc_attr($skill['color']); ?>;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="music-section">
            <div class="song-info">
                <div class="song-title"><?php echo esc_html($music_title); ?></div>
                <div class="song-artist"><?php echo esc_html($music_artist); ?></div>
            </div>
            
            <div class="lyrics-container" id="lyricsContainer">
                <!-- 歌词将通过 JavaScript 动态加载 -->
            </div>
            
            <div class="progress-container" id="progressContainer">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            
            <div class="player-controls">
                <span class="time-info" id="currentTime">00:00</span>
                <button class="play-btn" id="playBtn">
                    <i class="fas fa-play"></i>
                </button>
                <span class="time-info" id="totalTime">00:00</span>
            </div>
            
            <audio id="audioPlayer" src="<?php echo esc_url($music_url); ?>"></audio>
        </div>
        
        <?php
        // Lyanna 2.1：社交、友链、自定义 HTML、订阅本站（在音乐与名言之间）
        $personal_sns = get_post_meta(get_the_ID(), 'personal_sns', true);
        $sns = $personal_sns ? json_decode($personal_sns, true) : null;
        $sns_urls = array(
            'github' => 'https://github.com/%s',
            'twitter' => 'https://twitter.com/%s',
            'email' => 'mailto:%s',
            'zhihu' => 'https://www.zhihu.com/people/%s',
            'douban' => 'https://www.douban.com/people/%s',
            'linkedin' => 'https://www.linkedin.com/in/%s',
        );
        $sns_icons = array('github' => 'fa-brands fa-github', 'twitter' => 'fa-brands fa-twitter', 'email' => 'fas fa-envelope', 'zhihu' => 'fas fa-link', 'douban' => 'fas fa-link', 'linkedin' => 'fa-brands fa-linkedin-in');
        $sns_img_keys = array('wechat', 'weixingongzhonghao');
        $upload_base = get_template_directory_uri() . '/static/upload/';
        if ($sns && is_array($sns)):
        ?>
        <div class="erphp-block">
            <h2 class="section-title">社交链接</h2>
            <div class="lyanna-sns" style="display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                <?php foreach ($sns as $k => $v): if ($v === '' || $v === null) continue;
                    if (in_array($k, $sns_img_keys, true)) {
                        $img = (strpos($v, 'http://') === 0 || strpos($v, 'https://') === 0) ? $v : $upload_base . ltrim($v, '/');
                        $label = ($k === 'wechat') ? '微信' : '公众号';
                        ?><a href="<?php echo esc_url($img); ?>" target="_blank" rel="noopener" title="<?php echo esc_attr($label); ?>"><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($label); ?>" style="max-height:48px;max-width:48px;vertical-align:middle;" /></a><?php
                    } elseif (isset($sns_urls[$k])) {
                        $href = sprintf($sns_urls[$k], trim($v));
                        $icon = isset($sns_icons[$k]) ? $sns_icons[$k] : 'fas fa-link';
                        ?><a href="<?php echo esc_url($href); ?>" target="_blank" rel="noopener" class="lyanna-sns-link"><i class="<?php echo esc_attr($icon); ?>"></i> <?php echo esc_html($k); ?></a><?php
                    }
                endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php
        $personal_blogroll = get_post_meta(get_the_ID(), 'personal_blogroll', true);
        $blogroll = $personal_blogroll ? json_decode($personal_blogroll, true) : null;
        if ($blogroll && is_array($blogroll)):
        ?>
        <div class="erphp-block">
            <h2 class="section-title">友情链接</h2>
            <div class="lyanna-blogroll" style="display:flex; flex-wrap:wrap; gap:10px;">
                <?php foreach ($blogroll as $l): if (empty($l['url'])) continue; ?>
                <a href="<?php echo esc_url($l['url']); ?>" target="_blank" rel="noopener"><?php echo esc_html(isset($l['title']) ? $l['title'] : $l['url']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php
        $personal_html_blocks = get_post_meta(get_the_ID(), 'personal_html_blocks', true);
        $html_blocks = $personal_html_blocks ? json_decode($personal_html_blocks, true) : null;
        if ($html_blocks && is_array($html_blocks)): foreach ($html_blocks as $blk):
            if (empty($blk['body'])) continue;
        ?>
        <div class="erphp-block">
            <?php if (!empty($blk['title'])): ?><h2 class="section-title"><?php echo esc_html($blk['title']); ?></h2><?php endif; ?>
            <div class="lyanna-html"><?php echo wp_kses_post($blk['body']); ?></div>
        </div>
        <?php endforeach; endif; ?>
        
        <?php
        // Lyanna 2.2：最新评论（自定义 HTML 与订阅本站之间）
        if (get_post_meta(get_the_ID(), 'personal_show_latest_comments', true) === '1') {
            $n = (int) get_post_meta(get_the_ID(), 'personal_latest_comments_count', true);
            if ($n < 1) $n = 5;
            $comments = get_comments(array('number' => $n, 'status' => 'approve', 'orderby' => 'comment_date'));
            if (!empty($comments)):
        ?>
        <div class="erphp-block">
            <h2 class="section-title">最新评论</h2>
            <ul class="lyanna-list" style="list-style:none; padding:0; margin:0;">
                <?php foreach ($comments as $c):
                    $author = $c->comment_author ?: __('匿名', 'modown');
                    $excerpt = wp_trim_words($c->comment_content, 15);
                    $post_title = get_the_title($c->comment_post_ID);
                    $post_link = get_permalink($c->comment_post_ID);
                    $avatar_id = $c->user_id ? $c->user_id : $c->comment_author_email;
                ?>
                <li style="padding:8px 0; border-bottom:1px solid rgba(0,0,0,0.06);">
                    <?php echo get_avatar($avatar_id, 48, '', $author, array('style' => 'vertical-align:middle; margin-right:10px; border-radius:50%;')); ?>
                    <span><?php echo esc_html($author); ?></span>：<?php echo esc_html($excerpt); ?>
                    <a href="<?php echo esc_url($post_link); ?>" style="color:#4a89dc;"><?php echo esc_html($post_title); ?></a>
                    <span style="font-size:12px; color:#999;"><?php echo esc_html($c->comment_date); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; }
        // Lyanna 2.2：最热文章
        if (get_post_meta(get_the_ID(), 'personal_show_most_viewed', true) === '1') {
            $n = (int) get_post_meta(get_the_ID(), 'personal_most_viewed_count', true);
            if ($n < 1) $n = 5;
            $mq = new WP_Query(array('posts_per_page' => $n, 'orderby' => 'comment_count', 'post_type' => 'post', 'post_status' => 'publish'));
            if ($mq->have_posts()):
        ?>
        <div class="erphp-block">
            <h2 class="section-title">最热文章</h2>
            <ul class="lyanna-list" style="list-style:none; padding:0; margin:0;">
                <?php while ($mq->have_posts()): $mq->the_post(); ?>
                <li style="padding:6px 0; border-bottom:1px solid rgba(0,0,0,0.06);">
                    <a href="<?php echo esc_url(get_permalink()); ?>" style="color:#4a89dc;"><?php the_title(); ?></a>
                    <span style="font-size:12px; color:#999;">（<?php echo get_comments_number(); ?> 评）</span>
                </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php endif; }
        // Lyanna 2.2：标签云
        if (get_post_meta(get_the_ID(), 'personal_show_tagcloud', true) === '1') {
            $n = (int) get_post_meta(get_the_ID(), 'personal_tagcloud_count', true);
            if ($n < 1) $n = 20;
            $tag_html = wp_tag_cloud(array('number' => $n, 'echo' => false));
            if ($tag_html):
        ?>
        <div class="erphp-block">
            <h2 class="section-title">标签云</h2>
            <div class="lyanna-tagcloud"><?php echo $tag_html; ?></div>
        </div>
        <?php endif; }
        ?>
        
        <?php if (get_post_meta(get_the_ID(), 'personal_show_feed', true) === '1'): 
            $rss = get_bloginfo('rss2_url');
            $rss_enc = urlencode($rss);
        ?>
        <div class="erphp-block">
            <h2 class="section-title">订阅本站</h2>
            <p class="erphp-desc">通过 RSS 或阅读器订阅更新</p>
            <div style="display:flex; flex-wrap:wrap; gap:10px;">
                <a href="<?php echo esc_url($rss); ?>" class="erphp-btn" target="_blank" rel="noopener">RSS</a>
                <a href="<?php echo esc_url('https://feedly.com/i/subscription/feed/' . $rss_enc); ?>" class="erphp-btn" target="_blank" rel="noopener">Feedly</a>
                <a href="<?php echo esc_url('https://www.inoreader.com/add_feed/?feed=' . $rss_enc); ?>" class="erphp-btn" target="_blank" rel="noopener">Inoreader</a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php
        // Lyanna 2.3：我的收藏（订阅本站与名言之间）
        $personal_favorites_raw = get_post_meta(get_the_ID(), 'personal_favorites', true);
        $favs = $personal_favorites_raw ? json_decode($personal_favorites_raw, true) : null;
        if ($favs && is_array($favs) && count($favs) > 0):
        ?>
        <div class="erphp-block">
            <h2 class="section-title">我的收藏</h2>
            <div class="lyanna-favorites" style="display:flex; flex-wrap:wrap; gap:12px;">
                <?php foreach ($favs as $f):
                    if (empty($f['url'])) continue;
                    $type = isset($f['type']) ? $f['type'] : 'link';
                    $title = isset($f['title']) ? $f['title'] : $f['url'];
                    $cover = isset($f['cover']) ? $f['cover'] : '';
                    $note = isset($f['note']) ? $f['note'] : '';
                    $type_icons = array('movie' => 'fa-film', 'book' => 'fa-book', 'game' => 'fa-gamepad');
                    $icon = isset($type_icons[$type]) ? $type_icons[$type] : 'fa-link';
                ?>
                <a href="<?php echo esc_url($f['url']); ?>" target="_blank" rel="noopener" style="display:flex; align-items:center; gap:8px; padding:8px 12px; background:rgba(0,0,0,0.05); border-radius:10px; color:inherit; text-decoration:none;">
                    <?php if ($cover): ?><img src="<?php echo esc_url($cover); ?>" alt="" style="width:40px; height:40px; object-fit:cover; border-radius:6px;" /><?php else: ?><i class="fas <?php echo esc_attr($icon); ?>" style="color:#4a89dc;"></i><?php endif; ?>
                    <div>
                        <span><?php echo esc_html($title); ?></span>
                        <?php if ($note): ?><span style="font-size:12px; color:#7f8c8d;"> &nbsp;<?php echo esc_html($note); ?></span><?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="quote-section">
            <p class="quote-text" id="quoteText">只要过的开心，就不算虚度光阴。</p>
            <p class="quote-author" id="quoteAuthor">—— <?php echo esc_html($personal_name); ?></p>
        </div>
        
        <div class="footer-section">
            <p class="copyright">Copyright © <?php echo date('Y'); ?> <?php echo esc_html($personal_name); ?>. All Rights Reserved.</p>
            <p class="copyright"><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></p>
            <?php if ($personal_icp): ?><a href="https://beian.miit.gov.cn/" class="icp-number" target="_blank" rel="noopener"><?php echo esc_html($personal_icp); ?></a><?php endif; ?>
        </div>
    </div>
    
    <script>
    window.MODOWN_PERSONAL = {
        hasMusic: <?php echo $music_url ? 'true' : 'false'; ?>,
        lyrics: <?php
            $lyr = get_post_meta(get_the_ID(), 'music_lyrics', true);
            if ($lyr) { echo $lyr; }
            else { echo json_encode(array(
                array('time' => 16120, 'text' => '像我这样优秀的人'),
                array('time' => 19860, 'text' => '本该灿烂过一生'),
                array('time' => 23640, 'text' => '怎么二十多年到头来'),
                array('time' => 27370, 'text' => '还在人海里浮沉'),
                array('time' => 31270, 'text' => '像我这样聪明的人'),
                array('time' => 35340, 'text' => '早就告别了单纯'),
                array('time' => 39290, 'text' => '怎么还是用了一段情'),
                array('time' => 43060, 'text' => '去换一身伤痕'),
                array('time' => 46990, 'text' => '像我这样迷茫的人'),
                array('time' => 50680, 'text' => '像我这样寻找的人'),
                array('time' => 54870, 'text' => '像我这样碌碌无为的人'),
                array('time' => 58890, 'text' => '你还见过多少人'),
                array('time' => 79780, 'text' => '像我这样庸俗的人'),
                array('time' => 83600, 'text' => '从不喜欢装深沉'),
                array('time' => 87750, 'text' => '怎么偶尔听到老歌时'),
                array('time' => 91520, 'text' => '忽然也晃了神'),
                array('time' => 95270, 'text' => '像我这样懦弱的人'),
                array('time' => 99070, 'text' => '凡事都要留几分'),
                array('time' => 103130, 'text' => '怎么曾经也会为了谁'),
                array('time' => 106790, 'text' => '想过奋不顾身'),
                array('time' => 110860, 'text' => '像我这样迷茫的人'),
                array('time' => 114700, 'text' => '像我这样寻找的人'),
                array('time' => 118730, 'text' => '像我这样碌碌无为的人'),
                array('time' => 122490, 'text' => '你还见过多少人'),
                array('time' => 127130, 'text' => '像我这样孤单的人'),
                array('time' => 130220, 'text' => '像我这样傻的人'),
                array('time' => 134080, 'text' => '像我这样不甘平凡的人'),
                array('time' => 137920, 'text' => '世界上有多少人'),
                array('time' => 146400, 'text' => '像我这样迷茫的人'),
                array('time' => 151510, 'text' => '像我这样寻找的人'),
                array('time' => 155320, 'text' => '像我这样碌碌无为的人'),
                array('time' => 159180, 'text' => '你还见过多少人'),
                array('time' => 163080, 'text' => '像我这样孤单的人'),
                array('time' => 166970, 'text' => '像我这样傻的人'),
                array('time' => 171270, 'text' => '像我这样不甘平凡的人'),
                array('time' => 174770, 'text' => '世界上有多少人'),
                array('time' => 181090, 'text' => '像我这样莫名其妙的人'),
                array('time' => 187640, 'text' => '会不会有人心疼')
            ), JSON_UNESCAPED_UNICODE); }
        ?>
    };
    </script>
    <script src="<?php echo esc_url(get_template_directory_uri()); ?>/static/js/page-personal.js"></script>
    <?php wp_footer(); ?>
</body>
</html>
