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

    public function __construct()
    {
        $this->uniqueMapId = uniqid('map-');
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
