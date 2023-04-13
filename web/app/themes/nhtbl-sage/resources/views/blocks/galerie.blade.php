<div class="{{ $block->classes }}">

  @if ($galerie)
  <x-galerie-output :images="$galerie" />
  @else
    <p>{{ $block->preview ? 'Ajouter une image dans le panel à droite...' : '' }}</p>
  @endif

  <div>
    <InnerBlocks />
  </div>
</div>
