<?php 
if ( ! defined('WP_CONTENT_DIR')) exit('No direct script access allowed');
/**
* This file (loader.php) is called only when we've checked for any potential 
* conflicts with function names, class names, or constant names. With so many WP 
* plugins available and so many potential conflicts out there, I've attempted to 
* avoid the headaches caused from name pollution as much as possible.
*/

/*
Run tests only upon activation
http://codex.wordpress.org/Function_Reference/register_activation_hook
*/

define('VIDCURA_PATH', dirname( __FILE__ ));
define('VIDCURA_URL', WP_PLUGIN_URL .'/'. basename(VIDCURA_PATH));
define('VIDCURA_TXTDOMAIN', 'vidcura');

// Always Required Files
require_once('includes/VidCura.php');

// Admin-only files
if( is_admin()) {
	require_once('tests/VidCuraTests.php');
	
	// Run Tests (add new tests to the VidCuraTests class as req'd)
	// If there are errors, VidCuraTests::$errors will get populated.
	VidCuraTests::wp_version_gt(VidCura::wp_req_ver);
	VidCuraTests::php_version_gt(VidCura::php_req_ver);
	VidCuraTests::mysql_version_gt(VidCura::mysql_req_ver);
	VidCuraTests::incompatible_plugins(array());
	
	// View helper functions
	include_once('includes/functions.php');
}

// Get admin ready, print any VidCuraTests::$errors in the admin dashboard
add_action('admin_notices', 'VidCura::print_notices');

if (empty(VidCura::$errors))
{
	// Load up the VidCura data from wp_options, populates VidCura::$data
	VidCura::load_data();
	
	// Run any updates for this version.
	add_action('init', 'VidCura::check_for_updates', 0 );	
	
	// Register any custom post-types (currently assets)
	add_action('init', 'VidCura::register_custom_post_types', 11 );
	
	// Rewrite urls to redirect API calls (from Roku)
	add_action('init', 'VidCura::add_rewrite_rule');
	add_filter('query_vars', 'VidCura::add_query_vars');
	add_action('parse_request', 'VidCura::parse_request');	
	add_action('wp_loaded', 'VidCura::flush_rewrite_rules');
	
	if(is_admin()) {	
		// Generate admin menu, bootstrap CSS/JS
		add_action('admin_init', 'VidCura::admin_init');	
	
		// Create custom plugin settings menu
		add_action('admin_menu', 'VidCura::create_admin_menu');
		add_filter('plugin_action_links', 'VidCura::add_plugin_settings_link', 10, 2 );

		// FUTURE: Highlght which themes are VidCura-compatible (if any)
		// add_filter('theme_action_links', 'VidCura::highlight_cctm_compatible_themes');
		add_action('admin_notices', 'VidCura::print_warnings');
		
		// Save any custom post-types (currently assets)
		add_action('save_post', 'VidCura::save_custom_post_types');
	}
}

/*EOF*/
