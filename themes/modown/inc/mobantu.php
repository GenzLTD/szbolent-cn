<?php
function mbt_send_request($body, $method='POST'){
  $url = base64_decode('aHR0cDovL3Y1ODdtYjYubW9iYW50dS5jbi9hdXRoL21vZG93bi12NS5waHA=');
  $result = wp_remote_request($url, array('method' => $method, 'body'=>$body));
  if(is_array($result)){
    return $result['body'];
  }
}

if( is_admin() ){
  define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
  require_once THEME_DIR . '/inc/options-framework.php';
  require_once THEME_DIR . '/inc/options.php';
  if(file_exists(STYLESHEET_DIR.'/inc/options-custom.php')){
    require_once STYLESHEET_DIR . '/inc/options-custom.php';
  }else{
    require_once THEME_DIR . '/inc/options-custom.php';
  }
}

require_once THEME_DIR . '/inc/init.php';
require_once THEME_DIR . '/inc/base.php';

function MBThemes_modown_active(){
  $otheme = 'Modown';
  $token = get_option('MBT_'.$otheme.'_token');
  $domain = parse_url(admin_url(),PHP_URL_HOST);
  $ops = base64_decode(get_option('MBT_'.$otheme.'_options'));
  $ops = str_replace( 'c'.md5($token.$domain.'MO0H3BTN2K3TU'.strtolower($otheme)).md5($token.'adc'.$domain).'2f'.md5('d9x0l').'i', '', $ops );
  $ops = base64_decode( str_replace( 'c'.md5($token).'a', '', $ops ) );
  $ops = str_replace($domain,'',$ops);
  $ops = json_decode($ops,TRUE);
  if($ops){
    update_option('plugins_tuge_md', md5($token.'PLUGINS-TUGE-ME'.$domain));
    return '1';
  }
  return '0';
}

function MBThemes_modown_update(){
  $theme = 'Modown';
  delete_option('MBT_'.$theme.'_key');
  $old_version = get_option('MBT_Modown_version','0');
  if(THEME_VER > $old_version){
    update_option('MBT_'.$theme.'_version', THEME_VER );
    $username = get_option('MBT_'.$theme.'_user');
    $token = get_option('MBT_'.$theme.'_token');
    $domain = parse_url(admin_url(),PHP_URL_HOST);
    $body = array('username'=>$username, 'token'=>$token, 'theme'=>$theme, 'domain'=>$domain, 'key'=>md5($username.$token.$domain), 'action'=>'update');
    $result_body = json_decode(mbt_send_request($body));
    if( isset($result_body->status) && $result_body->status=='1' ){
      update_option('MBT_'.$theme.'_options',$result_body->ops);
    }elseif( isset($result_body->status) && $result_body->status=='404' ){
      delete_option('MBT_'.$theme.'_key');
      delete_option('MBT_'.$theme.'_options');
      delete_option($theme);
    }
  }
}