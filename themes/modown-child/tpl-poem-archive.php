<?php
/**
 * 诗词分面浏览共享模板
 * 被以下三个 WP 模板共用：
 *   - archive-poem.php            （/poem/ 自定义文章类型归档）
 *   - taxonomy-poem_genre.php     （/poem-genre/*/ 体裁归档）
 *   - taxonomy-poem_cipai.php     （/poem-cipai/*/ 词牌归档）
 * 仅含 .fw-wrap 内容；页头/页脚由调用方负责。
 */
$current      = get_queried_object();
$cur_tax      = ( $current && isset( $current->taxonomy ) ) ? $current->taxonomy : '';
$cur_slug     = ( $current && isset( $current->slug ) ) ? $current->slug : '';
$archive_title = post_type_archive_title( '', false );
if ( $current && isset( $current->name ) ) {
	$archive_title = $current->name;
}
?>
<div class="fw-wrap">
  <h1 class="fw-section-title" style="border:0;padding:0;font-size:22px;"><?php echo esc_html( $archive_title ?: '全部诗词' ); ?></h1>

  <div class="fw-filterbar">
	<span class="fw-label">体裁</span>
	<a class="fw-chip <?php echo ( $cur_tax === 'poem_genre' && $cur_slug === '诗' ) ? 'fw-chip--active' : ''; ?>" href="<?php echo esc_url( fw_term_link( '诗', 'poem_genre', home_url( '/poem-genre/%e8%af%97/' ) ) ); ?>">诗</a>
	<a class="fw-chip <?php echo ( $cur_tax === 'poem_genre' && $cur_slug === '词' ) ? 'fw-chip--active' : ''; ?>" href="<?php echo esc_url( fw_term_link( '词', 'poem_genre', home_url( '/poem-genre/%e8%af%8d/' ) ) ); ?>">词</a>
	<a class="fw-chip <?php echo ( ! $cur_tax ) ? 'fw-chip--active' : ''; ?>" href="<?php echo esc_url( home_url( '/poem/' ) ); ?>">全部</a>
  </div>

  <div class="fw-filterbar">
	<span class="fw-label">词牌</span>
	<?php
	$cipai_terms = get_terms( array( 'taxonomy' => 'poem_cipai', 'orderby' => 'count', 'order' => 'DESC', 'number' => 14 ) );
	if ( ! is_wp_error( $cipai_terms ) && $cipai_terms ) :
		foreach ( $cipai_terms as $t ) :
			$active = ( $cur_tax === 'poem_cipai' && $cur_slug === $t->slug ) ? 'fw-chip--active' : '';
	?>
	  <a class="fw-chip <?php echo $active; ?>" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
	<?php
		endforeach;
	endif;
	?>
  </div>

  <div class="fw-grid">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
		$lines  = array_filter( array_map( 'trim', explode( "\n", get_the_content() ) ) );
		$ex     = array_slice( $lines, 0, 2 );
		$genre  = get_post_meta( get_the_ID(), 'genre', true );
	?>
	<a class="fw-card" href="<?php the_permalink(); ?>">
	  <span class="fw-tag"><?php echo esc_html( $genre ?: '诗词' ); ?></span>
	  <h3><?php the_title(); ?></h3>
	  <div class="fw-excerpt"><?php echo esc_html( implode( ' ', $ex ) ); ?></div>
	  <span class="fw-more">阅读 →</span>
	</a>
	<?php endwhile; endif; ?>
  </div>

  <div style="margin-top:28px;">
	<?php
	global $wp_query;
	echo paginate_links( array(
		'total'     => $wp_query->max_num_pages,
		'prev_text' => '←',
		'next_text' => '→',
		'mid_size'  => 2,
	) );
	?>
  </div>
</div>
