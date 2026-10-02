<?php
require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
$action = $_POST['action'];
if($action == 'cover_share'){
    if(isset($_POST['id']) && $_POST['id'] && $post = get_post($_POST['id'])){
        setup_postdata( $post );
        if(_MBT("post_share_cover_default")){
            $share_head = _MBT("post_share_cover_img");
        }else{
            $img_url = MBThemes_thumbnail_share($post);
            $share_head = $img_url ? $img_url : _MBT("post_share_cover_img");
        }
        $share_logo = _MBT("post_share_cover_logo")?_MBT("post_share_cover_logo"):_MBT("logo");
        $excerpt = MBThemes_get_excerpt("200");

        $res = array(
            'head' => MBThemes_image_to_base64($share_head),
            'logo' => MBThemes_image_to_base64($share_logo),
            'title' => $post->post_title,
            'excerpt' => $excerpt,
            'timestamp' => get_post_time('U', true)
        );
        wp_reset_postdata();
        echo wp_json_encode($res);
    }
}elseif($action == 'cover_aff'){
    if(is_user_logged_in()){
        global $current_user;
        $share_head = _MBT("aff_card");
        $share_logo = MBThemes_get_avatar($current_user->ID);
        $excerpt = $current_user->description;

        $res = array(
            'head' => MBThemes_image_to_base64($share_head),
            'logo' => MBThemes_image_to_base64($share_logo),
            'title' => '',
            'excerpt' => $excerpt,
            'timestamp' => get_post_time('U', true)
        );
        echo wp_json_encode($res);
    }
}elseif($action == 'weixin_share'){
    $wx = array();
    $wx['timestamp'] = time();
    $wx['appId'] = _MBT('post_share_weixin_appid');
    $wx['noncestr'] = 'mobantu';
    $wx['jsapi_ticket'] = MBT_weixin_get_jsapi_ticket();
    $wx['url'] = urldecode($_POST['url']);
    $string = sprintf("jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s", $wx['jsapi_ticket'], $wx['noncestr'], $wx['timestamp'], $wx['url']);
    $wx['signature'] = sha1($string);
    $wx['desc'] = _MBT('post_share_weixin_desc');
    if(_MBT('post_share_weixin_img_fixed')){
        $img_url = _MBT('post_share_weixin_img');
    }else{
        if($_POST['ID']){
            $post = get_post($_POST['ID']);
            setup_postdata( $post );
            $img_url = MBThemes_thumbnail_share($post);
        }else{
            $img_url = _MBT('post_share_weixin_img');
        }
    }
    $wx['thumb'] = $img_url;
    echo json_encode($wx);
}

function MBT_weixin_get_token() {
    global $options;
    $AppID = _MBT('post_share_weixin_appid');
    $AppSecret = _MBT('post_share_weixin_appsecret');
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$AppID.'&secret='.$AppSecret;
    $result = wp_remote_request($url, array('method' => 'get'));
    if(is_array($result)){
        $res = $result['body'];
        $res = json_decode($res, true);
        if(isset($res['access_token'])){
            return $res['access_token'];
        }else{
            return '';
        }
    }
    return '';
}

function MBT_weixin_get_jsapi_ticket(){
    $ticket = '';
    if($old_ticket = get_option('wx_ticket')){
        if(time() - $old_ticket['timestamp']<6900 && $old_ticket['ticket']){
            $ticket = $old_ticket['ticket'];
        }
    }

    if($ticket=='') {
        $url = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi", MBT_weixin_get_token());
        $result = wp_remote_request($url, array('method' => 'get'));
        if (is_array($result)) {
            $res = $result['body'];
            $res = json_decode($res, true);
            if(isset($res['ticket'])){
                $tickets = array(
                    'ticket' => $res['ticket'],
                    'timestamp' => time()
                );
                update_option('wx_ticket', $tickets);

                $ticket = $res['ticket'];
            }
        }
    }
    return $ticket;
}