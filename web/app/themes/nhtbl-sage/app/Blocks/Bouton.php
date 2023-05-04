<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Bouton extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Bouton';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Bouton block.';

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
            'url' => $this->url(),
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
        $bouton = new FieldsBuilder('bouton');

        $bouton
        ->addPageLink('link', [
            'label' => 'Lien vers page',
            'type' => 'page_link',
            'instructions' => '',
            'required' => 0,
            'multiple' => 0,
        ]);
        return $bouton->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function url()
    {
        return get_field('url');
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
    public function template() 
    {
        $template = array(
            array( 'core/paragraph', array(
                'fontSize' => 'lg',
                'placeholder' => __('Texte ici...', 'sage'),
                'align' => 'center'
            ) )
        );
        return esc_attr(wp_json_encode( $template ));
    }
}
