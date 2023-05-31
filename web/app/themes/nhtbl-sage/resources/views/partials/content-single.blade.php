<article @php(post_class('h-entry'))>
  <header>
    <h1 class="p-name font-display text-xl md:text-2xl lg:text-4xl text-center uppercase tracking-widest my-4">
      {!! $title !!}
    </h1>

  </header>

  <div class="the-content">
    @php(the_content())
  </div>

  <footer>

  </footer>

</article>
