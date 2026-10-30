@extends('splotnikov::layout')

@section('title', 'Projects')
@section('description', 'Open source projects by Stanislav Plotnikov: a WhatsApp MCP server, a Claude Code skill that writes field manuals, a Telegram relay for Claude Code, an inventory product, game automation and this site. Plus two anonymized pieces of client work.')
@section('canonical', 'https://splotnikov.dev/projects')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="pj_lbl">Projects</span>
      <h1 data-i="pj_h1" data-dec>Open source, and what I build for myself</h1>
      <p class="lead" data-i="pj_lead">The personal side: tools I needed and published, products I run at home, a game turned into an engineering project. Everything here is on GitHub under my own name. Client work stays out, except the two anonymized cases at the bottom.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="case-grid">
      @foreach(\Spdotdev\SplotnikovDev\Projects::all() as $pr)
      <a class="c rv cg" href="{{ $pr['url'] }}" rel="noopener" target="_blank">
        <span class="lbl" data-i="pj_{{ $loop->index }}_k">{{ $pr['kind'] }}</span>
        <h2>{{ $pr['name'] }}</h2>
        <p data-i="pj_{{ $loop->index }}_s">{{ $pr['short'] }}</p>
        <div class="tags">@foreach($pr['stack'] as $t)<span>{{ $t }}</span>@endforeach</div>
        <span class="more" data-i="pj_more">On GitHub →</span>
      </a>
      @endforeach
    </div>
  </div>

  <section class="s tight" id="cases" style="margin-top:72px"><div class="wrap">
    <div class="gridh rv"><span class="lbl dot" data-i="pj_cs_l">Client work, anonymized</span><h2 data-i="pj_cs_h" style="margin-top:16px">Two pieces of work, told the way they went</h2><p data-i="pj_cs_p">The client names stay out; the numbers are real. The full case studies live on <a href="https://scuttle.dev/case-studies" rel="noopener" target="_blank">scuttle.dev</a>, my company site.</p></div>
    <div class="case-grid">
      @foreach(\Spdotdev\SplotnikovDev\Cases::all() as $c)
      <div class="c cg rv">
        <span class="lbl dot" data-i="pj_c{{ $loop->index }}_l">{{ $c['label'] }}</span>
        <h3 data-i="pj_c{{ $loop->index }}_h">{{ $c['title'] }}</h3>
        <p data-i="pj_c{{ $loop->index }}_1"><b>The mess.</b> {{ $c['mess'] }}</p>
        <p data-i="pj_c{{ $loop->index }}_2"><b>The solution.</b> {{ $c['solution'] }}</p>
        <p data-i="pj_c{{ $loop->index }}_3"><b>The impact.</b> {{ $c['impact'] }}</p>
        <div class="facts3">@foreach($c['facts'] as $f)<div><b data-i="pj_c{{ $loop->parent->index }}_f{{ $loop->index }}b">{{ $f[0] }}</b><span data-i="pj_c{{ $loop->parent->index }}_f{{ $loop->index }}">{{ $f[1] }}</span></div>@endforeach</div>
        <div class="tags">@foreach($c['tags'] as $t)<span>{{ $t }}</span>@endforeach</div>
      </div>
      @endforeach
    </div>
  </div></section>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('splotnikov::partials.logo', ['id' => 'cta', 'w' => 40, 'h' => 40])
      <h2 data-i="pj_cta_h">Want to build on one of these?</h2>
      <p data-i="pj_cta_p">Issues and pull requests are welcome on every repository. For work on your own system, my company site has the services and prices.</p>
      <div class="row"><a class="btn" href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a><a class="btn o" href="{{ route('splotnikov.contact.page') }}" data-i="pj_cta_b2">Contact me</a><a class="btn o" href="https://scuttle.dev" rel="noopener" target="_blank">scuttle.dev</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'pj_lbl' => 'Projecten',
    'pj_h1' => 'Open source, en wat ik voor mezelf bouw',
    'pj_lead' => 'De persoonlijke kant: tools die ik nodig had en publiceerde, producten die ik thuis draai, een spel omgebouwd tot engineeringproject. Alles hier staat op GitHub onder mijn eigen naam. Klantwerk blijft erbuiten, op de twee geanonimiseerde cases onderaan na.',
    'pj_more' => 'Op GitHub →',
    'pj_cs_l' => 'Klantwerk, geanonimiseerd',
    'pj_cs_h' => 'Twee stukken werk, verteld zoals het ging',
    'pj_cs_p' => 'De klantnamen blijven eruit; de cijfers zijn echt. De volledige casestudy\'s staan op <a href="https://scuttle.dev/case-studies" rel="noopener" target="_blank">scuttle.dev</a>, mijn bedrijfssite.',
    'pj_cta_h' => 'Wil je op een van deze verder bouwen?',
    'pj_cta_p' => 'Issues en pull requests zijn welkom op elke repository. Voor werk aan je eigen systeem staan de diensten en prijzen op mijn bedrijfssite.',
    'pj_cta_b2' => 'Neem contact op',
];
foreach (array_values(\Spdotdev\SplotnikovDev\Projects::all()) as $i => $pr) {
    $nl['pj_'.$i.'_k'] = $pr['nl']['kind'];
    $nl['pj_'.$i.'_s'] = $pr['nl']['short'];
}
foreach (\Spdotdev\SplotnikovDev\Cases::all() as $i => $c) {
    $nl['pj_c'.$i.'_l'] = $c['nl']['label'];
    $nl['pj_c'.$i.'_h'] = $c['nl']['title'];
    $nl['pj_c'.$i.'_1'] = '<b>De puinhoop.</b> '.e($c['nl']['mess']);
    $nl['pj_c'.$i.'_2'] = '<b>De oplossing.</b> '.e($c['nl']['solution']);
    $nl['pj_c'.$i.'_3'] = '<b>Het effect.</b> '.e($c['nl']['impact']);
    foreach ($c['nl']['facts'] as $j => $f) {
        $nl['pj_c'.$i.'_f'.$j.'b'] = $f[0];
        $nl['pj_c'.$i.'_f'.$j] = $f[1];
    }
}
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
