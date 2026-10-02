<?php
class Options_Framework_Admin {

    protected $options_screen = null;

    public function init() {
    	if(function_exists('MBThemes_modown_update')){
    		MBThemes_modown_update();
    	}
    	$options = & Options_Framework::_optionsframework_options();
		$token = get_option('MBT_'.optionsframework_option_name().'_token');
		if($token && $options){
			add_action( 'admin_menu', array( $this, 'add_custom_options_page' ) );
		}else{
			add_action( 'admin_menu', array( $this, 'add_custom_active_page' ) );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'optionsframework_admin_bar' ) );
    }

    function settings_init() {
		$options_framework = new Options_Framework;
	    $name = $options_framework->get_option_name();
		register_setting( 'optionsframework', $name, array ( $this, 'validate_options' ) );
		add_action( 'optionsframework_after_validate', array( $this, 'save_options_notice' ) );
    }

	static function menu_settings() {
		$menu = array(
            'mode' => 'submenu',
            'page_title' => optionsframework_option_name().'主题设置',
			'menu_title' => optionsframework_option_name().'主题设置',
			'active_title' => optionsframework_option_name().'主题激活',
			'capability' => 'edit_theme_options',
			'menu_slug' => 'options-framework',
            'parent_slug' => 'themes.php',
            'icon_url' => 'dashicons-admin-generic',
            'position' => '61'
		);
		return apply_filters( 'optionsframework_menu', $menu );
	}

	function add_custom_options_page() {

		$menu = $this->menu_settings();

		$this->options_screen = add_theme_page(
            $menu['page_title'],
            $menu['menu_title'],
            $menu['capability'],
            $menu['menu_slug'],
            array( $this, 'options_page' )
        );

	}

	function add_custom_active_page() {

		$menu = $this->menu_settings();

		$this->options_screen = add_theme_page(
            $menu['active_title'],
            $menu['menu_title'],
            $menu['capability'],
            $menu['menu_slug'],
            array( $this, 'active_page' )
        );

	}

	function enqueue_admin_styles( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

		wp_enqueue_style( 'optionsframework', OPTIONS_FRAMEWORK_DIRECTORY . 'css/optionsframework.css', array(),  THEME_VER );
		wp_enqueue_style( 'wp-color-picker' );
	}

	function enqueue_admin_scripts( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

		wp_enqueue_script(
			'mobantu-options-custom',
			OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js',
			array( 'jquery','wp-color-picker' ),
			THEME_VER
		);

		add_action( 'admin_head', array( $this, 'of_admin_head' ) );
	}

	function of_admin_head() {
		do_action( 'optionsframework_custom_scripts' );
	}

	function options_page() {
	 	$theme = 'Modown';
	?>
		<div id="optionsframework-wrap" class="wrap">
			<div class="optionsframework-side">
					<a href="http://www.mobantu.com" target="_blank"><img src="<?php bloginfo('template_url');?>/inc/images/mobantutheme.png" alt="主题作者：模板兔"></a>
				</div>
		<?php $menu = $this->menu_settings(); ?>
		<h2><?php echo esc_html( $menu['page_title'] ); ?><span style="font-size: 14px;">（当前状态：<font color="green">已激活</font>，当前版本 <?php echo 'v'.THEME_VER;?> <a href="javascript:;" id="mbt-theme-check" style="font-size: 12px;">检查更新</a>）<font style="font-size: 12px;float: right;color:#999;font-weight: 300;">（如需更换激活信息，可<a href="javascript:;" id="mbt-theme-restart" style="font-size: 12px;">重新激活</a>，平时别点！！！）</font></span></h2>
		<style>.nav-tab{padding:5px 7px;}</style>
	    <h2 class="nav-tab-wrapper">
	        <?php echo Options_Framework_Interface::optionsframework_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'options-framework' ); ?>

	    <div id="optionsframework-metabox" class="metabox-holder">
		    <div id="optionsframework" class="postbox">
				<form action="options.php" method="post">
					<div id="optionsframework-submit" style="border-top:none">
						<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( '保存设置', 'theme-textdomain' ); ?>" />

						<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( '重置设置', 'theme-textdomain' ); ?>" onclick="return confirm( '<?php print esc_js( __( '确定重置主题设置？重置后耶稣都恢复不了哦~', 'theme-textdomain' ) ); ?>' );" style="float:right;"/>
						<div class="clear"></div>
					</div>
					<?php settings_fields( 'optionsframework' ); ?>
					<script>window._MBT = { theme: '<?php echo optionsframework_option_name(); ?>', home: '<?php echo get_option('siteurl');?>', uri: '<?php bloginfo('template_url') ?>'}</script>
					<?php Options_Framework_Interface::optionsframework_fields(); ?>
					<div id="optionsframework-submit">
						<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( '保存设置', 'theme-textdomain' ); ?>" />

						<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( '重置设置', 'theme-textdomain' ); ?>" onclick="return confirm( '<?php print esc_js( __( '确定重置主题设置？重置后耶稣都恢复不了哦~', 'theme-textdomain' ) ); ?>' );" style="float:right;"/>

						<br><br><p>说明：有任何问题请到<a href="https://www.mobantu.com" target="_blank">模板兔</a>提交工单~</p>
						<div class="clear"></div>
					</div>
				</form>
			</div>
		</div>
		<?php do_action( 'optionsframework_after' ); ?>
		</div>

	<?php
	}


	function active_page() { ?>
		<div id="optionsframework-wrap" class="wrap">
			<div class="optionsframework-side">
					<a href="http://www.mobantu.com" target="_blank"><img src="<?php bloginfo('template_url');?>/inc/images/mobantutheme.png" alt="主题作者：模板兔"></a>
				</div>
		<?php $menu = $this->menu_settings(); ?>
		<h2><?php echo esc_html( $menu['active_title'] ); ?></h2>

	    <h2 class="nav-tab-wrapper">
	        <?php echo Options_Framework_Interface::optionsframework_active_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'options-framework' ); ?>
	    <script>window._MBT = { theme: '<?php echo optionsframework_option_name(); ?>', home: '<?php echo get_option('siteurl');?>', uri: '<?php bloginfo('template_url') ?>'}</script>
	    <div id="optionsframework-metabox" class="metabox-holder">
		    <div id="optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'optionsframework' ); ?>
				<?php Options_Framework_Interface::optionsframework_active_fields(); ?>
				<div id="optionsframework-submit">
					<input type="button" id="mbt-theme-active" class="button-primary" value="<?php esc_attr_e( '激活主题', 'theme-textdomain' ); ?>" />
					<p>激活说明：</p>
					<ul>
						<li style="color:#ff5f33">1、激活前请先关闭防火墙、CDN、缓存</li>
						<li>2、请勿将主题文件、激活码分享泄漏给他人，否则会导致激活异常，我们将永久禁封您的账号且不通知！</li>
						<li>3、模板兔官方网站：www.mobantu.com</li>
					</ul>
					<div class="clear"></div>
				</div>
				</form>
			</div>
		</div>
		<?php do_action( 'optionsframework_after' ); ?>
		</div>

	<?php
	}


	function validate_options( $input ) {

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'options-framework', 'restore_defaults', __( '所有设置已被重置', 'theme-textdomain' ), 'updated fade' );
			return $this->get_default_values();
		}

		$clean = array();
		$options = & Options_Framework::_optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		do_action( 'optionsframework_after_validate', $clean );

		return $clean;
	}


	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', __( '设置已保存', 'theme-textdomain' ), 'updated fade' );
	}

	function get_default_values() {
		$output = array();
		$config = & Options_Framework::_optionsframework_options();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$output[$option['id']] = apply_filters( 'of_sanitize_' . $option['type'], $option['std'], $option );
			}
		}
		return $output;
	}


	function optionsframework_admin_bar() {

		$menu = $this->menu_settings();

		global $wp_admin_bar;

		if ( 'menu' == $menu['mode'] ) {
			$href = admin_url( 'admin.php?page=' . $menu['menu_slug'] );
		} else {
			$href = admin_url( 'themes.php?page=' . $menu['menu_slug'] );
		}

		$args = array(
			'parent' => 'appearance',
			'id' => 'of_theme_options',
			'title' => $menu['menu_title'],
			'href' => $href
		);

		$wp_admin_bar->add_menu( apply_filters( 'optionsframework_admin_bar', $args ) );
	}

}