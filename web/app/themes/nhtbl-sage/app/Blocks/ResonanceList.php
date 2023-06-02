<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ResonanceList extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Resonance List';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Une grille de tous les lieux resonance.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'formatting';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'category';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => false,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'items' => $this->items(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $resonanceList = new FieldsBuilder('resonance_list');



        return $resonanceList->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        $posts = get_posts(array(
            'post_type' => 'lieu',
            'status' => 'publish',
            'meta_key'      => 'type',
            'meta_value'    => 'resonance',
            'numberposts' => -1,
          ));
      
          foreach ($posts as $post) {
            $coordinates = array(
              'longitude' => get_field('longitude', $post->ID),
              'latitude' => get_field('latitude', $post->ID),
            );

            $image = \get_post_thumbnail_id($post->ID);

            if ($image) {
              $subdir = get_post_meta($image, 'subdir', true);
              $other_formats = get_post_meta($image, 'image_variants', true);
              $alt = get_post_meta($image, '_wp_attachment_image_alt', TRUE);
              $image = array(
                'id' => $image,
                'src' => wp_get_attachment_image_src($image, 'medium_large'),
                'width' => wp_get_attachment_metadata($image)['width'],
                'height' => wp_get_attachment_metadata($image)['height'],
                'srcset' => wp_get_attachment_image_srcset($image, 'medium_large'),
                'image' => wp_get_attachment_image($image, 'medium_large'),
                'alt' => $alt,
                'other_formats' => $other_formats,
                'subdir' => $subdir,
                'caption' => wp_get_attachment_caption($image) ? '<figcaption>' . wp_get_attachment_caption($image) . '</figcaption>' : '',
              );
            }
      
            $lieux[] = array(
              'type' => 'Feature',
              'image' => $image,
              'properties' => array(
                'title' => $post->post_title,
                'description' => get_the_excerpt($post),
                'category' => array(
                  'slug' => get_field('type', $post->ID)["value"] ? get_field('type', $post->ID)["value"] :  'wee',
                  'name' => get_field('type', $post->ID)["label"] ? get_field('type', $post->ID)["value"] :  'wee',
                )
              ),
              'geometry' => array(
                'coordinates' => array_values($coordinates),
                'type' => 'Point',
              ),
            );
          }
      
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function enqueue()
    {
        //
    }
}
