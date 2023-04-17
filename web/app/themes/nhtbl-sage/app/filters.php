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
        'core/heading',
		'core/list',
        'core/button',
        'core/cover',
		'core/list-item',
        'acf/galerie',
        'acf/texte-intro',
        'acf/visuel-triennale',
        'acf/liste-des-liens',
	);
	return $allowed_blocks;
 
}, 25, 2 );


/* 
 * Add custom post type to graphql
 * https://www.wpgraphql.com/docs/custom-post-types/
 */

add_filter( 'register_post_type_args', function ( $args, $post_type ) {
	// Let's make sure that we're customizing the post type we really need
	if ( $post_type !== 'edition' ) {
		return $args;
	}
	
	// Now, we have access to the $args variable
	// If you want to modify just one label, you can do something like this
	$args['show_in_graphql'] = true;
    $args['graphql_single_name'] = 'Edition';
    $args['graphql_plural_name'] = 'Editions';
	$args['publicly_queryable'] = true;
	
	return $args;
}, 10, 2 );


add_filter( 'register_post_type_args', function ( $args, $post_type ) {
	// Let's make sure that we're customizing the post type we really need
	if ( $post_type !== 'lieu' ) {
		return $args;
	}
	
	// Now, we have access to the $args variable
	// If you want to modify just one label, you can do something like this
	$args['show_in_graphql'] = true;
    $args['graphql_single_name'] = 'Lieu';
    $args['graphql_plural_name'] = 'Lieux';
	$args['publicly_queryable'] = true;
	
	return $args;
}, 10, 2 );

add_filter( 'register_taxonomy_args', function( $args, $taxonomy ) {

    if ( 'lieu_type' === $taxonomy ) {
      $args['show_in_graphql'] = true;
      $args['graphql_single_name'] = 'LieuType';
      $args['graphql_plural_name'] = 'LieuTypes';
    }
  
    return $args;
  
  }, 10, 2 );


  /* 
   * Set relationship fields as biderectional
   * https://www.advancedcustomfields.com/resources/bidirectional-relationships/
   * 
  */
    function bidirectional_acf_update_value( $value, $post_id, $field  ) {

    // vars
    $field_name = $field['name'];
    $field_key = $field['key'];
    $global_name = 'is_updating_' . $field_name;


    // bail early if this filter was triggered from the update_field() function called within the loop below
    // - this prevents an inifinte loop
    if( !empty($GLOBALS[ $global_name ]) ) return $value;


    // set global variable to avoid inifite loop
    // - could also remove_filter() then add_filter() again, but this is simpler
    $GLOBALS[ $global_name ] = 1;


    // loop over selected posts and add this $post_id
    if( is_array($value) ) {

        foreach( $value as $post_id2 ) {
            
            // load existing related posts
            $value2 = get_field($field_name, $post_id2, false);
            
            
            // allow for selected posts to not contain a value
            if( empty($value2) ) {
                
                $value2 = array();
                
            }
            
            
            // bail early if the current $post_id is already found in selected post's $value2
            if( in_array($post_id, $value2) ) continue;
            
            
            // append the current $post_id to the selected post's 'related_posts' value
            $value2[] = $post_id;
            
            
            // update the selected post's value (use field's key for performance)
            update_field($field_key, $value2, $post_id2);
            
        }

    }


    // find posts which have been removed
    $old_value = get_field($field_name, $post_id, false);

    if( is_array($old_value) ) {
        
        foreach( $old_value as $post_id2 ) {
            
            // bail early if this value has not been removed
            if( is_array($value) && in_array($post_id2, $value) ) continue;
            
            
            // load existing related posts
            $value2 = get_field($field_name, $post_id2, false);
            
            
            // bail early if no value
            if( empty($value2) ) continue;
            
            
            // find the position of $post_id within $value2 so we can remove it
            $pos = array_search($post_id, $value2);
            
            
            // remove
            unset( $value2[ $pos] );
            
            
            // update the un-selected post's value (use field's key for performance)
            update_field($field_key, $value2, $post_id2);
            
        }
        
    }


    // reset global varibale to allow this filter to function as per normal
    $GLOBALS[ $global_name ] = 0;


    // return
    return $value;

}

