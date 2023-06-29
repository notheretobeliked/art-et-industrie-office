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
      'lang' => 'fr',
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
          ),
          'id' => $post->ID,
          'language' => pll_get_post_language( $post->ID ),
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
      'lang' => 'fr',
      'meta_key'      => 'type',
      'meta_value'    => $lieu,
      'numberposts' => -1,
    ));

    foreach ($posts as $post) {
      $coordinates = array(
        'longitude' => get_field('longitude', $post->ID),
        'latitude' => get_field('latitude', $post->ID),
      );
      is_array(get_field('type', $post->ID)) ?
        $category = [
          'slug' => get_field('type', $post->ID)["value"] ? get_field('type', $post->ID)["value"] :  'wee',
          'name' => get_field('type', $post->ID)["label"] ? get_field('type', $post->ID)["value"] :  'wee',
        ] : $category = [
          'slug' => get_field('type', $post->ID) ? get_field('type', $post->ID) :  'wee',
          'name' => get_field('type', $post->ID) ? get_field('type', $post->ID) :  'wee',
        ];

      $lieux[] = array(
        'type' => 'Feature',
        'properties' => array(
          'slug' => $post->post_name,
          'title' => $post->post_title,
          'description' => get_the_excerpt($post),
          'category' => $category,
          'language' => pll_get_post_language( $post->ID ),
          'id' => $post->ID,
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
      'lang' => 'fr',
      'numberposts' => 1,
    ));

    $post = $posts[1];
    error_log(pll_get_post($post->ID, 'fr'));
    error_log(print_r($post, true));
    if (!empty($posts)) {
      $coordinates = array(
        'longitude' => get_field('longitude', $post->ID),
        'latitude' => get_field('latitude', $post->ID),
      );
      $image = \get_post_thumbnail_id($post->ID) ? \get_post_thumbnail_id($post->ID) : null;
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
      is_array(get_field('type', $post->ID)) ?
        $category = [
          'slug' => get_field('type', $post->ID)["value"] ? get_field('type', $post->ID)["value"] :  'wee',
          'name' => get_field('type', $post->ID)["label"] ? get_field('type', $post->ID)["value"] :  'wee',
        ] : $category = [
          'slug' => get_field('type', $post->ID) ? get_field('type', $post->ID) :  'wee',
          'name' => get_field('type', $post->ID) ? get_field('type', $post->ID) :  'wee',
        ];
      $lieux[] = array(
        'type' => 'Feature',
        'properties' => array(
          'slug' => $post->post_name,
          'image' => $image,
          'acces' => get_field('address', $post->ID),
          'permalink' => get_permalink($post->ID),
          'title' => $post->post_title,
          'description' => get_the_excerpt($post),
          'category' => $category,
          'language' => pll_get_post_language( $post->ID ),
          'id' => $post->ID,
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
