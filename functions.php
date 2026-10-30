<?php
/**
 * Võ Phục Quốc Tế - chức năng của giao diện.
 */
if (!defined('ABSPATH')) {
    exit;
}

define('VP_VERSION', '1.5.0');

/* ------------------------------------------------------------------
 * 1. Thiết lập chung
 * ----------------------------------------------------------------*/
function vp_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 240,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
    add_image_size('vp-card', 600, 600, true);
    add_image_size('vp-large', 1000, 1000, true);
    add_image_size('vp-wide', 900, 600, true);
}
add_action('after_setup_theme', 'vp_setup');

function vp_assets() {
    wp_enqueue_style(
        'vp-font',
        'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );
    wp_enqueue_style('vp-style', get_stylesheet_uri(), array(), VP_VERSION);

    $accent = sanitize_hex_color(vp_mod('vp_accent'));
    if ($accent) {
        wp_add_inline_style('vp-style', ':root{--accent:' . $accent . '}');
    }
    $custom = vp_custom_css();
    if ($custom !== '') {
        wp_add_inline_style('vp-style', $custom);
    }

    wp_enqueue_script('vp-main', get_template_directory_uri() . '/assets/js/main.js', array(), VP_VERSION, true);
}
add_action('wp_enqueue_scripts', 'vp_assets');

/* ------------------------------------------------------------------
 * 2. Sản phẩm, Thương hiệu, Danh mục, Hoạt động
 * ----------------------------------------------------------------*/
function vp_register_content() {
    register_post_type('san_pham', array(
        'labels' => array(
            'name'               => 'Sản phẩm',
            'singular_name'      => 'Sản phẩm',
            'menu_name'          => 'Sản phẩm',
            'all_items'          => 'Tất cả sản phẩm',
            'add_new'            => 'Thêm sản phẩm',
            'add_new_item'       => 'Thêm sản phẩm mới',
            'edit_item'          => 'Sửa sản phẩm',
            'new_item'           => 'Sản phẩm mới',
            'view_item'          => 'Xem sản phẩm',
            'search_items'       => 'Tìm sản phẩm',
            'not_found'          => 'Chưa có sản phẩm nào',
            'not_found_in_trash' => 'Không có sản phẩm trong thùng rác',
        ),
        'public'        => true,
        'has_archive'   => 'san-pham',
        'rewrite'       => array('slug' => 'san-pham'),
        'menu_icon'     => 'dashicons-awards',
        'menu_position' => 5,
        'supports'      => array('title', 'editor', 'thumbnail'),
        'show_in_rest'  => true,
    ));

    register_taxonomy('thuong_hieu', 'san_pham', array(
        'labels' => array(
            'name'          => 'Thương hiệu',
            'singular_name' => 'Thương hiệu',
            'menu_name'     => 'Thương hiệu',
            'all_items'     => 'Tất cả thương hiệu',
            'add_new_item'  => 'Thêm thương hiệu mới',
            'edit_item'     => 'Sửa thương hiệu',
            'search_items'  => 'Tìm thương hiệu',
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'thuong-hieu'),
    ));

    register_taxonomy('nhom_san_pham', 'san_pham', array(
        'labels' => array(
            'name'          => 'Danh mục sản phẩm',
            'singular_name' => 'Danh mục sản phẩm',
            'menu_name'     => 'Danh mục sản phẩm',
            'all_items'     => 'Tất cả danh mục',
            'add_new_item'  => 'Thêm danh mục mới',
            'edit_item'     => 'Sửa danh mục',
            'search_items'  => 'Tìm danh mục',
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'danh-muc'),
    ));

    register_post_type('hoat_dong', array(
        'labels' => array(
            'name'               => 'Hoạt động',
            'singular_name'      => 'Hoạt động',
            'menu_name'          => 'Hoạt động và giải đấu',
            'all_items'          => 'Tất cả bài đăng',
            'add_new'            => 'Thêm bài đăng',
            'add_new_item'       => 'Thêm hoạt động mới',
            'edit_item'          => 'Sửa bài đăng',
            'new_item'           => 'Bài đăng mới',
            'view_item'          => 'Xem bài đăng',
            'search_items'       => 'Tìm bài đăng',
            'not_found'          => 'Chưa có bài đăng nào',
            'not_found_in_trash' => 'Không có bài đăng trong thùng rác',
        ),
        'public'        => true,
        'has_archive'   => 'hoat-dong',
        'rewrite'       => array('slug' => 'hoat-dong'),
        'menu_icon'     => 'dashicons-megaphone',
        'menu_position' => 6,
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'  => true,
    ));

    register_taxonomy('loai_hoat_dong', 'hoat_dong', array(
        'labels' => array(
            'name'          => 'Loại bài đăng',
            'singular_name' => 'Loại bài đăng',
            'menu_name'     => 'Loại bài đăng',
            'all_items'     => 'Tất cả loại',
            'add_new_item'  => 'Thêm loại mới',
            'edit_item'     => 'Sửa loại',
            'search_items'  => 'Tìm loại',
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => array('slug' => 'loai-hoat-dong'),
    ));
}
add_action('init', 'vp_register_content');

/* Tạo sẵn hai loại bài đăng (một lần) */
function vp_seed_terms() {
    if (get_option('vp_seeded')) {
        return;
    }
    foreach (array('Hoạt động', 'Giải đấu tài trợ') as $name) {
        if (!term_exists($name, 'loai_hoat_dong')) {
            wp_insert_term($name, 'loai_hoat_dong');
        }
    }
    update_option('vp_seeded', 1);
}
add_action('init', 'vp_seed_terms', 20);

/* Làm mới đường dẫn một lần mỗi khi cập nhật phiên bản giao diện, bạn không cần vào Đường dẫn tĩnh để lưu lại */
function vp_maybe_flush() {
    if (get_option('vp_flushed') !== VP_VERSION) {
        flush_rewrite_rules(false);
        update_option('vp_flushed', VP_VERSION);
    }
}
add_action('init', 'vp_maybe_flush', 99);

/* Số mục mỗi trang, tìm kiếm chỉ tìm trong sản phẩm, lọc combo */
function vp_query_vars($vars) {
    $vars[] = 'combo';
    return $vars;
}
add_filter('query_vars', 'vp_query_vars');

function vp_query($q) {
    if (is_admin() || !$q->is_main_query()) {
        return;
    }
    if ($q->is_search()) {
        $q->set('post_type', 'san_pham');
    }
    if ($q->is_post_type_archive('san_pham') || $q->is_tax(array('thuong_hieu', 'nhom_san_pham')) || $q->is_search()) {
        $q->set('posts_per_page', 12);
    }
    if ($q->is_post_type_archive('san_pham') && $q->get('combo')) {
        $q->set('meta_query', array(array('key' => 'vp_combo', 'value' => '1')));
    }
    if ($q->is_post_type_archive('hoat_dong') || $q->is_tax('loai_hoat_dong')) {
        $q->set('posts_per_page', 9);
    }
}
add_action('pre_get_posts', 'vp_query');

/* ------------------------------------------------------------------
 * 3. Giá trị mặc định và hàm trợ giúp
 * ----------------------------------------------------------------*/
function vp_defaults() {
    $sizes = array();
    for ($s = 140; $s <= 185; $s += 5) {
        $sizes[] = $s . 'cm';
    }
    return array(
        'vp_accent'          => '#C8281F',
        'vp_hero_kicker'     => 'VÕ PHỤC KARATE · ĐỒ BẢO HỘ',
        'vp_hero_title'      => 'VÕ PHỤC KARATE QUỐC TẾ',
        'vp_hero_text'       => 'Chọn mẫu bạn thích rồi nhắn Zalo, Facebook hoặc TikTok để được tư vấn size và chốt đơn. Không cần tạo tài khoản, không cần giỏ hàng.',
        'vp_about_title'     => 'Đồng hành cùng võ sinh và câu lạc bộ',
        'vp_about_p1'        => '',
        'vp_about_p2'        => '',
        'vp_cta_title'       => 'Cần tư vấn size hoặc chốt đơn?',
        'vp_cta_text'        => 'Gửi tên sản phẩm, chiều cao và cân nặng, chúng tôi sẽ phản hồi sớm nhất có thể.',
        'vp_f_mark'          => 'V',
        'vp_f_name'          => '',
        'vp_f_about'         => 'Võ phục Karate và đồ bảo hộ nhiều thương hiệu. Đặt hàng qua Zalo, Facebook và TikTok.',
        'vp_f_col1_title'    => 'Sản phẩm',
        'vp_f_col1_links'    => "Tất cả sản phẩm | /san-pham/\nCombo tiết kiệm | /san-pham/?combo=1\nThương hiệu | /#thuong-hieu",
        'vp_f_col2_title'    => 'Hỗ trợ',
        'vp_f_col2_links'    => "Cách đặt hàng | /#dat-hang\nHướng dẫn chọn size | #lien-he\nChính sách đổi trả | #lien-he\nVề chúng tôi | /#gioi-thieu\nHoạt động và giải đấu | /hoat-dong/",
        'vp_f_contact_title' => 'Liên hệ',
        'vp_f_contact_extra' => '',
        'vp_f_copyright'     => '© {year} {site}',
        'vp_f_bottom_right'  => '',
        'vp_default_options' => "Kích cỡ: " . implode(' | ', $sizes) . "\nMẫu: Hai áo khoác, một quần | Một áo, một quần\nMàu sắc: Xanh | Đỏ | Trắng | Đen",
    );
}

function vp_mod($key) {
    $defaults = vp_defaults();
    $default  = isset($defaults[$key]) ? $defaults[$key] : '';
    return get_theme_mod($key, $default);
}

/* Chân trang: mỗi dòng "Chữ hiển thị | Đường dẫn". Đường dẫn bắt đầu bằng / là trang trong website */
function vp_footer_links($raw) {
    $out = array();
    foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $parts = array_map('trim', explode('|', $line, 2));
        $text  = $parts[0];
        $url   = isset($parts[1]) ? $parts[1] : '';
        if ($text === '') {
            continue;
        }
        if ($url === '') {
            $url = '#';
        } elseif ($url[0] === '/') {
            $url = home_url($url);
        }
        $out[] = array('text' => $text, 'url' => $url);
    }
    return $out;
}

/* Văn bản chân trang: cho phép {year}, {site}, thẻ a, br, strong, em */
function vp_footer_text($raw) {
    $raw = str_replace(array('{year}', '{site}'), array(date_i18n('Y'), esc_html(get_bloginfo('name'))), (string) $raw);
    return trim(wp_kses($raw, array(
        'a'      => array('href' => array(), 'target' => array(), 'rel' => array()),
        'br'     => array(),
        'strong' => array(),
        'em'     => array(),
    )));
}

/* Danh sách màu tùy chọn: khóa => [nhãn, selector, loại] */
function vp_color_map() {
    return array(
        'section' => array(
            'header'   => array('Thanh menu đầu trang', '.site-header'),
            'hero'     => array('Banner đầu trang', '.hero'),
            'benefits' => array('Dải 4 ưu điểm', '.benefits'),
            'featured' => array('Sản phẩm nổi bật', '#san-pham'),
            'combo'    => array('Combo tiết kiệm', '#combo'),
            'cat'      => array('Danh mục sản phẩm', '#danh-muc'),
            'brands'   => array('Thương hiệu', '#thuong-hieu'),
            'about'    => array('Về chúng tôi', '#gioi-thieu'),
            'activity' => array('Hoạt động và giải đấu', '#hoat-dong'),
            'steps'    => array('Cách đặt hàng', '#dat-hang'),
            'cta'      => array('Phần liên hệ (logo 3 ứng dụng)', '.cta'),
            'footer'   => array('Chân trang', '.site-footer'),
        ),
    );
}

function vp_custom_css() {
    $css = '';
    $get = function ($key) {
        $c = sanitize_hex_color((string) get_theme_mod($key, ''));
        return $c ? $c : '';
    };
    $map = vp_color_map();
    foreach ($map['section'] as $k => $info) {
        $bg = $get('vp_c_' . $k . '_bg');
        $tx = $get('vp_c_' . $k . '_text');
        $decl = ($bg ? 'background:' . $bg . ';' : '') . ($tx ? 'color:' . $tx . ';' : '');
        if ($decl) {
            $css .= $info[1] . '{' . $decl . '}';
        }
        if ($tx && $k === 'footer') {
            $css .= '.site-footer .footer-col h3,.site-footer .footer-brand,.site-footer .footer-col a,.site-footer .footer-col a:hover{color:' . $tx . '}';
        }
        if ($tx && $k === 'header') {
            $css .= '.site-header .main-nav a:hover{color:' . $tx . '}';
        }
    }
    $b = $get('vp_btn_bg');   $bt = $get('vp_btn_text');
    if ($b || $bt) {
        $css .= '.btn-accent{' . ($b ? 'background:' . $b . ';' : '') . ($bt ? 'color:' . $bt . ';' : '') . '}';
    }
    $d = $get('vp_btn_dark_bg'); $dt = $get('vp_btn_dark_text');
    if ($d || $dt) {
        $css .= '.btn-dark{' . ($d ? 'background:' . $d . ';' : '') . ($dt ? 'color:' . $dt . ';' : '') . '}';
    }
    $o = $get('vp_btn_outline');
    if ($o) {
        $css .= '.btn-outline{border-color:' . $o . ';color:' . $o . '}';
    }
    $w = $get('vp_btn_white_bg'); $wt = $get('vp_btn_white_text');
    if ($w || $wt) {
        $css .= '.btn-white{' . ($w ? 'background:' . $w . ';' : '') . ($wt ? 'color:' . $wt . ';' : '') . '}';
    }
    return $css;
}

function vp_zalo_url() {
    $z = trim((string) get_theme_mod('vp_zalo', ''));
    if ($z === '') {
        return 'https://zalo.me/';
    }
    if (preg_match('#^https?://#i', $z)) {
        return $z;
    }
    return 'https://zalo.me/' . preg_replace('/\D+/', '', $z);
}

function vp_tiktok_url() {
    $t = trim((string) get_theme_mod('vp_tiktok', ''));
    return $t !== '' ? $t : 'https://www.tiktok.com/';
}

function vp_facebook_url() {
    $f = trim((string) get_theme_mod('vp_facebook', ''));
    return $f !== '' ? $f : 'https://www.facebook.com/';
}

/* Ba kênh liên hệ: màu theo từng ứng dụng, logo do bạn tải lên ở Giao diện > Tùy biến */
function vp_channels() {
    return array(
        array('key' => 'facebook', 'label' => 'Facebook', 'url' => vp_facebook_url(), 'color' => '#1877F2', 'logo' => (int) get_theme_mod('vp_logo_facebook', 0)),
        array('key' => 'zalo', 'label' => 'Zalo', 'url' => vp_zalo_url(), 'color' => '#0068FF', 'logo' => (int) get_theme_mod('vp_logo_zalo', 0)),
        array('key' => 'tiktok', 'label' => 'TikTok', 'url' => vp_tiktok_url(), 'color' => '#000000', 'logo' => (int) get_theme_mod('vp_logo_tiktok', 0)),
    );
}

function vp_product_image($post_id, $size = 'vp-card') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, array('loading' => 'lazy'));
    }
    return '<div class="ph">Ảnh sản phẩm</div>';
}

