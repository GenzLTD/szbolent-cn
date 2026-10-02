<?php
/* 
 * post meta form
 * ====================================================
*/
$postmeta_form = array(
    "单栏" => array(
        "name" => "nosidebar",
        "std" => "",
        "desc" => "（隐藏右侧栏）",
        "type" => "checkbox",
        "title" => "单栏"),
    "标题颜色" => array(
        "name" => "title_color",
        "std" => "",
        "desc" => "",
        "type" => "color",
        "title" => "标题颜色"),
	"浏览数" => array(
        "name" => "views",
        "std" => _MBT('post_new_rand_views')?($pagenow === 'post-new.php' ? rand(100,999) : 0):0,
        "desc" => "",
        "type" => "number",
        "title" => "浏览数"),
	"下载数" => array(
        "name" => "down_times",
        "std" => "0",
        "desc" => "",
        "type" => "number",
        "title" => "下载数"),
    "标记颜色" => array(
        "name" => "sign_color",
        "std" => "#ff9600",
        "desc" => "",
        "type" => "color",
        "title" => "标记颜色"),
    "小标记" => array(
        "name" => "sign",
        "std" => "",
        "type" => "minitext",
        "title" => "小标记",
        "desc" => "显示在列表的标题前，字数2-4个最佳，例如：独家、正版、原创、已测试"),
    "小提示" => array(
        "name" => "tips",
        "std" => "",
        "type" => "text",
        "title" => "小提示",
        "desc" => "显示在详情页的正文前面，例如：此主题为模板兔原创，官方正版授权！"),
    "演示标题" => array(
        "name" => "demo_title",
        "desc" => "留空而默认为在线演示",
        "type" => "text",
        "title" => "演示标题"),
    "演示地址" => array(
        "name" => "demo",
        "desc" => "显示在边栏购买下载按钮下面",
        "type" => "text",
        "title" => "演示地址"),
    "视频收费" => array(
        "name" => "video_erphpdown",
        "std" => "",
        "desc" => "（基于Erphpdown属性里设置的收费信息）",
        "type" => "checkbox",
        "title" => "视频收费"),
    "嵌入视频" => array(
        "name" => "video_type",
        "std" => "",
        "desc" => "（指爱奇艺、腾讯、优酷等视频网站的分享地址，视频地址仅需填写分享代码里src的值，不是填整串代码）",
        "type" => "checkbox",
        "title" => "嵌入视频"),
    "预览视频地址" => array(
        "name" => "video_preview",
        "desc" => "免费观看的MP4等格式的视频文件地址",
        "type" => "file",
        "title" => "预览视频地址"),
    "单视频地址" => array(
        "name" => "video",
        "desc" => "MP4等格式的视频文件地址，可做付费后看",
        "type" => "file",
        "title" => "单视频地址"),
    "剧集样式" => array(
        "name" => "videos_tv",
        "std" => "",
        "desc" => "（多视频是剧集模式，可不用填视频名称与时长，默认按顺序显示第1集 第2集 第3集等）",
        "type" => "checkbox",
        "title" => "剧集样式"),
    "多视频集" => array(
        "name" => "videos",
        "desc" => "如果视频集要收费，得先启用上面的【视频收费】，然后视频集单个可以设置免费，比如可以设置前两三个视频免费，后面的收费",
        "type" => "videos",
        "title" => "多视频集"),
    "音频收费" => array(
        "name" => "audio_erphpdown",
        "std" => "",
        "desc" => "（基于Erphpdown属性里设置的收费信息）",
        "type" => "checkbox",
        "title" => "音频收费"),
    "试听音频地址" => array(
        "name" => "audio",
        "desc" => "MP3等格式的音频文件试听地址",
        "type" => "audio",
        "title" => "试听音频地址"),
    "音频地址" => array(
        "name" => "audio_url",
        "desc" => "MP3等格式的音频文件地址，可做付费后听",
        "type" => "audio_url",
        "title" => "音频地址"),
    "图片集" => array(
        "name" => "images",
        "desc" => "（以幻灯片方式显示在文章页顶部）",
        "type" => "slider",
        "title" => "图片集"),
    
    "推荐" => array(
        "name" => "down_recommend",
        "std" => "",
        "desc" => "",
        "type" => "checkbox",
        "title" => "推荐"),
    "推荐特色图" => array(
        "name" => "image_recommend",
        "desc" => "首页幻灯片右侧显示推荐文章时的图片",
        "type" => "file",
        "title" => "推荐特色图"),
    "幻灯片" => array(
        "name" => "down_slider",
        "std" => "",
        "desc" => "（若首页幻灯片是通过文章来显示，那么会显示勾选了此项的文章）",
        "type" => "checkbox",
        "title" => "幻灯片"),
    "幻灯片特色图" => array(
        "name" => "image_slider",
        "desc" => "首页幻灯片显示文章时的图片",
        "type" => "file",
        "title" => "幻灯片特色图"),
    "特殊" => array(
        "name" => "down_special",
        "std" => "",
        "desc" => "（特色图片在列表页将以背景图的方式显示）",
        "type" => "checkbox",
        "title" => "特殊"),
    "回复" => array(
        "name" => "down_reply",
        "std" => "",
        "desc" => "（需要评论后才会显示购买下载框，注意不是评论后显示下载地址）",
        "type" => "checkbox",
        "title" => "回复"),
    "备注" => array(
        "name" => "remarks",
        "desc" => "仅用于后台备注，前台不会显示",
        "type" => "textarea",
        "title" => "备注")
);

if(!_MBT('seo')){
    $postmeta_form[] = array(
        "name" => "seo_title",
        "std" => "",
        "desc" => "",
        "type" => "text",
        "title" => "SEO标题");
    $postmeta_form[] = array(
        "name" => "seo_keyword",
        "std" => "",
        "desc" => "",
        "type" => "text",
        "title" => "SEO关键字");
    $postmeta_form[] = array(
        "name" => "seo_description",
        "std" => "",
        "desc" => "",
        "type" => "textarea",
        "title" => "SEO描述");
}

if(function_exists('modown_custom_postmeta')){
    $postmeta_form = array_merge($postmeta_form,modown_custom_postmeta());
}

if(!_MBT('seo')){
    $pagemeta_form = array(
        array(
            "name" => "vip_see",
            "std" => "0",
            "desc" => "仅对部分单页面有效，与erphpdown的设置没有必然联系",
            "type" => "select",
            "options" => array(
                '0'=>'不启用',
                '6'=>'VIP',
                '7'=>'包月VIP',
                '8'=>'季度VIP',
                '9'=>'包年VIP',
                '10'=>'终身VIP',
            ),
            "title" => "VIP可见"),
        array(
            "name" => "seo_title",
            "std" => "",
            "desc" => "",
            "type" => "text",
            "title" => "SEO标题"),
        array(
            "name" => "seo_keyword",
            "std" => "",
            "desc" => "",
            "type" => "text",
            "title" => "SEO关键字"),
        array(
            "name" => "seo_description",
            "std" => "",
            "desc" => "",
            "type" => "textarea",
            "title" => "SEO描述")
    );
}else{
    $pagemeta_form = array(
        array(
            "name" => "vip_see",
            "std" => "0",
            "desc" => "VIP才可以看见页面内容，与erphpdown的设置没有必然联系",
            "type" => "select",
            "options" => array(
                '0'=>'不启用',
                '6'=>'VIP',
                '7'=>'包月VIP',
                '8'=>'季度VIP',
                '9'=>'包年VIP',
                '10'=>'终身VIP',
            ),
            "title" => "VIP可见")
    );
}

