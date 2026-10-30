<?php
/**
 * Kết quả tìm kiếm sản phẩm.
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();
global $wp_query;
?>
<section class="page-band">
  <div class="container">
    <nav class="crumbs" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a><span aria-hidden="true">›</span><strong>Kết quả tìm kiếm</strong>
    </nav>
    <div class="band-row">
      <div class="band-text">
        <h1>Kết quả cho “<?php echo esc_html(get_search_query()); ?>”</h1>
        <div class="band-count"><?php echo (int) $wp_query->found_posts; ?> sản phẩm</div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php get_template_part('template-parts/product-grid'); ?>
  </div>
</section>

<?php get_footer(); ?>
