<?php
/**
 * Trang "Tất cả sản phẩm" (và "Combo tiết kiệm" khi có ?combo=1).
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();
global $wp_query;
$vp_combo = (bool) get_query_var('combo');
$vp_title = $vp_combo ? 'Combo tiết kiệm, giá tốt' : 'Tất cả sản phẩm';
$vp_desc  = $vp_combo ? 'Các combo võ phục và đồ bảo hộ giá ưu đãi.' : 'Toàn bộ võ phục Karate và đồ bảo hộ từ các thương hiệu chúng tôi phân phối.';
?>
<section class="page-band">
  <div class="container">
    <nav class="crumbs" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a><span aria-hidden="true">›</span><strong><?php echo esc_html($vp_title); ?></strong>
    </nav>
    <div class="band-row">
      <div class="band-text">
        <h1><?php echo esc_html($vp_title); ?></h1>
        <div class="band-desc"><p><?php echo esc_html($vp_desc); ?></p></div>
        <div class="band-count"><?php echo (int) $wp_query->found_posts; ?> sản phẩm</div>
      </div>
    </div>
    <div class="chips" style="margin-top:16px">
      <a class="chip<?php echo $vp_combo ? '' : ' is-current'; ?>" href="<?php echo esc_url(get_post_type_archive_link('san_pham')); ?>">Tất cả sản phẩm</a>
      <a class="chip<?php echo $vp_combo ? ' is-current' : ''; ?>" href="<?php echo esc_url(add_query_arg('combo', '1', get_post_type_archive_link('san_pham'))); ?>">Combo tiết kiệm</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php get_template_part('template-parts/product-grid'); ?>
  </div>
</section>

<?php
get_template_part('template-parts/brands-grid', null, array('title' => 'Xem theo thương hiệu'));
get_footer();