/* Chuyển "1.250.000đ" hoặc "1250000" thành số; chữ khác trả về null */
function vp_price_number($raw) {
    $digits = preg_replace('/[\s.,đ₫]/u', '', trim((string) $raw));
    return ($digits !== '' && ctype_digit($digits)) ? (float) $digits : null;
}

/* Định dạng giá: "1250000" hoặc "1.250.000" thành "1.250.000đ"; chữ khác (vd "Từ 450.000đ") giữ nguyên */
function vp_format_price($raw) {
    $raw = trim((string) $raw);
    if ($raw === '') {
        return '';
    }
    $num = vp_price_number($raw);
    if ($num !== null && preg_match('/^[\d\s.,]+$/', $raw)) {
        return number_format($num, 0, ',', '.') . 'đ';
    }
    return $raw;
}

/* Giá dạng chữ thuần (dùng trong tin nhắn đặt hàng); rỗng nếu chưa nhập */
function vp_price_plain($post_id) {
    return vp_format_price(get_post_meta($post_id, 'vp_gia', true));
}

/* Giá dạng HTML: giá bán + giá cũ gạch ngang (nếu có). Chưa nhập giá thì để trống (không hiện gì) */
function vp_price($post_id) {
    $now = vp_price_plain($post_id);
    if ($now === '') {
        return '';
    }
    $html = '<span class="price-now">' . esc_html($now) . '</span>';
    $old  = vp_format_price(get_post_meta($post_id, 'vp_gia_cu', true));
    if ($old !== '') {
        $html .= ' <del class="price-old">' . esc_html($old) . '</del>';
    }
    return $html;
}