add_filter('acf/update_value/name=lieu_event', 'App\bidirectional_acf_update_value', 13, 3);
add_filter('acf/update_value/name=event_artiste', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=event_edition', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=artiste_edition', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=artiste_lieu', 'App\bidirectional_acf_update_value', 10, 3);
add_filter('acf/update_value/name=edition_lieu', 'App\bidirectional_acf_update_value', 10, 3);




/* 
 * Fix return value of acf gallery blocks
 * https://www.wpgraphql.com/docs/custom-post-types/
 */

add_filter( 'graphql_resolve_field', function( $result, $source, $args, $context, $info, $type_name, $field_key, $field, $field_resolver ) {

    if ( 'AcfGalerieBlock' === $type_name  && 'attributesJSON' === $field_key ) {
      $newresult = json_decode($result);
      //error_log(print_r($newresult, true));  
      $outputresult = array();
      foreach($newresult->data->galerie as $image) {
        error_log($image);
        $outputresult[] = wp_get_attachment_image_src($image, 'large')[0];
      }
      error_log(print_r($outputresult, true));  
      return json_encode($outputresult);
    }
  
    return $result;
  
  }, 10, 9 );


/* add a new 'clean_data' field to the REST API
*/
// add_action(
//     'rest_api_init',
//     function () {

//         if (!function_exists('use_block_editor_for_post_type')) {
//             require ABSPATH . 'wp-admin/includes/post.php';
//         }

//         // Surface all Gutenberg blocks in the WordPress REST API
//         $post_types = get_post_types_by_support(['editor']);
//         foreach ($post_types as $post_type) {
//             if (use_block_editor_for_post_type($post_type)) {
//                 register_rest_field(
//                     $post_type,
//                     'clean_data',
//                     [
//                         'get_callback' => function (array $post) {
//                             $blocks = parse_blocks($post['content']['raw']);
//                             $newblocks = $blocks;
//                             foreach ($newblocks as &$block) {
//                                 if ('acf/galerie' === $block['blockName']) {
//                                     /* 
//                                     If the block is an acf gallery, add a filed called 'rendered_gallery' which contains the actual
//                                     image srcset for each image in the gallery
//                                     */
//                                     $images = array();
//                                     foreach ($block['attrs']['data']['galerie'] as $imageId) {
//                                         // get srcset for $imageId
//                                         $images[] = wp_get_attachment_image_srcset($imageId, 'medium-large');
//                                     }
//                                     error_log(print_r($images, true));
//                                     $block['attrs']['data']['rendered_gallery'] = $images;
//                                 }  elseif ('acf/liste-des-liens' === $block['blockName']) {
//                                     /* 
//                                     If the block is a list-des-liens block, add a field called 'attached_posts' which contains the metadata
//                                     for each of the posts in the block
//                                     */
//                                     error_log(print_r($block, true));
//                                     //  echo apply_filters( 'the_content', render_block( $block ) );
//                                     error_log(print_r($block['attrs']['data']['galerie'], true));
//                                     $images = array();
//                                     foreach ($block['attrs']['data']['content'] as $contentId) {
//                                         // get srcset for $imageId
//                                         $content[] = [
//                                             'title' => get_the_title($contentId),
//                                             'link' => get_the_permalink($contentId),
//                                             'thumbnail' => wp_get_attachment_image_srcset($contentId, 'medium-large'),
//                                         ];
//                                     }
//                                     error_log(print_r($content, true));
//                                     $block['attrs']['data']['attached_posts'] = $content;
//                                 }

//                             }

//                             return $newblocks;
//                         },
//                     ]
//                 );
//             }
//         }
//     }
// );
