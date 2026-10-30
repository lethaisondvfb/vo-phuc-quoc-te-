<?php
/**
 * Trang chủ.
 * Thứ tự: Banner, Ưu điểm, Sản phẩm nổi bật, Combo, Danh mục sản phẩm, Thương hiệu, Về chúng tôi (kèm hoạt động), Cách đặt hàng.
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();

$vp_hero_img  = (int) vp_mod('vp_hero_image');
$vp_about_img = (int) vp_mod('vp_about_image');
$vp_p1        = trim((string) vp_mod('vp_about_p1'));
$vp_p2        = trim((string) vp_mod('vp_about_p2'));

$vp_benefits = array(
    array('01', 'Chuyên về Karate', 'Võ phục và đồ bảo hộ Karate từ nhiều thương hiệu.'),
    array('02', 'Tư vấn size qua Zalo', 'Gửi chiều cao, cân nặng để được gợi ý size phù hợp.'),
    array('03', 'Đặt hàng nhanh', 'Chỉ cần một tin nhắn, không cần tạo tài khoản.'),
    array('04', 'Dành cho câu lạc bộ', 'Hỗ trợ tư vấn khi đặt số lượng nhiều.'),
);
$vp_steps = array(
    array('1', 'Chọn mẫu', 'Xem sản phẩm trên website hoặc TikTok, chọn mẫu, kích cỡ và màu bạn thích.'),
    array('2', 'Nhắn Zalo, Facebook hoặc TikTok', 'Bấm nút đặt hàng, nội dung đơn đã được sao chép sẵn, bạn chỉ cần dán và gửi.'),
    array('3', 'Xác nhận và nhận hàng', 'Chốt đơn, thanh toán và nhận hàng theo hình thức hai bên thống nhất.'),
);

/* Sản phẩm nổi bật: ưu tiên sản phẩm được đánh dấu "Nổi bật", nếu chưa có thì lấy sản phẩm mới nhất */
$vp_featured = new WP_Query(array(
    'post_type'      => 'san_pham',
    'posts_per_page' => 8,
    'meta_key'       => 'vp_noi_bat',
    'meta_value'     => '1',
));
if (!$vp_featured->have_posts()) {
    $vp_featured = new WP_Query(array('post_type' => 'san_pham', 'posts_per_page' => 8));
}

/* Combo: các sản phẩm được đánh dấu là combo */
$vp_combos = new WP_Query(array(
    'post_type'      => 'san_pham',
    'posts_per_page' => 4,
    'meta_key'       => 'vp_combo',
    'meta_value'     => '1',
));

$vp_groups     = get_terms(array('taxonomy' => 'nhom_san_pham', 'hide_empty' => false));
$vp_activities = new WP_Query(array('post_type' => 'hoat_dong', 'posts_per_page' => 3));
?>

<section class="hero">
  <div class="hero-inner">
    <div class="hero-text">
      <div class="kicker"><?php echo esc_html(vp_mod('vp_hero_kicker')); ?></div>
      <h1 class="hero-title"><?php echo esc_html(vp_mod('vp_hero_title')); ?></h1>
      <p class="hero-sub"><?php echo esc_html(vp_mod('vp_hero_text')); ?></p>
      <div class="btn-row">
        <a class="btn btn-accent btn-lg" href="<?php echo esc_url(get_post_type_archive_link('san_pham')); ?>">Xem sản phẩm</a>
      </div>
    </div>
    <div class="hero-media">
      <?php if ($vp_hero_img) : ?>
        <?php echo wp_get_attachment_image($vp_hero_img, 'large'); ?>
      <?php else : ?>
        <div class="hero-ph">Ảnh banner<br>(thêm ở Giao diện &gt; Tùy biến &gt; Võ Phục: Trang chủ)</div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="benefits">
  <div class="benefits-inner">
    <?php foreach ($vp_benefits as $b) : ?>
      <div class="benefit">
        <div class="benefit-n"><?php echo esc_html($b[0]); ?></div>
        <div class="benefit-t"><?php echo esc_html($b[1]); ?></div>
        <div class="benefit-d"><?php echo esc_html($b[2]); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Sản phẩm nổi bật -->
