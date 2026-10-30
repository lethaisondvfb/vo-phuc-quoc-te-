<?php
/**
 * Trang một thương hiệu hoặc một nhóm sản phẩm: liệt kê toàn bộ sản phẩm thuộc về nó.
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();
global $wp_query;
$vp_term     = get_queried_object();
$vp_is_brand = is_tax('thuong_hieu');
$vp_logo     = vp_term_image($vp_term->term_id, 'medium');
$vp_desc     = term_description($vp_term->term_id, $vp_term->taxonomy);
?>
<section class="page-band">
  <div class="container">
    <nav class="crumbs" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a><span aria-hidden="true">›</span><strong><?php echo esc_html($vp_term->name); ?></strong>
    </nav>
    <div class="band-row">
      <?php if ($vp_logo) : ?>
        <div class="band-logo"><?php echo $vp_logo; ?></div>
      <?php elseif ($vp_is_brand) : ?>
        <div class="band-logo ph">Logo thương hiệu</div>
      <?php endif; ?>
      <div class="band-text">
        <h1><?php echo esc_html($vp_term->name); ?></h1>
        <?php if ($vp_desc) : ?><div class="band-desc"><?php echo wp_kses_post($vp_desc); ?></div><?php endif; ?>
        <div class="band-count"><?php echo (int) $wp_query->found_posts; ?> sản phẩm</div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2 class="section-title" style="margin-bottom:28px">Tất cả sản phẩm <?php echo esc_html($vp_term->name); ?></h2>
    <?php get_template_part('template-parts/product-grid'); ?>
  </div>
</section>

<?php
if ($vp_is_brand) {
    get_template_part('template-parts/brands-grid', null, array('title' => 'Xem thương hiệu khác', 'current' => $vp_term->term_id));
}
get_footer();
