<?php 
if(_MBT('search_post_id')){
    $sss = get_query_var('s');
    if($sss && is_numeric($sss)){
        if (get_post_status($sss) === 'publish') {   
            $permalink = get_permalink($sss);   
            wp_redirect($permalink, 301);  
            exit; 
        }
    }
}
get_header();
$style = _MBT('list_style');$cat_class = 'grids'; if($style == 'list') $cat_class = 'lists';elseif($style == 'list2') $cat_class = 'lists cols-two'; elseif($style == 'list3') $cat_class = 'lists cols-three';elseif($style == 'list-title') $cat_class = 'lists cols-title';elseif($style == 'grid-audio') $cat_class = 'grids';
$style_cat_ID = 0;
if(isset($_GET['cat']) && $_GET['cat']){
    $style_cat_ID = $_GET['cat'];

    if(isset($_GET['c4']) && $_GET['c4']){
        $style_cat_ID = $_GET['c4'];
    }elseif(isset($_GET['c3']) && $_GET['c3']){
        $style_cat_ID = $_GET['c3'];
    }elseif(isset($_GET['c2']) && $_GET['c2']){
        $style_cat_ID = $_GET['c2'];
    }
}
if($style_cat_ID){
    $style = get_term_meta($style_cat_ID,'style',true);
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    elseif($style == 'list2') $cat_class = 'lists cols-two';
    elseif($style == 'list3') $cat_class = 'lists cols-three';
    elseif($style == 'list-title') $cat_class = 'lists cols-title';
    else{
        $style = get_term_meta(MBThemes_parent_cid($style_cat_ID),'style',true);
        if($style == 'list'){
            $cat_class = 'lists';
        }elseif($style == 'list2'){
            $cat_class = 'lists cols-two';
        }elseif($style == 'list3'){
            $cat_class = 'lists cols-three';
        }elseif($style == 'list-title'){
            $cat_class = 'lists cols-title';
        }else{
            $style = _MBT('list_style');if($style == 'list') $cat_class = 'lists';elseif($style == 'list2') $cat_class = 'lists cols-two'; elseif($style == 'list3') $cat_class = 'lists cols-three';elseif($style == 'list-title') $cat_class = 'lists cols-title';elseif($style == 'grid-audio') $cat_class = 'grids';
        }
    }
}

$page_params = array( 'paged', 'page');
$current_url = remove_query_arg($page_params, MBThemes_selfURL());
$current_url = preg_replace('/\/page\/(\d+)/','',$current_url);
?>
<div class="banner-archive banner-search" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php _e('搜索','mobantu');?> <?php echo get_query_var( 's' );?></h1>
		<div class="search-form">
            <form method="get" class="site-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
                <?php 
                if(_MBT('post_search_types')){
                    $post_search_types = explode(',', trim(_MBT('post_search_types')));
                    if(count($post_search_types)){
                        echo '<div class="search-cat">';
                        if(isset($_GET['post_type'])){
                            $postType = get_post_type_object($_GET['post_type']);
                            if($postType){
                                echo $postType->labels->name;
                            }else{
                                echo __('所有类型','mobantu');
                            }
                        }else{
                            echo __('文章类型','mobantu');
                        }
                        echo '</div>';
                    }
                }
                ?>
                <input class="search-input" name="s" type="text" placeholder="<?php __('搜索一下','mobantu');?>" value="<?php echo get_query_var( 's' );?>">
                <input type="hidden" name="post_type" class="search-cat-val" value="<?php if(isset($_GET['post_type'])) echo $_GET['post_type'];else echo 'post';?>">
                <button class="search-btn" type="submit"><i class="icon icon-search"></i></button>
                <?php 
                    if(_MBT('post_search_types')){
                        echo '<div class="search-cats"><ul>';
                        echo '<li data-id="">'.__('所有类型','mobantu').'</li>';
                        $post_search_types = explode(',', trim(_MBT('post_search_types')));
                        foreach ($post_search_types as $type) {
                            $postType = get_post_type_object($type);
                            if($postType){
                                echo '<li data-id="'.$type.'">'.$postType->labels->name.'</li>';
                            }
                        }
                        echo '</ul></div>';
                    }
                ?>
            </form>
        </div>
	</div>
