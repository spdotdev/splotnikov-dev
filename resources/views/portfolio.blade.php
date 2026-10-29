@extends('splotnikov::layout')

@section('title', 'Senior developer and solution architect, Eindhoven')
@section('description', 'Stanislav Plotnikov: legacy modernization, automation with AI agents and security. 14 years of PHP, Laravel, Python and Linux. Available for freelance and contract roles in and around Eindhoven, remote everywhere else.')
@section('canonical', 'https://splotnikov.dev/')
@section('ogtype', 'website')

@section('head')
  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@type": "Person",
    "name": "Stanislav Plotnikov",
    "jobTitle": "Senior developer and solution architect",
    "description": "Legacy modernization, automation with AI agents and security. PHP, Laravel, Phalcon, Python, Kotlin, Linux.",
    "url": "https://splotnikov.dev",
    "image": "{{ secure_asset('vendor/splotnikov/portrait.jpg') }}",
    "email": "stplotnikov@gmail.com",
    "address": { "@type": "PostalAddress", "addressLocality": "Eindhoven", "addressCountry": "NL" },
    "worksFor": { "@type": "Organization", "name": "Scuttle Development", "url": "https://scuttle.dev" },
    "knowsLanguage": ["en", "nl", "ru"],
    "sameAs": ["https://github.com/spdotdev", "https://www.linkedin.com/in/stanislavplotnikov/", "https://scuttle.dev"]
  }
  </script>
@endsection

@section('content')
<header class="wrap hero">
  <div class="portrait in1"><img src="{{ secure_asset('vendor/splotnikov/portrait.jpg') }}" alt="Stanislav Plotnikov" width="120" height="120"></div>
  <h1 data-i="h1" data-dec>Stanislav Plotnikov</h1>
  <div class="sub in2" data-i="sub">Senior developer · Solution architect · Eindhoven</div>
  <p class="hero-q in2" data-i="hq">I turn legacy technical debt into infrastructure that scales and keeps running, by putting return on investment before industry trends.</p>
  <div class="cta in3"><a class="btn" href="{{ route('splotnikov.cv') }}" data-i="cta1">View the CV <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a><a class="btn o" href="{{ route('splotnikov.contact.page') }}" data-i="cta2">Contact me</a><a class="btn o" href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a></div>
  <div class="cmd in3"><span>&gt; whoami&nbsp;&nbsp;<span>stas</span>&nbsp;&nbsp;<b data-i="cmd_b">14 years in PHP, Laravel and Linux · modernization · automation · security · freelance since 2024</b></span></div>
</header>

<div class="wrap demo-wrap">
  <div class="shot-hint in4" aria-hidden="true"><svg viewBox="0 0 120 70" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M112 6c-30 2-62 10-80 36"/><path d="M40 30l-9 13 15 3"/></svg><span data-i="hint">A live terminal, not a picture. Click through, or let it play.</span></div>
  <div class="shot in4" id="demo" aria-label="Interactive examples: terminal sessions, code and logs from my daily work">
    <aside class="side" id="side">
      <div class="h"><span data-i="sd_t">Terminal</span><span>6</span></div>
      <button class="it on" data-k="netscan" type="button"><i class="led"></i><span>lab-scan</span><small data-i="now">now</small></button>
      <button class="it" data-k="hardening" type="button"><i class="led"></i><span>harden --check</span><small>ssh</small></button>
      <button class="it" data-k="npm-build" type="button"><i class="led"></i><span>npm run build</span><small>vite</small></button>
      <button class="it" data-k="artisan" type="button"><i class="led"></i><span>php artisan</span><small>laravel</small></button>
      <button class="it" data-k="ansible" type="button"><i class="led"></i><span>ansible-playbook</span><small>ssh</small></button>
      <button class="it" data-k="release" type="button"><i class="led"></i><span>git push</span><small>ci</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_c">Code</span></div>
      <button class="it" data-k="strangler" type="button"><i class="led"></i><span>router.ts</span><small>ts</small></button>
      <button class="it" data-k="bridge" type="button"><i class="led"></i><span>Bridge.php</span><small>php</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_a">Agents and logs</span></div>
      <button class="it" data-k="claude" type="button"><i class="led"></i><span>claude -p</span><small>agent</small></button>
      <button class="it" data-k="applog" type="button"><i class="led"></i><span>tail -f app.log</span><small>live</small></button>
    </aside>
    <div class="main" id="main">
      <div class="top"><h4 id="sh-h">zsh · weekly scan of my lab network</h4><div class="st"><i id="sti"></i><span id="stt">running</span></div></div>
      <div class="prog"><i id="pb"></i></div>
      <div class="view" id="view"><div class="log" id="log"></div><div class="find" id="find"></div></div>
    </div>
  </div>
  <p class="shot-note" data-i="shot_note">Sessions are re-enacted from my own lab and projects, with made-up hosts and names. Security tools run only on networks and machines I own or am authorized to test.</p>
