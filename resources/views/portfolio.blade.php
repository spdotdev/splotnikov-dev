@extends('splotnikov::layout')

@section('title', 'Senior developer and solution architect, Eindhoven')
@section('description', 'Stanislav Plotnikov: legacy modernization, automation with AI agents and security. PHP since 2008, Linux and networks since 2005. Available for freelance and contract roles in and around Eindhoven, remote everywhere else.')
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
  <h1 class="name" data-dec>Stanislav Plotnikov</h1>
  <div class="sub in2" data-i="sub">Legacy modernization · Automation with AI · Security</div>
  <p class="hero-q in2" data-i="hq">I turn legacy technical debt into infrastructure that scales and keeps running, by putting return on investment before industry trends.</p>
  <div class="cta in3"><a class="btn" href="{{ route('splotnikov.cv') }}" title="Curriculum vitae" data-i="cta1">View the CV <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a><a class="btn o" href="{{ route('splotnikov.contact.page') }}" data-i="cta2">Contact me</a><a class="btn o" href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a></div>
  <div class="cmd in3"><span>&gt; whoami&nbsp;&nbsp;<span>stas</span>&nbsp;&nbsp;<b data-i="cmd_b"><span>senior developer</span><span>PHP since 2008, Linux and networks since 2005</span><span>modernization</span><span>automation</span><span>AI</span><span>security</span><span>freelance since 2024</span></b></span></div>
</header>

<div class="wrap demo-wrap">
  <div class="shot-hint in4" aria-hidden="true"><svg viewBox="0 0 120 70" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M112 6c-30 2-62 10-80 36"/><path d="M40 30l-9 13 15 3"/></svg><span data-i="hint">A live terminal, not a picture. Click through, or let it play.</span></div>
  <div class="shot in4" id="demo" aria-label="Interactive examples: terminal sessions, code and an agent run from my daily work">
    <aside class="side" id="side">
      <div class="h"><span data-i="sd_t">Terminal</span><span>6</span></div>
      <button class="it on" data-k="artisan" type="button"><i class="led"></i><span>php artisan test</span><small data-i="now">now</small></button>
      <button class="it" data-k="npm-build" type="button"><i class="led"></i><span>npm run build</span><small>vite</small></button>
      <button class="it" data-k="ansible" type="button"><i class="led"></i><span>ansible-playbook</span><small>ssh</small></button>
      <button class="it" data-k="git" type="button"><i class="led"></i><span>git rebase</span><small>ci</small></button>
      <button class="it" data-k="nmap" type="button"><i class="led"></i><span>nmap</span><small>lab</small></button>
      <button class="it" data-k="history" type="button"><i class="led"></i><span>history</span><small>zsh</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_c">Code</span></div>
      <button class="it" data-k="strangler" type="button"><i class="led"></i><span>router.ts</span><small>ts</small></button>
      <button class="it" data-k="bridge" type="button"><i class="led"></i><span>Bridge.php</span><small>php</small></button>
      <div class="h" style="margin-top:22px"><span data-i="sd_a">Agents</span></div>
      <button class="it" data-k="claude" type="button"><i class="led"></i><span>claude -p</span><small>agent</small></button>
    </aside>
    <div class="main" id="main">
      <div class="top"><h4 id="sh-h">312 tests green before anything ships · Laravel, PHPUnit, Playwright</h4><div class="st"><i id="sti"></i><span id="stt">running</span></div></div>
      <div class="prog"><i id="pb"></i></div>
      <div class="view" id="view"><div class="log" id="log"></div><div class="find" id="find"></div></div>
    </div>
  </div>
  <p class="shot-note" data-i="shot_note">Sessions are re-enactments from my own lab and projects, with made-up hosts and names; nothing runs in your browser. Security tools run only on networks and machines I own or am authorized to test.</p>
</div>

