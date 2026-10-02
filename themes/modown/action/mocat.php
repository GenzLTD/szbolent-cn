<?php 
require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
global $post_target, $vip_jtext;
if(isset($_GET)){ 
	$html = '';
	$cf = isset($_GET['cf'])?$_GET['cf']:'';
	$o = isset($_GET['o'])?$_GET['o']:'';
	$c = isset($_GET['c'])?$_GET['c']:'';
	$c2 = isset($_GET['c2'])?$_GET['c2']:'';
	$num = isset($_GET['num'])?$_GET['num']:'';

	$cat_class = 'grids';
	$style = _MBT('list_style');
	if($style == 'list') $cat_class = 'lists';
	elseif($style == 'list2') $cat_class = 'lists cols-two';
  elseif($style == 'list3') $cat_class = 'lists cols-three';

	if($cf){
		if($c){
			$args = array(
		        'cat' => $c,
		        'showposts' => $num
		    );
		    $style = get_term_meta($c,'style',true);
		    if($style == 'list') $cat_class = 'lists';
	      elseif($style == 'grid') $cat_class = 'grids';
	      elseif($style == 'grid-audio') $cat_class = 'grids';
	      elseif($style == 'list2') $cat_class = 'lists cols-two';
  			elseif($style == 'list3') $cat_class = 'lists cols-three';
		}else{
			$args = array(
				'post_type' => 'post',
		        'showposts' => $num,
		        'category__not_in' => explode(',', _MBT('home_cats_exclude'))
		    );
		}

		if($o == 'views'){
		    $args['orderby'] = 'meta_value_num';
		    $args['meta_key'] = 'views';
		}elseif($o == 'downs'){
		    $args['orderby'] = 'meta_value_num';
		    $args['meta_key'] = 'down_times';
		}elseif($o == 'colls'){
		    $args['orderby'] = 'meta_value_num';
		    $args['meta_key'] = 'collects';
		}elseif($o == 'fee'){
			$args['meta_key'] = 'down_price';
      $args['meta_query'] = array('key' => 'down_price', 'compare' => '>','value' => '0');
		}elseif($o == 'free'){
			$args['meta_query'] = array(
                'relation' => 'AND',
                array('key' => 'member_down', 'value' => array(4,8,9), 'compare' => 'NOT IN'),
                array(
        			'relation' => 'OR',
        			array('key' => 'down_price', 'value' => ''),
        			array('key' => 'down_price', 'value' => '0')
                )
    		);
		}
	}else{
		if($c2){
			$args = array(
		        'cat' => $c2,
		        'showposts' => $num
		    );
		    $style = get_term_meta($c2,'style',true);
		    if($style == 'list') $cat_class = 'lists';
	      elseif($style == 'grid') $cat_class = 'grids';
	      elseif($style == 'grid-audio') $cat_class = 'grids';
	      elseif($style == 'list2') $cat_class = 'lists cols-two';
  			elseif($style == 'list3') $cat_class = 'lists cols-three';
		}else{
			if($c){
				$args = array(
			        'cat' => $c,
			        'showposts' => $num
			    );
			}else{
				$args = array(
					'post_type' => 'post',
			        'showposts' => $num,
			        'category__not_in' => explode(',', _MBT('home_cats_exclude'))
			    );
			}

	    if($c){
		    $style = get_term_meta($c,'style',true);
		    if($style == 'list') $cat_class = 'lists';
        elseif($style == 'grid') $cat_class = 'grids';
        elseif($style == 'grid-audio') $cat_class = 'grids';
        elseif($style == 'list2') $cat_class = 'lists cols-two';
				elseif($style == 'list3') $cat_class = 'lists cols-three';
	    }
		}
	}

	$args['ignore_sticky_posts'] = 1;
	query_posts($args);
	while ( have_posts() ) : the_post(); 
	if($style == 'grid-audio'){
      $audio = get_post_meta(get_the_ID(),'audio',true);
      $audio_time = get_post_meta(get_the_ID(),'audio_time',true);
      $html .= '<div class="post grid audio" data-audio="'.$audio.'" data-id="'.get_the_ID().'">
          <i class="audio-play"></i>
          <div class="info">
              <a class="title" target="_blank" href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>
          </div>
          <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
              <source src="'.$audio.'" type="audio/mpeg">
          </audio>
          <span class="star-time">00:00</span>
          <div class="time-bar">
              <span class="progressBar"></span>
              <i class="move-color"></i>
              <p class="timetip"></p>
          </div>
          <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
      </div>';
    }else{
		$ts = get_post_meta(get_the_ID(),'down_special',true);
		$tj = get_post_meta(get_the_ID(),'down_recommend',true);
		$sign = get_post_meta(get_the_ID(),'sign',true);
		if($sign){
      $sign_color = get_post_meta(get_the_ID(),'sign_color',true);
      $sign = '<span class="post-sign"'.(($sign_color && $sign_color != '#ff9600')?' style="background:'.$sign_color.'"':'').'>'.$sign.'</span>';
    }
		$tsstyle = '';
		if(_MBT('post_author')) $zz = ' grid-zz'; else $zz = '';
    if($ts && $cat_class != 'lists'){ 
      $ts = ' grid-ts'; 
      $tsstyle = ' style="background-image:url('.MBThemes_thumbnail_full().')"';
    }else $ts = '';
		$erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
    $price=MBThemes_erphpdown_price(get_the_ID());
    $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
    $downtimes = get_post_meta(get_the_ID(),'down_times',true);
    $video = get_post_meta(get_the_ID(),'video_preview',true);
    $down_tuan = get_post_meta(get_the_ID(),'down_tuan',true);
    $title_color = get_post_meta(get_the_ID(),'title_color',true);
    $tag_icon = '';
    $tc = '';
    if($title_color && $title_color != "#000000" && $title_color != "#333333") $tc= ' style="color:'.$title_color.'"';
    if($video) $vd = ' grid-vd';else $vd='';
    $noimg = '';
    if(_MBT('post_list_img') && !MBThemes_thumbnail_has()){
      $noimg = ' noimg';
    }
		$html .= '<div class="post grid'.$ts.$zz.$vd.$noimg.'"'.($video?' data-video="'.$video.'"':'').' data-id="'.get_the_ID().'"'.$tsstyle.'>
		  <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
		    <img src="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">';
		  if($video){
        $video_type = get_post_meta(get_the_ID(),'video_type',true);
        if($video_type){
          $html .= '<div class="grid-video"><iframe id="video-'.get_the_ID().'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="allowfullscreen" src=""></iframe></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
        }else{
          $html .= '<div class="grid-video"><video id="video-'.get_the_ID().'" autoplay="autoplay" muted="true" preload="none" poster="'.MBThemes_thumbnail().'" src=""></video></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
        }
      }
		$html .='</a>';
    if(_MBT('post_cat') && _MBT('post_cat_lefttop')){ 
      $html .= '<div class="img-cat">'; 
      if(function_exists('modown_categorys_before')) $html .= modown_categorys_before(); 
      $html .= MBThemes_categorys();
      $html .= '</div>';
    }
    $html .='</div>';
    $html .='<div class="con">';
		if(_MBT('post_cat') && !_MBT('post_cat_lefttop')){ 
			$html .= '<div class="cat">'; 
			if(function_exists('modown_categorys_before')) $html .= modown_categorys_before(); 
			$html .= MBThemes_categorys();
			if(_MBT('post_price') && _MBT('post_price_cat')){
        if($down_tuan && function_exists('get_erphpdown_tuan_num')){
            $down_tuan_price=get_post_meta(get_the_ID(), 'down_tuan_price', true);
            $html .= '<span class="price"><span class="fee"><i class="icon icon-money"></i> '.$down_tuan_price.'</span></span>';
        }else{
          if(ERPHPDOWN_IS_ACTIVE){
            $erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
            $price=MBThemes_erphpdown_price(get_the_ID());
            $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
            $downtimes = get_post_meta(get_the_ID(),'down_times',true);
            if($erphp_down && $erphp_down != '4'){
                if(is_user_logged_in() || !_MBT('hide_user_all')){
                    $html .= '<span class="price">';
                    if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
                        if($price){
                            $html .= '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                        }
                        if(_MBT('post_vip_free_fs')){
                            $tag_icon = 'vip';
                        }
                    }elseif($memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
                        $html .= '<span class="fee vip-tag">VIP</span>';
                        $tag_icon = 'vip';
                    }elseif($price){
                        $html .= '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                    }else{ 
                        $html .= '<span class="fee free-tag">'.__('免费','mobantu').'</span>';
                        $tag_icon = 'free';
                    }
                    $html .= '</span>';
                }
            }
          }
        }
      }
			$html .= '</div>';
		}
		if(_MBT('post_tag')){
      $html .= '<div class="tag">';
      $posttags = get_the_tags();
      if ($posttags) {
          $i = 1;
          foreach($posttags as $tag) {
              if($i <= 3){
                  $html .= '<a href="'.esc_attr( get_tag_link( $tag->term_id ) ).'" target="_blank">'.$tag->name . '</a>'; 
                  $i ++;
              }else{
                  break;
              }
          }
      }
      $html .= '</div>';
    }
		$html .= '<h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'"'.$tc.'>'.$sign.get_the_title().'</a></h3>';
		//if(function_exists('modown_grid_custom_field')) $html .= modown_grid_custom_field();
		$html .= '<div class="excerpt">'.MBThemes_get_excerpt(80).'</div>';

    $html .= '<div class="grid-meta">';
    if($down_tuan && function_exists('get_erphpdown_tuan_num')){
        $down_tuan_num=get_post_meta(get_the_ID(), 'down_tuan_num', true);
        $down_tuan_price=get_post_meta(get_the_ID(), 'down_tuan_price', true);
        $tnum = get_erphpdown_tuan_num(get_the_ID());
        $percent = get_erphpdown_tuan_percent(get_the_ID(),$tnum);
        $html .= '<div class="erphpdown-tuan-process"><div class="line"><span style="width:'.$percent.'%"></span></div><div class="data">'.$percent.'%</div>'.'</div>';
        if(!_MBT('post_price')){
          $html .= '<span class="price"><span class="fee"><i class="icon icon-money"></i> '.$down_tuan_price.'</span></span>';
        }
        $tag_icon = 'tuan';
    }else{
      if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( MBThemes_post_date() ).'</span>';
      if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
      if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
      if(_MBT('post_downloads')) $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';
      if(ERPHPDOWN_IS_ACTIVE && $erphp_down && $erphp_down != '4'){
        if(!_MBT('post_price')){
          $html .= '<span class="price">';
          if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
              if($price){
                  $html .= '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
              }
              if(_MBT('post_vip_free_fs')){
                  $tag_icon = 'vip';
              }
          }elseif($memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
              $html .= '<span class="fee vip-tag">VIP</span>';
              $tag_icon = 'vip';
          }elseif($price){
            $html .= '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
          }else{
            $html .= '<span class="fee free-tag">'.__('免费','mobantu').'</span>';
            $tag_icon = 'free';
          }
          $html .= '</span>';
        }else{
          if((_MBT('post_vip_free_fs') && ($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7')) || $memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
            $tag_icon = 'vip';
          }elseif(!$price){
            $tag_icon = 'free';
          }
        }
      }
    }
    $html .= '</div>';
    
		if(_MBT('post_author')){
      $html .= '<div class="grid-author">
        <a target="_blank" href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'"  class="avatar-link">'.get_avatar(get_the_author_meta( 'ID' )).'<span class="author-name">'.get_the_author().'</span></a>
        <span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( MBThemes_post_date() ).'</span>
      </div>';
    }
    $html .='</div>';
    if($tag_icon == 'tuan'){$html .= '<span class="vip-tag tuan-tag"><i>'.__('拼团','mobantu').'</i></span>';}elseif($tag_icon == 'vip'){$html .= '<span class="vip-tag"><i>'.$vip_jtext.'</i></span>';}elseif($tag_icon == 'free'){$html .= '<span class="vip-tag free-tag"><i>'.__('免费','mobantu').'</i></span>';}
    if($tj){$html .= '<span class="recommend-tag">'.__('荐','mobantu').'</span>';}
		$html .= '</div>';
	}
	endwhile;wp_reset_query(); 
	echo $html;
}
exit;