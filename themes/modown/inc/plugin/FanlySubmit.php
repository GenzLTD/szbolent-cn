<?php
add_action('init', 'FanlySubmit', 100);
function FanlySubmit() { // 自定义文章类型
	$Fanly = get_option('FanlySubmit');//获取选项
	if ( is_array($Fanly['Types']) ) {
		foreach($Fanly['Types'] as $type) {
			//add_action('save_'.$type, 'fanly_submit', 10, 2);
			add_filter('manage_'.$type.'_posts_columns', 'fanly_submit_add_post_columns');
			add_action('manage_'.$type.'s_custom_column', 'fanly_submit_render_post_columns', 10, 2);
		}
	} 
}

// Fanly Submit
add_action('post_updated', 'fanly_submit', 10, 3);
function fanly_submit($post_ID, $post_after, $post_before) {
	if((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_ID)) return;
    if (wp_is_post_revision($post_ID) || wp_is_post_autosave($post_ID)) return;

	if( isset($_POST['Fanly_Submit_CHECK']) || ($post_after->post_status=='publish' && in_array(get_post_meta($post_ID,'Fanly_Submit',true), array('dailysubmit','linksubmit'))) ){
		//获取选项
		$Fanly = get_option('FanlySubmit');
		$P_Fanly_Submit = isset($_POST['Fanly_Submit']) ? $_POST['Fanly_Submit'] : '';
		$Fanly_Submit = get_post_meta($post_ID,'Fanly_Submit',true);
		//$future = $post->post_status=='future' ? true :false;//定时文章
		//判断是否设置新增
		if( $Fanly_Submit!='-1' && $Fanly_Submit!='OK' ){
			if($P_Fanly_Submit=='dailysubmit'){update_post_meta($post_ID, 'Fanly_Submit', 'dailysubmit');}
			//else{update_post_meta($post_ID, 'Fanly_Submit', 0);}
			//if($future){update_post_meta($post_ID, 'Fanly_Submit_Future', 1);}
		}
		
		//判断文章状态与推送状态 返回/继续
		if( $post_after->post_status != 'publish' || $Fanly_Submit=='OK' )return;
		//执行
		if( ($P_Fanly_Submit && $post_after->post_status=='publish') || (in_array($Fanly_Submit,array('dailysubmit','linksubmit')) && !isset($_POST['Fanly_Submit_CHECK'])) ){
			$type = $P_Fanly_Submit=='dailysubmit' || ($Fanly_Submit=='dailysubmit'&&$P_Fanly_Submit=='') ? '&type=daily' : '';
			$api_url = 'http://data.zz.baidu.com/urls?site='.$Fanly['Site'].'&token='.$Fanly['Token'].$type;
			$link = $Fanly['Site']==home_url() ? get_permalink($post_ID) : str_replace(home_url(),$Fanly['Site'],get_permalink($post_ID));
			
			$cambrian_re = wp_remote_post($api_url, array(
				'headers'	=> array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
				'timeout'	=> 10,
				'sslverify'	=> false,
				'blocking'	=> true,
				'body'		=> $link
			));

			if ( is_wp_error( $cambrian_re ) ) {
				update_post_meta($post_ID, 'Fanly_Submit', '-1');
				$Fanly['msg'] = $cambrian_re->get_error_message();//错误信息
				update_option('FanlySubmit', $Fanly);//更新选项
			} else {
				//若同步成功，则给自定义栏目Fanly_Submit
				$res = json_decode($cambrian_re['body'], true);
				if($res['success']==1){
					if($type){//快速收录
						$Fanly['daily'] = $res['remain_daily'].'|'.date('Ymd');
					}else{
						$Fanly['link'] = $res['remain'].'|'.date('Ymd');
					}
					update_post_meta($post_ID, 'Fanly_Submit', 'OK');// OK 成功
				}elseif($res['remain']==0||$res['success']==0){//当天剩余的可推送url条数
					if($res['success_daily']==0){//当快速收录为0时，自动用普通收录重试
						$linksubmit_re = wp_remote_post('http://data.zz.baidu.com/urls?site='.$Fanly['Site'].'&token='.$Fanly['Token'], array('timeout'=>10,'sslverify'=>false,'blocking'=>true,'body'=>$body));
						$linksubmit_json = json_decode($linksubmit_re['body'], true);
						if($linksubmit_json['success']==1){
							update_post_meta($post_ID, 'Fanly_Submit', 'OK');// OK 成功
						}
					}
					$Fanly['link'] = $res['remain'].'|'.date('Ymd');
				}else{//未知错误 提交失败
					update_post_meta($post_ID, 'Fanly_Submit', '-1');
					$Fanly['err']=$res;//调试
				}
				update_option('FanlySubmit', $Fanly);//更新选项
			}
		}
	}
}

