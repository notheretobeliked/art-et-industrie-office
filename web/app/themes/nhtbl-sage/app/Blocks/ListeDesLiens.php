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
            'intro' => get_field('has_intro') ? get_field('texte_intro') : '',
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
            ->addTrueFalse('has_intro', [
                'label' => 'Texte d\'intro',
                'instructions' => '',
                'default_value' => 0,
                'ui' => 1,
            ])
            ->addWysiwyg('texte_intro', [
                'label' => 'Texte d\'intro',
                'instructions' => 'Laisser vide pour ne pas avoir de texte d\'intro',
                'required' => 0,
                'conditional_logic' => [],
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 0,
                'delay' => 0,
            ])
            ->conditional('has_intro', '==', '1')

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
                'label' => __('Afficher image', 'sage'),
                'ui' => 1,
                'ui_on_text' => 'Oui',
                'ui_off_text' => 'Non',
            ])
            ->addTrueFalse('show_intro', [
                'label' => __('Afficher le début du texte', 'sage'),
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
        $contents = get_field('content');
        $showIntro = get_field('show_intro');
        $output = array();
        $language = pll_current_language();
        
        foreach ($contents as $content) {
            $content = pll_get_post($content, $language);
            $post_language = pll_get_post_language( $content );

            $image = \get_post_thumbnail_id($content);
            // get image in size medium_large from $image['id']
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
            $output[] = [
                'url' => \get_permalink($content),
                'language' => $post_language,
                'title' => \get_the_title($content),
                'introtext' => $showIntro ? \get_the_excerpt($content) : '',
                'image' => $image,
            ];
        }
        return $output;
    }

    public function showImage()
    {
        return get_field('show_image');
    }

    public function template()
    {
        $template = array(
            array('core/heading', array(
                'level' => 3,
                'placeholder' => __('Titre du bloc', 'sage'),
            ))
        );
        return esc_attr(wp_json_encode($template));
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