function mobantu_postmeta_form() {
    global $post, $postmeta_form, $pagenow;
    echo '<style>.modown-videos, .modown-images{position: relative;}
    .modown-videos .modown-video-item,.modown-images .modown-image-item{margin-bottom: 5px;border: 1px dashed #ccc;padding: 5px;border-radius: 5px;position: relative;cursor: move;}</style>';
    foreach($postmeta_form as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if($pagenow === 'post-new.php' && isset($meta_box['std'])){
            $meta_box_value = $meta_box['std'];
        }
        echo'<div style="padding-left:100px;margin: 1em 0;position:relative;"><label style="font-weight:bold;width:100px;position:absolute;left:0;top:0;">'.$meta_box['title'].'</label>';
        if($meta_box['type'] == 'checkbox'){
            echo '<input type="checkbox" value="1" name="'.$meta_box['name'].'" ';
            if ( htmlentities( $meta_box_value, 1 ) == '1' ) echo ' checked="checked"';
            echo '><span style="color:#666;font-size:12px">'.$meta_box['desc'].'</span></div>';
        }elseif($meta_box['type'] == 'textarea'){
            echo '<textarea name="'.$meta_box['name'].'" style="width: 100%" row="3">'.$meta_box_value.'</textarea><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'radio'){
            $i=1;
            foreach ($meta_box['options'] as $key => $option) {
                echo '<span><input type="radio" name="'.$meta_box['name'].'" id="'.$meta_box['name'].$i.'" value="'. $key . '" '. checked( $meta_box_value, $key, false) .' /><label for="'.$meta_box['name'].$i.'">' . esc_html( $option ) . '</label>&nbsp;&nbsp;</span>';
                $i ++;
            }
            echo '<div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'number'){
            echo '<input type="number" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width:100px"></div>';
        }elseif($meta_box['type'] == 'slider'){
            echo '<div class="modown-images">';
            if($meta_box_value){
                $cnt = count($meta_box_value['src']);
                if($cnt){
                    for($i=0; $i<$cnt;$i++){
                        echo '<div class="modown-image-item">';
                        echo '<input type="text" name="images[alt][]" value="'.$meta_box_value['alt'][$i].'" placeholder="图片标题" style="width:30%"><input type="text" name="images[src][]" value="'.$meta_box_value['src'][$i].'" placeholder="图片地址" style="width:40%"><a href="javascript:;" class="modown-add-image button">上传图片</a> <a href="javascript:;" class="modown-del-image">删除</a>';
                        echo '</div>';
                    }
                }
            }
            echo '</div><a class="button-primary modown-add-image-item">+ 添加图集</a>';
?>
            <script>
                jQuery(function($) {
                    $( ".modown-images" ).sortable({ disabled: false });
                    $(".modown-add-image-item").click(function(){
                        $(".modown-images").append('<div class="modown-image-item"><input type="text" name="images[alt][]" placeholder="图片标题" style="width:30%"><input type="text" name="images[src][]" placeholder="图片地址" style="width:40%"><a href="javascript:;" class="modown-add-image button">上传图片</a> <a href="javascript:;" class="modown-del-image">删除</a></div>');
                        return false;
                    });

                    $(document).on("click",".modown-del-image",function(){
                        $(this).parent().remove();
                    });
                    
                    $(document).on('click', '.modown-add-image', function(e) {
                        e.preventDefault();
                        var button = $(this);
                        var id = button.prev();
                        var original_send = wp.media.editor.send.attachment;
                        wp.media.editor.send.attachment = function(props, attachment) {
                            id.val(attachment.url); 
                            wp.media.editor.send.attachment = original_send; 
                        };
                        wp.media.editor.open(button);
                        return false;
                    });
                    
                });
            </script>    
<?php
            echo '<div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'videos'){
            echo '<div class="modown-videos">';
            if($meta_box_value){
                $cnt = count($meta_box_value['src']);
                if($cnt){
                    for($i=0; $i<$cnt;$i++){
                        echo '<div class="modown-video-item">';
                        echo '<input type="text" name="videos[alt][]" value="'.$meta_box_value['alt'][$i].'" placeholder="视频标题" style="width:30%"><input type="text" name="videos[src][]" value="'.$meta_box_value['src'][$i].'" placeholder="视频地址 *" style="width:40%"><a href="javascript:;" class="modown-add-video button">上传视频</a><input type="text" name="videos[time][]" value="'.$meta_box_value['time'][$i].'" placeholder="时长，例如：15:30" style="width:10%"><select name="videos[free][]" style="display:inline-block;"><option value="0">默认</option><option value="1" '.((isset($value['free'][$i]) && $value['free'][$i] == '1')?'selected':'').'>免费</option></select> <a href="javascript:;" class="modown-del-video">删除</a>';
                        echo '</div>';
                    }
                }
            }
            echo '</div><a class="button-primary modown-add-video-item">+ 添加视频</a>';
?>
            <script>
                jQuery(function($) {
                    $( ".modown-videos" ).sortable({ disabled: false });
                    $(".modown-add-video-item").click(function(){
                        $(".modown-videos").append('<div class="modown-video-item"><input type="text" name="videos[alt][]" placeholder="视频标题" style="width:30%"><input type="text" name="videos[src][]" placeholder="视频地址 *" style="width:40%"><a href="javascript:;" class="modown-add-video button">上传视频</a><input type="text" name="videos[time][]" placeholder="时长，例如：15:30" style="width:10%"><select name="videos[free][]" style="display:inline-block;"><option value="0">默认</option><option value="1">免费</option></select> <a href="javascript:;" class="modown-del-video">删除</a></div>');
                        return false;
                    });

                    $(document).on("click",".modown-del-video",function(){
                        $(this).parent().remove();
                    });
                    
                    $(document).on('click', '.modown-add-video', function(e) {
                        e.preventDefault();
                        var button = $(this);
                        var id = button.prev();
                        var original_send = wp.media.editor.send.attachment;
                        wp.media.editor.send.attachment = function(props, attachment) {
                            id.val(attachment.url); 
                            wp.media.editor.send.attachment = original_send; 
                        };
                        wp.media.editor.open(button);
                        return false;
                    });
                    
                });
            </script>    
<?php
            echo '<div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'minitext'){
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%;max-width:150px;"><br><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'color'){
            echo '<input type="color" value="'.$meta_box_value.'" name="'.$meta_box['name'].'"></div>';
        }elseif($meta_box['type'] == 'file'){
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: calc(100% - 80px)"><a href="javascript:;" class="modown-add-file button">上传文件</a><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
?>
        <script>
            jQuery(function($) {
                $(document).on('click', '.modown-add-file', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    var original_send = wp.media.editor.send.attachment;
                    wp.media.editor.send.attachment = function(props, attachment) {
                        id.val(attachment.url); 
                        wp.media.editor.send.attachment = original_send; 
                    };
                    wp.media.editor.open(button);
                    return false;
                });
            });
        </script>
<?php
        }elseif($meta_box['type'] == 'audio'){
            $audio_time = get_post_meta($post->ID,'audio_time',true);
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: calc(100% - 180px)"><a href="javascript:;" class="modown-add-file button">上传文件</a><input type="number" name="audio_time" value="'.$audio_time.'" style="width:100px" placeholder="时长：秒"><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
?>
        <script>
            jQuery(function($) {
                $(document).on('click', '.modown-add-file', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    var original_send = wp.media.editor.send.attachment;
                    wp.media.editor.send.attachment = function(props, attachment) {
                        id.val(attachment.url); 
                        wp.media.editor.send.attachment = original_send; 
                    };
                    wp.media.editor.open(button);
                    return false;
                });
            });
        </script>
<?php
        }elseif($meta_box['type'] == 'audio_url'){
            $audio_time = get_post_meta($post->ID,'audio_url_time',true);
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: calc(100% - 180px)"><a href="javascript:;" class="modown-add-file button">上传文件</a><input type="number" name="audio_url_time" value="'.$audio_time.'" style="width:100px" placeholder="时长：秒"><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
?>
        <script>
            jQuery(function($) {
                $(document).on('click', '.modown-add-file', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    var original_send = wp.media.editor.send.attachment;
                    wp.media.editor.send.attachment = function(props, attachment) {
                        id.val(attachment.url); 
                        wp.media.editor.send.attachment = original_send; 
                    };
                    wp.media.editor.open(button);
                    return false;
                });
            });
        </script>
<?php
        }else{
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%"><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }
    }
   
    echo '<input type="hidden" name="modown_metabox_nonce" id="modown_metabox_nonce" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
}

