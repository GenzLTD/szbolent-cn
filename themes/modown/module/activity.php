<div class="topbar-activity">
    <i class="icon icon-gonggao"></i>
    <ul>
        <?php 
        $results = $wpdb->get_results("select * from ".$wpdb->prefix."activitys order by create_time desc limit 0,10");
        if($results){
            foreach ($results as $value) {
                echo '<li><span><b class="name">';
                if($value->user_id){
                    echo substr(_mbt_substr_cut(get_the_author_meta( 'user_login', $value->user_id )),0,8);
                }else{
                    echo __('游客','mobantu');
                }
                echo '</b> ';

                if($value->user_activity == 'checkin'){
                    echo '签到打卡，获得'.$value->activity_value.get_option('ice_name_alipay').'奖励';
                }elseif($value->user_activity == 'login'){
                    echo '登录了本站';
                }elseif($value->user_activity == 'register'){
                    echo '加入了本站';
                }elseif($value->user_activity == 'buy'){
                    $pp = get_post($value->activity_value);
                    echo '购买了资源 <b>'.($pp?$pp->post_title:'').'</b>';
                }elseif($value->user_activity == 'vip'){
                    echo '开通了VIP';
                }elseif($value->user_activity == 'download'){
                    $pp = get_post($value->activity_value);
                    echo '下载了资源 <b>'.($pp?$pp->post_title:'').'</b>';
                }

                echo '</span><time>'.MBThemes_timeago2($value->create_time).'</time>';

                echo '</li>';
            }
        }
        ?>
    </ul>
</div>
<script>
    setInterval(function () {
        jQuery(".topbar-activity ul li").eq(0).fadeOut("slow",function(){
            jQuery(this).clone().appendTo(jQuery(this).parent()).fadeIn("slow");
            jQuery(this).remove();
        });
    }, 3000);
</script>