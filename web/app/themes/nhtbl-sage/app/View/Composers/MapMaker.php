<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class MapMaker extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.content-*',
        'tribe.events.*'
    ];

    /**
     * Data to be passed to view before rendering, but after merging.
     *
     * @return array
     */
    public function with()
    {
        $this->enqueue();
        return [
            'slug' => get_post_field( 'post_name', get_post() ),
        ];
    }

    /**
     * Enqueue mapbox
     *
     * @return string
     */
    public function enqueue()
    {
      wp_enqueue_style('mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css');
      wp_enqueue_script('mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js');
    }
}
