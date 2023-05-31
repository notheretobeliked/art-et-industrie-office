<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Evenements extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Evenements';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Evenements block.';

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
    public $icon = 'calendar';

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
     * The block styles.
     *
     * @var array
     */
    public $styles = [
        [
            'name' => 'light',
            'label' => 'Light',
            'isDefault' => true,
        ],
        [
            'name' => 'dark',
            'label' => 'Dark',
        ]
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
            'events' => $this->getEvents(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $evenements = new FieldsBuilder('evenements');

        $evenements
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

        ->addSelect('type', [
            'label' => 'Choix par',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => [],
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'choices' => ['manual' => 'Choix manuel', 'latest' => 'Toues les évenements'],
            'default_value' => [],
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
        ])
        ->addRelationship('content', [
            'post_type' => ['tribe_events'],
            'filters' => [
                0 => 'search',
                2 => 'taxonomy',
            ],
            'return_format' => 'value',        
        ])
        ->conditional('type', '==', 'manual')
        ->addNumber('nombre', [
            'label' => 'Nombre d\'évenements',
            'instructions' => 'Mettre \'-1\' pour tous les évenements',
            'required' => 1,
            'wrapper' => [
                'width' => '',
                'class' => '',
                'id' => '',
            ],
            'default_value' => '-1',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => '',
            'max' => '',
            'step' => '',
      ])
      ->conditional('type', '!=', 'manual');
    

        return $evenements->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function getEvents()
    {
        if (get_field('type') == 'manual') {
            $items = tribe_get_events([
                'posts_per_page' => -1,
                'start_date' => date('Y-m-d H:i:s'),
                'eventDisplay' => 'list',
                'post__in' => get_field('content')
            ]);
        } else {
            $items = tribe_get_events([
                'posts_per_page' => get_field('nombre'),
                'start_date' => date('Y-m-d H:i:s'),
                'eventDisplay' => 'list',
            ]);
        }

        $return = [];
        foreach ($items as $item) {
            $tags = [];
            if (get_the_terms($item->ID, 'post_tag')) {
                foreach (get_the_terms($item->ID, 'tribe_events_cat') as $cat) {
                    $categories[] = [
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                    ];
                }
            }
            $categories = [];
            if (get_the_terms($item->ID, 'tribe_events_cat')) {
                foreach (get_the_terms($item->ID, 'tribe_events_cat') as $cat) {
                    $categories[] = [
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                    ];
                }
            }

            $lieu = get_field('lieu_event', $item->ID);
            $lieu ? $lieu = [
                'title' => get_the_title($lieu[0]->ID),
                'permalink' => get_permalink($lieu[0]->ID),
            ] : $lieu = null;

            $start_date = tribe_get_start_date($item->ID, false, 'j/n/Y');
            if (tribe_get_start_date($item->ID, false, 'j F Y') != tribe_get_end_date($item->ID, false, 'j F Y')) {
                $end_date = tribe_get_end_date($item->ID, false, 'j/n/Y');
                $startMonth = (tribe_get_start_date($item->ID, false, '/n/'));
                if ($startMonth === tribe_get_end_date($item->ID, false, '/n/')) $startMonth = "";
                if (tribe_get_start_date($item->ID, false, 'Y') === tribe_get_end_date($item->ID, false, 'Y')) $start_date = (tribe_get_start_date($item->ID, false, 'j') . " " . $startMonth);
            } else $end_date = null;

            $return[] = [
                'lieu' => $lieu,
                'title' => get_the_title($item->ID),
                'permalink' => get_permalink($item->ID),
                'thumbnail' => get_the_post_thumbnail_url($item->ID, 'medium'),
                'date' => $start_date,
                'time' => tribe_event_is_all_day($item->ID) ? null : tribe_get_start_date($item->ID, false, 'H:i'),
                'end_date' => $end_date,
                'end_time' => tribe_event_is_all_day($item->ID) ? null : tribe_get_end_date($item->ID, false, 'H:i'),
                'excerpt' => get_the_excerpt($item->ID),
                'categories' => $categories,
                'tags' => $tags,
            ];
        }

        return $return;
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