function mobantu_pagemeta_form() {
    global $post, $pagemeta_form;
    foreach($pagemeta_form as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if($meta_box_value == "" && isset($meta_box['std']))
            $meta_box_value = $meta_box['std'];
        echo'<div style="padding-left:100px;margin: 1em 0;position:relative;"><label style="font-weight:bold;width:100px;position:absolute;left:0;top:0;">'.$meta_box['title'].'</label>';
        if($meta_box['type'] == 'checkbox'){
            echo '<input type="checkbox" value="1" name="'.$meta_box['name'].'" ';
            if ( htmlentities( $meta_box_value, 1 ) == '1' ) echo ' checked="checked"';
            echo '><span style="color:#666;font-size:12px">'.$meta_box['desc'].'</span></div>';
        }elseif($meta_box['type'] == 'radio'){
            $i=1;
            foreach ($meta_box['options'] as $key => $option) {
                echo '<span><input type="radio" name="'.$meta_box['name'].'" id="'.$meta_box['name'].$i.'" value="'. $key . '" '. checked( $meta_box_value, $key, false) .' /><label for="'.$meta_box['name'].$i.'">' . esc_html( $option ) . '</label>&nbsp;&nbsp;</span>';
                $i ++;
            }
            echo '<div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'select'){
            echo '<select name="'.$meta_box['name'].'">';
            foreach ($meta_box['options'] as $key => $option) {
                echo '<option value="'. $key . '" '. ( $meta_box_value == $key?' selected':'') .' />' . esc_html( $option ) . '</option>';
            }
            echo '</select>';
            echo '<br><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'textarea'){
            echo '<textarea name="'.$meta_box['name'].'" style="width: 100%" row="3">'.$meta_box_value.'</textarea><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'number'){
            echo '<input type="number" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width:100px"></div>';
        }elseif($meta_box['type'] == 'minitext'){
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%;max-width:150px;"><br><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }else{
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%"><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }
    }
   
    echo '<input type="hidden" name="modown_metabox_nonce" id="modown_metabox_nonce" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
}

function mobantu_create_meta_box() {
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'modown-metaboxes', 'Modown属性', 'mobantu_postmeta_form', 'post', 'normal', 'high' );
        add_meta_box( 'modown-metaboxes', 'Modown属性', 'mobantu_pagemeta_form', 'page', 'normal', 'high' );
    }
}

function mobantu_save_postdata( $post_id ) {
    global $postmeta_form, $pagemeta_form;

    if(!isset($_POST['modown_metabox_nonce']))
        return;
   
    if ( !current_user_can( 'edit_posts', $post_id ))
        return;

    if(get_post_type($post_id) == 'page'){
        $postmeta_form = $pagemeta_form;
    }
                   
    foreach($postmeta_form as $meta_box) {
        if(isset($_POST[$meta_box['name']])){
            update_post_meta($post_id, $meta_box['name'], $_POST[$meta_box['name']]);
            if($_POST[$meta_box['name']] == ''){
                delete_post_meta($post_id, $meta_box['name']);
            }
        }else{
            delete_post_meta($post_id, $meta_box['name']);
        }
    }
    if(isset($_POST['audio_time'])){
        update_post_meta($post_id, 'audio_time', $_POST['audio_time']);
        if($_POST['audio_time'] == ''){
            delete_post_meta($post_id, 'audio_time');
        }
    }else{
        delete_post_meta($post_id, 'audio_time');
    }
    if(isset($_POST['audio_url_time'])){
        update_post_meta($post_id, 'audio_url_time', $_POST['audio_url_time']);
        if($_POST['audio_url_time'] == ''){
            delete_post_meta($post_id, 'audio_url_time');
        }
    }else{
        delete_post_meta($post_id, 'audio_url_time');
    }
}

add_action('admin_menu', 'mobantu_create_meta_box');
add_action('save_post', 'mobantu_save_postdata');




add_action( 'admin_menu', 'MBThemes_keywords_page_metabox' );
function MBThemes_keywords_page_metabox() {
    global $pagenow;
    if($pagenow == 'post.php'){
        if(isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit'){
            if(get_page_template_slug($_GET['post']) == 'template/keywords.php'){
                add_meta_box( 'keywords-postmeta-box','文章聚合属性', 'MBThemes_keywords_show_metabox', 'page', 'normal', 'high' );
            }
        }
    }
}

$keywordsmeta_form = array(
    "搜索关键词" => array(
        "name" => "keyword_search",
        "desc" => "",
        "type" => "text",
        "title" => "搜索关键词"),
    "分类ID" => array(
        "name" => "keyword_cat",
        "desc" => "",
        "type" => "text",
        "title" => "分类ID"),
    "标签ID" => array(
        "name" => "keyword_tag",
        "desc" => "",
        "type" => "text",
        "title" => "标签ID"),
);

function MBThemes_keywords_show_metabox() {
    global $post, $keywordsmeta_form;
    foreach($keywordsmeta_form as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if($meta_box_value == "" && isset($meta_box['std']))
            $meta_box_value = $meta_box['std'];
        echo'<div style="padding-left:100px;margin: 1em 0;position:relative;"><label style="font-weight:bold;width:100px;position:absolute;left:0;top:0;">'.$meta_box['title'].'</label>';
        if($meta_box['type'] == 'checkbox'){
            echo '<input type="checkbox" value="1" name="'.$meta_box['name'].'" ';
            if ( htmlentities( $meta_box_value, 1 ) == '1' ) echo ' checked="checked"';
            echo '><span style="color:#666;font-size:12px">'.$meta_box['desc'].'</span></div>';
        }elseif($meta_box['type'] == 'textarea'){
            echo '<textarea name="'.$meta_box['name'].'" style="width: 100%" row="3">'.$meta_box_value.'</textarea><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'number'){
            echo '<input type="number" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width:100px"></div>';
        }elseif($meta_box['type'] == 'minitext'){
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%;max-width:150px;"><br><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'color'){
            echo '<input type="color" value="'.$meta_box_value.'" name="'.$meta_box['name'].'"></div>';
        }elseif($meta_box['type'] == 'file'){
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: calc(100% - 80px)"><a href="javascript:;" class="modown-add-file button">上传文件</a><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
?>
        <script>
            jQuery(function($) {
                $(document).on('click', '.modown-add-file', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    var original_send = wp.media.editor.send.attachment;
                    wp.media.editor.send.attachment = function(props, attachment) {
                        id.val(attachment.url); 
                        wp.media.editor.send.attachment = original_send; 
                    };
                    wp.media.editor.open(button);
                    return false;
                });
            });
        </script>
<?php
        }else{
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%"><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }
    }
   
    echo '<input type="hidden" name="modown_keywords_metabox_nonce" id="modown_keywords_metabox_nonce" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
}

function MBThemes_keywords_save_postdata( $post_id ) {
    global $keywordsmeta_form;
    if(!isset($_POST['modown_keywords_metabox_nonce']))
        return;
   
    if ( !current_user_can( 'edit_posts', $post_id ))
        return;
                   
    foreach($keywordsmeta_form as $meta_box) {
        if(isset($_POST[$meta_box['name']])){
            update_post_meta($post_id, $meta_box['name'], $_POST[$meta_box['name']]);
            if($_POST[$meta_box['name']] == ''){
                delete_post_meta($post_id, $meta_box['name']);
            }
        }else{
            delete_post_meta($post_id, $meta_box['name']);
        }
    }
}
add_action('save_post', 'MBThemes_keywords_save_postdata');



add_action( 'admin_menu', 'MBThemes_caticons_page_metabox' );
function MBThemes_caticons_page_metabox() {
    global $pagenow;
    if($pagenow == 'post.php'){
        if(isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit'){
            if(get_page_template_slug($_GET['post']) == 'template/cat-icons.php'){
                add_meta_box( 'caticons-postmeta-box','分类图标属性', 'MBThemes_caticons_show_metabox', 'page', 'normal', 'high' );
            }
        }
    }
}

