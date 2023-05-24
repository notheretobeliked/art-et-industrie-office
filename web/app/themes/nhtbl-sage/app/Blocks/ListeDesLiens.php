<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class ListeDesLiens extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Liste Des Liens';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Liste Des Liens block.';

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
            'title' => get_field('has_title') ? get_field('title') : '',
            'content' => $this->content(),
            'showImage' => $this->showImage(),
            'template' => $this->template(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $listeDesLiens = new FieldsBuilder('liste_des_liens');

        $listeDesLiens
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

            ->addRelationship('content', [
                'post_type' => [],
                'taxonomy' => [],
                'filters' => [
                    0 => 'search',
                    1 => 'post_type',
                    2 => 'taxonomy',
                ],
                'elements' => '',
                'min' => '',
                'max' => '',
                'return_format' => 'value',        
            ])
            ->addTrueFalse('show_image', [
                'label' => __('Afficher image','sage'),
                'ui' => 1,
                'ui_on_text' => 'Oui',
                'ui_off_text' => 'Non',
            ]);
        


        return $listeDesLiens->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function content()
    {
        if (! get_field('content')) return null;
        $args = [
            'post__in' => get_field('content'),
            'post_type' => get_post_types(),
            'post_status' => 'all',
            'numberposts' => -1,
        ];
            $items = get_posts($args);
            return $items;
    }

    public function showImage()
    {
        return get_field('show_image');
    }

    public function template() 
    {
        $template = array(
            array( 'core/heading', array(
                'level' => 3,
                'placeholder' => __('Titre du bloc','sage'),
            ) )
        );
        return esc_attr(wp_json_encode( $template ));
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
