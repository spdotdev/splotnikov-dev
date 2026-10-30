{{-- One toolkit group card: $g from Toolkit::all(), $n the 1-based number. The Dutch keys tg_<slug>_* are pushed by the page that includes it. --}}
<div class="c g rv" id="{{ $g['slug'] }}"><span class="lbl">{{ sprintf('%02d', $n) }} <span data-i="tg_{{ $g['slug'] }}_l">{{ $g['label'] }}</span></span><h3 data-i="tg_{{ $g['slug'] }}_h">{{ $g['title'] }}</h3><div class="pl">
  @foreach($g['tools'] as $j => $t)
  <a href="{{ route('splotnikov.toolkit') }}#{{ $g['slug'] }}"><i class="lt"></i><span>{{ $t['name'] }}</span><small data-i="tg_{{ $g['slug'] }}_{{ $j }}">{{ $t['note'] }}</small></a>
  @endforeach
</div></div>
