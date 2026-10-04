<?php
/**
 * 单篇诗词阅读页：元信息（体裁/词牌/平仄）+ 全文 + 上/下首导航 + 与陆游对话 + 穿越解读位
 */
get_header();

global $wpdb;
$pid   = get_the_ID();
$genre = get_post_meta( $pid, 'genre', true );
$strain= get_post_meta( $pid, 'strains', true );
$title = get_the_title();
$cipai = '';
if ( preg_match( '/・(.+?)(?:（|\()/u', $title, $m ) || preg_match( '/・(.+)$/u', $title, $m ) ) {
	$cipai = trim( $m[1] );
}
$prev_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type='poem' AND post_status='publish' AND ID < %d ORDER BY ID DESC LIMIT 1", $pid ) );
$next_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type='poem' AND post_status='publish' AND ID > %d ORDER BY ID ASC LIMIT 1", $pid ) );
?>
<div class="fw-wrap">
  <article>
	<h1 class="fw-section-title" style="border:0;padding:0;font-size:24px;"><?php the_title(); ?></h1>

	<div class="fw-single-meta">
	  <?php if ( $genre ) : ?><span class="fw-pill">体裁：<?php echo esc_html( $genre ); ?></span><?php endif; ?>
	  <?php if ( $cipai ) : ?><span class="fw-pill">词牌：<?php echo esc_html( $cipai ); ?></span><?php endif; ?>
	  <span class="fw-pill">作者：陆游（南宋）</span>
	  <?php if ( $strain ) : ?><span class="fw-pill">平仄：<?php echo esc_html( mb_substr( preg_replace( '/\s+/', '', $strain ), 0, 24 ) ); ?>…</span><?php endif; ?>
	</div>

	<div class="fw-poem-text"><?php echo esc_html( get_the_content() ); ?></div>

	<div class="fw-prevnext">
	  <?php if ( $prev_id ) : ?>
		<a href="<?php echo esc_url( get_permalink( $prev_id ) ); ?>"><span>← 上一首</span><?php echo esc_html( get_the_title( $prev_id ) ); ?></a>
	  <?php else : ?><span></span><?php endif; ?>
	  <?php if ( $next_id ) : ?>
		<a href="<?php echo esc_url( get_permalink( $next_id ) ); ?>" style="text-align:right;"><span>下一首 →</span><?php echo esc_html( get_the_title( $next_id ) ); ?></a>
	  <?php else : ?><span></span><?php endif; ?>
	</div>

	<div class="fw-looma">
	  <div class="fw-looma-body">
		<h3>与陆游对话</h3>
		<p>把这首诗交给 Looma，用穿越视角重新解读——古意今说，生成属于你的原创 commentary。</p>
	  </div>
	  <a class="fw-btn" href="<?php echo esc_url( FW_LOOMA_URL ); ?>" target="_blank" rel="noopener">打开 Looma 对话 ↗</a>
	</div>

	<div class="fw-looma" style="border-style:solid;">
	  <div class="fw-looma-body">
		<h3>穿越解读（由 Looma 生成）</h3>
		<p>内联解读即将上线：配置 Looma API 后，本栏将直接展示基于本诗的原创穿越 commentary，无需跳转。</p>
	  </div>
	</div>
  </article>
</div>
<?php get_footer(); ?>
