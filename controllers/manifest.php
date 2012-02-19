<?php
/*------------------------------------------------------------------------------
Create the channel manifest.
------------------------------------------------------------------------------*/
if ( ! defined('VIDCURA_PATH')) exit('No direct script access allowed');
if (!current_user_can('administrator')) exit('Admins only.');
require_once(VIDCURA_PATH.'/includes/VidCura_Manifest.php');


$data=array();
$data['page_title'] = __('Update Channel Manifest', VIDCURA_TXTDOMAIN);

$data['action_name']  = 'vidcura_update_manifest';
$data['nonce_name']  = 'vidcura_update_manifest_nonce';

if (!empty($_POST) && check_admin_referer($data['action_name'], $data['nonce_name'])) {
	$sanitized_vals = VidCura::striptags_deep($_POST);

	// WP always adds slashes: see http://kovshenin.com/archives/wordpress-and-magic-quotes/
	$sanitized_vals = VidCura::stripslashes_deep($sanitized_vals);

	$error = array();
	
	$title = $sanitized_vals['title'];
	$subtitle = $sanitized_vals['subtitle'];
	$mm_icon_focus_hd = $sanitized_vals['mm_icon_focus_hd'];
	$mm_icon_side_hd = $sanitized_vals['mm_icon_side_hd'];
	$mm_icon_focus_sd = $sanitized_vals['mm_icon_focus_sd'];
	$mm_icon_side_sd = $sanitized_vals['mm_icon_side_sd'];
	
	if ($title == '')
	{
		$error['title'] = 'The title cannot be empty.'; 
	}
	
	if ($subtitle == '')
	{
		$error['subtitle'] = 'The subtitle cannot be empty.'; 
	}		

	if (empty($error)) {
		VidCura_Manifest::save_manifest($sanitized_vals);
	}
	else {
		$data['error']  = $error;
		$data['manifest'] = $sanitized_vals;
	}
}

if (!isset($data['manifest'])) $data['manifest'] = VidCura_Manifest::get_manifest();
$data['content'] = VidCura::load_view('manifest.php', $data);
print VidCura::load_view('templates/default.php', $data);
/*EOF*/