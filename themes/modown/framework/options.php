<?php
if( class_exists( 'CSF' ) ) {
	//文章属性
	$prefix_metabox = 'modown_post';
	CSF::createMetabox( $prefix_metabox, array(
	    'title'     => 'Tab选项卡',
	    'post_type' => 'post',
	    'data_type' => 'unserialize',
	    'theme'     => 'light'
	) );

	CSF::createSection( $prefix_metabox, array(
	    'fields' => array(
	    	array(
			  	'id'          => 'tabs_content_before',
			  	'type'        => 'wp_editor',
			  	'title'       => 'Tab前内容',
			  	'default'     => '',
			  	'desc'        => '显示在整个Tab上方的内容'
			),
	    	array(
			  	'id'          => 'tabs_content_title',
			  	'type'        => 'text',
			  	'title'       => '正文Tab标题',
			  	'default'     => __('详细介绍','mobantu'),
			  	'desc'        => '在下面添加Tab选项时，默认的正文部分的标题。正文就是Tab的第一个选项卡内容'
			),
	    	array(
			  	'id'          => 'tabs',
			  	'type'        => 'repeater',
			  	'title'       => 'Tab',
			  	'fields' => array(
				    array(
				      'id'    => 'title',
				      'type'  => 'text',
				      'title' => '标题'
				    ),
				    array(
				      'id'    => 'content',
				      'type'  => 'wp_editor',
				      'title' => '内容',
				      'media_buttons' => true,
		      		  'quicktags' => true,
		      		  'height'  => '150px'
				    )
				)
			)
	    )
	) );
}