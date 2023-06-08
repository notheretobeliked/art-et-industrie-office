@php
  
  $qualitySwitch = isset($_COOKIE['qualitySwitch']) ? $_COOKIE['qualitySwitch'] : '.png';
  
@endphp

<a class="brand" href="{{ home_url('/') }}">
  <h1
    class="px-2 grid grid-cols-logo font-ui text-xs text-fill-transparent text-stroke-05 md:text-stroke  text-stroke-black tracking-wider lg:tracking-widest uppercase md:fluid-xl hover:text-fill-black hover:text-stroke-0 dark:hover:text-fill-white">
    <span class="block">Triennale Art<br>Industrie</span>
    <span class="block text-center">&</span>
    <span class="block text-right">Dunkerque<br>Hauts–de–France</span>

    <span class="sr-only"> Triennale Art & Industrie Dunkerque Hauts–de–France </span>
  </h1>
</a>
<header x-cloak class="banner sticky top-0 bg-white dark:bg-black z-10" @keydown.escape="showMenu = false" x-data="{ showMenu: false }">
  <div class="px-2 w-full grid grid-cols-2 md:grid-cols-menu gap-4 bg-white dark:bg-black items-center"
    x-data="{ 'showModal': false }" @keydown.escape="showModal = false">
    <div class="flex flex-row gap-0 items-center top-0">
      <button @click="showMenu = !showMenu" class="items-center py-4 flex flex-row gap-2">
        <svg x-show="showMenu" class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
          stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <svg x-show="!showMenu" class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
          stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
        <p class="m-0 uppercase-small">Menu</p>
      </button>

    </div>
    @if (has_nav_menu('shortcut_navigation'))
      <nav class="hidden lg:flex flex-row gap-4 items-center justify-evenly" aria-label="Menu principal"
        aria-label="{{ wp_get_nav_menu_name('shortcut_navigation') }}">
        {!! wp_nav_menu([
            'theme_location' => 'shortcut_navigation',
            'menu_class' => 'shortcut_navigation flex flex-row gap-8 uppercase-small flex-grow justify-center !font-ui',
            'echo' => false,
        ]) !!}
      </nav>
    @else
      <div></div>
    @endif
    <div class="flex flex-row items-center justify-end relative" x-data="{ popupOpen: false }" @mouseleave="popupOpen = false"
      @mouseover="popupOpen = true">

      <p class="text-sm mb-0 mr-2 text-serif hidden md:block">{{ __('Mode', '_sage') }}</p>
      <div x-cloak class="mr-2 lg:mr-4 relative flex items-center gap-1 rounded-3xl p-1 border h-8 justify-self-end">
        <button x-on:click="darkMode = 'light'">
          <svg xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 p-1 text-black transition rounded-full cursor-pointer bg-gray-50 hover:bg-gray-200 stroke-white dark:stroke-black bg-red dark:bg-transparent"
            fill="none" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span class="sr-only">light</span>
        </button>

        <button x-on:click="darkMode = 'dark'">
          <svg xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 p-1 transition rounded-full dark:bg-green cursor-pointer dark:hover:bg-gray-100"
            viewBox="0 0 20 20" fill="currentColor">
            <path class="fill-black dark:fill-black"
              d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
          </svg>
          <span class="sr-only">dark</span>
        </button>
      </div>

      <p class="hidden md:block text-sm mb-0 mr-2 text-serif">{{ __('Energie', '_sage') }}</p>
      <div class="relative flex items-center gap-1 rounded-3xl p-1 border h-8 justify-self-end">
        <button
          x-on:click="$store.quality.qualitySwitch = 'webp-bw'; $store.utils.setCookie('qualitySwitch', 'webp-bw', 365)">
          <svg class="w-6 h-6" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle :class="{ 'fill-green': $store.quality.qualitySwitch === 'webp-bw' }" cx="9" cy="9"
              r="9" />
          </svg>


          <span class="sr-only">{{ __('Utilisation d\'energie basse', 'sage') }} </span>
        </button>

        <button
          x-on:click="$store.quality.qualitySwitch = 'webp-low'; $store.utils.setCookie('qualitySwitch', 'webp-low', 365)">
          <svg class="w-6 h-6" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle :class="{ 'fill-yellow': $store.quality.qualitySwitch === 'webp-low' }" cx="9"
              cy="9" r="9" />
          </svg>

          <span class="sr-only">{{ __('Utilisation d\'energie moyenne', 'sage') }} </span>
        </button>


        <button
          x-on:click="$store.quality.qualitySwitch = 'webp'; $store.utils.setCookie('qualitySwitch', 'webp', 365)">
          <svg class="w-6 h-6" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle :class="{ 'fill-red': $store.quality.qualitySwitch === 'webp' }" cx="9" cy="9"
              r="9" />
          </svg>

          <span class="sr-only">{{ __('Utilisation d\'energie elevée', 'sage') }} </span>
        </button>
      </div>
      <div x-show="popupOpen" class="absolute rounded-2 top-8 transform -translate-x-1/2 left-1/2">
        <svg class="text-black h-5 w-full left-0" x="0px" y="0px" viewBox="0 0 255 255"
          xml:space="preserve">
          <path d="M0 255L127.5 127.5L255 255H0Z" class="fill-black dark:fill-white" />
        </svg>
        <div class="py-4 px-8 bg-black dark:bg-white">
          <button @click="() => { showModal = true; popupOpen = false}"
            class="text-white dark:text-black w-full text-center text-xs font-serif whitespace-nowrap hover:italic">{!! $qualitySwitchData["tooltip"] !!}</button>
        </div>
      </div>
    </div>
    <div class="fixed inset-0 top-0 left-0 z-30 flex items-center justify-center overflow-auto bg-black dark:bg-gray-500 bg-opacity-50 dark:bg-opacity-50 max-h-screen"
      x-show="showModal" >

      <div @click.away="showModal = false" x-transition:enter="motion-safe:ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        class="max-w-3xl px-6 py-4 max-h-screen overflow-y-scroll mx-auto text-left bg-white dark:bg-black rounded shadow-lg">
        <div class="flex items-center justify-between">
          <h2 class="mr-3 text-black dark:text-white max-w-none">{!! $qualitySwitchData["modal_title"] !!}</h5>

          <button type="button" class="z-50 cursor-pointer" @click="showModal = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="w-full mt-4">
            {!! $qualitySwitchData["modal_content"] !!}
        </div>

      </div>

    </div>

  </div>

  @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary flex flex-col fixed top-0 z-10 bg-white text-black w-full h-screen" x-show="showMenu"
      x-collapse.duration.1000ms aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      <div class="w-full h-screen relative">
      <img src="@asset('images/degrade.svg')" :class="{ 'hidden': $store.quality.qualitySwitch === 'webp-bw' }" class="w-full h-full" alt="Degrade d'un charte chaleur" />
        <div class="absolute backdrop-blur-xl left-0 w-full h-full top-0 right-0 bottom-0"></div>
        <div class="absolute left-0 top-0 z-50 px-2">
          <button @click="showMenu = !showMenu" class="items-center py-4 flex flex-row gap-2">
            <svg x-show="showMenu" class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <svg x-show="!showMenu" class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
        {!! wp_nav_menu([
            'theme_location' => 'primary_navigation',
            'menu_class' => 'nav absolute w-full top-0 left-0 justify-center h-full',
            'echo' => false,
        ]) !!}
      </div>
    </nav>
  @endif
</header>
