<div class="w-full overflow-y-scroll h-128 gap-4 scroll-container overflow-hidden">
  <div class="flex flex-row h-128 gap-4 flex-grow">
    @foreach ($images as $image)
      <figure class="h-full max-w-fit">
        <img class="!max-w-fit !h-full src=" {!! $image['src'] !!}" srcset=" {!! $image['srcset'] !!}"
          alt=" {!! $image['alt'] !!}" />
        {!! $image['caption'] !!}
      </figure>
    @endforeach
  </div>
</div>

