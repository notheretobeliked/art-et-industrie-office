<article class="border-b border-black dark:border-white py-2">
  @if (!is_admin())
    <a href="{{ $event['permalink'] }}"
      class="grid md:grid-cols-event gap-2">
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
    <p class="text-sm m-0 w-full text-right md:text-left">{!! $event['lieu']['title'] !!}</p>
  </div>

  @if ($event['image'])
    <x-image-output :image="$event['image']" size="medium" customsize class="h-12 w-48" />
  @endif
  @if (!is_admin())
    </a>
  @endif
  
</article>
