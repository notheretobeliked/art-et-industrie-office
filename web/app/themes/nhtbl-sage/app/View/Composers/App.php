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
        $qualitySwitch = isset($_COOKIE['qualitySwitch']) ? $_COOKIE['qualitySwitch'] : 'webp-low';
        $languages = function_exists('pll_the_languages') ? pll_the_languages( array( 'raw' => 1 ) ) : null;
        $qualitySwitchData = pll_current_language() === 'fr' ? get_field('energy_selector', 'option') : get_field('energy_selector_en', 'option');
        return [
            'siteName' => $this->siteName(),
            'qualitySwitch' => $qualitySwitch,
            'qualitySwitchData' => $qualitySwitchData,
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
