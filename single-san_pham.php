<?php
/**
 * Trang chi tiết một sản phẩm: ảnh, giá, tùy chọn, nút đặt qua Zalo/TikTok, mô tả và thông số bên dưới.
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

while (have_posts()) :
    the_post();
    $vp_id      = get_the_ID();
    $vp_brand   = vp_brand_of($vp_id);
    $vp_group   = get_the_terms($vp_id, 'nhom_san_pham');
    $vp_group   = ($vp_group && !is_wp_error($vp_group)) ? $vp_group[0] : null;
    $vp_short   = trim((string) get_post_meta($vp_id, 'vp_mo_ta_ngan', true));
    $vp_panel   = vp_option_panel($vp_id);
    $vp_options = $vp_panel['options'];
    $vp_fixed   = $vp_panel['fixed'];
    $vp_content = trim(get_the_content());

    // Ảnh: ảnh đại diện + thư viện ảnh
    $vp_imgs = array();
    if (has_post_thumbnail()) {
        $vp_imgs[] = (int) get_post_thumbnail_id();
    }
    foreach (explode(',', (string) get_post_meta($vp_id, 'vp_gallery', true)) as $g) {
        $g = absint($g);
        if ($g && !in_array($g, $vp_imgs, true)) {
            $vp_imgs[] = $g;
        }
    }

    // Thông số
    $vp_specs = array();
    if ($vp_brand) { $vp_specs['Thương hiệu'] = $vp_brand->name; }
    if ($vp_group) { $vp_specs['Danh mục'] = $vp_group->name; }
    if (vp_reversible_mode($vp_id) === 'co') { $vp_specs['Có thể đảo ngược'] = 'Có'; }
    foreach (array('Chất liệu' => 'vp_chat_lieu', 'Size có sẵn' => 'vp_size', 'Màu sắc' => 'vp_mau_sac', 'Xuất xứ' => 'vp_xuat_xu') as $label => $key) {
        $val = trim((string) get_post_meta($vp_id, $key, true));
        if ($val !== '') { $vp_specs[$label] = $val; }
    }
    ?>
<main class="product">
  <div class="container">
    <nav class="crumbs" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a><span aria-hidden="true">›</span>
      <?php if ($vp_brand) : ?>
        <a href="<?php echo esc_url(get_term_link($vp_brand)); ?>"><?php echo esc_html($vp_brand->name); ?></a><span aria-hidden="true">›</span>
      <?php endif; ?>
      <strong><?php the_title(); ?></strong>
    </nav>

    <div class="product-wrap">
      <div class="product-gallery">
        <div class="product-main">
          <?php if ($vp_imgs) : ?>
            <?php echo wp_get_attachment_image($vp_imgs[0], 'vp-large', false, array('id' => 'vp-main-img', 'alt' => esc_attr(get_the_title()), 'data-full' => esc_url(wp_get_attachment_image_url($vp_imgs[0], 'full')))); ?>
          <?php else : ?>
            <div class="ph">Chưa có ảnh sản phẩm</div>
          <?php endif; ?>
        </div>
        <?php if (count($vp_imgs) > 1) : ?>
          <div class="thumbs">
            <?php foreach ($vp_imgs as $i => $img_id) : ?>
              <button type="button" class="thumb<?php echo $i === 0 ? ' is-active' : ''; ?>" data-src="<?php echo esc_url(wp_get_attachment_image_url($img_id, 'vp-large')); ?>" data-full="<?php echo esc_url(wp_get_attachment_image_url($img_id, 'full')); ?>" aria-label="Xem ảnh <?php echo (int) ($i + 1); ?>">
                <?php echo wp_get_attachment_image($img_id, 'thumbnail'); ?>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="product-info">
        <?php if ($vp_brand) : ?>
          <a class="product-brand" href="<?php echo esc_url(get_term_link($vp_brand)); ?>"><?php echo esc_html($vp_brand->name); ?></a>
        <?php endif; ?>
        <h1 class="product-title"><?php the_title(); ?></h1>
        <?php $vp_price_html = vp_price($vp_id); if ($vp_price_html !== '') : ?><div class="product-price"><?php echo $vp_price_html; ?></div><?php endif; ?>

        <?php if ($vp_short !== '') : ?>
          <div class="product-short"><?php echo wp_kses_post(wpautop(esc_html($vp_short))); ?></div>
        <?php endif; ?>

        <div class="order-box"
             data-title="<?php echo esc_attr(get_the_title()); ?>"
             data-url="<?php echo esc_url(get_permalink()); ?>"
             data-price="<?php echo esc_attr(vp_price_plain($vp_id)); ?>">

          <?php foreach ($vp_options as $i => $opt) : $sel_id = 'vp-opt-' . (int) $i; ?>
            <div class="option-row">
              <label for="<?php echo esc_attr($sel_id); ?>"><?php echo esc_html($opt['label']); ?></label>
              <select id="<?php echo esc_attr($sel_id); ?>" class="js-option" data-label="<?php echo esc_attr($opt['label']); ?>">
                <option value="">Chọn một tùy chọn</option>
                <?php foreach ($opt['choices'] as $choice) : ?>
                  <option value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endforeach; ?>

          <?php foreach ($vp_fixed as $fx) : ?>
            <div class="option-row">
              <span class="option-label"><?php echo esc_html($fx['label']); ?></span>
              <span class="option-fixed js-fixed" data-label="<?php echo esc_attr($fx['label']); ?>" data-value="<?php echo esc_attr($fx['value']); ?>"><?php echo esc_html($fx['value']); ?></span>
            </div>
          <?php endforeach; ?>

          <div class="option-row">
            <label for="vp-qty">Số lượng</label>
            <input id="vp-qty" class="js-qty" type="number" min="1" value="1" inputmode="numeric">
          </div>

          <div class="btn-row product-cta">
            <a class="btn btn-accent js-order" href="<?php echo esc_url(vp_zalo_url()); ?>" target="_blank" rel="noopener">Đặt qua Zalo</a>
            <a class="btn btn-outline js-order" href="<?php echo esc_url(vp_facebook_url()); ?>" target="_blank" rel="noopener">Đặt qua Facebook</a>
            <a class="btn btn-outline js-order" href="<?php echo esc_url(vp_tiktok_url()); ?>" target="_blank" rel="noopener">Đặt qua TikTok</a>
          </div>
          <p class="order-msg" role="status" aria-live="polite"></p>
          <p class="note">Chọn xong bấm nút, nội dung đơn hàng sẽ được sao chép sẵn. Bạn chỉ cần dán vào khung chat để gửi cho shop.</p>
        </div>
      </div>
    </div>
  </div>
</main>

<?php if ($vp_content !== '' || $vp_specs) : ?>
<section class="product-detail">
  <div class="container">
    <?php if ($vp_content !== '') : ?>
      <div class="detail-block">
        <h2>Mô tả sản phẩm</h2>
        <div class="entry"><?php the_content(); ?></div>
      </div>
    <?php endif; ?>

    <?php if ($vp_specs) : ?>
      <div class="detail-block">
        <h2>Thông số</h2>
        <dl class="specs" style="margin-top:0;max-width:860px">
          <?php foreach ($vp_specs as $label => $val) : ?>
            <div class="spec"><dt><?php echo esc_html($label); ?></dt><dd><?php echo esc_html($val); ?></dd></div>
          <?php endforeach; ?>
        </dl>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

    <?php
    // Sản phẩm khác cùng hãng
    if ($vp_brand) :
        $vp_related = new WP_Query(array(
            'post_type'      => 'san_pham',
            'posts_per_page' => 4,
            'post__not_in'   => array($vp_id),
            'tax_query'      => array(array('taxonomy' => 'thuong_hieu', 'field' => 'term_id', 'terms' => $vp_brand->term_id)),
        ));
        if ($vp_related->have_posts()) : ?>
<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">Sản phẩm khác của <?php echo esc_html($vp_brand->name); ?></h2>
      <a class="link-accent" href="<?php echo esc_url(get_term_link($vp_brand)); ?>">Xem tất cả →</a>
    </div>
    <div class="grid">
      <?php
      while ($vp_related->have_posts()) {
          $vp_related->the_post();
          get_template_part('template-parts/product-card');
      }
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>
        <?php endif;
    endif;
endwhile;

get_footer();
