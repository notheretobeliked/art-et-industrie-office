<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;
use DateTime;

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
        'mode' => true,
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
            'has_filter' => get_field('has_filter'),
            'events' => $this->getEvents()["results"],
            'allcategories' => $this->getEvents()["categories"],
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
            ->addTrueFalse('has_filter', [
                'label' => 'Afficher filtrage',
                'instructions' => '',
                'default_value' => 0,
                'ui' => 1,
            ])
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
        $allCategories = [];

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

            $allCategories = array_merge($allCategories, array_map(function ($category) {
                return $category['name'];
            }, $categories));

            $lieu = get_field('lieu_event', $item->ID);
            $lieu ? $lieu = [
                'title' => get_the_title($lieu[0]),
                'permalink' => get_permalink($lieu[0]),
            ] : $lieu = null;

            $start_date = tribe_get_start_date($item->ID, false, 'j/n/Y');
            if (tribe_get_start_date($item->ID, false, 'j F Y') != tribe_get_end_date($item->ID, false, 'j F Y')) {
                $end_date = tribe_get_end_date($item->ID, false, 'j/n/Y');
                $startMonth = (tribe_get_start_date($item->ID, false, '/n/'));
                if ($startMonth === tribe_get_end_date($item->ID, false, '/n/')) $startMonth = "";
                if (tribe_get_start_date($item->ID, false, 'Y') === tribe_get_end_date($item->ID, false, 'Y')) $start_date = (tribe_get_start_date($item->ID, false, 'j') . " " . $startMonth);
            } else $end_date = null;

            $image = \get_post_thumbnail_id($item->ID);
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

            $filtertags = [];

            // Populate $filtertags with list of categories.
            $filtertags = array_map(function ($category) {
                return $category['name'];
            }, $categories);

            // Check if the event is taking place today, this month or in the future

            $start_date_str = tribe_get_start_date($item->ID, false, 'Y-m-d');
            $start_date_date = DateTime::createFromFormat('Y-m-d', $start_date_str);
            $today = new DateTime();

            if ($start_date_date->format('Y-m-d') >= $today->format('Y-m-d')) {
                $filtertags[] = 'future';
            }

            if ($start_date_date->format('Y-m-d') == $today->format('Y-m-d')) {
                $filtertags[] = 'today';
            }

            if ($start_date_date->format('Y-m') == $today->format('Y-m') && $start_date_date->format('Y-m-d') >= $today->format('Y-m-d')) {
                $filtertags[] = 'this-month';
            }


            $return[] = [
                'lieu' => $lieu,
                'title' => get_the_title($item->ID),
                'permalink' => get_permalink($item->ID),
                'thumbnail' => $image,
                'filtertags' => $filtertags,
                'date' => $start_date,
                'time' => tribe_event_is_all_day($item->ID) ? null : tribe_get_start_date($item->ID, false, 'H:i'),
                'start_date' => tribe_get_start_date($item->ID, false, 'j/n/Y'),
                'end_date' => $end_date ? $end_date : tribe_get_start_date($item->ID, false, 'j/n/Y'),
                'end_time' => tribe_event_is_all_day($item->ID) ? null : tribe_get_end_date($item->ID, false, 'H:i'),
                'excerpt' => get_the_excerpt($item->ID),
                'categories' => $categories,
                'tags' => $tags,
            ];
        }

        $allCategories = array_unique($allCategories);


        return ['results' => $return, 'categories' => $allCategories];
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
