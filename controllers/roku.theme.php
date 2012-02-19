<?php
/*------------------------------------------------------------------------------
Roku Theme API call.
------------------------------------------------------------------------------*/
require_once(VIDCURA_PATH.'/includes/VidCura_Theme.php');

$data = array();
if (!isset($data['theme'])) $data['theme'] = VidCura_Theme::get_theme();
echo VidCura::load_view('roku.theme.php', $data);
exit;