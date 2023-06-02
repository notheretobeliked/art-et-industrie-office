<article
  @php(post_class('h-entry'))>
  <div class="the-content">
  <header class="alignwide">
    <h1 class="p-name font-display text-lg md:text-xl lg:text-4xl text-center uppercase tracking-widest my-4">
      {!! $title !!}
    </h1>
    <h3 class="font-serif text-lg normal-case tracking-normal">{!! $date_activite !!}, {!! $localite !!}</h3>

  </header>
  @php(the_content()) </div>

  @if ($event_artiste)
    <div class="alignwide">
    <h2 class="text-left border-b border-black dark:border-white text-lg md:text-xl lg:text-2xl uppercase mb-4">{{ __('Evènements associés') }}</h2>
    @foreach ($event_artiste as $event)
      <x-events-item-output :event="$event" />
    @endforeach
    </div>
  @endif

  <footer>

  </footer>

</article>