</div>

<section class="s" id="manifesto"><div class="wrap">
  <div class="center rv"><span class="lbl dot" data-i="mf_l">01 · The manifesto</span><h2 data-i="mf_h" style="margin-top:16px">Engineering for return, not for trends</h2><p data-i="mf_p">The most expensive line of code is the one that should never have been written. I do not follow cargo-cult architecture: while others over-engineer with microservices they cannot manage, I argue for modular monoliths and pragmatic scaling.</p></div>
  <div class="cards3">
    <div class="c rv"><span class="lbl">01</span><h3 data-i="mf1">Pragmatism over hype</h3><p data-i="mf1s">A stack is chosen on the talent pipeline and the total cost of ownership, not on GitHub stars.</p></div>
    <div class="c rv"><span class="lbl">02</span><h3 data-i="mf2">The Strangler Fig strategy</h3><p data-i="mf2s">No grand rewrites. Legacy debt is replaced piece by piece while the business keeps running, every release with a rollback.</p></div>
    <div class="c rv"><span class="lbl">03</span><h3 data-i="mf3">Systems thinking</h3><p data-i="mf3s">A ticket developer closes tasks. An architect anticipates the 24-month trade-off of every structural decision.</p></div>
  </div>
</div></section>

<section class="s tight" id="proof"><div class="wrap">
  <div class="gridh rv"><span class="lbl dot" data-i="pf_l">02 · Selected proof</span><h2 data-i="pf_h" style="margin-top:16px">Case studies</h2><p data-i="pf_p">Two pieces of work, told the way they went. The client names stay out; the numbers are real.</p></div>
  <div class="case-grid">
    <div class="c cg rv">
      <span class="lbl dot" data-i="c1_l">Legacy modernization</span>
      <h3 data-i="c1_h">Modernizing a monolith: Phalcon to Laravel</h3>
      <p data-i="c1_1"><b>The mess.</b> A client was trapped in a legacy Phalcon system on an unsupported PHP, unable to scale or to find specialized talent.</p>
      <p data-i="c1_2"><b>The solution.</b> A custom PHP middleware layer that lets both frameworks run side by side, then an incremental migration (the Strangler Fig pattern), module by module.</p>
      <p data-i="c1_3"><b>The impact.</b> 60 percent lower latency, PHP 7.4 to 8.4 back in support, and the hotels that run on the platform kept working through the whole migration.</p>
      <div class="facts3"><div><b>60%</b><span data-i="c1_f1">lower latency</span></div><div><b>7.4 → 8.4</b><span data-i="c1_f2">PHP, back in support</span></div><div><b data-i="c1_f3b">in use</b><span data-i="c1_f3">during the whole migration</span></div></div>
      <div class="tags"><span>PHP</span><span>Laravel</span><span>Phalcon</span><span>Strangler Fig</span></div>
    </div>
    <div class="c cg rv">
      <span class="lbl dot" data-i="c2_l">Automation with AI</span>
      <h3 data-i="c2_h">AI agents in the sales stack</h3>
      <p data-i="c2_1"><b>The mess.</b> Manual research and data entry were stalling a sales operation: hours a week of looking things up and typing them over.</p>
      <p data-i="c2_2"><b>The solution.</b> LLM agents integrated into the existing workflow: they research prospects, draft in the sender's words and queue for a human tap. Evaluation set, cost cap and a switch-off in the runbook.</p>
      <p data-i="c2_3"><b>The impact.</b> 16 hours a week of manual work reclaimed; nothing leaves the building without a person approving it.</p>
      <div class="facts3"><div><b>16 h</b><span data-i="c2_f1">a week reclaimed</span></div><div><b data-i="c2_f2b">human</b><span data-i="c2_f2">approval before any send</span></div><div><b data-i="c2_f3b">capped</b><span data-i="c2_f3">model cost per day</span></div></div>
      <div class="tags"><span>LLM</span><span>AI agents</span><span>Python</span><span>Automation</span></div>
    </div>
  </div>
