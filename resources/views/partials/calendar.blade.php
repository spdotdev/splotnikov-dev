{{-- A month in the archive card: Monday first, the days with posts are links, every row's week number filters that week.
     Variables: $base, $tree, $period, $prefix. The month shown is $month (Y-m), chosen by ?m= or from the filter. --}}
@php($month = preg_match('/^\d{4}-\d{2}$/', (string) request('m')) ? (string) request('m') : $A::calendarMonth($tree, $period))
@php($cur = \Carbon\Carbon::createFromFormat('!Y-m', $month)->startOfMonth())
@php($q = $period !== '' ? '&p='.$period : '')
<div class="cal">
  <div class="cal-h">
    @php($prev = $cur->copy()->subMonth()->format('Y-m'))
    @php($next = $cur->copy()->addMonth()->format('Y-m'))
    {{-- the archive ends at its first and last month with a post: no link leads outside it --}}
    @if($A::hasMonth($tree, $prev))<a href="{{ $base }}?m={{ $prev }}{{ $q }}" aria-label="Previous month">‹</a>@else<a class="off" aria-hidden="true">‹</a>@endif
    <b><span data-i="{{ $prefix }}_cm_{{ $cur->format('m') }}">{{ $cur->format('F') }}</span> {{ $cur->format('Y') }}</b>
    @if($A::hasMonth($tree, $next))<a href="{{ $base }}?m={{ $next }}{{ $q }}" aria-label="Next month">›</a>@else<a class="off" aria-hidden="true">›</a>@endif
  </div>
  <div class="cal-g">
    <i data-i="{{ $prefix }}_wk">wk</i>@foreach(['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $i => $wd)<i data-i="{{ $prefix }}_wd{{ $i }}">{{ $wd }}</i>@endforeach
    @foreach($A::calendar($tree, $month) as $row)
    @if($row['posts'])<a class="wk{{ $period === $row['week'] ? ' cur' : '' }}" href="{{ $base }}?p={{ $row['week'] }}" title="{{ $row['week'] }}" aria-label="Posts of week {{ $row['week'] }}">{{ (int) substr($row['week'], -2) }}</a>
    @else<span class="wk off">{{ (int) substr($row['week'], -2) }}</span>@endif
    @foreach($row['days'] as $d)
      @if($d['posts'])<a class="d on{{ $period === $d['date'] ? ' cur' : '' }}{{ $d['inMonth'] ? '' : ' out' }}" href="{{ $base }}?p={{ $d['date'] }}" title="{{ $d['posts'] }}" aria-label="Posts of {{ $d['date'] }}">{{ $d['day'] }}</a>
      @else<span class="d{{ $d['inMonth'] ? '' : ' out' }}">{{ $d['day'] }}</span>@endif
    @endforeach
    @endforeach
  </div>
</div>
