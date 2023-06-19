<article
  @php(post_class('h-entry'))>
  <div class="the-content">
  <header class="alignwide">
    <h1 class="p-name font-display text-lg md:text-xl lg:text-4xl text-center uppercase tracking-widest my-4">
      {!! $title !!}
    </h1>

  </header>
  
  @if ($galerie)
    <div class="mx-auto max-w-4xl">
      <x-galerie-output :images="$galerie" />
    </div>
  @endif

  <div class="grid gap-8 md:gap-16 md:grid-cols-mapandcontent alignwide">
    <div class="flex flex-col gap-1">
      <h3 class="font-ui uppercase text-base md:text-lg"> {{ __('Accès', 'sage') }} </h3>
        <x-map-output size="small" :slug="get_post_field( 'post_name', get_post() )" />
        <div>{!! $address !!}</div>
    </div>

    <div>
      @php(the_content()) 
    </div>
  </div>  

  @if ($lieu_listofevent)
  <div class="alignwide"  x-data="{ filter: 'all', dateFilter: 'future', showFilter: false }">
  <div class="flex flex-row justify-between items-center border-b border-black dark:border-white mb-4">
    <h2 class="border-0 text-left text-lg md:text-xl lg:text-2xl uppercase">
     {{ __('Evènements associés') }}</h2>
      @if (count($lieu_listofevent) > 10)
          <button x-on:click="showFilter = !showFilter"
      class="inline-block py-1 px-3 md:py-1 md:px-3 rounded-2 font-serif  hover:bg-gray-500 border border-black dark:border-white dark:bg-black dark:text-white bg-white text-black"
      x-text="showFilter ? '{{ __('Fermer ↑', 'sage') }}' : '{{ __('Filtrés par ↓', 'sage') }}' "></button>
      @endif
  </div>

    <div id="category-filter" x-show="showFilter"  x-collapse.duration.200ms class="mb-12 md:grid grid-cols-eventmenu gap-4 content-center" x-data=>
      <h3 class="border-0 m-0 mb-3 md:mb-0">{{ __('Catégories', 'sage') }}</h3>
      <div class="flex flex-row flex-wrap gap-2">
        <button x-on:click="filter = 'all'"
          class="inline-block py-1 px-3 md:py-2 md:px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="filter === 'all' ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:bg-black dark:text-white bg-white text-black'">{{ __('Tous', 'sage') }}</button>

        @foreach ($lieu_eventsfilter as $category)
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


    
    @foreach ($lieu_listofevent as $event)
      <x-events-item-output :event="$event" />
    @endforeach
  </div>
  @endif

  @if ($lieu_listofartistes)
  <div class="alignwide">
    <h2>{{ __('Artistes associés') }}</h2>
    <ul class="list-none columns-2 lg:columns-3 gap-4 p-0 m-0">
      @foreach ($lieu_listofartistes as $artiste)
        <li class="!m-0 !p-0"><a class="!no-underline hover:italic" href="{!! $artiste['permalink'] !!}">{!! $artiste['title'] !!}</a></li>
      @endforeach
    </ul>
  </div>
  @endif
  </article>
