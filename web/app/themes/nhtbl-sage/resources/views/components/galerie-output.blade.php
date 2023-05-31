<div class="w-full overflow-x-scroll hide-scrollbar h-96 md:h-132 gap-4 scroll-container overflow-y-hidden">
  <div class="flex flex-row h-96 md:h-132 gap-4 flex-grow">
    @foreach ($images as $image)
      <x-image-output :image="$image" size="large" caption class=" h-80 md:h-128" />
    @endforeach
  </div>
</div>