$caticonsmeta_form = array(
    "分类IDs" => array(
        "name" => "cats",
        "desc" => "",
        "type" => "textarea",
        "title" => "分类IDs",
        "desc" => "多个用半角英文逗号隔开，例如：1,3,12。<a href='https://www.mobantu.com/7616.html' target='_blank'>如何查看ID</a>")
);

function MBThemes_caticons_show_metabox() {
    global $post, $caticonsmeta_form;
    foreach($caticonsmeta_form as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if($meta_box_value == "" && isset($meta_box['std']))
            $meta_box_value = $meta_box['std'];
        echo'<div style="padding-left:100px;margin: 1em 0;position:relative;"><label style="font-weight:bold;width:100px;position:absolute;left:0;top:0;">'.$meta_box['title'].'</label>';
        if($meta_box['type'] == 'checkbox'){
            echo '<input type="checkbox" value="1" name="'.$meta_box['name'].'" ';
            if ( htmlentities( $meta_box_value, 1 ) == '1' ) echo ' checked="checked"';
            echo '><span style="color:#666;font-size:12px">'.$meta_box['desc'].'</span></div>';
        }elseif($meta_box['type'] == 'textarea'){
            echo '<textarea name="'.$meta_box['name'].'" style="width: 100%" row="3">'.$meta_box_value.'</textarea><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'number'){
            echo '<input type="number" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width:100px"></div>';
        }elseif($meta_box['type'] == 'minitext'){
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%;max-width:150px;"><br><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }elseif($meta_box['type'] == 'color'){
            echo '<input type="color" value="'.$meta_box_value.'" name="'.$meta_box['name'].'"></div>';
        }elseif($meta_box['type'] == 'file'){
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: calc(100% - 80px)"><a href="javascript:;" class="modown-add-file button">上传文件</a><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
?>
        <script>
            jQuery(function($) {
                $(document).on('click', '.modown-add-file', function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    var original_send = wp.media.editor.send.attachment;
                    wp.media.editor.send.attachment = function(props, attachment) {
                        id.val(attachment.url); 
                        wp.media.editor.send.attachment = original_send; 
                    };
                    wp.media.editor.open(button);
                    return false;
                });
            });
        </script>
<?php
        }else{
            echo '<input type="text" value="'.$meta_box_value.'" name="'.$meta_box['name'].'" style="width: 100%"><div style="color:#666;font-size:12px">'.$meta_box['desc'].'</div></div>';
        }
    }
   
    echo '<input type="hidden" name="modown_caticons_metabox_nonce" id="modown_caticons_metabox_nonce" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
}

function MBThemes_caticons_save_postdata( $post_id ) {
    global $caticonsmeta_form;
    if(!isset($_POST['modown_caticons_metabox_nonce']))
        return;
   
    if ( !current_user_can( 'edit_posts', $post_id ))
        return;
                   
    foreach($caticonsmeta_form as $meta_box) {
        if(isset($_POST[$meta_box['name']])){
            update_post_meta($post_id, $meta_box['name'], $_POST[$meta_box['name']]);
            if($_POST[$meta_box['name']] == ''){
                delete_post_meta($post_id, $meta_box['name']);
            }
        }else{
            delete_post_meta($post_id, $meta_box['name']);
        }
    }
}
add_action('save_post', 'MBThemes_caticons_save_postdata');



add_filter( 'admin_post_thumbnail_html', 'MBThemes_thumbnail_url_field' );
add_action( 'save_post', 'MBThemes_thumbnail_url_field_save', 10, 2 );

function MBThemes_thumbnail_url_field( $html ) {
    global $post;
    $value = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE );
    $nonce = wp_create_nonce( plugin_basename(__FILE__) );
    $html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="' 
        . esc_attr( $nonce ) . '">';
    $html .= '<div><p>外链特色图片地址（留空即删除）：</p>';
    $html .= '<p><input type="url" name="thumbnail_ext_url" id="thumbnail_ext_url" value="' . $value . '" style="width:100%"></p>';
    if ( ! empty($value) ) {
        $html .= '<p><img style="max-width:254px;height:auto;" src="' 
            . esc_url($value) . '"></p>';
    }
    $html .= '</div>';
    return $html;
}

function MBThemes_thumbnail_url_field_save( $pid, $post ) {

	if(!isset($_POST['thumbnail_ext_url_nonce']))
    	return;

    $cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
    if (
        ! current_user_can( $cap, $pid )
        || ! post_type_supports( $post->post_type, 'thumbnail' )
        || defined( 'DOING_AUTOSAVE' )
    ) {
        return;
    }

    $url = $_POST['thumbnail_ext_url'];
    update_post_meta( $pid, '_thumbnail_ext_url', esc_url($url) );
    
}


