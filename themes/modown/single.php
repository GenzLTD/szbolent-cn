<?php 
if (!session_id()) session_start();
get_header();
if(MBThemes_single_role()){
	echo '<div class="only-erphpdown-vip"><div class="container"><a href="'.get_permalink(MBThemes_page("template/vip.php")).'"><i class="icon icon-crown-s"></i></a><br><p>'.__('此内容仅限VIP查看','mobantu').'</p></div></div>';
}else{
	$tips = get_post_meta(get_the_ID(),'tips',true);
	$nosidebar = MBThemes_single_sidebar();
	$boxshow = 0;
	if(MBThemes_post_down_position() == 'box' || MBThemes_post_down_position() == 'boxbottom' || MBThemes_post_down_position() == 'boxside'){
		$boxshow = 1;
	}
	if(ERPHPDOWN_IS_ACTIVE && _MBT('post_box_erphpdown')){
		$start_down=get_post_meta(get_the_ID(), 'start_down', true);
		$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
		if(!($start_down || $start_down2)){
			$boxshow = 0;
		}
	}
?>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container clearfix">
		<?php MBThemes_ad('ad_post_top');?>
		<?php if (!_MBT('post_breadcrumb')) MBThemes_breadcrumbs(); ?>
		<?php if(!_MBT('post_video_content') && _MBT('post_video_fullwidth')) get_template_part('module/video');?>
		<?php if($boxshow && !_MBT('post_box_leftside')) get_template_part('module/post-box');?>
		<div class="content-wrap">
	    	<div class="content<?php if($nosidebar) echo ' nosidebar';?>">
	    		<?php MBThemes_ad('ad_post_header');?>
	    		<?php if(!_MBT('post_video_content') && !_MBT('post_video_fullwidth')) get_template_part('module/video');?>
	    		<?php get_template_part('module/images');?>
	    		<?php while (have_posts()) : the_post(); ?>
	    		<article id="single-content" class="single-content">
		    		<?php if(!$boxshow || _MBT('post_box_leftside')) get_template_part('module/post-header');?>
		    		<?php if($boxshow && _MBT('post_box_leftside')) get_template_part('module/post-box');?>
		    		<div class="article-content clearfix">
		    			<?php if($tips) echo '<div class="article-tips"><div><i class="icon icon-smile"></i> '.$tips.'</div></div>';?>
		    			<?php MBThemes_ad('ad_post_content_header');?>
		    			<?php if(_MBT('post_meta_top')) get_template_part('module/post-meta');?>
		    			<?php if(ERPHPDOWN_IS_ACTIVE){ 
		    				if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop') {
		    					MBThemes_erphpdown_box();
		    				}
		    			}?>
		    			<?php 
		    				$tabs = get_post_meta(get_the_ID(),'tabs',true);
		    				$tabs_content_title = get_post_meta(get_the_ID(),'tabs_content_title',true);
		    				$tabs_content_before = get_post_meta(get_the_ID(),'tabs_content_before',true);
		    				if(isset($tabs) && $tabs){
		    					echo do_shortcode(wpautop($tabs_content_before));
		    			?>
			    			<div class="article-tabs-tab">
			    				<a href="javascript:;" class="active" data-item="desc"><?php echo $tabs_content_title?$tabs_content_title:__('详细介绍','mobantu');?></a>
			    				<?php $i = 1;foreach($tabs as $tab){?>
			    				<a href="javascript:;" data-item="tab<?php echo $i;?>"><?php echo $tab['title'];?></a>
			    				<?php $i ++;}?>
			    			</div>
			    			<div class="article-tabs-content">
			    				<div class="article-tab-item article-tab-desc active">
					    			<?php 
					    				if(_MBT('post_video_content')) get_template_part('module/video');
					    				if(_MBT('post_feature') && MBThemes_feature_has()){
					    					global $post;
					    					echo '<div class="feature-image"><img src="'.MBThemes_thumbnail_share($post).'" alt="'.get_the_title().'" /></div>';
					    				}
					    			?>
					    			<?php get_template_part('module/audio');?>
					    			<?php if(_MBT('post_content_all')){?>
					    			<div class="article-content-all"><?php the_content(); ?><div class="article-content-all-more"><span>阅读全文<br><i class="ic icon icon-arrow-double-down"></i></span></div></div>
					    			<?php }else{?>
					    			<?php the_content(); ?>
					    			<?php }?>
					    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
					    			<?php 
						    		if(ERPHPDOWN_IS_ACTIVE){ 
						    			if(MBThemes_post_down_position() == 'bottom' || MBThemes_post_down_position() == 'sidebottom' || MBThemes_post_down_position() == 'boxbottom' || MBThemes_post_down_position() == 'side') {MBThemes_erphpdown_box();
						    			}else{
						    				if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop'){}
						    				else MBThemes_erphpdown_box(false);
						    			}
						    		}?>
			    				</div>
			    				<?php if(isset($tabs) && $tabs){$j = 1;foreach($tabs as $tab){?>
			    				<div class="article-tab-item article-tab-tab<?php echo $j;?>">
			    					<?php echo do_shortcode(wpautop($tab['content']));?>
			    				</div>
			    				<?php $j ++;}}?>
			    			</div>
		    			<?php
		    				}else{
		    			?>
			    			<?php 
			    				if(_MBT('post_video_content')) get_template_part('module/video');
			    				if(_MBT('post_feature') && MBThemes_feature_has()){
			    					global $post;
			    					echo '<div class="feature-image"><img src="'.MBThemes_thumbnail_share($post).'" alt="'.get_the_title().'" /></div>';
			    				}
			    			?>
			    			<?php get_template_part('module/audio');?>
			    			<?php if(_MBT('post_content_all')){?>
			    			<div class="article-content-all"><?php the_content(); ?><div class="article-content-all-more"><span>阅读全文<br><i class="ic icon icon-arrow-double-down"></i></span></div></div>
			    			<?php }else{?>
			    			<?php the_content(); ?>
			    			<?php }?>
			    			<?php wp_link_pages('link_before=<span>&link_after=</span>&before=<div class="article-paging">&after=</div>&next_or_number=number'); ?>
			    			<?php 
				    		if(ERPHPDOWN_IS_ACTIVE){ 
				    			if(MBThemes_post_down_position() == 'bottom' || MBThemes_post_down_position() == 'sidebottom' || MBThemes_post_down_position() == 'boxbottom' || MBThemes_post_down_position() == 'side') {MBThemes_erphpdown_box();
				    			}else{
				    				if(MBThemes_post_down_position() == 'top' || MBThemes_post_down_position() == 'sidetop'){}
				    				else MBThemes_erphpdown_box(false);
				    			}
				    		}?>
			    		<?php }?>
			    		
			    		<?php MBThemes_ad('ad_post_content_footer');?>
			    		<?php 
			    			if(_MBT('post_faq')){
			    				echo '<div class="article-faqs"><div class="article-faq-title">'.__("常见问题","mobantu").'</div><div class="items">';
			    				$sort = '1 2 3 4 5';
					            $sort = array_unique(explode(' ', trim($sort)));
					            foreach ($sort as $key => $value) {
					                if( _MBT('post_faq_question'.$value) ){
					                    echo '<div class="item"><div class="que"><i class="icon icon-help"></i> '._MBT('post_faq_question'.$value).'<i class="ic icon icon-arrow-down"></i></div><div class="ans">'._MBT('post_faq_answer'.$value).'</div></div>';
					                }
					            }
			    				echo '</div></div>';
			    			}
			    		?>
		    			<?php if(_MBT('post_copyright')){?>
		    			<div class="article-copyright"><i class="icon icon-warning1"></i> <?php if(_MBT('post_copyright_custom')){
		    					echo str_replace('%post%', '<a href="'.get_permalink(get_the_ID()).'">'.get_permalink(get_the_ID()).'</a>', _MBT('post_copyright_custom'));
		    				}else{?>
		    				<?php _e('本文链接：','mobantu')?><a href="<?php the_permalink();?>"><?php the_permalink();?></a><?php _e('，转载请注明出处。','mobantu')?>
		    				<?php }?>
		    			</div><?php }?>
		            </div>
		    		<?php get_template_part('module/act');?>
		            <?php if(_MBT('post_tags')) the_tags('<div class="article-tags">','','</div>'); ?>
					<?php if(_MBT('post_share')) get_template_part('module/share');?>
	            </article>
	            <?php endwhile;  ?>
	            <?php if(_MBT('post_nav')){?>
	            <nav class="article-nav">
	                <span class="article-nav-prev"><?php previous_post_link(__('上一篇','mobantu').'<br>%link'); ?></span>
	                <span class="article-nav-next"><?php next_post_link(__('下一篇','mobantu').'<br>%link'); ?></span>
	            </nav>
	            <?php }?>
	            <?php MBThemes_ad('ad_post_footer');?>
	            <?php if(_MBT('post_related')) get_template_part('module/related');?>
	            <?php comments_template('', true); ?>
	            <?php MBThemes_ad('ad_post_comment');?>
	    	</div>
	    </div>
		<?php if(!$nosidebar) get_sidebar(); ?>
	</div>
</div>
<?php } get_footer();?>