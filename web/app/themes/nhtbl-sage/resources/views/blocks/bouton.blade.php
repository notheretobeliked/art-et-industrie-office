<div class="text-center">
@if (!is_admin())<a href="{{$url}}">@endif
<div class="mx-auto border border-black hover:text-stroke-0 dark:border-white rounded {{ $block->classes }} {{ $size == 'grand' ? ' px-4 lg:px-8 py-3 lg:py-11 text-stroke-05 md:text-stroke text-fill-transparent hover:text-fill-black dark:hover:text-fill-white' : ' inline-block py-2 px-6' }}">

  <h2 class="border-0 uppercase {{ $size == 'grand' ? ' text-lg md:text-xl lg:text-2xl' : 'text-lg lg:text-xl' }}">{{ $label }}</h2>

<div class=""></div>


</div>
@if (!is_admin())</a>@endif
</div>