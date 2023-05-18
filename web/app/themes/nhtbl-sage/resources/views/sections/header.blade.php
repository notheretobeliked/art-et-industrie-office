<header class="banner sticky top-0 bg-white z-10" x-data="{ showMenu: false }">

  <a class="brand" href="{{ home_url('/') }}">
    {!! $siteName !!}
  </a>
  <div class="w-full flex flex-row gap-4 bg-white">
    <div class="flex w-1/6 flex-row gap-0 items-center top-0">
      <button @click="showMenu = !showMenu" class="px-2 py-4 flex flex-row">
        <svg x-show="showMenu" class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
          stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <svg x-show="!showMenu" class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round"
          stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
        <div class="uppercase-small">Menu</div>
      </button>

    </div>
  @if (has_nav_menu('shortcut_navigation'))
    <nav class="hidden md:flex w-2/3 flex-row gap-4 items-center justify-evenly" aria-label="Menu principal"
      aria-label="{{ wp_get_nav_menu_name('shortcut_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'shortcut_navigation', 'menu_class' => 'flex flex-row gap-4 uppercase-small flex-grow justify-center', 'echo' => false]) !!}
    </nav>
  @endif

  </div>

  @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary flex flex-col fixed z-10 bg-white w-full h-screen" x-show="showMenu"
      aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    </nav>
  @endif
</header>
