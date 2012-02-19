<?php

/**
 * VidCura
 * 
 * This is the main class for the VidCura plugin.
 * It holds its functions hooked to WP events and utilty functions and configuration
 * settings.
 * 
 * @package vidcura
 */
class VidCura {
	// Name of this plugin and version data.
	// See http://php.net/manual/en/function.version-compare.php:
	// any string not found in this list < dev < alpha =a < beta = b < RC = rc < # < pl = p
	const name   = 'VidCura';
	const version = '0.1';
	const version_meta = 'rc'; // dev, rc (release candidate), pl (public release)

	// Required versions (referenced in the VidCuraTest class).
	const wp_req_ver  = '3.3';
	const php_req_ver  = '5.2.6';
	const mysql_req_ver = '4.1.2';

	// Directory relative to wp-content/uploads/ where we can store def files
	// Omit the trailing slash.
	const base_storage_dir = 'vidcura';

	/**
	 * The following constants identify the option_name in the wp_options table
	 * where this plugin stores various data.
	 */
	const db_key  = 'vidcura_data';
	const site_token  = 'vidcura_token';


	/**
	 * Determines where the main CCTM menu appears. WP is vulnerable to conflicts
	 * with menu items, so the parameter is listed here for easier editing.
	 * See http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=203
	 */
	const menu_position = 73;


	// Data object stored in the wp_options table representing all primary data
	// for post_types and custom fields
	public static $data = array();
	
	
	/**
	 * Warnings are stored as a simple array of text strings, e.g. 'You spilled your coffee!'
	 * Whether or not they are displayed is determined by checking against the self::$data['warnings']
	 * array: the text of the warning is hashed and this is used as a key to identify each warning.
	 */
	public static $warnings = array();

	/**
	 * used to store validation errors. The errors take this format:
	 * self::$errors['field_name'] = 'Description of error';
	 */
	public static $errors;


	// Used to filter settings inputs (e.g. descriptions of custom fields or post-types)
	public static $allowed_html_tags = '';
	
	
	//------------------------------------------------------------------------------
	/**
	 * Prepare a post type definition for registration.  This gets run immediately 
	 * before the register_post_type() function is called.  It allows us to abstract 
	 * what WP gets from the stored definition a bit.
	 *
	 * @param mixed   the VidCura definition for a post type
	 * @param unknown $def
	 * @return mixed  the WordPress authorized definition format.
	 */
	private static function _prepare_post_type_def($def) {
		// Sigh... working around WP's irksome inputs
		if (isset($def['vidcura_show_in_menu']) && $def['vidcura_show_in_menu'] == 'custom') {
			$def['show_in_menu'] = $def['vidcura_show_in_menu_custom'];
		}
		else {
			$def['show_in_menu'] = (bool) self::get_value($def, 'vidcura_show_in_menu');
		}
		// We display "include" type options to the user, and here on the backend
		// we swap this for the "exclude" option that the function requires.
		$include = self::get_value($def, 'include_in_search');

		if (empty($include)) {
			$def['exclude_from_search'] = true;
		}
		else {
			$def['exclude_from_search'] = false;
		}

		// TODO: retro-support... if public is checked, then the following options are inferred
		/*
		if (isset($def['public']) && $def['public']) {
			$def['publicly_queriable'] = true;
			$def['show_ui'] = true;
			$def['show_in_nav_menus'] = true;
			$def['exclude_from_search'] = false;
		}
		*/

		// Verbosely check to see if "public" is inferred
		if (isset($def['publicly_queriable']) && $def['publicly_queriable']
			&& isset($def['show_ui']) && $def['show_ui']
			&& isset($def['show_in_nav_menus']) && $def['show_in_nav_menus']
			&& (!isset($def['exclude_from_search']) || (isset($def['exclude_from_search']) && !$def['publicly_queriable']))
		) {
			$def['public'] = true;
		}

		unset($def['custom_orderby']);

		return $def;
	}


	//------------------------------------------------------------------------------
	/**
	 * Adds a link to the settings directly from the plugins page.  This filter is
	 * called for each plugin, so we need to make sure we only alter the links that
	 * are displayed for THIS plugin.
	 *
	 * INPUTS (determined by WordPress):
	 *   array('deactivate' => 'Deactivate')
	 * relative to the plugins directory, e.g. 'vidcura/index.php'
	 *
	 * @param array   $links is a hash of links to display in the format of name => translation e.g.
	 * @param string  $file  is the path to plugin's main file (the one with the info header),
	 * @return array $links
	 */
	public static function add_plugin_settings_link($links, $file) {
		if ( $file == basename(self::get_basepath()) . '/index.php' ) {
			$settings_link = sprintf('<a href="%s">%s</a>'
				, admin_url( 'admin.php?page=vidcura' )
				, __('Settings')
			);
			array_unshift( $links, $settings_link );
		}

		return $links;
	}


