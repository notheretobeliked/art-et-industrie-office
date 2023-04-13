<div class="w-full flex flex-row overflow-x-scroll overflow-y-hidden h-96 gap-4">
  @foreach ($images as $image)
    <figure class="h-full w-auto">
      {!! $image['image'] !!}
      {!! $image['caption'] !!}
    </figure>
  @endforeach
</div>