//获取当前文章类型
function fanly_submit_get_post_type() {
  global $post, $typenow, $current_screen;
  if ( $post && $post->post_type ) {return $post->post_type;
  } elseif ( $typenow ) {return $typenow;
  } elseif ( $current_screen && $current_screen->post_type ) {return $current_screen->post_type;
  } elseif ( isset( $_REQUEST['post_type'] ) ) {return sanitize_key( $_REQUEST['post_type'] );
  } elseif ( isset( $_REQUEST['post'] ) ) {return get_post_type( $_REQUEST['post'] );
  } return 'post';
}

//同步
add_action( 'admin_menu', 'fanly_submit_create' );
function fanly_submit_create(){
	$Fanly = get_option('FanlySubmit');//获取选项
	if(is_array($Fanly['Types']) && in_array(fanly_submit_get_post_type(),$Fanly['Types'])){
		add_action( 'post_submitbox_misc_actions', 'fanly_submit_to_publish_metabox' );//同步选项
	}
}

add_action( 'add_meta_boxes', 'fanly_submit_register_meta_boxes' );
function fanly_submit_register_meta_boxes() {
	global $post_id;
	if(	class_exists( 'Classic_Editor' ) )return;
	$Fanly = get_option('FanlySubmit');//获取选项
	if(!is_array($Fanly['Types']))return;
	add_meta_box(
		'fanly-submit-meta-box',
		'Fanly Submit',
		'fanly_submit_to_publish_metabox',
		$Fanly['Types'],
		'normal',
		'high',
		array(
			'__block_editor_compatible_meta_box' => true,
		)
	);
}

//同步选项
function fanly_submit_to_publish_metabox() {
	$Fanly = get_option('FanlySubmit');//获取选项
	if($Fanly['Token']=='')return;
    global $post,$post_ID;
	$fanly_submit	= get_post_meta($post_ID,'Fanly_Submit',true);
	$remain_d		= explode('|',@$Fanly['daily']);//快速收录
	$remain_l		= explode('|',@$Fanly['link']);//普通收录
	$checked		= ($Fanly['Default']=='true' && $fanly_submit=='') || $fanly_submit=='dailysubmit' || $fanly_submit=='linksubmit' ? 'checked="checked"' : '';
	
	if($fanly_submit=='OK'){//成功
		$z = @$remain_d[1]==date('Ymd') ? '['.$remain_d[0].']' : '';
		$input = '
			<input id="Fanly_Submit" type="checkbox" checked="checked" disabled>
			<label for="Fanly_Submit" class="selectit">成功'.$z.'</label>
		';
	}elseif( strtotime(date($post->post_date))+24*60*60 <= time() && $post->post_status=='publish'){//周级收录/文章发布已经超过二十四小时
		if($remain_l[0]==0 && $remain_l[1]==date('Ymd')){
			$input = '<label for="Fanly_Submit" class="selectit">上限<a style="font-weight:bold;color:#0066FF;text-decoration:none;" href="javascript:;" title="超过提交配额数量">?</a></label>';
		}else{
			$repost_text = $fanly_submit=='-1' ? '失败重试' : '普通';
			$input = '
			<label class="selectit"><input name="Fanly_Submit" type="checkbox" value="linksubmit" '.$checked.'>'.$repost_text.'</label>';
		}
	}else{
		if(@$remain_d[0]==0 && @$remain_d[1]==date('Ymd')){//快速收录
			$input = '
			<label class="selectit"><input name="Fanly_Submit" type="checkbox" value="dailysubmit" '.$checked.'>上限</label>';
		}else{
			$z = @$remain_d[1]==date('Ymd') ? '['.$remain_d[0].']' : '';
			$realtime_text = $fanly_submit=='-1' ? '重试' : '快速';
			$input = '
				<label class="selectit"><input name="Fanly_Submit" type="checkbox" value="dailysubmit" '.$checked.'>'.$realtime_text.$z.'</label>
			';
		}
		if(@$remain_l[0]==0 && @$remain_l[1]==date('Ymd')){//普通收录
			$input .= '
			
			<label class="selectit"><input name="Fanly_Submit" type="checkbox" value="linksubmit" '.$checked.'>上限</label>';
		}else{
			$batch_text = $fanly_submit=='-1' ? '重试' : '普通';
			$input .= '
			<label class="selectit"><input name="Fanly_Submit" type="checkbox" value="linksubmit">'.$batch_text.'</label>';
		}
	}
	echo '<div class="misc-pub-section misc-pub-post-status"><input name="Fanly_Submit_CHECK" type="hidden" value="true">百度收录：<span id="submit-span">'.$input.'</span></div>';
}

