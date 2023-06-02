<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

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
      'event_artiste' => $this->event_artiste(),
      'lieu_event' => $this->lieu_event(),
      'date_activite' => get_field('dates'),
      'localite' => get_field('localite'),
      'artiste_edition' => get_field('artiste_edition'),
      'artiste_lieu' => $this->artiste_lieu(),
      'event_edition' => get_field('event_edition'),
      'edition_lieu' => get_field('edition_lieu'),
    ];
  }

  /**
   * Returns the events associated with an artist.
   *
   * @return string
   */

  public function event_artiste()
  {
    if (get_field('event_artiste')) {
      $args = array(
        'posts_per_page' => -1,
        'start_date' => date('Y-m-d H:i:s'),
        'eventDisplay' => 'list',
        'post__in' => get_field('event_artiste')
      );
      $items = tribe_get_events($args);

      $return = [];
      foreach ($items as $item) {
        if (get_the_terms($item->ID, 'post_tag')) {
          $tags = [];
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
          'title' => get_the_title($lieu[0]->ID),
          'permalink' => get_permalink($lieu[0]->ID),
        ] : $lieu = null;

        $date = tribe_get_start_date($item->ID, false, 'd F Y');
        if (tribe_get_start_date($item->ID, false, 'd F Y') != tribe_get_end_date($item->ID, false, 'd F Y')) {
          $end_date = tribe_get_end_date($item->ID, false, 'd F Y');
          if (tribe_get_start_date($item->ID, false, 'Y') === tribe_get_end_date($item->ID, false, 'Y')) $date = (tribe_get_start_date($item->ID, false, 'd F'));
        } else $end_date = null;

        $return[] = [
          'lieu' => $lieu,
          'title' => get_the_title($item->ID),
          'lieu_event' => $this->lieu_event(),
          'artiste_lieu' => $this->artiste_lieu(),
          'permalink' => get_permalink($item->ID),
          'thumbnail' => get_the_post_thumbnail_url($item->ID, 'medium'),
          'date' => $date,
          'time' => tribe_get_start_date($item->ID, false, 'H:i'),
          'end_date' => $end_date,
          'end_time' => tribe_get_end_date($item->ID, false, 'H:i'),
          'excerpt' => get_the_excerpt($item->ID),
          'categories' => $categories,
          'tags' => $tags,
        ];
      }

      return $return;
    }
  }

  public function lieu_event()
  {
    if (get_field('lieu_event')) {
      $args = array(
        'posts_per_page' => -1,
        'start_date' => date('Y-m-d H:i:s'),
        'eventDisplay' => 'list',
        'post__in' => get_field('lieu_event')
      );
      $items = tribe_get_events($args);

      $return = [];
      foreach ($items as $item) {
        if (get_the_terms($item->ID, 'post_tag')) {
          $tags = [];
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
          'title' => get_the_title($lieu[0]->ID),
          'permalink' => get_permalink($lieu[0]->ID),
        ] : $lieu = null;

        $date = tribe_get_start_date($item->ID, false, 'd F Y');
        if (tribe_get_start_date($item->ID, false, 'd F Y') != tribe_get_end_date($item->ID, false, 'd F Y')) {
          $end_date = tribe_get_end_date($item->ID, false, 'd F Y');
          if (tribe_get_start_date($item->ID, false, 'Y') === tribe_get_end_date($item->ID, false, 'Y')) $date = (tribe_get_start_date($item->ID, false, 'd F'));
        } else $end_date = null;

        $return[] = [
          'lieu' => $lieu,
          'title' => get_the_title($item->ID),
          'permalink' => get_permalink($item->ID),
          'thumbnail' => get_the_post_thumbnail_url($item->ID, 'medium'),
          'date' => $date,
          'time' => tribe_get_start_date($item->ID, false, 'H:i'),
          'end_date' => $end_date,
          'end_time' => tribe_get_end_date($item->ID, false, 'H:i'),
          'excerpt' => get_the_excerpt($item->ID),
          'categories' => $categories,
          'tags' => $tags,
        ];
      }

      return $return;
    }
  }

  public function artiste_lieu()
  {
    if (!get_field('artiste_lieu')) return;

    $artiste_lieu = get_field('artiste_lieu');
    $args = [
      'post__in' => $artiste_lieu,
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


  public function galerieoutput()
  {
    $images = get_field('galerie');
    $output = array();
    foreach ($images as $image) {
      // get image in size medium_large from $image['id']
      $subdir = get_post_meta($image['id'], 'subdir', true);
      $other_formats = get_post_meta($image['id'], 'image_variants', true);
      $output[] = array(
        'id' => $image['id'],
        'width' => wp_get_attachment_metadata($image['id'])['width'],
        'height' => wp_get_attachment_metadata($image['id'])['height'],
        'src' => wp_get_attachment_image_src($image['id'], 'medium_large'),
        'srcset' => wp_get_attachment_image_srcset($image['id'], 'medium_large'),
        'image' => wp_get_attachment_image($image['id'], 'medium_large'),
        'alt' => $image['alt'],
        'other_formats' => $other_formats,
        'subdir' => $subdir,
        'caption' => wp_get_attachment_caption($image['id']) ? '<figcaption>' . wp_get_attachment_caption($image['id']) . '</figcaption>' : '',
      );
    }
    return $output;
  }
}
