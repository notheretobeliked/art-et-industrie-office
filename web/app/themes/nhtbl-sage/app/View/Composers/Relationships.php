<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use DateTime;

class Relationships extends Composer
{
  /**
   * List of views served by this composer.
   *
   * @var array
   */
  protected static $views = [
    'partials.content-single-artistes',
    'partials.content-single-lieu',
    'tribe.events.*',

  ];

  /**
   * Data to be passed to view before rendering, but after merging.
   *
   * @return array
   */
  public function override()
  {
    return [
      'galerie' => $this->galerieoutput(),
      'artiste_listofevents' => is_array($this->getEvents('event_artiste')) ? $this->getEvents('event_artiste')["results"] : null,
      'artiste_eventsfilter' => is_array($this->getEvents('event_artiste')) ? $this->getEvents('event_artiste')["categories"] : null,
      'artiste_listoflieux' => $this->getLieux('artiste_lieu'),
      'lieu_listofevent' => $this->getEvents('lieu_event')["results"],
      'lieu_eventsfilter' => $this->getEvents('lieu_event')["categories"],
      'lieu_listofartistes' => $this->getArtistes('artiste_lieu'),
      'event_location' => $this->getLieux('lieu_event', false),
      'date_activite' => get_field('dates'),
      'localite' => get_field('localite'),
      'artiste_edition' => get_field('artiste_edition'),
      'edition_lieu' => get_field('edition_lieu'),
    ];
  }

  /**
   * Returns the events associated with a content.
   *
   * @return array
   */

  public function getEvents($field_name)
  {
    error_log(print_r(get_field($field_name), true));
    if (get_field($field_name)) {
      $args = array(
        'posts_per_page' => -1,
        'eventDisplay' => 'list',
        'ends_after' => 'now',
        'post__in' => get_field($field_name)
      );
      $items = tribe_get_events($args);

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
        if (get_the_terms($item->ID, 'tribe_events_cat')) {
          $categories = [];
          foreach (get_the_terms($item->ID, 'tribe_events_cat') as $cat) {
            $categories[] = [
              'name' => $cat->name,
              'slug' => $cat->slug,
            ];
          }
        }

        $lieu = get_field('lieu_event', $item->ID);
        $lieu ? $lieu = [
          'title' => get_the_title($lieu[0]),
          'permalink' => get_permalink($lieu[0]),
        ] : $lieu = null;

        $date = tribe_get_start_date($item->ID, false, 'd F Y');
        if (tribe_get_start_date($item->ID, false, 'd F Y') != tribe_get_end_date($item->ID, false, 'd F Y')) {
          $end_date = tribe_get_end_date($item->ID, false, 'd F Y');
          if (tribe_get_start_date($item->ID, false, 'Y') === tribe_get_end_date($item->ID, false, 'Y')) $date = (tribe_get_start_date($item->ID, false, 'd F'));
        } else $end_date = null;

        $image = \get_post_thumbnail_id($item->ID);

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
         
        $allCategories = array_merge($allCategories, array_map(function ($category) {
          return $category['name'];
        }, $categories));
        $filtertags = [];

        // Populate $filtertags with list of categories.
        $filtertags = array_map(function ($category) {
          return $category['name'];
        }, $categories);

        // Check if the event is taking place today, this month or in the future

        $start_date_str = tribe_get_end_date($item->ID, false, 'Y-m-d');
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
          'filtertags' => $filtertags,
          'thumbnail' => $image,
          'date' => $date,
          'time' => tribe_get_start_date($item->ID, false, 'H:i'),
          'end_date' => $end_date,
          'end_time' => tribe_get_end_date($item->ID, false, 'H:i'),
          'excerpt' => get_the_excerpt($item->ID),
          'categories' => $categories,
          'tags' => $tags,
        ];

      }
      $allCategories = array_unique($allCategories);

      return ['results' => $return, 'categories' => $allCategories];
    }
  }


  public function getArtistes($field_name)
  {
    if (!get_field($field_name)) return;

    $artistes = get_field($field_name);
    $args = [
      'post__in' => $artistes,
      'post_type' => 'artistes',
      'post_status' => 'publish',
      'numberposts' => -1,
      'order'             => 'ASC',
      'meta_key' => 'nom_de_famille',
      'orderby' => 'meta_value',
    ];
    $items = get_posts($args);
    $output = [];
    foreach ($items as $item) {
      $output[] = [
        'id' => $item->ID,
        'title' => $item->post_title,
        'permalink' => get_permalink($item->ID),
      ];
    }
    return $output;
  }



  public function getLieux($field_name, $many = true)
  {
    if (!get_field($field_name)) return;
    $contents = get_field($field_name);
    $output = $many ? array() : '';
    foreach ($contents as $content) {
      $image = \get_post_thumbnail_id($content);
      // get image in size medium_large from $image['id']
      if ($image) {
        $subdir = get_post_meta($image, 'subdir', true);
        $other_formats = get_post_meta($image, 'image_variants', true);
        $alt = get_post_meta($image, '_wp_attachment_image_alt', TRUE);
        $imageMeta = wp_get_attachment_metadata($image);
        if (is_array($imageMeta)) {
          $width = isset($imageMeta["width"]) ? $imageMeta["width"] : 160;
          $height = isset($imageMeta["height"]) ? $imageMeta["height"] : 90;
        } else {
          $width = 160;
          $height = 90;
        }
        $image = array(
          'id' => $image,
          'src' => wp_get_attachment_image_src($image, 'medium_large'),
          'width' => $width,
          'height' => $height,
          'srcset' => wp_get_attachment_image_srcset($image, 'medium_large'),
          'image' => wp_get_attachment_image($image, 'medium_large'),
          'alt' => $alt,
          'other_formats' => $other_formats,
          'subdir' => $subdir,
          'caption' => wp_get_attachment_caption($image) ? '<figcaption>' . wp_get_attachment_caption($image) . '</figcaption>' : '',
        );
      }
      if ($many) {
        $output[] = [
          'url' => \get_permalink($content),
          'slug' => basename(\get_permalink($content)),
          'title' => \get_the_title($content),
          'introtext' => \get_the_excerpt($content),
          'image' => $image,
          'address' => wpautop(get_field('address', $content))
        ];
      } else {
        $output = [
          'url' => \get_permalink($content),
          'slug' => basename(\get_permalink($content)),
          'title' => \get_the_title($content),
          'introtext' => \get_the_excerpt($content),
          'image' => $image,
          'address' => wpautop(get_field('address', $content))
        ];
      }
    }
    return $output;
  }


  public function galerieoutput()
  {
    $images = get_field('galerie');
    if (!$images) return;
    $output = array();
    foreach ($images as $image) {
      $id = is_array($image) ? $image['id'] : $image;
      // get image in size medium_large from $image['id']
      $subdir = get_post_meta($id, 'subdir', true);
      $other_formats = get_post_meta($id, 'image_variants', true);
      $output[] = array(
        'id' => $id,
        'width' => wp_get_attachment_metadata($id)['width'],
        'height' => wp_get_attachment_metadata($id)['height'],
        'src' => wp_get_attachment_image_src($id, 'medium_large'),
        'srcset' => wp_get_attachment_image_srcset($id, 'medium_large'),
        'image' => wp_get_attachment_image($id, 'medium_large'),
        'alt' => get_post_meta($id, '_wp_attachment_image_alt', TRUE),
        'other_formats' => $other_formats,
        'subdir' => $subdir,
        'caption' => wp_get_attachment_caption($id) ? '<figcaption>' . wp_get_attachment_caption($image['id']) . '</figcaption>' : '',
      );
    }
    return $output;
  }
}
