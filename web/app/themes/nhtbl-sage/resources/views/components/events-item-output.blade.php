<article x-data="{ showDetail: false }" data-tags="{{ implode(' ', $event['filtertags']) }}" x-on:mouseenter="showDetail = true"
  x-on:mouseleave="showDetail = false" x-transition
  class="border-b border-black dark:border-white py-2 grid md:grid-cols-event gap-2 hover:bg-black dark:hover:bg-white hover:bg-opacity-10 dark:hover:bg-opacity-10"
  x-collapse.duration.100ms x-show="(filter === 'all' || '{{ implode(' ', $event['filtertags']) }}'.includes(filter)) && (dateFilter === 'all' || '{{ implode(' ', $event['filtertags']) }}'.includes(dateFilter))">

  @if (!is_admin())
    <a href="{{ $event['permalink'] }}" class="contents">
  @endif

  <div class="flex flex-row md:flex-col content-between gap-2">
    <p class="m-0 w-full text-sm">
      @if ($event['end_date'])
        {{ $event['date'] }} – {{ $event['end_date'] }}
      @else
        {{ $event['date'] }}
      @endif
    </p>
    @if ($event['time'])
      <p class="text-sm m-0 w-full text-right md:text-left">{{ $event['time'] }} – {{ $event['end_time'] }}
      </p>
    @endif
  </div>
  <div>
    <h3 class="text-base m-0 border-0">{!! $event['title'] !!}</h3>
    @if ($event['categories'])
      <p class="m-0">
        @foreach ($event['categories'] as $category)
          {{ $category['name'] }}
          @if (!$loop->last)
            |
          @endif
        @endforeach
      </p>
    @endif
  </div>
  <div>
    <p class="text-sm m-0 w-full text-right md:text-left">{!! $event['lieu']['title'] !!}</p>
  </div>
  @if (!is_admin())
    </a>
  @endif
  @if ($event['thumbnail'])
    <div></div>
    <div x-show="showDetail" x-collapse.duration.200ms>
      <x-image-output :image="$event['thumbnail']" size="large" customsize class="w-1/2" />
    </div>
  @endif
</article>
