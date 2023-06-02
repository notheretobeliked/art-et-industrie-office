<article
  @php(post_class('h-entry'))>
  <div class="the-content">
  <header class="alignwide">
    <h1 class="p-name font-display text-lg md:text-xl lg:text-4xl text-center uppercase tracking-widest my-4">
      {!! $title !!}
    </h1>
    <h3 class="font-serif text-lg normal-case tracking-normal">{!! $date_activite !!}, {!! $localite !!}</h3>

  </header>
  @php(the_content()) 


  @if ($artiste_listofevents)
    <div class="alignwide">
    <h2 class="text-left border-b border-black dark:border-white text-lg md:text-xl lg:text-2xl uppercase mb-4">{{ __('Evènements associés') }}</h2>
    @foreach ($artiste_listofevents as $event)
      <x-events-item-output :event="$event" />
    @endforeach
    </div>
  @endif

  @if ($artiste_listoflieux)
    <div class="alignwide flex flex-col">
      <h2 class="text-left border-b border-black dark:border-white text-lg md:text-xl lg:text-2xl uppercase mb-4">{{ __('Lieux d\'exposition') }}</h2>

      @foreach ($artiste_listoflieux as $contentitem)
        @if (!is_admin())
          <a href="{!! $contentitem["url"] !!}">
        @endif
        <div class="max-w-full overflow-x-scroll hide-scrollbar scroll-container border-b border-black dark:border-white hover:text-stroke-0">
          <div class="flex flex-row py-4 gap-4 items-center content-center">
            @if ($contentitem["image"] != '')
              <x-image-output :image="$contentitem['image']" size="medium" customsize class="h-12 w-48" />
            @endif
            <p
              class="whitespace-nowrap text-lg md:text-xl lg:text-4xl uppercase tracking-wider font-display text-stroke-05 md:text-stroke  text-fill-transparent hover:text-fill !mb-0">
              {!! $contentitem["title"] !!} </p>
            <p class="!mb-0 font-ui text-base uppercase whitespace-nowrap">{!! $contentitem["introtext"] !!}</p>
          </div>
        </div>
        @if (!is_admin())
          </a>
        @endif
      @endforeach
    </div>

    </div>
  @endif

@dump($artiste_listoflieux)
  </div>



</article>
