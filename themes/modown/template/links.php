<?php 
/*
	template name: 友情链接（网址导航）
	description: template for mobantu.com modown theme 
*/
get_header();
if(MBThemes_page_role()){
    $vip_see = get_post_meta(get_the_ID(),'vip_see',true);
    echo '<div class="only-erphpdown-vip"><div class="container"><a href="'.get_permalink(MBThemes_page("template/vip.php")).'"><i class="icon icon-crown-s"></i></a><br><p>'.sprintf(__('此内容仅限%s查看','mobantu'), getVipTypeName($vip_see)).'</p></div></div>';
}else{

if(_MBT('friendlink_no')){
    $cats = get_terms( 'link_category', array(
        'hierarchical' => true,
    	'hide_empty' => false,
        'exclude' => _MBT('friendlink_id')
    ) );
}else{
    $cats = get_terms( 'link_category', array(
        'hierarchical' => true,
        'hide_empty' => false
    ) );
}
?>
<style>
.pageside h2{background: #f5f5f5;text-align: center;font-size: 18px;padding:15px 0;}
body.night .pageside h2{background: #151515}
</style>
<div class="main">
    <?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap content-nav">
			<?php if(!empty($cats)){?>
			<div class="pageside">
                <div class="theiaStickySidebar">
    				<h2 class="pagemenu-tit"><?php _e('快速导航','mobantu');?></h2>
                    <a href="javascript:;" class="pagemenu-trigger"><i class="icon icon-menu"></i></a>
    			    <div class="pagemenus pagelinks">
    			    	<ul class="pagemenu">
    			        	<?php
    							if(!empty($cats)){
    								foreach ($cats as $cat) {
    									echo '<li><a href="javascript:scrollToTop(\'#links_'.$cat->term_id.'\',-100);">'.$cat->name.' <i class="icon icon-arrow-right"></i></a></li>';
    								}
    							}
    						?>
    			    	</ul>
    			    </div>
                </div>
			</div>
			<?php }else{echo '<style>.content-nav{padding-left:0}</style>';}?>
	    	<div class="content" style="min-height: 300px">
	    		<?php 
	    			if(!empty($cats)){
                        foreach ( $cats as $cat ) {
                            $bookmarks = get_bookmarks( array('category'=>$cat->term_id, 'orderby' => 'rating', 'order' => 'desc') );
                            $html = '';
                            foreach ($bookmarks as $bookmark) {
                                $img = '';
                                if($bookmark->link_image ){
                                    $img = $bookmark->link_image;
                                }
                                $description = $bookmark->link_description ? $bookmark->link_description : __('这个网站没有任何描述信息','mobantu');
                                $html .= '<div class="link-item">
                                        <div class="link-main">
											<a href="'.$bookmark->link_url.'" target="_blank">';
                                            if($img){
                                                $html .= '<img class="link-img" src="'.$img. '"/>';
                                            }else{
                                                $html .= '<i class="icon icon-link link-img"></i>';
                                            }
                                            $html .= '</a>
                                            <a href="'.$bookmark->link_url.'" target="_blank">
                                                <h2>'.$bookmark->link_name.'</h2>
                                            </a>
										</div>
                                        <p class="link-desc">'.$description.'</p>
                                </div>';
                            }

                            echo '<div class="link-box" id="links_'.$cat->term_id.'">
                                <div class="link-title">'.$cat->name.'</div>
                                <div class="link-list clearfix">
                                    '.$html.'
                                </div>
                            </div>';
                        }
                    }else{
                    	$bookmarks = get_bookmarks();
                        $html = '';
                        foreach ($bookmarks as $bookmark) {
                            $img = '';
                            if($bookmark->link_image ){
                                $img = $bookmark->link_image;
                            }
                            $description = $bookmark->link_description ? $bookmark->link_description : __('这个网站没有任何描述信息','mobantu');
                            $html .= '<div class="link-item">
                                    <div class="link-main">
                                        <a href="'.$bookmark->link_url.'" target="_blank">';
                                        if($img){
                                            $html .= '<img class="link-img" src="'.$img. '"/>';
                                        }else{
                                            $html .= '<i class="icon icon-link link-img"></i>';
                                        }
                                        $html .= '</a>
                                        <a href="'.$bookmark->link_url.'" target="_blank">
                                            <h2>'.$bookmark->link_name.'</h2>
                                        </a>
                                    </div>
                                    <p class="link-desc">'.$description.'</p>
                            </div>';
                        }

                        echo '<div class="link-box">
                            <div class="link-title">'.__('友情链接','mobantu').'</div>
                            <div class="link-list clearfix">
                                '.$html.'
                            </div>
                        </div>';
                    }
	    		?>
	    	</div>
	    </div>
	</div>
</div>
<?php } get_footer();?>