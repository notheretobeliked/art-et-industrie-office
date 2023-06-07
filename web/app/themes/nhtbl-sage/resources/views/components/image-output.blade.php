<div class="w-screen">
  @php error_log("cookie value:" . $_COOKIE['qualitySwitch']) @endphp
</div>
<div class="snap-start">
  <figure>
    <picture class="block object-cover {{ $class }}" x-data="{
        qualitySwitch: $store.utils.getCookie('qualitySwitch') || 'webp-low',
        basePath: '/app/uploads{!! $image['subdir'] !!}/',
        imageSources: {
            'webp': '{!! $image['other_formats']['webp'] !!}',
            'webp-low': '{!! $image['other_formats']['webp-low'] !!}',
            'webp-bw': '{!! $image['other_formats']['webp-bw'] !!}'
        }
    }">
      <source :srcset="basePath + imageSources[$store.quality.qualitySwitch]" srcset="/app/uploads/{!! $image['other_formats'][$cookieValue] !!}/" media="(min-width: 0px)" alt=" ">
      <img width="{!! $image['width'] !!}" height="{!! $image['height'] !!}"
        class="@if (!$customsize) !w-auto max-w-none @elseif (!$crop) w-full @endif @if ($crop) {{ $class }} @endif object-cover !h-full object-center"
        src=" {!! $image['src'][0] !!}" srcset=" {!! $image['srcset'] !!}" alt="{!! $image['alt'] !!}" />
    </picture>
    @if ($caption)
      {!! $image['caption'] !!}
    @endif
  </figure>
</div>
