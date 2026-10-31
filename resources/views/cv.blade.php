@extends('splotnikov::layout')
@php($cv = \Spdotdev\SplotnikovDev\Cv::all())

@section('title', 'CV')
@section('description', 'Curriculum vitae of Stanislav Plotnikov: senior developer and solution architect in Eindhoven, PHP since 2008, Laravel, Python, AI agents and Linux. Printable, and downloadable as PDF.')
@section('canonical', 'https://splotnikov.dev/cv')

@section('head')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha384-Yv5O+t3uE3hunW8uyrbpPW3iw6/5/Y7HitWJBLgqfMoA36NogMmy+8wWZMpn3HWc" crossorigin="anonymous" defer></script>
  <script type="application/ld+json">{!! json_encode(['@'.'context' => 'https://schema.org', '@type' => 'ProfilePage', 'url' => 'https://splotnikov.dev/cv', 'mainEntity' => ['@type' => 'Person', 'name' => 'Stanislav Plotnikov', 'jobTitle' => $cv['title'], 'url' => 'https://splotnikov.dev', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Eindhoven', 'addressCountry' => 'NL'], 'knowsLanguage' => ['nl', 'en', 'ru'], 'knowsAbout' => ['PHP', 'Laravel', 'Python', 'Legacy modernization', 'AI agents', 'Linux', 'Server hardening'], 'sameAs' => ['https://github.com/spdotdev', 'https://www.linkedin.com/in/stanislavplotnikov/', 'https://scuttle.dev']]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endsection

@section('content')
<article class="prod cv-page">
  <header class="wrap ph no-print">
    <div class="cv-head">
      <div>
        <span class="lbl dot" data-i="cv_lbl">Curriculum vitae</span>
        <h1 data-dec>Stanislav Plotnikov</h1>
        <p class="lead" data-i="cv_lead">{{ $cv['lead'] }}</p>
      </div>
      <div class="cv-actions"><div class="seg biz" role="group" aria-label="With or without company details" title="With or without the company details, on the page, in the PDF and in the print"><button type="button" data-biz="1" aria-pressed="true" data-i="cv_biz_on">Company</button><button type="button" data-biz="0" aria-pressed="false" data-i="cv_biz_off">Personal</button></div><button type="button" class="btn" id="btn-pdf" data-i="cv_pdf">Download PDF</button><button type="button" class="btn o" id="btn-print" data-i="cv_print">Print</button></div>
    </div>
  </header>

  <div class="wrap no-print">
    <div class="plate rv glance-cv">
      <span class="lbl dot" data-i="cv_g_l">At a glance</span>
      <dl>
        <div><dt data-i="cv_g1t">Work I do</dt><dd data-i="cv_g1d">Legacy modernization, automation with AI agents, security hardening. PHP and Laravel first, Python and Linux next to it.</dd></div>
        <div><dt data-i="cv_g2t">Form</dt><dd data-i="cv_g2d">Freelance and contract, through my company Scuttle Development. EUR 90 per hour excl. VAT, written down on <a href="https://scuttle.dev/pricing">scuttle.dev/pricing</a>.</dd></div>
        <div><dt data-i="cv_g3t">Where</dt><dd data-i="cv_g3d">Eindhoven. On site in Brabant, remote for the rest of the Netherlands and Europe.</dd></div>
        <div><dt data-i="cv_g4t">Languages</dt><dd data-i="cv_g4d">Dutch, English, Russian. I write documents in Dutch or English.</dd></div>
        <div><dt data-i="cv_g5t">Look before you call</dt><dd data-i="cv_g5d">Sixteen public repositories on <a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a>, the <a href="{{ route('splotnikov.projects') }}">projects page</a> and the <a href="{{ route('splotnikov.blog') }}">blog</a>.</dd></div>
      </dl>
    </div>
  </div>

  <div class="wrap">
    <div class="cv" id="cv-content">
      <div class="cv-main">
        <div class="cv-print-head">
          <h2 class="cv-name">Stanislav Plotnikov</h2>
          <p class="cv-title" data-i="cv_title">{{ $cv['title'] }}</p>
          <p class="cv-availability" data-i="cv_avail">{{ $cv['availability'] }}</p>
        </div>

        @foreach($cv['sections'] as $sk => $sec)
        <h2 data-i="cv_s_{{ $sk }}">{{ $sec['title'] }}</h2>
        @if(isset($sec['text']))<p data-i="cv_t_{{ $sk }}">{{ $sec['text'] }}</p>@endif
        @if(isset($sec['items']))<ul class="cv-list">@foreach($sec['items'] as $i => $it)<li data-i="cv_i_{{ $sk }}_{{ $i }}"><b>{{ $it[0] }}:</b> {{ $it[1] }}</li>@endforeach</ul>@endif
        @if(isset($sec['jobs']))
          @foreach($sec['jobs'] as $j => $job)
          <div class="job">
            <p class="job-title"><span data-i="cv_j_{{ $sk }}_{{ $j }}">{{ $job['role'] }}</span>@if($job['company'])<span>{{ $job['company'] }}</span>@endif</p>
            @if(isset($job['bullets']))<ul class="cv-list">@foreach($job['bullets'] as $b => $line)<li data-i="cv_jb_{{ $sk }}_{{ $j }}_{{ $b }}">{{ $line }}</li>@endforeach</ul>@endif
            @if(isset($job['lines']))@foreach($job['lines'] as $b => $line)<p data-i="cv_jl_{{ $sk }}_{{ $j }}_{{ $b }}"><b>{{ $line[0] }}:</b> {{ $line[1] }}</p>@endforeach @endif
          </div>
          @endforeach
        @endif
        @if(isset($sec['roles']))
          <div class="roles">@foreach($sec['roles'] as $r => $role)<div><b>{{ $role[0] }}</b><small data-i="cv_rs_{{ $r }}">{{ $role[1] }}</small><span data-i="cv_rd_{{ $r }}">{{ $role[2] }}</span></div>@endforeach</div>
        @endif
        @endforeach

        <h2 data-i="cv_s_edu">Education</h2>
        <p data-i="cv_t_edu">MBO 4 IT Administration, <a href="https://www.linkedin.com/company/radius-college/" rel="noopener" target="_blank">Radius College Breda</a>.</p>
      </div>

      <div class="cv-side">
        <img class="cv-photo" src="{{ secure_asset('vendor/splotnikov/portrait.jpg') }}" alt="Stanislav Plotnikov" width="160" height="160">
        <div class="blk"><small data-i="cv_b_contact">Contact</small><span data-i="cv_b_where">Eindhoven, the Netherlands</span><span>+31 (0) 6 345 00 328</span><span>stplotnikov@gmail.com</span><a href="https://splotnikov.dev">splotnikov.dev</a></div>
        <details class="blk fold" id="cv-biz" open><summary><small data-i="cv_b_biz">Business details</small></summary><div class="fold-body"><span>Scuttle Development</span><span>+31 (0) 6 182 33 175</span><span>info@scuttle.dev</span><span>KvK 96040947</span><span>BTW NL005184584B85</span><a href="https://scuttle.dev" rel="noopener" target="_blank">scuttle.dev</a></div></details>
        <details class="blk fold" open><summary><small data-i="cv_b_links">Links</small></summary><div class="fold-body"><a href="https://www.linkedin.com/in/stanislavplotnikov/">linkedin.com/in/stanislavplotnikov</a><a href="https://github.com/spdotdev">github.com/spdotdev</a><a href="https://scuttle.dev" title="Scuttle Development, my company">scuttle.dev</a></div></details>
        <details class="blk fold" open><summary><small data-i="cv_b_exp">Expertise</small></summary><div class="fold-body">
          @foreach($cv['expertise'] as $ek => $eg)
          <em data-i="cv_e_{{ $ek }}">{{ $eg[0] }}</em><div class="tags">@foreach($eg[1] as $t)<span>{{ $t }}</span>@endforeach</div>
          @endforeach
        </div></details>
        <details class="blk fold" open><summary><small data-i="cv_b_lang">Spoken languages</small></summary><div class="fold-body"><div class="tags"><span data-i="cv_b_l1">English</span><span data-i="cv_b_l2">Dutch</span><span data-i="cv_b_l3">Russian</span></div></div></details>
      </div>
    </div>
  </div>

  <section class="cta-end no-print"><div class="wrap">
    <div class="cta-box rv">
      @include('splotnikov::partials.logo', ['id' => 'cta', 'w' => 40, 'h' => 40])
      <h2 data-i="cv_cta_h">Want this on paper, or in your inbox?</h2>
      <p data-i="cv_cta_p">Download the PDF above, or write me and I send it with a short note on what I could do for your system.</p>
      <div class="row"><a class="btn" href="{{ route('splotnikov.contact.page') }}" data-i="cv_cta_b1">Use the contact form</a><a class="btn o" href="{{ route('splotnikov.projects') }}" data-i="cv_cta_b2">See the projects</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, @json(\Spdotdev\SplotnikovDev\Cv::nl()));
</script>
@endpush

@section('scripts')
<script>
(function () {
  var el = document.getElementById('cv-content'), pdf = document.getElementById('btn-pdf'), prt = document.getElementById('btn-print');
  // Company details on or off, remembered in this browser; the PDF and the print follow the switch.
  var segBtns = [].slice.call(document.querySelectorAll('.seg.biz button'));
  var biz = function (on) { el.classList.toggle('no-biz', !on); segBtns.forEach(function (b) { b.setAttribute('aria-pressed', (b.dataset.biz === '1') === on); }); try { localStorage.setItem('cv_business', on ? '1' : '0'); } catch (e) {} };
  try { biz(localStorage.getItem('cv_business') !== '0'); } catch (e) {}
  segBtns.forEach(function (b) { b.onclick = function () { biz(b.dataset.biz === '1'); }; });
  // The four side blocks fold on screens up to 980 px (closed by default), stay open on the desktop, and are opened for print and the PDF.
  var folds = [].slice.call(document.querySelectorAll('.cv-side .fold')), closedBefore = [];
  if (window.matchMedia && window.matchMedia('(max-width: 980px)').matches) folds.forEach(function (d) { d.open = false; });
  var openFolds = function () { closedBefore = folds.filter(function (d) { return !d.open; }); closedBefore.forEach(function (d) { d.open = true; }); };
  var restoreFolds = function () { closedBefore.forEach(function (d) { d.open = false; }); closedBefore = []; };
  window.addEventListener('beforeprint', openFolds); window.addEventListener('afterprint', restoreFolds);
  if (prt) prt.onclick = function () { window.print(); };
  if (pdf) pdf.onclick = function () {
    if (!window.html2pdf) { window.print(); return; }
    var was = document.documentElement.getAttribute('data-theme');
    document.documentElement.setAttribute('data-theme', 'light');
    // Render from a fixed box at the top left of the viewport, so html2canvas has no scroll or centering offset to get wrong.
    var host = document.createElement('div'), mark = document.createElement('div');
    host.style.cssText = 'position:fixed;left:0;top:0;width:740px;background:#fff;z-index:-1;overflow:visible';
    el.parentNode.insertBefore(mark, el); host.appendChild(el); document.body.appendChild(host);
    el.classList.add('pdf'); openFolds();
    var label = pdf.textContent; pdf.disabled = true; pdf.textContent = '…';
    window.html2pdf().set({
      margin: [10, 10, 12, 10], filename: 'Stanislav_Plotnikov_CV.pdf',
      image: { type: 'jpeg', quality: .98 },
      html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff', scrollX: 0, scrollY: 0, x: 0, y: 0, width: 740, windowWidth: 740 },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
      pagebreak: { mode: ['css', 'legacy'], avoid: ['.job', '.roles > div', '.blk'] }
    }).from(el).save().then(done, done);
    function done() {
      el.classList.remove('pdf'); mark.parentNode.insertBefore(el, mark); mark.remove(); host.remove();
      if (was) document.documentElement.setAttribute('data-theme', was); else document.documentElement.removeAttribute('data-theme');
      pdf.disabled = false; pdf.textContent = label;
      restoreFolds();
    }
  };
})();
</script>
@endsection
