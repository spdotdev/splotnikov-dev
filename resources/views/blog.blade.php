@extends('splotnikov::layout')

@section('title', 'Blog')
@section('description', 'The personal blog of Stanislav Plotnikov: technology, what I am busy with, why one thing beats another, from my own experience with PHP, Laravel, AI agents, security and Linux.')
@section('canonical', 'https://splotnikov.dev/blog')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="bl_lbl">Blog</span>
      <h1 data-i="bl_h1" data-dec>Notes from my own desk</h1>
      <p class="lead" data-i="bl_lead">Technology and what I am busy with: why one thing beats another, what a year of running two frameworks in one app taught me, what I build with AI and where I keep a human in the loop. My own experience, no client names.</p>
    </div>
  </header>

  @php($A = \Spdotdev\SplotnikovDev\Archive::class)
  @php($all = \Spdotdev\SplotnikovDev\Posts::all())
  @php($period = preg_match('/^\d{4}(-\d{2}){0,2}$/', (string) request('p')) ? (string) request('p') : '')
  @php([$posts, $page, $pages] = $A::page($A::filter($all, $period), request('page')))
  <div class="wrap arch-grid">
    @include('splotnikov::partials.archive', ['base' => route('splotnikov.blog'), 'tree' => $A::tree($all), 'period' => $period, 'page' => $page, 'pages' => $pages, 'prefix' => 'bl'])
    <div class="posts">
      @if($period !== '')<p class="arch-cur"><span data-i="bl_showing">Showing</span> <b>{{ $A::label($period) }}</b> · <a href="{{ route('splotnikov.blog') }}" data-i="bl_all2">all posts</a></p>@endif
      @if(! count($posts))<p class="arch-empty" data-i="bl_none">Nothing on this page.</p>@endif
      @foreach($posts as $post)
      <a class="post-card rv" href="{{ route('splotnikov.post', ['slug' => $post['slug']]) }}">
        <div class="meta"><time datetime="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time><span>{{ implode(' · ', $post['tags']) }}</span></div>
        <h3 data-i="bl_t_{{ str_replace('-', '_', $post['slug']) }}">{{ $post['title'] }}</h3>
        <p data-i="bl_s_{{ str_replace('-', '_', $post['slug']) }}">{{ $post['summary'] }}</p>
        <span class="more" data-i="bl_more">Read →</span>
      </a>
      @endforeach
      @include('splotnikov::partials.pager', ['base' => route('splotnikov.blog'), 'period' => $period, 'page' => $page, 'pages' => $pages, 'prefix' => 'bl'])
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('splotnikov::partials.logo', ['id' => 'cta', 'w' => 40, 'h' => 40])
      <h2 data-i="bl_cta_h">Want to talk about one of these?</h2>
      <p data-i="bl_cta_p">Disagree, have a better way, or want the same thing in your own system: write me. I answer every message myself.</p>
      <div class="row"><a class="btn" href="{{ route('splotnikov.contact.page') }}" data-i="bl_cta_b1">Contact me</a><a class="btn o" href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'bl_lbl' => 'Blog',
    'bl_h1' => 'Notities van mijn eigen bureau',
    'bl_lead' => 'Technologie en waar ik mee bezig ben: waarom het ene beter is dan het andere, wat een jaar met twee frameworks in één app me leerde, wat ik met AI bouw en waar ik een mens in de lus houd. Mijn eigen ervaring, geen klantnamen.',
    'bl_more' => 'Lees →',
    'bl_arch' => 'Archief', 'bl_all' => 'Alle berichten', 'bl_showing' => 'Je ziet', 'bl_all2' => 'alle berichten', 'bl_none' => 'Niets op deze pagina.', 'bl_prev' => '← Nieuwer', 'bl_page' => 'Pagina', 'bl_next' => 'Ouder →',
    'bl_cta_h' => 'Wil je het over een van deze hebben?',
    'bl_cta_p' => 'Oneens, een betere manier, of hetzelfde willen in je eigen systeem: schrijf me. Ik beantwoord elk bericht zelf.',
    'bl_cta_b1' => 'Neem contact op',
];
foreach (\Spdotdev\SplotnikovDev\Posts::all() as $post) {
    $k = str_replace('-', '_', $post['slug']);
    $nl['bl_t_'.$k] = e($post['nl']['title']);
    $nl['bl_s_'.$k] = e($post['nl']['summary']);
}
foreach (\Spdotdev\SplotnikovDev\Archive::tree(\Spdotdev\SplotnikovDev\Posts::all()) as $y => $months) {
    foreach (array_keys($months) as $m) {
        $nl['bl_m_'.$y.'_'.$m] = \Carbon\Carbon::create((int) $y, (int) $m, 1)->locale('nl')->translatedFormat('F');
    }
}
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
