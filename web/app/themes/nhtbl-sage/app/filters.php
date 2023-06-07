<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */

add_filter('jpeg_quality', function ($arg) {
    return 50;
});


add_action( 'wp_enqueue_scripts', function() {

    //this is based on using the "skeleton styles" option
    $styles = [
        'tribe-events-bootstrap-datepicker-css',
        'tribe-events-calendar-style',
        'tribe-events-custom-jquery-styles',
        'tribe-events-calendar-style',
        'tribe-events-calendar-pro-style',
        'tribe-events-full-calendar-style-css',
        'tribe-common-skeleton-style-css',
        'tribe-tooltip',
        'tribe-accessibility-css'
    ];

    $scripts = [
       "tribe-common",
       "tribe-admin-url-fragment-scroll",
       "tribe-buttonset",
       "tribe-dependency",
       "tribe-pue-notices",
       "tribe-validation",
       "tribe-timepicker",
       "tribe-jquery-timepicker",
       "tribe-dropdowns",
       "tribe-attrchange",
       "tribe-bumpdown",
       "tribe-datatables",
       "tribe-migrate-legacy-settings",
       "tribe-admin-help-page",
       "tribe-tooltip-js",
       "mt-a11y-dialog",
       "tribe-dialog-js",
       "tribe-moment",
       "tribe-tooltipster",
       "tribe-events-settings",
       "tribe-events-php-date-formatter",
       "tribe-events-jquery-resize",
       "tribe-events-chosen-jquery",
       "tribe-events-bootstrap-datepicker",
       "tribe-events-ecp-plugins",
       "tribe-events-editor",
       "tribe-events-dynamic",
       "jquery-placeholder",
       "tribe-events-calendar-script",
       "tribe-events-bar",
       "the-events-calendar",
       "tribe-events-ajax-day",
       "tribe-events-list",
       "tribe-query-string",
       "tribe-clipboard",
       "datatables",
       "tribe-select2",
       "tribe-utils-camelcase",
       "tribe-app-shop-js"
    ];

    wp_deregister_script($scripts);

    wp_deregister_style($styles);

}, 99);

add_filter('wp_editor_set_quality', function ($quality, $mime_type) {
    if ('image/webp' === $mime_type) {
        return 30;
    }
    return $quality;
}, 10, 2);

add_action('add_attachment', function ($attachment_id) {
    $attachment = get_post($attachment_id);
    if (strpos($attachment->post_mime_type, 'image') === 0) {

        // Get the file paths for the source and destination images
        $source_path = get_attached_file($attachment_id);
        //error_log('source path: ' . $source_path);
        $destination_path_base = pathinfo($source_path, PATHINFO_DIRNAME) . '/';

        //error_log('destination_path_base: ' . $destination_path_base);
        // Generate the image variants
        generate_image_variants($attachment_id, $source_path, $destination_path_base);
    }
});


add_filter( 'render_block', function ( $block_content, $block ) {
    // Check if the block is of type 'core/image'
    if ( 'core/image' === $block['blockName'] ) {
        error_log(print_r($block, true));
        // Extract the image URL and alt text from the block attributes
        $imageId = $block['attrs']['id'];
        $size = $block['attrs']['sizeSlug'];
        $image_alt = get_post_meta($imageId, '_wp_attachment_image_alt', TRUE);
        $subdir = get_post_meta($imageId, 'subdir', true);
        $other_formats = get_post_meta($imageId, 'image_variants', true);
        $output = array(
            'src' => wp_get_attachment_image_src($imageId, $size),
            'width' => wp_get_attachment_image_src($imageId, $size)[1],
            'height' => wp_get_attachment_image_src($imageId, $size)[2],
            'srcset' => wp_get_attachment_image_srcset($imageId, $size),
            'image' => wp_get_attachment_image($imageId, $size),
            'alt' => $image_alt,
            'other_formats' => $other_formats[$size],
            'subdir' => $subdir,
            'caption' => wp_get_attachment_caption($imageId) ? '<figcaption class="font-serif text-sm mt-2">' . wp_get_attachment_caption($imageId) . '</figcaption>' : '',
        );

        // Return the custom output instead of the original $block_content
        return \Roots\view('components.image-output', ['class' => 'w-full max-w-full', 'crop' => true, 'image' => $output, 'size' => $size, 'caption' => true])->render();    
    }

    // For other blocks, return the original $block_content
    return $block_content;
}, 10, 2 );

