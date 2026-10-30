<?php
/**
 * Lưới sản phẩm của truy vấn chính + phân trang.
 */
if (!defined('ABSPATH')) {
    exit;
}
if (have_posts()) : ?>
  <div class="grid">
    <?php
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/product-card');
    }
    ?>
  </div>
  <?php
  the_posts_pagination(array(
      'mid_size'  => 1,
      'prev_text' => '‹ Trước',
      'next_text' => 'Sau ›',
  ));
else : ?>
  <div class="empty">
    <h2>Chưa có sản phẩm phù hợp</h2>
    <p>Hãy thử từ khóa khác, hoặc nhắn Zalo để chúng tôi tìm giúp bạn.</p>
    <a class="btn btn-dark" href="<?php echo esc_url(get_post_type_archive_link('san_pham')); ?>">Xem tất cả sản phẩm</a>
  </div>
<?php endif; ?>
