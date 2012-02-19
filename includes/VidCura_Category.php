<?php
/**
 * Library used by the category.php controller
 */
class VidCura_Category {

	/**
	 * This saves a serialized data structure to the db
	 *
	 * @return
	 * @param int $cat_id the id of the category for which the metadata is being saved.
	 * @param array $data associative array definition describing the theme of the channel.
	 */
	public static function save_category($cat_id, $data) {
		$option = get_option( VidCura::db_key );
		$option['category'][$cat_id] = $data;
		update_option( VidCura::db_key, $option );
	}


	/**
	 * This returns the metadata associated with a category of the channel that is stored in the db
	 *
	 * @return array associative array describing the metadata associated to a category of the channel
	 * @param int $cat_id the id of the category for which the metadata is being fetched.
	 */	
	public static function get_category($cat_id) {
		$cat = get_category($cat_id, false);
		
		$option = get_option( VidCura::db_key );
		if ( !isset($option['category'][$cat_id]['name']) ) 										$option['category'][$cat_id]['name'] = $cat->name;
		if ( !isset($option['category'][$cat_id]['short_description_line1']) )	$option['category'][$cat_id]['short_description_line1'] = '';
		if ( !isset($option['category'][$cat_id]['hd_poster_url']) ) 						$option['category'][$cat_id]['hd_poster_url'] = '';
		if ( !isset($option['category'][$cat_id]['sd_poster_url']) ) 						$option['category'][$cat_id]['sd_poster_url'] = '';
		
		return $option['category'][$cat_id];
	}	
}
/*EOF*/
