<?php 
	$share_link = get_the_permalink();
	if(is_user_logged_in()){
		global $current_user;
		$share_link = add_query_arg('aff',$current_user->ID,get_permalink());
	}
	echo '<div class="article-shares">';
    echo '<a href="javascript:;" data-url="'. $share_link .'" class="share-weixin"><i class="icon icon-weixin"></i></a><a data-share="qzone" class="share-qzone" data-url="'. $share_link .'"><i class="icon icon-qzone"></i></a><a data-share="weibo" class="share-tsina" data-url="'. $share_link .'"><i class="icon icon-weibo"></i></a><a data-share="qq" class="share-sqq" data-url="'. $share_link .'"><i class="icon icon-qq"></i></a><a data-share="douban" class="share-douban" data-url="'. $share_link .'"><i class="icon icon-douban"></i></a>';
	if(_MBT('post_share_cover_img')){
    	echo '<a href="javascript:;" class="article-cover right" data-s-id="'.get_the_ID().'"><i class="icon icon-fenxiang"></i> '.__('分享海报','mobantu').'<span id="wx-thumb-qrcode" data-url="'.$share_link.'"></span></a>';
    }
    echo '</div>';
?>