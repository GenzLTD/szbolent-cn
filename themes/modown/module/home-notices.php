<div class="banner-notices">
    <div class="container">
        <img src="<?php bloginfo("template_url");?>/static/img/gg.png" class="notices-icon" />
        <?php if(_MBT('banner_bottom_notice_scroll')){?>
        <div class="marquee-wrap">
            <ul class="marquee-notices">
            <?php 
                $args = array(
                    'post_type'        => 'blog',
                    'order'            => 'DESC',
                    'showposts'        => 6,
                    'ignore_sticky_posts' => 1,
                    'tax_query' => array(
                        array('taxonomy' => 'blogs','field' => 'term_id','terms' => _MBT('banner_bottom_notice'))
                    )
                );
                
                query_posts($args);
                $copy_notices = '';
                while (have_posts()) : the_post(); 
                    $copy_notices .= '<li><a href="'.get_permalink().'" target="_blank"><span>'.get_the_title().'</span><time>'.get_the_date("Y-m-d").'</time></a></li>';
                    echo '<li><a href="'.get_permalink().'" target="_blank"><span>'.get_the_title().'</span><time>'.get_the_date("Y-m-d").'</time></a></li>';
                endwhile; wp_reset_query(); 
                echo $copy_notices;
            ?>
            </ul>
        </div>
        <?php }else{?>
        <div class="marquee-wrap">
            <ul>
            <?php 
                $args = array(
                    'post_type'        => 'blog',
                    'order'            => 'DESC',
                    'showposts'        => 3,
                    'ignore_sticky_posts' => 1,
                    'tax_query' => array(
                        array('taxonomy' => 'blogs','field' => 'term_id','terms' => _MBT('banner_bottom_notice'))
                    )
                );
                
                query_posts($args);
                while (have_posts()) : the_post(); 
                    echo '<li><a href="'.get_permalink().'" target="_blank"><span>'.get_the_title().'</span><time>'.get_the_date("Y-m-d").'</time></a></li>';
                endwhile; wp_reset_query(); 
            ?>
            </ul>
        </div>
        <?php }?>
    </div>
</div>