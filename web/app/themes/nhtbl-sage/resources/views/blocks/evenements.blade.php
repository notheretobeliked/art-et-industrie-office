<div class="{{ $block->classes }}">
  @if ($title)
    <h2 class="text-left border-b border-black text-2xl uppercase mb-4">{{ $title }}</h2>
  @endif


  @if ($intro)
    <p class="text-lg mb-4">{!! $intro !!}</h2>
  @endif

  @if ($events)

    @foreach ($events as $event)
      <article>
      @if (!is_admin()) <a href="{{ $event['permalink'] }}" class="grid grid-cols-event border-b border-black">@endif
          <div>
            <p class="m-0">
              @if ($event['end_date'])
                {{ $event['date'] }} – {{ $event['end_date'] }}
              @else
                {{ $event['date'] }}
              @endif
            </p>
            <p>{{ $event['time'] }} – {{ $event['end_time'] }} </p>
          </div>
          <div>
            <h3 class="text-base m-0">{{ $event['title'] }}</h3>
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
            {{ $event["lieu"]['title']}}
          </div>

        @if (!is_admin()) </a>@endif
      </article>
    @endforeach

@endif
</div>
