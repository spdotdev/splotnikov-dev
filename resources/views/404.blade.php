@extends('splotnikov::layout')

@section('title', 'Page not found')
@section('description', 'This page does not exist on splotnikov.dev. The projects, the CV and the contact form are one click away.')
@section('canonical', 'https://splotnikov.dev/404')
@section('robots', 'noindex, follow')

@section('content')
<article class="wrap page">
  <span class="lbl dot" data-i="e4_l">404</span>
  <h1 data-i="e4_h">That page does not exist</h1>
  <p class="lead" data-i="e4_lead">The address is wrong or the page moved. The projects, the CV and the contact form are a click away.</p>
  <div class="cta" style="margin-top:28px">
    <a class="btn" href="{{ route('splotnikov.projects') }}" data-i="e4_b1">See the projects</a>
    <a class="btn o" href="{{ route('splotnikov.cv') }}" data-i="e4_b2">Read the CV</a>
    <a class="btn o" href="{{ route('splotnikov.contact.page') }}" data-i="e4_b3">Get in touch</a>
  </div>
</article>
@endsection

@push('nl')
<script>
Object.assign(window.SCUTTLE_NL.t, {
 "e4_h": "Die pagina bestaat niet",
 "e4_lead": "Het adres klopt niet of de pagina is verhuisd. De projecten, het CV en het contactformulier zijn een klik verderop.",
 "e4_b1": "Bekijk de projecten",
 "e4_b2": "Lees het CV",
 "e4_b3": "Neem contact op"
});
</script>
@endpush
