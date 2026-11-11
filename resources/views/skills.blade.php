@extends('splotnikov::layout')

@section('title', 'Skills I built')
@section('description', 'The skills I built for my own work with Claude Code: what each one does, how often it has been used, and which ones are public.')
@section('canonical', 'https://splotnikov.dev/skills')

@section('content')
<article class="prod">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Skills</span>
      <h1>Skills I built, and what each one does</h1>
      <p class="lead">A skill is a written procedure that Claude Code follows, so the same work is done the same way every time. These are the ones I built for my own work. The count is how often each one has run on my machine, from the local usage record. A count of zero means it is built and not yet used on a real job.</p>
      <div class="cta"><a class="btn" href="{{ route('splotnikov.contact.page') }}">Ask about a skill for your team</a></div>
    </div>
  </header>

  <div class="wrap">
    <div class="case-grid">
      @foreach(\Spdotdev\SplotnikovDev\Skills::all() as $sk)
      <div class="c rv cg">
        <span class="lbl">{{ $sk['uses'] }} {{ $sk['uses'] === 1 ? 'use' : 'uses' }}</span>
        <h2>{{ $sk['name'] }}</h2>
        <p>{{ $sk['what'] }}</p>
        @if($sk['public_url'])
          <a class="more" href="{{ $sk['public_url'] }}" rel="noopener" target="_blank">Public on GitHub →</a>
        @else
          <span class="more">Private repository, not published</span>
        @endif
      </div>
      @endforeach
    </div>
  </div>
</article>
@endsection