function mbt_add_category_field(){  
    wp_enqueue_media ();
    echo '<div class="form-field">  
            <label for="banner_img">Banner横幅</label>  
            <input name="banner_img" id="banner_img" type="text" value="" style="width:calc(100% - 100px)">
            <a href="javascript:;" class="button upload-img">上传图片</a>
            <script>
                jQuery(document).ready(function() {
                var $ = jQuery;
                if ($(".upload-img").length > 0) {
                if ( typeof wp !== "undefined" && wp.media && wp.media.editor) {
                $(document).on("click", ".upload-img", function(e) {
                e.preventDefault();
                var button = $(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                id.val(attachment.url);
                };
                wp.media.editor.open(button);
                return false;
                });
                }
                }
                });
            </script>
          </div>';
    echo '<div class="form-field">  
            <label for="thumb_img">特色图</label>  
            <input name="thumb_img" id="thumb_img" type="text" value="" style="width:calc(100% - 100px)">
            <a href="javascript:;" class="button upload-img">上传图片</a>
          </div>';
    if($_GET['taxonomy'] == 'category'){
        echo '<div class="form-field">  
            <label for="thumb_icon">图标</label>  
            <input name="thumb_icon" id="thumb_icon" type="text" value="" style="width:calc(100% - 100px)">
            <a href="javascript:;" class="button upload-img">上传图片</a>
          </div>';
    }
    echo '<div class="form-field">  
            <label for="seo-title">SEO标题</label>  
            <input name="seo-title" id="seo-title" type="text" value="">
          </div>';
    echo '<div class="form-field">  
            <label for="seo-keyword">SEO关键字</label>  
            <input name="seo-keyword" id="seo-keyword" type="text" value="">
          </div>';
    echo '<div class="form-field">  
            <label for="seo-description">SEO描述</label>  
            <textarea name="seo-description" id="seo-description" row="5"></textarea>
          </div>';
    
    if($_GET['taxonomy'] == 'category'){
        echo '<div class="form-field">  
            <label for="timthumb_height">缩略图图片高度</label>  
            <input name="timthumb_height" id="timthumb_height" type="text" value="">
            <p>文章列表的图片高度，单位px，输入一个数字即可，默认180，如果是正方形，请填285（若最大列数为小5列，默认144，正方形请填228），此设置仅对该分类页面以及mocat短代码指定该分类模块生效。</p>  
          </div>';
        echo '<div class="form-field">
                <label for="filter_s">筛选开关</label>
                <select name="filter_s" id="filter_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';
        echo '<div class="form-field">
                <label for="taxonomys_s">自定义分类法筛选开关</label>
                <select name="taxonomys_s" id="taxonomys_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';
        echo '<div class="form-field">  
            <label for="taxonomys">自定义分类法筛选</label>  
            <input name="taxonomys" id="taxonomys" type="text" value="">  
            <p>名称,别名,筛选参数，多个用|隔开。例如：格式,format,fm|大小,size,sz<br>名称,别名,筛选参数,显示IDs，每项显示的ID用-隔开。例如：格式,format,fm,7-8-9|大小,size,sz,4-5</p>  
          </div>';
        echo '<div class="form-field">
                <label for="tags_s">标签筛选开关</label>
                <select name="tags_s" id="tags_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';
        echo '<div class="form-field">  
            <label for="tags">标签筛选IDs</label>  
            <input name="tags" id="tags" type="text" value="" placeholder="1,3,6">  
            <p>需要筛选的标签ID列表，多个用半角英文逗号隔开。</p>  
          </div>';   
        echo '<div class="form-field">
                <label for="price_s">价格筛选开关</label>
                <select name="price_s" id="price_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';  
        echo '<div class="form-field">
                <label for="order_s">排序筛选开关</label>
                <select name="order_s" id="order_s" class="postform">
                    <option value="0">默认</option>
                    <option value="1">关闭</option>
                    <option value="2">开启</option>
                </select>
            </div>';
        echo '<div class="form-field">
            <label for="down_position">下载框位置</label>
            <select name="down_position" id="down_position" class="postform">
                <option value="">默认</option>
                <option value="side">边栏</option>
                <option value="top">内容上</option>
                <option value="bottom">内容下</option>
                <option value="sidetop">边栏+内容上</option>
                <option value="sidebottom">边栏+内容下</option>
                <option value="box">独立模块</option>
                <option value="boxbottom">独立模块+内容下</option>
                <option value="boxside">独立模块+边栏</option>
                <option value="none">隐藏</option>
            </select>
        </div>';   
        echo '<div class="form-field">
            <label for="vip_see">VIP可见</label>
            <select name="vip_see" id="vip_see" class="postform">
                <option value="0">关闭</option>
                <option value="1">开启</option>
            </select>
            <p class="description">不推荐开启，仅对分类页、分类下的文章页有效，其他途径获取的文章信息无效</p>
        </div>';  
        echo '<div class="form-field">
            <label for="cat_vip">分类VIP</label>
            <select name="cat_vip" id="cat_vip" class="postform">
                <option value="0">不启用</option>
                <option value="1">启用</option>
            </select>
            <p class="description" style="color:red">仅支持一级分类启用，子分类不需要启用。</p>
        </div>';  
        echo '<div class="form-field">  
            <label for="cat_vip_name">分类VIP名称</label>  
            <input name="cat_vip_name" id="cat_vip_name" type="text" value="">  
            <p>仅对上面分类VIP选【启用】时生效。例如：视频VIP</p>  
          </div>';
        echo '<div class="form-field">  
            <label for="cat_vip_desc">分类VIP介绍</label>  
            <textarea name="cat_vip_desc" id="cat_vip_desc" row="5"></textarea>
            <p>仅对上面分类VIP选【启用】时生效。</p>  
          </div>';
        echo '<div class="form-field">  
            <label for="cat_vip_month">分类VIP包月价格</label>  
            <input name="cat_vip_month" id="cat_vip_month" type="text" value="">  
            <p>仅对上面分类VIP选【启用】时生效。</p>  
          </div>';
        echo '<div class="form-field">  
            <label for="cat_vip_quarter">分类VIP包季价格</label>  
            <input name="cat_vip_quarter" id="cat_vip_quarter" type="text" value="">  
            <p>仅对上面分类VIP选【启用】时生效。</p>  
          </div>';
        echo '<div class="form-field">  
            <label for="cat_vip_year">分类VIP包年价格</label>  
            <input name="cat_vip_year" id="cat_vip_year" type="text" value="">  
            <p>仅对上面分类VIP选【启用】时生效。</p>  
          </div>';
        echo '<div class="form-field">  
            <label for="cat_vip_life">分类VIP终身价格</label>  
            <input name="cat_vip_life" id="cat_vip_life" type="text" value="">  
            <p>仅对上面分类VIP选【启用】时生效。</p>  
          </div>';
        echo '<div class="form-field">
            <label for="nosidebar">文章单栏</label>
            <select name="nosidebar" id="nosidebar" class="postform">
                <option value="0">关闭</option>
                <option value="1">开启</option>
            </select>
            <p class="description">分类下的文章内页单栏显示（无右侧栏）</p>
        </div>';
        echo '<div class="form-field">
            <label for="list_column">Grid文章列数</label>
            <select name="style" id="list_column" class="postform">
                <option value="">默认</option>
                <option value="four">4列</option>
                <option value="four-large">大4列</option>
                <option value="five">5列</option>
                <option value="five-mini">小5列</option>
                <option value="six">6列</option>
                <option value="six-mini">小6列</option>
            </select>
        </div>';
    }

    echo '<div class="form-field">
        <label for="style">显示样式</label>
        <select name="style" id="style" class="postform">
            <option value="default">默认</option>
            <option value="grid">网格Grid</option>
            <option value="grid-audio">音频Grid</option>
            <option value="list">列表List</option>
            <option value="list2">两列List（无边栏）</option>
            <option value="list3">三列List（无边栏）</option>
            <option value="list-title">纯标题List</option>
        </select>
    </div>';  

    echo '<div class="form-field">
        <label for="waterfall">瀑布流</label>
        <select name="waterfall" id="waterfall" class="postform">
            <option value="default">默认</option>
            <option value="0">关闭</option>
            <option value="1">开启</option>
        </select>
        <p class="description">仅支持默认网格Grid显示样式的瀑布流显示，且仅对分类标签专题列表页面有效</p>
    </div>';                
}  
add_action('category_add_form_fields','mbt_add_category_field',10,2); 
add_action('post_tag_add_form_fields','mbt_add_category_field',10,2);   
add_action('topic_add_form_fields','mbt_add_category_field',10,2);  
  

