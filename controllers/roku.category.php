<?php
/*------------------------------------------------------------------------------
Roku Category/Asset API call.
------------------------------------------------------------------------------*/
require_once(VIDCURA_PATH.'/includes/VidCura_Category.php');

$childcats = get_categories('parent=' . $parent_id . '&hide_empty=1');
$metadata = array();
foreach ($childcats as $childcat)
{
	$metadata[$childcat->cat_ID] = VidCura_Category::get_category($childcat->cat_ID);
}
$data['categories'] = $metadata;
$data['breadcrumb'] =  $parent_id ? get_category_parents($parent_id, false, ' >> ') : '';//$parent_id ? VidCura_Category::get_category($parent_id) : array();
$data['assets'] = get_posts( array('category' => $parent_id, 'post_type' => 'assets', 'numberposts' => 100000) );
echo empty($data['categories']) ? VidCura::load_view('roku.asset.php', $data) : VidCura::load_view('roku.category.php', $data);
exit;