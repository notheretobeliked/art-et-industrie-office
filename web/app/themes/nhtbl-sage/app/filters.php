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
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

add_filter('jpeg_quality', function ($arg) {
    return 50;
});

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
        $destination_path_base = pathinfo($source_path, PATHINFO_DIRNAME) . '/';

        // Generate the image variants
        generate_image_variants($attachment_id, $source_path, $destination_path_base);
    }
});

add_action('delete_attachment', function ($attachment_id) {
    // Get the image variants custom field for the attachment post
    $attachment = get_post($attachment_id);
    if (strpos($attachment->post_mime_type, 'image') === 0) {

        $variants = get_post_meta($attachment_id, 'image_variants', true);

        if ($variants) {
            $upload_dir = wp_upload_dir();
            $path = $upload_dir['basedir'] . get_post_meta($attachment_id, 'subdir', true) . '/';
            
            // Delete the AVIF and WebP images from disk
            foreach ($variants as $size) {
                
                foreach ($size as $variant_type => $variant_path) {
                    $variant_path = $path . $variant_path;
                    error_log($variant_path);
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
    error_log(print_r($level, true));

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
	if ( !empty($gallery) &! has_post_thumbnail($post_id) ) {
		$image_id = $gallery[0];
        error_log($image_id);
		set_post_thumbnail($post_id, $image_id);
	}
});

// filter default fields for events
add_filter( 'tribe_events_editor_default_template', function( $template ) {
    $template = [
      [ 'tribe/event-datetime' ],
      [ 'core/paragraph', [
        'placeholder' => __( 'Description ici...', 'sage' ),
      ], ],
    ];
    return $template;
  }, 11, 1 );


// only allow default blocks

add_filter( 'allowed_block_types_all', function ( $allowed_blocks, $editor_context ) {
	$allowed_blocks = array(
		'core/image',
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
        'acf/galerie',
        'acf/texte-intro',
	);
	return $allowed_blocks;
 
}, 25, 2 );


/* add Gutenberg blocks to rest api AND insert custom fields into output

ok so this fucks up the entire editor so let's not use it for now

add_action(
    'rest_api_init',
    function () {

        if (!function_exists('use_block_editor_for_post_type')) {
            require ABSPATH . 'wp-admin/includes/post.php';
        }

        // Surface all Gutenberg blocks in the WordPress REST API
        $post_types = get_post_types_by_support(['editor']);
        foreach ($post_types as $post_type) {
            if (use_block_editor_for_post_type($post_type)) {
                register_rest_field(
                    $post_type,
                    'blocks',
                    [
                        'get_callback' => function (array $post) {
                            $blocks = parse_blocks($post['content']['raw']);
                            $newblocks = $blocks;
                            foreach ($newblocks as &$block) {

                                // Test block name
                                if ('acf/galerie' === $block['blockName']) {
                                    error_log(print_r($block, true));
                                    //                                    echo apply_filters( 'the_content', render_block( $block ) );
                                    error_log(print_r($block['attrs']['data']['gallery_test_field'], true));
                                    $images = array();
                                    foreach ($block['attrs']['data']['gallery_test_field'] as $imageId) {
                                        // get srcset for $imageId
                                        $images[] = wp_get_attachment_image_srcset($imageId, 'full');
                                    }
                                    error_log(print_r($images, true));
                                    $block['attrs']['data']['gallery_test_field'] = $images;
                                }
                            }

                            return $newblocks;
                        },
                    ]
                );
            }
        }
    }
);
*/