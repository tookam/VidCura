<?php
/*------------------------------------------------------------------------------
Categories management.
------------------------------------------------------------------------------*/
if ( ! defined('VIDCURA_PATH')) exit('No direct script access allowed');
if (!current_user_can('administrator')) exit('Admins only.');
require_once(VIDCURA_PATH.'/includes/VidCura_Category.php');


$data=array();
$data['page_title'] = __('Update Channel Category', VIDCURA_TXTDOMAIN);

$data['action_name']  = 'vidcura_update_category';
$data['nonce_name']  = 'vidcura_update_category_nonce';

if (!empty($_POST) && check_admin_referer($data['action_name'], $data['nonce_name'])) {
	$sanitized_vals = VidCura::striptags_deep($_POST);

	// WP always adds slashes: see http://kovshenin.com/archives/wordpress-and-magic-quotes/
	$sanitized_vals = VidCura::stripslashes_deep($sanitized_vals);

	$error = array();
	
	$name = $sanitized_vals['name'];
	$short_description_line1 = $sanitized_vals['short_description_line1'];
	$hd_poster_url = $sanitized_vals['hd_poster_url'];
	$sd_poster_url = $sanitized_vals['sd_poster_url'];

	if (empty($error)) {
		VidCura_Category::save_category($_GET['cat'], $sanitized_vals);
	}
	else {
		$data['error']  = $error;
		$data['category'] = $sanitized_vals;
	}
}

if (!isset($data['category'])) $data['category'] = isset($_GET['cat']) ? VidCura_Category::get_category($_GET['cat']) : array();
$data['content'] = VidCura::load_view('category.php', $data);
print VidCura::load_view('templates/default.php', $data);
/*EOF*/