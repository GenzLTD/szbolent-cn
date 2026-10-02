<?php 
/*
	template name: 任务页面
	description: template for mobantu.com modown theme 
*/
get_header();$erphpdown = erphp_task_erphpdown_active();

$page_params = array( 'paged', 'page');
$current_url = remove_query_arg($page_params, MBThemes_selfURL());
$current_url = preg_replace('/\/page\/(\d+)/','',$current_url);
?>
<?php 
if(_MBT('banner') == '1'){
    get_template_part("module/banner");
}elseif(_MBT('banner') == '2'){ 
    get_template_part("module/slider");
}elseif(_MBT('banner') == '3'){ 
    get_template_part("module/banner");
    get_template_part("module/slider");
}?>
<?php if(_MBT('ad_banner_footer_s')) {echo '<div class="banner-bottom"><div class="container">';MBThemes_ad('ad_banner_footer');echo '</div></div>';}?>
<div class="main">
    <?php do_action("modown_main");?>
    <div class="container clearfix">
        <?php if(function_exists('MBThemes_ad')) MBThemes_ad('ad_list_header');?>
        <div class="filters-task">
            <?php if(get_option("erphp_task_type")){?>
            <div class="filter-task-item">
                <span><?php _e('类型','mobantu');?></span>
                <div class="filter-task">
                    <?php 
                        $class1 = '';$class2='';$class3='';
                        if((isset($_GET['tt']) && $_GET['tt'] == '') || !isset($_GET['tt'])){ 
                            $class1="active";
                        }elseif(isset($_GET['tt']) && $_GET['tt'] == '1'){
                            $class2 = 'active';
                        }elseif(isset($_GET['tt']) && $_GET['tt'] == '2'){
                            $class3 = 'active';
                        }
                        echo '<a href="'.add_query_arg(array("tt"=>""),$current_url).'" rel="nofollow" class="'.$class1.'">'.__('全部','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("tt"=>"1"),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('单人任务','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("tt"=>"2"),$current_url).'" rel="nofollow" class="'.$class3.'">'.__('多人任务','mobantu').'</a>';
                    ?>
                </div>
            </div>
            <?php }?>
            <div class="filter-task-item">
                <span><?php _e('类别','mobantu');?></span>
                <div class="filter-task">
                    <?php 
                        if(!isset($_GET['tc']) || (isset($_GET['tc']) && $_GET['tc'] == '')) $class2="active";else $class2 = ''; 
                        
                        echo '<a href="'.add_query_arg(array('tc'=>'','tc2'=>''),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('全部','mobantu').'</a>';
                        $tasks = get_terms( array(
                            'taxonomy' => 'tasks',
                            'hide_empty' => false,
                            'parent' => 0
                        ) );
                        if($tasks){
                            foreach ( $tasks as $term ) {
                                if(isset($_GET['tc']) && $_GET['tc'] == $term->term_id) $class="active";else $class = ''; 
                                echo '<a href="'.add_query_arg(array('tc'=>$term->term_id),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                            }
                        }
                    ?>
                </div>
            </div>
            <?php if(isset($_GET['tc']) && $_GET['tc']){
                $tasks = get_terms( array(
                    'taxonomy' => 'tasks',
                    'hide_empty' => false,
                    'parent' => $_GET['tc']
                ) );
                if($tasks){
            ?>
            <div class="filter-task-item">
                <span><?php _e('子类','mobantu');?></span>
                <div class="filter-task">
                    <?php 
                        if(!isset($_GET['tc2']) || (isset($_GET['tc2']) && $_GET['tc2'] == '')) $class2="active";else $class2 = ''; 
                        
                        echo '<a href="'.add_query_arg(array('tc2'=>''),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('全部','mobantu').'</a>';
                        
                        foreach ( $tasks as $term ) {
                            if(isset($_GET['tc2']) && $_GET['tc2'] == $term->term_id) $class="active";else $class = ''; 
                            
                            echo '<a href="'.add_query_arg(array('tc2'=>$term->term_id),$current_url).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                        }
                        
                    ?>
                </div>
            </div>
            <?php }}?>
            <div class="filter-task-item">
                <span><?php _e('状态','mobantu');?></span>
                <div class="filter-task">
                    <?php 
                        $class1 = '';$class2='';$class3='';$class4='';
                        if((isset($_GET['td']) && $_GET['td'] == '') || !isset($_GET['td'])){ 
                            $class1="active";
                        }elseif(isset($_GET['td']) && $_GET['td'] == '0'){
                            $class2 = 'active';
                        }elseif(isset($_GET['td']) && $_GET['td'] == '1'){
                            $class3 = 'active';
                        }elseif(isset($_GET['td']) && $_GET['td'] == '2'){
                            $class4 = 'active';
                        }
                        echo '<a href="'.add_query_arg(array("td"=>""),$current_url).'" rel="nofollow" class="'.$class1.'">'.__('全部','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("td"=>"0"),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('投标中','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("td"=>"1"),$current_url).'" rel="nofollow" class="'.$class3.'">'.__('已选标','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("td"=>"2"),$current_url).'" rel="nofollow" class="'.$class4.'">'.__('已完成','mobantu').'</a>';
                    ?>
                </div>
            </div>
            <div class="filter-task-item">
                <span><?php _e('资金','mobantu');?></span>
                <div class="filter-task">
                    <?php 
                        $class1 = '';$class2='';$class3='';
                        if((isset($_GET['tp']) && $_GET['tp'] == '') || !isset($_GET['tp'])){ 
                            $class1="active";
                        }elseif(isset($_GET['tp']) && $_GET['tp'] == '1'){
                            $class2 = 'active';
                        }elseif(isset($_GET['tp']) && $_GET['tp'] == '0'){
                            $class3 = 'active';
                        }
                        echo '<a href="'.add_query_arg(array("tp"=>""),$current_url).'" rel="nofollow" class="'.$class1.'">'.__('全部','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("tp"=>"1"),$current_url).'" rel="nofollow" class="'.$class2.'">'.__('已托管','mobantu').'</a>';
                        echo '<a href="'.add_query_arg(array("tp"=>"0"),$current_url).'" rel="nofollow" class="'.$class3.'">'.__('未托管','mobantu').'</a>';
                    ?>
                </div>
            </div>
        </div>
        <div class="tasks-title">
            <h2><?php _e('任务列表','mobantu');?></h2>
            <a href="<?php echo get_permalink(get_option("erphp_task_new_id"));?>" class="erphp-task-new" target="_blank"><?php _e('发布任务','mobantu');?></a>
        </div>
        <div class="tasks-nav clearfix">
            <div class="task-part task-part1"><?php _e('标题','mobantu');?></div>
            <div class="task-part task-part2"><?php _e('发布时间','mobantu');?></div>
            <div class="task-part task-part3"><?php _e('参与人数','mobantu');?></div>
            <div class="task-part task-part4"><?php _e('预算金额','mobantu');?></div>
            <div class="task-part task-part5"><?php _e('状态','mobantu');?></div>
            <div class="task-part task-part6"><?php _e('操作','mobantu');?></div>
        </div>
        <div id="posts" class="posts tasks clearfix">
            <?php 
                if(is_front_page()){
                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                }else{
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                }
                $args = array(
                    'post_type' => 'task',
                    //'ignore_sticky_posts' => 1,
                    'paged' => $paged
                );

                $args['tax_query'] = array();
                $args['meta_query'] = array();
                if(isset($_GET['tt']) && $_GET['tt']){
                    array_push($args['meta_query'], array('key' => 'task_type','value' => $_GET['tt']) );
                }
                if(isset($_GET['tc2']) && $_GET['tc2']){
                    array_push($args['tax_query'], array('taxonomy' => 'tasks','field' => 'term_id','terms' => $_GET['tc2']) );
                }elseif(isset($_GET['tc']) && $_GET['tc']){
                    array_push($args['tax_query'], array('taxonomy' => 'tasks','field' => 'term_id','terms' => $_GET['tc']) );
                }
                if(isset($_GET['td']) && $_GET['td'] != ''){
                    if($_GET['td'] == '0'){
                        array_push($args['meta_query'], array('relation' => 'or',array('key' => 'task_status','compare' => 'NOT EXISTS'),array('key' => 'task_status','value' => '0')) );
                    }else{
                        array_push($args['meta_query'], array('key' => 'task_status','value' => $_GET['td']) );
                    }
                }
                if(isset($_GET['tp']) && $_GET['tp'] != ''){
                    if($_GET['tp'] == '0'){
                        array_push($args['meta_query'], array('key' => 'task_paid','compare' => 'NOT EXISTS') );
                    }else{
                        array_push($args['meta_query'], array('key' => 'task_paid','value' => $_GET['tp']) );
                    }
                }
                query_posts($args);
                if ( have_posts() ){
                    while ( have_posts() ) : the_post(); 
                    include (ERPHP_TASK_PATH . '/temp/task-item.php');
                    endwhile; //wp_reset_query(); 
                }else{
                    get_template_part( 'module/none' );
                }
            ?>
        </div>
        <?php erphp_task_paging();?>
        <?php if(function_exists('MBThemes_ad')) MBThemes_ad('ad_list_footer');?>
    </div>
    <?php if(is_front_page()){?>
        <?php if(_MBT('home_blog')) get_template_part("module/home-blogs");?>
        <?php if(_MBT('home_authors')) get_template_part("module/home-authors");?>
        <?php if(_MBT('home_vip') && (is_user_logged_in() || !_MBT('hide_user_all'))) get_template_part("module/vip");?>
        <?php if(_MBT('home_why')) get_template_part("module/why");?>
        <?php if(_MBT('home_total')) get_template_part("module/total");?>
        <?php if(_MBT('ad_home_footer_s')) {echo '<div class="container">';MBThemes_ad('ad_home_footer');echo '</div>';}?>
    <?php }?>
</div>
<?php get_footer();?>