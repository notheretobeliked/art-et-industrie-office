<div>
  <figure>
    <picture class="block object-cover {{$class}}">
      <source srcset="/app/uploads{!! $image['subdir'] !!}/{!! $image['other_formats']['webp-low'] !!}" media="(min-width: 0px)"
        alt=" {!! $image['alt'] !!}">
      <img width="{!! $image['width'] !!}" height="{!! $image['height'] !!}" class="@if (!$customsize) !max-w-fit @else w-full @endif object-cover !h-full object-center" src=" {!! $image['src'][0] !!}" srcset=" {!! $image['srcset'] !!}"
        alt="{!! $image['alt'] !!}" />
    </picture>
    {!! $image['caption'] !!}
  </figure>
</div>
