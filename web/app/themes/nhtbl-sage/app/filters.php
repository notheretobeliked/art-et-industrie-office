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

add_filter('image_editor_output_formats', function () {
    return array('image/webp', 'image/aiff');
});

add_filter('wp_editor_set_quality', function ($quality, $mime_type) {
    if ('image/webp' === $mime_type) {
        return 1;
    }
    return $quality;
}, 10, 2);

add_filter('wp_generate_attachment_metadata', 'rb_bw_filter');

function rb_bw_filter($meta)
{

    $path = wp_upload_dir(); // get upload directory
    $file = $path['basedir'] . '/' . $meta['file']; // Get full size image

    $files[] = $file; // Set up an array of image size urls

    foreach ($meta['sizes'] as $size) {
        $files[] = $path['path'] . '/' . $size['file'];
    }

    foreach ($files as $file) { // iterate through each image size

        // Convert image to grayscale credit to http://ottopress.com/2011/customizing-wordpress-images/

        list($orig_w, $orig_h, $orig_type) = @getimagesize($file);
        $image = wp_get_image_editor($file);
        imagefilter($image, IMG_FILTER_GRAYSCALE);
        switch ($orig_type) {
            case IMAGETYPE_GIF:
                imagegif($image, $file);
                break;
            case IMAGETYPE_PNG:
                imagepng($image, $file);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($image, $file);
                break;
        }
    }
    return $meta;
}
