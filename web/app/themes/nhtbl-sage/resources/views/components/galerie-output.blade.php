<div class="w-full overflow-y-scroll h-128 gap-4 scroll-container overflow-hidden">
  <div class="flex flex-row h-128 gap-4 flex-grow">
    @foreach ($images as $image)
    
      <figure class="h-full max-w-fit">
        <picture>
          <source srcset="/app/uploads{!! $image["subdir"]!!}/{!! $image["other_formats"]["large"]["webp-low"]!!}" media="(min-width: 0px)">
          <img class="!max-w-fit !h-full src=" {!! $image['src'] !!}" srcset=" {!! $image['srcset'] !!}" alt=" {!! $image['alt'] !!}" />
        </picture>
        {!! $image['caption'] !!}
      </figure>
    @endforeach
  </div>
</div>
