<?php
/**
 * Library used by the manifest.php controller
 */
class VidCura_Manifest {

	/**
	 * This saves a serialized data structure to the db
	 *
	 * @return
	 * @param array $data associative array definition describing the manifest of the channel.
	 */
	public static function save_manifest($data) {
		$option = get_option( VidCura::db_key );
		$option['manifest'] = $data;
		update_option( VidCura::db_key, $option );
	}


	/**
	 * This returns the definition of the channel manifest stored in the db
	 *
	 * @return array associative array describing the manifest of the channel
	 */	
	public static function get_manifest() {
		$option = get_option( VidCura::db_key );
		if ( !isset($option['manifest']['title']) ) 						$option['manifest']['title'] = '';
		if ( !isset($option['manifest']['subtitle']) ) 					$option['manifest']['subtitle'] = '';
		if ( !isset($option['manifest']['mm_icon_focus_hd']) ) 	$option['manifest']['mm_icon_focus_hd'] = '';
		if ( !isset($option['manifest']['mm_icon_side_hd']) ) 	$option['manifest']['mm_icon_side_hd'] = '';
		if ( !isset($option['manifest']['mm_icon_focus_sd']) ) 	$option['manifest']['mm_icon_focus_sd'] = '';
		if ( !isset($option['manifest']['mm_icon_side_sd']) ) 	$option['manifest']['mm_icon_side_sd'] = '';
		
		return $option['manifest'];
	}	
}
/*EOF*/
