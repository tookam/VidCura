<?php
/*------------------------------------------------------------------------------
This is run on the admin_init event: this is what generates the menus in 
the WP dashboard.  The default behavior here makes the menu show only for 
site administrators or for super_admin's (in a multi-site install).
------------------------------------------------------------------------------*/

// Adjust menus for multi-site: menu should only be visible to the super_admin
$capability = 'manage_options';

if (defined('WP_ALLOW_MULTISITE') && WP_ALLOW_MULTISITE == true && is_super_admin()) {
	$capability = 'manage_network';
}

// Main menu item
add_menu_page(
	__('Manage VidCura', VIDCURA_TXTDOMAIN),
	__('VidCura', VIDCURA_TXTDOMAIN),      		// menu title
	$capability,															// capability
	'vidcura',																// menu-slug (should be unique)
	'VidCura::page_main_controller',       		// callback function
	VIDCURA_URL .'/images/favicon.ico',       // Icon
	self::menu_position												// menu position
);

add_submenu_page(
	'vidcura',          																// parent slug (menu-slug from add_menu_page call)
	__('VidCura Channel Manifest', VIDCURA_TXTDOMAIN),
	__('Manifest', VIDCURA_TXTDOMAIN),   								// menu title
	$capability,																				// capability
	'vidcura',																					// menu_slug: cf = custom fields
	'VidCura::page_main_controller'											// callback function
);

add_submenu_page(
	'vidcura',
	__('VidCura Channel Theme', VIDCURA_TXTDOMAIN),
	__('Theme', VIDCURA_TXTDOMAIN),
	$capability,
	'vidcura_theme',
	'VidCura::page_main_controller'
);

add_submenu_page(
	'vidcura',
	__('VidCura Category Management', VIDCURA_TXTDOMAIN),
	__('Categories Metadata', VIDCURA_TXTDOMAIN),
	$capability,
	'vidcura_category',
	'VidCura::page_main_controller'
);

add_submenu_page(
	'vidcura',
	__('VidCura Channel Deployment', VIDCURA_TXTDOMAIN),
	__('Deployment', VIDCURA_TXTDOMAIN),
	$capability,
	'vidcura_deployment',
	'VidCura::page_main_controller'
);


/*EOF*/
