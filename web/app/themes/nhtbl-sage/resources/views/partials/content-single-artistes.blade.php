<article
  @php(post_class('h-entry'))>
  <div class="the-content">
  <header class="alignwide">
    <h1 class="p-name font-display text-4xl text-center uppercase tracking-widest my-4">
      {!! $title !!} WEE
    </h1>
    <h3 class="font-serif text-lg normal-case tracking-normal">{!! $date_activite !!}, {!! $localite !!}</h3>

  </header>
  @if ($event_artiste)
      <div class="alignwide">

<x-image-output :image="1417" />

    <h2 class="text-left border-b border-black dark:border-white text-2xl uppercase mb-4">{{ __('Evènements associés') }}</h2>
    @foreach ($event_artiste as $event)
      <x-events-item-output :event="$event" />
    @endforeach
    </div>

@endif
  @php(the_content()) </div>

  <footer>

  </footer>

</article>