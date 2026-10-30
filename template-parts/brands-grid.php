<?php
/**
 * Khối "Các thương hiệu": mỗi ô dẫn tới trang liệt kê sản phẩm của hãng.
 * Tham số: title (tiêu đề), current (ID hãng đang xem).
 */
if (!defined('ABSPATH')) {
    exit;
}
$vp_title   = isset($args['title']) ? $args['title'] : 'Các thương hiệu chúng tôi phân phối';
$vp_current = isset($args['current']) ? (int) $args['current'] : 0;
$vp_brands  = get_terms(array('taxonomy' => 'thuong_hieu', 'hide_empty' => false));
if (is_wp_error($vp_brands) || !$vp_brands) {
    return;
}
?>
<section id="thuong-hieu" class="section section-alt">
  <div class="container">
    <h2 class="section-title center"><?php echo esc_html($vp_title); ?></h2>
    <p class="lead center">Bấm vào một thương hiệu để xem tất cả sản phẩm của hãng đó.</p>
    <div class="brand-grid">
      <?php foreach ($vp_brands as $b) : ?>
        <a class="brand-tile<?php echo $vp_current === (int) $b->term_id ? ' is-current' : ''; ?>" href="<?php echo esc_url(get_term_link($b)); ?>">
          <?php echo vp_term_image($b->term_id, 'medium'); ?>
          <span class="brand-name"><?php echo esc_html($b->name); ?></span>
          <span class="brand-count"><?php echo (int) $b->count; ?> sản phẩm</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