<section id="san-pham" class="section">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">Sản phẩm nổi bật</h2>
      <a class="link-accent" href="<?php echo esc_url(get_post_type_archive_link('san_pham')); ?>">Xem tất cả →</a>
    </div>
    <?php if ($vp_featured->have_posts()) : ?>
      <div class="grid">
        <?php
        while ($vp_featured->have_posts()) {
            $vp_featured->the_post();
            get_template_part('template-parts/product-card');
        }
        wp_reset_postdata();
        ?>
      </div>
    <?php else : ?>
      <div class="empty">
        <h2>Chưa có sản phẩm</h2>
        <p>Vào Quản trị &gt; Sản phẩm &gt; Thêm sản phẩm để bắt đầu.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Combo tiết kiệm -->
<?php if ($vp_combos->have_posts()) : ?>
<section id="combo" class="section section-alt">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">Combo tiết kiệm, giá tốt</h2>
      <a class="link-accent" href="<?php echo esc_url(add_query_arg('combo', '1', get_post_type_archive_link('san_pham'))); ?>">Xem tất cả combo →</a>
    </div>
    <div class="grid">
      <?php
      while ($vp_combos->have_posts()) {
          $vp_combos->the_post();
          get_template_part('template-parts/product-card');
      }
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Danh mục sản phẩm -->
<?php if (!is_wp_error($vp_groups) && $vp_groups) : ?>
<section id="danh-muc" class="section">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">Danh mục sản phẩm</h2>
      <a class="link-accent" href="<?php echo esc_url(get_post_type_archive_link('san_pham')); ?>">Xem tất cả sản phẩm →</a>
    </div>
    <div class="cat-grid">
      <?php foreach ($vp_groups as $g) : ?>
        <a href="<?php echo esc_url(get_term_link($g)); ?>">
          <div class="cat-img">
            <?php $img = vp_term_image($g->term_id, 'vp-card'); echo $img ? $img : '<div class="ph" style="width:100%;height:100%">Ảnh danh mục</div>'; ?>
          </div>
          <div class="cat-name"><?php echo esc_html($g->name); ?></div>
          <div class="cat-sub"><?php echo esc_html($g->description); ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php get_template_part('template-parts/brands-grid'); ?>

<!-- Về chúng tôi -->
<section id="gioi-thieu" class="section">
  <div class="container about-inner">
    <div class="about-media">
      <?php if ($vp_about_img) : ?>
        <?php echo wp_get_attachment_image($vp_about_img, 'large'); ?>
      <?php else : ?>
        <div class="ph">Ảnh cửa hàng hoặc võ đường</div>
      <?php endif; ?>
    </div>
    <div class="about-text">
      <div class="kicker">VỀ CHÚNG TÔI</div>
      <h2><?php echo esc_html(vp_mod('vp_about_title')); ?></h2>
      <?php if ($vp_p1 !== '') : ?><p><?php echo esc_html($vp_p1); ?></p><?php endif; ?>
      <?php if ($vp_p2 !== '') : ?><p><?php echo esc_html($vp_p2); ?></p><?php endif; ?>
    </div>
  </div>
</section>

<?php if ($vp_activities->have_posts()) : ?>
<section id="hoat-dong" class="section" style="padding-top:0">
  <div class="container">
    <div class="section-head">
      <h2 class="section-title">Hoạt động và giải đấu tài trợ</h2>
      <a class="link-accent" href="<?php echo esc_url(get_post_type_archive_link('hoat_dong')); ?>">Xem tất cả →</a>
    </div>
    <div class="act-grid">
      <?php
      while ($vp_activities->have_posts()) {
          $vp_activities->the_post();
          get_template_part('template-parts/activity-card');
      }
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section id="dat-hang" class="section section-alt">
  <div class="container">
    <h2 class="section-title center">Đặt hàng thế nào?</h2>
    <div class="steps">
      <?php foreach ($vp_steps as $s) : ?>
        <div class="step">
          <div class="step-n"><?php echo esc_html($s[0]); ?></div>
          <div class="step-t"><?php echo esc_html($s[1]); ?></div>
          <div class="step-d"><?php echo esc_html($s[2]); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