// 文章列表字段
function fanly_submit_add_post_columns($columns) {
    $columns['Fanly_Submit'] = '百度推送';
    return $columns;
}
function fanly_submit_render_post_columns($column_name, $id) {
    switch ($column_name) {
		case 'Fanly_Submit':
			echo get_post_meta( $id, 'Fanly_Submit', TRUE)=='OK' ? '<font color="green">已推送</font>' : (get_post_meta( $id, 'Fanly_Submit', TRUE)=='-1' ? '<font color="red">提交失败</font>' : '—'); //数据提交
			break;
    }
}

/*	Token测试
	$Appid
	$Token
*/
function fanly_submit_test($Site, $Token){
	$baidu_api_url = 'http://data.zz.baidu.com/urls?site='.$Site.'&token='.$Token;
	$response = wp_remote_post($baidu_api_url, array(
		'headers'	=> array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
		'timeout'	=> 10,
		'sslverify'	=> false,
		'blocking'	=> true,
		'body'		=> $Site
	));
	if(is_array($response) && array_key_exists('body', $response)){
		$data = json_decode( $response['body'], true );
		return $data;
	}else{return FALSE;}
}

//默认数据
add_action('admin_init', 'fanly_submit_default_options');
function fanly_submit_default_options(){
	$Fanly = get_option('FanlySubmit');//获取选项
	if( $Fanly == '' ){   
		$Fanly = array(//设置默认数据
			'Types'		=> '',
			'Token'	=> '',
			'Default'	=> '',
		);
		update_option('FanlySubmit', $Fanly);//更新选项   
	}
}

