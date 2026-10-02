<?php 
/*
	template name: 空页面
	description: 适合用可视化插件搭建整个页面
*/
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
  <meta http-equiv="Cache-Control" content="no-siteapp">
  <title><?php wp_title( _MBT('delimiter','-'), true, 'right' ); ?></title>
  <?php if(!_MBT('seo')){MBThemes_keywords();MBThemes_description();}?>
  <link rel="shortcut icon" href="<?php echo _MBT('favicon')?>">
  <?php wp_head();?>
  <style>body{margin-top: 0;background: #fff;}</style>
</head>
<body <?php body_class(); ?>>
	<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<?php endwhile;  ?>
	<?php wp_footer();?>
	<div class="analysis"><?php echo _MBT('analysis');?></div>
</body>
</html>