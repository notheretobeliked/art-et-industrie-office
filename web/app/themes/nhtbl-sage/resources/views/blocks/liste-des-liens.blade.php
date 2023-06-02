<div class="{{ $block->classes }}">
  @if ($content)
    @if ($title)
      <h2 class="text-left border-b border-black dark:border-white hover:text-stroke-0 text-lg md:text-xl lg:text-2xl uppercase mb-4">{{ $title }}</h2>
    @endif

    @if ($intro)
      <p class="text-lg mb-4">{!! $intro !!}</p>
    @endif
    <div class="flex flex-col gap-4 w-full max-w-full">
      @foreach ($content as $contentitem)
        @if (!is_admin())
          <a href="{!! $contentitem["url"] !!}">
        @endif
        <div class="max-w-full overflow-x-scroll hide-scrollbar scroll-container border-b border-black dark:border-white hover:text-stroke-0">
          <div class="flex flex-row py-4 gap-4 items-center content-center">
            @if ($showImage && $contentitem["image"])
              <x-image-output :image="$contentitem['image']" size="medium" customsize class="h-12 w-48" />
            @endif
            <p
              class="whitespace-nowrap text-lg md:text-xl lg:text-4xl uppercase tracking-wider font-display text-stroke-05 md:text-stroke text-fill-transparent hover:text-fill !mb-0">
              {!! $contentitem["title"] !!} </p>
            <p class="!mb-0 font-ui text-base uppercase whitespace-nowrap">{!! $contentitem["introtext"] !!}</p>
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