</div></section>

<section class="s tight" id="toolkit"><div class="wrap">
  <div class="gridh rv"><span class="lbl dot" data-i="tk_l">03 · The toolkit</span><h2 data-i="tk_h" style="margin-top:16px">Technologies and practices</h2><p data-i="tk_p">The tools in daily use, not a wish list.</p></div>
  <div class="cards3">
    <div class="c g rv"><span class="lbl">01 <span data-i="tk1">Architecture and orchestration</span></span><h3 data-i="tk1h">Infrastructure that can be rebuilt from a file</h3><div class="pl">
      <a href="#toolkit"><i class="lt"></i><span>Docker, Ansible, Linux</span><small data-i="tk1a">Debian and RHEL hardening, infrastructure as code</small></a>
      <a href="#toolkit"><i class="lt"></i><span data-i="tk1b">Scalability audits</span><small data-i="tk1bs">Where it breaks first, and what it costs to fix</small></a>
      <a href="#toolkit"><i class="lt"></i><span data-i="tk1c">Disaster recovery</span><small data-i="tk1cs">Backups that have been restored, and timed</small></a>
    </div></div>
    <div class="c g rv"><span class="lbl">02 <span data-i="tk2">Backend engineering</span></span><h3 data-i="tk2h">PHP first, the right tool next to it</h3><div class="pl">
      <a href="#toolkit"><i class="lt"></i><span>PHP: Laravel, Phalcon, Symfony</span><small data-i="tk2a">14 years, from 5.x to 8.4, in production</small></a>
      <a href="#toolkit"><i class="lt"></i><span>Python, Node.js</span><small data-i="tk2b">Data processing, AI pipelines, event-driven services</small></a>
      <a href="#toolkit"><i class="lt"></i><span>MySQL, PostgreSQL, Redis</span><small data-i="tk2c">Schema design, migrations without downtime</small></a>
    </div></div>
    <div class="c g rv"><span class="lbl">03 <span data-i="tk3">DevOps, AI and security</span></span><h3 data-i="tk3h">Pipelines that block red, agents with guardrails</h3><div class="pl">
      <a href="#toolkit"><i class="lt"></i><span>GitHub Actions, CI/CD</span><small data-i="tk3a">Tests, static analysis, security gates, rollback image kept</small></a>
      <a href="#toolkit"><i class="lt"></i><span>Claude Code, MCP, LLM orchestration</span><small data-i="tk3b">Skills, evaluation sets, cost caps, a human step</small></a>
      <a href="#toolkit"><i class="lt"></i><span data-i="tk3c">Security testing</span><small data-i="tk3cs">Network inventory, web app testing, hardening; authorized scope only</small></a>
    </div></div>
  </div>
</div></section>

