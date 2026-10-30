<?php
/**
 * Chân trang: phần liên hệ (logo 3 ứng dụng), thông tin chân trang, bong bóng chat.
 * Mọi nội dung chỉnh ở Giao diện > Tùy biến > Võ Phục: Chân trang.
 */
if (!defined('ABSPATH')) {
    exit;
}
$vp_facebook = vp_facebook_url();
$vp_tiktok   = vp_tiktok_url();
$vp_phone    = trim((string) get_theme_mod('vp_zalo', ''));
$vp_address  = trim((string) get_theme_mod('vp_address', ''));
$vp_hours    = trim((string) get_theme_mod('vp_hours', ''));
$vp_strip    = function ($url) {
    return preg_replace('#^https?://(www\.)?#i', '', rtrim($url, '/'));
};
$vp_cols = array(
    array(vp_mod('vp_f_col1_title'), vp_footer_links(vp_mod('vp_f_col1_links'))),
    array(vp_mod('vp_f_col2_title'), vp_footer_links(vp_mod('vp_f_col2_links'))),
);
$vp_extra   = array_filter(array_map('trim', explode("\n", (string) vp_mod('vp_f_contact_extra'))));
$vp_about   = trim((string) vp_mod('vp_f_about'));
$vp_mark    = trim((string) vp_mod('vp_f_mark'));
$vp_name    = trim((string) vp_mod('vp_f_name'));
$vp_name    = $vp_name !== '' ? $vp_name : get_bloginfo('name');
$vp_copy    = vp_footer_text(vp_mod('vp_f_copyright'));
$vp_right   = vp_footer_text(vp_mod('vp_f_bottom_right'));
?>

<section id="lien-he" class="cta">
  <div class="cta-inner">
    <div class="cta-text">
      <h2><?php echo esc_html(vp_mod('vp_cta_title')); ?></h2>
      <p><?php echo esc_html(vp_mod('vp_cta_text')); ?></p>
    </div>
    <div class="cta-apps">
      <?php foreach (vp_channels() as $c) : ?>
        <a class="cta-app" href="<?php echo esc_url($c['url']); ?>" target="_blank" rel="noopener" aria-label="Mở <?php echo esc_attr($c['label']); ?>">
          <span class="cta-app-logo" style="background:<?php echo esc_attr($c['color']); ?>">
            <?php if ($c['logo']) : ?>
              <?php echo wp_get_attachment_image($c['logo'], 'medium', false, array('alt' => esc_attr($c['label']), 'loading' => 'lazy')); ?>
            <?php else : ?>
              <span class="bubble-text"><?php echo esc_html($c['label']); ?></span>
            <?php endif; ?>
          </span>
          <span class="cta-app-name"><?php echo esc_html($c['label']); ?></span>
          <span class="cta-app-url"><?php echo esc_html($vp_strip($c['url'])); ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-cols">
      <div class="footer-col wide">
        <div class="footer-brand">
          <?php if ($vp_mark !== '') : ?><span class="logo-mark"><?php echo esc_html($vp_mark); ?></span><?php endif; ?>
          <span><?php echo esc_html($vp_name); ?></span>
        </div>
        <?php if ($vp_about !== '') : ?><p><?php echo esc_html($vp_about); ?></p><?php endif; ?>
      </div>

      <?php foreach ($vp_cols as $col) : if (!$col[1]) { continue; } ?>
        <div class="footer-col">
          <?php if (trim((string) $col[0]) !== '') : ?><h3><?php echo esc_html($col[0]); ?></h3><?php endif; ?>
          <?php foreach ($col[1] as $l) : ?>
            <a href="<?php echo esc_url($l['url']); ?>"><?php echo esc_html($l['text']); ?></a>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>

      <div class="footer-col contact">
        <?php if (trim((string) vp_mod('vp_f_contact_title')) !== '') : ?><h3><?php echo esc_html(vp_mod('vp_f_contact_title')); ?></h3><?php endif; ?>
        <?php if ($vp_phone !== '') : ?><p class="line">Zalo: <?php echo esc_html($vp_phone); ?></p><?php endif; ?>
        <p class="line">Facebook: <a style="display:inline" href="<?php echo esc_url($vp_facebook); ?>" target="_blank" rel="noopener"><?php echo esc_html($vp_strip($vp_facebook)); ?></a></p>
        <p class="line">TikTok: <a style="display:inline" href="<?php echo esc_url($vp_tiktok); ?>" target="_blank" rel="noopener"><?php echo esc_html($vp_strip($vp_tiktok)); ?></a></p>
        <?php if ($vp_address !== '') : ?><p class="line">Địa chỉ: <?php echo esc_html($vp_address); ?></p><?php endif; ?>
        <?php if ($vp_hours !== '') : ?><p class="line">Giờ làm việc: <?php echo esc_html($vp_hours); ?></p><?php endif; ?>
        <?php foreach ($vp_extra as $line) : ?><p class="line"><?php echo esc_html($line); ?></p><?php endforeach; ?>
      </div>
    </div>
    <?php if ($vp_copy !== '' || $vp_right !== '') : ?>
      <div class="footer-bottom">
        <span><?php echo $vp_copy; ?></span>
        <?php if ($vp_right !== '') : ?><span><?php echo $vp_right; ?></span><?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</footer>

<!-- Bong bóng chat -->
<div class="chat-bubbles">
  <?php foreach (vp_channels() as $c) : ?>
    <a class="bubble bubble-<?php echo esc_attr($c['key']); ?>"
       href="<?php echo esc_url($c['url']); ?>" target="_blank" rel="noopener"
       style="background:<?php echo esc_attr($c['color']); ?>"
       aria-label="Nhắn qua <?php echo esc_attr($c['label']); ?>">
      <?php if ($c['logo']) : ?>
        <?php echo wp_get_attachment_image($c['logo'], 'thumbnail', false, array('alt' => '', 'loading' => 'eager')); ?>
      <?php else : ?>
        <span class="bubble-text"><?php echo esc_html($c['label']); ?></span>
      <?php endif; ?>
      <span class="bubble-tip"><?php echo esc_html($c['label']); ?></span>
    </a>
  <?php endforeach; ?>
</div>

<?php wp_footer(); ?>
</body>
</html>
