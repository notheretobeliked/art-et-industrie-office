<div class="{{ $block->classes }}">
  @if ($content)
    <div>
      <InnerBlocks template="{!! $template !!}" templateLock="all" />
    </div>
    <div class="flex flex-col gap-4">
      @foreach ($content as $contentitem)
        @if (!is_admin())
          <a href="{!! get_permalink($contentitem->ID) !!}">
        @endif
        <div class="flex flex-row border-b border-black py-4 gap-4 items-center">
          @if ($showImage && get_the_post_thumbnail($contentitem->ID, 'post-thumbnail'))
            <figure>
              {!! get_the_post_thumbnail($contentitem->ID, 'post-thumbnail') !!}
            </figure>
          @endif
          <p class="text-4xl uppercase tracking-wider font-display text-stroke-2 text-fill-transparent hover:text-fill">
            {!! get_the_title($contentitem->ID) !!} </p>
        </div>
        @if (!is_admin())
          </a>
        @endif
      @endforeach
    @else
      <p>{{ $block->preview ? 'Add an item...' : 'No items found!' }}</p>
  @endif
</div>
