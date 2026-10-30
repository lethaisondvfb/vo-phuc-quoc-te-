<?php
/**
 * Thẻ sản phẩm dùng trong mọi danh sách.
 */
if (!defined('ABSPATH')) {
    exit;
}
$vp_id       = get_the_ID();
$vp_brand    = vp_brand_of($vp_id);
$vp_discount = vp_discount_percent($vp_id);
?>
<article class="card">
  <a class="card-link" href="<?php the_permalink(); ?>">
    <div class="card-img">
      <?php echo vp_product_image($vp_id); ?>
      <?php if ($vp_discount > 0) : ?><span class="badge-sale">-<?php echo (int) $vp_discount; ?>%</span><?php endif; ?>
    </div>
    <div class="card-body">
      <?php if ($vp_brand) : ?><div class="card-brand"><?php echo esc_html($vp_brand->name); ?></div><?php endif; ?>
      <h3 class="card-title"><?php the_title(); ?></h3>
      <?php $vp_price_html = vp_price($vp_id); if ($vp_price_html !== '') : ?><div class="card-price"><?php echo $vp_price_html; ?></div><?php endif; ?>
    </div>
  </a>
</article>
