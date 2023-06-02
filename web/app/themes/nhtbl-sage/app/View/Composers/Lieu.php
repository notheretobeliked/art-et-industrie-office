<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Lieu extends Composer
{
  /**
   * List of views served by this composer.
   *
   * @var array
   */
  protected static $views = [
    'partials.content-single-lieu',
  ];

  /**
   * Data to be passed to view before rendering, but after merging.
   *
   * @return array
   */
  public function with()
  {
    return [
      'address' => get_field('address'),
    ];
  }

}
