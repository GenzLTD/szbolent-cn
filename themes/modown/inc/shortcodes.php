<?php
add_shortcode('moad','MBThemes_shortcode_ad');
function MBThemes_shortcode_ad($atts){
  $atts = shortcode_atts( array(
    'bgcolor' => '',
    'img' => '',
    'fullwidth' => 0,
    'mb' => '',
    'link' => '',
  ), $atts, 'moad' );
  $style = '';
  if($atts['bgcolor']){
    $style .= 'background-color:'.$atts['bgcolor'].';';
  }
  if($atts['mb'] != ''){
    $style .= 'margin-bottom:'.$atts['mb'].'px;';
  }
  if($atts['fullwidth']){
    $html = '<div class="moad fullwidth" style="'.$style.'"><a href="'.$atts['link'].'" target="_blank"><img src="'.$atts['img'].'"></a></div>';
  }else{
    $html = '<div class="moad" style="'.$style.'"><div class="container"><a href="'.$atts['link'].'" target="_blank"><img src="'.$atts['img'].'"></a></div></div>';
  }
  return $html;
}

add_shortcode('mocat','MBThemes_shortcode_cat');
function MBThemes_shortcode_cat($atts){
  if(!is_page() && !is_home()){
    return '';
  }
  global $post_target, $vip_jtext;
	$atts = shortcode_atts( array(
    'id' => '',
    'ids' => '',
    'posts' => '',
    'num' => 8,
    'title' => '',
    'icon' => '',
    'left' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'sort' => 0,
    'recommend' => 0,
    'sticky' => 0,
    'orderby' => 'date',
    'text' => __('查看更多','mobantu'),
    'link' => '',
    'child' => 0,
    'child-num' => 5,
    'water' => 0,
    'cols' => 3,
    'style' => ''
  ), $atts, 'mocat' );
  $title = $atts['icon']?('<icon class="'.$atts['icon'].'"></icon>'):'';
  $title .= $atts['title']?$atts['title']:get_cat_name($atts['id']);
  $css = '';
  $cat_class = 'grids';
  $style = _MBT('list_style');
  if($style == 'list') $cat_class = 'lists';
  elseif($style == 'list2') $cat_class = 'lists cols-two';
  elseif($style == 'list3') $cat_class = 'lists cols-three';

  if($atts['id']) {
    $category = get_term( $atts['id'], 'category' );
    $moid = ' id="mocat-'.$atts['id'].'"';
    $style = get_term_meta($atts['id'],'style',true);
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    elseif($style == 'list2') $cat_class = 'lists cols-two';
    elseif($style == 'list3') $cat_class = 'lists cols-three';
    else{
      $style = get_term_meta(MBThemes_parent_cid($atts['id']),'style',true);
      if($style == 'list') $cat_class = 'lists';
      elseif($style == 'list2'){
        $cat_class = 'lists cols-two';
      }elseif($style == 'list3'){
        $cat_class = 'lists cols-three';
      }
    }
    if(!$atts['water']){
      $timthumb_height = get_term_meta($atts['id'],'timthumb_height',true);
      if($timthumb_height){
        $css = '<style>#mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
          @media (max-width: 1230px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
          }
          @media (max-width: 1024px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
          }
          @media (max-width: 925px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
          }
          @media (max-width: 768px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
          }
          @media (max-width: 620px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
          }
          @media (max-width: 480px){
            #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"180":(180*$timthumb_height/285)).'px;}
          }
          </style>';
      }
    }
  }else {
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    elseif($style == 'list2') $cat_class = 'lists cols-two';
    elseif($style == 'list3') $cat_class = 'lists cols-three';
    $category = '';
    if($atts['ids']){
      $moid = ' id="mocat-'.str_replace(",","",$atts['ids']).'s"';
    }else{
      $moid = ' id="mocat-new"';
    }
  }
  $html = '<div class="mocat'.($atts['new']?' mocat-new':'').($atts['left']?' align-left':'').($atts['water']?' water':'').'"'.$moid.'>'.$css.'<div class="container">';

  if(!$category && !$atts['title']){

  }else{
    if($atts['new'] == '2'){
      $html .= '<div class="mocat-title-nav"><a href="'.add_query_arg('o','recommend',$atts['link']).'"><span>'.__('站长推荐','mobantu').'</span></a><a href="'.$atts['link'].'" class="active"><span>'.$title.'<i>NEW</i></span></a><a href="'.add_query_arg('o','view',$atts['link']).'"><span>'.__('最热浏览','mobantu').'</span></a></div>';
    }else{
      $html .= '<h2><span>'.$title;
      if($atts['new'] == '1'){
      	$html .= '<i>NEW</i>';
      }
      $html .= '</span></h2>';
    }
  }

  if($atts['desc']){
    $html .= '<p class="desc'.(($atts['child'] && $atts['id']) || (!$category && $atts['ids']) ?' desc-child':'').'">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc'.($atts['child'] && $atts['id'] ?' desc-child':'').'">'.$category->description.'</p>';
    }
  }

  if($atts['child'] && $atts['id']){
  	$category = get_term_by('id',$atts['id'],'category');
    if($category){
    	$cat_childs = get_categories("parent=".$category->term_id."&hide_empty=1&depth=1");  
  		if($cat_childs){
  			$html .= '<ul class="child"><li><a href="javascript:;" class="active" data-c="'.$atts['id'].'" data-c2="0" data-num="'.$atts['num'].'">'.__('全部','mobantu').'</a></li>';
  			$i = 1;
  			foreach ($cat_childs as $term) {
  				if($i > $atts['child-num']) $html .= '';
  				else $html .= '<li><a href="javascript:;" data-c="'.$atts['id'].'" data-c2="'.$term->term_id.'" data-num="'.$atts['num'].'">'.$term->name.'</a></li>';
  				$i ++;
  			}
  			$html .= '</ul>';
  		}
    }
  }elseif(!$category && $atts['ids']){
    $html .= '<ul class="child"><li><a href="javascript:;" class="active" data-c="" data-c2="0" data-link="'.$atts['link'].'" data-num="'.$atts['num'].'">'.__('全部','mobantu').'</a></li>';
    $cat_ids = explode(',', $atts['ids']);
    foreach ($cat_ids as $term_id) {
      $html .= '<li><a href="javascript:;" data-c="'.$term_id.'" data-c2="0" data-link="'.get_category_link($term_id).'" data-num="'.$atts['num'].'">'.get_term( $term_id, 'category' )->name.'</a></li>';
    }
    $html .= '</ul>';
  }elseif(!$category && $atts['sort']){
    $html .= '<ul class="child child-sort"><li><a href="'.add_query_arg('o','',$atts['link']).'" class="active">'.__('最新','mobantu').'</a></li>';
    $html .= '<li><a href="'.add_query_arg('o','update',$atts['link']).'">'.__('更新','mobantu').'</a></li>';
    $html .= '<li><a href="'.add_query_arg('o','recommend',$atts['link']).'">'.__('推荐','mobantu').'</a></li>';
    $html .= '<li><a href="'.add_query_arg('o','download',$atts['link']).'">'.__('下载','mobantu').'</a></li>';
    $html .= '<li><a href="'.add_query_arg('o','view',$atts['link']).'">'.__('浏览','mobantu').'</a></li>';
    $html .= '<li><a href="'.add_query_arg('o','zan',$atts['link']).'">'.__('点赞','mobantu').'</a></li>';
    $html .= '<li><a href="'.add_query_arg('o','comment',$atts['link']).'">'.__('评论','mobantu').'</a></li>';
    $html .= '<li><a href="'.add_query_arg('o','rand',$atts['link']).'">'.__('随机','mobantu').'</a></li>';
    $html .= '</ul>';
  }

  if($atts['cols'] == 2){
    $cat_class .= ' cols-two';
  }

  if($atts['water']){
    $cat_class .= ' waterfall';
  }

  $html .= '<div class="'.$cat_class.' clearfix">';
  if($atts['id']){ 
    $args = array(
      'post_type'        => 'post',
  		'cat'              => $atts['id'],
  		'showposts'        => $atts['num'],
      'order'            => 'DESC'
  	);
  }else{
    $args = array(
      'post_type'        => 'post',
      'showposts'        => $atts['num'],
      'category__not_in' => explode(',', _MBT('home_cats_exclude')),
      'order'            => 'DESC'
    );
  }

  if(!$atts['sticky']){
    $args['ignore_sticky_posts'] = 1;
  }

  if($atts['orderby'] == 'views'){
    $args['meta_key'] = 'views';
    $args['orderby'] = 'meta_value_num';
  }elseif($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }

  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  $lz = (_MBT('lazyload') && !$atts['water'])?1:0;
  $price_show = 0;
  if(!_MBT('post_price')){
    $price_show = 1;
  }else{
    $price_show = 2;
  }
  
	query_posts($args);
	while (have_posts()) : the_post();
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
  		$html .= '<div class="post grid'.$ts.$vd.$zz.$noimg.'"'.($video?' data-video="'.$video.'"':'').' data-id="'.get_the_ID().'"'.$tsstyle.'>
  		  <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
  		    <img'.($lz?' src="'.(_MBT('thumbnail_loading')?_MBT('thumbnail_loading'):THEME_URI.'/static/img/thumbnail.png').'"':'').' '.($lz?'data-src':'src').'="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">';
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
          if($price_show){
            $html .= '<span class="price"><span class="fee"><i class="icon icon-money"></i> '.$down_tuan_price.'</span></span>';
          }
          $tag_icon = 'tuan';
      }else{
		    if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( MBThemes_post_date() ).'</span>';
		    if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
        if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
        if(_MBT('post_downloads')){$downtimes = get_post_meta(get_the_ID(),'down_times',true); $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';}
		    if(ERPHPDOWN_IS_ACTIVE && $erphp_down && $erphp_down != '4'){
            if($price_show == '1'){
                $html .= '<span class="price">';
                if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
                    if($price){
                        $html .= '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                    }
                    $tag_icon = 'vip';
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
            }elseif($price_show == '2'){
              if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7' || $memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
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
	endwhile; wp_reset_query(); 
	$html .= '</div>';
	if($atts['more']) {
    if($atts['orderby'] && $atts['orderby'] != 'date'){
      $linka = $atts['link']?$atts['link']:get_category_link($atts['id']);
      $oo = $atts['orderby'];
      if($atts['orderby'] == 'views'){
        $oo = 'view';
      }elseif($atts['orderby'] == 'modified'){
        $oo = 'update';
      }elseif($atts['orderby'] == 'downs'){
        $oo = 'download';
      }
      $html .= '<div class="more"><a href="'.add_query_arg('o',$oo,$linka).'" target="_blank">'.$atts['text'].'</a></div>';
    }else{
      $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_category_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
    }
  }
  $html .= '</div></div>';
  return $html;
}

add_shortcode('motag','MBThemes_shortcode_tag');
function MBThemes_shortcode_tag($atts){
  if(!is_page() && !is_home()){
    return '';
  }
  global $post_target, $vip_jtext;
	$atts = shortcode_atts( array(
    'id' => '',
    'num' => 8,
    'title' => '',
    'icon' => '',
    'left' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'recommend' => 0,
    'orderby' => 'date',
    'text' => __('查看更多','mobantu'),
    'link' => '',
    'water' => 0,
    'cols' => 3,
    'style' => ''
  ), $atts, 'motag' );
  $title = $atts['icon']?('<icon class="'.$atts['icon'].'"></icon>'):'';
  $title .= $atts['title']?$atts['title']:get_tag($atts['id'])->name;
  $css = '';
  $cat_class = 'grids';
  $style = _MBT('list_style');
  if($style == 'list') $cat_class = 'lists';
  elseif($style == 'list2') $cat_class = 'lists cols-two';
  elseif($style == 'list3') $cat_class = 'lists cols-three';

  if($atts['id']) {
    $category = get_term( $atts['id'], 'post_tag' );
    $moid = ' id="mocat-'.$atts['id'].'"';
    $style = get_term_meta($atts['id'],'style',true);
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'list2') $cat_class = 'lists cols-two';
    elseif($style == 'list3') $cat_class = 'lists cols-three';
  }else {
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    elseif($style == 'list2') $cat_class = 'lists cols-two';
    elseif($style == 'list3') $cat_class = 'lists cols-three';
    $category = '';
    $moid = ' id="mocat-rand-'.rand(1000,9999).'"';
  }
  $html = '<div class="mocat'.($atts['left']?' align-left':'').($atts['water']?' water':'').'"'.$moid.'>'.$css.'<div class="container">';

  if(!$category && !$atts['title']){

  }else{
    $html .= '<h2><span>'.$title;
    if($atts['new']){
    	$html .= '<i>NEW</i>';
    }
    $html .= '</span></h2>';
  }

  if($atts['desc']){
    $html .= '<p class="desc">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc">'.$category->description.'</p>';
    }
  }

  if($atts['cols'] == 2){
    $cat_class .= ' cols-two';
  }

  if($atts['water']){
    $cat_class .= ' waterfall';
  }

  $html .= '<div class="'.$cat_class.' clearfix">';
  if($atts['id']){ 
    $args = array(
      'post_type'        => 'post',
  		'tag_id'              => $atts['id'],
  		'showposts'        => $atts['num'],
      'order'            => 'DESC',
  		'ignore_sticky_posts' => 1
  	);
  }else{
    $args = array(
      'post_type'        => 'post',
      'showposts'        => $atts['num'],
      'category__not_in' => explode(',', _MBT('home_cats_exclude')),
      'order'            => 'DESC'
    );
  }
  if($atts['orderby'] == 'views'){
    $args['meta_key'] = 'views';
    $args['orderby'] = 'meta_value_num';
  }elseif($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  $lz = (_MBT('lazyload') && !$atts['water'])?1:0;
  $price_show = 0;
  if(!_MBT('post_price')){
    $price_show = 1;
  }else{
    $price_show = 2;
  }
  
	query_posts($args);
	while (have_posts()) : the_post();
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
		$html .= '<div class="post grid'.$ts.$zz.$noimg.'"'.($video?' data-video="'.$video.'"':'').' data-id="'.get_the_ID().'"'.$tsstyle.'>
		  <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
		    <img'.($lz?' src="'.(_MBT('thumbnail_loading')?_MBT('thumbnail_loading'):THEME_URI.'/static/img/thumbnail.png').'"':'').' '.($lz?'data-src':'src').'="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">';
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
        if($price_show){
          $html .= '<span class="price"><span class="fee"><i class="icon icon-money"></i> '.$down_tuan_price.'</span></span>';
        }
        $tag_icon = 'tuan';
    }else{
      if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( MBThemes_post_date() ).'</span>';
      if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
      if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
      if(_MBT('post_downloads')){$downtimes = get_post_meta(get_the_ID(),'down_times',true); $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';}
      if(ERPHPDOWN_IS_ACTIVE && $erphp_down && $erphp_down != '4'){
        if($price_show == '1'){
            $html .= '<span class="price">';
            if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
                if($price){
                    $html .= '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                }
                $tag_icon = 'vip';
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
        }elseif($price_show == '2'){
          if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7' || $memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
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

	endwhile; wp_reset_query(); 
	$html .= '</div>';
	if($atts['more']) $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_tag_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
    $html .= '</div></div>';
    return $html;
}

add_shortcode('motopic','MBThemes_shortcode_topic');
function MBThemes_shortcode_topic($atts){
  if(!is_page() && !is_home()){
    return '';
  }
  global $post_target, $vip_jtext;
  $atts = shortcode_atts( array(
    'id' => '',
    'num' => 8,
    'title' => '',
    'icon' => '',
    'left' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'recommend' => 0,
    'orderby' => 'date',
    'text' => __('查看更多','mobantu'),
    'link' => '',
    'water' => 0,
    'cols' => 3,
    'style' => ''
  ), $atts, 'motopic' );
  $title = $atts['icon']?('<icon class="'.$atts['icon'].'"></icon>'):'';
  $title .= $atts['title']?$atts['title']:get_term( $atts['id'], 'topic' )->name;
  $css = '';
  $cat_class = 'grids';
  $style = _MBT('list_style');
  if($style == 'list') $cat_class = 'lists';
  elseif($style == 'list2') $cat_class = 'lists cols-two';
  elseif($style == 'list3') $cat_class = 'lists cols-three';

  if($atts['id']) {
    $category = get_term( $atts['id'], 'topic' );
    $moid = ' id="mocat-'.$atts['id'].'"';
    $style = get_term_meta($atts['id'],'style',true);
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'list2') $cat_class = 'lists cols-two';
    elseif($style == 'list3') $cat_class = 'lists cols-three';
  }else {
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    elseif($style == 'list2') $cat_class = 'lists cols-two';
    elseif($style == 'list3') $cat_class = 'lists cols-three';
    $category = '';
    $moid = ' id="mocat-rand-'.rand(1000,9999).'"';
  }
  $html = '<div class="mocat'.($atts['left']?' align-left':'').($atts['water']?' water':'').'"'.$moid.'>'.$css.'<div class="container">';

  if(!$category && !$atts['title']){

  }else{
    $html .= '<h2><span>'.$title;
    if($atts['new']){
      $html .= '<i>NEW</i>';
    }
    $html .= '</span></h2>';
  }

  if($atts['desc']){
    $html .= '<p class="desc">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc">'.$category->description.'</p>';
    }
  }

  if($atts['cols'] == 2){
    $cat_class .= ' cols-two';
  }

  if($atts['water']){
    $cat_class .= ' waterfall';
  }

  $html .= '<div class="'.$cat_class.' clearfix">';
  if($atts['id']){ 
    $args = array(
      'post_type' => 'post',
      'tax_query' => array(
          array(
              'taxonomy' => 'topic',
              'field'    => 'term_id',
              'terms'    => $atts['id'],
          ),
      ),
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
  }else{
    $args = array(
      'post_type' => 'post',
      'showposts'        => $atts['num'],
      'category__not_in' => explode(',', _MBT('home_cats_exclude')),
      'order'            => 'DESC'
    );
  }
  if($atts['orderby'] == 'views'){
    $args['meta_key'] = 'views';
    $args['orderby'] = 'meta_value_num';
  }elseif($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  $lz = (_MBT('lazyload') && !$atts['water'])?1:0;
  $price_show = 0;
  if(!_MBT('post_price')){
    $price_show = 1;
  }else{
    $price_show = 2;
  }
  
  query_posts($args);
  while (have_posts()) : the_post();
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
    $html .= '<div class="post grid'.$ts.$zz.$noimg.'"'.($video?' data-video="'.$video.'"':'').' data-id="'.get_the_ID().'"'.$tsstyle.'>
      <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
        <img'.($lz?' src="'.(_MBT('thumbnail_loading')?_MBT('thumbnail_loading'):THEME_URI.'/static/img/thumbnail.png').'"':'').' '.($lz?'data-src':'src').'="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">';
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
        if($price_show){
          $html .= '<span class="price"><span class="fee"><i class="icon icon-money"></i> '.$down_tuan_price.'</span></span>';
        }
        $tag_icon = 'tuan';
    }else{
      if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( MBThemes_post_date() ).'</span>';
      if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
      if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
      if(_MBT('post_downloads')){$downtimes = get_post_meta(get_the_ID(),'down_times',true); $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';}
      if(ERPHPDOWN_IS_ACTIVE && $erphp_down && $erphp_down != '4'){
        if($price_show == '1'){
            $html .= '<span class="price">';
            if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7'){
                if($price){
                    $html .= '<span class="fee"><i class="icon icon-money"></i> '.$price.'</span>';
                }
                $tag_icon = 'vip';
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
        }elseif($price_show == '2'){
          if($memberDown == '3' || $memberDown == '16' || $memberDown == '6' || $memberDown == '7' || $memberDown == '4' || $memberDown == '15' || $memberDown == '8' || $memberDown == '9'){
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

  endwhile; wp_reset_query(); 
  $html .= '</div>';
  if($atts['more']) $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_tag_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
    $html .= '</div></div>';
    return $html;
}

add_shortcode('mocat2','MBThemes_shortcode_cat2');
function MBThemes_shortcode_cat2($atts){
  if(!is_page() && !is_home()){
    return '';
  }
  global $post_target;
  $atts = shortcode_atts( array(
    'id' => '',
    'cids' => '',
    'title' => '',
    'icon' => '',
    'left' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'recommend' => 0,
    'sticky' => 0,
    'orderby' => 'date',
    'text' => __('查看更多','mobantu'),
    'link' => ''
  ), $atts, 'mocat' );
  $title = $atts['icon']?('<icon class="'.$atts['icon'].'"></icon>'):'';
  $title .= $atts['title']?$atts['title']:get_cat_name($atts['id']);

  $category = get_term( $atts['id'], 'category' );
  $moid = ' id="mocat-'.$atts['id'].'"';
  $html = '<div class="mocat'.($atts['left']?' align-left':'').'"'.$moid.'><div class="container">';

  if(!$category && !$atts['title']){

  }else{
    $html .= '<h2><span>'.$title;
    if($atts['new']){
      $html .= '<i>NEW</i>';
    }
    $html .= '</span></h2>';
  }

  if($atts['desc']){
    $html .= '<p class="desc desc-child">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc desc-child">'.$category->description.'</p>';
    }
  }

  $html .= '<div class="cat2s clearfix">';
  if($atts['cids']){
    $cids = explode(',',$atts['cids']);
    foreach($cids as $cid){
      $category2 = get_term( $cid, 'category' );
      $thumb = get_term_meta($cid,'thumb_img',true);
      $html .= '<div class="cat2s-item"><h4>'.$category2->name.'</h4><ul>';
      $args = array(
        'post_type' => 'post',
        'cat' => $cid,
        'showposts'        => 5,
        'order'            => 'DESC',
        'ignore_sticky_posts' => 1
      );
      if($atts['orderby'] == 'views'){
        $args['meta_key'] = 'views';
        $args['orderby'] = 'meta_value_num';
      }elseif($atts['orderby'] == 'downs'){
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = 'down_times';
      }elseif($atts['orderby'] == 'comment'){
        $args['orderby'] = 'comment_count';
      }elseif($atts['orderby'] == 'rand'){
        $args['orderby'] = 'rand';
      }else{
        $args['orderby'] = $atts['orderby'];
      }
      
      if($atts['recommend']){
        $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
      }
      
      query_posts($args);
      while (have_posts()) : the_post();
        $html .= '<li><a href="'.get_permalink().'" target="'.$post_target.'">'.get_the_title().'</a></li>';
      endwhile; wp_reset_query();
      $html .= '</ul><a href="'.get_category_link($cid).'" target="_blank" class="cat2s-item-thumb"><img src="'.$thumb.'" alt="'.$category2->name.'" /></a></div>';
    }
  } 

  $html .= '</div>';
  if($atts['more']) $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_tag_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
  $html .= '</div></div>';
  return $html;
}

add_shortcode('mocats','MBThemes_shortcode_cats');
function MBThemes_shortcode_cats($atts, $content=null){
  if(!is_page() && !is_home()){
    return '';
  }
  global $post_target;
  $atts = shortcode_atts( array(
    'cols' => 3,
    'title' => '',
    'desc' => '',
    'text' => __('查看更多','mobantu'),
    'link' => '',
    'more' => 0
  ), $atts, 'mocats' );
  $cat_class = '';
  if($atts['cols'] == 2){
    $cat_class = ' cols-two';
  }elseif($atts['cols'] == 4){
    $cat_class = ' cols-four';
  }

  $title = '';
  if($atts['title']){
    $title .= '<h2><span>'.$atts['title'].'</span></h2>';
  }

  $html = '<div class="mocat mocats'.$cat_class.'" id="mocats-'.rand(1000,9999).'"><div class="container">'.$title;
  if($atts['desc']){
    $html .= '<p class="desc">'.$atts['desc'].'</p>';
  }
  $html .= '<div class="molis clearfix">'.do_shortcode($content).'</div>';
  if($atts['more']) $html .= '<div class="more"><a href="'.$atts['link'].'" target="_blank">'.$atts['text'].'</a></div>';
  $html .='</div></div>';
  return $html;
}

add_shortcode('moli','MBThemes_shortcode_li');
function MBThemes_shortcode_li($atts){
  if(!is_page() && !is_home()){
    return '';
  }
  global $post_target;
  $atts = shortcode_atts( array(
    'id' => '',
    'recommend' => 0,
    'orderby' => 'date',
    'title' => '',
    'desc' => '',
    'link' => '',
    'num' => 8
  ), $atts, 'moli' );

  $title = $atts['title']?$atts['title']:get_cat_name($atts['id']);
  $banner_archive_img = '';$bg = '';
  if($atts['id']) {
    $category = get_term( $atts['id'], 'category' );
    $args = array(
      'post_type'        => 'post',
      'cat'              => $atts['id'],
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
    if($category){
      $desc = $atts['desc']?$atts['desc']:$category->description;
    }else{
      $desc = $atts['desc'];
    }
    if($desc) $desc = '<p class="des">'.$desc.'</p>';
    $banner_img = get_term_meta($atts['id'],'banner_img',true);
    if($banner_img){
        $banner_archive_img = $banner_img;
    }else{
        if(_MBT('banner_archive_img')){
            $banner_archive_img = _MBT('banner_archive_img');
        }
    }
  }else{
    if(_MBT('banner_archive_img')){
        $banner_archive_img = _MBT('banner_archive_img');
    }
    $desc = $atts['desc'];
    if($desc) $desc = '<p class="des">'.$desc.'</p>';
    $args = array(
      'post_type'        => 'post',
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
  }

  if($atts['orderby'] == 'views'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'views';
  }if($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  if($banner_archive_img) $bg = 'style="background: url('.$banner_archive_img.');"';

  $html = '<div class="moli"><div class="moli-header"'.$bg.'><h3>'.$title.'</h3>'.$desc.'<a href="'.($atts['link']?$atts['link']:get_category_link($atts['id'])).'" target="_blank"></a></div><ul>';
  query_posts($args);
  $i = 1;
  while (have_posts()) : the_post();
    $title_color = get_post_meta(get_the_ID(),'title_color',true);
    $tc = '';
    if($title_color && $title_color != "#000000" && $title_color != "#333333") $tc= ' style="color:'.$title_color.'"';
    $html .= '<li><i>'.$i.'</i><a href="'.get_permalink().'" target="'.$post_target.'"'.$tc.'>'.get_the_title().'</a><span><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span></li>';
    $i ++;
  endwhile; wp_reset_query();
  $html .= '</ul></div>';
  return $html;
}

add_shortcode("post","MBThemes_post_shortcode");
function MBThemes_post_shortcode( $atts, $content=null ){
  $html = '';
  $atts = shortcode_atts( array(
    'id' => '',
    'title' => ''
  ), $atts, 'post' );

  if($atts['id']){
    $cpost = get_post($atts['id']);
    if($cpost){
      $ctitle = $atts['title']?$atts['title']:get_the_title($atts['id']);
      $html = '<div class="article-post-minibox clearfix"><a href="'.get_permalink($atts['id']).'" target="_blank"><img src="'.MBThemes_thumbnail_post($atts['id']).'" alt="'.$ctitle.'" /></a><a href="'.get_permalink($atts['id']).'" target="_blank" class="ctitle">'.$ctitle.'</a><div class="cmeta"><i class="icon icon-time"></i> '.MBThemes_timeago( MBThemes_post_date() ).'</div></div>';
    }
  }
  return $html;
}

function MBThemes_shortcode_img($atts, $content=null, $code="") {
    $return = '<img class="alignnone" src="';
    $return .= htmlspecialchars($content);
    $return .= '" alt="" />';
    return $return;
}
add_shortcode('img' , 'MBThemes_shortcode_img' );

function MBThemes_shortcode_code($atts, $content=null, $code="") {
    $content = htmlspecialchars($content);
    $return = '<div class="code-highlight"><pre><code class="hljs">';
    $return .= ltrim($content, '\n');
    $return .= '</code></pre></div>';
    return $return;
}
add_shortcode('code' , 'MBThemes_shortcode_code' );

add_shortcode("ckplayer","MBThemes_ckplayer_shortcode");
function MBThemes_ckplayer_shortcode( $atts, $content=null ){
    $nonce = wp_create_nonce(rand(10,1000));
    if(is_singular() && !is_front_page()){
      return '<script src="'.get_bloginfo('template_url').'/module/ckplayer/hls.js/hls.min.js"></script><link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_url').'/module/ckplayer/css/ckplayer.css"><script src="'.get_bloginfo('template_url').'/module/ckplayer/js/ckplayer.min.js"></script><div id="ckplayer-video-'.$nonce.'" class="ckplayer-video ckplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($content)).'"></div>';
    }else{
      return '<div class="container"><script src="'.get_bloginfo('template_url').'/module/ckplayer/hls.js/hls.min.js"></script><link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_url').'/module/ckplayer/css/ckplayer.css"><script src="'.get_bloginfo('template_url').'/module/ckplayer/js/ckplayer.min.js"></script><div id="ckplayer-video-'.$nonce.'" class="ckplayer-video ckplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($content)).'"></div></div>';
    }
}

add_shortcode("fplayer","MBThemes_fplayer_shortcode");
function MBThemes_fplayer_shortcode( $atts, $content=null ){
    $nonce = wp_create_nonce(rand(10,1000));
    if(is_singular() && !is_front_page()){
      return '<script src="'.get_bloginfo('template_url').'/module/fplayer/fplayer.min.js"></script><div id="fplayer-video-'.$nonce.'" class="fplayer-video fplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($content)).'"></div>';
    }else{
      return '<div class="container"><script src="'.get_bloginfo('template_url').'/module/fplayer/fplayer.min.js"></script><div id="fplayer-video-'.$nonce.'" class="fplayer-video fplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($content)).'"></div></div>';
    }
}

add_shortcode("dplayer","MBThemes_dplayer_shortcode");
function MBThemes_dplayer_shortcode( $atts, $content=null ){
    $nonce = wp_create_nonce(rand(10,1000));
    if(is_singular() && !is_front_page()){
      return '<script src="'.get_bloginfo('template_url').'/module/dplayer/hls.min.js"></script><script src="'.get_bloginfo('template_url').'/module/dplayer/flv.min.js"></script><script src="'.get_bloginfo('template_url').'/module/dplayer/dplayer.min.js"></script><div id="dplayer-video-'.$nonce.'" class="dplayer-video dplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($content)).'"></div>';
    }else{
      return '<div class="container"><script src="'.get_bloginfo('template_url').'/module/dplayer/hls.min.js"></script><script src="'.get_bloginfo('template_url').'/module/dplayer/flv.min.js"></script><script src="'.get_bloginfo('template_url').'/module/dplayer/dplayer.min.js"></script><div id="dplayer-video-'.$nonce.'" class="dplayer-video dplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-key="'.base64_encode(trim($content)).'"></div></div>';
    }
}

add_shortcode("login","MBThemes_login_shortcode");
function MBThemes_login_shortcode( $atts, $content=null ){
    if(is_user_logged_in()){
      return '<p>'.do_shortcode($content).'</p>';
    }else{
      return '<div class="modown-login">'.__('此内容 <a href="javascript:;" class="signin-loader">登录</a> 后可见！','mobantu').'</div>';
    }
}

add_shortcode("reply","MBThemes_reply_shortcode");
function MBThemes_reply_shortcode( $atts, $content=null ){
  global $wpdb;

  extract(shortcode_atts(array("notice" => '<div class="modown-reply">'.__('此内容 <a href="javascript:scrollToTop(\'#respond\',-120);">评论</a> 本文后<span>刷新页面</span>可见！','mobantu').'</div>'), $atts));   
  $email = null;   
  $user_ID = (int) wp_get_current_user()->ID;
  $post_id = get_the_ID();

  if ($user_ID > 0) {   
      $email = get_userdata($user_ID)->user_email;   
      $admin_email = get_option('admin_email');  
      if ($email == $admin_email) {   
        return '<p>'.do_shortcode($content).'</p>';
      }else{
        $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `user_id`='{$user_ID}' LIMIT 1";
        if ($wpdb->get_results($query)) {   
          return '<p>'.do_shortcode($content).'</p>';
        }else{
          return $notice;  
        }
      } 
  } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {   
      $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);   
  } else {   
      return $notice;   
  }  

  if (empty($email)) {   
      return $notice;   
  }  
     
  $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";   
  if ($wpdb->get_results($query)) {   
      return '<p>'.do_shortcode($content).'</p>';
  }

}

add_action( 'admin_init', 'modown_tinymce_button' );
function modown_tinymce_button() {
  add_filter( 'mce_buttons', 'modown_register_tinymce_button' );
  add_filter( 'mce_external_plugins', 'modown_add_tinymce_button' );
}

function modown_register_tinymce_button( $buttons ) {
     array_push( $buttons, "button_player");
     array_push( $buttons, "button_player2");
     array_push( $buttons, "button_gallery");
     array_push( $buttons, "button_erphpdown");
     array_push( $buttons, "button_vip");
     array_push( $buttons, "button_reply");
     array_push( $buttons, "button_login");
     array_push( $buttons, "button_catag");
     array_push( $buttons, "button_post");
     array_push( $buttons, "button_mocom_pic");
     return $buttons;
}

add_action( 'init', 'modown_tinymce_button2' );
function modown_tinymce_button2() {
  if ( !is_admin() ) {
    add_filter( 'mce_buttons', 'modown_register_tinymce_button2' );
    add_filter( 'mce_external_plugins', 'modown_add_tinymce_button' );
  }
}

function modown_register_tinymce_button2( $buttons ) {
     array_push( $buttons, "button_player");
     array_push( $buttons, "button_player2");
     array_push( $buttons, "button_erphpdown");
     array_push( $buttons, "button_vip");
     array_push( $buttons, "button_reply");
     array_push( $buttons, "button_login");
     array_push( $buttons, "button_post");
     return $buttons;
}

function modown_add_tinymce_button( $plugin_array ) {
  $plugin_array['mobantu_button_script'] = get_bloginfo('template_directory') . "/static/js/editor.js";
  return $plugin_array;
}

add_shortcode('erphptask','MBThemes_shortcode_task');
function MBThemes_shortcode_task($atts){
  if(function_exists('erphp_task_install')){
    $html = '<div class="mocat" id="mocat-task"><div class="container">
            <h2><span>悬赏任务</span></h2>
        <div class="tasks-nav clearfix">
            <div class="task-part task-part1">任务标题</div>
            <div class="task-part task-part2">发布时间</div>
            <div class="task-part task-part3">参与人数</div>
            <div class="task-part task-part4">预算</div>
            <div class="task-part task-part5">状态</div>
            <div class="task-part task-part6">操作</div>
        </div>
        <div class="posts tasks clearfix">';

          $args = array(
              'post_type'=>'task',
              'showposts'=>5
          );
          query_posts($args);
          $erphpdown = erphp_task_erphpdown_active();
          while ( have_posts() ) : the_post(); 
          $task_budget_type = get_post_meta(get_the_ID(),'task_budget_type',true);$task_budget_money = get_post_meta(get_the_ID(),'task_budget_money',true);$task_status = get_post_meta(get_the_ID(),'task_status',true);
          $html .= '<div class="post task clearfix">
                  <div class="task-part task-part1">
                      <h3><a href="'.get_permalink(get_the_ID()).'" target="_blank">'.get_the_title().'</a></h3>
                      <div class="excerpt">'.MBThemes_get_excerpt(100).'</div>
                  </div>
                  <div class="task-part task-part2">
                      '.get_the_date("Y-m-d").'
                  </div>
                  <div class="task-part task-part3">
                      '.get_comments_number('0', '1', '%').'
                  </div>
                  <div class="task-part task-part4">';
                      if($task_budget_type == '1'){if(!$erphpdown) $html .= '￥'; $html .= $task_budget_money; if($erphpdown) $html .= get_option('ice_name_alipay');}else{ $html .= '自由报价';}
                  $html .= '</div>
                  <div class="task-part task-part5">';
                      if($task_status == '1')  $html .= '已选标'; elseif($task_status == '2')  $html .= '已完成'; elseif($task_status == '3')  $html .= '已关闭'; else  $html .= '<span style="color:#ff5f33">投标中</span>';
                  $html .= '</div>
                  <div class="task-part task-part6">';
                      if(!$task_status){
                       $html .= '<a href="'.get_permalink(get_the_ID()).'" target="_blank" class="view">立即投标</a>';
                      }else{
                       $html .= '<a href="'.get_permalink(get_the_ID()).'" target="_blank" class="view">查看任务</a>';
                      }
                  $html .= '</div>
              </div>';
            endwhile; wp_reset_query(); 

        $html .= '</div></div><div class="more"><a href="'.get_post_type_archive_link('task').'" target="_blank">查看更多</a></div></div>';
  }else{
    $html = '';
  }
  return $html;
}

remove_shortcode( 'video', 'wp_video_shortcode' );
add_shortcode( 'video', 'MBT_video_shortcode' );
function MBT_video_shortcode( $attr, $content = '' ) {
  global $content_width;
  $post_id = get_post() ? get_the_ID() : 0;

  static $instance = 0;
  $instance++;
  $override = apply_filters( 'wp_video_shortcode_override', '', $attr, $content, $instance );
  if ( '' !== $override ) {
  return $override;
  }

  $video = null;

  $default_types = wp_get_video_extensions();
  $defaults_atts = array(
  'src' => '',
  'poster' => '',
  'loop' => '',
  'autoplay' => '',
  'preload' => 'metadata',
  'width' => 640,
  'height' => 360,
  //'class' => 'wp-video-shortcode',
  );

  foreach ( $default_types as $type ) {
  $defaults_atts[$type] = '';
  }

  $atts = shortcode_atts( $defaults_atts, $attr, 'video' );

  if ( is_admin() ) {
  if ( $atts['width'] > $defaults_atts['width'] ) {
  $atts['height'] = round( ( $atts['height'] * $defaults_atts['width'] ) / $atts['width'] );
  $atts['width'] = $defaults_atts['width'];
  }
  } else {
  if ( ! empty( $content_width ) && $atts['width'] > $content_width ) {
  $atts['height'] = round( ( $atts['height'] * $content_width ) / $atts['width'] );
  $atts['width'] = $content_width;
  }
  }

  $is_vimeo = $is_youtube = false;
  $yt_pattern = '#^https?://(?:www\.)?(?:youtube\.com/watch|youtu\.be/)#';
  $vimeo_pattern = '#^https?://(.+\.)?vimeo\.com/.*#';

  $primary = false;
  if ( ! empty( $atts['src'] ) ) {
  $is_vimeo = ( preg_match( $vimeo_pattern, $atts['src'] ) );
  $is_youtube = ( preg_match( $yt_pattern, $atts['src'] ) );
  if ( ! $is_youtube && ! $is_vimeo ) {
  $type = wp_check_filetype( $atts['src'], wp_get_mime_types() );
  if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) {
  return sprintf( '<a class="wp-embedded-video" href="%s">%s</a>', esc_url( $atts['src'] ), esc_html( $atts['src'] ) );
  }
  }

  if ( $is_vimeo ) {
  wp_enqueue_script( 'mediaelement-vimeo' );
  }

  $primary = true;
  array_unshift( $default_types, 'src' );
  } else {
  foreach ( $default_types as $ext ) {
  if ( ! empty( $atts[ $ext ] ) ) {
  $type = wp_check_filetype( $atts[ $ext ], wp_get_mime_types() );
  if ( strtolower( $type['ext'] ) === $ext ) {
  $primary = true;
  }
  }
  }
  }

  if ( ! $primary ) {
  $videos = get_attached_media( 'video', $post_id );
  if ( empty( $videos ) ) {
  return;
  }

  $video = reset( $videos );
  $atts['src'] = wp_get_attachment_url( $video->ID );
  if ( empty( $atts['src'] ) ) {
  return;
  }

  array_unshift( $default_types, 'src' );
  }

  $library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
  if ( 'mediaelement' === $library && did_action( 'init' ) ) {
  wp_enqueue_style( 'wp-mediaelement' );
  wp_enqueue_script( 'wp-mediaelement' );
  wp_enqueue_script( 'mediaelement-vimeo' );
  }

  if ( 'mediaelement' === $library ) {
  if ( $is_youtube ) {
  $atts['src'] = remove_query_arg( 'feature', $atts['src'] );
  $atts['src'] = set_url_scheme( $atts['src'], 'https' );
  } elseif ( $is_vimeo ) {
  $parsed_vimeo_url = wp_parse_url( $atts['src'] );
  $vimeo_src = 'https://' . $parsed_vimeo_url['host'] . $parsed_vimeo_url['path'];

  $loop = $atts['loop'] ? '1' : '0';
  $atts['src'] = add_query_arg( 'loop', $loop, $vimeo_src );
  }
  }

  if(isset($atts['class'])){
    $atts['class'] = apply_filters( 'wp_video_shortcode_class', $atts['class'], $atts );
  }

  $html_atts = array(
  //'class' => $atts['class'],
  //'id' => sprintf( 'video-%d-%d', $post_id, $instance ),
  //'width' => absint( $atts['width'] ),
  //'height' => absint( $atts['height'] ),
  'poster' => esc_url( $atts['poster'] ),
  'loop' => wp_validate_boolean( $atts['loop'] ),
  'autoplay' => wp_validate_boolean( $atts['autoplay'] ),
  //'preload' => $atts['preload'],
  );

  foreach ( array( 'poster', 'loop', 'autoplay', 'preload' ) as $a ) {
  if ( empty( $html_atts[$a] ) ) {
  unset( $html_atts[$a] );
  }
  }

  $attr_strings = array();
  foreach ( $html_atts as $k => $v ) {
  $attr_strings[] = $k . '="' . esc_attr( $v ) . '"';
  }

  $html = '';
  $fileurl = '';
  foreach ( $default_types as $fallback ) {
  if ( ! empty( $atts[ $fallback ] ) ) {
  if ( empty( $fileurl ) ) {
  $fileurl = $atts[ $fallback ];
  }
  if ( 'src' === $fallback && $is_youtube ) {
  $type = array( 'type' => 'video/youtube' );
  } elseif ( 'src' === $fallback && $is_vimeo ) {
  $type = array( 'type' => 'video/vimeo' );
  } else {
  $type = wp_check_filetype( $atts[ $fallback ], wp_get_mime_types() );
  }
  $url = add_query_arg( '_', $instance, $atts[ $fallback ] );
  }
  }

  $html .= sprintf( '<video %s src="'.esc_url( $url ).'" controls="controls">', join( ' ', $attr_strings ) );

  $html .= '</video>';

  $width_rule = '';
  if ( ! empty( $atts['width'] ) ) {
  $width_rule = sprintf( 'width: %dpx;', $atts['width'] );
  }
  $output = sprintf( '<div style="%s" class="wp-video">%s</div>', $width_rule, $html );
  //$output = $html;

  return apply_filters( 'MBT_video_shortcode', $output, $atts, $video, $post_id, $library );
}


add_shortcode('gallery_modown','MBThemes_shortcode_gallery_modown');
function MBThemes_shortcode_gallery_modown($atts){
  $atts = shortcode_atts( array(
    'urls' => '',
    'vip' => '0',
    'preview' => '0',
    'hide' => '0',
    'columns' => '4',
    'crop' => '0',
    'password' => '',
    'extra_btn' => '',
    'extra_url' => '',
  ), $atts, 'gallery_modown' );
  global $post,$wpdb;
  $gallery_column = $atts['columns'];
  $gallery_preview = $atts['preview'];
  $gallery_hide = $atts['hide'];
  $gallery_vip = $atts['vip'];
  $gallery_urls = $atts['urls'];
  if(strpos($gallery_urls,',') !== false){
    $gallery_arr = explode(',', $gallery_urls);
  }else{
    $gallery_arr = explode(' ', $gallery_urls);
  }

  $count = count($gallery_arr);

  $output = '';
  $erphp_url_front_vip = get_permalink(MBThemes_page("template/vip.php"));
  if(get_option('erphp_url_front_vip')){
    $erphp_url_front_vip = get_option('erphp_url_front_vip');
  }

  if(ERPHPDOWN_IS_ACTIVE){
    $userType=getUsreMemberType();
    if(function_exists('getUsreMemberCat')){
        if(is_single()){
          $categories = get_the_category();
          if ( !empty($categories) ) {
            $userCat=getUsreMemberCat(MBThemes_parent_cid($categories[0]->term_id));
            if(!$userType){
              if($userCat){
                $userType = $userCat;
              }
            }else{
              if($userCat){
                if($userCat > $userType){
                  $userType = $userCat;
                }
              }
            }
          }
        }
      }
  }

  $can = 0;

  $erphpdown_downkey = get_option('erphpdown_downkey');

  if($atts['password']){
    $erphpdown_private_downkey = get_option('erphpdown_private_downkey');
    if(!$erphpdown_private_downkey){
      update_option('erphpdown_private_downkey',wp_generate_password(64, true));
      $erphpdown_private_downkey = get_option('erphpdown_private_downkey');
    }

    if(isset($_COOKIE['post_gallery_'.$post->ID]) && $_COOKIE['post_gallery_'.$post->ID] == md5($erphpdown_private_downkey.$post->ID.$erphpdown_downkey.$atts['password'])){
      $can = 1;
    }else{

      $cypher = new ErphpCrypt(ErphpCrypt::CRYPT_MODE_HEXADECIMAL, ErphpCrypt::CRYPT_HASH_SHA1);
      $cypher->Key = md5($erphpdown_private_downkey.$erphpdown_downkey);
      $pk = $cypher->encrypt($atts['password']);

      $extra_div = '';
      if($atts['extra_url'] && $atts['extra_btn']){
        $extra_div = '<a href="'.$atts['extra_url'].'" target="_blank" rel="nofollow">'.$atts['extra_btn'].'</a>';
      }

      if($gallery_preview != ''){
        $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("加密相册仅限预览%s张，共%s张",'mobantu'),$gallery_preview,$count)."<a href='javascript:;' class='gallery-password-loader' data-post='".$post->ID."' data-pk='".$pk."'>".__("验证密码",'mobantu')."</a>".$extra_div."</span></div>";
      }else{
        $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时无权浏览此加密相册（共%s张），请先",'mobantu'),$count)."<a href='javascript:;' class='gallery-password-loader' data-post='".$post->ID."' data-pk='".$pk."'>".__("验证密码",'mobantu')."</a>".$extra_div."</span></div>";
      }
    }
  }else{
    $erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
    if($gallery_vip == '7'){
      $erphp_vip_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
    }elseif($gallery_vip == '8'){
      $erphp_vip_name  = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
    }elseif($gallery_vip == '9'){
      $erphp_vip_name  = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
    }elseif($gallery_vip == '10'){
      $erphp_vip_name  = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
    }

    if(!is_user_logged_in()){
      if($gallery_vip && ERPHPDOWN_IS_ACTIVE){
        if($gallery_preview != ''){
          $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("非%s用户仅限浏览%s张，共%s张",'mobantu'),$erphp_vip_name,$gallery_preview,$count)."<a href='javascript:;' class='signin-loader'>".__("登录",'mobantu')."</a></span></div>";
        }
      }else{
        if($gallery_preview != ''){
          $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("游客仅限浏览%s张，共%s张",'mobantu'),$gallery_preview,$count)."<a href='javascript:;' class='signin-loader'>".__("登录",'mobantu')."</a></span></div>";
        }
      }
    }else{
      $user_info = wp_get_current_user();
      if(ERPHPDOWN_IS_ACTIVE){
        $price = get_post_meta($post->ID,'down_price',true);
        $down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$post->ID."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
        if($down_info){
          $can = 1;
        }else{
          if($gallery_vip && !$userType){
            if($gallery_preview != ''){
              $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("非%s用户仅限浏览%s张，共%s张",'mobantu'),$erphp_vip_name,$gallery_preview,$count)."<a href='".$erphp_url_front_vip."' target='_blank'>".__('升级','mobantu').$erphp_vip_name."</a>";
              if($price){
                $output .= "<a href='".constant("erphpdown")."buy.php?postid=".$post->ID."' class='erphpdown-iframe'>".$price.get_option("ice_name_alipay")."购买</a>";
              }
              $output .= "</span></div>";
            }
          }elseif($gallery_vip && $userType){

            $erphp_life_times    = get_option('erphp_life_times');
            $erphp_year_times    = get_option('erphp_year_times');
            $erphp_quarter_times = get_option('erphp_quarter_times');
            $erphp_month_times  = get_option('erphp_month_times');
            $erphp_day_times  = get_option('erphp_day_times');

            if(get_option('vip_times_see')){
              $erphp_life_times    = get_option('erphp_life_times2');
              $erphp_year_times    = get_option('erphp_year_times2');
              $erphp_quarter_times = get_option('erphp_quarter_times2');
              $erphp_month_times  = get_option('erphp_month_times2');
              $erphp_day_times  = get_option('erphp_day_times2');
            }
            
            if(function_exists('checkSeeHas')){
              $cc = checkSeeHas($user_info->ID,$post->ID);
            }else{
              $cc = checkDownHas($user_info->ID,$post->ID);
            }

            if($cc){
              $can = 1;
            }else{
              if($userType >= $gallery_vip){
                if($userType == 6 && $erphp_day_times > 0){
                  if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),$count)."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$gallery_vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$gallery_vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_day_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }else{
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$gallery_preview,$count)."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_day_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }
                }elseif($userType == 7 && $erphp_month_times > 0){
                  if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),$count)."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$gallery_vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$gallery_vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_month_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }else{
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$gallery_preview,$count)."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_month_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }
                }elseif($userType == 8 && $erphp_quarter_times > 0){
                  if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),$count)."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$gallery_vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$gallery_vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_quarter_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }else{
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$gallery_preview,$count)."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_quarter_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }
                }elseif($userType == 9 && $erphp_year_times > 0){
                  if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),$count)."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$gallery_vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$gallery_vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_year_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }else{
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$gallery_preview,$count)."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_year_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }
                }elseif($userType == 10 && $erphp_life_times > 0){
                  if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),$count)."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$gallery_vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$gallery_vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_life_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }else{
                    $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$gallery_preview,$count)."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_life_times-getSeeCount($user_info->ID))."个）</span></div>";
                  }
                }else{
                  $can = 1;
                }
              }else{
                if($gallery_preview != ''){
                  $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("非%s用户仅限浏览%s张，共%s张",'mobantu'),$erphp_vip_name,$gallery_preview,$count)."<a href='".$erphp_url_front_vip."' target='_blank'>".__('升级','mobantu').$erphp_vip_name."</a>";
                  if($price){
                    $output .= "<a href='".constant("erphpdown")."buy.php?postid=".$post->ID."' class='erphpdown-iframe'>".$price.get_option("ice_name_alipay")."购买</a>";
                  }
                  $output .= "</span></div>";
                }
              }
            }
          }
        }
      }
    }
  }

  $output .= "<div id='gallery-".rand(1000,9999)."' class='gallery".(_MBT('post_gallery_water')?" gallery-water":"")." gallery-column-{$gallery_column} clearfix'>";

  $i = 0;
  foreach ( $gallery_arr as $attachment ) {
    if ($atts['crop']) {
      if(_MBT('bfithumb')){
        $thumb = bfi_thumb( $attachment, array("width"=>200, "height"=>200, "crop" => true) );
      }elseif(_MBT('timthumb')){
        $thumb = get_bloginfo('template_directory').'/timthumb.php?src='.$attachment.'&w=200&h=200&zc=1&q=95&a='.(_MBT('timthumb_type')?_MBT('timthumb_type'):'c');
      }else{
        $thumb = $attachment;
      }
    }else{
      $thumb = $attachment;
    }

    $i++;
    if(($gallery_preview != '' && $i <= $gallery_preview) || ($gallery_preview == '' && !$atts['password']) || (!$gallery_vip && !$atts['password'] && is_user_logged_in()) || $can){
      $image_output = '<a href="'.$attachment.'"><img src="'.$thumb.'"></a>';
      $output .= "<div class='gallery-item gallery-fancy-item'>";
    }else{
      if($gallery_hide){
          break;
      }else{
        $image_output = '<span class="img"><img src="'.$thumb.'"></span>';
        $output .= "<div class='gallery-item gallery-blur-item'>";
      }
    }
    $output .= "$image_output";
    $output .= "</div>";
  }
  $output .= "</div>\n";
  return $output;
}

