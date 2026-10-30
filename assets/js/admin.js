/* Ô chọn ảnh từ Thư viện phương tiện (logo hãng, thư viện ảnh sản phẩm) */
jQuery(function ($) {
  function thumbUrl(it) {
    return it.sizes && it.sizes.thumbnail ? it.sizes.thumbnail.url : it.url;
  }

  $(document).on('click', '.vp-media-pick', function (e) {
    e.preventDefault();
    var $field = $(this).closest('.vp-media-field');
    var multiple = String($field.data('multiple')) === '1';
    var $input = $field.find('input[type=hidden]');
    var $preview = $field.find('.vp-media-preview');

    var frame = wp.media({
      title: multiple ? 'Chọn các ảnh' : 'Chọn ảnh',
      button: { text: 'Dùng ảnh đã chọn' },
      library: { type: 'image' },
      multiple: multiple ? 'add' : false
    });

    frame.on('open', function () {
      if (!multiple) { return; }
      var selection = frame.state().get('selection');
      ($input.val() || '').split(',').filter(Boolean).forEach(function (id) {
        var attachment = wp.media.attachment(id);
        attachment.fetch();
        selection.add(attachment);
      });
    });

    frame.on('select', function () {
      var items = frame.state().get('selection').toJSON();
      if (!multiple) { items = items.slice(0, 1); }
      var ids = [];
      $preview.empty();
      items.forEach(function (it) {
        ids.push(it.id);
        $preview.append($('<img>').attr('src', thumbUrl(it)).attr('alt', ''));
      });
      $input.val(ids.join(','));
    });

    frame.open();
  });

  /* Xem trước giá: 1250000 hiện thành 1.250.000đ */
  function formatPrice(raw) {
    raw = $.trim(raw);
    if (!raw || !/^[\d\s.,]+$/.test(raw)) { return ''; }
    var digits = raw.replace(/[\s.,]/g, '');
    return '= ' + digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + 'đ';
  }
  $('.vp-price-preview').each(function () {
    var $out = $(this);
    var $in = $('#' + $out.data('for'));
    function update() { $out.text(formatPrice($in.val())); }
    $in.on('input', update);
    update();
  });

  /* Chèn bộ tùy chọn mặc định vào ô tùy chọn riêng để chỉnh sửa */
  $(document).on('click', '.vp-fill-default', function (e) {
    e.preventDefault();
    var $btn = $(this);
    var $ta = $('#' + $btn.data('target'));
    if ($.trim($ta.val()) !== '' && !window.confirm('Ô tùy chọn đang có nội dung. Thay bằng bộ mặc định?')) { return; }
    $ta.val($btn.attr('data-default') || '').trigger('focus');
  });

  $(document).on('click', '.vp-media-clear', function (e) {
    e.preventDefault();
    var $field = $(this).closest('.vp-media-field');
    $field.find('input[type=hidden]').val('');
    $field.find('.vp-media-preview').empty();
  });
});