function mbt_edit_category_field($tag){ 
    wp_enqueue_media ();
    echo '<tr class="form-field">  
            <th scope="row"><label for="banner_img">Banner图片</label></th>  
            <td>  
                <input name="banner_img" id="banner_img" type="text" value="';  
                echo get_term_meta($tag->term_id,'banner_img',true).'" style="width:calc(100% - 100px)"/>
                <a href="javascript:;" class="button upload-img">上传图片</a>
                <br><img src="'.get_term_meta($tag->term_id,'banner_img',true).'" style="max-width:400px;height:auto;">
                <script>
                    jQuery(document).ready(function() {
                    var $ = jQuery;
                    if ($(".upload-img").length > 0) {
                    if ( typeof wp !== "undefined" && wp.media && wp.media.editor) {
                    $(document).on("click", ".upload-img", function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.url);
                    };
                    wp.media.editor.open(button);
                    return false;
                    });
                    }
                    }
                    });
                </script>
            </td>  
        </tr>'; 
    echo '<tr class="form-field">  
            <th scope="row"><label for="banner_img">特色图</label></th>  
            <td>  
                <input name="thumb_img" id="thumb_img" type="text" value="';  
                echo get_term_meta($tag->term_id,'thumb_img',true).'" style="width:calc(100% - 100px)"/>
                <a href="javascript:;" class="button upload-img">上传图片</a>
                <br><img src="'.get_term_meta($tag->term_id,'thumb_img',true).'" style="max-width:400px;height:auto;">
            </td>  
        </tr>'; 
    if($_GET['taxonomy'] == 'category'){
        echo '<tr class="form-field">  
            <th scope="row"><label for="banner_img">图标</label></th>  
            <td>  
                <input name="thumb_icon" id="thumb_icon" type="text" value="';  
                echo get_term_meta($tag->term_id,'thumb_icon',true).'" style="width:calc(100% - 100px)"/>
                <a href="javascript:;" class="button upload-img">上传图片</a>
                <br><img src="'.get_term_meta($tag->term_id,'thumb_icon',true).'" style="max-width:400px;height:auto;">
            </td>  
        </tr>';
    }
    echo '<tr class="form-field">  
            <th scope="row"><label for="seo-title">SEO标题</label></th>  
            <td>  
                <input name="seo-title" id="seo-title" type="text" value="';  
                echo get_term_meta($tag->term_id,'seo-title',true).'" />
            </td>  
        </tr>';
    echo '<tr class="form-field">  
            <th scope="row"><label for="seo-keyword">SEO关键字</label></th>  
            <td>  
                <input name="seo-keyword" id="seo-keyword" type="text" value="';  
                echo get_term_meta($tag->term_id,'seo-keyword',true).'" />
            </td>  
        </tr>';
    echo '<tr class="form-field">  
            <th scope="row"><label for="seo-description">SEO描述</label></th>  
            <td>  
                <textarea name="seo-description" id="seo-description" row="5">';  
                echo get_term_meta($tag->term_id,'seo-description',true).'</textarea>
            </td>  
        </tr>';
    
    if($_GET['taxonomy'] == 'category'){
        echo '<tr class="form-field">  
            <th for="timthumb_height">缩略图图片高度</th>
            <td>  
            <input name="timthumb_height" id="timthumb_height" type="text" value="'.get_term_meta($tag->term_id,'timthumb_height',true).'">
            <p>文章列表的图片高度，单位px，输入一个数字即可，默认180，如果是正方形，请填285（若最大列数为小5列，默认144，正方形请填228），此设置仅对该分类页面以及mocat短代码指定该分类模块生效。</p> 
            </td> 
          </tr>';
        $filter_s = get_term_meta($tag->term_id,'filter_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="filter_s">筛选开关</label>
                    <td>
                        <select name="filter_s" id="filter_s" class="postform">
                            <option value="0" '. ('0'==$filter_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$filter_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$filter_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>';  
        $taxonomys_s = get_term_meta($tag->term_id,'taxonomys_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="taxonomys_s">自定义分类法筛选开关</label>
                    <td>
                        <select name="taxonomys_s" id="taxonomys_s" class="postform">
                            <option value="0" '. ('0'==$taxonomys_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$taxonomys_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$taxonomys_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>';  
        echo '<tr class="form-field">  
            <th scope="row"><label for="taxonomys">自定义分类法筛选</label></th>  
            <td>  
                <input name="taxonomys" id="taxonomys" type="text" value="';  
                echo get_term_meta($tag->term_id,'taxonomys',true).'" /><br>  
                <span class="cat-color">名称,别名,筛选参数，多个用|隔开。例如：格式,format,fm|大小,size,sz<br>名称,别名,筛选参数,显示IDs，每项显示的ID用-隔开。例如：格式,format,fm,7-8-9|大小,size,sz,4-5</span>  
            </td>  
        </tr>'; 
        $tags_s = get_term_meta($tag->term_id,'tags_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="tags_s">标签筛选开关</label>
                    <td>
                        <select name="tags_s" id="tags_s" class="postform">
                            <option value="0" '. ('0'==$tags_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$tags_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$tags_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>';  
        echo '<tr class="form-field">  
            <th scope="row"><label for="tags">标签筛选IDs</label></th>  
            <td>  
                <input name="tags" id="tags" type="text" value="';  
                echo get_term_meta($tag->term_id,'tags',true).'" /><br>  
                <span class="cat-color">'.$tag->name.' 的需要筛选的标签ID列表，多个用半角英文逗号隔开。</span>  
            </td>  
        </tr>'; 
        $price_s = get_term_meta($tag->term_id,'price_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="price_s">价格筛选开关</label>
                    <td>
                        <select name="price_s" id="price_s" class="postform">
                            <option value="0" '. ('0'==$price_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$price_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$price_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>'; 
        $order_s = get_term_meta($tag->term_id,'order_s',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="order_s">排序筛选开关</label>
                    <td>
                        <select name="order_s" id="order_s" class="postform">
                            <option value="0" '. ('0'==$order_s?'selected="selected"':'') .'>默认</option>
                            <option value="1" '. ('1'==$order_s?'selected="selected"':'') .'>关闭</option>
                            <option value="2" '. ('2'==$order_s?'selected="selected"':'') .'>开启</option>
                        </select>
                    </td>
                </th>
            </tr>'; 
        $down_position = get_term_meta($tag->term_id,'down_position',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="down_position">下载框位置</label>
                    <td>
                        <select name="down_position" id="down_position" class="postform">
                            <option value="">默认</option>
                            <option value="side" '. ('side'==$down_position?'selected="selected"':'') .'>边栏</option>
                            <option value="top" '. ('top'==$down_position?'selected="selected"':'') .'>内容上</option>
                            <option value="bottom" '. ('bottom'==$down_position?'selected="selected"':'') .'>内容下</option>
                            <option value="sidetop" '. ('sidetop'==$down_position?'selected="selected"':'') .'>边栏+内容上</option>
                            <option value="sidebottom" '. ('sidebottom'==$down_position?'selected="selected"':'') .'>边栏+内容下</option>
                            <option value="box" '. ('box'==$down_position?'selected="selected"':'') .'>独立模块</option>
                            <option value="boxbottom" '. ('boxbottom'==$down_position?'selected="selected"':'') .'>独立模块+内容下</option>
                            <option value="boxside" '. ('boxside'==$down_position?'selected="selected"':'') .'>独立模块+边栏</option>
                            <option value="none" '. ('none'==$down_position?'selected="selected"':'') .'>隐藏</option>
                        </select>
                    </td>
                </th>
            </tr>';
        $vip_see = get_term_meta($tag->term_id,'vip_see',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="vip_see">VIP可见</label>
                    <td>
                        <select name="vip_see" id="vip_see" class="postform">
                            <option value="0" '. ('0'==$vip_see?'selected="selected"':'') .'>关闭</option>
                            <option value="1" '. ('1'==$vip_see?'selected="selected"':'') .'>开启</option>
                        </select>
                        <p class="description">不推荐启用，仅对分类页、分类下的文章页有效，其他途径获取的文章信息无效</p>
                    </td>
                </th>
            </tr>';  
        $cat_vip = get_term_meta($tag->term_id,'cat_vip',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="cat_vip">分类VIP</label>
                    <td>
                        <select name="cat_vip" id="cat_vip" class="postform">
                            <option value="0" '. ('0'==$cat_vip?'selected="selected"':'') .'>不启用</option>
                            <option value="1" '. ('1'==$cat_vip?'selected="selected"':'') .'>启用</option>
                        </select>
                        <p class="description" style="color:red">仅支持一级分类启用，子分类不需要启用。</p>
                    </td>
                </th>
            </tr>';  
        echo '<tr class="form-field">  
            <th scope="row"><label for="cat_vip_name">分类VIP名称</label></th>  
            <td>  
                <input name="cat_vip_name" id="cat_vip_name" type="text" value="';  
                echo get_term_meta($tag->term_id,'cat_vip_name',true).'" /><br>  
                <span class="cat-color">仅对上面分类VIP选【启用】时生效。例如：视频VIP</span>  
            </td>  
        </tr>'; 
        echo '<tr class="form-field">  
            <th scope="row"><label for="cat_vip_desc">分类VIP描述</label></th>  
            <td>  
                <textarea name="cat_vip_desc" id="cat_vip_desc" row="5">';  
                echo get_term_meta($tag->term_id,'cat_vip_desc',true).'</textarea><br>  
                <span class="cat-color">仅对上面分类VIP选【启用】时生效。</span>  
            </td>  
        </tr>';
        echo '<tr class="form-field">  
            <th scope="row"><label for="cat_vip_month">分类VIP包月价格</label></th>  
            <td>  
                <input name="cat_vip_month" id="cat_vip_month" type="text" value="';  
                echo get_term_meta($tag->term_id,'cat_vip_month',true).'" /><br>  
                <span class="cat-color">仅对上面分类VIP选【启用】时生效。</span>  
            </td>  
        </tr>'; 
        echo '<tr class="form-field">  
            <th scope="row"><label for="cat_vip_quarter">分类VIP包季价格</label></th>  
            <td>  
                <input name="cat_vip_quarter" id="cat_vip_quarter" type="text" value="';  
                echo get_term_meta($tag->term_id,'cat_vip_quarter',true).'" /><br>  
                <span class="cat-color">仅对上面分类VIP选【启用】时生效。</span>  
            </td>  
        </tr>'; 
        echo '<tr class="form-field">  
            <th scope="row"><label for="cat_vip_year">分类VIP包年价格</label></th>  
            <td>  
                <input name="cat_vip_year" id="cat_vip_year" type="text" value="';  
                echo get_term_meta($tag->term_id,'cat_vip_year',true).'" /><br>  
                <span class="cat-color">仅对上面分类VIP选【启用】时生效。</span>  
            </td>  
        </tr>'; 
        echo '<tr class="form-field">  
            <th scope="row"><label for="cat_vip_life">分类VIP终身价格</label></th>  
            <td>  
                <input name="cat_vip_life" id="cat_vip_life" type="text" value="';  
                echo get_term_meta($tag->term_id,'cat_vip_life',true).'" /><br>  
                <span class="cat-color">仅对上面分类VIP选【启用】时生效。</span>  
            </td>  
        </tr>'; 
        $nosidebar = get_term_meta($tag->term_id,'nosidebar',true);
        echo '<tr class="form-field">
                <th scope="row">
                    <label for="nosidebar">文章单栏</label>
                    <td>
                        <select name="nosidebar" id="nosidebar" class="postform">
                            <option value="0" '. ('0'==$nosidebar?'selected="selected"':'') .'>关闭</option>
                            <option value="1" '. ('1'==$nosidebar?'selected="selected"':'') .'>开启</option>
                        </select>
                        <p class="description">分类下的文章内页单栏显示（无右侧栏）</p>
                    </td>
                </th>
            </tr>';  
        $list_column = get_term_meta($tag->term_id,'list_column',true);
        echo '<tr class="form-field">
                <th scope="row">
                        <label for="style">Grid文章列数</label>
                        <td>
                        <select name="list_column" id="list_column" class="postform">
                            <option value="" '. (''==$list_column?'selected="selected"':'') .'>默认</option>
                            <option value="four" '. ('four'==$list_column?'selected="selected"':'') .'>4列</option>
                            <option value="four-large" '. ('four-large'==$list_column?'selected="selected"':'') .'>大4列</option>
                            <option value="five" '. ('five'==$list_column?'selected="selected"':'') .'>5列</option>
                            <option value="five-mini" '. ('five-mini'==$list_column?'selected="selected"':'') .'>小5列</option>
                            <option value="six" '. ('six'==$list_column?'selected="selected"':'') .'>6列</option>
                            <option value="six-mini" '. ('six-mini'==$list_column?'selected="selected"':'') .'>小6列</option>
                        </select>
                </td>
                    </th>
        </tr>';
    }      
    $style = get_term_meta($tag->term_id,'style',true);
    echo '<tr class="form-field">
                <th scope="row">
                    <label for="style">显示样式</label>
                    <td>
                        <select name="style" id="style" class="postform">
                            <option value="default" '. ('default'==$style?'selected="selected"':'') .'>默认</option>
                            <option value="grid" '. ('grid'==$style?'selected="selected"':'') .'>网格Grid</option>
                            <option value="grid-audio" '. ('grid-audio'==$style?'selected="selected"':'') .'>音频Grid</option>
                            <option value="list" '. ('list'==$style?'selected="selected"':'') .'>列表List</option>
                            <option value="list2" '. ('list2'==$style?'selected="selected"':'') .'>两列List（无边栏）</option>
                            <option value="list3" '. ('list3'==$style?'selected="selected"':'') .'>三列List（无边栏）</option>';
                            echo '<option value="list-title" '. ('list-title'==$style?'selected="selected"':'') .'>纯标题List</option>';
                        echo '</select>
                    </td>
                </th>
            </tr>'; 
      

    $waterfall = get_term_meta($tag->term_id,'waterfall',true);
    echo '<tr class="form-field">
            <th scope="row">
                <label for="waterfall">瀑布流</label>
                <td>
                    <select name="waterfall" id="waterfall" class="postform">
                        <option value="default" '. ('default'==$waterfall?'selected="selected"':'') .'>默认</option>
                        <option value="0" '. ('0'==$waterfall?'selected="selected"':'') .'>关闭</option>
                        <option value="1" '. ('1'==$waterfall?'selected="selected"':'') .'>开启</option>
                    </select>
                    <p class="description">仅支持默认网格Grid显示样式的瀑布流显示，且仅对分类标签专题列表页面有效</p>
                </td>
            </th>
        </tr>';   
}  
add_action('category_edit_form_fields','mbt_edit_category_field',10,2);  
add_action('post_tag_edit_form_fields','mbt_edit_category_field',10,2);
add_action('topic_edit_form_fields','mbt_edit_category_field',10,2);
 
function mbt_taxonomy_metadate_edited($term_id){  
    if(!current_user_can('manage_categories')){  
        return $term_id;  
    } 
    if(isset($_POST['banner_img'])){ 
        update_term_meta($term_id,'banner_img',$_POST['banner_img']);
    }
    if(isset($_POST['thumb_img'])){ 
        update_term_meta($term_id,'thumb_img',$_POST['thumb_img']);
    }
    if(isset($_POST['thumb_icon'])){ 
        update_term_meta($term_id,'thumb_icon',$_POST['thumb_icon']);
    }
    if(isset($_POST['seo-title'])){ 
        update_term_meta($term_id,'seo-title',$_POST['seo-title']);
    }
    if(isset($_POST['seo-keyword'])){ 
        update_term_meta($term_id,'seo-keyword',$_POST['seo-keyword']);
    }
    if(isset($_POST['seo-description'])){ 
        update_term_meta($term_id,'seo-description',$_POST['seo-description']);
    }
    if(isset($_POST['timthumb_height'])){
        update_term_meta($term_id,'timthumb_height',$_POST['timthumb_height']);
    }
    if(isset($_POST['tags_s'])){
        update_term_meta($term_id,'tags_s',$_POST['tags_s']);
        update_term_meta($term_id,'tags',$_POST['tags']);        
    }
    if(isset($_POST['taxonomys_s'])){
        update_term_meta($term_id,'taxonomys_s',$_POST['taxonomys_s']);
        update_term_meta($term_id,'taxonomys',$_POST['taxonomys']);        
    }
    if(isset($_POST['price_s'])){
        update_term_meta($term_id,'price_s',$_POST['price_s']);        
    }
    if(isset($_POST['order_s'])){
        update_term_meta($term_id,'order_s',$_POST['order_s']);        
    }
    if(isset($_POST['filter_s'])){
        update_term_meta($term_id,'filter_s',$_POST['filter_s']);        
    }
    if(isset($_POST['down_position'])){
        update_term_meta($term_id,'down_position',$_POST['down_position']);        
    }
    if(isset($_POST['vip_see'])){
        update_term_meta($term_id,'vip_see',$_POST['vip_see']);        
    }
    if(isset($_POST['cat_vip'])){
        update_term_meta($term_id,'cat_vip',$_POST['cat_vip']);        
    }
    if(isset($_POST['cat_vip_name'])){
        update_term_meta($term_id,'cat_vip_name',$_POST['cat_vip_name']);        
    }
    if(isset($_POST['cat_vip_desc'])){
        update_term_meta($term_id,'cat_vip_desc',$_POST['cat_vip_desc']);        
    }
    if(isset($_POST['cat_vip_month'])){
        update_term_meta($term_id,'cat_vip_month',$_POST['cat_vip_month']);        
    }
    if(isset($_POST['cat_vip_quarter'])){
        update_term_meta($term_id,'cat_vip_quarter',$_POST['cat_vip_quarter']);        
    }
    if(isset($_POST['cat_vip_year'])){
        update_term_meta($term_id,'cat_vip_year',$_POST['cat_vip_year']);        
    }
    if(isset($_POST['cat_vip_life'])){
        update_term_meta($term_id,'cat_vip_life',$_POST['cat_vip_life']);        
    }
    if(isset($_POST['nosidebar'])){
        update_term_meta($term_id,'nosidebar',$_POST['nosidebar']);        
    }
    if(isset($_POST['style'])){
        update_term_meta($term_id,'style',$_POST['style']);
    }
    if(isset($_POST['list_column'])){
        update_term_meta($term_id,'list_column',$_POST['list_column']);
    }
    if(isset($_POST['waterfall'])){
        update_term_meta($term_id,'waterfall',$_POST['waterfall']);        
    }
} 

add_action('created_category','mbt_taxonomy_metadate_edited',10,1);  
add_action('edited_category','mbt_taxonomy_metadate_edited',10,1); 
add_action('created_post_tag','mbt_taxonomy_metadate_edited',10,1);  
add_action('edited_post_tag','mbt_taxonomy_metadate_edited',10,1);
add_action('created_topic','mbt_taxonomy_metadate_edited',10,1);  
add_action('edited_topic','mbt_taxonomy_metadate_edited',10,1); 


add_action( 'show_user_profile', 'mbt_user_profile_fields' );
add_action( 'edit_user_profile', 'mbt_user_profile_fields' );
add_action( 'user_new_form', 'mbt_user_profile_fields' );
function mbt_user_profile_fields( $user ) { 
    if(current_user_can('administrator')){
    global $wpdb,$pagenow;
    if(isset($user->ID)){
        $mobile = $wpdb->get_var("select mobile from $wpdb->users where ID=".$user->ID);
        $erphp_loggedin = get_user_meta($user->ID,'erphp_loggedin',true);
        $verify_user = get_user_meta($user->ID,'verify_user',true);
        $verify_error = get_user_meta($user->ID,'verify_error',true);
        $verify_name = get_user_meta($user->ID,'verify_name',true);
        $verify_idcard = get_user_meta($user->ID,'verify_idcard',true);
        $photo = get_user_meta($user->ID,'photo',true);
    }else{
        $mobile = '';$erphp_loggedin = '';$verify_user = '';$verify_error = '';$verify_name = '';$verify_idcard = '';
        $photo = '';
    }

    wp_enqueue_media ();
?>
<h3>Modown属性</h3>
<table class="form-table">
    <tr>
        <th><label for="photo">头像地址</label></th>
        <td>
            <input type="text" name="photo" id="photo" value="<?php echo $photo; ?>" class="regular-text" /><a href="javascript:;" class="button upload-img">上传头像</a>
            <script>
                jQuery(document).ready(function() {
                var $ = jQuery;
                if ($(".upload-img").length > 0) {
                if ( typeof wp !== "undefined" && wp.media && wp.media.editor) {
                $(document).on("click", ".upload-img", function(e) {
                e.preventDefault();
                var button = $(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                id.val(attachment.url);
                };
                wp.media.editor.open(button);
                return false;
                });
                }
                }
                });
            </script>
        </td>
    </tr>
    <tr>
        <th><label for="mobile">手机号</label></th>
        <td>
            <input type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>" class="regular-text" />
        </td>
    </tr>
    <?php if(ERPHPDOWN_IS_ACTIVE && $pagenow == 'user-new.php'){
        $erphp_life_name    = '终身VIP'.(get_option('erphp_life_name')?'('.get_option('erphp_life_name').')':'');
        $erphp_year_name    = '包年VIP'.(get_option('erphp_year_name')?'('.get_option('erphp_year_name').')':'');
        $erphp_quarter_name = '包季VIP'.(get_option('erphp_quarter_name')?'('.get_option('erphp_quarter_name').')':'');
        $erphp_month_name  = '包月VIP'.(get_option('erphp_month_name')?'('.get_option('erphp_month_name').')':'');
        $erphp_day_name  = '体验VIP'.(get_option('erphp_day_name')?'('.get_option('erphp_day_name').')':'');
    ?>
    <tr>
        <th><label for="mobile">VIP</label></th>
        <td>
            <input type="radio" name="userType" value="0" checked/>无<br />
            <input type="radio" name="userType" value="6" /><?php echo $erphp_day_name;?><br />
            <input type="radio" name="userType" value="7" /><?php echo $erphp_month_name;?><br />
            <input type="radio" name="userType" value="8" /><?php echo $erphp_quarter_name;?><br />
            <input type="radio" name="userType" value="9" /><?php echo $erphp_year_name;?><br />
            <input type="radio" name="userType" value="10" /><?php echo $erphp_life_name;?> 
        </td>
    </tr>
    <?php }?>
    <?php if(_MBT('plugin_loggedin')){?>
    <tr>
        <th><label for="erphp_loggedin">禁封</label></th>
        <td>
            <input type="checkbox" name="erphp_loggedin" id="erphp_loggedin" value="1" <?php if($erphp_loggedin) echo 'checked';?>>
        </td>
    </tr>
    <?php }?>
    <?php if(_MBT('user_idcard_verify')){?>
    <tr>
        <th><label for="verify_user">已实名</label></th>
        <td>
            <input type="checkbox" name="verify_user" id="verify_user" value="1" <?php if($verify_user) echo 'checked';?>>
        </td>
    </tr>
    <tr>
        <th><label for="verify_error">禁止实名</label></th>
        <td>
            <input type="checkbox" name="verify_error" id="verify_error" value="1" <?php if($verify_error) echo 'checked';?>>（由于实名未通过被禁止再次实名请求）
        </td>
    </tr>
    <tr>
        <th><label for="verify_name">姓名</label></th>
        <td>
            <input type="text" name="verify_name" id="verify_name" value="<?php echo $verify_name; ?>" class="regular-text" />
        </td>
    </tr>
    <tr>
        <th><label for="verify_idcard">身份证</label></th>
        <td>
            <input type="text" name="verify_idcard" id="verify_idcard" value="<?php echo $verify_idcard; ?>" class="regular-text" />
        </td>
    </tr>
    <?php }?>
</table>
<?php }
}

add_action( 'personal_options_update', 'mbt_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'mbt_save_user_profile_fields' );
add_action( 'user_register', 'mbt_save_user_profile_fields' );
function mbt_save_user_profile_fields( $user_id ) {
    if(current_user_can('administrator')){
        global $wpdb,$pagenow;
        if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
        if(isset($_POST['mobile'])){
            $wpdb->query("UPDATE $wpdb->users SET mobile = '".esc_sql($_POST['mobile'])."' WHERE ID = '".$user_id."'");
        }

        if(isset($_POST['erphp_loggedin'])){
            update_user_meta($user_id,'erphp_loggedin',$_POST['erphp_loggedin']);
        }else{
            delete_user_meta($user_id,'erphp_loggedin');
        }

        if(isset($_POST['verify_user'])){
            update_user_meta($user_id,'verify_user',$_POST['verify_user']);
        }else{
            delete_user_meta($user_id,'verify_user');
        }

        if(isset($_POST['verify_error'])){
            update_user_meta($user_id,'verify_error',$_POST['verify_error']);
        }else{
            delete_user_meta($user_id,'verify_error');
        }

        if(isset($_POST['verify_name'])){
            update_user_meta($user_id,'verify_name',$_POST['verify_name']);
        }

        if(isset($_POST['verify_idcard'])){
            update_user_meta($user_id,'verify_idcard',$_POST['verify_idcard']);
        }

        if(isset($_POST['photo'])){
            update_user_meta($user_id,'photo',$_POST['photo']);
        }

        if(ERPHPDOWN_IS_ACTIVE && $pagenow == 'user-new.php'){
            if(isset($_POST['userType']) && $_POST['userType']){
                $userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;
                addUserMoney($user_id,'0');
                if(userSetMemberSetData($userType,$user_id)){
                    addVipLogByAdmin(0, $userType, $user_id);
                }
                
            }
        }
    }
}

add_action('pre_user_query', 'mbt_search_user_meta_mobile');
function mbt_search_user_meta_mobile($user_query) {
    $search_term = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
    if (!empty($search_term)) {
        $user_query->query_where .= " OR mobile like '%{$search_term}%'";
    }
}
