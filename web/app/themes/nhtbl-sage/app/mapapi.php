<?php

/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest, * or null if none.
 */
function returnMapMarkers($data)
{
  $lieu = $data['lieu'];
  $lieux = array();

  if ($lieu === 'all') {
    $posts = get_posts(array(
      'post_type' => 'lieu',
      'status' => 'publish',
      'numberposts' => -1,
    ));

    foreach ($posts as $post) {
      $coordinates = array(
        'longitude' => get_field('longitude', $post->ID),
        'latitude' => get_field('latitude', $post->ID),
      );

      $lieux[] = array(
        'type' => 'Feature',
        'properties' => array(
          'title' => $post->post_title,
          'slug' => $post->post_name,
          'description' => get_the_excerpt($post),
          'category' => array(
            'slug' => get_field('type', $post->ID)["value"] ? get_field('type', $post->ID)["value"] :  'wee',
            'name' => get_field('type', $post->ID)["label"] ? get_field('type', $post->ID)["value"] :  'wee',
          )
        ),
        'geometry' => array(
          'coordinates' => array_values($coordinates),
          'type' => 'Point',
        ),
      );
    }
  } elseif ($lieu === 'resonance' || $lieu === 'triennale'  || $lieu === 'oeuvre-public') {
    $posts = get_posts(array(
      'post_type' => 'lieu',
      'status' => 'publish',
      'meta_key'      => 'type',
      'meta_value'    => $lieu,
      'numberposts' => -1,
    ));

    foreach ($posts as $post) {
      $coordinates = array(
        'longitude' => get_field('longitude', $post->ID),
        'latitude' => get_field('latitude', $post->ID),
      );

      $lieux[] = array(
        'type' => 'Feature',
        'properties' => array(
          'slug' => $post->post_name,
          'title' => $post->post_title,
          'description' => get_the_excerpt($post),
          'category' => array(
            'slug' => get_field('type', $post->ID)["value"] ? get_field('type', $post->ID)["value"] :  'wee',
            'name' => get_field('type', $post->ID)["label"] ? get_field('type', $post->ID)["value"] :  'wee',
          )
        ),
        'geometry' => array(
          'coordinates' => array_values($coordinates),
          'type' => 'Point',
        ),
      );
    }
  } else {
    $posts = get_posts(array(
      'post_type' => 'lieu',
      'status' => 'publish',
      'name' => $lieu,
      'numberposts' => 1,
    ));

    if (!empty($posts)) {
      $coordinates = array(
        'longitude' => get_field('longitude', $posts[0]->ID),
        'latitude' => get_field('latitude', $posts[0]->ID),
      );
      $image = \get_post_thumbnail_id($posts[0]->ID) ? \get_post_thumbnail_id($posts[0]->ID) : null;
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
      $lieux[] = array(
        'type' => 'Feature',
        'properties' => array(
          'slug' => $posts[0]->post_name,
          'image' => $image,
          'acces' => get_field('address', $posts[0]->ID),
          'permalink' => get_permalink($posts[0]->ID),
          'title' => $posts[0]->post_title,
          'description' => get_the_excerpt($posts[0]),
          'category' => array(
            'slug' => get_field('type', $posts[0]->ID)["value"],
            'name' => get_field('type', $posts[0]->ID)["label"]
          )
        ),
        'geometry' => array(
          'coordinates' => array_values($coordinates),
          'type' => 'Point',
        ),
      );
    }
  }

  $response = array(
    'type' => 'FeatureCollection',
    'features' => $lieux,
  );

  return $response;
}

add_action('rest_api_init', function () {
  register_rest_route('triennale/v1', '/lieux/(?P<lieu>[\w-]+)', array(
    'methods' => 'GET',
    'callback' => 'returnMapMarkers',
    'permission_callback' => '__return_true',
  ));
});