<section class="s tight" id="stack"><div class="wrap">
  <div class="plate belts rv">
    <div class="bh"><span class="lbl dot" data-i="st_l">Worked with</span><h2 data-i="st_h">Brands I have built for, tools I build with</h2><p data-i="st_p">In employment at agencies and product companies before 2024, and as a freelancer since.</p></div>
    <div class="belt" aria-label="Companies"><div class="track">
      @php($cos = ['Hosintra', 'East-West Support', 'Getnoticed', 'Bek', 'New Brand Activators', 'Mijnwebwinkel', '@Leisure Group', 'Van Os Beheer'])
      @for($r = 0; $r < 2; $r++)@foreach($cos as $co)<span class="tk"><i></i>{{ $co }}</span>@endforeach @endfor
    </div></div>
    <div class="belt rev" aria-label="Tools"><div class="track">
      @php($tools = ['PHP', 'Laravel', 'Phalcon', 'Symfony', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'Ansible', 'GitHub Actions', 'DigitalOcean', 'Python', 'Kotlin', 'Claude Code', 'MCP', 'Playwright', 'Arch Linux', 'Burp Suite'])
      @for($r = 0; $r < 2; $r++)@foreach($tools as $t)<span class="tk mono{{ in_array($t, ['Claude Code', 'MCP'], true) ? ' ai' : '' }}"><i></i>{{ $t }}</span>@endforeach @endfor
    </div></div>
  </div>
</div></section>

<section class="cta-end" id="contact"><div class="wrap">
  <div class="cta-box rv">
    @include('splotnikov::partials.logo', ['id' => 'cta', 'w' => 40, 'h' => 40])
    <h2 data-i="ct_h">Ready to cut the noise?</h2>
    <p data-i="ct_p">I am available for architecture consultations and modernization projects. If you want someone who takes ownership of your system's health instead of moving tickets, let's talk.</p>
    <div class="row"><a class="btn" href="{{ route('splotnikov.contact.page') }}" data-i="ct_b1">Use the contact form</a><a class="btn o" href="mailto:stplotnikov@gmail.com" data-i="ct_b2">Email stplotnikov@gmail.com</a><a class="btn o" href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a class="btn o" href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a></div>
  </div>
