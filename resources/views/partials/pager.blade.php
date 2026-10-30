{{-- Page links under a list. Variables: $base, $period, $page, $pages, $prefix. --}}
@if($pages > 1)
<nav class="pager" aria-label="Pages">
  @php($q = $period !== '' ? '&p='.$period : '')
  @if($page > 1)<a class="btn o" href="{{ $base }}?page={{ $page - 1 }}{{ $q }}" data-i="{{ $prefix }}_prev">← Newer</a>@endif
  <span class="n"><span data-i="{{ $prefix }}_page">Page</span> {{ $page }} / {{ $pages }}</span>
  @if($page < $pages)<a class="btn o" href="{{ $base }}?page={{ $page + 1 }}{{ $q }}" data-i="{{ $prefix }}_next">Older →</a>@endif
</nav>
@endif
