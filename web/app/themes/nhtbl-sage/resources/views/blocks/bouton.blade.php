<div class="text-center">
@if (!is_admin())<a href="{{$url}}">@endif
<div class="inline-block mx-auto border border-black rounded {{ $block->classes }} {{ $size == 'grand' ? ' px-8 py-11 text-stroke-2 text-fill-transparent hover:text-fill-black' : ' py-2 px-6' }}">

  <h2 class="border-0 uppercase {{ $size == 'grand' ? ' text-2xl' : 'text-xl' }}">{{ $label }}</h2>

<div class=""></div>


</div>
@if (!is_admin())</a>@endif
</div>