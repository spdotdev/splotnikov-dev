@extends('splotnikov::layout')

@section('title', 'Contact')
@section('description', 'Contact Stanislav Plotnikov in Eindhoven: the form, stplotnikov@gmail.com, LinkedIn and GitHub. I reply within one working day.')
@section('canonical', 'https://splotnikov.dev/contact')

@section('content')
<article class="prod contact-page">
  <header class="wrap ph">
    <div>
      <span class="lbl dot" data-i="ctp_lbl">Contact</span>
      <h1 data-i="ctp_h1" data-dec>Tell me what you are running</h1>
      <p class="lead" data-i="ctp_lead">A system that must not stop, a migration nobody dares to start, hours that go into work a machine could do. I reply within one working day with a first opinion, free. A hard question gets what I need to look at before I answer it, not a dodge.</p>
    </div>
  </header>

  <div class="wrap contact-grid">
    <form class="cform light" method="post" action="{{ route('splotnikov.contact') }}" id="cform">
      @csrf
      <input type="hidden" name="t" value="{{ \Spdotdev\SplotnikovDev\Http\Controllers\ContactController::token() }}">
      <div class="hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      @if(session('contact_sent') === 'stored')
        <div class="sent" role="status"><b data-i="ctp_stored">Received and saved. I could not send it on by email just now, but I have it and I will answer you at the address you gave. If it is urgent, email stplotnikov@gmail.com.</b></div>
      @elseif(session('contact_sent'))
        <div class="sent" role="status"><b data-i="ctp_sent">Sent. I reply within one working day.</b></div>
      @endif
      @if(session('contact_sent') && config('splotnikov-dev.analytics_key'))
        <script>document.addEventListener('DOMContentLoaded',function(){window.sda&&sda('Contact form sent')})</script>
      @endif
      @if($errors->any())
        <div class="err" role="alert">{{ $errors->first() }}</div>
      @endif
      <div class="two-f">
        <label><span data-i="ctp_name">Name</span><input type="text" name="name" required maxlength="120" value="{{ old('name') }}" autocomplete="name"></label>
        <label><span data-i="ctp_email">Email</span><input type="email" name="email" required maxlength="160" value="{{ old('email') }}" autocomplete="email"></label>
      </div>
      <label><span data-i="ctp_about">About</span><select name="about" id="cf-about">@foreach(['Not sure yet' => '', 'A freelance or contract role' => 'Freelance or contract role', 'Legacy modernization' => 'Legacy modernization', 'Automation with AI agents' => 'Automation with AI agents', 'Security' => 'Security', 'Architecture advice' => 'Architecture advice', 'Something else' => 'Something else'] as $label => $value)<option value="{{ $value }}" @selected(old('about', request('about')) === $value && $value !== '')>{{ $label }}</option>@endforeach</select></label>
      <label><span data-i="ctp_msg">What are you running, and what should change?</span><textarea name="message" required minlength="10" maxlength="4000" rows="8">{{ old('message') }}</textarea></label>
      @if(config('splotnikov-dev.turnstile_site_key'))
        <div class="cf-turnstile" data-sitekey="{{ config('splotnikov-dev.turnstile_site_key') }}"></div>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
      @endif
      <div class="cf-foot"><button class="btn" type="submit" data-i="ctp_send">Send</button><a class="btn o" href="{{ config('splotnikov-dev.booking_url') }}" data-i="ctp_book">Or book a call</a><small data-i="ctp_note">No tracking cookies, no newsletter. Your message goes to stplotnikov@gmail.com and nowhere else.</small></div>
    </form>

    <div class="side-cards">
      <div class="cc rv">
        <span class="lbl" data-i="ctp_reach">Reach me</span>
        <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span data-i="ctp_where">Eindhoven, the Netherlands<br>On site in the Eindhoven region, remote everywhere else.</span></div>
        <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m4 7 8 6 8-6"/></svg><a href="mailto:stplotnikov@gmail.com">stplotnikov@gmail.com</a></div>
        <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.7a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.7a2 2 0 0 1 1.7 2z"/></svg><a href="tel:+31634500328">+31 (0) 6 345 00 328</a></div>
        <div class="line"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span data-i="ctp_biz">For work on your company's software: <a href="https://scuttle.dev" rel="noopener" target="_blank">scuttle.dev</a>, services and prices.</span></div>
      </div>
      <div class="cc rv">
        <span class="lbl" data-i="ctp_aboutl">About</span>
        <div class="about">
          <button id="open-pic" type="button" aria-label="Show the photo larger"><img src="{{ secure_asset('vendor/splotnikov/portrait.jpg') }}" alt="Stanislav Plotnikov" width="96" height="96" loading="lazy"></button>
          <div><p data-i="ctp_bio">Stanislav Plotnikov, Stas. Senior developer and solution architect, PHP since 2008, Laravel and Phalcon in production, Python for automation and AI, Kotlin for Android. Dutch, English and Russian. One person, one point of contact, from the first call to the handover.</p>
            <div class="soc"><a href="https://www.linkedin.com/in/stanislavplotnikov/" rel="noopener" target="_blank">LinkedIn</a><a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a></div></div>
        </div>
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

@push('nl')
<?php
$nl = [
    'ctp_lbl' => 'Contact',
    'ctp_h1' => 'Vertel me wat je draait',
    'ctp_lead' => 'Een systeem dat niet mag stoppen, een migratie die niemand durft te starten, uren die in werk gaan dat een machine kan doen. Ik reageer binnen één werkdag met een eerste mening, gratis. Een moeilijke vraag krijgt wat ik moet bekijken voordat ik antwoord, geen ontwijking.',
    'ctp_name' => 'Naam', 'ctp_email' => 'E-mail', 'ctp_about' => 'Waarover', 'ctp_msg' => 'Wat draai je, en wat moet er veranderen?',
    'ctp_send' => 'Verstuur', 'ctp_book' => 'Of plan een gesprek', 'ctp_note' => 'Geen trackingcookies, geen nieuwsbrief. Je bericht gaat naar stplotnikov@gmail.com en nergens anders heen.',
    'ctp_reach' => 'Bereik me', 'ctp_where' => 'Eindhoven, Nederland<br>Op locatie in de regio Eindhoven, op afstand overal elders.',
    'ctp_biz' => 'Voor werk aan de software van je bedrijf: <a href="https://scuttle.dev" rel="noopener" target="_blank">scuttle.dev</a>, diensten en prijzen.',
    'ctp_aboutl' => 'Over mij',
    'ctp_sent' => 'Verstuurd. Ik reageer binnen één werkdag.',
    'ctp_stored' => 'Ontvangen en bewaard. Ik kon het nu niet per e-mail doorsturen, maar ik heb het en ik antwoord op het adres dat je opgaf. Is het dringend, mail dan stplotnikov@gmail.com.',
    'ctp_bio' => 'Stanislav Plotnikov, Stas. Senior developer en solution architect, PHP sinds 2008, Laravel en Phalcon in productie, Python voor automatisering en AI, Kotlin voor Android. Nederlands, Engels en Russisch. Eén persoon, één aanspreekpunt, van het eerste gesprek tot de overdracht.',
];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
