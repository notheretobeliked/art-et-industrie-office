<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $languages = function_exists('pll_the_languages') ? pll_the_languages( array( 'raw' => 1 ) ) : null;
        return [
            'siteName' => $this->siteName(),
            'qualitySwitch' => $_COOKIE['qualitySwitch'] ? $_COOKIE['qualitySwitch'] : 'webp-low',
            'qualitySwitchData' => get_field('energy_selector', 'option'),
            'languageSwitchActive' => get_field('language_selector_active', 'option'),
            'languages' => $languages,
        ];
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    public function siteName()
    {
        return get_bloginfo('name', 'display');
    }
}
