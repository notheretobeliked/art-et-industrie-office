<article @php(post_class('h-entry'))>
  <header>
    <h1 class="p-name">
      {!! $title !!}
    </h1>

  </header>

  <div class="the-content">
    @php(the_content())
  </div>

  <footer>

  </footer>

</article>
