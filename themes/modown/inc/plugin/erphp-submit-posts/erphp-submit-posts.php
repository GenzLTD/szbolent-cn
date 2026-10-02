<?php
add_action('admin_menu', 'modown_esp_menu');
function modown_esp_menu() {
	add_management_page('批量发布', '批量发布', 'activate_plugins', 'modown_esp_setting_page', 'modown_esp_setting_page','');
}

function modown_esp_setting_page(){
	global $wpdb, $current_user;
	if(isset($_POST['action']) && $_POST['action'] == '1'){
		
		if(is_uploaded_file($_FILES['esp_excel_file']['tmp_name'])){
			$file_name = $_FILES['esp_excel_file']['tmp_name'];
			$handle = fopen($file_name, 'r');
			if($handle === FALSE) die("打开文件资源失败");

			$csv_arr = array();
			while(($data = fgetcsv($handle, 1000, ",")) !== FALSE){
				$num = count($data);
		        for ($c=0; $c < $num; $c++) {
		            $arr[]=$data[$c]; 
		        }
		        $csv_arr[]=$arr;
		        unset($arr);
			}
			fclose($handle);

			//var_dump($csv_arr);
			foreach($csv_arr as $csv){
				$submit = array(
					'post_title' => $csv[0],
					'post_author' => $current_user->ID,
					'post_content' => $csv[1], //nl2br('<br />'.$csv[1])
					'post_category' => $csv[3]?array($csv[3]):array($_POST['cat']),
					'post_status' => 'publish' //draft  publish
				);
				if($csv[4]){
					$submit['tags_input'] = explode(',', $csv[4]);
				}
				$status = wp_insert_post( $submit );
				if ($status != 0) {
					if($csv[2]) update_post_meta( $status, '_thumbnail_ext_url', $csv[2] );
					update_post_meta($status, 'erphp_down', $_POST['erphp_down'] );
					if($_POST['erphp_down'] == '1'){
						update_post_meta($status,'start_down',"yes");
					}elseif($_POST['erphp_down'] == '2'){
						update_post_meta($status,'start_see',"yes");
					}elseif($_POST['erphp_down'] == '3'){
						update_post_meta($status,'start_see2',"yes");
					}
					update_post_meta($status,'member_down',$_POST['member_down']);
		        	update_post_meta($status,'down_price',$_POST['down_price']);
		        	$down_url = $csv[5];
		        	if(strpos($down_url, ',') !== false){
		        		$down_url = str_replace(',', "\r\n", $down_url);
		        	}
		        	update_post_meta($status,'down_url',$down_url);
				}
			}

			echo '<div id="message" class="updated notice is-dismissible"><p>发布完毕。</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button></div>';
		}else{
			echo '<div id="message" class="error notice is-dismissible"><p>请先上传文件。</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button></div>';
		}

	}
	
?>
<div class="wrap mobantu">
	<h2>批量发布文章</h2>
	<p>仅实现简单的批量发布，如需增加其他信息发布，可联系模板兔二次开发。</p>
	<form method="post" action="" enctype="multipart/form-data">
		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row"><label>.csv文件</label></th>
					<td>
						<ul class="esi-ul">
							<li class="esi-li">
								<input name="esp_excel_file" type="file" id="esp_excel_file" class="regular-text short-text" accept=".csv"/>
								<p>.csv文件里的格式如下图，一共6列，分别是标题、正文、特色图片URL、单分类ID、标签（多个用英文逗号隔开）、下载地址，<a href="<?php echo THEME_URI.'/inc/plugin/erphp-submit-posts/demo.csv'?>" target="_blank">查看demo.csv</a><br><img src="<?php echo THEME_URI.'/inc/plugin/erphp-submit-posts/demo.png'?>" style="width:100%;max-width: 400px;height: auto;"><br>请勿一次发布太多文章，否则可能卡死，具体请自行测试。</p>
							</li>
						</ul>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label>文章设置</label></th>
					<td>
						<ul class="esi-ul">
							<li class="esi-li">
								<code>分类</code>
								<?php wp_dropdown_categories('show_option_all=选择分类&orderby=name&hierarchical=1&selected=-1&depth=0&hide_empty=0');?>
								<p>若.csv文件里的分类ID留空则需要此处选择分类</p>
							</li>
						</ul>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label>Erphpdown设置</label></th>
					<td>
						<ul class="esi-ul">
							<li class="esi-li">
								<code>收费</code>
								<input type="radio" name="erphp_down" id="erphp_down4" value="4" checked><label for="erphp_down4">不启用</label>&nbsp;
								<input type="radio" name="erphp_down" id="erphp_down1" value="1"><label for="erphp_down1">下载</label>&nbsp;
								<input type="radio" name="erphp_down" id="erphp_down2" value="2"><label for="erphp_down2">查看全部</label>&nbsp;
								<input type="radio" name="erphp_down" id="erphp_down3" value="3"><label for="erphp_down3">查看部分</label>&nbsp;
								<input type="radio" name="erphp_down" id="erphp_down6" value="6"><label for="erphp_down6">发卡</label>&nbsp;
								<input type="radio" name="erphp_down" id="erphp_down7" value="7"><label for="erphp_down7">实物</label>
							</li>
							<li class="esi-li">
								<code>会员</code>
								<span><input type="radio" name="member_down" id="member_down1" value="1" class="" checked><label for="member_down1">无</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down2" value="3" class=" vip"><label for="member_down2">免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down3" value="16" class=" vip"><label for="member_down3">包季免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down4" value="6" class=" vip"><label for="member_down4">包年免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down5" value="7" class=" vip"><label for="member_down5">终身免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down6" value="4" class="login vip"><label for="member_down6">专享</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down7" value="15" class="login vip"><label for="member_down7">包季专享</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down8" value="8" class="login vip"><label for="member_down8">包年专享</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down9" value="9" class="login vip"><label for="member_down9">终身专享</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down10" value="2" class="login vip vip2"><label for="member_down10">5折</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down11" value="5" class="login vip vip2"><label for="member_down11">8折</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down12" value="23" class="login vip"><label for="member_down12">5折|包年免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down13" value="24" class="login vip"><label for="member_down13">8折|包年免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down14" value="13" class="login vip"><label for="member_down14">5折|终身免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down15" value="14" class="login vip"><label for="member_down15">8折|终身免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down16" value="21" class="login vip vip2"><label for="member_down16">自定义折扣</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down17" value="20" class="login vip"><label for="member_down17">自定义折扣|终身免费</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down18" value="10" class="login vip vip3"><label for="member_down18">专购</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down19" value="17" class="login vip vip3"><label for="member_down19">包季专购</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down20" value="18" class="login vip vip3"><label for="member_down20">包年专购</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down21" value="19" class="login vip vip3"><label for="member_down21">终身专购</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down22" value="11" class="login vip"><label for="member_down22">专购|包年5折</label>&nbsp;&nbsp;</span><span><input type="radio" name="member_down" id="member_down23" value="12" class="login vip"><label for="member_down23">专购|包年8折</label>&nbsp;&nbsp;</span>
							</li>
							<li class="esi-li">
								<code>价格</code>
								<input name="down_price" type="text" id="down_price" class="regular-text short-text" />
							</li>
						</ul>
					</td>
				</tr>

				<tr>
					<td>
						<div class="esp-submit-form">
							<input type="submit" class="button-primary" value="批量发布"/>
							<input type="hidden" name="action" value="1">
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
	</form>
	<style>
		/*Powered by mobantu.com*/
		.esp-li{position: relative;padding-left: 120px}
		.esp-li code{position: absolute;left: 0;top: 2px;}
		.short-text{width: 150px;}
	</style>		
</div>
<?php
}