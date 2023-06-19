<div class="{{ $block->classes }}" x-data="{ filter: 'all', dateFilter: 'future', showFilter: false }">
  @if ($title)
  <div class="flex flex-row justify-between items-center border-b border-black dark:border-white mb-4">
    <h2 class="border-0 text-left text-lg md:text-xl lg:text-2xl uppercase">
      {{ $title }}</h2>
      @if ($has_filter)
          <button x-on:click="showFilter = !showFilter"
      class="inline-block py-1 px-3 md:py-1 md:px-3 rounded-2 font-serif  hover:bg-gray-500 border border-black dark:border-white dark:bg-black dark:text-white bg-white text-black"
      x-text="showFilter ? '{{ __('Fermer ↑', 'sage') }}' : '{{ __('Filtrés par ↓', 'sage') }}' "></button>
      @endif
  </div>
  @endif


  @if ($intro)
    <p class="text-lg mb-4">{!! $intro !!}</p>
  @endif

  @if ($events)

    <div id="category-filter" x-show="showFilter"  x-collapse.duration.200ms class="mb-12 md:grid grid-cols-eventmenu gap-4 content-center" x-data=>
      <h3 class="border-0 m-0 mb-3 md:mb-0">{{ __('Catégories', 'sage') }}</h3>
      <div class="flex flex-row flex-wrap gap-2">
        <button x-on:click="filter = 'all'"
          class="inline-block py-1 px-3 md:py-2 md:px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="filter === 'all' ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:bg-black dark:text-white bg-white text-black'">{{ __('Tous', 'sage') }}</button>

        @foreach ($allcategories as $category)
          <button x-on:click="filter = '{{ $category }}'"
            class="inline-block bg-black whitespace-nowrap  py-1 px-3 md:py-2 md:px-6 rounded-2 font-serif  hover:bg-gray-500 border"
            :class="filter === '{{ $category }}' ?
                'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
                'border-black dark:border-white dark:bg-black dark:text-white bg-white text-black'">{{ $category }}</button>
        @endforeach
      </div>
      <h3 class="border-0 m-0 my-3 md:my-0">{{ __('Quand', 'sage') }}</h3>
      <div class="flex flex-row flex-wrap gap-2">
        <button x-on:click="dateFilter = 'future'"
          class="inline-block py-1 px-3 md:py-2 md:px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="dateFilter === 'future' ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:bg-black dark:text-white bg-white text-black'">{{ __('A venir', 'sage') }}</button>

        <button x-on:click="dateFilter = 'today'"
          class="inline-block py-1 px-3 md:py-2 md:px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="dateFilter === 'today' ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:bg-black dark:text-white bg-white text-black'">{{ __('Aujourd’hui', 'sage') }}</button>
        <button x-on:click="dateFilter = 'this-month'"
          class="inline-block py-1 px-3 md:py-2 md:px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="dateFilter === 'this-month' ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:bg-black dark:text-white bg-white text-black'">{{ __('Ce mois', 'sage') }}</button>
      </div>

    </div>



    <div id="event-list">
      @foreach ($events as $event)
        <x-events-item-output :event="$event" />
      @endforeach
    </div>

  @endif
</div>
