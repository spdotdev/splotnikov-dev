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

  <div class="wrap">
    <div class="posts">
      @foreach(\Spdotdev\SplotnikovDev\Posts::all() as $post)
      <a class="post-card rv" href="{{ route('splotnikov.post', ['slug' => $post['slug']]) }}">
        <div class="meta"><time datetime="{{ $post['date'] }}">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time><span>{{ implode(' · ', $post['tags']) }}</span></div>
        <h3 data-i="bl_{{ $loop->index }}_t">{{ $post['title'] }}</h3>
        <p data-i="bl_{{ $loop->index }}_s">{{ $post['summary'] }}</p>
        <span class="more" data-i="bl_more">Read →</span>
      </a>
      @endforeach
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
    'bl_cta_h' => 'Wil je het over een van deze hebben?',
    'bl_cta_p' => 'Oneens, een betere manier, of hetzelfde willen in je eigen systeem: schrijf me. Ik beantwoord elk bericht zelf.',
    'bl_cta_b1' => 'Neem contact op',
];
foreach (array_values(\Spdotdev\SplotnikovDev\Posts::all()) as $i => $post) {
    $nl['bl_'.$i.'_t'] = $post['nl']['title'];
    $nl['bl_'.$i.'_s'] = $post['nl']['summary'];
}
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
