<div class="{{ $block->classes }}" @if (!is_admin()):class="{ 'grayscale': $store.quality.qualitySwitch === 'webp-bw' }" @endif>
  <div class="w-full h-[50vh] md:h-[75vh]">
    <div class="w-full h-full relative">
      <img src="@asset('images/degrade.svg')" @if (!is_admin()):class="{ 'grayscale': $store.quality.qualitySwitch === 'webp-bw' }"@endif class="w-full h-full" alt="Degrade d'un charte chaleur" />
      <div class="absolute backdrop-blur-xl left-0 w-full h-full top-0 right-0 bottom-0"></div>

      <hgroup class="text-black dark:text-black uppercase absolute text-center w-full h-full top-0 bottom-0 flex items-center">
        <div class="w-full">
          <p class="tracking-wider text-sm md:text-base xl:text-lg !leading-supertight font-ui">du 10 juin 2023<br />
            au 14 janvier 2024</p>
          <h2 class="border-0 my-8  text-stroke-4 text-4xl md:fluid-3xl px-3 lg:px-48 !tracking-megawide blur-sm lg:blur-lg">
            Chaleur<br>Humaine
          </h2>
          <p class="tracking-widest text-sm md:text-base xl:text-lg font-ui">Consciences énergétiques</p>
        </div>
      </hgroup>
    </div>
  </div>
</div>