/* Phần trăm giảm giá (nếu có giá cũ cao hơn giá bán), trả về 0 nếu không tính được */
function vp_discount_percent($post_id) {
    $now = vp_price_number(get_post_meta($post_id, 'vp_gia', true));
    $old = vp_price_number(get_post_meta($post_id, 'vp_gia_cu', true));
    if ($now && $old && $old > $now) {
        return (int) round(($old - $now) / $old * 100);
    }
    return 0;
}

function vp_brand_of($post_id) {
    $terms = get_the_terms($post_id, 'thuong_hieu');
    return ($terms && !is_wp_error($terms)) ? $terms[0] : null;
}

function vp_term_image($term_id, $size = 'medium') {
    $id = (int) get_term_meta($term_id, 'vp_image', true);
    return $id ? wp_get_attachment_image($id, $size) : '';
}

/* Mở rộng khoảng số: "140-185/5 cm" thành 140 cm, 145 cm, ..., 185 cm (bước nhảy 5; không ghi thì bước 1) */
function vp_expand_choices($items) {
    $out = array();
    foreach ($items as $item) {
        if (preg_match('/^(\d+)\s*-\s*(\d+)(?:\s*\/\s*(\d+))?\s*(.*)$/u', $item, $m)) {
            $from = (int) $m[1];
            $to   = (int) $m[2];
            if ($from > $to) {
                $tmp = $from; $from = $to; $to = $tmp;
            }
            $step = (isset($m[3]) && $m[3] !== '') ? max(1, (int) $m[3]) : 1;
            $unit = trim($m[4]);
            $n    = 0;
            for ($v = $from; $v <= $to && $n < 300; $v += $step, $n++) {
                $out[] = $v . ($unit !== '' ? ' ' . $unit : '');
            }
        } else {
            $out[] = $item;
        }
    }
    return $out;
}