<section class="s" id="manifesto"><div class="wrap">
  <div class="center rv"><span class="lbl dot" data-i="mf_l">01 · The manifesto</span><h2 data-i="mf_h" style="margin-top:16px">Engineering for return, not for trends</h2><p data-i="mf_p">Senior developer and solution architect in Eindhoven, in code since 2008. The most expensive line of code is the one that should never have been written. I do not follow cargo-cult architecture: while others over-engineer with microservices they cannot manage, I argue for modular monoliths and pragmatic scaling.</p></div>
  <div class="cards3">
    <div class="c rv"><span class="lbl">01</span><h3 data-i="mf1">Pragmatism over hype</h3><p data-i="mf1s">A stack is chosen on the talent pipeline and the total cost of ownership, not on GitHub stars.</p></div>
    <div class="c rv"><span class="lbl">02</span><h3 data-i="mf2">The Strangler Fig strategy</h3><p data-i="mf2s">No grand rewrites. Legacy debt is replaced piece by piece while the business keeps running, every release with a rollback.</p></div>
    <div class="c rv"><span class="lbl">03</span><h3 data-i="mf3">Systems thinking</h3><p data-i="mf3s">A ticket developer closes tasks. An architect anticipates the 24-month trade-off of every structural decision.</p></div>
  </div>
</div></section>

<section class="s tight" id="projects"><div class="wrap pj">
  <div class="gridh rv"><span class="lbl dot" data-i="pj_l">02 · Projects</span><h2 data-i="pj_h" style="margin-top:16px">Open source, and what I build for myself</h2><p data-i="pj_p">Tools I needed and published, products I run at home, a game turned into an engineering project. <span class="desk">Scroll sideways.</span></p></div>
  <div class="scroller" aria-label="Projects">
    @foreach(\Spdotdev\SplotnikovDev\Projects::all() as $pr)
    <a class="c cg rv" href="{{ $pr['url'] }}" rel="noopener" target="_blank"><span class="lbl" data-i="pj_{{ $loop->index }}_k">{{ $pr['kind'] }}</span><h3>{{ $pr['name'] }}</h3><p data-i="pj_{{ $loop->index }}_s">{{ $pr['short'] }}</p><div class="tags">@foreach($pr['stack'] as $t)<span>{{ $t }}</span>@endforeach</div></a>
    @endforeach
  </div>
  <a class="btn o pj-all rv" href="{{ route('splotnikov.projects') }}" data-i="pj_all">All projects →</a>
</div></section>

<section class="s tight" id="toolkit"><div class="wrap">
  <div class="gridh rv"><span class="lbl dot" data-i="tk_l">03 · The toolkit</span><h2 data-i="tk_h" style="margin-top:16px">Technologies and practices</h2><p data-i="tk_p">The tools in daily use, not a wish list. Three groups here, seven on the toolkit page.</p></div>
  <div class="cards3">
    @foreach(\Spdotdev\SplotnikovDev\Toolkit::home() as $g)
    @include('splotnikov::partials.toolgroup', ['g' => $g, 'n' => $loop->iteration])
    @endforeach
  </div>
  <div class="more-row"><a class="btn" href="{{ route('splotnikov.toolkit') }}" data-i="tk_all">See the whole toolkit →</a></div>
</div></section>