</div></section>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
  sub: "Senior developer · Solution architect · Eindhoven", hq: "Ik maak van technische schuld in legacy software infrastructuur die schaalt en blijft draaien, door rendement boven trends te zetten.", cta1: "Bekijk het CV", cta2: "Neem contact op", cmd_b: "14 jaar PHP, Laravel en Linux · modernisering · automatisering · beveiliging · freelance sinds 2024",
  hint: "Een live terminal, geen plaatje. Klik erdoorheen, of laat het lopen.", sd_t: "Terminal", now: "nu", sd_c: "Code", sd_a: "Agents en logs",
  shot_note: "Sessies zijn nagespeeld uit mijn eigen lab en projecten, met verzonnen hosts en namen. Securitytools draaien alleen op netwerken en machines die van mij zijn of die ik mag testen.",
  mf_l: "01 · Het manifest", mf_h: "Engineering voor rendement, niet voor trends", mf_p: "De duurste regel code is de regel die nooit geschreven had moeten worden. Ik volg geen cargo-cult-architectuur: waar anderen over-engineeren met microservices die ze niet kunnen beheren, pleit ik voor modulaire monolieten en pragmatisch schalen.",
  mf1: "Pragmatisme boven hype", mf1s: "Een stack wordt gekozen op de mensen die hem kunnen draaien en de totale kosten, niet op GitHub-sterren.", mf2: "De Strangler Fig-strategie", mf2s: "Geen grote herbouw. Legacy schuld wordt stuk voor stuk vervangen terwijl het bedrijf doordraait, elke release met een rollback.", mf3: "Systeemdenken", mf3s: "Een ticketdeveloper sluit taken af. Een architect voorziet de afweging van elke structurele keuze over 24 maanden.",
  pf_l: "02 · Geselecteerd bewijs", pf_h: "Casestudy's", pf_p: "Twee stukken werk, verteld zoals het ging. De klantnamen blijven eruit; de cijfers zijn echt.",
  c1_l: "Modernisering van legacy", c1_h: "Een monoliet moderniseren: Phalcon naar Laravel", c1_1: "<b>De puinhoop.</b> Een klant zat vast in een legacy Phalcon-systeem op een PHP zonder support, kon niet schalen en vond geen specialisten.", c1_2: "<b>De oplossing.</b> Een eigen PHP-middlewarelaag waarmee beide frameworks naast elkaar draaien, daarna een stapsgewijze migratie (het Strangler Fig-patroon), module voor module.", c1_3: "<b>Het effect.</b> 60 procent lagere latency, PHP 7.4 naar 8.4 weer in support, en de hotels op het platform bleven de hele migratie door werken.", c1_f1: "lagere latency", c1_f2: "PHP, weer in support", c1_f3b: "in gebruik", c1_f3: "tijdens de hele migratie",
  c2_l: "Automatisering met AI", c2_h: "AI-agents in de salesstack", c2_1: "<b>De puinhoop.</b> Handmatig onderzoek en data-invoer remden een salesoperatie: uren per week opzoeken en overtypen.", c2_2: "<b>De oplossing.</b> LLM-agents in de bestaande workflow: ze onderzoeken prospects, schrijven concepten in de woorden van de afzender en zetten ze klaar voor één menselijke tik. Evaluatieset, kostenplafond en een uitknop in het runbook.", c2_3: "<b>Het effect.</b> 16 uur handwerk per week terug; niets verlaat het pand zonder dat een mens het goedkeurt.", c2_f1: "per week terug", c2_f2b: "mens", c2_f2: "keurt goed vóór elke verzending", c2_f3b: "begrensd", c2_f3: "modelkosten per dag",
  tk_l: "03 · Het gereedschap", tk_h: "Technologieën en werkwijze", tk_p: "De tools van dagelijks gebruik, geen wensenlijst.",
  tk1: "Architectuur en orkestratie", tk1h: "Infrastructuur die uit een bestand herbouwd kan worden", tk1a: "Debian- en RHEL-hardening, infrastructuur als code", tk1b: "Schaalbaarheidsaudits", tk1bs: "Waar het als eerste breekt, en wat de fix kost", tk1c: "Disaster recovery", tk1cs: "Back-ups die hersteld zijn, met de tijd erbij",
  tk2: "Backend engineering", tk2h: "PHP eerst, het juiste gereedschap ernaast", tk2a: "14 jaar, van 5.x tot 8.4, in productie", tk2b: "Dataverwerking, AI-pipelines, event-driven services", tk2c: "Schemaontwerp, migraties zonder downtime",
  tk3: "DevOps, AI en beveiliging", tk3h: "Pipelines die rood blokkeren, agents met vangrails", tk3a: "Tests, statische analyse, securitygates, rollback-image bewaard", tk3b: "Skills, evaluatiesets, kostenplafonds, een menselijke stap", tk3c: "Securitytests", tk3cs: "Netwerkinventarisatie, webapp-tests, hardening; alleen met toestemming",
  st_l: "Gewerkt met", st_h: "Merken waarvoor ik heb gebouwd, tools waarmee ik bouw", st_p: "In loondienst bij bureaus en productbedrijven vóór 2024, en sindsdien als freelancer.",
  ct_h: "Klaar om de ruis weg te snijden?", ct_p: "Ik ben beschikbaar voor architectuurconsultaties en moderniseringsprojecten. Wil je iemand die eigenaarschap neemt over de gezondheid van je systeem in plaats van tickets te verschuiven, laten we praten.", ct_b1: "Gebruik het contactformulier", ct_b2: "Mail stplotnikov@gmail.com",
});
</script>
@endpush
