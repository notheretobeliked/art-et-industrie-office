  <a class="brand" href="{{ home_url('/') }}">
    <h1
      class="px-2 grid grid-cols-logo font-ui text-xs text-fill-transparent text-stroke-05 md:text-stroke text-stroke text-stroke-black tracking-wider lg:tracking-widest uppercase md:fluid-xl hover:text-fill-black hover:text-stroke-0 dark:hover:text-fill-white">
      <span class="block">Triennale Art<br>Industrie</span>
      <span class="block text-center">&</span>
      <span class="block text-right">Dunkerque<br>Hauts–de–France</span>

      <span class="sr-only"> Triennale Art & Industrie Dunkerque Hauts–de–France </span>
    </h1>
  </a>
  <header class="banner sticky top-0 bg-white dark:bg-black z-10" x-data="{ showMenu: false }">
    <div class="px-2 w-full grid grid-cols-2 md:grid-cols-menu gap-4 bg-white dark:bg-black items-center">
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
        <nav class="hidden md:flex flex-row gap-4 items-center justify-evenly" aria-label="Menu principal"
          aria-label="{{ wp_get_nav_menu_name('shortcut_navigation') }}">
          {!! wp_nav_menu([
              'theme_location' => 'shortcut_navigation',
              'menu_class' => 'flex flex-row gap-4 uppercase-small flex-grow justify-center !font-ui',
              'echo' => false,
          ]) !!}
        </nav>
      @else
        <div></div>
      @endif
      <div x-cloak class="relative flex items-center gap-1 rounded-3xl p-1 border-2 h-8 justify-self-end">
        <button x-on:click="darkMode = 'light'">
          <svg xmlns="http://www.w3.org/2000/svg" x-bind:class="{ 'border-2 border-red/50': darkMode === 'light' }"
            class="w-6 h-6 p-1 text-black transition rounded-full cursor-pointer bg-gray-50 hover:bg-gray-200 stroke-black dark:stroke-white"
            fill="none" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span class="sr-only">light</span>
        </button>

        <button x-on:click="darkMode = 'dark'">
          <svg xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 p-1 transition rounded-full cursor-pointer dark:hover:bg-gray-100 border-2 border-gray-100 dark:border-white"
            viewBox="0 0 20 20" fill="currentColor">
            <path class="fill-black dark:fill-white" d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
          </svg>
          <span class="sr-only">dark</span>
        </button>
      </div>

    </div>

    @if (has_nav_menu('primary_navigation'))
      <nav class="nav-primary flex flex-col fixed z-10 bg-white text-black w-full h-screen" x-show="showMenu"
        x-collapse.duration.1000ms aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
      </nav>
    @endif
  </header>
