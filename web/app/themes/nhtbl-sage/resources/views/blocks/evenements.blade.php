<div class="{{ $block->classes }}">
  @if ($title)
    <h2 class="text-left border-b border-black dark:border-white text-lg md:text-xl lg:text-2xl uppercase mb-4">
      {{ $title }}</h2>
  @endif


  @if ($intro)
    <p class="text-lg mb-4">{!! $intro !!}</p>
  @endif

  @if ($events)
    <div id="date-filter" class="grid grid-cols-eventmenu gap-4 content-center" x-data=>
      <h3 class="border-0 m-0">{{ __('Quand', 'sage') }}</h3>
      <div class="flex flex-row gap-4">
        <button x-data="{ isActive: true }" @click="isActive = !isActive"
          class="inline-block bg-black text-white py-2 px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="isActive ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:text-white'">{{ __('Tout le temps', 'sage') }}</button>
        <button x-data="{ isActive: false }" @click="isActive = !isActive"
          class="inline-block bg-black text-white py-2 px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="isActive ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:text-white'">{{ __('Aujourd’hui', 'sage') }}</button>
        <button x-data="{ isActive: false }" @click="isActive = !isActive"
          class="inline-block bg-black text-white py-2 px-6 rounded-2 font-serif  hover:bg-gray-500 border"
          :class="isActive ? 'dark:bg-white dark:text-black bg-black text-white dark:hover:bg-gray-500' :
              'border-black dark:border-white dark:text-white'">{{ __('Ce mois', 'sage') }}</button>
      </div>
    </div>

    <div id="event-list" x-data="{ filter: 'all' }">
      @foreach ($events as $event)
        <x-events-item-output :event="$event" />
      @endforeach
    </div>

  @endif
</div>
<script>
  Alpine.data('eventList', () => ({
    filter: 'all',
    articles: [],
    isArticleVisible(article) {
      const { filter } = this.$data;
      const startDate = new Date(article.datestart);
      const endDate = new Date(article.dateend);
      const today = new Date();

      if (
        filter === 'all' ||
        (filter === 'today' && startDate <= today && endDate >= today) ||
        (filter === 'this_month' && startDate.getMonth() === today.getMonth())
      ) {
        return true;
      }

      return false;
    },
  }));
</script>