add_action('delete_attachment', function ($attachment_id) {
    // Get the image variants custom field for the attachment post
    $attachment = get_post($attachment_id);
    if ((strpos($attachment->post_mime_type, 'image')) === 0 && (pll_get_post_language( $attachment_id ) === 'fr')) {

        $variants = get_post_meta($attachment_id, 'image_variants', true);

        if ($variants) {
            $upload_dir = wp_upload_dir();
            $path = $upload_dir['basedir'] . get_post_meta($attachment_id, 'subdir', true) . '/';

            // Delete the AVIF and WebP images from disk
            foreach ($variants as $size) {

                foreach ($size as $variant_type => $variant_path) {
                    $variant_path = $path . $variant_path;
                    //error_log($variant_path);
                    if (file_exists($variant_path)) {
                        unlink($variant_path);
                    }
                }
            }
            // Delete the custom field from the attachment post
            delete_post_meta($attachment_id, 'image_variants');
            delete_post_meta($attachment_id, 'basedir');
        }
    }
});


add_filter('tribe_rest_single_event_data', function (array $event_data) {
    $event_id = $event_data['id'];

    $level = get_field('ressonance', $event_id);

    $event_data['resonances'] = $level;

    return $event_data;
});

add_filter('acf/settings/rest_api_format', function () {
    return 'standard';
});

add_filter('acf/settings/save_json', function ($path) {

    // update path
    $path = get_stylesheet_directory() . '/resources/acf-json';


    // return
    return $path;
});


add_action('admin_head', function () {
    echo '<style>#event_tribe_organizer, #event_url, #event_tribe_venue, #event_cost {display: none;}</style>';
});


// add first image from gallery field to thumbnail
add_filter('acf/save_post', function ($post_id) {
    $gallery = get_field('galerie', $post_id, false);
    if (!empty($gallery) & !has_post_thumbnail($post_id)) {
        $image_id = $gallery[0];
        set_post_thumbnail($post_id, $image_id);
    }
});

// filter default fields for events
add_filter('tribe_events_editor_default_template', function ($template) {
    $template = [
        ['tribe/event-datetime'],
        ['core/paragraph', [
            'placeholder' => __('Description ici...', 'sage'),
        ],],
    ];
    return $template;
}, 11, 1);


// only allow default blocks

// add_filter('allowed_block_types_all', function ($allowed_blocks, $editor_context) {
//     $allowed_blocks = array(
//         'core/image',
//         'core/paragraph',
//         'core/heading',
//         'core/heading',
//         'core/list',
//         'core/button',
//         'core/cover',
//         'core/list-item',
//         'acf/galerie',
//         'acf/texte-intro',
//         'acf/visuel-triennale',
//         'acf/liste-des-liens',
//         'acf/bouton',
//         'acf/evenements',
//     );
//     return $allowed_blocks;
// }, 25, 2);


/* 
 * Add custom post type to graphql
 * https://www.wpgraphql.com/docs/custom-post-types/
 */

add_filter('register_post_type_args', function ($args, $post_type) {
    // Let's make sure that we're customizing the post type we really need
    if ($post_type !== 'edition') {
        return $args;
    }

    // Now, we have access to the $args variable
    // If you want to modify just one label, you can do something like this
    $args['show_in_graphql'] = true;
    $args['graphql_single_name'] = 'Edition';
    $args['graphql_plural_name'] = 'Editions';
    $args['publicly_queryable'] = true;

    return $args;
}, 10, 2);

add_filter( 'big_image_size_threshold', '__return_false' );

add_filter('register_post_type_args', function ($args, $post_type) {
    // Let's make sure that we're customizing the post type we really need
    if ($post_type !== 'lieu') {
        return $args;
    }

    // Now, we have access to the $args variable
    // If you want to modify just one label, you can do something like this
    $args['show_in_graphql'] = true;
    $args['graphql_single_name'] = 'Lieu';
    $args['graphql_plural_name'] = 'Lieux';
    $args['publicly_queryable'] = true;

    return $args;
}, 10, 2);

