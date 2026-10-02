<?php 
/*
	template name: 标签存档
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
	    	<div class="content tagslist clearfix">
				<ul>
					<?php 
						$tags_per_page = 32;
				        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

						$all_tags = get_tags('orderby=count&order=DESC');
				        $total_tags = count($all_tags);
				        $total_pages = ceil($total_tags / $tags_per_page);
				        $offset = ($paged - 1) * $tags_per_page;

				        $tagslist = get_tags(array(
				            'number' => $tags_per_page,
				            'offset' => $offset,
				            'orderby' => 'count',
				            'order' => 'DESC'
				        ));

						foreach($tagslist as $tag) {
							echo '<li><a class="name" href="'.get_tag_link($tag).'">'. $tag->name .'</a><small>&times;'. $tag->count .'</small>'; 

							$posts = get_posts( "tag_id=". $tag->term_id ."&numberposts=1" );
							foreach( $posts as $post ) {
								setup_postdata( $post );
								echo '<p><a class="tit" href="'.get_permalink().'">'.get_the_title().'</a></p>';
							}

							echo '</li>';
						} 
				
					?>
				</ul>

	    	</div>
	    	<?php MBThemes_custom_paging($paged,$total_pages);?>
	    </div>
	</div>
</div>
<?php get_footer();?>