</div>
<div class="main">
    <?php do_action("modown_main");?>
	<div class="container clearfix">
        <?php if($style == 'list' || $style == 'list-title') echo '<div class="content-wrap"><div class="content">';?>
		<?php MBThemes_ad('ad_list_header');?>
		<?php if(_MBT('filter_search')){?>
		<div class="filters">
            <?php if(_MBT('filter_cat')){?>
			<div class="filter-item">
                <span><?php echo _MBT('filter_cats_title1')?_MBT('filter_cats_title1'):'分类';?></span>
                <div class="filter">
                    <?php 
                        if(!isset($_GET['cat']) || (isset($_GET['cat']) && $_GET['cat'] == '')) $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("cat"=>'','c2'=>'','c3'=>'','c4'=>'','t'=>''),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('全部','mobantu').'</a>';
                        $filter_cat_ids = _MBT('banner_cats');
                        if($filter_cat_ids){
                            $filter_cat_ids_array = explode(',', $filter_cat_ids);
                            foreach ($filter_cat_ids_array as $cat_id) {
                                $term = get_term_by('id',$cat_id,'category');
                                if($term){
                                    if(isset($_GET['cat']) && $_GET['cat'] == $term->term_id) $class="active";else $class = ''; 
                                    echo '<a href="'.add_query_arg(array("cat"=>$term->term_id),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <?php 
            if(isset($_GET['cat']) && $_GET['cat']){
                $category = get_term_by('id',$_GET['cat'],'category');
                $cat_childs = get_categories("parent=".$category->term_id."&hide_empty=0&depth=1");  
                if($cat_childs){
            ?>
            <div class="filter-item">
                <span><?php echo _MBT('filter_cats_title2')?_MBT('filter_cats_title2'):'二级分类';?></span>
                <div class="filter">
                    <?php 
                        if((isset($_GET['c2']) && $_GET['c2'] == '') || !isset($_GET['c2'])) $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("c2"=>'','c3'=>'','c4'=>'','t'=>''),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('全部','mobantu').'</a>';

                        foreach ($cat_childs as $term) {
                            if(isset($_GET['c2']) && $_GET['c2'] == $term->term_id) $class="active";else $class = ''; 
                            echo '<a href="'.add_query_arg(array("c2"=>$term->term_id,'c3'=>'','c4'=>'','t'=>''),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                        }
                        
                    ?>
                </div>
            </div>
            <?php
                }
            }
            ?>
            <?php 
            if(isset($_GET['c2']) && $_GET['c2']){
                $category = get_term_by('id',$_GET['c2'],'category');
                $cat_childs = get_categories("parent=".$category->term_id."&hide_empty=0&depth=1");  
                if($cat_childs){
            ?>
            <div class="filter-item">
                <span><?php echo _MBT('filter_cats_title3')?_MBT('filter_cats_title3'):'三级分类';?></span>
                <div class="filter">
                    <?php 
                        if((isset($_GET['c3']) && $_GET['c3'] == '') || !isset($_GET['c3'])) $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("c3"=>'','c4'=>'','t'=>''),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('全部','mobantu').'</a>';

                        foreach ($cat_childs as $term) {
                            if(isset($_GET['c3']) && $_GET['c3'] == $term->term_id) $class="active";else $class = ''; 
                            echo '<a href="'.add_query_arg(array("c3"=>$term->term_id,'c4'=>'','t'=>''),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                        }
                        
                    ?>
                </div>
            </div>
            <?php
                }
            }
            if(isset($_GET['c3']) && $_GET['c3']){
                $category = get_term_by('id',$_GET['c3'],'category');
                $cat_childs = get_categories("parent=".$category->term_id."&hide_empty=0&depth=1");  
                if($cat_childs){
            ?>
            <div class="filter-item">
                <span><?php echo _MBT('filter_cats_title5')?_MBT('filter_cats_title5'):'四级分类';?></span>
                <div class="filter">
                    <?php 
                        if((isset($_GET['c4']) && $_GET['c4'] == '') || !isset($_GET['c4'])) $class4="active";else $class4 = ''; 
                        echo '<a href="'.add_query_arg(array("c4"=>'',"t"=>''),$current_url).'" rel="nofollow" class="'.$class4.'">'.__('全部','mobantu').'</a>';

                        foreach ($cat_childs as $term) {
                            if(isset($_GET['c4']) && $_GET['c4'] == $term->term_id) $class="active";else $class = ''; 
                            echo '<a href="'.add_query_arg(array("c4"=>$term->term_id,"t"=>''),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                        }
                        
                    ?>
                </div>
            </div>
            <?php
                }
            }
            ?>

            <?php }?>

            <?php
            $filter_taxonomy_page = _MBT('filter_taxonomy_page'); 
            $post_texonomys = '';
            if((isset($_GET['cat']) && $_GET['cat']) || $filter_taxonomy_page){
                if(isset($_GET['cat']) && $_GET['cat']){
                    $cat_ID = $_GET['cat'];
                    $taxonomys_s = get_term_meta($cat_ID,'taxonomys_s',true);
                    if((_MBT('filter_taxonomy') && $taxonomys_s != '1') || $taxonomys_s == '2'){
                        $post_texonomys = get_term_meta($cat_ID,'taxonomys',true);
                        if(!$post_texonomys){
                            $post_texonomys = _MBT('post_taxonomy');
                        }
                    }
                }elseif(_MBT('filter_taxonomy') && $filter_taxonomy_page){
                    $post_texonomys = _MBT('post_taxonomy');
                }
                

                if($post_texonomys){
                    $post_texonomys = explode('|', $post_texonomys);
                    foreach ($post_texonomys as $post_texonomy) { 
                        $post_texonomy = explode(',', $post_texonomy);
            ?>
            <div class="filter-item">
                <span><?php echo $post_texonomy[0];?></span>
                <div class="filter">
                    <?php 
                        if(!isset($_GET[$post_texonomy[2]]) || (isset($_GET[$post_texonomy[2]]) && $_GET[$post_texonomy[2]] == '')) $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array($post_texonomy[2]=>''),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('全部','mobantu').'</a>';
                        if(count($post_texonomy) == '4'){
                            $taxonomy = get_terms( array(
                                'taxonomy' => $post_texonomy[1],
                                'hide_empty' => _MBT('filter_taxonomy_empty')?true:false,
                                'include' => explode('-', $post_texonomy[3])
                            ) );
                        }else{
                            $taxonomy = get_terms( array(
                                'taxonomy' => $post_texonomy[1],
                                'hide_empty' => _MBT('filter_taxonomy_empty')?true:false,
                            ) );
                        }
                        if($taxonomy){
                            foreach ( $taxonomy as $term ) {
                                if(isset($_GET[$post_texonomy[2]]) && $_GET[$post_texonomy[2]] == $term->term_id) $class="active";else $class = ''; 
                                echo '<a href="'.add_query_arg(array($post_texonomy[2]=>$term->term_id),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                            }
                        }
                    ?>
                </div>
            </div>
            <?php      
                    }
                }
            }
            ?>

            <?php if(_MBT('filter_tag')){?>
            <div class="filter-item">
                <span><?php echo _MBT('filter_cats_title4')?_MBT('filter_cats_title4'):'标签';?></span>
                <div class="filter">
                    <?php 
                        $tags = '';
                        if((isset($_GET['t']) && $_GET['t'] == '') || !isset($_GET['t'])) $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("t"=>''),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('全部','mobantu').'</a>';

                        if(isset($_GET['c4']) && $_GET['c4']){
                            $tags4 = get_term_meta($_GET['c4'],'tags',true);
                            if($tags4) $tags = $tags4;
                            elseif(isset($_GET['c3']) && $_GET['c3']){
                                $tags3 = get_term_meta($_GET['c3'],'tags',true);
                                if($tags3) $tags = $tags3;
                                elseif(isset($_GET['c2']) && $_GET['c2']){
                                    $tags2 = get_term_meta($_GET['c2'],'tags',true);
                                    if($tags2) $tags = $tags2;
                                    else{
                                        $tags = get_term_meta($_GET['c'],'tags',true);
                                    }
                                }
                            }
                        }elseif(isset($_GET['c3']) && $_GET['c3']){
                            $tags3 = get_term_meta($_GET['c3'],'tags',true);
                            if($tags3) $tags = $tags3;
                            elseif(isset($_GET['c2']) && $_GET['c2']){
                                $tags2 = get_term_meta($_GET['c2'],'tags',true);
                                if($tags2) $tags = $tags2;
                                elseif(isset($_GET['c']) && $_GET['c']){
                                    $tags3 = get_term_meta($_GET['c'],'tags',true);
                                    if($tags3) $tags = $tags3;
                                }
                            }elseif(isset($_GET['c']) && $_GET['c']){
                                $tags2 = get_term_meta($_GET['c'],'tags',true);
                                if($tags2) $tags = $tags2;
                            }
                        }elseif(isset($_GET['c2']) && $_GET['c2']){
                            $tags2 = get_term_meta($_GET['c2'],'tags',true);
                            if($tags2) $tags = $tags2;
                            elseif(isset($_GET['c']) && $_GET['c']){
                                $tags3 = get_term_meta($_GET['c'],'tags',true);
                                if($tags3) $tags = $tags3;
                            }
                        }elseif(isset($_GET['cat']) && $_GET['cat']){
                            $tags2 = get_term_meta($_GET['cat'],'tags',true);
                            if($tags2) $tags = $tags2;
                        }

                        $filter_tag_ids = _MBT('filter_tags');
                        if($tags) $filter_tag_ids = $tags;

                        if(_MBT('filter_tag_auto')){
                            if(isset($_GET['c4']) && $_GET['c4']){
                                $filter_tag_ids = MBThemes_related_tags($_GET['c4']);
                            }elseif(isset($_GET['c3']) && $_GET['c3']){
                                $filter_tag_ids = MBThemes_related_tags($_GET['c3']);
                            }elseif(isset($_GET['c2']) && $_GET['c2']){
                                $filter_tag_ids = MBThemes_related_tags($_GET['c2']);
                            }elseif(isset($_GET['cat']) && $_GET['cat']){
                                $filter_tag_ids = MBThemes_related_tags($_GET['cat']);
                            }
                        }

                        if($filter_tag_ids){
                            $filter_tag_ids_array = explode(',', $filter_tag_ids);
                            foreach ($filter_tag_ids_array as $tag_id) {
                                $term = get_term_by('id',$tag_id,'post_tag');
                                if($term){
                                    if(isset($_GET['t']) && $_GET['t'] == $term->term_id) $class="active";else $class = ''; 
                                    echo '<a href="'.add_query_arg(array("t"=>$term->term_id),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                                }
                            }
                        }
                    ?>
                </div>
            </div>
            <?php }?>

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
		<div id="posts" class="posts <?php echo $cat_class;?> <?php if(MBTheme_waterfall() && $style != 'list') echo 'waterfall';?> clearfix">
			<?php 
                $args = array();
				if(_MBT('filter_search')){
                    $args['meta_query'] = array('relation' => 'AND');

                    if(isset($_GET['t']) && $_GET['t']){
                        $args['tag_id'] = $_GET['t'];
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

                    if(isset($post_texonomys) && is_array($post_texonomys)){
                        $args['tax_query'] = array();
                        foreach ($post_texonomys as $post_texonomy) {
                            $post_texonomy = explode(',', $post_texonomy);
                            if(isset($_GET[$post_texonomy[2]]) && $_GET[$post_texonomy[2]]){
                                array_push($args['tax_query'], array('taxonomy' => $post_texonomy[1],'field' => 'term_id','terms' => $_GET[$post_texonomy[2]]) );
                            }
                        }
                    }

				}

                if(isset($_GET['post_type']) && $_GET['post_type']){
                    $args['post_type'] = $_GET['post_type'];
                }else{
                    $post_search_types = _MBT('post_search_types');
                    if($post_search_types){
                        $args['post_type'] = explode(',',$post_search_types);
                    }else{
                        $args['post_type'] = 'post';
                    }
                }

                if(_MBT('search_result_nocats')){
                    $args['category__not_in'] = explode(',', _MBT('search_result_nocats'));
                }

                $arms = array_merge($args, $wp_query->query);
                $arms['s'] = urldecode( get_query_var( 's' ) );

                if(isset($_GET['c4']) && $_GET['c4']){
                    $arms['cat'] = $_GET['c4'];
                }elseif(isset($_GET['c3']) && $_GET['c3']){
                    $arms['cat'] = $_GET['c3'];
                }elseif(isset($_GET['c2']) && $_GET['c2']){
                    $arms['cat'] = $_GET['c2'];
                }elseif(isset($_GET['cat']) && $_GET['cat']){
                    $arms['cat'] = $_GET['cat'];
                }

                query_posts($arms);
                $ccc = 'content';if($style == 'list') $ccc = 'content-list';elseif($style == 'list-title') $ccc = 'content-list-title';elseif($style == 'grid-audio') $ccc = 'content-audio';
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
		<?php MBThemes_ad('ad_list_footer');?>
        <?php if($style == 'list' || $style == 'list-title') {echo '</div></div>';get_sidebar();}?>
	</div>
</div>
<?php get_footer();?>