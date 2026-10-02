<?php 
/*
	template name: 专题列表
	description: template for mobantu.com modown theme 
*/
get_header();
?>
<div class="banner-page" <?php if(_MBT('banner_page_img')){?> style="background-image: url(<?php echo _MBT('banner_page_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1>
	</div>
</div>
<div class="main">
    <?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="topic-list clearfix">
			<?php 
				$topics = get_terms('topic', array(
				    'hide_empty' => false,
				) );
				if ( ! empty( $topics ) && ! is_wp_error( $topics ) ){
					foreach ( $topics as $topic ) {
						$bgimage = get_term_meta($topic->term_id,'thumb_img',true);
			?>
			<div class="topic-item">
				<a href="<?php echo get_term_link($topic);?>" target="_blank"><img src="<?php echo $bgimage;?>" alt="<?php echo $topic->name;?>"></a>
				<div class="info">
					<h3><a href="<?php echo get_term_link($topic);?>" target="_blank"><?php echo $topic->name;?></a></h3>
					<p><?php echo $topic->description;?></p>
				</div>
			</div>
			<?php 	}
				}
			?>
		</div>
	</div>
</div>
<?php get_footer();?>