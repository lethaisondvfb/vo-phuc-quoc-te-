<?php
/**
 * Đầu trang: logo, menu, ô tìm kiếm.
 */
if (!defined('ABSPATH')) {
    exit;
}
$vp_home = home_url('/');
$vp_nav  = array(
    array('Sản phẩm', get_post_type_archive_link('san_pham')),
    array('Thương hiệu', $vp_home . '#thuong-hieu'),
    array('Về chúng tôi', $vp_home . '#gioi-thieu'),
    array('Hoạt động', get_post_type_archive_link('hoat_dong')),
    array('Cách đặt hàng', $vp_home . '#dat-hang'),
    array('Liên hệ', '#lien-he'),
);
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
  <div class="header-inner">
    <?php if (has_custom_logo()) : ?>
      <div class="logo"><?php echo get_custom_logo(); ?></div>
    <?php else : ?>
      <a class="logo" href="<?php echo esc_url($vp_home); ?>">
        <span class="logo-mark">V</span>
        <span class="logo-text"><?php bloginfo('name'); ?></span>
      </a>
    <?php endif; ?>

    <nav class="main-nav" aria-label="Menu chính">
      <?php foreach ($vp_nav as $item) : ?>
        <a href="<?php echo esc_url($item[1]); ?>"><?php echo esc_html($item[0]); ?></a>
      <?php endforeach; ?>
    </nav>

    <form role="search" method="get" class="search-form" action="<?php echo esc_url($vp_home); ?>">
      <label class="screen-reader-text" for="vp-search">Tìm sản phẩm</label>
      <input id="vp-search" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Tìm võ phục Karate, đồ bảo hộ...">
      <button class="btn btn-accent" type="submit">Tìm kiếm</button>
    </form>
  </div>
</header>
