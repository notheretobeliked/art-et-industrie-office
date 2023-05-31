<article>
  @if (!is_admin())
    <a href="{{ $event['permalink'] }}" class="grid md:grid-cols-event gap-2 py-2 border-b border-black dark:border-white">
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
      <p class="text-sm m-0 w-full text-right md:text-left">{{ $event['time'] }} – {{ $event['end_time'] }} </p>
    @endif
  </div>
  <div>
    <h3 class="text-base m-0">{!! $event['title'] !!}</h3>
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
    <p>{!! $event['lieu']['title'] !!}</p>
  </div>

  @if (!is_admin())
    </a>
  @endif
</article>
