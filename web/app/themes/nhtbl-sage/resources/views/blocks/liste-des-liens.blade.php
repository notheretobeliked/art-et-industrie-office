<div class="{{ $block->classes }}">
  @if ($title)
    <h2 class="text-left border-b border-black text-2xl uppercase mb-4">{{ $title }}</h2>
  @endif
  @if ($content)
    @if ($intro)
      <h2 class="text-lg mb-4">{!! $intro !!}</h2>
    @endif
    <div class="flex flex-col gap-4 w-full max-w-full">
      @foreach ($content as $contentitem)
        @if (!is_admin())
          <a href="{!! get_permalink($contentitem->ID) !!}">
        @endif
        <div class="max-w-full overflow-hidden scroll-container border-b border-black">
        <div class="flex flex-row py-4 gap-4 items-center">
          @if ($showImage && get_the_post_thumbnail($contentitem->ID, 'post-thumbnail'))
            <figure>
              {!! get_the_post_thumbnail($contentitem->ID, 'post-thumbnail') !!}
            </figure>
          @endif
          <p class="whitespace-nowrap text-4xl uppercase tracking-wider font-display text-stroke-2 text-fill-transparent hover:text-fill">
            {!! get_the_title($contentitem->ID) !!} </p>
          </div>
        </div>
        @if (!is_admin())
          </a>
        @endif
      @endforeach
    </div>
    @else
      <p>{{ $block->preview ? 'Add an item...' : 'No items found!' }}</p>
  @endif
</div>
