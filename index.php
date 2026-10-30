<?php
/**
 * Mẫu dự phòng cho trang thường, bài viết và trang không tìm thấy.
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header(); ?>
<main class="page-default">
  <div class="container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article>
        <h1><?php the_title(); ?></h1>
        <div class="entry"><?php the_content(); ?></div>
      </article>
    <?php endwhile; else : ?>
      <div class="empty">
        <h1>Không tìm thấy trang</h1>
        <p>Trang bạn cần không tồn tại hoặc đã được chuyển đi.</p>
        <a class="btn btn-dark" href="<?php echo esc_url(home_url('/')); ?>">Về trang chủ</a>
      </div>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
