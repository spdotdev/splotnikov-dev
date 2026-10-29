@extends('splotnikov::layout')

@section('title', 'CV')
@section('description', 'Curriculum vitae of Stanislav Plotnikov: senior developer and solution architect in Eindhoven, 14 years of PHP, Laravel, Python and Linux. Printable, and downloadable as PDF.')
@section('canonical', 'https://splotnikov.dev/cv')

@section('head')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha384-Yv5O+t3uE3hunW8uyrbpPW3iw6/5/Y7HitWJBLgqfMoA36NogMmy+8wWZMpn3HWc" crossorigin="anonymous" defer></script>
@endsection

@section('content')
<article class="prod cv-page">
  <header class="wrap ph no-print">
    <div class="cv-head">
      <div>
        <span class="lbl dot">Curriculum vitae</span>
        <h1 data-dec>Stanislav Plotnikov</h1>
        <p class="lead">Senior developer and solution architect. Fourteen years of turning legacy debt into software that scales and stays in support. Available for freelance and contract roles.</p>
      </div>
      <div class="cv-actions"><button type="button" class="btn" id="btn-pdf">Download PDF</button><button type="button" class="btn o" id="btn-print">Print</button></div>
    </div>
  </header>

  <div class="wrap">
    <div class="cv" id="cv-content">
      <div class="cv-main">
        <div class="cv-print-head">
          <h2 class="cv-name">Stanislav Plotnikov</h2>
          <p class="cv-title">Senior Developer / Legacy Modernization and Solution Architect</p>
          <p class="cv-availability">Available for freelance and contract roles · Eindhoven, the Netherlands</p>
        </div>

        <h2>Executive summary</h2>
        <p>Pragmatic solution architect with 14 years of professional experience, focused on transforming legacy technical debt into scalable, high-performance infrastructure. Specialist in incremental modernization (Strangler Fig pattern) and modular monolith design. I prioritize business continuity and return on investment over industry trends, with migrations in daily use and long-term maintainability.</p>

        <h2>Core competencies</h2>
        <ul class="cv-list">
          <li><b>Architecture:</b> system design, modular monoliths, microservices feasibility, API gateway design.</li>
          <li><b>Engineering:</b> PHP (Laravel, Symfony, Phalcon expert), Node.js, Python (AI and LLM integration).</li>
          <li><b>Cloud and DevOps:</b> Docker, Ansible, CI/CD (GitHub Actions), Linux systems hardening.</li>
          <li><b>Strategic AI:</b> LLM orchestration, agentic workflows, automated system monitoring.</li>
        </ul>

        <h2>Professional experience</h2>
        <div class="job">
          <p class="job-title">Senior Developer / Startup Advisor</p>
          <ul class="cv-list">
            <li>Conducted in-depth market research for a startup entering the Brazilian specialty coffee market in the Tilburg region of the Netherlands.</li>
            <li>Delivered actionable market-entry insights on local consumer behavior, the competitive landscape and positioning, to guide the client's go-to-market decisions.</li>
          </ul>
        </div>
        <div class="job">
          <p class="job-title">Senior Developer / Modernization Engineer <span>Hosintra B.V.</span></p>
          <ul class="cv-list">
            <li>Upgraded the core application framework from Phalcon 3.4 to Laravel 12, opening a modern ecosystem and long-term vendor support.</li>
            <li>Migrated the runtime from PHP 7.4 to PHP 8.4, resolving every deprecation and modernizing the codebase to current language features.</li>
          </ul>
        </div>
        <div class="job">
          <p class="job-title">Senior Developer / Automation and AI Engineer <span>East-West Support</span></p>
          <ul class="cv-list">
            <li>Developed automated data processing pipelines in Python, increasing throughput and automation speed by 40 percent.</li>
            <li>Architected and built a scalable Voice AI phone call automation platform for customer support, replacing the client's support workforce requirement.</li>
          </ul>
        </div>
        <div class="job">
          <p class="job-title">Senior Developer / API Integration Engineer <span>Hosintra B.V.</span></p>
          <ul class="cv-list">
            <li>Engineered a custom middleware abstraction layer for complex vendor API integrations, cutting technical debt, removing the client migration risk and saving the product.</li>
          </ul>
        </div>

        <h2>Employment history</h2>
        <div class="roles">
          <div><b>Getnoticed</b><small>Recruitment marketing · PHP / Symfony</small><span>Employer branding and conversion-driven career websites for the labor market.</span></div>
          <div><b>Bek</b><small>Omnichannel communications · PHP / Laravel</small><span>Bridged physical print and digital marketing portals so brands communicate consistently with customers.</span></div>
          <div><b>New Brand Activators</b><small>Brand activation · PHP / Laravel</small><span>Physical and digital brand interaction platforms that drive customer loyalty and engagement.</span></div>
          <div><b>Mijnwebwinkel</b><small>E-commerce / SaaS · PHP / Symfony</small><span>Built and maintained the SaaS infrastructure that lets businesses launch and run online stores.</span></div>
          <div><b>@Leisure Group</b><small>E-commerce / travel · PHP / Symfony</small><span>A digital travel platform processing bookings and payments for vacation rentals.</span></div>
          <div><b>Van Os Beheer &amp; Beleggingen BV</b><small>Finance / telecom · PHP</small><span>Digital marketing and telecom solutions within a financial holding group.</span></div>
        </div>

        <h2>Key projects</h2>
        <div class="job">
          <p class="job-title">Legacy modernization: Phalcon to Laravel</p>
          <p><b>Challenge:</b> the client was stuck on an unmaintainable legacy framework with a high vendor lock-in risk.</p>
          <p><b>Solution:</b> a bridge layer that runs both frameworks in parallel. High-risk modules moved first while the legacy system stayed operational.</p>
          <p><b>Result:</b> lower total cost of ownership, and the client ships new features 30 percent faster.</p>
        </div>
        <div class="job">
          <p class="job-title">Automation and AI-driven workflow</p>
          <p><b>Challenge:</b> manual data entry and filtering created bottlenecks in the client's operations.</p>
          <p><b>Solution:</b> LLM agents integrated into the existing workflows for intelligent automation of sales.</p>
          <p><b>Result:</b> 16 hours of manual work a week automated.</p>
        </div>

        <h2>Education</h2>
        <p>MBO 4 IT Administration, <a href="https://radiuscollege.nl" rel="noopener" target="_blank">Radius College Breda</a>.</p>
      </div>

      <aside class="cv-side">
        <img class="cv-photo" src="{{ secure_asset('vendor/splotnikov/portrait.jpg') }}" alt="Stanislav Plotnikov" width="160" height="160">
        <div class="blk"><small>Contact</small><span>Eindhoven, the Netherlands</span><a href="tel:+31634500328">+31 (0) 6 345 00 328</a><a href="mailto:stplotnikov@gmail.com">stplotnikov@gmail.com</a><a href="https://splotnikov.dev">splotnikov.dev</a></div>
        <div class="blk"><small>Links</small><a href="https://www.linkedin.com/in/stanislavplotnikov/">linkedin.com/in/stanislavplotnikov</a><a href="https://github.com/spdotdev">github.com/spdotdev</a></div>
        <div class="blk"><small>Business details</small><span>Scuttle Development</span><a href="tel:+31618233175">+31 (0) 6 182 33 175</a><a href="mailto:info@scuttle.dev">info@scuttle.dev</a><span>KVK 96040947</span><span>VAT NL005184584B85</span></div>
        <div class="blk"><small>Expertise</small>
          <em>Backend</em><div class="tags"><span>PHP</span><span>Laravel</span><span>Symfony</span><span>Phalcon</span><span>Python</span></div>
          <em>Infrastructure</em><div class="tags"><span>Docker</span><span>Linux</span><span>Ansible</span><span>CI/CD</span></div>
          <em>AI</em><div class="tags"><span>Claude</span><span>MCP</span><span>Cursor</span><span>Gemini</span><span>OpenRouter</span></div>
        </div>
        <div class="blk"><small>Languages</small><div class="tags"><span>English</span><span>Dutch</span><span>Russian</span></div></div>
      </aside>
    </div>
  </div>

  <section class="cta-end no-print"><div class="wrap">
    <div class="cta-box rv">
      @include('splotnikov::partials.logo', ['id' => 'cta', 'w' => 40, 'h' => 40])
      <h2>Want this on paper, or in your inbox?</h2>
      <p>Download the PDF above, or write me and I send it with a short note on what I could do for your system.</p>
      <div class="row"><a class="btn" href="{{ route('splotnikov.contact.page') }}">Use the contact form</a><a class="btn o" href="{{ route('splotnikov.home') }}#proof">See the case studies</a></div>
    </div>
  </div></section>
</article>
@endsection

@section('scripts')
<script>
(function () {
  var el = document.getElementById('cv-content'), pdf = document.getElementById('btn-pdf'), prt = document.getElementById('btn-print');
  if (prt) prt.onclick = function () { window.print(); };
  if (pdf) pdf.onclick = function () {
    if (!window.html2pdf) { window.print(); return; }
    var was = document.documentElement.getAttribute('data-theme');
    document.documentElement.setAttribute('data-theme', 'light');
    el.classList.add('pdf');
    pdf.disabled = true; pdf.textContent = 'Building PDF…';
    window.html2pdf().set({
      margin: [10, 10, 12, 10], filename: 'Stanislav_Plotnikov_CV.pdf',
      image: { type: 'jpeg', quality: .98 },
      html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff', windowWidth: 1100 },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
      pagebreak: { mode: ['css', 'legacy'], avoid: ['.job', '.roles > div', '.blk'] }
    }).from(el).save().then(done, done);
    function done() { el.classList.remove('pdf'); if (was) document.documentElement.setAttribute('data-theme', was); else document.documentElement.removeAttribute('data-theme'); pdf.disabled = false; pdf.textContent = 'Download PDF'; }
  };
})();
</script>
@endsection