//插件设置菜单
add_action('admin_menu', 'fanly_submit_menu'); 
function fanly_submit_menu() {
	add_management_page('百度推送', '百度推送', 'activate_plugins', 'Fanly_Submit', 'fanly_submit_options');
}
//设置页面
function fanly_submit_options() {
	//保存数据
	if(isset($_POST['FanlySubmit'])){
		$error = '';
		$Site	= preg_replace('#/$#','', trim(@$_POST['Site']));
		$Token	= trim(@$_POST['Token']);
		if(substr($Site,0,7)=='http://' || substr($Site,0,8)=='https://'){}else{$Site='';}
		$xzh		= fanly_submit_test($Site,$Token);
		if( $xzh && @$xzh['success']!=1 ){
			$Token	= '';
			$error = @$xzh['message'];
		}
		$Fanly = array( 
			'Site'		=> $Site,
			'Token'		=> $Token,
			'Types'		=> @$_POST['Types'],
			'Default'	=> trim(@$_POST['Default']),
		);
		@update_option('FanlySubmit', $Fanly);//更新选项

		if($Site){
			$updated = $Token ? '设置成功！' : '失败：'.$error;
		}else{
			$updated = '站点域名必须以‘http://’或‘https://’开始';
		}
		echo '<div class="updated" id="message"><p>'.$updated.'</p></div>';
	}
	
	$Fanly		= get_option('FanlySubmit');//获取选项
	$Default	= $Fanly['Default']	!== '' ? 'checked="checked"' : '';
	echo '<div class="wrap">';
	echo '<h2>百度收录推送</h2>';
	echo '<form method="post">';
	echo '<table class="form-table">';
	echo '<tr valign="top">';
	echo '<th scope="row">站点域名</th>';
	$Site = @$Fanly['Site'] ? $Fanly['Site'] : home_url();
	echo '<td><input class="all-options" type="text" name="Site" value="'.$Site.'" /></td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row">普通收录 Token</th>';
	echo '<td><input class="all-options" type="text" name="Token" value="'.$Fanly['Token'].'" />
	<p>查看地址：https://ziyuan.baidu.com/linksubmit/index</p></td>';
	echo '</tr>';
	
	echo '<tr valign="top">';
	echo '<th scope="row">文章类型支持</th>';
	echo '<td>';
	$args = array('public' => true,);
	$post_types = get_post_types($args);
	foreach ( $post_types  as $post_type ) {
		if($post_type != 'attachment'){
			$postType = get_post_type_object($post_type);
			echo '<label><input type="checkbox" name="Types[]" value="'.$post_type.'" ';
			if(is_array($Fanly['Types'])) {if(in_array($post_type,$Fanly['Types'])) echo 'checked';}
			echo '>'.$postType->labels->singular_name.' &nbsp; &nbsp; </label>';
		}
	}
	echo '</td></tr>';
	
	echo '<tr valign="top">';
	echo '<th scope="row">是否默认提交数据</th>';
	echo '<td><label><input value="true" type="checkbox" name="Default" '.$Default.'> 勾选后默认都提交数据到百度，文章发布时可修改！</label></td>';
	echo '</tr>';
	
	echo '</table>';
	echo '<p class="submit">';
	echo '<input type="submit" name="FanlySubmit" id="submit" class="button button-primary" value="保存更改" />';
	echo '</p>';
	echo '</form>';
	echo '<p><strong>使用提示</strong>：<br>
	百度快速收录：<a target="_blank" href="https://ziyuan.baidu.com/dailysubmit">https://ziyuan.baidu.com/dailysubmit</a>
	</p>';
	echo '<p><strong>失败说明：</strong></p><table border="1" cellpadding="0" cellspacing="0"> <tbody><tr>  <td valign="top" width="75">  <p><span lang="EN-US">error<o:p></o:p></span></p>  </td>  <td valign="top" width="217">  <p><span lang="EN-US">提示<o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span>含义</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr> <tr>  <td rowspan="4" valign="top" width="75">  <p><span lang="EN-US">400<o:p></o:p></span></p>  </td>  <td valign="top" width="217">  <p><span lang="EN-US">site  error</span><span lang="EN-US"><o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span>站点未在站长平台验证</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr> <tr>  <td valign="top" width="217">  <p><span lang="EN-US">empty  content</span><span lang="EN-US"><o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span lang="EN-US">post</span><span>内容为空</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr> <tr>  <td valign="top" width="217">  <p><span lang="EN-US">only  2000 urls are allowed once</span><span lang="EN-US"><o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span>每次最多只能提交</span><span lang="EN-US">2000</span><span>条链接</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr> <tr>  <td valign="top" width="217">  <p><span lang="EN-US">over  quota</span><span lang="EN-US"><o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span>超过每日配额了，超配额后再提交都是无效的</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr> <tr>  <td valign="top" width="75">  <p><span lang="EN-US">401<o:p></o:p></span></p>  </td>  <td valign="top" width="217">  <p><span lang="EN-US">token  is not valid<o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span lang="EN-US">token</span><span>错误</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr> <tr>  <td valign="top" width="75">  <p><span lang="EN-US">404<o:p></o:p></span></p>  </td>  <td valign="top" width="217">  <p><span lang="EN-US">not  found<o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span>接口地址填写错误</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr> <tr>  <td valign="top" width="75">  <p><span lang="EN-US">500<o:p></o:p></span></p>  </td>  <td valign="top" width="217">  <p><span lang="EN-US">internal  error, please try later<o:p></o:p></span></p>  </td>  <td valign="top" width="246">  <p><span>服务器偶然异常，通常重试就会成功</span><span lang="EN-US"><o:p></o:p></span></p>  </td> </tr></tbody></table>';
	echo '</div>';
}