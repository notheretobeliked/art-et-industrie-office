<?php

namespace App\View\Components;

use Illuminate\View\Component;
use function Env\env;

class MapOutput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $uniqueMapId;
    public $slug = 'all';
    public $size = 'large';

    public function __construct($slug = 'all', $size = 'large')
    {
        $this->uniqueMapId = uniqid('map-');
        $this->slug = $slug;
        $this->size = $size;
    }

    public function mapboxApiToken(): string
    {
        return env('MABPOX_API_TOKEN');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.map-output');
    }
}
