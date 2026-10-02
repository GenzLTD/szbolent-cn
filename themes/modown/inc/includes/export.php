<?php
require( dirname(__FILE__).'/../../../../../wp-load.php' );
if(isset($_POST['export']) && current_user_can('administrator')){
	if (check_admin_referer('ie-export')) {
		$date = date("Y-m-d");
		$need_options = array();
		if( isset($_POST["content"]) ){
			if( $_POST["content"] == "widget" || $_POST["content"] == "all" ){
				$widgets = wp_get_sidebars_widgets();
				$need_options["sidebars_widgets"] = $widgets;
				$widgets_name = array();
				foreach($widgets as $key => $value){
					if( is_array($value) ){
						foreach($value as $k => $v){
							$v = preg_replace('/\-\d+/is', "", $v);
							$widgets_name[] = "widget_".$v;
						}
					}
				}
				$widgets_name = array_unique($widgets_name);
				foreach($widgets_name as $key){
					$need_options[$key] = get_option($key);
				}
			}
			
			if( $_POST["content"] == "theme" || $_POST["content"] == "all" ){
				$options = get_option('Modown');
				$need_options["Modown"] = $options;
			}
			
			$json_file = base64_encode( json_encode($need_options) );
				
			ob_clean();
			Header("Content-type:application/octet-stream");
			Header("Accept-Ranges:bytes");
			header("Content-Disposition:attachment;filename=modown.{$date}.json");
			header("Expires:0");
			header("Cache-Control:must-revalidate,post-check=0,pre-check=0 ");
			header("Pragma:public");
			echo $json_file;
			exit;
		}
	}
}