add_filter( 'post_gallery', 'MBThemes_gallery_shortcode', 10, 2 );
function MBThemes_gallery_shortcode( $output, $attr ) {
    global $wpdb;
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( empty(  $attr['link'] ) ) {
        $attr['link'] = 'none'; 
    }

    if ( !empty( $attr['ids'] ) ) {
        if ( empty( $attr['orderby'] ) )
            $attr['orderby'] = 'post__in';
        $attr['include'] = $attr['ids'];
    }

    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => 'div',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'preview'    => '',
        'vip'        => '',
        'hide'       => '',
        'password'   => '',
        'extra_btn'  => '',
        'extra_url'  => ''
    ), $attr, 'gallery'));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $icontag = tag_escape($icontag);
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) )
        $itemtag = 'dl';
    if ( ! isset( $valid_tags[ $captiontag ] ) )
        $captiontag = 'dd';
    if ( ! isset( $valid_tags[ $icontag ] ) )
        $icontag = 'dt';

    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';

    $size_class = sanitize_html_class( $size );
    $erphp_url_front_vip = get_permalink(MBThemes_page("template/vip.php"));
    if(get_option('erphp_url_front_vip')){
      $erphp_url_front_vip = get_option('erphp_url_front_vip');
    }

    if(ERPHPDOWN_IS_ACTIVE){
      $userType=getUsreMemberType();
      if(function_exists('getUsreMemberCat')){
        if(is_single()){
          $categories = get_the_category();
          if ( !empty($categories) ) {
            $userCat=getUsreMemberCat(MBThemes_parent_cid($categories[0]->term_id));
            if(!$userType){
              if($userCat){
                $userType = $userCat;
              }
            }else{
              if($userCat){
                if($userCat > $userType){
                  $userType = $userCat;
                }
              }
            }
          }
        }
      }
    }

    $can = 0;

    $erphpdown_downkey = get_option('erphpdown_downkey');

    if($password){
      $erphpdown_private_downkey = get_option('erphpdown_private_downkey');
      if(!$erphpdown_private_downkey){
        update_option('erphpdown_private_downkey',wp_generate_password(64, true));
        $erphpdown_private_downkey = get_option('erphpdown_private_downkey');
      }

      if(isset($_COOKIE['post_gallery_'.$post->ID]) && $_COOKIE['post_gallery_'.$post->ID] == md5($erphpdown_private_downkey.$post->ID.$erphpdown_downkey.$password)){
        $can = 1;
      }else{

        $cypher = new ErphpCrypt(ErphpCrypt::CRYPT_MODE_HEXADECIMAL, ErphpCrypt::CRYPT_HASH_SHA1);
        $cypher->Key = md5($erphpdown_private_downkey.$erphpdown_downkey);
        $pk = $cypher->encrypt($password);

        $extra_div = '';
        if($extra_url && $extra_btn){
          $extra_div = '<a href="'.$extra_url.'" target="_blank" rel="nofollow">'.$extra_btn.'</a>';
        }

        if($preview != ''){
          $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("加密相册仅限预览%s张，共%s张",'mobantu'),$preview,count($attachments))."<a href='javascript:;' class='gallery-password-loader' data-post='".$post->ID."' data-pk='".$pk."'>".__("验证密码",'mobantu')."</a>".$extra_div."</span></div>";
        }else{
          $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时无权浏览此加密相册（共%s张），请先",'mobantu'),count($attachments))."<a href='javascript:;' class='gallery-password-loader' data-post='".$post->ID."' data-pk='".$pk."'>".__("验证密码",'mobantu')."</a>".$extra_div."</span></div>";
        }
      }
    }else{
      $erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';
      if($vip == '7'){
        $erphp_vip_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
      }elseif($vip == '8'){
        $erphp_vip_name  = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
      }elseif($vip == '9'){
        $erphp_vip_name  = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
      }elseif($vip == '10'){
        $erphp_vip_name  = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
      }

      if(!is_user_logged_in()){
        if($vip && ERPHPDOWN_IS_ACTIVE){
          if($preview != ''){
            $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("非%s用户仅可查看%s张，共%s张",'mobantu'),$erphp_vip_name,$preview,count($attachments))."<a href='javascript:;' class='signin-loader'>".__('登录','mobantu')."</a></span></div>";
          }
        }else{
          if($preview != ''){
            $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("游客仅可查看%s张，共%s张",'mobantu'),$preview,count($attachments))."<a href='javascript:;' class='signin-loader'>".__('登录','mobantu')."</a></span></div>";
          }
        }
      }else{
        $user_info = wp_get_current_user();
        if(ERPHPDOWN_IS_ACTIVE){
          $price = get_post_meta($post->ID,'down_price',true);
          $down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$post->ID."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
          if($down_info){
            $can = 1;
          }else{
            if($vip && !$userType){
              if($preview != ''){
                $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("非%s用户仅可查看%s张，共%s张",'mobantu'),$erphp_vip_name,$preview,count($attachments))."<a href='".$erphp_url_front_vip."' target='_blank'>".__('升级','mobantu').$erphp_vip_name."</a>";
                if($price){
                  $gallery_div .= "<a href='".constant("erphpdown")."buy.php?postid=".$post->ID."' class='erphpdown-iframe'>".$price.get_option("ice_name_alipay")."购买</a>";
                }
                $gallery_div .= "</span></div>";
              }
            }elseif($vip && $userType){
              $erphp_life_times    = get_option('erphp_life_times');
              $erphp_year_times    = get_option('erphp_year_times');
              $erphp_quarter_times = get_option('erphp_quarter_times');
              $erphp_month_times  = get_option('erphp_month_times');
              $erphp_day_times  = get_option('erphp_day_times');

              if(get_option('vip_times_see')){
                $erphp_life_times    = get_option('erphp_life_times2');
                $erphp_year_times    = get_option('erphp_year_times2');
                $erphp_quarter_times = get_option('erphp_quarter_times2');
                $erphp_month_times  = get_option('erphp_month_times2');
                $erphp_day_times  = get_option('erphp_day_times2');
              }

              if(function_exists('checkSeeHas')){
                $cc = checkSeeHas($user_info->ID,$post->ID);
              }else{
                $cc = checkDownHas($user_info->ID,$post->ID);
              }
              
              if($cc){
                $can = 1;
              }else{
                if($userType >= $vip){
                  if($userType == 6 && $erphp_day_times > 0){
                    if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),count($attachments))."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_day_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }else{
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$preview,count($attachments))."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_day_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }
                  }elseif($userType == 7 && $erphp_month_times > 0){
                    if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),count($attachments))."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_month_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }else{
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$preview,count($attachments))."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_month_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }
                  }elseif($userType == 8 && $erphp_quarter_times > 0){
                    if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),count($attachments))."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_quarter_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }else{
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$preview,count($attachments))."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_quarter_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }
                  }elseif($userType == 9 && $erphp_year_times > 0){
                    if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),count($attachments))."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_year_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }else{
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$preview,count($attachments))."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_year_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }
                  }elseif($userType == 10 && $erphp_life_times > 0){
                    if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您可免费查看此相册，共%s张",'mobantu'),count($attachments))."<a href='javascript:;' class='erphpdown-see-btn2' data-post='".$post->ID."' data-vip='".$vip."' data-token='".md5($erphpdown_downkey.$post->ID.$erphpdown_downkey.$vip)."'>立即查看</a>（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_life_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }else{
                      $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("您暂时仅可查看%s张，共%s张",'mobantu'),$preview,count($attachments))."（今日已查看".getSeeCount($user_info->ID)."个，还可查看".($erphp_life_times-getSeeCount($user_info->ID))."个）</span></div>";
                    }
                  }else{
                    $can = 1;
                  }
                }else{
                  if($preview != ''){
                    $gallery_div .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> ".sprintf(__("非%s用户仅可查看%s张，共%s张",'mobantu'),$erphp_vip_name,$preview,count($attachments))."<a href='".$erphp_url_front_vip."' target='_blank'>".__('升级','mobantu').$erphp_vip_name."</a>";
                    if($price){
                      $gallery_div .= "<a href='".constant("erphpdown")."buy.php?postid=".$post->ID."' class='erphpdown-iframe'>".$price.get_option("ice_name_alipay")."购买</a>";
                    }
                    $gallery_div .= "</span></div>";
                  }
                }
              }
            }
          }
        }
      }
    }

    $gallery_div .= "<div id='$selector' class='gallery".(_MBT('post_gallery_water')?" gallery-water":"")." galleryid-{$id} gallery-column-{$columns} clearfix'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
      $i++;

      if(($preview != '' && $i <= $preview) || ($preview == '' && !$password) || (!$vip && !$password && is_user_logged_in()) || $can){
        if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] )
            $image_output = wp_get_attachment_link( $id, $size, false, false );
        elseif ( ! empty( $attr['link'] ) && 'none' === $attr['link'] )
            $image_output = wp_get_attachment_image( $id, $size, false );
        else
            $image_output = wp_get_attachment_link( $id, $size, true, false );
        $image_meta  = wp_get_attachment_metadata( $id );
        $orientation = '';
        if ( isset( $image_meta['height'], $image_meta['width'] ) )
            $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
        $output .= "<{$itemtag} class='gallery-item gallery-fancy-item'>";
      }else{
        if($hide){
          break;
        }else{
          $image_output = '<span class="img">'.wp_get_attachment_image( $id, $size, false ).'</span>';
          $image_meta  = wp_get_attachment_metadata( $id );
          $orientation = '';
          if ( isset( $image_meta['height'], $image_meta['width'] ) )
              $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
          $output .= "<{$itemtag} class='gallery-item gallery-blur-item'>";
        }
      }

      $output .= "$image_output";
      $output .= "</{$itemtag}>";
       
    }

    $output .= "</div>\n";

    return $output;
}