add_filter('register_taxonomy_args', function ($args, $taxonomy) {

    if ('lieu_type' === $taxonomy) {
        $args['show_in_graphql'] = true;
        $args['graphql_single_name'] = 'LieuType';
        $args['graphql_plural_name'] = 'LieuTypes';
    }

    return $args;
}, 10, 2);


/* 
 * Set relationship fields as biderectional
 * https://www.advancedcustomfields.com/resources/bidirectional-relationships/
 * 
 */
function bidirectional_acf_update_value($value, $post_id, $field)
{

    // vars
    $field_name = $field['name'];
    $field_key = $field['key'];
    $global_name = 'is_updating_' . $field_name;


    // bail early if this filter was triggered from the update_field() function called within the loop below
    // - this prevents an inifinte loop
    if (!empty($GLOBALS[$global_name])) return $value;


    // set global variable to avoid inifite loop
    // - could also remove_filter() then add_filter() again, but this is simpler
    $GLOBALS[$global_name] = 1;


    // loop over selected posts and add this $post_id
    if (is_array($value)) {

        foreach ($value as $post_id2) {

            // load existing related posts
            $value2 = get_field($field_name, $post_id2, false);


            // allow for selected posts to not contain a value
            if (empty($value2)) {

                $value2 = array();
            }


            // bail early if the current $post_id is already found in selected post's $value2
            if (in_array($post_id, $value2)) continue;


            // append the current $post_id to the selected post's 'related_posts' value
            $value2[] = $post_id;


            // update the selected post's value (use field's key for performance)
            update_field($field_key, $value2, $post_id2);
        }
    }


    // find posts which have been removed
    $old_value = get_field($field_name, $post_id, false);

    if (is_array($old_value)) {

        foreach ($old_value as $post_id2) {

            // bail early if this value has not been removed
            if (is_array($value) && in_array($post_id2, $value)) continue;


            // load existing related posts
            $value2 = get_field($field_name, $post_id2, false);


            // bail early if no value
            if (empty($value2)) continue;


            // find the position of $post_id within $value2 so we can remove it
            $pos = array_search($post_id, $value2);


            // remove
            unset($value2[$pos]);


            // update the un-selected post's value (use field's key for performance)
            update_field($field_key, $value2, $post_id2);
        }
    }


    // reset global varibale to allow this filter to function as per normal
    $GLOBALS[$global_name] = 0;


    // return
    return $value;
}

/* 
 * Make sure all relationship fields are biderctional
 * https://www.advancedcustomfields.com/resources/bidirectional-relationships/
 * 
 */
