<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Artistes extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Artistes';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Artistes block.';

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
    public $icon = 'editor-ul';

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
            'title' => get_field('has_title') ? get_field('title') : '',
            'mode' => get_field('manual_choice') ? 'manual' : 'all',
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $artistes = new FieldsBuilder('artistes');

        $artistes
            ->addTrueFalse('has_title', [
                'label' => 'Titre',
                'instructions' => '',
                'default_value' => 0,
                'ui' => 1,
            ])
            ->addText('title', [
                'label' => 'Titre',
                'instructions' => '',
                'allow_null' => 1,
            ])
            ->conditional('has_title', '==', '1')
            ->addTrueFalse('manual_choice', [
                'label' => __('Choix manuel', 'sage'),
                'ui' => 1,
                'ui_on_text' => 'Oui',
                'ui_off_text' => 'Non',
            ])
            ->addRelationship('artistes', [
                'post_type' => ['artistes'],
                'taxonomy' => [],
                'filters' => [
                    0 => 'search',
                ],
                'elements' => '',
                'min' => '',
                'max' => '',
                'return_format' => 'value',
            ])
            ->conditional('manual_choice', '==', '1');

        return $artistes->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        get_field('manual_choice') && get_field('artistes') ? $artistes = get_field('artistes') : $artistes = [];
        $args = [
            'post__in' => $artistes,
            'post_type' => 'artistes',
            'post_status' => 'publish',
            'numberposts' => -1,
            'order'             => 'ASC',
            'meta_key' => 'nom_de_famille',
            'orderby' => 'meta_value',
        ];
        $items = get_posts($args);
        $output = [];
        foreach ($items as $item) {
            $firstLetter = strtoupper(substr(get_field('nom_de_famille', $item->ID), 0, 1));
            if (!get_field("manual_choice")) {
                $output[$firstLetter][] = [
                    'id' => $item->ID,
                    'title' => $item->post_title,
                    'firstLetterofTitle' => $firstLetter,
                    'permalink' => get_permalink($item->ID),
                ];
            } else {
                $output[] = [
                    'id' => $item->ID,
                    'title' => $item->post_title,
                    'firstLetterofTitle' => $firstLetter,
                    'permalink' => get_permalink($item->ID),
                ];
            }
        }
        return $output;
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
