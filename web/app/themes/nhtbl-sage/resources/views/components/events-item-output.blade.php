<article>
  @if (!is_admin())
    <a href="{{ $event['permalink'] }}" class="grid grid-cols-event border-b border-black dark:border-white">
  @endif
  <div>
    <p class="m-0">
      @if ($event['end_date'])
        {{ $event['date'] }} – {{ $event['end_date'] }}
      @else
        {{ $event['date'] }}
      @endif
    </p>
    @if($event['time'] ) <p>{{ $event['time'] }} – {{ $event['end_time'] }} </p>@endif
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