	public static function add_query_vars($query_vars) {
			$query_vars[] = 'target';
			$query_vars[] = 'token';
			$query_vars[] = 'action';
			return $query_vars;
	}


	public static function add_rewrite_rule() {
			add_rewrite_rule( 
					 '^api/([^/]+)/([^/]+)/([^/]+)/?$'
					,'index.php?target=$matches[1]&token=$matches[2]&action=$matches[3]'
					,'top' 
			);
	}


	//! Public Functions
	//------------------------------------------------------------------------------
	/**CHANGE
	 * Load CSS and JS for admin folks in the manager.  Note that we have to verbosely
	 * ensure that thickbox's css and js are loaded: normally they are tied to the
	 * "editor" area of the content type, so thickbox would otherwise fail
	 * if your custom post_type doesn't use the main editor.
	 * See http://codex.wordpress.org/Function_Reference/wp_enqueue_script for a list
	 * of default scripts bundled with WordPress
	 */
	public static function admin_init() {

		load_plugin_textdomain( VIDCURA_TXTDOMAIN, false, VIDCURA_PATH.'/lang/' );

		$file = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')+1);
		$page = self::get_value($_GET, 'page');

		// Only add our junk if we are creating/editing a post or we're on
		// on of our VidCura pages
		if ( preg_match('/^vidcura.*/', $page) ) {

			wp_register_style('BOOTSTRAP_css', VIDCURA_URL . '/css/bootstrap.min.css');
			wp_enqueue_style('BOOTSTRAP_css');
			wp_register_style('COLORBOX_css', VIDCURA_URL . '/css/colorbox.css');
			wp_enqueue_style('COLORBOX_css');
			wp_register_style('COLORPICKER_css', VIDCURA_URL . '/css/colorpicker.css');
			wp_enqueue_style('COLORPICKER_css');
			wp_register_style('VIDCURA_css', VIDCURA_URL . '/css/vidcura.css');
			wp_enqueue_style('VIDCURA_css');
			
			wp_register_script('COLORBOX_js', VIDCURA_URL . '/js/jquery.colorbox.js');
			wp_enqueue_script('COLORBOX_js');
			wp_register_script('COLORPICKER_js', VIDCURA_URL . '/js/colorpicker.js');
			wp_enqueue_script('COLORPICKER_js');
			wp_register_script('VIDCURA_js', VIDCURA_URL . '/js/vidcura.js');
			wp_enqueue_script('VIDCURA_js');
		}
		
		wp_enqueue_style('thickbox');
			
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('video-upload', get_bloginfo('stylesheet_directory') . '/js/video-upload.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('video-upload');
	}


	//------------------------------------------------------------------------------
	/**
	 * Create custom post-type menu.  This should only be visible to
	 * admin users (single-sites) or the super_admin users (multi-site).
	 *
	 * See http://codex.wordpress.org/Administration_Menus
	 * http://wordpress.org/support/topic/plugin-custom-content-type-manager-multisite?replies=18#post-2501711
	 */
	public static function create_admin_menu() {
		self::load_file('/config/menus/admin_menu.php');
	}	


	public static function flush_rewrite_rules() {
    if ( function_exists( 'flush_rewrite_rules' ) ) {   // Introduced in WP v3.0
      flush_rewrite_rules();
    } else {                                            // Support for pre WP v3.0
      global $wp_rewrite;
      $wp_rewrite->flush_rules();
    }	
	}
	
	
	//------------------------------------------------------------------------------
	/**
	 *  Defines the diretory for this plugin.
	 *
	 * @return string
	 */
	public static function get_basepath() {
		return dirname(dirname(__FILE__));
	}


	//------------------------------------------------------------------------------
	/**
	 * Gets the plugin version from this class.
	 *
	 * @return string
	 */
	public static function get_current_version() {
		return self::version .'-'. self::version_meta;
	}
	

	//------------------------------------------------------------------------------
	/**
	 * Gets the plugin version (used to check if updates are available). This checks
	 * the database to see what the database thinks is the current version. Right
	 * after an update, the database will think the version is older than what
	 * the VidCura class will show as the current version. We use this to trigger 
	 * modifications of the VidCura data structure and/or database options.
	 *
	 * @return string
	 */
	public static function get_stored_version() {
		if ( isset(self::$data['vidcura_version']) ) {
			return self::$data['vidcura_version'];
		}
		else {
			return '0';
		}
	}

	//------------------------------------------------------------------------------
	/**
	 * Designed to safely retrieve scalar elements out of a hash. Don't use this
	 * if you have a more deeply nested object (e.g. an array of arrays).
	 *
	 * @param array   $hash    an associative array, e.g. array('animal' => 'Cat');
	 * @param string  $key     the key to search for in that array, e.g. 'animal'
	 * @param mixed   $default (optional) : value to return if the value is not set. Default=''
	 * @return mixed
	 */
	public static function get_value($hash, $key, $default='') {
		if ( !isset($hash[$key]) ) {
			return $default;
		}
		else {
			if ( is_array($hash[$key]) ) {
				return $hash[$key];
			}
			// Warning: stripslashes was added to avoid some weird behavior
			else {
				return esc_html(stripslashes($hash[$key]));
			}
		}
	}
	
	
	public static function check_for_updates() {
		
		// If it's not a new install, we check for updates
		if ( version_compare( self::get_stored_version(), self::get_current_version(), '<' ) ) {
			// set the flag
			define('VIDCURA_UPDATE_MODE', 1);
			// Load up available updates in order (scandir will sort the results automatically)
			$updates = scandir(VIDCURA_PATH.'/updates');
			foreach ($updates as $file) {
				// Skip the gunk
				if ($file === '.' || $file === '..') continue;
				if (is_dir(VIDCURA_PATH.'/updates/'.$file)) continue;
				if (substr($file, 0, 1) == '.') continue;
				// skip non-php files
				if (pathinfo(VIDCURA_PATH.'/updates/'.$file, PATHINFO_EXTENSION) != 'php') continue;

				// We don't want to re-run older updates
				$this_update_ver = substr($file, 0, -4);
				if ( version_compare( self::get_stored_version(), $this_update_ver, '<' ) ) {
					// Run the update by including the file
					include VIDCURA_PATH.'/updates/'.$file;
					// timestamp the update
					self::$data['vidcura_update_timestamp'] = time(); // req's new data structure
					// store the new version after the update
					self::$data['vidcura_version'] = $this_update_ver; // req's new data structure
					update_option( self::db_key, self::$data );
				}
			}
		}

		// If this is empty, then it is a first install, so we timestamp it
		// and prep the data structure
		if (empty(VidCura::$data)) {
			// TODO: run tests
			VidCura::$data['vidcura_installation_timestamp'] = time();
			VidCura::$data['vidcura_version'] = VidCura::get_current_version();
			VidCura::$data['export_info'] = array(
				'title'   => 'VidCura Site',
				'author'   => get_option('admin_email', 'admin@vidcura.org'),
				'url'    => get_option('siteurl', 'http://vidcura.org/'),
				'description' => __('This site was created in part using VidCura', VIDCURA_TXTDOMAIN),
			);
			update_option(VidCura::db_key, VidCura::$data);
		}
	}


	//------------------------------------------------------------------------------
	/**
	 * Load VidCura data from database.
	 */
	public static function load_data() {
		self::$data = get_option( VidCura::db_key, array() );
	}
	

	//------------------------------------------------------------------------------
	/**
	 * When given a PHP file name relative to the CCTM_PATH, e.g. '/config/image_search_parameters.php',
	 * this function will include that file using php include(). However, if the same file exists
	 * in the same location relative to the wp-content/uploads/cctm directory, THAT version of 
	 * the file will be used. E.g. calling load_file('test.php') will include 
	 * wp-content/uploads/cctm/test.php (if it exists); if the file doesn't exist in the uploads
	 * directory, then we'll look for the file inside the CCTM_PATH, e.g.
	 * wp-content/plugins/custom-content-type-manager/test.php 
	 *
	 * The purpose of this is to let users override certain files by placing their own in a location
	 * that is *outside* of this plugin's directory so that the user-created files will be safe
	 * from any overriting or deleting that may occur if the plugin is updated.
	 *	 
	 *
	 * Developers of 3rd party components can supply additional paths $path if they wish to load files
	 * in their components: if the $additional_path is supplied, this directory will be searched for tpl in question.
	 *
	 * To prevent directory transversing, file names may not contain '..'!
	 *
	 * @param	array|string	$files: filename relative to the path, e.g. '/config/x.php'. Should begin with "/"
	 * @param	array|string	(optional) $additional_paths: this adds one more paths to the default locations. OMIT trailing /, e.g. called via dirname(__FILE__)
	 * @param	mixed	file name used on success, false on fail.
	 */
	public static function load_file($files, $additional_paths=null) {

		if (!is_array($files)){
			$files = array($files);
		}

		if (!is_array($additional_paths)){
			$additional_paths = array($additional_paths);
		}
		
		// Populate the list of directories we will search in order. 
		$upload_dir = wp_upload_dir();
		$paths = array();
		$paths[] = $upload_dir['basedir'] .'/'.VidCura::base_storage_dir;
		$paths[] = VIDCURA_PATH;
		$paths = array_merge($paths, $additional_paths);

		// pull a file off the stack, then look for it
		$file = array_shift($files);
		
		if (preg_match('/\.\./', $file)) {
			die( sprintf(__('Invaid file name! %s  No directory traversing allowed!', VIDCURA_TXTDOMAIN), '<em>'.htmlspecialchars($file).'</em>'));
		}
		
		if (!preg_match('/\.php$/', $file)) {
			die( sprintf(__('Invaid file name! %s  Name must end with .php!', VIDCURA_TXTDOMAIN), '<em>'.htmlspecialchars($file).'</em>'));
		}		
		
		// Look through the directories in order.
		foreach ($paths as $dir) {
			if (file_exists($dir.$file)) { 
				include($dir.$file);
				return $dir.$file;
			}
		}
		
		// Try again with the remaining files... or fail.
		if (!empty($files)) {
			return self::load_file($files, $additional_paths);
		}
		else {
			return false;
		}
	}


	//------------------------------------------------------------------------------
	/**
	 * This is the grand poobah of functions for the admin pages: it routes requests
	 * to specific functions.
	 * This is the function called when someone clicks on the settings page.
	 * The job of a controller is to process requests and route them.
	 *
	 */
	public static function page_main_controller() {

		// TODO: this should be specific to the request
		if (!current_user_can('manage_options')) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		// Default Actions for each main menu item (see create_admin_menu)
		$page = self::get_value($_GET, 'page', 'vidcura');
		switch ($page) {
		case 'vidcura': // main: manifest
			$action = 'manifest';
			break;
		case 'vidcura_manifest': // manifest
			$action = 'manifest';
			break;
		case 'vidcura_theme': // theme
			$action = 'theme';
			break;
		case 'vidcura_category': // category
			$action = 'category';
			break;
		case 'vidcura_deployment': // deployment
			$action = 'deployment';
			break;
		}

		// Validation on the controller name to prevent mischief:
		if ( preg_match('/[^a-z_\-]/i', $action) ) {
			include VIDCURA_PATH.'/controllers/404.php';
			return;
		}

		$requested_page = VIDCURA_PATH.'/controllers/'.$action.'.php';

		if (file_exists($requested_page)) {
			include $requested_page;
		}
		else {
			include VIDCURA_PATH.'/controllers/404.php';
		}
		return;
	}

	
	public static function print_notices() {
		if ( !empty(VidCura::$errors) ) {
			$error_items = '';
			foreach ( VidCura::$errors as $e ) {
				$error_items .= "<li>$e</li>";
			}
			$msg = sprintf( __('The %s plugin encountered errors! It cannot load!', VIDCURA_TXTDOMAIN)
				, CCTM::name);
			printf('<div id="vidcura-warning" class="error">
				<p>
					<strong>%1$s</strong>
					<ul style="margin-left:30px;">
						%2$s
					</ul>
				</p>
				</div>'
				, $msg
				, $error_items);
		}
	}

	//------------------------------------------------------------------------------
	/**
	 * Load up a PHP file into a string via an include statement. MVC type usage here.
	 *
	 * @param string  $filename (relative to the views/ directory)
	 * @param array   $data (optional) associative array of data
	 * @param string  $path (optional) pathname. Can be overridden for 3rd party fields
	 * @return string the parsed contents of that file
	 */
	public static function load_view($filename, $data=array(), $path=null) {
		if (empty($path)) {
			$path = VIDCURA_PATH . '/views/';
		}
		if (is_file($path.$filename)) {
			ob_start();
			include $path.$filename;
			return ob_get_clean();
		}
		die('View file does not exist: ' .$path.$filename);
	}


	public static function parse_request(&$wp)	{
		$token = get_option(self::site_token);
		
		// Validation on the controller name to prevent mischief:
		if ( array_key_exists('target', $wp->query_vars ) &&
				 array_key_exists('action', $wp->query_vars ) &&
				 array_key_exists('token', $wp->query_vars ) && 
				 ($token == $wp->query_vars['token'] || current_user_can('administrator'))) {
			list($action, $parent_id) = explode('-', $wp->query_vars['action']);
			$requested_page = VIDCURA_PATH.'/controllers/' . $wp->query_vars['target'] . '.' . $action . '.php';

			if (file_exists($requested_page)) {
				include $requested_page;
			}
			else {
				include VIDCURA_PATH.'/controllers/404.php';
			}
		}
		
		return;
	}


	//------------------------------------------------------------------------------
	/**
	 * Print warnings if there are any that haven't been dismissed
	 */
	public static function print_warnings() {

		$warning_items = '';

		// Check for warnings
		if ( !empty(self::$data['warnings']) ) {
			//   print '<pre>'. print_r(self::$data['warnings']) . '</pre>'; exit;
			$clear_warnings_url = sprintf(
				'<a href="?page=vidcura&a=clear_warnings&_wpnonce=%s" title="%s" class="button">%s</a>'
				, wp_create_nonce('vidcura_clear_warnings')
				, __('Dismiss all warnings', VIDCURA_TXTDOMAIN)
				, __('Dismiss Warnings', VIDCURA_TXTDOMAIN)
			);
			$warning_items = '';
			foreach ( self::$data['warnings'] as $warning => $viewed ) {
				if ($viewed == 0) {
					$warning_items .= "<li>$warning</li>";
				}
			}
		}

		if ($warning_items) {
			$msg = __('VidCura encountered the following warnings:', VIDCURA_TXTDOMAIN);
			printf('<div id="vidcura-warning" class="error">
				<p>
					<strong>%s</strong>
					<ul style="margin-left:30px;">
						%s
					</ul>
				</p>
				<p>%s</p>
				</div>'
				, $msg
				, $warning_items
				, $clear_warnings_url
			);
		}
	}


	//------------------------------------------------------------------------------
	public static function register_custom_fields() {
		add_meta_box('vc_media_url', 'Media Url', 'vc_media_url', 'assets', 'normal', 'default');
		add_meta_box('vc_content_type', 'Content Type', 'vc_content_type', 'assets', 'normal', 'default');
		add_meta_box('vc_stream_format', 'Stream Format', 'vc_stream_format', 'assets', 'normal', 'default');
		add_meta_box('vc_short_description', 'Short Description', 'vc_short_description', 'assets', 'normal', 'default');
		add_meta_box('vc_description', 'Description', 'vc_description', 'assets', 'normal', 'default');
		add_meta_box('vc_hd_poster_url', 'HD Poster Url', 'vc_hd_poster_url', 'assets', 'normal', 'default');
		add_meta_box('vc_sd_poster_url', 'SD Poster Url', 'vc_sd_poster_url', 'assets', 'normal', 'default');
	}


	//------------------------------------------------------------------------------
	public static function register_custom_post_types() {

		$def = array(
			'label' => __('Assets', 'globe'),
			'singular_label' => __('Asset', 'globe'),
			'public' => false,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => true,
			'supports' => array('title'),
			'taxonomies' => array('category', 'post_tag'),
			'register_meta_box_cb' => 'VidCura::register_custom_fields'
		);
	
		register_post_type('assets', $def);
		
		// Added per issue 50
		// http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=50
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	
	public static function save_custom_post_types($post_id) {
		// verify nonce
		if (!wp_verify_nonce($_POST['asset_meta_noncename'], plugin_basename(dirname(__FILE__) . '/functions.php'))) {
			return $post_id;
		}
		
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
	
		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		
		$fields = array('media_url', 'content_type', 'stream_format', 'short_description', 'description', 'hd_poster_url', 'sd_poster_url');
		foreach ($fields as $field) {
			$old = get_post_meta($post_id, $field, true);
			$new = $_POST[$field];
	
			if ($new && $new != $old) {
				update_post_meta($post_id, $field, $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field, $old);
			}
		}	
	}


	//------------------------------------------------------------------------------
	/**
	 * Recursively removes all quotes from $_POSTED data if magic quotes are on
	 * http://algorytmy.pl/doc/php/function.stripslashes.php
	 *
	 * @param array   possibly nested
	 * @param unknown $value
	 * @return array clensed of slashes
	 */
	public static function stripslashes_deep($value) {
		if ( is_array($value) ) {
			$value = array_map( 'VidCura::'. __FUNCTION__, $value);
		}
		else {
			$value = stripslashes($value);
		}
		return $value;
	}


	//------------------------------------------------------------------------------
	/**
	 * Recursively strips tags from all inputs, including nested ones.
	 *
	 * @param unknown $value
	 * @return array the input array, with tags stripped out of each value.
	 */
	public static function striptags_deep($value) {
		if ( is_array($value) ) {
			$value = array_map('VidCura::'. __FUNCTION__, $value);
		}
		else {
			$value = strip_tags($value, self::$allowed_html_tags);
		}
		return $value;
	}
	
}


/*EOF VidCura.php*/