/* splotnikov.dev: language, theme, menu, decrypt headline, lights, the terminal panel, reveals, photo modal. No tracking. Shares its shape with scuttle.dev's site.js. */
(function () {
  var $ = function (id) { return document.getElementById(id); };
  var root = document.documentElement;
  var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var GLYPHS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789<>/{}[]#*&%$@!?=+~^|';

  // Scramble text into place, like a decrypt. Keeps tags (for example <br>) and spaces.
  function decrypt(el, ms) {
    if (reduce || !el) return;
    var html = el.innerHTML, parts = html.split(/(<[^>]+>)/), total = 0;
    parts.forEach(function (p) { if (p && p[0] !== '<') total += p.length; });
    if (!total) return;
    var start = null, lh = parseFloat(getComputedStyle(el).lineHeight) || 40, oneLine = el.offsetHeight < lh * 1.6 && html.indexOf('<br') < 0;
    // Random glyphs are wider than the real letters: keep a one-line headline on one line while it settles.
    if (oneLine) el.style.whiteSpace = 'nowrap';
    function frame(ts) {
      if (start === null) start = ts;
      var k = Math.min(1, (ts - start) / (ms || 900)), done = Math.floor(k * total), idx = 0, out = '';
      parts.forEach(function (p) {
        if (!p) return;
        if (p[0] === '<') { out += p; return; }
        for (var i = 0; i < p.length; i++, idx++) {
          var ch = p[i];
          if (ch === ' ' || ch === ' ' || idx < done) out += ch;
          else out += GLYPHS[Math.floor(Math.random() * GLYPHS.length)];
        }
      });
      el.innerHTML = out;
      if (k < 1) requestAnimationFrame(frame); else { el.innerHTML = html; el.style.whiteSpace = ''; }
    }
    requestAnimationFrame(frame);
  }

  // Dutch strings. English is the text already in the HTML.
  var T = window.SCUTTLE_NL || { t: {}, ph: {} };
  var base = {};
  document.querySelectorAll('[data-i]').forEach(function (e) { base[e.dataset.i] = base[e.dataset.i] || e.innerHTML; });
  function lang(l, animate) {
    root.lang = l;
    document.querySelectorAll('[data-i]').forEach(function (e) {
      var k = e.dataset.i;
      e.innerHTML = (l === 'nl' && T.t[k]) ? T.t[k] : base[k];
    });
    var en = $('en'), nl = $('nl');
    if (en) en.setAttribute('aria-pressed', l === 'en');
    if (nl) nl.setAttribute('aria-pressed', l === 'nl');
    try { localStorage.setItem('lang', l); } catch (e) {}
    if (animate) document.querySelectorAll('[data-dec]').forEach(function (h) { decrypt(h, 700); });
  }
  if ($('en')) $('en').onclick = function () { lang('en', true); };
  if ($('nl')) $('nl').onclick = function () { lang('nl', true); };

  // Theme: system by default, or the visitor's choice (the head script applied it before paint).
  var thBtns = [].slice.call(document.querySelectorAll('.seg.th button'));
  function theme(t) {
    if (t === 'dark' || t === 'light') root.setAttribute('data-theme', t); else { root.removeAttribute('data-theme'); t = 'system'; }
    thBtns.forEach(function (b) { b.setAttribute('aria-pressed', b.dataset.th === t); });
    try { localStorage.setItem('theme', t); } catch (e) {}
  }
  thBtns.forEach(function (b) { b.onclick = function () { theme(b.dataset.th); }; });
  try { var t0 = localStorage.getItem('theme'); thBtns.forEach(function (b) { b.setAttribute('aria-pressed', b.dataset.th === ((t0 === 'dark' || t0 === 'light') ? t0 : 'system')); }); } catch (e) {}

  // Phone menu and the products menu.
  var links = $('links'), megaBtn = $('mega-btn'), hasMega = $('has-mega');
  if ($('burger') && links) {
    $('burger').onclick = function () { links.classList.toggle('open'); };
    links.addEventListener('click', function (e) { if (e.target.closest('a')) links.classList.remove('open'); });
  }
  if (megaBtn) {
    megaBtn.onclick = function () { megaBtn.setAttribute('aria-expanded', megaBtn.getAttribute('aria-expanded') !== 'true'); };
    document.addEventListener('click', function (e) { if (hasMega && !hasMega.contains(e.target)) megaBtn.setAttribute('aria-expanded', 'false'); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') megaBtn.setAttribute('aria-expanded', 'false'); });
  }

  // Brand: the letters rearrange on hover and the three squares fade in again from nothing.
  var brandTxt = document.querySelector('.brand [data-scr]'), brandLogo = document.querySelector('.brand .lg');
  if (brandTxt && !reduce) {
    var busy = false;
    brandTxt.parentNode.addEventListener('mouseenter', function () {
      if (busy) return; busy = true;
      decrypt(brandTxt, 450);
      if (brandLogo) { brandLogo.classList.remove('anim'); void brandLogo.getBoundingClientRect(); brandLogo.classList.add('anim'); }
      setTimeout(function () { busy = false; }, 900);
    });
  }

  // Case studies: swipe, arrows, dots, auto-rotate every 7 s (paused on hover, off under reduced motion).
  var car = $('car'), track = $('car-track');
  if (car && track) {
    var slides = [].slice.call(track.children), n = slides.length, idx = 0, dots = $('car-dots'), auto, x0 = null;
    slides.forEach(function (_, i) { var d = document.createElement('button'); d.type = 'button'; d.setAttribute('aria-label', 'Case ' + (i + 1)); d.onclick = function () { go(i, true); }; dots.appendChild(d); });
    var go = function (i, manual) {
      idx = (i + n) % n; track.style.transform = 'translateX(' + (-idx * 100) + '%)';
      [].slice.call(dots.children).forEach(function (d, j) { d.classList.toggle('on', j === idx); });
      if (manual) restart();
    };
    var restart = function () { clearInterval(auto); if (!reduce) auto = setInterval(function () { if (!document.hidden) go(idx + 1); }, 7000); };
    $('car-prev').onclick = function () { go(idx - 1, true); };
    $('car-next').onclick = function () { go(idx + 1, true); };
    car.addEventListener('mouseenter', function () { clearInterval(auto); });
    car.addEventListener('mouseleave', restart);
    car.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    car.addEventListener('touchend', function (e) { if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; x0 = null; if (Math.abs(dx) > 40) go(idx + (dx < 0 ? 1 : -1), true); }, { passive: true });
    document.addEventListener('keydown', function (e) { if (e.target.closest && e.target.closest('#car')) { if (e.key === 'ArrowRight') go(idx + 1, true); if (e.key === 'ArrowLeft') go(idx - 1, true); } });
    go(0); restart();
  }

  // Headline decrypt on load.
  document.querySelectorAll('[data-dec]').forEach(function (h) { decrypt(h, 950); });

  // Status lights: one flashes briefly every so often.
  var lights = [].slice.call(document.querySelectorAll('.lt'));
  if (lights.length && !reduce) {
    setInterval(function () {
      if (document.hidden) return;
      var l = lights[Math.floor(Math.random() * lights.length)];
      l.classList.add('on');
      setTimeout(function () { l.classList.remove('on'); }, 420);
    }, 650);
  }

  // Active section in the nav (home page only).
  var navA = [].slice.call(document.querySelectorAll('.links a[href^="#"]'));
  var secs = navA.map(function (a) { return document.querySelector(a.getAttribute('href')); });
  function onScroll() {
    var cur = -1;
    secs.forEach(function (el, i) { if (el && el.getBoundingClientRect().top <= innerHeight * 0.4) cur = i; });
    navA.forEach(function (a, i) { a.classList.toggle('cur', i === cur); });
  }
  if (navA.length) { addEventListener('scroll', onScroll, { passive: true }); onScroll(); }

  // Interactive panel: terminal sessions, code and logs, typed line by line.
  var main = $('main'), view = $('view'), side = $('side');
  if (main && view && side) {
    var esc = function (x) { return String(x).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); };
    var run = function (log, rows) { return { kind: 'run', log: log, rows: rows }; };
    // Each scene: typed lines [column, text, class]. Column "$" is a prompt, a number is a code line, "" is plain output, a time is a log.
    var D = {
      'netscan': Object.assign(run([
        ['$', 'lab-scan --network lab --report', 'ac'],
        ['', 'inventory of my own lab network · 256 addresses · 3 hosts up', ''],
        ['', 'gw.lab      ssh, dns, https          up to date', 'ok'],
        ['', 'nas.lab     file sharing, admin ui   admin panel without TLS', 'warn'],
        ['', 'cam.lab     telnet                   factory firmware, default service', 'bad'],
        ['', 'report → 1 high, 1 medium, written to lab/2026-10-29.md', ''],
        ['', 'fix → camera on its own VLAN, admin panel behind TLS, the same evening', 'ok'],
        ['', 'done · 41 s · next run scheduled weekly', 'ac']
      ], [
        ['One forgotten device is the whole story', 'A camera on factory firmware is what a scan of a home or office network finds first. It went to its own network segment that evening.', '#toolkit', 'h', 'HIGH'],
        ['Inventory every week', 'A scheduled scan of my own network, diffed against last week: a new device or a new open service is a line in a report, not a surprise.', '#toolkit', 'l', 'WEEKLY'],
        ['Scope', 'My own lab, my own hardware. Security tooling runs only where I own the machines or hold written authorization.', '#contact', 'l', 'SCOPE'],
        ['What I do with it for clients', 'The same habit, applied to a server estate: a quick scan with a written report and a fix list.', 'https://scuttle.dev/services/security-quick-scan', 'l', '→']
      ]), { title: 'zsh · weekly scan of my lab network', done: 'done · 3 hosts · 2 findings' }),
      'hardening': Object.assign(run([
        ['$', 'ssh deploy@web-02 sudo harden --check', 'ac'],
        ['', 'sshd       key only, root login off, port 22 limited to the office and the VPN', 'ok'],
        ['', 'firewall   default deny · 22, 80, 443 open · everything else closed', 'ok'],
        ['', 'fail2ban   active · 2 bans in the last 24 h', 'ok'],
        ['', 'updates    unattended security updates on · last reboot 6 days ago', 'ok'],
        ['', 'tls        app.example.nl valid 71 days · HSTS · TLS 1.2 and 1.3 only', 'ok'],
        ['', 'secrets    none in the repository · .env outside the web root · keys 0600', 'ok'],
        ['', 'backups    nightly dump · off-site copy · last restore test 12 days ago', 'warn'],
        ['', 'done · 1 warning · restore test due this week', 'ac']
      ], [
        ['Eight checks, one warning', 'SSH, firewall, bans, updates, TLS, secrets, backups: the baseline every server I run passes, printed in one command.', '#toolkit', 'l', 'BASE'],
        ['A backup counts after a restore', 'The dump exists; the restore test is twelve days old, so it is due. The warning is the point of the check.', '#toolkit', 'm', 'DUE'],
        ['Rebuild instead of repair', 'All of this comes from the same playbook, so a misbehaving server is rebuilt from the file with last night\'s data.', '#toolkit', 'l', 'IAC'],
        ['Sold as', 'Hardening and secure pipeline, at Scuttle Development.', 'https://scuttle.dev/services/hardening', 'l', '→']
      ]), { title: 'zsh · server hardening check (web-02)', done: 'done · 1 warning' }),
      'npm-build': Object.assign(run([
        ['$', 'npm run build', 'ac'],
        ['', '> site@2.4.0 build', ''],
        ['', '> vite build', ''],
        ['', 'vite v6.3.5 building for production...', ''],
        ['', '✓ 184 modules transformed.', 'ok'],
        ['', 'dist/index.html                  2.41 kB │ gzip:  1.02 kB', ''],
        ['', 'dist/assets/index-Bd4k2x.css    38.20 kB │ gzip:  7.91 kB', ''],
        ['', 'dist/assets/index-C9s1pQ.js    142.77 kB │ gzip: 46.30 kB', ''],
        ['', '✓ built in 1.84s', 'ok'],
        ['$', 'npm audit --omit=dev', 'ac'],
        ['', 'found 0 vulnerabilities', 'ok'],
        ['$', 'npx playwright test', 'ac'],
        ['', '12 passed (14.2s)', 'ok']
      ], [
        ['1.84 s build, 46 kB of JS', 'A front end the size the page needs; no framework for a page that does not need one.', '#toolkit', 'l', 'VITE'],
        ['Audit clean', 'Production dependencies audited on every build; a known vulnerability blocks the release.', '#toolkit', 'l', 'AUDIT'],
        ['12 browser tests', 'Playwright runs the real pages, light and dark, phone and desktop, before anything deploys.', '#toolkit', 'l', 'E2E'],
        ['This site, the same way', 'One stylesheet, one script, tested in the pipeline, deployed from main.', 'https://github.com/spdotdev/splotnikov-dev', 'l', '→']
      ]), { title: 'zsh · npm run build (Vite, Playwright)', done: 'done · 1.84 s · 12 tests' }),
      'artisan': Object.assign(run([
        ['$', 'php artisan migrate --force', 'ac'],
        ['', 'INFO  Running migrations.', ''],
        ['', '2026_10_12_000001_add_index_to_bookings_table ........ 212 ms DONE', 'ok'],
        ['', '2026_10_12_000002_backfill_booking_status ............ 1.8 s DONE', 'ok'],
        ['$', 'php artisan test --parallel', 'ac'],
        ['', 'PASS  Tests\\Feature\\BookingFlowTest', 'ok'],
        ['', 'PASS  Tests\\Feature\\InvoiceExportTest', 'ok'],
        ['', 'Tests: 312 passed (1 048 assertions) · Duration: 41.2 s', 'ok'],
        ['$', 'vendor/bin/phpstan analyse --level=6 --memory-limit=512M', 'ac'],
        ['', '[OK] No errors', 'ok'],
        ['$', 'php artisan queue:work --once', 'ac'],
        ['', 'Processing: App\\Jobs\\SyncChannelManager ............ 1.2 s DONE', 'ok']
      ], [
        ['Migrations without downtime', 'Expand first, backfill, contract later: a column is never dropped in the same release that stops writing it.', '#proof', 'l', 'SAFE'],
        ['312 tests, level 6', 'The suite and static analysis run on every push; nothing merges red.', '#toolkit', 'l', 'CI'],
        ['Phalcon to Laravel', 'The same commands run on a platform that started on Phalcon 3.4 and PHP 7.4, migrated while in daily use.', '#proof', 'l', 'CASE'],
        ['Read the case', 'Sixty percent lower latency, PHP back in support, the hotels kept working.', '#proof', 'l', '→']
      ]), { title: 'zsh · php artisan (Laravel 12, PHP 8.4)', done: 'done · 312 tests green' }),
      'ansible': Object.assign(run([
        ['$', 'ansible-playbook site.yml --limit web-02 --diff', 'ac'],
        ['', 'PLAY [web] *********************************************', ''],
        ['', 'TASK [base : apt security updates] ............ changed: [web-02]', 'ok'],
        ['', 'TASK [base : sshd, key only, no root] ......... ok: [web-02]', 'ok'],
        ['', 'TASK [base : firewall 22/80/443, default deny]  changed: [web-02]', 'ok'],
        ['', 'TASK [base : fail2ban] ........................ ok: [web-02]', 'ok'],
        ['', 'TASK [docker : engine and compose] ............ ok: [web-02]', 'ok'],
        ['', 'TASK [app : pull image v2.31.0] ............... changed: [web-02]', 'ok'],
        ['', 'TASK [app : health check /up] ................. ok: [web-02] 200', 'ok'],
        ['', 'TASK [backup : nightly dump to object storage]  ok: [web-02]', 'ok'],
        ['', 'PLAY RECAP  web-02 : ok=14 changed=4 unreachable=0 failed=0', 'ac']
      ], [
        ['A server from a file', 'Hardening, Docker, the app, backups and alerts in one playbook; staging and production are the same machine.', '#toolkit', 'l', 'IAC'],
        ['Rebuild instead of repair', 'A misbehaving server is rebuilt from the playbook with last night\'s data; no hand-made snowflakes.', '#toolkit', 'l', 'OK'],
        ['Restore tested', 'A backup counts only after it has been restored on a scratch box and timed.', '#toolkit', 'l', 'DR'],
        ['How I work', 'Infrastructure as code, written down in your repository, owned by you from day one.', '#manifesto', 'l', '→']
      ]), { title: 'zsh · ansible-playbook (DigitalOcean, Docker)', done: 'done · 4 changed · 0 failed' }),
      'release': Object.assign(run([
        ['$', 'git push origin feat/channel-sync', 'ac'],
        ['', 'remote: Create a pull request for feat/channel-sync on GitHub', ''],
        ['', '✓ ci · lint, phpstan, 312 tests · 1 min 40 s', 'ok'],
        ['', '✓ security · composer audit, secret scan, SAST · clean', 'ok'],
        ['', '✓ review · agent review posted 2 comments · human approved', 'ok'],
        ['$', 'gh pr merge 412 --squash', 'ac'],
        ['', '✓ squashed and merged pull request #412', 'ok'],
        ['', '✓ build · image tagged v2.31.0', 'ok'],
        ['', '✓ deploy · staging smoke test 200 → production', 'ok'],
        ['', '✓ post-deploy · migrations ok · error rate flat · rollback image kept', 'ok'],
        ['', 'done · 9 min from push to production · nobody touched a server', 'ac']
      ], [
        ['9 minutes to production', 'Tests, security gates, review, build, staging, production, in one pipeline that fails fast.', '#toolkit', 'l', 'CI/CD'],
        ['Nothing merges red', 'A failing test or a leaked secret stops the release before a person has to notice.', '#toolkit', 'l', 'GATE'],
        ['Rollback in one command', 'The previous image stays tagged; undoing a release takes a minute, not a night.', '#toolkit', 'l', 'SAFE'],
        ['Strangler Fig, release by release', 'Every module moved off the old framework shipped like this: small and reversible.', '#proof', 'l', '→']
      ]), { title: 'zsh · git push → production (GitHub Actions)', done: 'done · 9 min' }),
      'strangler': Object.assign(run([
        ['1', '// Strangler Fig: the new service takes one route at a time.', 'cm'],
        ['2', 'import { legacy } from \'./legacy\'        // the old monolith, untouched', ''],
        ['3', 'import { bookings } from \'./bookings\'    // the new module', ''],
        ['4', '', ''],
        ['5', 'router.use(\'/api/v2/bookings\', bookings)           // migrated', 'ok'],
        ['6', 'router.use(\'/api/v2/invoices\', bookings.invoices)  // migrated', 'ok'],
        ['7', 'router.use(\'/api\', legacy)                         // everything else, for now', ''],
        ['8', '', ''],
        ['9', '// One flag per module: flip back in seconds if a release misbehaves.', 'cm'],
        ['10', 'if (flags.on(\'bookings.legacy\')) router.use(\'/api/v2/bookings\', legacy)', 'warn']
      ], [
        ['One route at a time', 'The new code takes over a route when it is ready; the old code keeps serving everything else.', '#manifesto', 'l', 'PATTERN'],
        ['A flag per module', 'Each migrated module can go back to the old code in seconds; there is no big-bang cutover.', '#proof', 'l', 'SAFE'],
        ['The monolith stays untouched', 'The old code keeps serving until its last route is gone, then it is deleted, not rewritten.', '#manifesto', 'l', 'OK'],
        ['Read the case', 'Phalcon to Laravel, migrated while hotels used the platform every day.', '#proof', 'l', '→']
      ]), { title: 'router.ts · Strangler Fig in ten lines', done: 'open · 10 lines' }),
      'bridge': Object.assign(run([
        ['1', '<?php', ''],
        ['2', '// Bridge: a request handled by the new framework, or passed to the old one untouched.', 'cm'],
        ['3', 'final class Bridge', 'kw'],
        ['4', '{', ''],
        ['5', '    public function handle(Request $request, Closure $next): Response', ''],
        ['6', '    {', ''],
        ['7', '        if (! $this->migrated->matches($request->path())) {', ''],
        ['8', '            return $this->phalcon->handle($request);   // old framework, same session', 'warn'],
        ['9', '        }', ''],
        ['10', '        return $next($request);                      // new Laravel module', 'ok'],
        ['11', '    }', ''],
        ['12', '}', '']
      ], [
        ['Two frameworks, one session', 'Login, session and database are shared, so a user never notices which framework answered.', '#proof', 'l', 'BRIDGE'],
        ['Migrated routes in a list', 'The list grows by one module per release; the bridge is deleted together with the last old route.', '#proof', 'l', 'LIST'],
        ['PHP 7.4 to 8.4 on the way', 'The runtime was upgraded during the migration, so the system stayed in support throughout.', '#proof', 'l', 'OK'],
        ['Read the case', 'Sixty percent lower latency, a supported runtime, a codebase developers know.', '#proof', 'l', '→']
      ]), { title: 'Bridge.php · old and new framework side by side', done: 'open · 12 lines' }),
      'claude': Object.assign(run([
        ['$', 'claude -p "triage overnight tickets, draft answers, never send"', 'ac'],
        ['', 'skill ticket-triage → helpdesk: 23 new tickets overnight', ''],
        ['', 'classify → 14 how-to, 6 bugs, 3 billing', ''],
        ['', 'how-to → answered from the manual, sources linked (14)', 'ok'],
        ['', 'bugs → reproduced 4, logs attached, 2 need more info', 'warn'],
        ['', 'billing → never automated, handed to a person (3)', ''],
        ['', 'priority → P1: 1 (payment page down)', 'bad'],
        ['', 'P1 → on-call paged ✓', 'ok'],
        ['', 'cost → $0.84 · cap $5 a day · evaluation set 48/48 passed', 'ok'],
        ['', 'done · 23 triaged in 91 s · nothing sent without a human tap', 'ac']
      ], [
        ['14 drafts before 07:02', 'Answered from the product manual with the paragraph quoted; a person sees them before anyone else does.', '#proof', 'l', 'AGENT'],
        ['A skill is a checklist', 'What to read, how to write, what never to include. The procedure lives in the repository, not in a prompt someone remembers.', '#toolkit', 'l', 'SKILL'],
        ['Guardrails', 'An evaluation set before any change ships, a cost cap, an audit log, a switch-off in the runbook.', '#toolkit', 'l', 'OK'],
        ['The sales case', 'Sixteen hours a week reclaimed the same way.', '#proof', 'l', '→']
      ]), { title: 'zsh · claude -p (agent run with a skill)', done: 'done · 23 triaged · 91 s' }),
      'applog': Object.assign(run([
        ['$', 'tail -f storage/logs/app.log', 'ac'],
        ['09:14:01', 'INFO  server listening on :8080 · php 8.4.27 · opcache warm', ''],
        ['09:14:02', 'INFO  db connected · 4 ms · redis ok', ''],
        ['09:15:10', 'GET   /api/health 200 · 2 ms', 'ok'],
        ['09:17:22', 'POST  /api/sync 201 · 45 ms · 312 rows', 'ok'],
        ['09:20:00', 'INFO  scheduler: channel-sync done · 1.2 s', 'ok'],
        ['09:21:48', 'WARN  queue: retry 2/3 SyncChannelManager · upstream 503', 'warn'],
        ['09:21:51', 'INFO  queue: SyncChannelManager done · 1.9 s', 'ok'],
        ['09:30:00', 'INFO  backup: dump 412 MB → object storage · checksum verified', 'ok'],
        ['09:30:04', 'INFO  certificate app.example.nl valid 71 days', 'ok'],
        ['09:40:00', 'INFO  health check passed · error rate flat · p95 118 ms', 'ok']
      ], [
        ['A retry, not a page', 'An upstream hiccup at 09:21, retried and done ten seconds later; a person reads it in the morning report, not at night.', '#toolkit', 'l', 'QUEUE'],
        ['p95 118 ms', 'Latency watched per route after the migration; the number that fell by sixty percent.', '#proof', 'l', 'PERF'],
        ['Backups verified daily', 'Dumped, shipped off-site, checksum compared, restore tested every month.', '#toolkit', 'l', 'DR'],
        ['How this is built', 'Monitoring and alerts to one phone, in every system I run.', '#toolkit', 'l', '→']
      ]), { title: 'zsh · tail -f app.log (production, anonymized)', done: 'live · last line 09:40' })
    };
    var pb = $('pb'), stt = $('stt'), sti = $('sti'), shH = $('sh-h'), timers = [], cur = null;
    var clear = function () { timers.forEach(clearTimeout); timers = []; };
    var later = function (f, ms) { timers.push(setTimeout(f, ms)); };
    var show = function (k) {
      var d = D[k]; if (!d || cur === k) return; cur = k; clear();
      [].slice.call(side.querySelectorAll('.it')).forEach(function (b) { b.classList.toggle('on', b.dataset.k === k); b.style.setProperty('--p', 0); });
      main.scrollTop = 0;
      shH.textContent = d.title; stt.textContent = d.kind === 'run' ? 'running' : 'opened'; sti.className = ''; pb.style.width = '0';
      view.style.animation = 'none'; void view.offsetWidth; view.style.animation = '';
      if (d.kind === 'run') {
        view.innerHTML = '<div class="log" id="log"></div><div class="find" id="find"></div>';
        var log = $('log'), find = $('find');
        d.log.forEach(function (l, i) {
          var r = document.createElement('div');
          var tc = l[0] === '$' ? 't p' : (/^\d+$/.test(l[0]) ? 't n' : 't');
          r.innerHTML = '<span class="' + tc + '">' + esc(l[0]) + '</span><span class="' + l[2] + '">' + esc(l[1]) + '</span>';
          log.appendChild(r);
          later(function () { r.classList.add('in'); pb.style.width = ((i + 1) / d.log.length * 100) + '%'; }, 300 + i * (reduce ? 0 : 380));
        });
        d.rows.forEach(function (x) {
          var r = document.createElement('a'); r.className = 'row'; r.href = x[2]; r.title = 'See what this is about';
          r.innerHTML = '<div><b>' + esc(x[0]) + '</b><small>' + esc(x[1]) + '</small></div><span class="sev ' + x[3] + '">' + esc(x[4]) + '</span>';
          find.appendChild(r);
        });
        later(function () {
          stt.textContent = d.done; sti.className = 'ok';
          [].slice.call(find.children).forEach(function (r, i) { later(function () { r.classList.add('in'); }, i * 120); });
        }, 300 + d.log.length * (reduce ? 0 : 380));
      }
    };
    // Auto-rotate through the menu; a click pauses it for a while, hovering pauses it.
    var keys = [].slice.call(side.querySelectorAll('.it')).map(function (b) { return b.dataset.k; }), ROT = 11000, rotT = null, rotStart = 0, hold = false, hover = false, raf;
    var tick = function () {
      var on = side.querySelector('.it.on');
      if (on && !hold && !hover) on.style.setProperty('--p', Math.min(1, (Date.now() - rotStart) / ROT));
      raf = requestAnimationFrame(tick);
    };
    var rotate = function () {
      clearTimeout(rotT); rotStart = Date.now();
      rotT = setTimeout(function () {
        if (hold || hover || document.hidden) { rotate(); return; }
        show(keys[(keys.indexOf(cur) + 1) % keys.length]); rotate();
      }, ROT);
    };
    main.addEventListener('mouseenter', function () { hover = true; }); main.addEventListener('mouseleave', function () { hover = false; rotStart = Date.now(); rotate(); });
    side.addEventListener('mouseenter', function () { hover = true; }); side.addEventListener('mouseleave', function () { hover = false; });
    side.addEventListener('click', function (e) { var b = e.target.closest('.it'); if (b) { show(b.dataset.k); hold = true; clearTimeout(rotT); setTimeout(function () { hold = false; rotate(); }, 30000); } });
    var started = false, first = function () { if (started) return; started = true; show('netscan'); if (!reduce) { rotate(); raf = requestAnimationFrame(tick); } };
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) first(); }); }, { threshold: 0.2 }).observe(main);
    }
    setTimeout(first, 2500);
  }

  // Reveal on scroll, with a fallback so nothing stays hidden.
  var rv = document.querySelectorAll('.rv');
  if (rv.length && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
    }, { threshold: 0.1 });
    rv.forEach(function (e, i) { e.style.transitionDelay = (i % 4) * 60 + 'ms'; io.observe(e); });
  }
  setTimeout(function () { document.querySelectorAll('.rv:not(.in)').forEach(function (e) { e.classList.add('in'); }); }, 1500);

  // Modals: QR code, photo, map (the map iframe is created only when opened).
  function modal(id, openBtn) {
    var m = $(id); if (!m) return;
    var close = function () { m.classList.remove('active'); m.setAttribute('aria-hidden', 'true'); };
    var open = function () {
      m.classList.add('active'); m.setAttribute('aria-hidden', 'false');
      var f = m.querySelector('.frame[data-src]');
      if (f && !f.firstChild) { var i = document.createElement('iframe'); i.src = f.dataset.src; i.loading = 'lazy'; i.referrerPolicy = 'no-referrer-when-downgrade'; i.setAttribute('allowfullscreen', ''); i.title = 'Map'; f.appendChild(i); }
    };
    if (openBtn) openBtn.onclick = open;
    [].slice.call(m.querySelectorAll('[data-close], .ov, .x')).forEach(function (b) { b.addEventListener('click', close); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && m.classList.contains('active')) close(); });
  }
  modal('qr-modal', $('open-qr'));
  modal('pic-modal', $('open-pic'));
  modal('map-modal', $('open-map'));

  // Contact form: preselect the product from ?about= and scroll to a sent or failed form.
  var cf = $('cform');
  if (cf) {
    try {
      var about = new URLSearchParams(location.search).get('about'), sel = $('cf-about');
      if (about && sel) [].slice.call(sel.options).forEach(function (o) { if (o.value === about) sel.value = about; });
    } catch (e) {}
    if (cf.querySelector('.sent, .err')) setTimeout(function () { cf.scrollIntoView({ block: 'center' }); }, 100);
  }

  try { if (localStorage.getItem('lang') === 'nl') lang('nl'); } catch (e) {}
})();