add_filter('acf/update_value/name=lieu_event', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=event_artiste', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=event_edition', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=artiste_edition', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=artiste_lieu', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=edition_lieu', 'App\bidirectional_acf_update_value', 10, 3);




add_action('graphql_register_types', function () {
    register_graphql_field('Block', 'attachedPages', [
        'type' => ['list_of' => 'ACFListeDesLiensPage'],
        'resolve' => function ($block, $args, $context, $info) {
            if ($block['name'] === 'acf/liste-des-liens') {
                $content = $block['attributes']['data']['content'];
                return array_map(function ($pageId) use ($context, $info) {
                    $link = get_the_permalink($pageId);
                    $title = get_the_title($pageId);
                    $image = get_post_thumbnail_id($pageId);

                    if ($image) {
                        $image = \WPGraphQL\Data\DataSource::resolve_post_object(
                            $image,
                            $context,
                            $info,
                            'ATTACHMENT'
                        );
                    }
                    return compact('link', 'image', 'title');
                }, $content);
            }

            return null;
        },
    ]);

    register_graphql_field('Block', 'attachedImages', [
        'type' => ['list_of' => 'AcfGalerieBlockImages'],
        'resolve' => function ($block, $args, $context, $info) {
            if ($block['name'] === 'acf/galerie') {
                $content = $block['attributes']['data']['galerie'];

                return array_map(function ($content) use ($context, $info) {
                    $image = $content;
                    $image = \WPGraphQL\Data\DataSource::resolve_post_object(
                        $content,
                        $context,
                        $info,
                        'ATTACHMENT'
                    );
                    return compact('image');
                }, $content);
            }
            return null;
        },
    ]);
});

add_action('graphql_register_types', function () {
    register_graphql_object_type('ACFListeDesLiensPage', [
        'fields' => [
            'link' => ['type' => 'String'],
            'title' => ['type' => 'String'],
            'image' => [
                'type' => 'mediaItem'
              ],
              
        ],
    ]);
    register_graphql_object_type('AcfGalerieBlockImages', [
        'fields' => [
            'image' => [
                'type' => 'mediaItem'
              ],
              
        ],
    ]);
});


/*
 * Add new fields to mediaItem type to accomodate for different image compression levels
 * 
 * 
*/

add_action('graphql_register_types', function () {
    // Add new fields to mediaItem type
    register_graphql_fields('mediaSize', array(
        'sourceUrlLow' => array(
            'type' => 'String',
            'resolve' => function ($image) {
                $src_url = null;

                if ( ! empty( $image['ID'] ) ) {
                    $src = wp_get_attachment_image_src( absint( $image['ID'] ), $image['name'] );
                    if ( ! empty( $src ) ) {
                        $src_url = $src[0];
                    }
                } elseif ( ! empty( $image['file'] ) ) {
                    $src_url = $image['file'];
                }

                return $src_url . '-low.webp';
             }
        ),

        'sourceUrlBW' => array(
            'type' => 'String',
            'resolve' => function ($image) {
                $src_url = null;

                if ( ! empty( $image['ID'] ) ) {
                    $src = wp_get_attachment_image_src( absint( $image['ID'] ), $image['name'] );
                    if ( ! empty( $src ) ) {
                        $src_url = $src[0];
                    }
                } elseif ( ! empty( $image['file'] ) ) {
                    $src_url = $image['file'];
                }

                return $src_url . '-bw.webp';
             }
        ),

    ));
});


/* add a new 'clean_data' field to the REST API
*/
// add_action(
//     'rest_api_init',
//     function () {

//         if (!function_exists('use_block_editor_for_post_type')) {
//             require ABSPATH . 'wp-admin/includes/post.php';
//         }

//         // Surface all Gutenberg blocks in the WordPress REST API
//         $post_types = get_post_types_by_support(['editor']);
//         foreach ($post_types as $post_type) {
//             if (use_block_editor_for_post_type($post_type)) {
//                 register_rest_field(
//                     $post_type,
//                     'clean_data',
//                     [
//                         'get_callback' => function (array $post) {
//                             $blocks = parse_blocks($post['content']['raw']);
//                             $newblocks = $blocks;
//                             foreach ($newblocks as &$block) {
//                                 if ('acf/galerie' === $block['blockName']) {
//                                     /* 
//                                     If the block is an acf gallery, add a filed called 'rendered_gallery' which contains the actual
//                                     image srcset for each image in the gallery
//                                     */
//                                     $images = array();
//                                     foreach ($block['attrs']['data']['galerie'] as $imageId) {
//                                         // get srcset for $imageId
//                                         $images[] = wp_get_attachment_image_srcset($imageId, 'medium-large');
//                                     }
//                                     error_log(print_r($images, true));
//                                     $block['attrs']['data']['rendered_gallery'] = $images;
//                                 }  elseif ('acf/liste-des-liens' === $block['blockName']) {
//                                     /* 
//                                     If the block is a list-des-liens block, add a field called 'attached_posts' which contains the metadata
//                                     for each of the posts in the block
//                                     */
//                                     error_log(print_r($block, true));
//                                     //  echo apply_filters( 'the_content', render_block( $block ) );
//                                     error_log(print_r($block['attrs']['data']['galerie'], true));
//                                     $images = array();
//                                     foreach ($block['attrs']['data']['content'] as $contentId) {
//                                         // get srcset for $imageId
//                                         $content[] = [
//                                             'title' => get_the_title($contentId),
//                                             'link' => get_the_permalink($contentId),
//                                             'thumbnail' => wp_get_attachment_image_srcset($contentId, 'medium-large'),
//                                         ];
//                                     }
//                                     error_log(print_r($content, true));
//                                     $block['attrs']['data']['attached_posts'] = $content;
//                                 }

//                             }

//                             return $newblocks;
//                         },
//                     ]
//                 );
//             }
//         }
//     }
// );
