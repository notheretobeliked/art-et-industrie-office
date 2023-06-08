<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use function Roots\bundle;

class Carte extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Carte';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Carte Triennale.';

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
            'slug' => $this->returnSlug(),
            'type' => $this->returnType()
        ];
    }

    public function returnType()
    {
        return get_field("carte_type") != 'svg' ? 'mapbox' : 'svg';
    }


    public function returnSlug()
    {
        return get_field("choix_par") == 'cat' ? get_field("type") : get_field("single_lieu")[0]->post_name;
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $carte = new FieldsBuilder('carte');

        $carte
        ->addRadio('carte_type', [
            'label' => 'Type de carte',
            'choices' => ['mapbox' => 'Carte interactive', 'svg' => 'Carte des œuvres publiques'],
            'default_value' => ['mapbox'],
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
        ])
        ->addRadio('choix_par', [
            'label' => 'Catégorie',
            'choices' => ['cat' => 'Catégorie', 'lieu' => 'Lieu specifique'],
            'default_value' => ['cat'],
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
        ])
        ->conditional('carte_type', '!=', 'svg')
            ->addRadio('type', [
                'label' => 'Catégorie',
                'choices' => ['all' => 'Tous les lieux', 'triennale' => 'Lieux triennale', 'resonance' => 'Lieux résonance', 'oeuvre-public' => 'Œuvres publiques'],
                'default_value' => ['all'],
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'ajax' => 0,
                'return_format' => 'value',
                'placeholder' => '',
            ])
            ->conditional('choix_par', '==', 'cat')
            ->addRelationship('single_lieu', [
                'label' => 'Lien vers page',
                'post_type' => ['lieu'],
                'instructions' => '',
                'filters' => [
                    0 => 'search',
                ],
                'min' => '1',
                'max' => '1',
                'return_format' => 'object',
            ])
            ->conditional('choix_par', '==', 'lieu');


        return $carte->build();
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function enqueue()
    {
        wp_enqueue_style('mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css');
        wp_enqueue_script('mapbox', 'https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js');
    }
}
