<?php 
/*
	template name: 所有作者
	description: template for mobantu.com modown theme 
*/
get_header();?>
<div class="banner-page" <?php if(_MBT('banner_page_img')){?> style="background-image: url(<?php echo _MBT('banner_page_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container">
		<div class="content-wrap">
	    	<div class="content authors clearfix">
				<?php
					$authors = get_users( 
						array( 
							'role__in' => array( 'author' ) 
						) 
					);
					if($authors){
						$perpage = 16;
						$pagess = ceil(count($authors) / $perpage);
						if (!get_query_var('paged')) {
							$paged = 1;
						}else{
							$paged = $wpdb->escape(get_query_var('paged'));
						}
						$offset = $perpage*($paged-1);
						$authors = get_users( 
							array( 
								'role__in' => array( 'author' ) ,
								'number' => $perpage,
								'offset' => $offset,
								'paged' => $paged,
								'orderby' => 'post_count', 
								'order' => 'DESC'
							) 
						);
						if($authors){
							foreach($authors as $author){
				?>
				<div class="author-item">
					<a href="<?php echo get_author_posts_url($author->ID);?>" target="_blank"><?php echo get_avatar($author->ID,70);?></a>
					<h4 class="name"><?php echo $author->display_name;?></h4>
					<div class="posts">
						<?php 
		    				$args = array('caller_get_posts' => 1, 'showposts' => '5', 'author' => $author->ID, 'post_type' => 'post', 'post_status' => 'publish');
		    				query_posts($args);
		    				while (have_posts()) {
		    					the_post();
		    				?>
		    		  		<a class="title" href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a>
		    		   	<?php
		    				} wp_reset_query();
		    			?>
					</div>
				</div>
				<?php
							}
						}
					}
				?>
	    	</div>
	    	<?php MBThemes_custom_paging($paged,$pagess);?>
	    </div>
	</div>
</div>
<?php get_footer();?>