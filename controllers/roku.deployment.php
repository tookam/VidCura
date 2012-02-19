<?php
/*------------------------------------------------------------------------------
Roku Deployment API call.
------------------------------------------------------------------------------*/
  
function recursive_copy($source, $dest, $diff_dir = '') {
		$source_handle = opendir($source);
		if(!$diff_dir)
						$diff_dir = $source;
	 
		mkdir($dest . '/' . $diff_dir);
	 
		while($res = readdir($source_handle)) {
				if($res == '.' || $res == '..')
						continue;
				if (is_dir($source . '/' . $res)) {
						recursive_copy($source . '/' . $res, $dest, $diff_dir . '/' . $res);
				} else {
						copy($source . '/' . $res, $dest . '/' . $diff_dir . '/' . $res);
				}
		}
}   

function recursive_zip($source, $destination) {	
		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
				return false;
		}

		$source = str_replace('\\', '/', realpath($source));

		if (is_dir($source) === true) {
				$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

				foreach ($files as $file){
						$file = str_replace('\\', '/', realpath($file));

						if (is_dir($file) === true) {
								$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
						}
						else if (is_file($file) === true) {
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
						}
				}
		}
		else if (is_file($source) === true) {
				$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
}


$data=array();
$data['page_title'] = __('Channel Deployment', VIDCURA_TXTDOMAIN);

$data['action_name']  = 'vidcura_deployment';
$data['nonce_name']  = 'vidcura_deployment_nonce';

// token
$token = get_option(VidCura::site_token);
if (empty($token)) {
	$token = md5(wp_salt());
	update_option(VidCura::site_token, $token);
}

require_once(VIDCURA_PATH.'/includes/VidCura_Theme.php');
require_once(VIDCURA_PATH.'/includes/VidCura_Manifest.php');

$data['manifest'] = VidCura_Manifest::get_manifest();
$data['theme'] = VidCura_Theme::get_theme();

$target = md5(rand(0, 123456) . time());
recursive_copy(VIDCURA_PATH . '/channel', '/tmp', $target);
		
// manifest
$mm_icon_focus_hd = $data['manifest']['mm_icon_focus_hd'];
$mm_icon_side_hd = $data['manifest']['mm_icon_side_hd']; 
$mm_icon_focus_sd = $data['manifest']['mm_icon_focus_sd']; 
$mm_icon_side_sd = $data['manifest']['mm_icon_side_sd'];  
if (!empty($mm_icon_focus_hd)) file_put_contents('/tmp/' . $target . '/images/' . urlencode($mm_icon_focus_hd), file_get_contents($mm_icon_focus_hd));
if (!empty($mm_icon_side_hd)) file_put_contents('/tmp/' . $target . '/images/' . urlencode($mm_icon_side_hd), file_get_contents($mm_icon_side_hd));
if (!empty($mm_icon_focus_sd)) file_put_contents('/tmp/' . $target . '/images/' . urlencode($mm_icon_focus_sd), file_get_contents($mm_icon_focus_sd));
if (!empty($mm_icon_side_sd)) file_put_contents('/tmp/' . $target . '/images/' . urlencode($mm_icon_side_sd), file_get_contents($mm_icon_side_sd));

$title = $data['manifest']['title'];
$subtitle = $data['manifest']['subtitle'];
$manifest = "title=" . (empty($title) ? 'Please update the Manifest section' : $title) . "\n";
if (!empty($subtitle)) $manifest .= "subtitle=" . $subtitle . "\n";
if (!empty($mm_icon_focus_hd)) $manifest .= "mm_icon_focus_hd=pkg:/images/" . urlencode($mm_icon_focus_hd) . "\n";
if (!empty($mm_icon_side_hd)) $manifest .= "mm_icon_side_hd=pkg:/images/" . urlencode($mm_icon_side_hd) . "\n";
if (!empty($mm_icon_focus_sd)) $manifest .= "mm_icon_focus_sd=pkg:/images/" . urlencode($mm_icon_focus_sd) . "\n";
if (!empty($mm_icon_side_sd)) $manifest .= "mm_icon_side_sd=pkg:/images/" . urlencode($mm_icon_side_sd) . "\n";
$manifest .= "major_version=1\n";
$manifest .= "minor_version=0\n";
$manifest .= "build_version=" . time() . "\n";
file_put_contents('/tmp/' . $target . '/manifest', $manifest);

// script
$main_script = file_get_contents('/tmp/' . $target . '/source/appMain.brs');
$main_script = str_replace(
									array('<AUTH_TOKEN>', '<BASE_URL>'), 
									array($token, 'http://' . $_SERVER['HTTP_HOST']), 
							 $main_script);
file_put_contents('/tmp/' . $target . '/source/appMain.brs', $main_script);

recursive_zip('/tmp/' . $target . '/', '/tmp/' . $target . '.zip');

header('Pragma: public');
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' . $target . '.zip"');
readfile('/tmp/' . $target . '.zip');
exit;