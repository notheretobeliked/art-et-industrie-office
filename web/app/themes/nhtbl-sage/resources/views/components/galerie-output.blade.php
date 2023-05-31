<div class="w-full overflow-y-scroll h-128 gap-4 scroll-container overflow-hidden">
  <div class="flex flex-row h-128 gap-4 flex-grow">
    @foreach ($images as $image)
      <x-image-output :image="$image" size="large" caption class="h-128" />
    @endforeach
  </div>
</div>
