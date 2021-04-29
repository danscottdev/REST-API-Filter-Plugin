<?php

/**
 * REST API Filter + Search + Sort Plugin
 *
 * Plugin Name:       Stilts REST API
 * Version:           1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_STILTS_REST_FILTER_VERSION', '1.0.0' );




// Third-party include to extend get_template_part() functionality to plugins folder
include plugin_dir_path(__FILE__ ) . '/includes/template-loader.php';


// Create REST API endpoints & callback functions
require_once plugin_dir_path(__FILE__ ) . '/includes/rest-api-endpoints.php';
include plugin_dir_path(__FILE__ ) . '/includes/rest-api-callbacks.php';



// Include JavaScript
function plugin_js_filter_scripts() {
	// only include plugin JS on pages where we need it
	// need to look into if this is the best way to do this

	if(is_page()){
		$input = file_get_contents("wp-content\plugins\stilts-rest-api\config.json");
		$config = json_decode($input);
		$pages = $config->pages;
		
		$page_templates = array();
		
		foreach($pages as $page){
			array_push($page_templates, $page->wp_template_file);
		}
		
		global $wp_query;
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );

		if(in_array($template_name, $page_templates)){
			wp_enqueue_script( 'rest-filter', plugin_dir_url(__FILE__) . '/js/filter.js', array(), 'PLUGIN_STILTS_REST_FILTER_VERSION', true);
			wp_enqueue_script( 'rest-filter-utilities', plugin_dir_url(__FILE__) . '/js/filter-utilities.js', array('rest-filter'), 'PLUGIN_STILTS_REST_FILTER_VERSION', true);
		}
	} 
}
add_action("wp_enqueue_scripts", 'plugin_js_filter_scripts');