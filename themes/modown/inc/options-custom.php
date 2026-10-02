<?php
//如果二次开发需要增加新的主题设置选项，可以修改此函数，也可将此文件放到子主题里面覆盖修改，切勿修改函数名。获取值的方法就是 _MBT('child_checkbox_demo')
function modown_custom_options(){
	$options = array();
	/*
	//Tab1示例
	$options[] = array(
		'name' => 'Tab1',
		'type' => 'heading');

	$options[] = array(
		'name' => 'checkbox示例',
		'id' => 'child_checkbox_demo',
		'type' => "checkbox",
		'std' => false,
		'desc' => '开启');

	$options[] = array(
		'name' => 'text示例',
		'id' => 'child_text_demo',
		'type' => "text",
		'desc' => '这是简介');

	$options[] = array(
		'name' => 'textarea示例',
		'id' => 'child_textarea_demo',
		'type' => "textarea",
		'desc' => '');

	$options[] = array(
		'name' => 'editor示例',
		'id' => 'child_editor_demo',
		'type' => "editor",
		'desc' => '');

	$options[] = array(
		'name' => 'upload示例',
		'id' => 'child_upload_demo',
		'type' => "upload",
		'desc' => '');

	$options[] = array(
		'name' => 'select示例',
		'id' => 'child_select_demo',
		'type' => "select",
		'options' => array(
			'0' => '值0',
			'1' => '值1'
		),
		'desc' => '');

	$options[] = array(
		'name' => 'radio示例',
		'id' => 'child_radio_demo',
		'type' => "radio",
		'options' => array(
			'0' => '值0',
			'1' => '值1'
		),
		'std' => 0,
		'desc' => '');

	$options[] = array(
		'name' => 'color示例',
		'id' => 'child_color_demo',
		'type' => "color",
		'std' => '#ff5f33',
		'desc' => '');

	//Tab2示例
	$options[] = array(
		'name' => 'Tab2',
		'type' => 'heading');

	$options[] = array(
		'name' => 'checkbox2示例',
		'id' => 'child_checkbox2_demo',
		'type' => "checkbox",
		'std' => false,
		'desc' => '开启');
	*/

	return $options;
}