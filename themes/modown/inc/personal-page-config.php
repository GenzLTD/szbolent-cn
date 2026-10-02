<?php
/**
 * 个人主页配置功能
 * 添加自定义字段到页面编辑器
 */

// 添加自定义字段到页面编辑器
add_action('add_meta_boxes', 'add_personal_page_meta_boxes');
function add_personal_page_meta_boxes() {
    add_meta_box(
        'personal_page_config',
        '个人主页配置',
        'personal_page_config_callback',
        'page',
        'normal',
        'high'
    );
}

// 自定义字段回调函数
function personal_page_config_callback($post) {
    wp_nonce_field('personal_page_config_nonce', 'personal_page_config_nonce');
    
    // 获取现有值
    $personal_name = get_post_meta($post->ID, 'personal_name', true);
    $personal_avatar = get_post_meta($post->ID, 'personal_avatar', true);
    $personal_slogan = get_post_meta($post->ID, 'personal_slogan', true);
    $personal_location = get_post_meta($post->ID, 'personal_location', true);
    $personal_gender = get_post_meta($post->ID, 'personal_gender', true);
    $personal_age = get_post_meta($post->ID, 'personal_age', true);
    $personal_nav_items = get_post_meta($post->ID, 'personal_nav_items', true);
    $personal_skills = get_post_meta($post->ID, 'personal_skills', true);
    $music_title = get_post_meta($post->ID, 'music_title', true);
    $music_artist = get_post_meta($post->ID, 'music_artist', true);
    $music_url = get_post_meta($post->ID, 'music_url', true);
    $music_lyrics = get_post_meta($post->ID, 'music_lyrics', true);
    $dark_bg_image = get_post_meta($post->ID, 'dark_bg_image', true);
    $personal_description = get_post_meta($post->ID, 'personal_description', true);
    $personal_keywords = get_post_meta($post->ID, 'personal_keywords', true);
    $personal_icp = get_post_meta($post->ID, 'personal_icp', true);
    
    ?>
    <style>
        .personal-config-table {
            width: 100%;
        }
        .personal-config-table th {
            width: 150px;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        .personal-config-table td {
            padding: 10px;
        }
        .personal-config-table input[type="text"],
        .personal-config-table textarea {
            width: 100%;
            max-width: 500px;
        }
        .personal-config-table textarea {
            height: 80px;
        }
        .config-help {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
        .config-section {
            background: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #2271b1;
        }
        .config-section h3 {
            margin-top: 0;
            color: #2271b1;
        }
    </style>
    
    <div class="config-section">
        <h3>📋 基本信息（必需）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="personal_name">姓名 *</label></th>
                <td>
                    <input type="text" id="personal_name" name="personal_name" value="<?php echo esc_attr($personal_name); ?>" placeholder="例如：腾飞" />
                    <p class="config-help">显示在页面顶部的姓名</p>
                </td>
            </tr>
            <tr>
                <th><label for="personal_avatar">头像URL *</label></th>
                <td>
                    <input type="text" id="personal_avatar" name="personal_avatar" value="<?php echo esc_attr($personal_avatar); ?>" placeholder="https://example.com/avatar.jpg" />
                    <p class="config-help">头像图片的完整URL地址</p>
                </td>
            </tr>
            <tr>
                <th><label for="personal_slogan">标语 *</label></th>
                <td>
                    <input type="text" id="personal_slogan" name="personal_slogan" value="<?php echo esc_attr($personal_slogan); ?>" placeholder="例如：欲买桂花同载酒 终不似 少年游" />
                    <p class="config-help">显示在姓名下方的个人标语</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>ℹ️ 个人信息（可选）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="personal_location">地理位置</label></th>
                <td>
                    <input type="text" id="personal_location" name="personal_location" value="<?php echo esc_attr($personal_location); ?>" placeholder="例如：广东" />
                </td>
            </tr>
            <tr>
                <th><label for="personal_gender">性别</label></th>
                <td>
                    <input type="text" id="personal_gender" name="personal_gender" value="<?php echo esc_attr($personal_gender); ?>" placeholder="例如：男" />
                </td>
            </tr>
            <tr>
                <th><label for="personal_age">年龄</label></th>
                <td>
                    <input type="text" id="personal_age" name="personal_age" value="<?php echo esc_attr($personal_age); ?>" placeholder="例如：22岁" />
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>🔗 站点导航（可选）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="personal_nav_items">导航链接</label></th>
                <td>
                    <textarea id="personal_nav_items" name="personal_nav_items" placeholder='[{"title": "首页", "url": "http://genz.ltd"}, {"type": "vip", "title": "升级VIP", "icon": "fa-crown", "highlight": true}, {"type": "charge", "title": "充值积分"}, {"type": "user"}, {"type": "aff", "title": "我的推广"}, {"type": "custom", "title": "博客", "url": "/blog"}]'><?php echo esc_textarea($personal_nav_items); ?></textarea>
                    <p class="config-help">JSON 格式。支持 type：vip、charge、user、aff、custom。vip/charge/user/aff 可不填 url，由系统生成；custom 或不填 type 则必填 url。可选 icon（如 fa-crown、fa-rss）、highlight（true 强调）。</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>📱 Lyanna 组件（2.1）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="personal_sns">社交链接</label></th>
                <td>
                    <textarea id="personal_sns" name="personal_sns" rows="4" placeholder='{"github":"yourname","wechat":"wechat.png","email":"you@example.com","zhihu":"people/xxx"}'><?php echo esc_textarea(get_post_meta($post->ID, 'personal_sns', true)); ?></textarea>
                    <p class="config-help">JSON。github/twitter/zhihu/douban/linkedin 等为 id，生成跳转链接；wechat、weixingongzhonghao 为图片路径（放 static/upload/ 或填完整 URL），点击看图。保存时若 static/upload/ 不存在会自动创建。</p>
                </td>
            </tr>
            <tr>
                <th><label for="personal_blogroll">友情链接</label></th>
                <td>
                    <textarea id="personal_blogroll" name="personal_blogroll" rows="3" placeholder='[{"title":"友站A","url":"https://a.com"},{"title":"友站B","url":"https://b.com"}]'><?php echo esc_textarea(get_post_meta($post->ID, 'personal_blogroll', true)); ?></textarea>
                    <p class="config-help">JSON：<code>[{"title":"","url":""}]</code></p>
                </td>
            </tr>
            <tr>
                <th><label for="personal_html_blocks">自定义 HTML</label></th>
                <td>
                    <textarea id="personal_html_blocks" name="personal_html_blocks" rows="4" placeholder='[{"title":"区块标题","body":"<p>内容 HTML</p>"}]'><?php echo esc_textarea(get_post_meta($post->ID, 'personal_html_blocks', true)); ?></textarea>
                    <p class="config-help">JSON：<code>[{"title":"","body":"<p>...</p>"}]</code>，body 支持常用 HTML，保存时过滤。</p>
                </td>
            </tr>
            <tr>
                <th>订阅本站</th>
                <td>
                    <label><input type="checkbox" name="personal_show_feed" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_feed', true), '1'); ?> /> 显示「订阅本站」区块（RSS + Feedly / Inoreader）</label>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>📊 Lyanna 组件（2.2 / 2.3）</h3>
        <table class="personal-config-table">
            <tr>
                <th>最新评论</th>
                <td>
                    <label><input type="checkbox" name="personal_show_latest_comments" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_latest_comments', true), '1'); ?> /> 显示「最新评论」</label>
                    <input type="number" id="personal_latest_comments_count" name="personal_latest_comments_count" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_latest_comments_count', true) ?: '5'); ?>" min="1" max="50" style="width:80px;" /> 条
                </td>
            </tr>
            <tr>
                <th>最热文章</th>
                <td>
                    <label><input type="checkbox" name="personal_show_most_viewed" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_most_viewed', true), '1'); ?> /> 显示「最热文章」</label>
                    <input type="number" id="personal_most_viewed_count" name="personal_most_viewed_count" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_most_viewed_count', true) ?: '5'); ?>" min="1" max="50" style="width:80px;" /> 篇
                </td>
            </tr>
            <tr>
                <th>标签云</th>
                <td>
                    <label><input type="checkbox" name="personal_show_tagcloud" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_tagcloud', true), '1'); ?> /> 显示「标签云」</label>
                    <input type="number" id="personal_tagcloud_count" name="personal_tagcloud_count" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_tagcloud_count', true) ?: '20'); ?>" min="1" max="100" style="width:80px;" /> 个
                </td>
            </tr>
            <tr>
                <th><label for="personal_favorites">我的收藏</label></th>
                <td>
                    <textarea id="personal_favorites" name="personal_favorites" rows="4" placeholder='[{"type":"movie","title":"片名","url":"https://...","cover":"","note":""},{"type":"book","title":"书名","url":"https://..."}]'><?php echo esc_textarea(get_post_meta($post->ID, 'personal_favorites', true)); ?></textarea>
                    <p class="config-help">JSON：<code>[{"type":"movie|book|game","title":"","url":"","cover":"","note":""}]</code>，手动维护。</p>
                </td>
            </tr>
            <tr>
                <th><label for="personal_promo">推广 CTA</label></th>
                <td>
                    <textarea id="personal_promo" name="personal_promo" rows="3" placeholder='{"title":"推广","buttons":[{"text":"订阅 RSS","url":"/feed","icon":"fa-rss"}]}'><?php echo esc_textarea(get_post_meta($post->ID, 'personal_promo', true)); ?></textarea>
                    <p class="config-help">JSON，放在「我的站点」与「技能」之间。结构：<code>{"title":"","buttons":[{"text":"","url":"","icon":""}]}</code>，留空不显示。</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>💰 支付/订阅/积分（modown+erphpdown 可选）</h3>
        <table class="personal-config-table">
            <tr>
                <th>升级VIP 区块</th>
                <td>
                    <label><input type="checkbox" name="personal_show_vip" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_vip', true), '1'); ?> /> 显示「升级VIP」区块</label>
                    <p class="config-help">依赖 erphpdown 且主题未隐藏 VIP</p>
                </td>
            </tr>
            <tr>
                <th><label for="personal_vip_block_title">升级VIP 标题</label></th>
                <td>
                    <input type="text" id="personal_vip_block_title" name="personal_vip_block_title" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_vip_block_title', true)); ?>" placeholder="留空则用 erphp_vip_name 或 升级VIP" />
                </td>
            </tr>
            <tr>
                <th><label for="personal_vip_block_desc">升级VIP 描述</label></th>
                <td>
                    <input type="text" id="personal_vip_block_desc" name="personal_vip_block_desc" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_vip_block_desc', true)); ?>" placeholder="可选" />
                </td>
            </tr>
            <tr>
                <th>充值/积分 区块</th>
                <td>
                    <label><input type="checkbox" name="personal_show_charge" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_charge', true), '1'); ?> /> 显示「充值/积分」区块</label>
                </td>
            </tr>
            <tr>
                <th><label for="personal_charge_block_title">充值 标题</label></th>
                <td>
                    <input type="text" id="personal_charge_block_title" name="personal_charge_block_title" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_charge_block_title', true)); ?>" placeholder="留空则 在线充值" />
                </td>
            </tr>
            <tr>
                <th>我的推广 区块</th>
                <td>
                    <label><input type="checkbox" name="personal_show_aff" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_aff', true), '1'); ?> /> 显示「我的推广」区块</label>
                    <p class="config-help">依赖 erphpdown 推广已开且主题未隐藏</p>
                </td>
            </tr>
            <tr>
                <th><label for="personal_aff_block_title">推广 标题</label></th>
                <td>
                    <input type="text" id="personal_aff_block_title" name="personal_aff_block_title" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_aff_block_title', true)); ?>" placeholder="留空则 我的推广" />
                </td>
            </tr>
            <tr>
                <th><label for="personal_aff_block_desc">推广 描述</label></th>
                <td>
                    <input type="text" id="personal_aff_block_desc" name="personal_aff_block_desc" value="<?php echo esc_attr(get_post_meta($post->ID, 'personal_aff_block_desc', true)); ?>" placeholder="可选" />
                </td>
            </tr>
            <tr>
                <th>用户中心 区块</th>
                <td>
                    <label><input type="checkbox" name="personal_show_user_center" value="1" <?php checked(get_post_meta($post->ID, 'personal_show_user_center', true), '1'); ?> /> 显示「用户中心」入口</label>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>💪 技能展示（可选）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="personal_skills">技能数据</label></th>
                <td>
                    <textarea id="personal_skills" name="personal_skills" placeholder='[{"name": "HTML", "icon": "fab fa-html5", "percentage": 85, "color": "linear-gradient(90deg, #e34f26, #f06529)"}]'><?php echo esc_textarea($personal_skills); ?></textarea>
                    <p class="config-help">JSON格式，包含技能名称、图标、百分比和颜色</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>🎵 音乐播放器（可选）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="music_title">音乐标题</label></th>
                <td>
                    <input type="text" id="music_title" name="music_title" value="<?php echo esc_attr($music_title); ?>" placeholder="例如：像我这样的人" />
                </td>
            </tr>
            <tr>
                <th><label for="music_artist">艺术家</label></th>
                <td>
                    <input type="text" id="music_artist" name="music_artist" value="<?php echo esc_attr($music_artist); ?>" placeholder="例如：毛不易" />
                </td>
            </tr>
            <tr>
                <th><label for="music_url">音乐文件URL</label></th>
                <td>
                    <input type="text" id="music_url" name="music_url" value="<?php echo esc_attr($music_url); ?>" placeholder="留空则使用主题默认" />
                    <p class="config-help">留空则使用主题默认：static/audio/xwzydr.mp3（需先将 xwzydr.mp3 放到主题 static/audio/）</p>
                </td>
            </tr>
            <tr>
                <th><label for="music_lyrics">歌词数据（JSON）</label></th>
                <td>
                    <textarea id="music_lyrics" name="music_lyrics" rows="8" placeholder='[{"time": 16120, "text": "像我这样优秀的人"}, {"time": 19860, "text": "本该灿烂过一生"}]'><?php echo esc_textarea($music_lyrics); ?></textarea>
                    <p class="config-help">JSON格式，包含时间（毫秒）和歌词文本。如果不填写，将使用默认歌词（像我这样的人）</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>🌙 主题设置（可选）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="dark_bg_image">黑夜模式背景图</label></th>
                <td>
                    <input type="text" id="dark_bg_image" name="dark_bg_image" value="<?php echo esc_attr($dark_bg_image); ?>" placeholder="留空则使用 static/img/xk.jpg" />
                    <p class="config-help">留空则使用主题默认：static/img/xk.jpg（需先将 xk.jpg 放到主题 static/img/）</p>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="config-section">
        <h3>📄 页面与 SEO（可选）</h3>
        <table class="personal-config-table">
            <tr>
                <th><label for="personal_description">页面描述</label></th>
                <td>
                    <input type="text" id="personal_description" name="personal_description" value="<?php echo esc_attr($personal_description); ?>" placeholder="用于 meta description、SEO" />
                </td>
            </tr>
            <tr>
                <th><label for="personal_keywords">页面关键词</label></th>
                <td>
                    <input type="text" id="personal_keywords" name="personal_keywords" value="<?php echo esc_attr($personal_keywords); ?>" placeholder="例如：个人主页,姓名,导航页" />
                </td>
            </tr>
            <tr>
                <th><label for="personal_icp">ICP 备案号</label></th>
                <td>
                    <input type="text" id="personal_icp" name="personal_icp" value="<?php echo esc_attr($personal_icp); ?>" placeholder="例如：粤ICP备2023129262号" />
                    <p class="config-help">填写后将在页脚显示备案号链接（跳转至 beian.miit.gov.cn）</p>
                </td>
            </tr>
        </table>
    </div>
    
    <p><strong>提示：</strong>只有标记为"必需"的字段必须填写，其他字段为可选。如果不填写可选字段，将使用默认值。</p>
    <?php
}

// 保存自定义字段
add_action('save_post', 'save_personal_page_config');
function save_personal_page_config($post_id) {
    if (get_post_type($post_id) !== 'page') {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (!isset($_POST['personal_page_config_nonce']) || !wp_verify_nonce($_POST['personal_page_config_nonce'], 'personal_page_config_nonce')) {
        return;
    }

    // 若 static/upload 不存在则创建，供社交链接 wechat、weixingongzhonghao 等相对路径图片使用
    $upload_dir = get_template_directory() . '/static/upload';
    if (!is_dir($upload_dir)) {
        wp_mkdir_p($upload_dir);
    }

    // checkbox：未勾选时 $_POST 不包含，需显式存 '0'
    $checkboxes = array(
        'personal_show_vip', 'personal_show_charge', 'personal_show_aff', 'personal_show_user_center', 'personal_show_feed',
        'personal_show_latest_comments', 'personal_show_most_viewed', 'personal_show_tagcloud',
    );
    foreach ($checkboxes as $field) {
        update_post_meta($post_id, $field, isset($_POST[$field]) ? '1' : '0');
    }

    // 2.2 数量：number 输入
    $count_fields = array(
        'personal_latest_comments_count' => 5,
        'personal_most_viewed_count'    => 5,
        'personal_tagcloud_count'       => 20,
    );
    foreach ($count_fields as $field => $default) {
        $v = isset($_POST[$field]) ? absint($_POST[$field]) : 0;
        update_post_meta($post_id, $field, $v > 0 ? $v : $default);
    }

    // 2.3 JSON textarea：personal_favorites、personal_promo（用 sanitize_textarea_field 保留换行）
    foreach (array('personal_favorites', 'personal_promo') as $field) {
        if (isset($_POST[$field])) {
            $raw = sanitize_textarea_field(wp_unslash($_POST[$field]));
            update_post_meta($post_id, $field, $raw);
        } else {
            delete_post_meta($post_id, $field);
        }
    }

    // personal_html_blocks：JSON 内 body 用 wp_kses_post 过滤
    if (isset($_POST['personal_html_blocks'])) {
        $raw = wp_unslash($_POST['personal_html_blocks']);
        $arr = json_decode($raw, true);
        if (is_array($arr)) {
            $out = array();
            foreach ($arr as $b) {
                $out[] = array(
                    'title' => isset($b['title']) ? sanitize_text_field($b['title']) : '',
                    'body'  => isset($b['body']) ? wp_kses_post($b['body']) : '',
                );
            }
            update_post_meta($post_id, 'personal_html_blocks', wp_json_encode($out, JSON_UNESCAPED_UNICODE));
        } else {
            update_post_meta($post_id, 'personal_html_blocks', '');
        }
    } else {
        delete_post_meta($post_id, 'personal_html_blocks');
    }

    // JSON textarea：personal_nav_items、personal_sns、personal_blogroll、personal_skills、music_lyrics，用 sanitize_textarea_field 保留换行
    $json_textarea_fields = array('personal_nav_items', 'personal_sns', 'personal_blogroll', 'personal_skills', 'music_lyrics');
    foreach ($json_textarea_fields as $field) {
        if (isset($_POST[$field])) {
            $raw = sanitize_textarea_field(wp_unslash($_POST[$field]));
            update_post_meta($post_id, $field, $raw);
        } else {
            delete_post_meta($post_id, $field);
        }
    }

    // 其余字段：sanitize_text_field
    $fields = array(
        'personal_name',
        'personal_avatar',
        'personal_slogan',
        'personal_location',
        'personal_gender',
        'personal_age',
        'music_title',
        'music_artist',
        'music_url',
        'dark_bg_image',
        'personal_description',
        'personal_keywords',
        'personal_icp',
        'personal_vip_block_title',
        'personal_vip_block_desc',
        'personal_charge_block_title',
        'personal_aff_block_title',
        'personal_aff_block_desc',
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        } else {
            delete_post_meta($post_id, $field);
        }
    }
}
