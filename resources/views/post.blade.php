@extends('splotnikov::layout')

@section('title', $post['title'])
@section('description', $post['summary'])
@section('canonical', 'https://splotnikov.dev/blog/'.$post['slug'])
@section('ogtype', 'article')

@section('head')
  <script type="application/ld+json">{!! json_encode(['@'.'context' => 'https://schema.org', '@type' => 'BlogPosting', 'headline' => \Illuminate\Support\Str::limit($post['title'], 108, ''), 'description' => $post['summary'], 'datePublished' => $post['date'], 'mainEntityOfPage' => 'https://splotnikov.dev/blog/'.$post['slug'], 'image' => secure_asset('vendor/splotnikov/og-image.png'), 'author' => ['@type' => 'Person', 'name' => 'Stanislav Plotnikov', 'url' => 'https://splotnikov.dev'], 'publisher' => ['@type' => 'Person', 'name' => 'Stanislav Plotnikov']], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endsection

@section('content')
<article class="wrap page">
  <span class="lbl dot"><time datetime="{{ $post['date'] }}" data-i="blp_date">{{ \Carbon\Carbon::parse($post['date'])->format('j M Y') }}</time> · {{ implode(' · ', $post['tags']) }}</span>
  <h1 data-i="blp_h1">{{ $post['title'] }}</h1>
  <p class="lead" data-i="blp_lead">{{ $post['summary'] }}</p>
  <span data-i="blp_note" style="display:block"></span>

  @php($list = false)
  @foreach($post['body'] as $line)
    @if(str_starts_with($line, '- '))
      @if(!$list)<ul>@php($list = true)@endif
      <li>{{ substr($line, 2) }}</li>
    @else
      @if($list)</ul>@php($list = false)@endif
      @if(str_starts_with($line, '## '))
        <h2>{{ substr($line, 3) }}</h2>
      @else
        <p>{{ $line }}</p>
      @endif
    @endif
  @endforeach
  @if($list)</ul>@endif

  <aside class="callout">
    <p data-i="blp_q">Written by Stanislav Plotnikov, senior developer and solution architect in Eindhoven. Disagree or want to talk about your own system? <a href="/contact">Write me</a>, or find me on <a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a>.</p>
  </aside>

  <p class="updated" style="margin-top:32px"><span data-i="blp_more">More notes:</span> @foreach(\Spdotdev\SplotnikovDev\Posts::all() as $o)@if($o['slug'] !== $post['slug'])<a href="{{ route('splotnikov.post', ['slug' => $o['slug']]) }}">{{ $o['title'] }}</a> · @endif @endforeach <a href="{{ route('splotnikov.blog') }}" data-i="blp_all">all posts</a></p>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'blp_note' => 'Dit artikel is in het Engels.',
    'blp_h1' => e($post['nl']['title']),
    'blp_lead' => e($post['nl']['summary']),
    'blp_date' => \Carbon\Carbon::parse($post['date'])->locale('nl')->translatedFormat('j M Y'),
    'blp_q' => 'Geschreven door Stanislav Plotnikov, senior developer en solution architect in Eindhoven. Oneens, of wil je het over je eigen systeem hebben? <a href="/contact">Schrijf me</a>, of vind me op <a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a>.',
    'blp_more' => 'Meer notities:',
    'blp_all' => 'alle berichten',
];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