/* Tùy chọn của sản phẩm: mỗi dòng "Tên tùy chọn: lựa chọn 1 | lựa chọn 2 | ..." (cũng nhận dấu phẩy nếu dòng không có dấu |) */
function vp_parse_options($raw) {
    $out = array();
    foreach (preg_split('/\r\n|\r|\n/', (string) $raw) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, ':') === false) {
            continue;
        }
        $parts   = explode(':', $line, 2);
        $label   = trim($parts[0]);
        $sep     = (strpos($parts[1], '|') !== false) ? '|' : ',';
        $choices = vp_expand_choices(array_values(array_filter(array_map('trim', explode($sep, $parts[1])), 'strlen')));
        if ($label !== '' && $choices) {
            $out[] = array('label' => $label, 'choices' => $choices);
        }
    }
    return $out;
}

/* Tùy chọn riêng của sản phẩm; nếu để trống thì dùng bộ tùy chọn mặc định (trừ khi sản phẩm tắt tùy chọn mặc định) */
function vp_get_options($post_id) {
    $raw = trim((string) get_post_meta($post_id, 'vp_options', true));
    if ($raw === '') {
        if (get_post_meta($post_id, 'vp_no_default_options', true) === '1') {
            return array();
        }
        $raw = (string) vp_mod('vp_default_options');
    }
    return vp_parse_options($raw);
}

/* So sánh nhãn không phân biệt dấu và hoa thường */
function vp_is_reversible_label($label) {
    return strtolower(remove_accents(trim((string) $label))) === 'co the dao nguoc';
}

/* Bộ tùy chọn mặc định, bỏ dòng "Có thể đảo ngược" (mục này quản lý riêng cho từng sản phẩm) */
function vp_default_options_clean() {
    $lines = array();
    foreach (preg_split('/\r\n|\r|\n/', (string) vp_mod('vp_default_options')) as $line) {
        $label = trim(explode(':', $line, 2)[0]);
        if (trim($line) !== '' && !vp_is_reversible_label($label)) {
            $lines[] = trim($line);
        }
    }
    return implode("\n", $lines);
}

/* Đảo ngược (lật mặt): chon = khách chọn Không/Có; co = bộ này đảo ngược được; khong = không đảo ngược */
function vp_reversible_mode($post_id) {
    $m = (string) get_post_meta($post_id, 'vp_dao_nguoc', true);
    return in_array($m, array('chon', 'co', 'khong'), true) ? $m : 'chon';
}

/* Các dòng tùy chọn hiện cạnh ảnh: 'options' là ô chọn, 'fixed' là thông tin cố định (khách không phải chọn) */
function vp_option_panel($post_id) {
    $options = array();
    foreach (vp_get_options($post_id) as $opt) {
        if (!vp_is_reversible_label($opt['label'])) {
            $options[] = $opt;
        }
    }
    $fixed = array();
    $mode  = vp_reversible_mode($post_id);
    if ($mode === 'chon') {
        array_unshift($options, array('label' => 'Có thể đảo ngược', 'choices' => array('Không', 'Có')));
    } elseif ($mode === 'co') {
        $fixed[] = array('label' => 'Có thể đảo ngược', 'value' => 'Có');
    }
    return array('options' => $options, 'fixed' => $fixed);
}

/* Ô chọn ảnh từ Thư viện phương tiện (dùng cho logo hãng và thư viện ảnh sản phẩm) */
function vp_media_field($name, $value, $multiple = false) {
    $ids = array_filter(array_map('absint', explode(',', (string) $value)));
    echo '<div class="vp-media-field" data-multiple="' . ($multiple ? '1' : '0') . '">';
    echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr(implode(',', $ids)) . '">';
    echo '<div class="vp-media-preview">';
    foreach ($ids as $id) {
        echo wp_get_attachment_image($id, 'thumbnail');
    }
    echo '</div>';
    echo '<button type="button" class="button vp-media-pick">' . ($multiple ? 'Chọn các ảnh' : 'Chọn ảnh') . '</button> ';
    echo '<button type="button" class="button vp-media-clear">Xóa</button>';
    echo '</div>';
}

