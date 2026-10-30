{{-- Variables also: $A (the Archive class), $ns (the view namespace).
     The archive beside a post list: years collapsed, months inside, days that link to the posts of that day; plus the page links.
     Variables: $base (the list url), $tree (Archive::tree), $period (the active filter or ''), $page, $pages, $prefix (data-i prefix). --}}
<aside class="arch">
  <small data-i="{{ $prefix }}_arch">Archive</small>
  <a class="all{{ $period === '' ? ' cur' : '' }}" href="{{ $base }}" data-i="{{ $prefix }}_all">All posts</a>
  @foreach($tree as $y => $months)
  <details class="y"{{ $loop->first || str_starts_with($period, (string) $y) ? ' open' : '' }}>
    <summary><a href="{{ $base }}?p={{ $y }}" class="{{ $period === (string) $y ? 'cur' : '' }}">{{ $y }}</a><span>{{ array_sum(array_map('array_sum', $months)) }}</span></summary>
    @foreach($months as $m => $days)
    <details class="m"{{ str_starts_with($period, "$y-$m") ? ' open' : '' }}>
      <summary><a href="{{ $base }}?p={{ $y }}-{{ $m }}" class="{{ $period === "$y-$m" ? 'cur' : '' }}" data-i="{{ $prefix }}_m_{{ $y }}_{{ $m }}">{{ \Carbon\Carbon::create((int) $y, (int) $m, 1)->format('F') }}</a><span>{{ array_sum($days) }}</span></summary>
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
</aside>
