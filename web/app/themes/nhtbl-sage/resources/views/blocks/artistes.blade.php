<div class="{{ $block->classes }}">
  @if ($items)
    @if ($title)
      <h2 class="text-left border-b border-black dark:border-white text-2xl uppercase mb-4">{{ $title }}</h2>
    @endif
    @if ($mode == 'all')
      <ul class="list-none columns-2 lg:columns-3 gap-4 p-0 m-0">
        @foreach ($items as $letter => $group)
          <li><h3 class="mt-0 pt-0 uppercase text-lg lg:text-xl">{{ $letter }}</h3>
            <!-- this should output 'A', 'B' etc -->
            <ul class="list-none p-0 m-0">
              @foreach ($group as $subitem)
                <li class="!m-0 !p-0"><a class="!no-underline hover:italic" href="{!! $subitem['permalink'] !!}">{!! $subitem['title'] !!}</a></li>
              @endforeach
            </ul>
          </li>
        @endforeach
      </ul>
    @else
      <ul class="list-none p-0 m-0">
        @foreach ($items as $item)
          <li class="!m-0 !p-0"><a class="!no-underline hover:italic" href="{!! $subitem['permalink'] !!}">{!! $subitem['title'] !!}</a></li>
        @endforeach
      </ul>
    @endif
  @else
    <p>{{ $block->preview ? 'Aucun artiste choisi-e...' : '!' }}</p>
  @endif

</div>