/* ------------------------------------------------------------------
 * 4. Ảnh / logo cho Thương hiệu và Danh mục sản phẩm
 * ----------------------------------------------------------------*/
foreach (array('thuong_hieu', 'nhom_san_pham') as $vp_tax) {
    add_action($vp_tax . '_add_form_fields', 'vp_term_field_add');
    add_action($vp_tax . '_edit_form_fields', 'vp_term_field_edit');
    add_action('created_' . $vp_tax, 'vp_term_save');
    add_action('edited_' . $vp_tax, 'vp_term_save');
}

function vp_term_field_add() {
    echo '<div class="form-field"><label>Logo / ảnh đại diện</label>';
    vp_media_field('vp_term_image', '', false);
    echo '<p>Thương hiệu: dùng làm logo. Danh mục sản phẩm: dùng làm ảnh danh mục ở trang chủ.</p></div>';
}

function vp_term_field_edit($term) {
    echo '<tr class="form-field"><th scope="row"><label>Logo / ảnh đại diện</label></th><td>';
    vp_media_field('vp_term_image', get_term_meta($term->term_id, 'vp_image', true), false);
    echo '<p class="description">Thương hiệu: dùng làm logo. Danh mục sản phẩm: dùng làm ảnh danh mục ở trang chủ.</p></td></tr>';
}

function vp_term_save($term_id) {
    if (!current_user_can('manage_categories') || !isset($_POST['vp_term_image'])) {
        return;
    }
    $img = absint($_POST['vp_term_image']);
    if ($img) {
        update_term_meta($term_id, 'vp_image', $img);
    } else {
        delete_term_meta($term_id, 'vp_image');
    }
}

/* ------------------------------------------------------------------
 * 5. Ô thông tin riêng cho từng sản phẩm và từng bài hoạt động
 * ----------------------------------------------------------------*/
function vp_product_fields() {
    return array(
        'vp_gia'       => 'Giá bán (đồng)',
        'vp_gia_cu'    => 'Giá cũ (đồng, tùy chọn)',
        'vp_chat_lieu' => 'Chất liệu',
        'vp_size'      => 'Size có sẵn',
        'vp_mau_sac'   => 'Màu sắc',
        'vp_xuat_xu'   => 'Xuất xứ',
    );
}

function vp_add_boxes() {
    add_meta_box('vp_product', 'Thông tin sản phẩm', 'vp_product_box', 'san_pham', 'normal', 'high');
    add_meta_box('vp_activity', 'Liên kết liên quan', 'vp_activity_box', 'hoat_dong', 'normal', 'high');
}
add_action('add_meta_boxes', 'vp_add_boxes');

