<?php 
	$home_cats_icon = explode(',', _MBT('home_cats_icon'));
	if(is_array($home_cats_icon) && count($home_cats_icon)){
		echo '<div class="container"><div class="home-caticons"><div class="items clearfix">';
		foreach($home_cats_icon as $cat){
			if($cat == '0'){
				$pp = MBThemes_page("template/cat-icons.php");
				if($pp){
					echo '<div class="item"><a href="'.get_permalink($pp).'"><img src="'.MBThemes_thumbnail_share(get_post($pp)).'" alt="全部分类"><h4>全部分类</h4></a></div>';
				}
			}else{
				$term = get_term_by('id',$cat,'category');
				if($term){
					$thumb_img = get_term_meta($cat,'thumb_icon',true);
					echo '<div class="item"><a href="'.get_category_link($cat).'"><img src="'.$thumb_img.'" alt="'.$term->name.'"><h4>'.$term->name.'</h4></a></div>';
				}
			}
		}
		echo '</div></div></div>';
	}
?>