<?php
/** define the field name of database **/
define('NEW_BAIDU_SITEMAP_OPTION','new_baidu_sitemapoption');

if (!function_exists('LCZ_GetHomePath')) {
function LCZ_GetHomePath() {
	
	$res="";
	//Check if we are in the admin area -> get_home_path() is avaiable
	if(function_exists("get_home_path")) {
		$res = get_home_path();
	} else {
		//get_home_path() is not available, but we can't include the admin
		//libraries because many plugins check for the "check_admin_referer"
		//function to detect if you are on an admin page. So we have to copy
		//the get_home_path function in our own...
		$home = get_option( 'home' );
		if ( $home != '' && $home != get_option( 'siteurl' ) ) {
			$home_path = parse_url( $home );
			$home_path = $home_path['path'];
			$root = str_replace( $_SERVER["PHP_SELF"], '', $_SERVER["SCRIPT_FILENAME"] );
			$home_path = trailingslashit( $root.$home_path );
		} else {
			$home_path = ABSPATH;
		}

		$res = $home_path;
	}
	return $res;
}
}

function LCZ_EscapeXML($string) {
	return str_replace ( array ( '&', '"', "'", '<', '>'), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;'), $string);
}

if (!function_exists('LCZ_IsFileWritable')) {
function LCZ_IsFileWritable($filename) {
	clearstatcache();
	//can we write?
	if(!is_writable($filename)) {
		//no we can't.
		if(!@chmod($filename, 0666)) {
			$pathtofilename = dirname($filename);
			//Lets check if parent directory is writable.
			if(!is_writable($pathtofilename)) {
				//it's not writeable too.
				if(!@chmod($pathtofilename, 0666)) {
					//darn couldn't fix up parrent directory this hosting is foobar.
					//Lets error because of the permissions problems.
					return false;
				}
			}
		}
	}
	//we can write, return 1/true/happy dance.
	return true;
}
}

/*
*
*Un-quotes quoted string\
*/
if (!function_exists('LCZ_stripslashes_deep')) {
	function LCZ_stripslashes_deep($value)
	{
		$value = is_array($value) ?
					array_map('LCZ_stripslashes_deep', $value) :
					stripslashes($value);

		return $value;
	}
}


/**
 * Returns the path to the directory where the plugin file is located
 * @since 3.0b5
 * @access private
 * @author Arne Brachhold
 * @return string The path to the plugin directory
 */
if (!function_exists('LCZ_GetPluginPath')) {
function LCZ_GetPluginPath() {
	$path = dirname(__FILE__);
	return trailingslashit(str_replace("\\","/",$path));
}
}


/**
 * Returns the URL to the directory where the plugin file is located
 * @since 3.0b5
 * @access private
 * @author Arne Brachhold
 * @return string The URL to the plugin directory
 */
if (!function_exists('LCZ_GetPluginUrl')) {
function LCZ_GetPluginUrl() {
	
	//Try to use WP API if possible, introduced in WP 2.6
	if (function_exists('plugins_url')) return trailingslashit(plugins_url(basename(dirname(__FILE__))));
	
	//Try to find manually... can't work if wp-content was renamed or is redirected
	$path = dirname(__FILE__);
	$path = str_replace("\\","/",$path);
	$path = trailingslashit(get_bloginfo('wpurl')) . trailingslashit(substr($path,strpos($path,"wp-content/")));
	return $path;
}
}


function LCZ_rebuild_message() {
				if(function_exists("wp_next_scheduled")) {
					$next = wp_next_scheduled('do_this_auto');
					if($next) {
						$diff = (time()-$next)*-1;
						if($diff <= 0) {
							$diffMsg = __('Your sitemap is being refreshed at the moment. Depending on your Post Count this might take some time!','baidu_sitemap');
						} else {
							$diffMsg = str_replace("%s",$diff,__('Your sitemap will be refreshed in %s seconds!','baidu_sitemap'));
						}

					}else{
							$diffMsg = __('Donot activate the Auto build the sitema options, you need build the XML file by yourself.','baidu_sitemap');
					}
					echo "<strong><p>$diffMsg</p></strong>";	
				}
}
function xml_file_exist() {
	$array_baidu_sitemap_options = get_baidu_sitemap_options();
	$lc_blog_url = home_url();
	$fileName = LCZ_GetHomePath();
	$filename = $fileName.$array_baidu_sitemap_options['lc_XML_FileName'].'.xml';;
	echo '<div class="tool-box">';
	echo '<h3 class="title">XML状态</h3>';
    if(file_exists($filename)){
		//$filctime=date("Y-m-d H:i:s",filectime("$filename")); 
		$filemtime=date("Y-m-d H:i:s",filemtime("$filename")); 
		//$fileatime=date("Y-m-d H:i:s",fileatime("$filename")); 
		echo "<p>";
		#_e('When you change Path of the XML file(Better not). please use 301 redirect to the new XML-file, or setting as 404 page.','baidu_sitemap');
		echo "</p>";
		echo '<p>检查XML文件存在：<a href="'.$lc_blog_url.'/'.$array_baidu_sitemap_options['lc_XML_FileName'].'.xml'.'" target="_blank">'.$lc_blog_url.'/'.$array_baidu_sitemap_options['lc_XML_FileName'].'.xml'.'</a></p>';
		#echo '<p>'; _e('Last updated: ','baidu_sitemap'); print $filemtime.'</p>';
		echo '';
	}else{
		_e('Sitemap文件不存在，请先生成XML','baidu_sitemap');
	}

	echo '</div>';
}

function LCZ_GetTimestampFromMySql($mysqlDateTime) {
	list($date, $hours) = explode(' ', $mysqlDateTime);
	list($year,$month,$day) = explode('-',$date);
	list($hour,$min,$sec) = explode(':',$hours);
	return mktime(intval($hour), intval($min), intval($sec), intval($month), intval($day), intval($year));
}

/** add a Menu,like "Baidu Sitemap" **/
function baidu_sitemap_menu() {
   /** Add a page to the options section of the website **/
    if (current_user_can('manage_options')){		
 		add_management_page('站点地图Sitemap', '站点地图Sitemap', 'activate_plugins', 'baidu_sitemap_optionpage', 'baidu_sitemap_optionpage');
 	}
}

/** custom message **/
function baidu_sitemap_topbarmessage($msg) {
	 echo '<div class="updated fade" id="message"><p>' . $msg . '</p></div>';
}

function get_baidu_sitemap_options(){
	$array_baidu_sitemap_options = array();
	$get_baidu_sitemap_options = get_option(NEW_BAIDU_SITEMAP_OPTION);
	if( $get_baidu_sitemap_options ){
		list( $array_baidu_sitemap_options['lc_is_Enabled_XML_Sitemap'], $array_baidu_sitemap_options['lc_is_update_sitemap_when_post'], $array_baidu_sitemap_options['lc_post_limit1000'], $array_baidu_sitemap_options['lc_post_select'], $array_baidu_sitemap_options['lc_page_select'], $array_baidu_sitemap_options['lc_category_select'], $array_baidu_sitemap_options['lc_tag_select'], $array_baidu_sitemap_options['lc_XML_FileName']) = explode("|",$get_baidu_sitemap_options);
	}else{
		if( !isset($array_baidu_sitemap_options['lc_XML_FileName']) ){ $array_baidu_sitemap_options['lc_XML_FileName'] = 'sitemap'; }
		if( !isset($array_baidu_sitemap_options['lc_is_Enabled_XML_Sitemap']) ){ $array_baidu_sitemap_options['lc_is_Enabled_XML_Sitemap'] = '1'; }
		if( !isset($array_baidu_sitemap_options['lc_is_update_sitemap_when_post']) ){ $array_baidu_sitemap_options['lc_is_update_sitemap_when_post'] = '1'; }
		if( !isset($array_baidu_sitemap_options['lc_post_limit1000']) ){ $array_baidu_sitemap_options['lc_post_limit1000'] = '0'; }
		if( !isset($array_baidu_sitemap_options['lc_post_select']) ){ $array_baidu_sitemap_options['lc_post_select'] = '1'; }
		if( !isset($array_baidu_sitemap_options['lc_page_select']) ){ $array_baidu_sitemap_options['lc_page_select'] = '0'; }
		if( !isset($array_baidu_sitemap_options['lc_category_select']) ){ $array_baidu_sitemap_options['lc_category_select'] = '0'; }
		if( !isset($array_baidu_sitemap_options['lc_tag_select']) ){ $array_baidu_sitemap_options['lc_tag_select'] = '0'; }
	}
	return $array_baidu_sitemap_options;
}

/** Baidu sitemap page **/
function baidu_sitemap_form() {
	$array_baidu_sitemap_options = get_baidu_sitemap_options();
	?>
		<div class="postbox-container" style="width:75%;">
		<div class="metabox-holder">
		<div class="meta-box-sortables">	
						
		<div class="tool-box">
			<h3 class="title">基础设置</h3>
			<a name="baidu_sitemap_options"></a><form name="baidu_sitemap_options" method="post" action="">
			<input type="hidden" name="action" value="build_options" />
			<table>
				<tr><td><?php _e('启用Sitemap','mobantu');?></td><td><input type="checkbox" name="lc_is_Enabled_XML_Sitemap" value="1" <?php if( $array_baidu_sitemap_options['lc_is_Enabled_XML_Sitemap'] ) { echo 'checked="checked"'; } ?> /></td></tr>
				<tr><td><?php _e('XML名称','mobantu');?></td><td><input type="radio" name="lc_XML_FileName" value="baidu_sitemap" <?php if( $array_baidu_sitemap_options['lc_XML_FileName']=='baidu_sitemap' ) { echo 'checked="checked"'; } ?> />baidu_sitemap</td></tr>
				<tr><td></td><td><input type="radio" name="lc_XML_FileName" value="sitemap" <?php if( $array_baidu_sitemap_options['lc_XML_FileName']=='sitemap' ) { echo 'checked="checked"'; } ?> />sitemap</td></tr>

				
				<tr><td><?php _e('自动更新','mobantu');?></td><td><input type="checkbox" name="lc_is_update_sitemap_when_post" value="1" <?php if( $array_baidu_sitemap_options['lc_is_update_sitemap_when_post'] ) { echo 'checked="checked"'; } ?> /></td></tr>
				<tr><td><?php _e('限制1000篇','mobantu');?></td><td><input type="checkbox" name="lc_post_limit1000" value="1" <?php if( $array_baidu_sitemap_options['lc_post_limit1000'] ) { echo 'checked="checked"'; } ?> /></td><td><a title="<?php _e('XML文件仅包含最新的1000篇文章，以减小服务器压力','mobantu');?>">[?]</a><td></tr>
				<tr><td>锭接包括：</td><td><input type="checkbox" name="lc_post_select" value="1" <?php if( $array_baidu_sitemap_options['lc_post_select'] ) { echo 'checked="checked"'; } ?> readonly />文章</td></tr>
				<tr><td></td><td><input type="checkbox" name="lc_page_select" value="1" <?php if( $array_baidu_sitemap_options['lc_page_select'] ) { echo 'checked="checked"'; } ?> />页面</td></tr>
				<tr><td></td><td><input type="checkbox" name="lc_category_select" value="1" <?php if( $array_baidu_sitemap_options['lc_category_select'] ) { echo 'checked="checked"'; } ?> />分类</td></tr>
				<tr><td></td><td><input type="checkbox" name="lc_tag_select" value="1" <?php if( $array_baidu_sitemap_options['lc_tag_select'] ) { echo 'checked="checked"'; } ?> />标签</td></tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" /></p>
			</form>
		</div>


		<div class="tool-box">
		<h3 class="title">生成XML</h3>
				<form name="baidu_sitemap_build" method="post" action="">
				<input type="hidden" name="action" value="build_xml" />
				<p class="submit"><input type="submit" class="button-primary" value="更新" /></p>
				</form>
		</div>


			<?php
			/** show the XML file if exist **/ 
			xml_file_exist();
			?>
		</div>
		</div>
		</div>
	<?php
}


/** Baidu sitemap page **/
function baidu_sitemap_optionpage()
{
      /** Perform any action **/
		if(isset($_POST["action"])) {
			if ($_POST["action"]=='build_options') {update_baidu_sitemap_options(); }
		    if ($_POST["action"]=='build_xml') { build_baidu_sitemap($mes=1);}
		}
		
		/** Definition **/
        echo '<div class="wrap">';
		echo '<h2>站点地图Sitemap</h2>';
		/** show the option Form **/ 
		baidu_sitemap_form();
		//test_form();
	
        
		//echo '</div>';
}

/** update the options **/
function update_baidu_sitemap_options() {
	if (isset($_POST['action']) && $_POST['action']=='build_options'){
		if(isset($_POST['lc_is_Enabled_XML_Sitemap'])){
			$lc_is_Enabled_XML_Sitemap = $_POST['lc_is_Enabled_XML_Sitemap'];
			if(!$lc_is_Enabled_XML_Sitemap){ $lc_is_Enabled_XML_Sitemap = 0; }
		}else{
			$lc_is_Enabled_XML_Sitemap = 0;
		}

		if(isset($_POST['lc_is_update_sitemap_when_post'])){
			$lc_is_update_sitemap_when_post = $_POST['lc_is_update_sitemap_when_post'];
			if(!$lc_is_update_sitemap_when_post){ $lc_is_update_sitemap_when_post = 0; }
		}else{
			$lc_is_update_sitemap_when_post = 0;
		}

		if(isset($_POST['lc_post_limit1000'])){
			$lc_post_limit1000 = $_POST['lc_post_limit1000'];
			if(!$lc_post_limit1000){ $lc_post_limit1000 = 0; }
		}else{
			$lc_post_limit1000 = 0;
		}

		if(isset($_POST['lc_post_select'])){
			$lc_post_select = $_POST['lc_post_select'];
			if(!$lc_post_select){ $lc_post_select = 1; }
		}else{
			$lc_post_select = 0;
		}

		if(isset($_POST['lc_page_select'])){
			$lc_page_select = $_POST['lc_page_select'];
			if(!$lc_page_select){ $lc_page_select = 0; }
		}else{
			$lc_page_select = 0;
		}

		if(isset($_POST['lc_category_select'])){
			$lc_category_select = $_POST['lc_category_select'];
			if(!$lc_category_select){ $lc_category_select = 0; }
		}else{
			$lc_category_select = 0;
		}

		if(isset($_POST['lc_tag_select'])){
			$lc_tag_select = $_POST['lc_tag_select'];
			if(!$lc_tag_select){ $lc_tag_select = 0; }
		}else{
			$lc_tag_select = 0;
		}

		if(isset($_POST['lc_XML_FileName'])){
			$lc_XML_FileName = $_POST['lc_XML_FileName'];
			if(!$lc_XML_FileName){ $lc_XML_FileName = 'sitemap'; }
		}

		$baidu_sitemap_options = implode('|',array($lc_is_Enabled_XML_Sitemap, $lc_is_update_sitemap_when_post, $lc_post_limit1000, $lc_post_select, $lc_page_select, $lc_category_select, $lc_tag_select, $lc_XML_FileName));
		update_option(NEW_BAIDU_SITEMAP_OPTION,$baidu_sitemap_options); 
        baidu_sitemap_topbarmessage(__('设置成功','mobantu'));
	}
}


/** build the XML file, sitemap.xml **/
function build_baidu_sitemap($mes=0) {
    global $wpdb, $posts;
	$array_baidu_sitemap_options = get_baidu_sitemap_options();
	if($array_baidu_sitemap_options['lc_post_limit1000']){ $lc_limit = '1000'; } else { $lc_limit = '10000'; }

    ## $lc_contents , $lc_limit = '1000'
	$sql_mini = "select ID, post_modified,post_date,post_type FROM $wpdb->posts
	        WHERE post_password = ''
			AND (post_type='post' or post_type='page')
			AND post_status = 'publish' 
			ORDER BY post_modified DESC
			LIMIT 0,$lc_limit
	       ";
	$recentposts_mini  = $wpdb->get_results($sql_mini);
	$xml_contents_page = '';
	$xml_contents_post = '';
	$xml_contents_cat  = '';
	$xml_contents_tag  = '';
	if($recentposts_mini){
		foreach ($recentposts_mini as $post){
			if( $post->post_type == 'page' ){
				if(!$array_baidu_sitemap_options['lc_page_select']){ continue; } ###跳过
				$loc = get_page_link($post->ID);
				$loc = LCZ_EscapeXML($loc);
				if(!$loc){ continue; }
				if($post->post_modified == '0000-00-00 00:00:00'){ $post_date = $post->post_date; } else { $post_date = $post->post_modified; } 
				$lastmod = date("Y-m-d\TH:i:s+00:00",LCZ_GetTimestampFromMySql($post_date));
				$changefreq = 'weekly';
				$priority = '0.3';
				$xml_contents_page .= "<url>";
				$xml_contents_page .= "<loc>$loc</loc>";
				$xml_contents_page .= "<lastmod>$lastmod</lastmod>";
				$xml_contents_page .= "<changefreq>$changefreq</changefreq>";
				$xml_contents_page .= "<priority>$priority</priority>";
				$xml_contents_page .= "</url>";
			}else{
				if(!$array_baidu_sitemap_options['lc_post_select']){ continue; } ###跳过
				$loc = get_permalink($post->ID);
				$loc = LCZ_EscapeXML($loc);
				if(!$loc){ continue; }
				if($post->post_modified == '0000-00-00 00:00:00'){ $post_date = $post->post_date; } else { $post_date = $post->post_modified; } ##$post->post_date_gmt
				$lastmod = date("Y-m-d\TH:i:s+00:00",LCZ_GetTimestampFromMySql($post_date));
				$changefreq = 'monthly';
				$priority = '0.6';
				$xml_contents_post .= "<url>";
				$xml_contents_post .= "<loc>$loc</loc>";
				$xml_contents_post .= "<lastmod>$lastmod</lastmod>";
				$xml_contents_post .= "<changefreq>$changefreq</changefreq>";
				$xml_contents_post .= "<priority>$priority</priority>";
				$xml_contents_post .= "</url>";
			}
		}
		## get_category_link
		if($array_baidu_sitemap_options['lc_category_select']){ 
			$category_ids = get_all_category_ids();
			if($category_ids){
				foreach($category_ids as $cat_id) {
					$loc = get_category_link($cat_id);
					$loc = LCZ_EscapeXML($loc);
					if(!$loc){ continue; }
					$lastmod = date("Y-m-d\TH:i:s+00:00",current_time('timestamp', '1'));
					$changefreq = 'Weekly';
					$priority = '0.3';
					$xml_contents_cat .= "<url>";
					$xml_contents_cat .= "<loc>$loc</loc>";
					$xml_contents_cat .= "<lastmod>$lastmod</lastmod>";
					$xml_contents_cat .= "<changefreq>$changefreq</changefreq>";
					$xml_contents_cat .= "<priority>$priority</priority>";
					$xml_contents_cat .= "</url>";
				}
			}
		}
		##

		###tag
		if($array_baidu_sitemap_options['lc_tag_select']){
			$all_the_tags = get_tags();
			if($all_the_tags){
				foreach($all_the_tags as $this_tag) {
					$tag_id = $this_tag->term_id;
					$loc = get_tag_link($tag_id);
					$loc = LCZ_EscapeXML($loc);
					if(!$loc){ continue; }
					$lastmod = date("Y-m-d\TH:i:s+00:00",current_time('timestamp', '1'));
					$changefreq = 'Weekly';
					$priority = '0.3';
					$xml_contents_tag .= "<url>";
					$xml_contents_tag .= "<loc>$loc</loc>";
					$xml_contents_tag .= "<lastmod>$lastmod</lastmod>";
					$xml_contents_tag .= "<changefreq>$changefreq</changefreq>";
					$xml_contents_tag .= "<priority>$priority</priority>";
					$xml_contents_tag .= "</url>";
				}
			}
		}
		###end tag
		$xml_contents = $xml_contents_post.$xml_contents_page.$xml_contents_cat.$xml_contents_tag;
	}


	## XML
	if($array_baidu_sitemap_options['lc_is_Enabled_XML_Sitemap']){
		build_baidu_sitemap_xml($xml_contents,$mes);
	}

}
function build_baidu_sitemap_xml($xml_contents,$mes){
	$array_baidu_sitemap_options = get_baidu_sitemap_options();
	$lc_blog_url = home_url();
	$blogtime = current_time('timestamp', '1');
	$lc_blog_time = date("Y-m-d\TH:i:s+00:00",$blogtime);

	$xml_begin = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	$xml_home = "<url><loc>$lc_blog_url</loc><lastmod>$lc_blog_time</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>";
	$xml_end = '</urlset>';
	if($xml_contents){
		$baidu_xml = $xml_begin.$xml_home.$xml_contents.$xml_end;

		/** save XML file as sitemap_baidu.xml **/
		$LCZ_GetHomePath = LCZ_GetHomePath();
		$filename = $LCZ_GetHomePath.$array_baidu_sitemap_options['lc_XML_FileName'].'.xml';
		if( LCZ_IsFileWritable($LCZ_GetHomePath) || LCZ_IsFileWritable($filename) ){ 
			file_put_contents("$filename","$baidu_xml"); 
			@chmod($filename, 0777);
			/** Messages  **/
			if($mes){
			baidu_sitemap_topbarmessage(__('Congratulate, Build the XML file success','baidu_sitemap'));
			}
		}else{ 
			/** Messages  **/
			if(!$mes){
			baidu_sitemap_topbarmessage(__('Directory is not writable. please chmod your directory to 777.','baidu_sitemap'));
			}
		}
	}
}

## Auto
add_action( 'wp', 'baidu_sitemap_is_auto_daily' );
function baidu_sitemap_is_auto_daily() {
	if ( ! wp_next_scheduled( 'do_baidu_sitemap_auto_daily' ) ) {
		wp_schedule_event( time(), 'daily', 'do_baidu_sitemap_auto_daily');
	}
}
add_action('do_baidu_sitemap_auto_daily','build_baidu_sitemap');


function baidu_sitemap_by_post($post_ID) {
	$get_baidu_sitemap_options = get_option(NEW_BAIDU_SITEMAP_OPTION);
	if(isset($get_baidu_sitemap_options['lc_is_update_sitemap_when_post'])){
		if($get_baidu_sitemap_options['lc_is_update_sitemap_when_post'] == '1'){
			  build_baidu_sitemap();
		}
	}
	return $post_ID;
}

add_action('publish_post', 'baidu_sitemap_by_post');
add_action('save_post', 'baidu_sitemap_by_post');
add_action('edit_post', 'baidu_sitemap_by_post');
add_action('delete_post', 'baidu_sitemap_by_post');


/** Tie the module into Wordpress **/
add_action('admin_menu','baidu_sitemap_menu');
#add_action('init','baidu_sitemap_is_auto_daily',1001,0);

