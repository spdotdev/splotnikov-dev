@extends('splotnikov::layout')

@section('title', 'Contact')
@section('description', 'Contact Stanislav Plotnikov in Eindhoven: the form, stplotnikov@gmail.com, LinkedIn and GitHub. I reply within one working day.')
@section('canonical', 'https://splotnikov.dev/contact')

@section('content')
<article class="prod contact-page">
  <header class="wrap ph">
    <div>
      <span class="lbl dot">Contact</span>
      <h1 data-dec>Tell me what you are running</h1>
      <p class="lead">A system that must not stop, a migration nobody dares to start, hours that go into work a machine could do. I reply within one working day with a first opinion, free. A hard question gets what I need to look at before I answer it, not a dodge.</p>
    </div>
  </header>

  <div class="wrap">
    <form class="cform light" method="post" action="{{ route('splotnikov.contact') }}" id="cform">
      @csrf
      <input type="hidden" name="t" value="{{ \Spdotdev\SplotnikovDev\Http\Controllers\ContactController::token() }}">
      <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      @if(session('contact_sent'))
        <div class="sent" role="status"><b>Sent. I reply within one working day.</b></div>
      @endif
      @if($errors->any())
        <div class="err" role="alert">{{ $errors->first() }}</div>
      @endif
      <div class="two-f">
        <label><span>Name</span><input type="text" name="name" required maxlength="120" value="{{ old('name') }}" autocomplete="name"></label>
        <label><span>Email</span><input type="email" name="email" required maxlength="160" value="{{ old('email') }}" autocomplete="email"></label>
      </div>
      <label><span>About</span><select name="about" id="cf-about">@foreach(['Not sure yet' => '', 'A freelance or contract role' => 'Freelance or contract role', 'Legacy modernization' => 'Legacy modernization', 'Automation with AI agents' => 'Automation with AI agents', 'Security' => 'Security', 'Architecture advice' => 'Architecture advice', 'Something else' => 'Something else'] as $label => $value)<option value="{{ $value }}" @selected(old('about', request('about')) === $value && $value !== '')>{{ $label }}</option>@endforeach</select></label>
      <label><span>What are you running, and what should change?</span><textarea name="message" required minlength="10" maxlength="4000" rows="7">{{ old('message') }}</textarea></label>
      @if(config('splotnikov-dev.turnstile_site_key'))
        <div class="cf-turnstile" data-sitekey="{{ config('splotnikov-dev.turnstile_site_key') }}"></div>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
      @endif
      <div class="cf-foot"><button class="btn" type="submit">Send</button><a class="btn o" href="{{ config('splotnikov-dev.booking_url') }}">Or book a call</a><small>No tracking, no newsletter. Your message goes to stplotnikov@gmail.com and nowhere else.</small></div>
    </form>
  </div>

  <div class="wrap contact two-c" id="about">
    <div class="cc rv">
      <span class="lbl">Reach me</span>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>Eindhoven, the Netherlands<br>On site in the Eindhoven region, remote everywhere else.</span></div>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/></svg><a href="mailto:stplotnikov@gmail.com">stplotnikov@gmail.com</a></div>
      <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.7a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.7a2 2 0 0 1 1.7 2z"/></svg><a href="tel:+31634500328">+31 (0) 6 345 00 328</a></div>
      <div class="line mono"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span>Business: <a href="https://scuttle.dev" rel="noopener" target="_blank">Scuttle Development</a><br>KvK 96040947 · BTW NL005184584B85</span></div>
    </div>
    <div class="cc rv">
      <span class="lbl">About</span>
      <div class="about">
        <button id="open-pic" type="button" aria-label="Show the photo larger"><img src="{{ secure_asset('vendor/splotnikov/portrait.jpg') }}" alt="Stanislav Plotnikov" width="96" height="96" loading="lazy"></button>
        <div><p>Stanislav Plotnikov, Stas. Senior developer and solution architect, PHP since the 2000s, Laravel and Phalcon in production, Python for automation and AI, Kotlin for Android. Dutch, English and Russian. One person, one point of contact, from the first call to the handover.</p>
          <div class="soc"><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a><a href="{{ route('splotnikov.cv') }}">CV</a><a href="https://scuttle.dev" rel="noopener" target="_blank">scuttle.dev</a></div></div>
      </div>
    </div>
  </div>

  <div id="pic-modal" class="modal" aria-hidden="true" role="dialog" aria-label="Photo of Stanislav Plotnikov">
    <div class="ov" data-close="pic-modal"></div>
    <div class="box pic">
      <button class="x" data-close="pic-modal" type="button" aria-label="Close">&times;</button>
      <img src="{{ secure_asset('vendor/splotnikov/portrait.jpg') }}" alt="Stanislav Plotnikov" width="480" height="480">
      <div class="cap">Stanislav Plotnikov · Eindhoven</div>
    </div>
  </div>
</article>
@endsection
