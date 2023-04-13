<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class TexteIntro extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Texte Intro';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Un grand texte.';

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
    public $icon = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" width="36" height="36" aria-hidden="true" focusable="false"><path d="M4 5.417h2.267V12h1.466V5.417H10V4H4v1.417ZM20 16H4v-1.5h16V16Zm-7 4H4v-1.5h9V20Z"></path></svg>';

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
    public $align = 'wide';

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
     * The block styles.
     *
     * @var array
     */

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'items' => $this->items(),
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
        $texteIntro = new FieldsBuilder('texte_intro');

        return $texteIntro->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        
    }

    public function template() 
    {
        $template = array(
            array( 'core/paragraph', array(
                'fontSize' => 'lg',
                'placeholder' => __('Texte ici...', 'sage'),
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
