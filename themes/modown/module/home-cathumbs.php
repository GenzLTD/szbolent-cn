<?php 
	$home_cats_thumb = explode(',', _MBT('home_cats_thumb'));
	if(is_array($home_cats_thumb) && count($home_cats_thumb)){
		echo '<div class="home-cathumbs">';		
		echo '<div class="container"><div class="swiper-cathumbs"><div class="items swiper-wrapper clearfix">';
		foreach($home_cats_thumb as $cat){
			$term = get_term_by('id',$cat,'category');
			if($term){
				$thumb_img = get_term_meta($cat,'thumb_img',true);
				echo '<div class="item swiper-slide"><a href="'.get_category_link($cat).'" title="'.$term->name.'" target="_blank"><img src="'.$thumb_img.'" alt="'.$term->name.'"><h4>'.$term->name.'</h4></a></div>';
			}
		}
		echo '</div></div>';
?>
		<div class="swiper-button-next swiper-button-white"></div>
		<div class="swiper-button-prev swiper-button-white"></div>
		<script src="<?php bloginfo('template_url');?>/static/js/swiper.min.js"></script>
		<script>
		    var swiper = new Swiper('.swiper-cathumbs', {
		      slidesPerView: 'auto',
		      spaceBetween: 10,
		      loop: true,
		      freeMode: true,
		      autoHeight: true,
		      autoplay: {
	            delay: 2000,
	            disableOnInteraction: false,
	          },
		      navigation: {
		          nextEl: ".swiper-button-next",
		          prevEl: ".swiper-button-prev",
		      }
		    });
		</script>
<?php
		echo '</div></div>';
	}
?>