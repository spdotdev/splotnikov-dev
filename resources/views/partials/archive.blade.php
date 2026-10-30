{{-- Variables also: $A (the Archive class), $ns (the view namespace).
     The archive beside a post list: years collapsed, months inside, days that link to the posts of that day; plus the page links.
     Variables: $base (the list url), $tree (Archive::tree), $period (the active filter or ''), $page, $pages, $prefix (data-i prefix). --}}
<div class="arch">
  <small data-i="{{ $prefix }}_arch">Archive</small>
  <a class="all{{ $period === '' ? ' cur' : '' }}" href="{{ $base }}" data-i="{{ $prefix }}_all">All posts</a>
  @foreach($tree as $y => $months)
  <details class="y"{{ $loop->first || str_starts_with($period, (string) $y) ? ' open' : '' }}>
    <summary class="{{ $period === (string) $y ? 'cur' : '' }}"><b>{{ $y }}</b><span>{{ array_sum(array_map('array_sum', $months)) }}</span></summary>
    <a class="sall{{ $period === (string) $y ? ' cur' : '' }}" href="{{ $base }}?p={{ $y }}" data-i="{{ $prefix }}_all">All posts</a>
    @foreach($months as $m => $days)
    <details class="m"{{ str_starts_with($period, "$y-$m") ? ' open' : '' }}>
      <summary class="{{ $period === "$y-$m" ? 'cur' : '' }}"><b data-i="{{ $prefix }}_m_{{ $y }}_{{ $m }}">{{ \Carbon\Carbon::create((int) $y, (int) $m, 1)->format('F') }}</b><span>{{ array_sum($days) }}</span></summary>
      <a class="sall{{ $period === "$y-$m" ? ' cur' : '' }}" href="{{ $base }}?p={{ $y }}-{{ $m }}" data-i="{{ $prefix }}_all">All posts</a>
      <div class="days">
        @foreach($days as $d => $n)
        <a href="{{ $base }}?p={{ $y }}-{{ $m }}-{{ $d }}" class="{{ $period === "$y-$m-$d" ? 'cur' : '' }}" title="{{ $n }}">{{ (int) $d }}</a>
        @endforeach
      </div>
    </details>
    @endforeach
  </details>
  @endforeach
  @include($ns.'::partials.calendar', ['A' => $A, 'base' => $base, 'tree' => $tree, 'period' => $period, 'prefix' => $prefix])
</div>
