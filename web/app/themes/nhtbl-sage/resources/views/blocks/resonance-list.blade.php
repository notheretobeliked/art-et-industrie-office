<div class="{{ $block->classes }}">
  @if ($items)
    <ul class="list-none m-0 p-0 grid grid-cols-2 md:grid-cols-3 gap-4">
      @foreach ($items as $item)
      <li class="m-0 p-0">
        <a href="{!!$item["permalink"]!!}" class="hover:italic font-ui uppercase tracking-wider">
        @if ($item['image'])
        <x-image-output :image="$item['image']" size="medium" customsize crop class="h-48 md:h-56 w-full" />
        @else
        <div class="relative h-48 md:h-56 w-full overflow-hidden">
          <img src="@asset("images/degrade.svg")" class="h-48 md:h-56 w-full" width="100%" height="100%" preserveAspectRatio="none" />
          <div class="absolute backdrop-blur-xl left-0 w-full h-full top-0 right-0 bottom-0"></div>
        </div>
        @endif
        {!!$item["title"]!!}
        </a>
      </li>
      @endforeach
    </ul>
  @else
    <p>{{ $block->preview ? 'Add an item...' : 'No items found!' }}</p>
  @endif

  <div>
    <InnerBlocks />
  </div>
</div>