add_shortcode("external_video","MBThemes_external_video_shortcode");
function MBThemes_external_video_shortcode( $atts, $content=null ){
  $atts = shortcode_atts( array(
    'width' => '',
    'height' => ''
  ), $atts, 'external_video' );
  $nonce = wp_create_nonce(rand(10,1000));
  $html = '<div id="external-video-'.$nonce.'" class="external-video">';
  if($atts['width'] || $atts['height']){
    $html .= '<style>#external-video-'.$nonce.' iframe{';
    if($atts['width']){
      $html .= 'width:'.$atts['width'].' !important;';
    }
    if($atts['height']){
      $html .= 'height:'.$atts['height'].' !important;';
    }
    $html .= 'display:inline-block;}</style>';
  }
  $html .= '<iframe frameborder="0" src="'.$content.'" allowFullScreen="true"></iframe></div>';
  return $html;
}

add_shortcode("mocom_pic","MBThemes_mocom_pic_shortcode");
function MBThemes_mocom_pic_shortcode( $atts, $content=null ){
  $atts = shortcode_atts( array(
    'pos' => '',
    'type' => '',
    'title' => '',
    'desc' => '',
    'img' => '',
    'btn' => '',
    'link' => '',
  ), $atts, 'mocom_pic' );
  $nonce = wp_create_nonce(rand(10,1000));
  $html = '<div class="mocom-pic">';
  $html .= '<div class="container"><div class="pic-text pos'.$atts['pos'].' clearfix">';
  if($atts['type'] == 'video'){
    $html .= '<video src="'.$atts['img'].'" controls="controls"></video>';
  }else{
    $html .= '<img src="'.$atts['img'].'">';
  }
  $html .= '<div class="con"><h2><span>'.$atts['title'].'</span></h2><div class="desc">'.$atts['desc'].'</div><a href="'.$atts['link'].'" class="more">'.$atts['btn'].'</a></div></div></div>';
  $html .= '</div>';
  return $html;
}