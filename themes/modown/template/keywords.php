<?php 
/*
	template name: 关键词页面
	description: template for mobantu.com modown theme 
*/
get_header();
$style = _MBT('list_style');$cat_class = 'grids'; if($style == 'list') $cat_class = 'lists'; elseif($style == 'list2') $cat_class = 'lists cols-two'; elseif($style == 'list3') $cat_class = 'lists cols-three';elseif($style == 'list-title') $cat_class = 'lists cols-title';

$page_params = array( 'paged', 'page');
$current_url = remove_query_arg($page_params, MBThemes_selfURL());
$current_url = preg_replace('/\/page\/(\d+)/','',$current_url);

$keyword_search = get_post_meta(get_the_ID(),'keyword_search',true);
$keyword_cat = get_post_meta(get_the_ID(),'keyword_cat',true);
$keyword_tag = get_post_meta(get_the_ID(),'keyword_tag',true);
?>
<div class="banner-page" <?php if(_MBT('banner_page_img')){?> style="background-image: url(<?php echo _MBT('banner_page_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1>
	</div>
</div>
<div class="main">
    <?php do_action("modown_main");?>
	<div class="container clearfix">
        <?php if($style == 'list') echo '<div class="content-wrap"><div class="content">';?>
		<?php if(_MBT('filter')){?>
		<div class="filters">
            
            <?php 
            if(is_user_logged_in() || !_MBT('hide_user_all')){
            if(_MBT('filter_price')){
                $erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
                $erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
                $erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
            ?>
            <div class="filter-item">
                <span><?php _e('价格','mobantu');?></span>
                <div class="filter">
                    <?php 
                        $class3 = '';$class4='';$class5='';$class6='';$class7='';$class8='';$class9='';$class10='';
                        if((isset($_GET['v']) && $_GET['v'] == '') || !isset($_GET['v'])){ 
                            $class3="active";
                        }elseif(isset($_GET['v']) && $_GET['v'] == 'fee'){
                            $class4 = 'active';
                        }elseif(isset($_GET['v']) && $_GET['v'] == 'free'){
                            $class5 = 'active';
                        }elseif(isset($_GET['v']) && $_GET['v'] == 'vip'){
                            $class6 = 'active';
                        }elseif(isset($_GET['v']) && $_GET['v'] == 'nvip'){
                            $class7 = 'active';
                        }elseif(isset($_GET['v']) && $_GET['v'] == 'svip'){
                            $class8 = 'active';
                        }elseif(isset($_GET['v']) && $_GET['v'] == 'vipf'){
                            $class9 = 'active';
                        }elseif(isset($_GET['v']) && $_GET['v'] == 'qvip'){
                            $class10 = 'active';
                        }
                        echo '<a href="'.add_query_arg(array("v"=>""),$current_url).'" rel="nofollow" class="'.$class3.'">'.__('全部','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("v"=>"free"),$current_url).'" rel="nofollow" class="'.$class5.'">'.__('免费','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("v"=>"fee"),$current_url).'" rel="nofollow" class="'.$class4.'">'.__('收费','mobantu').'</a>';
                        if(!_MBT('vip_hidden') && !_MBT('filter_vip')){
                            echo '<a href="'.add_query_arg(array("v"=>"vip"),$current_url).'" rel="nofollow" class="'.$class6.'">'.__('VIP免费','mobantu').'</a>';
                            echo '<a href="'.add_query_arg(array("v"=>"vipf"),$current_url).'" rel="nofollow" class="'.$class9.'">'.__('VIP优惠','mobantu').'</a>';
                            if(get_option('erphp_quarter_price')) echo '<a href="'.add_query_arg(array("v"=>"qvip"),$current_url).'" rel="nofollow" class="'.$class10.'">'.sprintf(__('%s免费','mobantu'), $erphp_quarter_name).'</a>';
                            if(get_option('erphp_year_price')) echo '<a href="'.add_query_arg(array("v"=>"nvip"),$current_url).'" rel="nofollow" class="'.$class7.'">'.sprintf(__('%s免费','mobantu'), $erphp_year_name).'</a>';
                            if(get_option('erphp_life_price')) echo '<a href="'.add_query_arg(array("v"=>"svip"),$current_url).'" rel="nofollow" class="'.$class8.'">'.sprintf(__('%s免费','mobantu'), $erphp_life_name).'</a>';
                        }  
                    ?>
                </div>
            </div>
            <?php }}?>
            <?php if(_MBT('filter_order')){?>
            <div class="filter-item filter-item-order">
                <span><?php _e('排序','mobantu');?></span>
                <div class="filter">
                    <?php 
                        $class3 = '';$class4='';$class5='';$class6='';$class7='';$class8='';$class9='';$class10='';
                        if((isset($_GET['o']) && $_GET['o'] == '') || !isset($_GET['o'])){ 
                            $class3="active";
                        }elseif(isset($_GET['o']) && $_GET['o'] == 'download'){
                            $class4 = 'active';
                        }elseif(isset($_GET['o']) && $_GET['o'] == 'view'){
                            $class5 = 'active';
                        }elseif(isset($_GET['o']) && $_GET['o'] == 'comment'){
                            $class6 = 'active';
                        }elseif(isset($_GET['o']) && $_GET['o'] == 'update'){
                            $class7 = 'active';
                        }elseif(isset($_GET['o']) && $_GET['o'] == 'recommend'){
                            $class8 = 'active';
                        }elseif(isset($_GET['o']) && $_GET['o'] == 'rand'){
                            $class9 = 'active';
                        }elseif(isset($_GET['o']) && $_GET['o'] == 'zan'){
                            $class10 = 'active';
                        }
                        echo '<a href="'.add_query_arg(array("o"=>''),$current_url).'" rel="nofollow" class="'.$class3.'">'.__('最新','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                        echo '<a href="'.add_query_arg(array("o"=>"update"),$current_url).'" rel="nofollow" class="'.$class7.'">'.__('更新','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                        echo '<a href="'.add_query_arg(array("o"=>"recommend"),$current_url).'" rel="nofollow" class="'.$class8.'">'.__('推荐','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                        echo '<a href="'.add_query_arg(array("o"=>"download"),$current_url).'" rel="nofollow" class="'.$class4.'">'.__('下载','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                        echo '<a href="'.add_query_arg(array("o"=>"view"),$current_url).'" rel="nofollow" class="'.$class5.'">'.__('浏览','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                        echo '<a href="'.add_query_arg(array("o"=>"zan"),$current_url).'" rel="nofollow" class="'.$class10.'">'.__('点赞','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                        echo '<a href="'.add_query_arg(array("o"=>"comment"),$current_url).'" rel="nofollow" class="'.$class6.'">'.__('评论','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                        echo '<a href="'.add_query_arg(array("o"=>"rand"),$current_url).'" rel="nofollow" class="'.$class9.'">'.__('随机','mobantu').' <i class="icon icon-arrow-down-o"></i></a>';
                    ?>
                </div>
            </div>
        	<?php }?>
        </div>
        <?php }?>

		<div id="posts" class="posts <?php echo $cat_class;?> <?php if(_MBT('waterfall')) echo 'waterfall';?> clearfix">
			<?php 
			  	if(is_front_page()){
                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                }else{
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                }
			  	$args = array(
                    'post_type' => 'post',
				    'ignore_sticky_posts' => 1,
                    //'category__not_in' => explode(',', _MBT('all_cats_exclude')),
				    'paged' => $paged
				);

			  	//if(_MBT('filter')){
			  		$args['meta_query'] = array('relation' => 'AND');

			  		if($keyword_search){
                        $args['s'] = $keyword_search;
                    }

                    if($keyword_cat){
                        $args['cat'] = $keyword_cat;
                    }

			  		if($keyword_tag){
                        $args['tag_id'] = $keyword_tag;
                    }

                    if(isset($_GET['v']) && $_GET['v']){
                        if($_GET['v'] == 'fee'){
                            if(!_MBT('filter_prices')){
                                $args['meta_query'] = array(array('key' => 'down_price', 'compare' => '>','value' => '0'));
                            }else{
                                $args['meta_query'] = array(
                                    array(
                                        'relation' => 'OR',
                                        array('key' => 'down_price', 'compare' => '>','value' => '0'),
                                        array('key' => 'down_urls', 'compare' => 'EXISTS')
                                    )
                                );
                            }
                        }elseif($_GET['v'] == 'free'){
                            if(!_MBT('filter_prices')){
                                $args['meta_query'] = array(
                                    array('key' => 'member_down', 'value' => array(4,8,9,15), 'compare' => 'NOT IN'),
                                    array(
                                        'relation' => 'OR',
                                        array('key' => 'down_price', 'value' => ''),
                                        array('key' => 'down_price', 'value' => '0')
                                    )
                                );
                            }else{
                                $args['meta_query'] = array(
                                    array('key' => 'member_down', 'value' => array(4,8,9,15), 'compare' => 'NOT IN'),
                                    array(
                                        'relation' => 'AND',
                                        array(
                                            'relation' => 'OR',
                                            array('key' => 'down_price', 'value' => ''),
                                            array('key' => 'down_price', 'value' => '0')
                                        ),
                                        array(
                                            'relation' => 'OR',
                                            array('key' => 'down_urls', 'compare' => 'NOT EXISTS'),
                                            array('key' => 'down_urls', 'value' => '')
                                        )
                                    )
                                );
                            }
                        }elseif($_GET['v'] == 'vip'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(4,3), 'compare' => 'IN'));
                        }elseif($_GET['v'] == 'nvip'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(8,6), 'compare' => 'IN'));
                        }elseif($_GET['v'] == 'svip'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(9,7,10,11,13,14,20), 'compare' => 'IN'));
                        }elseif($_GET['v'] == 'vipf'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(2,5), 'compare' => 'IN'));
                        }elseif($_GET['v'] == 'qvip'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(15,16), 'compare' => 'IN'));
                        }
                    }
                    
                    if(isset($_GET['o']) && $_GET['o']){
                        if($_GET['o'] == 'comment'){
                            $args['orderby'] = 'comment_count';
                        }elseif($_GET['o'] == 'update'){
                            $args['orderby'] = 'modified';
                        }elseif($_GET['o'] == 'rand'){
                            $args['orderby'] = 'rand';
                        }elseif($_GET['o'] == 'recommend'){
                            array_push($args['meta_query'], array('key' => 'down_recommend', 'value' => '1'));
                        }else{
                            if($_GET['o'] == 'download'){
                                $args['meta_key'] = 'down_times';
                            }
                            elseif($_GET['o'] == 'view'){
                                $args['meta_key'] = 'views';
                            }elseif($_GET['o'] == 'zan'){
                                $args['meta_key'] = 'zan';
                            }
                            $args['orderby'] = 'meta_value_num';
                        }
                    }
                    
			  	//}
				query_posts($args);
                $ccc = 'content';if($style == 'list') $ccc = 'content-list';elseif($style == 'grid-audio') $ccc = 'content-audio';elseif($style == 'list-title') $ccc = 'content-list-title';
                if ( have_posts() ){
    				while ( have_posts() ) : the_post(); 
    				get_template_part( 'module/'.$ccc );
    				endwhile; //wp_reset_query(); 
                }else{
                    get_template_part( 'module/none' );
                }
			?>
		</div>
		<?php MBThemes_paging();?>
		<div class="posts-loading"><img src="<?php bloginfo('template_url')?>/static/img/loader.gif"></div>
        <?php if($style == 'list') {echo '</div></div>';get_sidebar();}?>
	</div>
</div>
<?php get_footer();?>