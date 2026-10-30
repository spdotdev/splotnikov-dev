@extends('splotnikov::layout')

@section('title', 'Toolkit')
@section('description', 'The tools and practices Stanislav Plotnikov uses every day: PHP and Laravel, AI agents with guardrails, security since the BackTrack days, infrastructure as code, pipelines that block red, automation.')
@section('canonical', 'https://splotnikov.dev/toolkit')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="tkp_lbl">Toolkit</span>
      <h1 data-i="tkp_h1" data-dec>Technologies and practices</h1>
      <p class="lead" data-i="tkp_lead">Seven groups, the same as the expertise on my CV: the tools in daily use, not a wish list. Each line says what I do with it. The three on the home page are the ones people ask about first.</p>
    </div>
  </header>

  <div class="wrap">
    <div class="cards3">
      @foreach(\Spdotdev\SplotnikovDev\Toolkit::all() as $g)
      @include('splotnikov::partials.toolgroup', ['g' => $g, 'n' => $loop->iteration])
      @endforeach
    </div>
  </div>

  <section class="cta-end"><div class="wrap">
    <div class="cta-box rv">
      @include('splotnikov::partials.logo', ['id' => 'cta', 'w' => 40, 'h' => 40])
      <h2 data-i="tkp_cta_h">Missing the one you use?</h2>
      <p data-i="tkp_cta_p">A stack is chosen on the people who can run it and its total cost, not on this list. Tell me what you run and I say honestly whether I am the right person for it.</p>
      <div class="row"><a class="btn" href="{{ route('splotnikov.contact.page') }}" data-i="tkp_cta_b1">Contact me</a><a class="btn o" href="{{ route('splotnikov.cv') }}" data-i="tkp_cta_b2">View the CV</a></div>
    </div>
  </div></section>
</article>
@endsection

@push('nl')
<?php
$nl = [
    'tkp_lbl' => 'Gereedschap',
    'tkp_h1' => 'Technologieën en werkwijze',
    'tkp_lead' => 'Zeven groepen, dezelfde als de expertise op mijn CV: de tools van dagelijks gebruik, geen wensenlijst. Elke regel zegt wat ik ermee doe. De drie op de homepage zijn die waar mensen het eerst naar vragen.',
    'tkp_cta_h' => 'Mis je die ene die jij gebruikt?',
    'tkp_cta_p' => 'Een stack wordt gekozen op de mensen die hem kunnen draaien en de totale kosten, niet op deze lijst. Vertel me wat je draait en ik zeg eerlijk of ik de juiste persoon ervoor ben.',
    'tkp_cta_b1' => 'Neem contact op',
    'tkp_cta_b2' => 'Bekijk het CV',
];
foreach (\Spdotdev\SplotnikovDev\Toolkit::all() as $g) {
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
