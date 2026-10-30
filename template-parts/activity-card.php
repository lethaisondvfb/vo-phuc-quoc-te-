<?php
/**
 * Thẻ một bài hoạt động / giải đấu tài trợ.
 */
if (!defined('ABSPATH')) {
    exit;
}
$vp_id    = get_the_ID();
$vp_types = get_the_terms($vp_id, 'loai_hoat_dong');
$vp_type  = ($vp_types && !is_wp_error($vp_types)) ? $vp_types[0]->name : '';
?>
<article class="act-card">
  <a class="act-link" href="<?php the_permalink(); ?>">
    <div class="act-img">
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('vp-wide', array('loading' => 'lazy')); ?>
      <?php else : ?>
        <div class="ph">Ảnh hoạt động</div>
      <?php endif; ?>
    </div>
    <div class="act-body">
      <div class="act-meta">
        <?php if ($vp_type !== '') : ?><span class="act-type"><?php echo esc_html($vp_type); ?></span><?php endif; ?>
        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
      </div>
      <h3 class="act-title"><?php the_title(); ?></h3>
      <p class="act-excerpt"><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 24)); ?></p>
    </div>
  </a>
</article>
