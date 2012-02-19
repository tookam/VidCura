<?php
/**
These are functions primarily reserved for use in the display of the asset content type fields.
*/

//------------------------------------------------------------------------------
/**
 * Get the Media Url field.
 * @return string three HTML input fields (one hidden, one text and one button) and the associated javascript to enable WP uploader
 */
function vc_media_url()
{
	global $post;
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="asset_meta_noncename" id="asset_meta_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	// Get the media url if it has already been entered
	$media_url = get_post_meta($post->ID, 'media_url', true);
	// Echo out the field
	echo '<input type="text" name="media_url" id="media_url" value="' . $media_url  . '" class="widefat" />';	
	echo '<input type="button" name="media_url_button" id="media_url_button" value="Browse" class="xsmall">';
	echo '<script type="text/javascript">
jQuery(document).ready(function($){
	$("#media_url_button").click(function() {
		window.send_to_editor = function(html) {
	 		var vidurl = $(html).attr("href");
	 		$("#media_url").val(vidurl);
	 		tb_remove();
		}
	 	
	 	tb_show("", "media-upload.php?post_id=1&amp;type=video&amp;TB_iframe=true");
	 	return false;
	});
});
</script>';
}


//------------------------------------------------------------------------------
/**
 * Get the Content Type field.
 * @return string one HTML select field
 */
function vc_content_type()
{
	global $post;
	// Get the content type if it has already been entered
	$content_type = get_post_meta($post->ID, 'content_type', true);
	// Echo out the field
	echo '<select name="content_type" id="content_type">
<option ' . selected( $content_type, 'episode', false ) . '>video</option>
<option ' . selected( $content_type, 'audio', false ) . '>audio</option>
</select>';	
}


//------------------------------------------------------------------------------
/**
 * Get the Stream Format field.
 * @return string one HTML select field
 */
function vc_stream_format()
{
	global $post;
	// Get the stream format if it has already been entered
	$stream_format = get_post_meta($post->ID, 'stream_format', true);
	// Echo out the field
	echo '<select name="stream_format" id="stream_format">
<option ' . selected( $stream_format, 'hls', false ) . '>hls</option>
<option ' . selected( $stream_format, 'mp4', false ) . '>mp4</option>
<option ' . selected( $stream_format, 'mp3', false ) . '>mp3</option>
</select>';	
}


//------------------------------------------------------------------------------
/**
 * Get the Short Description field.
 * @return string one HTML text input field
 */
function vc_short_description() {
	global $post;
	// Get the short description if it has already been entered
	$short_description = get_post_meta($post->ID, 'short_description', true);
	// Echo out the field
	echo '<input type="text" name="short_description" id="short_description" value="' . $short_description  . '" class="widefat" />';
}


//------------------------------------------------------------------------------
/**
 * Get the Description.
 * @return string one HTML textarea field
 */
function vc_description()
{
	global $post;
	// Get the description if it has already been entered
	$description = get_post_meta($post->ID, 'description', true);
	// Echo out the field
	echo '<textarea cols="60" rows="4" " style="width:97%" name="description" id="description">' . $description . '</textarea>';	
}


//------------------------------------------------------------------------------
/**
 * Get the Sd Poster Url field.
 * @return string two HTML input fields (one text and one button) and the associated javascript to enable WP uploader
 */
function vc_sd_poster_url()
{
	global $post;
	// Get the sd poster url if it has already been entered
	$sd_poster_url = get_post_meta($post->ID, 'sd_poster_url', true);
	// Echo out the field
	echo '<input type="text" name="sd_poster_url" id="sd_poster_url" value="' . $sd_poster_url  . '" class="widefat" />';
	echo '<input type="button" name="sd_poster_url_button" id="sd_poster_url_button" value="Browse" class="xsmall">';
	echo '<script type="text/javascript">
jQuery(document).ready(function($){
	$("#sd_poster_url_button").click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $("img", html).attr("src");
	 		$("#sd_poster_url").val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show("", "media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true");
	 	return false;
	});
});
</script>';
}


//------------------------------------------------------------------------------
/**
 * Get the Hd Poster Url field.
 * @return string two HTML input fields (one text and one button) and the associated javascript to enable WP uploader
 */
function vc_hd_poster_url()
{
	global $post;
	// Get the hd poster url if it has already been entered
	$hd_poster_url = get_post_meta($post->ID, 'hd_poster_url', true);
	// Echo out the field
	echo '<input type="text" name="hd_poster_url" id="hd_poster_url" value="' . $hd_poster_url  . '" class="widefat" />';	
	echo '<input type="button" name="hd_poster_url_button" id="hd_poster_url_button" value="Browse" class="xsmall">';
	echo '<script type="text/javascript">
jQuery(document).ready(function($){
	$("#hd_poster_url_button").click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $("img", html).attr("src");
	 		$("#hd_poster_url").val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show("", "media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true");
	 	return false;
	});
});
</script>';
}
