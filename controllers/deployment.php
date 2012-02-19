<?php
/*------------------------------------------------------------------------------
Deployment.
------------------------------------------------------------------------------*/
if ( ! defined('VIDCURA_PATH')) exit('No direct script access allowed');
if (!current_user_can('administrator')) exit('Admins only.');


$data=array();
$data['page_title'] = __('Channel Deployment', VIDCURA_TXTDOMAIN);

$data['action_name']  = 'vidcura_deployment';
$data['nonce_name']  = 'vidcura_deployment_nonce';

$data['content'] = VidCura::load_view('deployment.php', $data);
print VidCura::load_view('templates/default.php', $data);
/*EOF*/