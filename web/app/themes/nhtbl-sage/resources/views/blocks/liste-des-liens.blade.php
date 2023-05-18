<div class="{{ $block->classes }}">
<?php
            $items = get_posts([
                'post__in' => get_field('content')
            ]);
?>
@dump($items)
  @if ($content)

  @else
    <p>{{ $block->preview ? 'Add an item...' : 'No items found!' }}</p>
  @endif
</div>
