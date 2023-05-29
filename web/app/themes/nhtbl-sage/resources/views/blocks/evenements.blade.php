<div class="{{ $block->classes }}">
  @if ($title)
    <h2 class="text-left border-b border-black dark:border-white text-2xl uppercase mb-4">{{ $title }}</h2>
  @endif


  @if ($intro)
    <p class="text-lg mb-4">{!! $intro !!}</p>
  @endif

  @if ($events)

    @foreach ($events as $event)
      <x-events-item-output :event="$event" />
    @endforeach

@endif
</div>