function vp_product_box($post) {
    wp_nonce_field('vp_product_save', 'vp_product_nonce');
    echo '<table class="form-table" role="presentation">';
    $hints = array(
        'vp_gia'    => array('Ví dụ: 1250000', 'Nhập số, web tự hiện thành dạng 1.250.000đ. Nên nhập giá cho mọi sản phẩm, vì chưa có giá thì trang web không hiện giá.'),
        'vp_gia_cu' => array('Ví dụ: 1500000', 'Không bắt buộc. Nhập giá cũ để hiện gạch ngang cạnh giá bán và nhãn giảm giá (%).'),
    );
    foreach (vp_product_fields() as $key => $label) {
        $is_price = isset($hints[$key]);
        echo '<tr><th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td>';
        echo '<input type="text" class="' . ($is_price ? 'regular-text' : 'large-text') . '"' . ($is_price ? ' inputmode="numeric" placeholder="' . esc_attr($hints[$key][0]) . '"' : '') . ' id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr(get_post_meta($post->ID, $key, true)) . '">';
        if ($is_price) {
            echo ' <strong class="vp-price-preview" data-for="' . esc_attr($key) . '"></strong>';
            echo '<p class="description">' . esc_html($hints[$key][1]) . '</p>';
        }
        echo '</td></tr>';
    }
    echo '<tr><th scope="row"><label for="vp_mo_ta_ngan">Mô tả ngắn</label></th><td>';
    echo '<textarea class="large-text" rows="3" id="vp_mo_ta_ngan" name="vp_mo_ta_ngan">' . esc_textarea(get_post_meta($post->ID, 'vp_mo_ta_ngan', true)) . '</textarea>';
    echo '<p class="description">Hiện ngay dưới giá, cạnh ảnh sản phẩm. Mô tả dài viết ở khung soạn thảo phía trên, sẽ hiện bên dưới.</p>';
    echo '</td></tr>';
    $vp_mode = vp_reversible_mode($post->ID);
    echo '<tr><th scope="row"><label for="vp_dao_nguoc">Đảo ngược (lật mặt)</label></th><td>';
    echo '<select id="vp_dao_nguoc" name="vp_dao_nguoc">';
    foreach (array(
        'chon'  => 'Cho khách chọn: Không / Có',
        'co'    => 'Bộ này đảo ngược được (hiện "Có thể đảo ngược: Có", khách không phải chọn)',
        'khong' => 'Bộ này không đảo ngược (ẩn mục này)',
    ) as $val => $text) {
        echo '<option value="' . esc_attr($val) . '"' . selected($vp_mode, $val, false) . '>' . esc_html($text) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">Mỗi sản phẩm chọn một kiểu riêng: bộ đảo ngược được thì chọn "đảo ngược được", bộ không đảo ngược thì chọn "không đảo ngược".</p>';
    echo '</td></tr>';
    echo '<tr><th scope="row"><label for="vp_options">Tùy chọn riêng cho sản phẩm này</label></th><td>';
    echo '<textarea class="large-text code" rows="5" id="vp_options" name="vp_options" placeholder="Kích cỡ: 160cm | 165cm | 170cm&#10;Màu sắc: Xanh | Đỏ | Trắng | Đen">' . esc_textarea(get_post_meta($post->ID, 'vp_options', true)) . '</textarea>';
    echo '<p class="description"><strong>Để trống</strong> thì dùng bộ tùy chọn mặc định (sửa ở Giao diện &gt; Tùy biến &gt; Võ Phục: Tùy chọn đặt hàng). Nếu nhập, mỗi dòng một tùy chọn theo dạng <code>Tên tùy chọn: lựa chọn 1 | lựa chọn 2 | ...</code>, dùng dấu <code>|</code> để ngăn cách các lựa chọn.</p>';
    echo '<p><button type="button" class="button vp-fill-default" data-target="vp_options" data-default="' . esc_attr(vp_default_options_clean()) . '">Chèn bộ tùy chọn mặc định để chỉnh sửa</button> <span class="description">Bấm rồi sửa trực tiếp: xóa dòng không cần, đổi danh sách kích cỡ hay màu cho riêng sản phẩm này.</span></p>';
    echo '<p><label><input type="checkbox" name="vp_no_default_options" value="1" ' . checked(get_post_meta($post->ID, 'vp_no_default_options', true), '1', false) . '> Sản phẩm này không có tùy chọn (không dùng bộ mặc định, ví dụ găng tay, bảo vệ răng)</label></p>';
    echo '</td></tr>';
    echo '<tr><th scope="row">Thư viện ảnh<br><small>(ngoài ảnh đại diện)</small></th><td>';
    vp_media_field('vp_gallery', get_post_meta($post->ID, 'vp_gallery', true), true);
    echo '</td></tr>';
    echo '<tr><th scope="row">Hiển thị trang chủ</th><td>';
    echo '<p><label><input type="checkbox" name="vp_noi_bat" value="1" ' . checked(get_post_meta($post->ID, 'vp_noi_bat', true), '1', false) . '> Hiện ở mục "Sản phẩm nổi bật"</label></p>';
    echo '<p><label><input type="checkbox" name="vp_combo" value="1" ' . checked(get_post_meta($post->ID, 'vp_combo', true), '1', false) . '> Đây là combo, hiện ở mục "Combo tiết kiệm, giá tốt"</label></p>';
    echo '</td></tr>';
    echo '</table>';
    echo '<p class="description">Ảnh đại diện đặt ở khung "Ảnh đại diện" bên phải.</p>';
}

function vp_product_save($post_id) {
    if (!isset($_POST['vp_product_nonce']) || !wp_verify_nonce($_POST['vp_product_nonce'], 'vp_product_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    foreach (vp_product_fields() as $key => $label) {
        update_post_meta($post_id, $key, isset($_POST[$key]) ? sanitize_text_field(wp_unslash($_POST[$key])) : '');
    }
    foreach (array('vp_mo_ta_ngan', 'vp_options') as $area) {
        update_post_meta($post_id, $area, isset($_POST[$area]) ? sanitize_textarea_field(wp_unslash($_POST[$area])) : '');
    }
    $gallery = isset($_POST['vp_gallery']) ? implode(',', array_filter(array_map('absint', explode(',', wp_unslash($_POST['vp_gallery']))))) : '';
    update_post_meta($post_id, 'vp_gallery', $gallery);
    foreach (array('vp_noi_bat', 'vp_combo', 'vp_no_default_options') as $flag) {
        update_post_meta($post_id, $flag, !empty($_POST[$flag]) ? '1' : '');
    }
    $mode = isset($_POST['vp_dao_nguoc']) ? sanitize_key(wp_unslash($_POST['vp_dao_nguoc'])) : 'chon';
    update_post_meta($post_id, 'vp_dao_nguoc', in_array($mode, array('chon', 'co', 'khong'), true) ? $mode : 'chon');
}
add_action('save_post_san_pham', 'vp_product_save');

function vp_activity_box($post) {
    wp_nonce_field('vp_activity_save', 'vp_activity_nonce');
    echo '<table class="form-table" role="presentation">';
    echo '<tr><th scope="row"><label for="vp_hd_link">Link liên quan</label></th><td>';
    echo '<input type="url" class="large-text" id="vp_hd_link" name="vp_hd_link" placeholder="https://" value="' . esc_attr(get_post_meta($post->ID, 'vp_hd_link', true)) . '">';
    echo '<p class="description">Bài đăng Facebook, kết quả giải đấu, website ban tổ chức... Để trống nếu không có.</p></td></tr>';
    echo '<tr><th scope="row"><label for="vp_hd_link_text">Chữ trên nút</label></th><td>';
    echo '<input type="text" class="regular-text" id="vp_hd_link_text" name="vp_hd_link_text" placeholder="Xem thêm" value="' . esc_attr(get_post_meta($post->ID, 'vp_hd_link_text', true)) . '">';
    echo '</td></tr></table>';
    echo '<p class="description">Nội dung bài (chữ, ảnh, link) viết ở khung soạn thảo phía trên. Ảnh đại diện đặt ở khung bên phải. Chọn loại bài (Hoạt động hay Giải đấu tài trợ) ở khung "Loại bài đăng" bên phải.</p>';
}

function vp_activity_save($post_id) {
    if (!isset($_POST['vp_activity_nonce']) || !wp_verify_nonce($_POST['vp_activity_nonce'], 'vp_activity_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    update_post_meta($post_id, 'vp_hd_link', isset($_POST['vp_hd_link']) ? esc_url_raw(wp_unslash($_POST['vp_hd_link'])) : '');
    update_post_meta($post_id, 'vp_hd_link_text', isset($_POST['vp_hd_link_text']) ? sanitize_text_field(wp_unslash($_POST['vp_hd_link_text'])) : '');
}
add_action('save_post_hoat_dong', 'vp_activity_save');

/* Danh sách sản phẩm trong quản trị: thêm cột Giá, và nhắc khi sản phẩm đã đăng mà chưa có giá */
function vp_product_columns($cols) {
    $out = array();
    foreach ($cols as $k => $v) {
        $out[$k] = $v;
        if ($k === 'title') {
            $out['vp_gia'] = 'Giá';
        }
    }
    return $out;
}
add_filter('manage_san_pham_posts_columns', 'vp_product_columns');

function vp_product_column_value($col, $post_id) {
    if ($col !== 'vp_gia') {
        return;
    }
    $price = vp_price_plain($post_id);
    if ($price === '') {
        echo '<span style="color:#b32d2e;font-weight:600">Chưa có giá</span>';
    } else {
        echo esc_html($price);
    }
}
add_action('manage_san_pham_posts_custom_column', 'vp_product_column_value', 10, 2);

function vp_missing_price_notice() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'san_pham') {
        return;
    }
    global $post;
    if ($post && $post->post_status === 'publish' && vp_price_plain($post->ID) === '') {
        echo '<div class="notice notice-warning"><p>Sản phẩm này chưa có giá, nên trang web sẽ không hiện giá. Nhập giá ở ô <strong>Giá bán</strong> trong khung Thông tin sản phẩm rồi bấm Cập nhật.</p></div>';
    }
}
add_action('admin_notices', 'vp_missing_price_notice');

/* Script và kiểu cho trang quản trị */
function vp_admin_assets($hook) {
    if (!in_array($hook, array('post.php', 'post-new.php', 'edit-tags.php', 'term.php'), true)) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('vp-admin', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), VP_VERSION, true);
    wp_register_style('vp-admin', false);
    wp_enqueue_style('vp-admin');
    wp_add_inline_style('vp-admin', '.vp-media-preview{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px}.vp-media-preview img{width:80px;height:80px;object-fit:cover;border:1px solid #c3c4c7;border-radius:4px}');
}
add_action('admin_enqueue_scripts', 'vp_admin_assets');

/* ------------------------------------------------------------------
 * 6. Tùy biến giao diện: Giao diện > Tùy biến
 * ----------------------------------------------------------------*/
function vp_sanitize_footer_text($v) {
    return wp_kses((string) $v, array('a' => array('href' => array(), 'target' => array(), 'rel' => array()), 'br' => array(), 'strong' => array(), 'em' => array()));
}

function vp_customize($wp) {
    $wp->add_section('vp_contact', array('title' => 'Võ Phục: Thông tin liên hệ', 'priority' => 30));
    $wp->add_section('vp_bubbles', array(
        'title'       => 'Võ Phục: Logo bong bóng chat',
        'description' => 'Tải lên logo chính thức của Facebook, Zalo, TikTok (tải từ trang tài nguyên thương hiệu của từng hãng). Nên dùng ảnh vuông, nền trong suốt hoặc đúng nền màu của hãng. Chưa tải logo thì bong bóng hiện tên ứng dụng.',
        'priority'    => 31,
    ));
    $wp->add_section('vp_options_sec', array(
        'title'       => 'Võ Phục: Tùy chọn đặt hàng',
        'description' => 'Bộ tùy chọn mặc định hiện cạnh ảnh ở mọi sản phẩm (có thể ghi đè riêng cho từng sản phẩm). Mỗi dòng một tùy chọn: Tên tùy chọn: lựa chọn 1 | lựa chọn 2 | ...',
        'priority'    => 32,
    ));
    $wp->add_section('vp_home', array('title' => 'Võ Phục: Trang chủ', 'priority' => 33));
    $wp->add_section('vp_cta_sec', array(
        'title'       => 'Võ Phục: Phần liên hệ (logo 3 ứng dụng)',
        'description' => 'Phần màu đỏ phía trên chân trang, hiện logo và đường dẫn Zalo, Facebook, TikTok. Logo tải ở mục "Logo bong bóng chat".',
        'priority'    => 34,
    ));
    $wp->add_section('vp_footer_sec', array(
        'title'       => 'Võ Phục: Chân trang',
        'description' => 'Chỉnh toàn bộ chân trang. Mỗi dòng liên kết ghi dạng: Chữ hiển thị | Đường dẫn (đường dẫn bắt đầu bằng / là trang trong website, ví dụ /san-pham/). Để trống một ô thì phần đó tự ẩn. Bản quyền dùng được {year} (năm hiện tại), {site} (tên website).',
        'priority'    => 35,
    ));
    $wp->add_section('vp_colors_btn', array(
        'title'       => 'Võ Phục: Màu các nút',
        'description' => 'Để trống để dùng màu mặc định.',
        'priority'    => 36,
    ));
    $wp->add_section('vp_colors_sec', array(
        'title'       => 'Võ Phục: Màu từng phần của trang',
        'description' => 'Mỗi phần có màu nền và màu chữ riêng. Để trống để dùng màu mặc định.',
        'priority'    => 37,
    ));

    $defaults = vp_defaults();
    $text = array(
        array('vp_zalo', 'Số Zalo (hoặc link zalo.me)', 'text', 'vp_contact', 'sanitize_text_field'),
        array('vp_facebook', 'Link Facebook (trang cá nhân, fanpage hoặc link m.me)', 'url', 'vp_contact', 'esc_url_raw'),
        array('vp_tiktok', 'Link kênh TikTok', 'url', 'vp_contact', 'esc_url_raw'),
        array('vp_address', 'Địa chỉ', 'text', 'vp_contact', 'sanitize_text_field'),
        array('vp_hours', 'Giờ làm việc', 'text', 'vp_contact', 'sanitize_text_field'),
        array('vp_default_options', 'Tùy chọn mặc định', 'textarea', 'vp_options_sec', 'sanitize_textarea_field'),
        array('vp_hero_kicker', 'Dòng nhỏ phía trên tiêu đề banner', 'text', 'vp_home', 'sanitize_text_field'),
        array('vp_hero_title', 'Tiêu đề banner', 'text', 'vp_home', 'sanitize_text_field'),
        array('vp_hero_text', 'Đoạn mô tả banner', 'textarea', 'vp_home', 'sanitize_textarea_field'),
        array('vp_about_title', 'Tiêu đề phần Về chúng tôi', 'text', 'vp_home', 'sanitize_text_field'),
        array('vp_about_p1', 'Giới thiệu (đoạn 1)', 'textarea', 'vp_home', 'sanitize_textarea_field'),
        array('vp_about_p2', 'Giới thiệu (đoạn 2)', 'textarea', 'vp_home', 'sanitize_textarea_field'),
        array('vp_cta_title', 'Tiêu đề phần liên hệ', 'text', 'vp_cta_sec', 'sanitize_text_field'),
        array('vp_cta_text', 'Mô tả phần liên hệ', 'textarea', 'vp_cta_sec', 'sanitize_textarea_field'),
        array('vp_f_mark', 'Chữ trong ô logo nhỏ ở chân trang (để trống để ẩn)', 'text', 'vp_footer_sec', 'sanitize_text_field'),
        array('vp_f_name', 'Tên ở chân trang (để trống dùng tên website)', 'text', 'vp_footer_sec', 'sanitize_text_field'),
        array('vp_f_about', 'Giới thiệu ngắn ở chân trang', 'textarea', 'vp_footer_sec', 'sanitize_textarea_field'),
        array('vp_f_col1_title', 'Cột 1: tiêu đề', 'text', 'vp_footer_sec', 'sanitize_text_field'),
        array('vp_f_col1_links', 'Cột 1: các liên kết (mỗi dòng: Chữ | Đường dẫn)', 'textarea', 'vp_footer_sec', 'sanitize_textarea_field'),
        array('vp_f_col2_title', 'Cột 2: tiêu đề', 'text', 'vp_footer_sec', 'sanitize_text_field'),
        array('vp_f_col2_links', 'Cột 2: các liên kết (mỗi dòng: Chữ | Đường dẫn)', 'textarea', 'vp_footer_sec', 'sanitize_textarea_field'),
        array('vp_f_contact_title', 'Cột liên hệ: tiêu đề', 'text', 'vp_footer_sec', 'sanitize_text_field'),
        array('vp_f_contact_extra', 'Cột liên hệ: dòng thêm (email, hotline... mỗi dòng một mục)', 'textarea', 'vp_footer_sec', 'sanitize_textarea_field'),
        array('vp_f_copyright', 'Dòng bản quyền (bên trái)', 'textarea', 'vp_footer_sec', 'vp_sanitize_footer_text'),
        array('vp_f_bottom_right', 'Dòng cuối chân trang (bên phải, để trống để ẩn)', 'textarea', 'vp_footer_sec', 'vp_sanitize_footer_text'),
    );
    foreach ($text as $f) {
        $wp->add_setting($f[0], array(
            'default'           => isset($defaults[$f[0]]) ? $defaults[$f[0]] : '',
            'sanitize_callback' => $f[4],
        ));
        $wp->add_control($f[0], array('label' => $f[1], 'section' => $f[3], 'type' => $f[2]));
    }

    $wp->add_setting('vp_accent', array('default' => '#C8281F', 'sanitize_callback' => 'sanitize_hex_color'));
    $wp->add_control(new WP_Customize_Color_Control($wp, 'vp_accent', array(
        'label'   => 'Màu chủ đạo (nút, giá, điểm nhấn)',
        'section' => 'vp_home',
    )));

    $btn = array(
        'vp_btn_bg'         => 'Nút chính: màu nền',
        'vp_btn_text'       => 'Nút chính: màu chữ',
        'vp_btn_dark_bg'    => 'Nút đen: màu nền',
        'vp_btn_dark_text'  => 'Nút đen: màu chữ',
        'vp_btn_outline'    => 'Nút viền: màu viền và chữ',
        'vp_btn_white_bg'   => 'Nút trắng: màu nền',
        'vp_btn_white_text' => 'Nút trắng: màu chữ',
    );
    foreach ($btn as $id => $label) {
        $wp->add_setting($id, array('default' => '', 'sanitize_callback' => 'sanitize_hex_color'));
        $wp->add_control(new WP_Customize_Color_Control($wp, $id, array('label' => $label, 'section' => 'vp_colors_btn')));
    }
    $map = vp_color_map();
    foreach ($map['section'] as $k => $info) {
        foreach (array('bg' => 'màu nền', 'text' => 'màu chữ') as $suffix => $word) {
            $id = 'vp_c_' . $k . '_' . $suffix;
            $wp->add_setting($id, array('default' => '', 'sanitize_callback' => 'sanitize_hex_color'));
            $wp->add_control(new WP_Customize_Color_Control($wp, $id, array('label' => $info[0] . ': ' . $word, 'section' => 'vp_colors_sec')));
        }
    }

    $images = array(
        'vp_hero_image'     => array('Ảnh banner', 'vp_home'),
        'vp_about_image'    => array('Ảnh phần Về chúng tôi', 'vp_home'),
        'vp_logo_facebook'  => array('Logo Facebook', 'vp_bubbles'),
        'vp_logo_zalo'      => array('Logo Zalo', 'vp_bubbles'),
        'vp_logo_tiktok'    => array('Logo TikTok', 'vp_bubbles'),
    );
    foreach ($images as $id => $info) {
        $wp->add_setting($id, array('default' => 0, 'sanitize_callback' => 'absint'));
        $wp->add_control(new WP_Customize_Media_Control($wp, $id, array(
            'label'     => $info[0],
            'section'   => $info[1],
            'mime_type' => 'image',
        )));
    }
}
add_action('customize_register', 'vp_customize');
