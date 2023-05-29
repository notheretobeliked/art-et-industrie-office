<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageOutput extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $image;
    public $size = 'medium_large';

    public function __construct($image, $size = 'medium_large')
    {
        $this->image = $this->transformImage($image, $size);
    }

    public function transformImage($image, $size)
    {
        $imageId = $image['id'] ? $image['id'] : $image;
        $image_alt = get_post_meta($imageId, '_wp_attachment_image_alt', TRUE);
        $subdir = get_post_meta($imageId, 'subdir', true);
        $other_formats = get_post_meta($imageId, 'image_variants', true);
        $output = array(
            'src' => wp_get_attachment_image_src($imageId, $size),
            'srcset' => wp_get_attachment_image_srcset($imageId, $size),
            'image' => wp_get_attachment_image($imageId, $size),
            'alt' => $image_alt,
            'other_formats' => $other_formats[$size],
            'subdir' => $subdir,
            'caption' => wp_get_attachment_caption($imageId) ? '<figcaption>' . wp_get_attachment_caption($imageId) . '</figcaption>' : '',
        );

        return $output;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.image-output');
    }
}
