  <a class="brand" href="{{ home_url('/') }}">
    <h1
      class="px-2 grid grid-cols-logo font-ui text-xs text-fill-transparent text-stroke-05 md:text-stroke  text-stroke-black tracking-wider lg:tracking-widest uppercase md:fluid-xl hover:text-fill-black hover:text-stroke-0 dark:hover:text-fill-white">
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
              'menu_class' => 'shortcut_navigation flex flex-row gap-8 uppercase-small flex-grow justify-center !font-ui',
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
      <nav class="nav-primary flex flex-col fixed top-0 z-10 bg-white text-black w-full h-screen" x-show="showMenu"
        x-collapse.duration.1000ms aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
    <div class="w-full h-screen relative">
      <svg width="100%" height="100%" preserveAspectRatio="none" viewBox="0 0 1440 920" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_202_56030)">
          <rect width="1440" height="920" fill="#5A8BFF" />
          <path
            d="M503.536 -22.3029C524.503 10.3564 529.862 57.1264 509.474 97.7493C489.087 138.372 397.115 253.26 369.92 275.384C342.724 297.509 342.7 333.338 415.616 334.876C488.532 336.415 533.556 341.999 513.396 393.228C493.237 444.457 388.618 569.09 283.782 594.811C208.196 613.331 -341.204 518.537 -56.9393 61.5356C87.3328 -170.413 457.161 -94.4951 503.536 -22.3029Z"
            fill="#8CFDCC" />
          <mask id="mask0_202_5603" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="-138" y="-95"
            width="661" height="692">
            <path
              d="M504.143 -22.3058C525.13 10.353 530.494 57.1294 510.087 97.7516C489.68 138.374 397.64 253.253 370.4 275.377C343.161 297.501 343.154 333.336 416.14 334.889C489.125 336.442 534.191 342.012 514.013 393.233C493.834 444.454 389.116 569.092 284.181 594.813C208.523 613.325 -341.396 518.526 -56.8626 61.5243C87.546 -170.406 457.712 -94.4969 504.143 -22.3058Z"
              fill="white" />
          </mask>
          <g mask="url(#mask0_202_5603)">
            <path
              d="M307.999 603.051C621.529 326.834 676.049 -198.851 429.772 -571.098C183.496 -943.345 -270.317 -1021.19 -583.847 -744.975C-897.377 -468.758 -951.896 56.9264 -705.62 429.174C-459.343 801.421 -5.53047 879.268 307.999 603.051Z"
              fill="url(#paint0_radial_202_56030)" />
          </g>
          <path
            d="M1422.07 -56.0025C1422.07 -56.0025 1176.08 -71.1486 1114.58 42.4736C1053.08 156.096 1008.61 302.76 914.005 353.164C819.394 403.568 551.47 489.308 513.45 553.8C479.974 610.592 489.771 875.744 458.447 923.762C435.874 958.317 279.731 1131.09 386.267 1186.67C492.803 1242.25 1255.79 1283.85 1404.84 1186.36C1682.81 1004.52 2054.26 24.802 1422.07 -56.0025Z"
            fill="#8CFDCC" />
          <mask id="mask1_202_5603" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="349" y="-57"
            width="1420" height="1302">
            <path
              d="M1421.32 -56.0025C1421.32 -56.0025 1175.5 -71.1486 1114.05 42.4736C1052.59 156.096 1008.16 302.76 913.612 353.164C819.068 403.568 551.329 489.308 513.336 553.8C479.883 610.592 489.673 875.744 458.371 923.762C435.814 958.317 279.779 1131.09 386.241 1186.67C492.703 1242.25 1255.16 1283.85 1404.11 1186.36C1681.89 1004.52 2053.07 24.802 1421.32 -56.0025Z"
              fill="white" />
          </mask>
          <g mask="url(#mask1_202_5603)">
            <path
              d="M1913.13 2033.41C2362.74 1680.94 2426.13 989.948 2054.72 490.032C1683.3 -9.88513 1017.72 -129.414 568.104 223.056C118.49 575.526 55.1005 1266.52 426.52 1766.44C797.939 2266.36 1463.52 2385.88 1913.13 2033.41Z"
              fill="url(#paint1_radial_202_56030)" />
          </g>
          <path
            d="M831.251 1194.24C1065.44 1150.02 1219.66 914.718 1175.7 668.681C1131.74 422.643 906.252 259.037 672.059 303.257C437.865 347.476 283.65 582.776 327.61 828.814C371.57 1074.85 597.057 1238.46 831.251 1194.24Z"
            fill="url(#paint2_radial_202_56030)" />
          <g filter="url(#filter0_b_202_56034)">
            <rect width="1440" height="920" fill="white" fill-opacity="0.01" />
          </g>
        </g>
        <defs>
          <filter id="filter0_b_202_56034" x="-164" y="-164" width="1768" height="1248"
            filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix" />
            <feGaussianBlur in="BackgroundImageFix" stdDeviation="82" />
            <feComposite in2="SourceAlpha" operator="in" result="effect1_backgroundBlur_202_5603" />
            <feBlend mode="normal" in="SourceGraphic" in2="effect1_backgroundBlur_202_5603" result="shape" />
          </filter>
          <radialGradient id="paint0_radial_202_56030" cx="0" cy="0" r="1"
            gradientUnits="userSpaceOnUse"
            gradientTransform="translate(-136.122 -60.5628) rotate(-41.3798) scale(757.529 802.398)">
            <stop offset="0.19" stop-color="#FF4D0D" />
            <stop offset="0.52" stop-color="#FBEC40" />
            <stop offset="0.65" stop-color="#E4F05D" />
            <stop offset="1" stop-color="#8CFDCC" stop-opacity="0" />
          </radialGradient>
          <radialGradient id="paint1_radial_202_56030" cx="0" cy="0" r="1"
            gradientUnits="userSpaceOnUse"
            gradientTransform="translate(1242.42 1130.91) rotate(53.3891) scale(1126.6 1033.48)">
            <stop offset="0.19" stop-color="#FF4D0D" />
            <stop offset="0.52" stop-color="#FBEC40" />
            <stop offset="0.65" stop-color="#E4F05D" />
            <stop offset="1" stop-color="#8CFDCC" stop-opacity="0" />
          </radialGradient>
          <radialGradient id="paint2_radial_202_56030" cx="0" cy="0" r="1"
            gradientUnits="userSpaceOnUse"
            gradientTransform="translate(759.811 749.16) rotate(79.8698) scale(452.353 432.934)">
            <stop offset="0.19" stop-color="#FF4D0D" />
            <stop offset="0.52" stop-color="#FBEC40" />
            <stop offset="0.99" stop-color="#8CFDCC" stop-opacity="0" />
          </radialGradient>
          <clipPath id="clip0_202_56030">
            <rect width="1440" height="920" fill="white" />
          </clipPath>
        </defs>
      </svg>
      <div class="absolute backdrop-blur-3xl left-0 w-full h-full top-0 right-0 bottom-0"></div>
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
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav absolute w-full top-0 left-0 justify-center h-full', 'echo' => false]) !!}
    </div>
      </nav>

    @endif
  </header>
