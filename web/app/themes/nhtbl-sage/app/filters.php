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
    return 10;
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

        error_log(print_r($variants, true));
     
        if ($variants) {
            // Delete the AVIF and WebP images from disk
            foreach ($variants as $size) {
                foreach ($size as $variant_type => $variant_path) {
                    error_log($variant_type);
                        if (file_exists($variant_path)) {
                            unlink($variant_path);
                        }
                }
            }
        }
    }
    // Delete the custom field from the attachment post
    delete_post_meta($attachment_id, 'image_variants');
});

add_action( 'graphql_register_types', function () {
    register_graphql_object_type( 'LoresMedia', [
      'description' => __( "A list of media by size", 'sage' ),
      'fields' => [
        'small' => [
            'type' => 'String',
            'description' => __( 'Small image size filename', 'sage' ),
        ],
        'medium' => [
            'type' => 'String',
            'description' => __( 'Medium image size filename', 'sage' ),
        ],
        'large' => [
            'type' => 'String',
            'description' => __( 'Large image size filename', 'sage' ),
        ],
      ],
    ] );
});

add_action( 'graphql_register_types', function () {
    
    register_graphql_field( 'ContentNode', 'image_variants', [
        'type' => 'LoresMedia',
        'description' => __( 'Image Variants', 'sage' ),
        'resolve' => function( $post ) {
            return [
                "small" => "smallone",
                "medium" => "mediumone",
                "large" => "largetwo",
            ];
//            return json_encode(get_post_meta( $post->ID, 'image_variants', true ));
        },
    ] );
} );
