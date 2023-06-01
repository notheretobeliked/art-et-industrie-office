<div class="flex flex-row w-full overflow-x-scroll hide-scrollbar snap-x snap-mandatory h-96 md:h-136 gap-4 scroll-container overflow-y-hidden">
    @foreach ($images as $image)
      <x-image-output :image="$image" size="large" caption class=" h-80 md:h-128" />
    @endforeach
</div>
