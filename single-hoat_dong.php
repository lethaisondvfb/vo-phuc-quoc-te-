<?php
/**
 * Trang chi tiết một hoạt động / giải đấu tài trợ: ảnh, nội dung, link liên quan.
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

while (have_posts()) :
    the_post();
    $vp_id    = get_the_ID();
    $vp_types = get_the_terms($vp_id, 'loai_hoat_dong');
    $vp_type  = ($vp_types && !is_wp_error($vp_types)) ? $vp_types[0] : null;
    $vp_link  = trim((string) get_post_meta($vp_id, 'vp_hd_link', true));
    $vp_ltext = trim((string) get_post_meta($vp_id, 'vp_hd_link_text', true));
    ?>
<main class="activity">
  <div class="container">
    <nav class="crumbs" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a><span aria-hidden="true">›</span>
      <a href="<?php echo esc_url(get_post_type_archive_link('hoat_dong')); ?>">Hoạt động</a><span aria-hidden="true">›</span>
      <strong><?php the_title(); ?></strong>
    </nav>

    <article class="activity-body">
      <div class="act-meta">
        <?php if ($vp_type) : ?>
          <a class="act-type" href="<?php echo esc_url(get_term_link($vp_type)); ?>"><?php echo esc_html($vp_type->name); ?></a>
        <?php endif; ?>
        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
      </div>
      <h1 class="activity-title"><?php the_title(); ?></h1>

      <?php if (has_post_thumbnail()) : ?>
        <div class="activity-img"><?php the_post_thumbnail('large'); ?></div>
      <?php endif; ?>

      <div class="entry"><?php the_content(); ?></div>

      <?php if ($vp_link !== '') : ?>
        <p><a class="btn btn-accent" href="<?php echo esc_url($vp_link); ?>" target="_blank" rel="noopener"><?php echo esc_html($vp_ltext !== '' ? $vp_ltext : 'Xem thêm'); ?></a></p>
      <?php endif; ?>
    </article>
  </div>
</main>

    <?php
    $vp_more = new WP_Query(array(
        'post_type'      => 'hoat_dong',
        'posts_per_page' => 3,
        'post__not_in'   => array($vp_id),
    ));
    if ($vp_more->have_posts()) : ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">Hoạt động khác</h2>
      <a class="link-accent" href="<?php echo esc_url(get_post_type_archive_link('hoat_dong')); ?>">Xem tất cả →</a>
    </div>
    <div class="act-grid">
      <?php
      while ($vp_more->have_posts()) {
          $vp_more->the_post();
          get_template_part('template-parts/activity-card');
      }
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>
    <?php endif;
endwhile;

get_footer();
