<?php
/**
 * Plugin Name: DOMS Search
 * Plugin URI: https://github.com/darwin06/doms-search
 * Description: Plugin to enable filter search by PDF's, Doc's, Images and/or Videos 🚀.
 * Version: 0.0.2
 * Author: Darwin Mateos
 * Author URI: http://darwin06.github.io/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: doms-search
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/*
 * ADD JS and CSS
*/
function doms_scripts() {
  wp_enqueue_style( 'fontAwesome', plugin_dir_url( __FILE__ ) . '/public/css/all.min.css');
}
add_action('wp_enqueue_scripts','doms_scripts');

/*
* this function loads my plugin translation files
*/
function doms_load_translation_files() {
  load_plugin_textdomain('doms-search', false, 'doms-search/languages');
}
//add action to load my plugin files
add_action('plugins_loaded', 'doms_load_translation_files');

function doms_search_setup($content) {
  if ( is_search() || is_page( esc_html_x('Search', 'doms-search') ) ) {
    $get_post_type = get_post_type( get_the_ID() );
    if ($get_post_type === 'attachment') {
      $attach_content = '';

      // $attach_file = wp_get_attachment_url( get_post_thumbnail_id() );
      $attach_thumb = wp_get_attachment_image_src( get_the_ID(), 'thumbnail' ) ;

      $attach_file = wp_get_attachment_url( get_the_ID() );
      $id = get_the_ID();

      if ( wp_attachment_is_image( $id ) ) {
        $attach_content .= '<p><img class="img-fluid" src="'. $attach_thumb[0] .'" /><br />'. $attach_file .' <a href="' . $attach_file . '" target="_blank" class="btn btn-primary">'. __('Download here','doms-search') .'</a></p>' ;
      } else {
        $attach_content .= '<p>'. $attach_file .' <a href="' . $attach_file . '" target="_blank" class="btn btn-primary">'. __('Download here','doms-search') .'</a></p>' ;
      }

      return $attach_content;
    } else {
      return $content;
    }

  }
}
add_filter('the_content', 'doms_search_setup', 20);

/* *
 * Modify the Search form for a form with filter checkboxes
 */
function doms_my_search_form( $form ) {
  $form = include( plugin_dir_path( __FILE__ ) . 'includes/searchform.php' );

  return $form;
}
add_filter( 'get_search_form', 'doms_my_search_form' );
// Query vars for the preview
function doms_query_vars($public_query_vars) {
  $public_query_vars[] = 'type_mime';

  return $public_query_vars;
}
add_filter('query_vars', 'doms_query_vars', 10, 1);

/**
 * Filter Search by specific format file
 *
 * url https://codex.wordpress.org/Function_Reference/get_allowed_mime_types
 *
 */
function doms_search_attachments($query) {
  if ($query->is_search()) {
    $type_mime = get_query_var('type_mime', false);

    if ($type_mime) {
      $mime_type_format = array();

      if ($type_mime[0] !== 'all') {
        $mime_type_format = array();

        foreach($type_mime as $format){

          switch ($format) {
            case "pdf":
              array_push($mime_type_format,'application/pdf');
            break;
            case "doc":
              array_push($mime_type_format,
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
              );
            break;
            case "img":
              array_push($mime_type_format,
                'image/jpeg',
                'image/gif',
                'image/png'
              );
            break;
            case "video":
              array_push($mime_type_format,
                'video/x-ms-asf',
                'video/x-ms-wmv',
                'video/x-ms-wmx',
                'video/x-ms-wm',
                'video/avi',
                'video/divx',
                'video/x-flv',
                'video/quicktime',
                'video/mpeg',
                'video/mp4',
                'video/ogg',
                'video/webm',
                'video/x-matroska'
              );
            break;
          }
        }

          $query->set('post_type', 'attachment');
          $query->set('post_mime_type', $mime_type_format);
          $query->set('post_status', array('publish','inherit','any'));
      }

      /* foreach($type_mime as $format){
        if ($format === 'pdf') {
          array_push($mime_type_format, 'application/pdf');
        } elseif ($format === 'doc') {
          array_push($mime_type_format, 'application/msword',
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        } elseif ($format === 'img') {
          array_push($mime_type_format, 'image/jpeg', 'image/gif','image/png');
        }
      }

      $query->set('post_type', 'attachment');
      $query->set('post_mime_type', $mime_type_format);
      $query->set('post_status', 'any'); */
    }

  }
}
add_filter('pre_get_posts', 'doms_search_attachments', 40);


/* *
* Options
* url http://ottopress.com/2009/wordpress-settings-api-tutorial/
*/
include( plugin_dir_path( __FILE__ ) . 'includes/options.php' );

/**
 * https://developer.wordpress.org/plugins/plugin-basics/#basic-hooks
 */
function doms_search_activation() {
  // trigger our function that registers the custom post type
  doms_search_setup($content);

  // clear the permalinks after the post type has been registered
  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'doms_search_activation');


function doms_search_deactivation() {
  // Deactivation code here

  // clear the permalinks to remove our post type's rules from the database
  flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'doms_search_deactivation' );


function doms_search_uninstall() {

}
register_uninstall_hook( __FILE__, 'doms_search_uninstall' );
