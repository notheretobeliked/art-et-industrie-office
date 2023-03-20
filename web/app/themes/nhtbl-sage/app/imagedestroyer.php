<?php

/**
 * fuck with images.
 */

namespace App;

function add_image_variant_custom_field($attachment_id, $suffix, $variants) {
  // Get the current value of the custom field
  $existing_variants = get_post_meta($attachment_id, 'image_variants', true);

  // If there are no existing variants, create an empty array
  if (!is_array($existing_variants)) {
      $existing_variants = [];
  }

  // Add the new variant to the existing variants
  $existing_variants[$suffix] = $variants;

  //error_log(print_r($existing_variants, true));
  // Update the custom field value for this attachment
  update_post_meta($attachment_id, 'image_variants', $existing_variants);
}


function generate_image_variants($attachment_id, $source_path, $destination_path_base) {
  // Set the desired image qualities (0-100)
  $quality_low = 2;

  $sizes = wp_get_registered_image_subsizes();

  //get an array from $sizes that maps keys to values

  $variants = [];
  // Set the maximum dimensions for each variant
  foreach ($sizes as $size=>$sizes) {
    $variants[$size] = $sizes;
  }

  $variants = wp_get_image_editor($source_path)->multi_resize($variants);
  
  
  // Create a new Imagick object from the source image
  $image = new \Imagick($source_path);

  
  // Loop through each variant and create AVIF and WebP versions
  foreach ($variants as $suffix => $size_info) {
      // Clone the image to work with a fresh copy each time
      error_log(print_r($size_info, true));
      
      $maxwidth = $size_info['width'];
      $maxheight = $size_info['height'];
      $file = $size_info['file'];

      $resized_image = clone $image;

      // Resize the image if necessary
      $resized_image->scaleImage($maxwidth, $maxheight, true);
      $resized_image-> posterizeImage(4, true);
      $resized_image-> stripImage();


      // Save the high-quality AVIF version of the image
      $destination_path_avif = $destination_path_base . $file . '.avif';
      $resized_image->setImageFormat('avif');
      $resized_image->setImageCompressionQuality($quality_low);
      $resized_image->writeImage($destination_path_avif);
      
      // Save the low-quality WebP version of the image
      $destination_path_webp = $destination_path_base . $file . '.webp';
      $resized_image->setImageFormat('webp');
      $resized_image->setImageCompressionQuality($quality_low);
      $resized_image->writeImage($destination_path_webp);

      // Add the variants to the custom field for this attachment
      $variants = [
        'avif' => $destination_path_avif,
        'webp' => $destination_path_webp,
      ];
      add_image_variant_custom_field($attachment_id, $suffix, $variants);

      // Destroy the resized image object to free up memory
      $resized_image->destroy();
  }
  
  // Destroy the original image object to free up memory
  $image->destroy();
}