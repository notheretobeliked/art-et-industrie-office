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
  $directory = wp_upload_dir();
  

  //error_log(print_r($existing_variants, true));
  // Update the custom field value for this attachment
  update_post_meta($attachment_id, 'image_variants', $existing_variants);
  update_post_meta($attachment_id, 'subdir', $directory['subdir']);
}


function generate_image_variants($attachment_id, $source_path, $destination_path_base) {
  // Set the desired image qualities (0-100)
  $quality_low = 1;
  $quality_medium = 20;

  $sizes = wp_get_registered_image_subsizes();

  //get an array from $sizes that maps keys to values

  $variants = [];
  // Set the maximum dimensions for each variant
  foreach ($sizes as $size=>$sizes) {
    $variants[$size] = $sizes;
  }

  error_log(print_r($variants, true));

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
      $resized_image-> stripImage();


      // Save the high-quality AVIF version of the image
      $filename_avif = $file . '.avif';
      $destination_path_avif = $destination_path_base . $filename_avif;
      

      $resized_image_avif = clone $resized_image;
      $resized_image_avif->setImageFormat('avif');
      $resized_image_avif->setImageCompressionQuality($quality_low);
      $resized_image_avif->writeImage($destination_path_avif);
      $resized_image_avif->destroy();

      $resized_image_lowquality = clone $resized_image;
      $resized_image_lowquality->setImageFormat('avif');
      $resized_image_lowquality->posterizeImage(48, true);
      $resized_image_lowquality->setImageCompressionQuality($quality_low);
      $filename_aviflowquality = $file . '-low.avif';
      $destination_path_aviflowquality = $destination_path_base . $filename_aviflowquality;
      $resized_image_lowquality->writeImage($destination_path_aviflowquality);
      $resized_image_lowquality->destroy();

      $resized_image_bw = clone $resized_image;
      $resized_image_bw->setImageFormat('avif');
      $resized_image_bw->setImageType(2);
      $resized_image_bw->posterizeImage(8, true);
      $filename_avif_bw = $file . '-bw.avif';
      $destination_path_avif_bw = $destination_path_base . $filename_avif_bw;
      $resized_image_bw->setImageCompressionQuality($quality_low);
      $resized_image_bw->writeImage($destination_path_avif_bw);
      $resized_image_bw->destroy();
     
      
      // Save the medium-quality WebP version of the image
      $filename_webp = $file . '.webp';
      $destination_path_webp = $destination_path_base . $filename_webp;
      

      $resized_image->setImageFormat('webp');

      $resized_image_lowquality = clone $resized_image;
      $resized_image_lowquality->posterizeImage(48, true);
      $filename_webp_lowquality = $file . '-low.webp';
      $destination_path_webp_lowquality = $destination_path_base . $filename_webp_lowquality;
      

      $resized_image_lowquality->setImageCompressionQuality($quality_low);
      $resized_image_lowquality->writeImage($destination_path_webp_lowquality);
      $resized_image_lowquality->destroy();


      $resized_image_bw = clone $resized_image;
      $resized_image_bw->setImageType(2);
      $resized_image_bw->posterizeImage(8, true);
      $filename_webp_bw = $file . '-bw.webp';
      $destination_path_webp_bw = $destination_path_base . $filename_webp_bw;
      $resized_image_bw->setImageCompressionQuality($quality_low);
      $resized_image_bw->writeImage($destination_path_webp_bw);
      $resized_image_bw->destroy();

      $resized_image->setImageCompressionQuality($quality_medium);
      $resized_image->writeImage($destination_path_webp);

      // Add the variants to the custom field for this attachment
      $variants = [
        'webp' => $filename_webp,
        'webp-low' => $filename_webp_lowquality,
        'avif' => $filename_avif,
        'avif-low' => $filename_aviflowquality,
        'webp-bw' => $filename_webp_bw,
        'avif-bw' => $filename_avif_bw,
      ];
      add_image_variant_custom_field($attachment_id, $suffix, $variants);

      // Destroy the resized image object to free up memory
      $resized_image->destroy();
  }
  
  // Destroy the original image object to free up memory
  $image->destroy();
}