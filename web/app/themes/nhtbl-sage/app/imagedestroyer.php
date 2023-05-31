<?php

namespace App;

function add_image_variant_custom_field($attachment_id, $suffix, $variants)
{
    $existing_variants = get_post_meta($attachment_id, 'image_variants', true);
    if (!is_array($existing_variants)) {
        $existing_variants = [];
    }
    $existing_variants[$suffix] = $variants;
    $directory = wp_upload_dir();

    update_post_meta($attachment_id, 'image_variants', $existing_variants);
    update_post_meta($attachment_id, 'subdir', $directory['subdir']);
}

function generate_image_variants($attachment_id, $source_path, $destination_path_base)
{
    if ($source_path) {
        $quality_low = 1;
        $quality_medium = 20;

        $sizes = wp_get_registered_image_subsizes();
        $variants = array_map(fn ($size) => $size, $sizes);

        $original_file_name = pathinfo($source_path, PATHINFO_FILENAME);

        if ($source_path) $image = new \Imagick($source_path);

        foreach ($variants as $suffix => $size_info) {

            $maxwidth = is_numeric($size_info['width']) && $size_info['width'] > 0 ? $size_info['width'] : 1;
            $maxheight = is_numeric($size_info['height']) && $size_info['height'] > 0 ? $size_info['height'] : 1;
            if ($source_path) $file = $source_path;

            //      error_log(print_r($size_info, true));

            $resized_image = clone $image;
            $resized_image->scaleImage($maxwidth, $maxheight, true);
            $resized_image->stripImage();

            $file = $original_file_name . '-' . $suffix;

            $variants = process_image_variants($resized_image, $file, $destination_path_base, $quality_low, $quality_medium);
            add_image_variant_custom_field($attachment_id, $suffix, $variants);
            $resized_image->destroy();
        }

        $image->destroy();
    }
}

function process_image_variant($image, $file, $destination_path_base, $quality, $format, $suffix = '', $type = null, $posterize = null)
{
    $resized_image = clone $image;

    if ($type !== null) {
        $resized_image->setImageType($type);
    }

    if ($posterize !== null) {
        $resized_image->posterizeImage($posterize, true);
    }

    $resized_image->setImageFormat($format);
    $resized_image->setImageCompressionQuality($quality);

    $filename = $file . $suffix . '.' . $format;
    $destination_path = $destination_path_base . $filename;
    $resized_image->writeImage($destination_path);
    $resized_image->destroy();

    return $filename;
}

function process_image_variants($image, $file, $destination_path_base, $quality_low, $quality_medium)
{
    $variants = [];

    $variants['webp'] = process_image_variant($image, $file, $destination_path_base, $quality_medium, 'webp');
    $variants['webp-low'] = process_image_variant($image, $file, $destination_path_base, $quality_low, 'webp', '-low', null, 48);
    $variants['webp-bw'] = process_image_variant($image, $file, $destination_path_base, $quality_low, 'webp', '-bw', 2, 8);

    return $variants;
}
