<?php
/**
 * 放翁文库 首页（国风）：每日一诗 Hero + 体裁/词牌分面 + 精选网格 + 订阅/Looma 入口
 * 由子主题接管（show_on_front=posts 时 WP 优先用 home.php）。
 */
get_header();
?>

<div class="fw-wrap">

  <?php
  // 每日一诗：按年日种子稳定选取一首
  $total = (int) wp_count_posts( 'poem' )->publish;
  $seed  = $total > 0 ? ( (int) date( 'z' ) % $total ) : 0;
  $daily = get_posts( array( 'post_type' => 'poem', 'posts_per_page' => 1, 'offset' => $seed ) );
  if ( ! empty( $daily ) ) :
	  $dp   = $daily[0];
	  $dlines = array_filter( array_map( 'trim', explode( "\n", $dp->post_content ) ) );
	  $dfirst = array_slice( $dlines, 0, 2 );
	  $dgenre = get_post_meta( $dp->ID, 'genre', true );
  ?>
  <section class="fw-hero">
	<div class="fw-hero-body">
	  <span class="fw-kicker">每日一诗 · <?php echo date_i18n( 'Y.m.d' ); ?></span>
	  <h1><?php echo esc_html( $dp->post_title ); ?></h1>
	  <div class="fw-meta"><?php echo esc_html( $dgenre ?: '诗词' ); ?> · 陆游（南宋）</div>
	  <div class="fw-lines"><?php echo esc_html( implode( "\n", $dfirst ) ); ?></div>
	  <div class="fw-actions">
		<a class="fw-btn" href="<?php echo esc_url( get_permalink( $dp->ID ) ); ?>">读原玉</a>
		<a class="fw-btn fw-btn--ghost" href="<?php echo esc_url( FW_LOOMA_URL ); ?>" target="_blank" rel="noopener">看穿越解读 ↗</a>
	  </div>
	</div>
  </section>
  <?php endif; ?>

  <h2 class="fw-section-title">按体裁浏览</h2>
  <div class="fw-chips">
	<a class="fw-chip" href="<?php echo esc_url( fw_term_link( '诗', 'poem_genre', home_url( '/poem-genre/%e8%af%97/' ) ) ); ?>">诗</a>
	<a class="fw-chip" href="<?php echo esc_url( fw_term_link( '词', 'poem_genre', home_url( '/poem-genre/%e8%af%8d/' ) ) ); ?>">词</a>
  </div>

  <h2 class="fw-section-title">按词牌浏览</h2>
  <div class="fw-chips">
	<?php
	$cipai_terms = get_terms( array( 'taxonomy' => 'poem_cipai', 'orderby' => 'count', 'order' => 'DESC', 'number' => 12 ) );
	if ( ! is_wp_error( $cipai_terms ) && $cipai_terms ) :
		foreach ( $cipai_terms as $t ) :
	?>
	  <a class="fw-chip" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
	<?php
		endforeach;
	endif;
	?>
	<a class="fw-chip fw-chip--active" href="<?php echo esc_url( home_url( '/poem/' ) ); ?>">全部诗词 →</a>
  </div>

  <h2 class="fw-section-title">精选诗词</h2>
  <div class="fw-grid">
	<?php
	$featured = get_posts( array( 'post_type' => 'poem', 'posts_per_page' => 6, 'orderby' => 'rand' ) );
	foreach ( $featured as $fp ) :
		$flines = array_filter( array_map( 'trim', explode( "\n", $fp->post_content ) ) );
		$fex    = array_slice( $flines, 0, 2 );
		$fgenre = get_post_meta( $fp->ID, 'genre', true );
	?>
	<a class="fw-card" href="<?php echo esc_url( get_permalink( $fp->ID ) ); ?>">
	  <span class="fw-tag"><?php echo esc_html( $fgenre ?: '诗词' ); ?></span>
	  <h3><?php echo esc_html( $fp->post_title ); ?></h3>
	  <div class="fw-excerpt"><?php echo esc_html( implode( ' ', $fex ) ); ?></div>
	  <span class="fw-more">阅读 →</span>
	</a>
	<?php endforeach; ?>
  </div>

  <div class="fw-subscribe">
	<?php if ( isset( $_GET['fw_sub'] ) ) :
		$st = sanitize_key( $_GET['fw_sub'] );
		$msg = array(
			'ok'      => '订阅成功，每日一诗将送达你的邮箱。',
			'exists'  => '这个邮箱已经在订阅列表里啦。',
			'invalid' => '邮箱格式有误或未通过校验，请重试。',
		);
		$cls = ( $st === 'ok' || $st === 'exists' ) ? 'fw-note--ok' : 'fw-note--err';
	?>
	  <p class="fw-note <?php echo esc_attr( $cls ); ?>"><?php echo esc_html( $msg[ $st ] ?? '' ); ?></p>
	<?php endif; ?>
	<div class="fw-sub-body">
	  <h2>订阅每日一诗</h2>
	  <p>每天一首陆游，配原创穿越解读，直达你的邮箱。</p>
	  <form class="fw-sub-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'fw_subscribe', 'fw_sub_nonce' ); ?>
		<input type="hidden" name="action" value="fw_subscribe">
		<input type="email" name="fw_email" placeholder="you@example.com" required>
		<button class="fw-btn" type="submit">订阅</button>
	  </form>
	  <div class="fw-sub-note">我们尊重你的隐私，随时可退订。</div>
	</div>
	<a class="fw-btn fw-btn--ghost" href="<?php echo esc_url( FW_LOOMA_URL ); ?>" target="_blank" rel="noopener">与陆游对话 ↗</a>
  </div>

</div>

<?php get_footer(); ?>
