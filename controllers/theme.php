<?php
/*------------------------------------------------------------------------------
Create a channel theme.
------------------------------------------------------------------------------*/
if (!defined('VIDCURA_PATH')) exit('No direct script access allowed');
if (!current_user_can('administrator')) exit('Admins only.');
require_once(VIDCURA_PATH.'/includes/VidCura_Theme.php');


$data=array();
$data['page_title'] = __('Update Channel Theme', VIDCURA_TXTDOMAIN);

$data['action_name']  = 'vidcura_update_theme';
$data['nonce_name']  = 'vidcura_update_theme_nonce';

if (!empty($_POST) && check_admin_referer($data['action_name'], $data['nonce_name'])) {
	$sanitized_vals = VidCura::striptags_deep($_POST);

	// WP always adds slashes: see http://kovshenin.com/archives/wordpress-and-magic-quotes/
	$sanitized_vals = VidCura::stripslashes_deep($sanitized_vals);

	$error = array();
	
	$overhang_offset_sd_x = $sanitized_vals['overhang_offset_sd_x'];
	$overhang_offset_sd_y = $sanitized_vals['overhang_offset_sd_y'];
	$overhang_offset_hd_x = $sanitized_vals['overhang_offset_hd_x'];
	$overhang_offset_hd_y = $sanitized_vals['overhang_offset_hd_y'];
	$overhang_logo_sd = $sanitized_vals['overhang_logo_sd'];
	$overhang_slice_sd = $sanitized_vals['overhang_slice_sd'];
	$overhang_logo_hd = $sanitized_vals['overhang_logo_hd'];
	$overhang_slice_hd = $sanitized_vals['overhang_slice_hd'];
	$breadcrumb_text_right = $sanitized_vals['breadcrumb_text_right'];
	$breadcrumb_text_left = $sanitized_vals['breadcrumb_text_left'];
	$background_color = $sanitized_vals['background_color'];
			
	if ($overhang_offset_sd_x < 0 || $overhang_offset_sd_x > 1280)
	{
		$error['overhang_offset_sd_x'] = 'You must enter an integer (max: 720).'; 
	}
	
	if ($overhang_offset_sd_y < 0 || $overhang_offset_sd_y > 480)
	{
		$error['overhang_offset_sd_y'] = 'You must enter an integer (max: 480).'; 
	}
	
	if ($overhang_offset_hd_x < 0 || $overhang_offset_hd_x > 1280)
	{
		$error['overhang_offset_hd_x'] = 'You must enter an integer (max: 1280).'; 
	}
	
	if ($overhang_offset_hd_y < 0 || $overhang_offset_hd_y > 720)
	{
		$error['overhang_offset_hd_y'] = 'You must enter an integer (max: 720).'; 
	}
	
	if (!preg_match('/\#[0-9A-F]{6}/', $breadcrumb_text_right, $matches))
	{
		$error['breadcrumb_text_right'] = 'You must enter an hex value (ex: #FFFFFF).'; 
	}
	
	if (!preg_match('/\#[0-9A-F]{6}/', $breadcrumb_text_left, $matches))
	{
		$error['breadcrumb_text_left'] = 'You must enter an hex value (ex: #FFFFFF).'; 
	}
	
	if (!preg_match('/\#[0-9A-F]{6}/', $background_color, $matches))
	{
		$error['background_color'] = 'You must enter an hex value (ex: #FFFFFF).'; 
	}

	if (empty($error)) {
		VidCura_Theme::save_theme($sanitized_vals);
	}
	else {
		$data['error']  = $error;
		$data['theme'] = $sanitized_vals;
	}
}

if (!isset($data['theme'])) $data['theme'] = VidCura_Theme::get_theme();
$data['content'] = VidCura::load_view('theme.php', $data);
print VidCura::load_view('templates/default.php', $data);
/*EOF*/