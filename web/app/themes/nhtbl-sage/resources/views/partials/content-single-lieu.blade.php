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
  <div class="alignwide">
    <h2>{{ __('Evènements associés') }}</h2>
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
        <li class="m-0 p-0"><a href="{!! $artiste['permalink'] !!}">{!! $artiste['title'] !!}</a></li>
      @endforeach
    </ul>
  </div>
  @endif
  </article>
