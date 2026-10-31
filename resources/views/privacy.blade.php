@extends('splotnikov::layout')

@section('title', 'Privacy statement')
@section('description', 'What data splotnikov.dev uses: no cookies when you read, an own visit counter that stores no IP address, and the contact form.')
@section('canonical', 'https://splotnikov.dev/privacy')

@section('content')
<article class="wrap page">
    <h1 data-i="pv_h1">Privacy statement</h1>
    <p class="updated" data-i="pv_upd">Last updated: 31 October 2026</p>

    <h2 data-i="pv_h_who">Who I am</h2>
    <p data-i="pv_who">This is the personal site of Stanislav Plotnikov, freelance developer and software architect in Eindhoven, the Netherlands. For anything about your data, use the <a href="{{ route('splotnikov.contact.page') }}">contact form</a>.</p>

    <h2 data-i="pv_h_contact">The contact form</h2>
    <p data-i="pv_contact">If you write through the contact form, I store your name, email address, subject and message on my own server, so a message is not lost when email fails, together with a keyed hash of your IP address (not the address itself) to keep abuse in check. I use it only to answer you. The form page sets one technically necessary session cookie, which protects the form against forged requests and is gone after two hours.</p>

    <h2 data-i="pv_h_stats">Cookies and statistics</h2>
    @if(config('splotnikov-dev.analytics_key'))
    <p data-i="pv_stats">Reading the pages sets no cookie and stores nothing in your browser, and no third party analytics is loaded. I count visits with a small tool that runs on my own server, so no other company sees them. For each page you open it records: the page address, the website you came from (the site name only, not the full address), the campaign tags in the link you used (utm_source, utm_medium, utm_campaign), the kind of device, browser and system, clicks on links that leave this site, the contact form being sent, and the time. It does not store your IP address or your full browser identification. To tell two visits of the same day apart, the server makes a short code from your IP address and browser details with a random key; that key is deleted after two days, so the code cannot be traced back to you and cannot be linked to a visit on another day. If your browser sends Do Not Track or Global Privacy Control, nothing is recorded. Single visits are kept for 30 days; the daily totals that remain contain no personal data. The basis is my legitimate interest in knowing which pages are useful (article 6(1)(f) GDPR). The requests for this counting are left out of the server access log.</p>
    @else
    <p data-i="pv_stats">Reading the pages sets no cookie and stores nothing in your browser, and no analytics of any kind is loaded. Should I ever add visitor statistics, this statement will say what is measured and why before it is switched on.</p>
    @endif

    <h2 data-i="pv_h_share">Who sees data</h2>
    <p data-i="pv_share">I sell no data. The site runs on a server of DigitalOcean in Amsterdam. Email goes through Google.</p>

    <h2 data-i="pv_h_rights">Your rights</h2>
    <p data-i="pv_rights">You can view, correct or remove your data, and object to its use. Use the <a href="{{ route('splotnikov.contact.page') }}">contact form</a>; you get an answer within a month. If you disagree with how I handle your data, you can complain to the Dutch Data Protection Authority, the <a href="https://autoriteitpersoonsgegevens.nl" rel="noopener">Autoriteit Persoonsgegevens</a>.</p>
</article>
@endsection

@push('nl')
<?php
$on = (bool) config('splotnikov-dev.analytics_key');
$nl = [
    'pv_h1' => 'Privacyverklaring',
    'pv_upd' => 'Laatst bijgewerkt: 31 oktober 2026',
    'pv_h_who' => 'Wie ik ben',
    'pv_who' => 'Dit is de persoonlijke site van Stanislav Plotnikov, freelance developer en software architect in Eindhoven. Voor alles over je gegevens gebruik je het <a href="'.route('splotnikov.contact.page').'">contactformulier</a>.',
    'pv_h_contact' => 'Het contactformulier',
    'pv_contact' => 'Als je via het contactformulier schrijft, sla ik je naam, e-mailadres, onderwerp en bericht op mijn eigen server op, zodat een bericht niet verloren gaat als e-mail faalt, samen met een gesleutelde hash van je IP-adres (niet het adres zelf) om misbruik tegen te gaan. Ik gebruik het alleen om je te antwoorden. De formulierpagina zet één technisch noodzakelijke sessiecookie, die het formulier beschermt tegen vervalste verzoeken en na twee uur verdwijnt.',
    'pv_h_stats' => 'Cookies en statistieken',
    'pv_stats' => $on
        ? 'Het lezen van de pagina\'s zet geen cookie en slaat niets op in je browser, en er wordt geen analytics van derden geladen. Ik tel bezoeken met een klein programma dat op mijn eigen server draait, dus geen ander bedrijf ziet ze. Per pagina die je opent legt het vast: het adres van de pagina, de website waar je vandaan kwam (alleen de sitenaam, niet het volledige adres), de campagnelabels in de link die je gebruikte (utm_source, utm_medium, utm_campaign), het soort apparaat, browser en systeem, klikken op links die deze site verlaten, het versturen van het contactformulier en het tijdstip. Je IP-adres en je volledige browseridentificatie worden niet bewaard. Om twee bezoeken op dezelfde dag uit elkaar te houden maakt de server een korte code van je IP-adres en browsergegevens met een willekeurige sleutel; die sleutel wordt na twee dagen verwijderd, waardoor de code niet naar jou terug te leiden is en niet gekoppeld kan worden aan een bezoek op een andere dag. Stuurt je browser Do Not Track of Global Privacy Control mee, dan wordt er niets vastgelegd. Losse bezoeken bewaar ik 30 dagen; de dagtotalen die blijven bevatten geen persoonsgegevens. De grondslag is mijn gerechtvaardigd belang om te weten welke pagina\'s nuttig zijn (artikel 6, lid 1, onder f AVG). De verzoeken voor deze telling worden niet in het toegangslogboek van de server opgenomen.'
        : 'Het lezen van de pagina\'s zet geen cookie en slaat niets op in je browser, en er wordt geen analytics van welke soort ook geladen. Mocht ik ooit bezoekersstatistieken toevoegen, dan zegt deze verklaring wat er gemeten wordt en waarom, voordat het aan gaat.',
    'pv_h_share' => 'Wie gegevens ziet',
    'pv_share' => 'Ik verkoop geen gegevens. De site draait op een server van DigitalOcean in Amsterdam. E-mail loopt via Google.',
    'pv_h_rights' => 'Je rechten',
    'pv_rights' => 'Je kunt je gegevens inzien, laten corrigeren of laten verwijderen, en bezwaar maken tegen het gebruik ervan. Gebruik daarvoor het <a href="'.route('splotnikov.contact.page').'">contactformulier</a>; je krijgt binnen een maand antwoord. Ben je het niet eens met hoe ik met je gegevens omga, dan kun je klagen bij de Autoriteit Persoonsgegevens.',
];
?>
<script>
Object.assign(window.SCUTTLE_NL.t, @json($nl));
</script>
@endpush
