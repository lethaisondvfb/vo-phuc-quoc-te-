/* Trang chi tiết sản phẩm: đổi ảnh lớn (mờ dần), phóng to khi rê chuột, bấm để xem ảnh lớn */
(function () {
  var main = document.getElementById('vp-main-img');
  if (!main) { return; }
  var box = main.parentNode;
  var thumbs = document.querySelectorAll('.thumb');
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  thumbs.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var src = btn.getAttribute('data-src');
      if (!src || main.getAttribute('src') === src) { return; }
      thumbs.forEach(function (t) { t.classList.remove('is-active'); });
      btn.classList.add('is-active');
      var full = btn.getAttribute('data-full');
      var pre = new Image();
      function apply() {
        main.removeAttribute('srcset');
        main.removeAttribute('sizes');
        main.setAttribute('src', src);
        if (full) { main.setAttribute('data-full', full); }
        main.classList.remove('is-fading');
      }
      if (reduce) { apply(); return; }
      main.classList.add('is-fading');
      pre.onload = pre.onerror = function () { setTimeout(apply, 120); };
      pre.src = src;
    });
  });

  /* Phóng to theo vị trí chuột (chỉ máy có chuột) */
  var canHover = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (canHover && !reduce) {
    box.classList.add('can-zoom');
    box.addEventListener('mouseenter', function () { box.classList.add('is-zooming'); });
    box.addEventListener('mouseleave', function () { box.classList.remove('is-zooming'); main.style.transformOrigin = '50% 50%'; });
    box.addEventListener('mousemove', function (e) {
      var r = box.getBoundingClientRect();
      main.style.transformOrigin = ((e.clientX - r.left) / r.width * 100) + '% ' + ((e.clientY - r.top) / r.height * 100) + '%';
    });
  } else {
    box.style.cursor = 'zoom-in';
  }

  /* Xem ảnh lớn */
  var box2 = null;
  function close() { if (box2) { box2.classList.remove('is-open'); } }
  box.addEventListener('click', function () {
    var url = main.getAttribute('data-full') || main.currentSrc || main.src;
    if (!box2) {
      box2 = document.createElement('div');
      box2.className = 'vp-lightbox';
      box2.setAttribute('role', 'dialog');
      box2.setAttribute('aria-label', 'Xem ảnh lớn');
      box2.innerHTML = '<img alt=""><button type="button" class="vp-lightbox-close" aria-label="Đóng">&times;</button>';
      box2.addEventListener('click', close);
      document.body.appendChild(box2);
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { close(); } });
    }
    var img = box2.querySelector('img');
    img.src = url;
    img.alt = main.getAttribute('alt') || '';
    box2.classList.add('is-open');
  });
})();

/* Menu cố định: thêm bóng đổ khi đã cuộn xuống */
(function () {
  var header = document.querySelector('.site-header');
  if (!header) { return; }
  function update() { header.classList.toggle('is-scrolled', (window.pageYOffset || document.documentElement.scrollTop) > 8); }
  window.addEventListener('scroll', update, { passive: true });
  update();
})();

/* Hiện dần các khối khi cuộn tới */
(function () {
  if (!('IntersectionObserver' in window)) { return; }
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }
  var sel = '.benefit,.section-head,.cat-grid>a,.grid>.card,.act-grid>.act-card,.brand-tile,.step,.about-inner>*,.cta-inner>*,.detail-block,.page-band .band-row';
  var items = document.querySelectorAll(sel);
  if (!items.length) { return; }
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (en) {
      if (!en.isIntersecting) { return; }
      var el = en.target;
      io.unobserve(el);
      el.classList.add('is-in');
      setTimeout(function () { el.classList.remove('reveal', 'is-in'); el.style.transitionDelay = ''; }, 1000 + (parseInt(el.getAttribute('data-d'), 10) || 0));
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
  items.forEach(function (el) {
    var sibs = el.parentNode ? Array.prototype.indexOf.call(el.parentNode.children, el) : 0;
    var d = (sibs % 4) * 90;
    el.setAttribute('data-d', d);
    el.style.transitionDelay = d + 'ms';
    el.classList.add('reveal');
    io.observe(el);
  });
})();

/* Trang chi tiết sản phẩm: gom các lựa chọn của khách thành tin nhắn đặt hàng,
   sao chép sẵn vào bộ nhớ tạm rồi mở Zalo/TikTok để khách dán và gửi */
(function () {
  var box = document.querySelector('.order-box');
  if (!box) { return; }
  var msg = box.querySelector('.order-msg');

  function show(text, kind) {
    if (!msg) { return; }
    msg.textContent = text;
    msg.className = 'order-msg' + (kind ? ' is-' + kind : '');
  }

  function build() {
    var missing = [];
    var lines = [];
    box.querySelectorAll('.js-option').forEach(function (s) {
      var label = s.getAttribute('data-label');
      if (!s.value) { missing.push(label); } else { lines.push('- ' + label + ': ' + s.value); }
    });
    box.querySelectorAll('.js-fixed').forEach(function (f) {
      lines.push('- ' + f.getAttribute('data-label') + ': ' + f.getAttribute('data-value'));
    });
    var qtyEl = box.querySelector('.js-qty');
    var qty = qtyEl ? Math.max(1, parseInt(qtyEl.value, 10) || 1) : 1;
    var price = box.getAttribute('data-price');

    var text = 'Chào shop, mình muốn đặt:\n- Sản phẩm: ' + box.getAttribute('data-title');
    if (price) { text += '\n- Giá niêm yết: ' + price; }
    if (lines.length) { text += '\n' + lines.join('\n'); }
    text += '\n- Số lượng: ' + qty;
    text += '\n- Link: ' + box.getAttribute('data-url');
    text += '\nNhờ shop tư vấn và xác nhận đơn giúp mình.';
    return { text: text, missing: missing };
  }

  function fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.setAttribute('readonly', '');
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.select();
    var ok = false;
    try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
    document.body.removeChild(ta);
    return ok;
  }

  function copy(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      return navigator.clipboard.writeText(text).then(
        function () { return true; },
        function () { return fallbackCopy(text); }
      );
    }
    return Promise.resolve(fallbackCopy(text));
  }

  box.querySelectorAll('.js-order').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var result = build();
      if (result.missing.length) {
        e.preventDefault();
        show('Vui lòng chọn: ' + result.missing.join(', ') + '.', 'error');
        return;
      }
      copy(result.text).then(function (ok) {
        if (ok) {
          show('Đã sao chép nội dung đơn hàng. Hãy dán (Ctrl+V hoặc giữ ngón tay rồi chọn Dán) vào khung chat để gửi cho shop.', 'ok');
        } else {
          show('Chưa tự sao chép được. Bạn hãy nhắn cho shop tên sản phẩm và các lựa chọn vừa chọn.', 'error');
        }
      });
    });
  });
})();