<section class="s tight" id="stack"><div class="wrap">
  <div class="plate belts rv">
    <div class="bh"><span class="lbl dot" data-i="st_l">Worked with</span><h2 data-i="st_h">Brands I have built for, tools I build with</h2><p data-i="st_p">In employment at agencies and product companies before 2024, and as a freelancer since.</p></div>
    <div class="belt" aria-label="Companies"><div class="track">
      @php($cos = ['Hosintra', 'East-West Support', 'Getnoticed', 'Bek', 'New Brand Activators', 'Mijnwebwinkel', '@Leisure Group', 'Van Os Beheer'])
      @for($r = 0; $r < 2; $r++)@foreach($cos as $co)<span class="tk"><i></i>{{ $co }}</span>@endforeach @endfor
    </div></div>
    <div class="belt rev" aria-label="Tools"><div class="track">
      @php($tools = ['PHP', 'Laravel', 'Phalcon', 'Symfony', 'MySQL', 'PostgreSQL', 'Redis', 'Docker', 'Ansible', 'GitHub Actions', 'DigitalOcean', 'Python', 'Kotlin', 'Claude Code', 'MCP', 'n8n', 'Playwright', 'Arch Linux', 'Kali Linux'])
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
<?php
$nl = [
    'sub' => 'Modernisering van legacy · Automatisering met AI · Beveiliging',
    'hq' => 'Ik maak van technische schuld in legacy software infrastructuur die schaalt en blijft draaien, door rendement boven trends te zetten.',
    'cta1' => 'Bekijk het CV', 'cta2' => 'Neem contact op',
    'cmd_b' => '<span>senior developer</span><span>PHP sinds 2008, Linux en netwerken sinds 2005</span><span>modernisering</span><span>automatisering</span><span>AI</span><span>beveiliging</span><span>freelance sinds 2024</span>',
    'hint' => 'Een live terminal, geen plaatje. Klik erdoorheen, of laat het lopen.', 'sd_t' => 'Terminal', 'now' => 'nu', 'sd_c' => 'Code', 'sd_a' => 'Agents',
    'shot_note' => 'Sessies zijn nagespeeld uit mijn eigen lab en projecten, met verzonnen hosts en namen; er draait niets in je browser. Securitytools draaien alleen op netwerken en machines die van mij zijn of die ik mag testen.',
    'mf_l' => '01 · Het manifest', 'mf_h' => 'Engineering voor rendement, niet voor trends',
    'mf_p' => 'Senior developer en solution architect in Eindhoven, sinds 2008 in de code. De duurste regel code is de regel die nooit geschreven had moeten worden. Ik volg geen cargo-cult-architectuur: waar anderen over-engineeren met microservices die ze niet kunnen beheren, pleit ik voor modulaire monolieten en pragmatisch schalen.',
    'mf1' => 'Pragmatisme boven hype', 'mf1s' => 'Een stack wordt gekozen op de mensen die hem kunnen draaien en de totale kosten, niet op GitHub-sterren.',
    'mf2' => 'De Strangler Fig-strategie', 'mf2s' => 'Geen grote herbouw. Legacy schuld wordt stuk voor stuk vervangen terwijl het bedrijf doordraait, elke release met een rollback.',
    'mf3' => 'Systeemdenken', 'mf3s' => 'Een ticketdeveloper sluit taken af. Een architect voorziet de afweging van elke structurele keuze over 24 maanden.',
    'pj_l' => '02 · Projecten', 'pj_h' => 'Open source, en wat ik voor mezelf bouw', 'pj_p' => 'Tools die ik nodig had en publiceerde, producten die ik thuis draai, een spel omgebouwd tot engineeringproject. <span class="desk">Scroll opzij.</span>', 'pj_all' => 'Alle projecten →',
    'tk_l' => '03 · Het gereedschap', 'tk_h' => 'Technologieën en werkwijze', 'tk_p' => 'De tools van dagelijks gebruik, geen wensenlijst. Drie groepen hier, zeven op de gereedschapspagina.', 'tk_all' => 'Bekijk het hele gereedschap →',
    'st_l' => 'Gewerkt met', 'st_h' => 'Merken waarvoor ik heb gebouwd, tools waarmee ik bouw', 'st_p' => 'In loondienst bij bureaus en productbedrijven vóór 2024, en sindsdien als freelancer.',
    'ct_h' => 'Klaar om de ruis weg te snijden?', 'ct_p' => 'Ik ben beschikbaar voor architectuurconsultaties en moderniseringsprojecten. Wil je iemand die eigenaarschap neemt over de gezondheid van je systeem in plaats van tickets te verschuiven, laten we praten.', 'ct_b1' => 'Gebruik het contactformulier', 'ct_b2' => 'Mail stplotnikov@gmail.com',
];
foreach (array_values(\Spdotdev\SplotnikovDev\Projects::all()) as $i => $pr) {
    $nl['pj_'.$i.'_k'] = $pr['nl']['kind'];
    $nl['pj_'.$i.'_s'] = $pr['nl']['short'];
}
foreach (\Spdotdev\SplotnikovDev\Toolkit::home() as $g) {
    $nl['tg_'.$g['slug'].'_l'] = $g['nl']['label'];
    $nl['tg_'.$g['slug'].'_h'] = $g['nl']['title'];
    foreach ($g['tools'] as $j => $t) {
        $nl['tg_'.$g['slug'].'_'.$j] = $t['nl'];
    }
}
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
