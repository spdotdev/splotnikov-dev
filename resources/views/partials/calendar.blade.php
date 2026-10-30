{{-- A month in the archive card: Monday first, the days with posts are links, every row's week number filters that week.
     Variables: $base, $tree, $period, $prefix. The month shown is $month (Y-m), chosen by ?m= or from the filter. --}}
@php($month = preg_match('/^\d{4}-\d{2}$/', (string) request('m')) ? (string) request('m') : $A::calendarMonth($tree, $period))
@php($cur = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth())
@php($q = $period !== '' ? '&p='.$period : '')
<div class="cal">
  <div class="cal-h">
    <a href="{{ $base }}?m={{ $cur->copy()->subMonth()->format('Y-m') }}{{ $q }}" aria-label="Previous month">‹</a>
    <b><span data-i="{{ $prefix }}_cm_{{ $cur->format('m') }}">{{ $cur->format('F') }}</span> {{ $cur->format('Y') }}</b>
    <a href="{{ $base }}?m={{ $cur->copy()->addMonth()->format('Y-m') }}{{ $q }}" aria-label="Next month">›</a>
  </div>
  <div class="cal-g">
    <i data-i="{{ $prefix }}_wk">wk</i>@foreach(['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $i => $wd)<i data-i="{{ $prefix }}_wd{{ $i }}">{{ $wd }}</i>@endforeach
    @foreach($A::calendar($tree, $month) as $row)
    <a class="wk{{ $period === $row['week'] ? ' cur' : '' }}" href="{{ $base }}?p={{ $row['week'] }}" title="{{ $row['week'] }}">{{ (int) substr($row['week'], -2) }}</a>
    @foreach($row['days'] as $d)
      @if($d['posts'])<a class="d on{{ $period === $d['date'] ? ' cur' : '' }}{{ $d['inMonth'] ? '' : ' out' }}" href="{{ $base }}?p={{ $d['date'] }}" title="{{ $d['posts'] }}">{{ $d['day'] }}</a>
      @else<span class="d{{ $d['inMonth'] ? '' : ' out' }}">{{ $d['day'] }}</span>@endif
    @endforeach
    @endforeach
  </div>
</div>
