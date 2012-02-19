<?php
/**
 * Library used by the theme.php controller
 */
class VidCura_Theme {

	/**
	 * This saves a serialized data structure to the db
	 *
	 * @return
	 * @param array $data associative array definition describing the theme of the channel.
	 */
	public static function save_theme($data) {
		$option = get_option( VidCura::db_key );
		$option['theme'] = $data;
		update_option( VidCura::db_key, $option );
	}


	/**
	 * This returns the definition of the channel theme stored in the db
	 *
	 * @return array associative array describing the theme of the channel
	 */	
	public static function get_theme() {
		$option = get_option( VidCura::db_key );
		if ( !isset($option['theme']['overhang_offset_sd_x']) )	$option['theme']['overhang_offset_sd_x'] = '';
		if ( !isset($option['theme']['overhang_offset_sd_y']) ) $option['theme']['overhang_offset_sd_y'] = '';
		if ( !isset($option['theme']['overhang_offset_hd_x']) )	$option['theme']['overhang_offset_hd_x'] = '';
		if ( !isset($option['theme']['overhang_offset_hd_y']) ) $option['theme']['overhang_offset_hd_y'] = '';
		if ( !isset($option['theme']['overhang_logo_sd']) ) 		$option['theme']['overhang_logo_sd'] = '';
		if ( !isset($option['theme']['overhang_slice_sd']) ) 		$option['theme']['overhang_slice_sd'] = '';
		if ( !isset($option['theme']['overhang_logo_hd']) ) 		$option['theme']['overhang_logo_hd'] = '';
		if ( !isset($option['theme']['overhang_slice_hd']) ) 		$option['theme']['overhang_slice_hd'] = '';
		if ( !isset($option['theme']['breadcrumb_text_right']) ) 		$option['theme']['breadcrumb_text_right'] = '';
		if ( !isset($option['theme']['breadcrumb_text_left']) ) 		$option['theme']['breadcrumb_text_left'] = '';
		if ( !isset($option['theme']['background_color']) ) 		$option['theme']['background_color'] = '';
		return $option['theme'];
	}	
}
/*EOF*/
