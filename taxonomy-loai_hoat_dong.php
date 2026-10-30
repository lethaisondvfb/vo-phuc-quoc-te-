<?php
/**
 * Danh sách bài đăng theo loại (Hoạt động, Giải đấu tài trợ...).
 */
if (!defined('ABSPATH')) {
    exit;
}
get_header();
get_template_part('template-parts/activity-list');
get_footer();
