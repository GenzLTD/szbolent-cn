<?php
/**
 * 书店首页模板 —— 按体裁（词 / 诗）分栏，点进去就是这首的原文页
 *
 * 由 mu-plugins/bookstore-front.php 的 template_include 接管 is_home/is_front_page 后加载。
 * 头尾用主题自带的 get_header()/get_footer()，中间换成书店分栏 —— 博主的皮肤原样保留。
 *
 * 注意：这里是模板文件，不是主题文件；改主题（themes/modown）会被 CI 同步覆盖，别改。
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>
<div class="bookstore-home">
	<?php foreach ( sb_genres() as $genre ) : ?>
		<?php
		$q     = sb_query_poems( $genre, sb_home_per_genre() );
		$total = (int) $q->found_posts;
		?>
		<section class="bookstore-genre bookstore-genre-<?php echo esc_attr( sanitize_title( $genre ) ); ?>">
			<header class="bookstore-genre-head">
				<h2 class="bookstore-genre-title"><?php echo esc_html( $genre ); ?></h2>
				<span class="bookstore-genre-count">共 <?php echo $total; ?> 首</span>
				<a class="bookstore-genre-more" href="<?php echo esc_url( sb_genre_archive_url( $genre ) ); ?>">
					查看全部 →
				</a>
			</header>

			<?php if ( $q->have_posts() ) : ?>
				<ul class="bookstore-list">
					<?php while ( $q->have_posts() ) : $q->the_post(); ?>
						<?php $line = sb_clip( sb_first_line( get_post() ), 24 ); ?>
						<li class="bookstore-item">
							<a class="bookstore-item-title" href="<?php echo esc_url( get_permalink() ); ?>">
								<?php echo esc_html( get_the_title() ); ?>
							</a>
							<?php if ( $line ) : ?>
								<span class="bookstore-item-first"><?php echo esc_html( $line ); ?>…</span>
							<?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php else : ?>
				<p class="bookstore-empty">这一栏暂时还没有内容。</p>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</section>
	<?php endforeach; ?>
</div>
<?php
get_footer();
