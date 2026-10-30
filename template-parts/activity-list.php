<?php
/**
 * Danh sách hoạt động và giải đấu (dùng cho trang tất cả và trang theo loại).
 */
if (!defined('ABSPATH')) {
    exit;
}
global $wp_query;
$vp_is_term = is_tax('loai_hoat_dong');
$vp_cur     = $vp_is_term ? get_queried_object() : null;
$vp_title   = $vp_cur ? $vp_cur->name : 'Hoạt động và giải đấu tài trợ';
$vp_types   = get_terms(array('taxonomy' => 'loai_hoat_dong', 'hide_empty' => false));
?>
<section class="page-band">
  <div class="container">
    <nav class="crumbs" aria-label="Đường dẫn">
      <a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a><span aria-hidden="true">›</span>
      <?php if ($vp_cur) : ?>
        <a href="<?php echo esc_url(get_post_type_archive_link('hoat_dong')); ?>">Hoạt động</a><span aria-hidden="true">›</span>
      <?php endif; ?>
      <strong><?php echo esc_html($vp_title); ?></strong>
    </nav>
    <div class="band-row">
      <div class="band-text">
        <h1><?php echo esc_html($vp_title); ?></h1>
        <div class="band-desc"><p>Các hoạt động, sự kiện và giải đấu mà chúng tôi tham gia và tài trợ.</p></div>
        <div class="band-count"><?php echo (int) $wp_query->found_posts; ?> bài đăng</div>
      </div>
    </div>
    <?php if (!is_wp_error($vp_types) && $vp_types) : ?>
      <div class="chips" style="margin-top:16px">
        <a class="chip<?php echo $vp_cur ? '' : ' is-current'; ?>" href="<?php echo esc_url(get_post_type_archive_link('hoat_dong')); ?>">Tất cả</a>
        <?php foreach ($vp_types as $t) : ?>
          <a class="chip<?php echo ($vp_cur && (int) $vp_cur->term_id === (int) $t->term_id) ? ' is-current' : ''; ?>" href="<?php echo esc_url(get_term_link($t)); ?>"><?php echo esc_html($t->name); ?></a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if (have_posts()) : ?>
      <div class="act-grid">
        <?php
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/activity-card');
        }
        ?>
      </div>
      <?php
      the_posts_pagination(array('mid_size' => 1, 'prev_text' => '‹ Trước', 'next_text' => 'Sau ›'));
    else : ?>
      <div class="empty">
        <h2>Chưa có bài đăng</h2>
        <p>Vào Quản trị &gt; Hoạt động và giải đấu &gt; Thêm bài đăng để đăng tin đầu tiên.</p>
      </div>
    <?php endif; ?>
  </div>
</section>
