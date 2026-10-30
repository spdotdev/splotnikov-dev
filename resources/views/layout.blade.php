<!doctype html>
<html lang="@yield('lang', 'en')">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  @php
    // Search results cut titles near 60 characters and descriptions near 160: keep both inside, at a word boundary.
    $rawTitle = trim(preg_replace('/\s+/', ' ', $__env->yieldContent('title')));
    $seoTitle = mb_strlen($rawTitle.' | Stanislav Plotnikov') <= 66 ? $rawTitle.' | Stanislav Plotnikov' : $rawTitle;
    if (mb_strlen($seoTitle) > 70) { $seoTitle = rtrim(preg_replace('/\s+\S*$/u', '', mb_substr($seoTitle, 0, 68)), " ,.:;-").'…'; }
    $rawDesc = trim(preg_replace('/\s+/', ' ', html_entity_decode($__env->yieldContent('description'))));
    $seoDesc = $rawDesc;
    if (mb_strlen($rawDesc) > 160) {
        $cut = mb_substr($rawDesc, 0, 160);
        $seoDesc = preg_match('/^(.{80,}(?<!excl)(?<!e\.g)[.!?])\s/us', $cut, $m) ? $m[1] : rtrim(preg_replace('/\s+\S*$/u', '', mb_substr($rawDesc, 0, 157)), " ,.:;-").'…';
    }
    $canon = trim($__env->yieldContent('canonical'));
    $enPath = '/'.trim((string) parse_url($canon, PHP_URL_PATH), '/');
    $isNl = request()->is('nl') || request()->is('nl/*');
    $paired = \Spdotdev\SplotnikovDev\Dutch::paired($enPath);
  @endphp
  <title>{{ $seoTitle }}</title>
  <meta name="description" content="{{ $seoDesc }}" />
  <meta name="author" content="Stanislav Plotnikov" />
  <meta name="robots" content="@yield('robots', 'index, follow')" />
  <link rel="canonical" href="@yield('canonical')" />
  @if($paired)
  <link rel="alternate" hreflang="en" href="{{ \Spdotdev\SplotnikovDev\Dutch::enUrl($enPath) }}" />
  <link rel="alternate" hreflang="nl" href="{{ \Spdotdev\SplotnikovDev\Dutch::nlUrl($enPath) }}" />
  <link rel="alternate" hreflang="x-default" href="{{ \Spdotdev\SplotnikovDev\Dutch::enUrl($enPath) }}" />
  @endif
  <meta name="theme-color" content="#f5f5f5" />

  <link rel="icon" type="image/svg+xml" href="{{ secure_asset('vendor/splotnikov/icon.svg') }}" />
  <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('vendor/splotnikov/favicon-32x32.png') }}" />
  <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('vendor/splotnikov/favicon-16x16.png') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('vendor/splotnikov/apple-touch-icon.png') }}" />
  <link rel="manifest" href="{{ route('splotnikov.webmanifest') }}" />

  <meta property="og:type" content="@yield('ogtype', 'website')" />
  <meta property="og:url" content="{{ $canon }}" />
  <meta property="og:title" content="{{ $seoTitle }}" />
  <meta property="og:description" content="{{ $seoDesc }}" />
  <meta property="og:image" content="{{ secure_asset('vendor/splotnikov/og-image.png') }}" />
  <meta property="og:image:alt" content="Stanislav Plotnikov, senior developer and solution architect in Eindhoven" />
  <meta property="og:site_name" content="splotnikov.dev" />
  <meta property="og:locale" content="en_GB" />
  <meta property="og:locale:alternate" content="nl_NL" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="{{ $seoTitle }}" />
  <meta name="twitter:description" content="{{ $seoDesc }}" />
  <meta name="twitter:image" content="{{ secure_asset('vendor/splotnikov/og-image.png') }}" />
  @php
    // BreadcrumbList for every inner page: Home, the section, the page.
    $path = trim(parse_url($canon, PHP_URL_PATH) ?: '', '/');
    if ($path !== '') {
        $segs = explode('/', $path); $crumbs = [['Home', 'https://splotnikov.dev/']]; $acc = '';
        $names = ['blog' => 'Blog', 'cv' => 'CV', 'toolkit' => 'Toolkit', 'projects' => 'Projects', 'contact' => 'Contact'];
        foreach ($segs as $i => $seg) {
            $acc .= '/'.$seg; $last = $i === count($segs) - 1;
            $crumbs[] = [$last ? $rawTitle : ($names[$seg] ?? \Illuminate\Support\Str::headline($seg)), 'https://splotnikov.dev'.$acc];
        }
        $ld = ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => array_map(fn ($c, $k) => ['@type' => 'ListItem', 'position' => $k + 1, 'name' => $c[0], 'item' => $c[1]], $crumbs, array_keys($crumbs))];
    }
  @endphp
  @isset($ld)<script type="application/ld+json">{!! json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>@endisset

  <link rel="preload" href="{{ secure_asset('vendor/splotnikov/fonts/geist-latin.woff2') }}" as="font" type="font/woff2" crossorigin />
  <link rel="preload" href="{{ secure_asset('vendor/splotnikov/fonts/geist-mono-latin.woff2') }}" as="font" type="font/woff2" crossorigin />
  <link rel="stylesheet" href="{{ secure_asset('vendor/splotnikov/site.css') }}" />
  <script>try{var t=localStorage.getItem('theme');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-theme',t)}catch(e){}</script>
  @yield('head')
</head>

<body>
  @php($home = request()->routeIs('splotnikov.home') || request()->is('nl'))
  @php($cur = fn (string $r) => (request()->routeIs($r) || request()->is('nl/'.\Illuminate\Support\Str::after($r, 'splotnikov.'))) ? ' class="cur-page"' : '')
  <a class="skip no-print" href="#top" data-i="skip">Skip to content</a>
  <nav class="top no-print" aria-label="Main navigation"><div class="wrap">
    <a class="brand" href="{{ route('splotnikov.home') }}" aria-label="splotnikov.dev home">@include('splotnikov::partials.logo', ['id' => 'nav'])<span data-scr="splotnikov">splotnikov</span></a>
    <div class="links" id="links">
      <a href="{{ route('splotnikov.home') }}" data-i="n_home"{!! $cur('splotnikov.home') !!}>Home</a>
      <a href="{{ $home ? '' : route('splotnikov.home') }}#manifesto" data-i="n_manifesto">Manifesto</a>
      <a href="{{ route('splotnikov.projects') }}" data-i="n_projects"{!! $cur('splotnikov.projects') !!}>Projects</a>
      <a href="{{ route('splotnikov.toolkit') }}" data-i="n_toolkit"{!! $cur('splotnikov.toolkit') !!}>Toolkit</a>
      <a href="{{ route('splotnikov.blog') }}" data-i="n_blog"{!! $cur('splotnikov.blog') !!}>Blog</a>
      <a href="{{ route('splotnikov.cv') }}" data-i="n_cv" title="Curriculum vitae"{!! $cur('splotnikov.cv') !!}>CV</a>
      <a href="{{ route('splotnikov.contact.page') }}" data-i="n_contact"{!! $cur('splotnikov.contact.page') !!}>Contact</a>
    </div>
    <div class="right">
      @if($paired)
      <div class="seg" role="group" aria-label="Language"><a id="en" href="{{ \Spdotdev\SplotnikovDev\Dutch::enUrl($enPath) }}" hreflang="en" lang="en" @if(! $isNl) aria-current="true" @endif>EN</a><a id="nl" href="{{ \Spdotdev\SplotnikovDev\Dutch::nlUrl($enPath) }}" hreflang="nl" lang="nl" @if($isNl) aria-current="true" @endif>NL</a></div>
      @else
      <div class="seg" role="group" aria-label="Language"><button id="en" aria-pressed="true" type="button">EN</button><button id="nl" aria-pressed="false" type="button">NL</button></div>
      @endif
      <div class="seg th" role="group" aria-label="Theme"><button type="button" data-th="system" aria-pressed="true" title="System"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg></button><button type="button" data-th="light" aria-pressed="false" title="Light"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg></button><button type="button" data-th="dark" aria-pressed="false" title="Dark"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg></button></div>
      <a class="btn cta" href="{{ route('splotnikov.contact.page') }}" data-i="n_hire">Contact me</a>
      <button class="ic" id="burger" aria-label="Menu" type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
    </div>
  </div></nav>

  <main id="top">
    @yield('content')
  </main>

  <div class="wrap no-print"><footer>
    <div class="col"><div class="brand">@include('splotnikov::partials.logo', ['id' => 'foot'])<span>splotnikov</span></div><p data-i="ft_p">Senior developer and solution architect in Eindhoven. Legacy modernization, automation with AI agents, and security, by one person who takes ownership of the system.</p></div>
    <div class="col"><small data-i="ft_nav">Navigate</small><a href="{{ $home ? '' : route('splotnikov.home') }}#manifesto" data-i="n_manifesto">Manifesto</a><a href="{{ route('splotnikov.projects') }}" data-i="n_projects">Projects</a><a href="{{ route('splotnikov.toolkit') }}" data-i="n_toolkit">Toolkit</a><a href="{{ route('splotnikov.blog') }}" data-i="n_blog">Blog</a><a href="{{ route('splotnikov.cv') }}" data-i="ft_cv" title="Curriculum vitae">CV, print or PDF</a></div>
    <div class="col"><small data-i="ft_connect">Connect</small><a href="{{ route('splotnikov.contact.page') }}" data-i="ft_form">Contact form</a><a href="{{ route('splotnikov.privacy') }}" data-i="ft_privacy">Privacy</a><a href="mailto:stplotnikov@gmail.com">stplotnikov@gmail.com</a><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a></div>
    <div class="col"><small data-i="ft_work">Work</small><a href="https://scuttle.dev" rel="noopener" target="_blank">Scuttle Development</a><a href="https://scuttle.dev/pricing" rel="noopener" target="_blank" data-i="ft_pricing">Services and pricing</a><a href="https://scuttle.dev/case-studies" rel="noopener" target="_blank" data-i="ft_cases">Case studies</a></div>
    <div class="bottom"><span>© {{ date('Y') }} STANISLAV PLOTNIKOV</span><span>EINDHOVEN, NL</span></div>
  </footer></div>

  <script>
    window.SCUTTLE_NL = { t: { skip: "Naar de inhoud", n_home: "Home", n_manifesto: "Manifest", n_projects: "Projecten", n_toolkit: "Tools", n_blog: "Blog", n_cv: "CV", n_contact: "Contact", n_hire: "Neem contact op", ft_p: "Senior developer en solution architect in Eindhoven. Modernisering van legacy software, automatisering met AI-agents en beveiliging, door één persoon die eigenaarschap neemt over het systeem.", ft_nav: "Navigatie", ft_cv: "CV, printen of PDF", ft_connect: "Contact", ft_form: "Contactformulier", ft_privacy: "Privacy", ft_work: "Werk", ft_pricing: "Diensten en prijzen", ft_cases: "Casestudy's" }, ph: {} };
  </script>
  @stack('nl')
  @yield('scripts')
  @if(config('splotnikov-dev.analytics_key'))
  {{-- sd-analytics: no cookie, no browser storage, same host (/_sda/ is answered by the sd-analytics package). --}}
  <script defer data-site="{{ config('splotnikov-dev.analytics_key') }}" src="/_sda/s.js"></script>
  <noscript><img src="/_sda/px/{{ config('splotnikov-dev.analytics_key') }}.gif" alt="" width="1" height="1" style="position:absolute;left:-9999px"></noscript>
  @endif
  <script src="{{ secure_asset('vendor/splotnikov/site.js') }}" defer></script>
</body>

</html>
