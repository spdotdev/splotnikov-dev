// Particle picture: a fixed set of dots that morph into a shape. Starts on our logo; hovering or focusing a card
// morphs the dots into that card's shape. Shapes are sampled from drawn outlines, so one code path handles them all.
(function () {
  var canvas = document.getElementById('pt');
  if (!canvas || !canvas.getContext) return;
  var ctx = canvas.getContext('2d');
  var N = 900;                       // dots, the same count for every shape so the morph is one-to-one
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var accent = getComputedStyle(document.documentElement).getPropertyValue('--acc').trim() || '#0d9488';
  var dots = [], targets = {}, current = 'logo', W = 0, H = 0, raf = 0;
  var logoImg = new Image();
  logoImg.src = canvas.getAttribute('data-logo') || '/vendor/splotnikov/logo.svg';

  // Outline of each shape, drawn on a unit square (0..1) and scaled to the canvas.
  var draw = {
    logo: function (c, s) {
      // The mark drawn directly (a rounded frame and a chevron), so every stroke is solid and gets sampled.
      var k = s / 48, w = 4 * k;
      c.lineWidth = w; c.strokeStyle = '#000';
      c.beginPath(); if (c.roundRect) { c.roundRect(6 * k, 6 * k, 36 * k, 36 * k, 10 * k); } else { c.rect(6 * k, 6 * k, 36 * k, 36 * k); } c.stroke();
      c.beginPath(); c.moveTo(15 * k, 17 * k); c.lineTo(23 * k, 24 * k); c.lineTo(15 * k, 31 * k); c.lineWidth = w * 1.2; c.lineCap = 'round'; c.stroke();
    },
    agents: function (c, s) {
      var pts = [[0.5, 0.2], [0.22, 0.75], [0.78, 0.75]];
      c.lineWidth = s * 0.03; c.strokeStyle = '#000';
      c.beginPath(); c.moveTo(pts[0][0] * s, pts[0][1] * s); c.lineTo(pts[1][0] * s, pts[1][1] * s); c.lineTo(pts[2][0] * s, pts[2][1] * s); c.closePath(); c.stroke();
      pts.forEach(function (p) { c.beginPath(); c.arc(p[0] * s, p[1] * s, s * 0.11, 0, Math.PI * 2); c.fillStyle = '#000'; c.fill(); });
    },
    modern: function (c, s) {
      c.fillStyle = '#000';
      [0.22, 0.46, 0.7].forEach(function (y, i) {
        c.beginPath(); c.moveTo(0.1 * s, (y + 0.1) * s); c.lineTo(0.5 * s, y * s); c.lineTo(0.9 * s, (y + 0.1) * s); c.lineTo(0.5 * s, (y + 0.2) * s); c.closePath(); c.fill();
      });
    },
    security: function (c, s) {
      c.fillStyle = '#000'; c.beginPath();
      c.moveTo(0.5 * s, 0.1 * s); c.lineTo(0.85 * s, 0.22 * s); c.lineTo(0.85 * s, 0.5 * s);
      c.quadraticCurveTo(0.85 * s, 0.8 * s, 0.5 * s, 0.92 * s); c.quadraticCurveTo(0.15 * s, 0.8 * s, 0.15 * s, 0.5 * s);
      c.lineTo(0.15 * s, 0.22 * s); c.closePath(); c.fill();
    },
    architecture: function (c, s) {
      c.fillStyle = '#000';
      [[0.1, 0.1], [0.6, 0.1], [0.1, 0.6], [0.6, 0.6]].forEach(function (p) { c.fillRect(p[0] * s, p[1] * s, s * 0.3, s * 0.3); });
    }
  };

  // Sample a shape: draw it on a scratch canvas, keep the pixels that are filled, and spread N points over them.
  function sample(name, size) {
    var off = document.createElement('canvas'); off.width = size; off.height = size;
    var c = off.getContext('2d'); c.clearRect(0, 0, size, size);
    draw[name](c, size);
    var d = c.getImageData(0, 0, size, size).data, pts = [];
    for (var y = 0; y < size; y += 2) for (var x = 0; x < size; x += 2) if (d[(y * size + x) * 4 + 3] > 20) pts.push([x / size, y / size]);
    if (!pts.length) pts.push([0.5, 0.5]);
    var out = [];
    for (var i = 0; i < N; i++) out.push(pts[Math.floor((i * pts.length) / N)]);
    return out;
  }

  function layout() {
    var r = canvas.getBoundingClientRect(), dpr = Math.min(window.devicePixelRatio || 1, 2);
    W = Math.max(1, Math.round(r.width)); H = Math.max(1, Math.round(r.height));
    canvas.width = W * dpr; canvas.height = H * dpr; ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    var side = Math.min(W, H) * 0.9, ox = (W - side) / 2, oy = (H - side) / 2;
    targets = {};
    Object.keys(draw).forEach(function (k) {
      targets[k] = sample(k, 160).map(function (p) { return [ox + p[0] * side, oy + p[1] * side]; });
    });
    if (!dots.length) dots = targets.logo.map(function (t) { return { x: t[0] + (Math.random() - 0.5) * W, y: t[1] + (Math.random() - 0.5) * H, t: t }; });
    dots.forEach(function (d, i) { d.t = targets[current][i]; });
    if (reduce) { dots.forEach(function (d) { d.x = d.t[0]; d.y = d.t[1]; }); paint(); }
  }

  function paint() {
    ctx.clearRect(0, 0, W, H);
    ctx.fillStyle = accent;
    for (var i = 0; i < dots.length; i++) { ctx.fillRect(dots[i].x, dots[i].y, 2, 2); }
  }

  function step() {
    var moving = false;
    for (var i = 0; i < dots.length; i++) {
      var d = dots[i], dx = d.t[0] - d.x, dy = d.t[1] - d.y;
      if (Math.abs(dx) > 0.3 || Math.abs(dy) > 0.3) { d.x += dx * 0.09; d.y += dy * 0.09; moving = true; }
    }
    paint();
    raf = moving ? requestAnimationFrame(step) : 0;
  }

  function morph(name) {
    if (!targets[name] || name === current) return;
    current = name;
    dots.forEach(function (d, i) { d.t = targets[name][i]; });
    if (reduce) { dots.forEach(function (d) { d.x = d.t[0]; d.y = d.t[1]; }); paint(); return; }
    if (!raf) raf = requestAnimationFrame(step);
  }

  var cards = document.querySelectorAll('[data-shape]');
  cards.forEach(function (card) {
    var name = card.getAttribute('data-shape');
    card.addEventListener('mouseenter', function () { morph(name); });
    card.addEventListener('focus', function () { morph(name); });
  });
  logoImg.onload = function () { layout(); if (!raf) raf = requestAnimationFrame(step); };
  var rz; window.addEventListener('resize', function () { clearTimeout(rz); rz = setTimeout(layout, 150); });
  layout();
  if (!raf) raf = requestAnimationFrame(step);
})();
