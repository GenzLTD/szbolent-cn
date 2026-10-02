<?php get_header();$taxonomy = get_queried_object();?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php single_tag_title() ?></h1>
		<p class="archive-desc"><?php echo trim(strip_tags(tag_description()));?></p>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap">
	    	<div class="content">
				<?php MBThemes_ad('ad_list_header');?>
				<div class="list-question-new"><span class="tit"><?php echo __("最新问题","mobantu");?></span><a href="<?php echo get_permalink(MBThemes_page("template/ask.php"));?>" class="btn"><?php echo __('我要提问','mobantu');?></a></div>
				<div class="filters">
					<div class="filter-item">
		                <span><?php echo __("分类","mobantu");?></span>
		                <div class="filter">
		                    <?php 
		                    	$page_params = array( 'paged', 'page');
								$current_url = remove_query_arg($page_params, MBThemes_selfURL());
								$current_url = preg_replace('/\/page\/(\d+)/','',$current_url);
		                        if(is_post_type_archive('question')) $class2="active";else $class2 = ''; 
		                        echo '<a href="'.get_post_type_archive_link('question').'" rel="nofollow" class="'.$class2.'">全部</a>';
		                        $question_categorys = get_terms( array(
		                            'taxonomy' => 'question_category',
		                            'hide_empty' => false,
		                            'parent' => 0
		                        ) );
		                        if($question_categorys){
		                            foreach ( $question_categorys as $term ) {
		                            	$class = '';
		                                if(!is_post_type_archive('question')){
		                                    if($taxonomy->term_id == $term->term_id) $class="active";
		                                }
		                                echo '<a href="'.get_term_link($term).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
		                            }
		                        }
		                    ?>
		                </div>
		            </div>
				</div>
				<div id="posts" class="lists clearfix">
					<?php 
						if ( have_posts() ){
							while ( have_posts() ) : the_post(); 
							get_template_part( 'module/content-question' );
							endwhile; wp_reset_query(); 
						}else{
		                    get_template_part( 'module/none' );
		                }
					?>
				</div>
				<?php MBThemes_paging();?>
				<?php MBThemes_ad('ad_list_footer');?>
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer();?>