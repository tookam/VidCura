<div class="alert-message block-message info">
<p>Establishing a set of artwork and colors allows you to specify a theme for your channel. If these values are not set, the channel will use the default theme values.</p>
</div>

<form method="post">
	<fieldset>
		<legend></legend>
		<div class="clearfix <?php if (isset($data['error']['overhang_offset_sd_x'])): ?>error<?php endif ?>">
			<label for="overhang_offset_sd_x">Overhang Offset SD X</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_offset_sd_x'] ?>" size="30" name="overhang_offset_sd_x" id="overhang_offset_sd_x" class="small <?php if (isset($data['error']['overhang_offset_sd_x'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['overhang_offset_sd_x'])): ?><span class="help-inline"><?php echo $data['error']['overhang_offset_sd_x'] ?></span><?php endif ?>
      	<span class="help-block">
        Offset in pixels from the top-left origin of the display. Range 0 to 720.
        </span>
      </div>
    </div>

		<div class="clearfix <?php if (isset($data['error']['overhang_offset_sd_y'])): ?>error<?php endif ?>">
			<label for="overhang_offset_sd_y">Overhang Offset SD Y</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_offset_sd_y'] ?>" size="30" name="overhang_offset_sd_y" id="overhang_offset_sd_y" class="small <?php if (isset($data['error']['overhang_offset_sd_y'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['overhang_offset_sd_y'])): ?><span class="help-inline"><?php echo $data['error']['overhang_offset_sd_y'] ?></span><?php endif ?>
      	<span class="help-block">
        Offset in pixels from the top-left origin of the display. Range 0 to 480.
        </span>
      </div>
    </div>  

		<div class="clearfix <?php if (isset($data['error']['overhang_logo_sd'])): ?>error<?php endif ?>">
    	<label for="overhang_logo_sd">Overhang Logo SD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_logo_sd'] ?>" name="overhang_logo_sd" id="overhang_logo_sd" class="large"><input type="button" name="overhang_logo_sd_button" id="overhang_logo_sd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['overhang_logo_sd'])): ?><span class="help-inline"><?php echo $data['error']['overhang_logo_sd'] ?></span><?php endif ?>
      	<span class="help-block">
        Small application logo formatted for display in overhang top left (<a title="Sample" href="<?php print VIDCURA_URL; ?>/images/Logo_Overhang_Roku_SDK_SD43.png" class="group1 cboxElement">sample</a>)
        </span>
        <?php if ($url = $data['theme']['overhang_logo_sd']): ?>	
        	<img alt="" src="<?php echo $url ?>" max-width="500" class="thumbnail">
      	<?php endif ?>
      </div>
    </div> 

		<div class="clearfix <?php if (isset($data['error']['overhang_slice_sd'])): ?>error<?php endif ?>">
    	<label for="overhang_slice_sd">Overhang Slice SD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_slice_sd'] ?>" name="overhang_slice_sd" id="overhang_slice_sd" class="large"><input type="button" name="overhang_slice_sd_button" id="overhang_slice_sd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['overhang_slice_sd'])): ?><span class="help-inline"><?php echo $data['error']['overhang_slice_sd'] ?></span><?php endif ?>
      	<span class="help-block">
        Overhang slice graphic (thin piece of top of screen border, <a title="Sample" href="<?php print VIDCURA_URL; ?>/images/Overhang_BackgroundSlice_Blue_SD43.png" class="group1 cboxElement">sample</a>).
        </span>
        <?php if ($url = $data['theme']['overhang_slice_sd']): ?>	
        	<img alt="" src="<?php echo $url ?>" width="10" class="thumbnail">
      	<?php endif ?>
      </div>
    </div> 
    
		<div class="clearfix <?php if (isset($data['error']['overhang_offset_hd_x'])): ?>error<?php endif ?>">
			<label for="overhang_offset_hd_x">Overhang Offset HD X</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_offset_hd_x'] ?>" size="30" name="overhang_offset_hd_x" id="overhang_offset_hd_x" class="small <?php if (isset($data['error']['overhang_offset_hd_x'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['overhang_offset_hd_x'])): ?><span class="help-inline"><?php echo $data['error']['overhang_offset_hd_x'] ?></span><?php endif ?>
      	<span class="help-block">
        Offset in pixels from the top-left origin of the display. Range 0 to 1280.
        </span>
      </div>
    </div>

		<div class="clearfix <?php if (isset($data['error']['overhang_offset_hd_y'])): ?>error<?php endif ?>">
			<label for="overhang_offset_hd_y">Overhang Offset HD Y</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_offset_hd_y'] ?>" size="30" name="overhang_offset_hd_y" id="overhang_offset_hd_y" class="small <?php if (isset($data['error']['overhang_offset_hd_y'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['overhang_offset_hd_y'])): ?><span class="help-inline"><?php echo $data['error']['overhang_offset_hd_y'] ?></span><?php endif ?>
      	<span class="help-block">
        Offset in pixels from the top-left origin of the display. Range 0 to 720.
        </span>
      </div>
    </div>  

		<div class="clearfix <?php if (isset($data['error']['overhang_logo_hd'])): ?>error<?php endif ?>">
    	<label for="overhang_slice_hd">Overhang Logo HD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_logo_hd'] ?>" name="overhang_logo_hd" id="overhang_logo_hd" class="large"><input type="button" name="overhang_logo_hd_button" id="overhang_logo_hd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['overhang_logo_hd'])): ?><span class="help-inline"><?php echo $data['error']['overhang_logo_hd'] ?></span><?php endif ?>
      	<span class="help-block">
        Small application logo formatted for display in overhang top left (<a title="Sample" href="<?php print VIDCURA_URL; ?>/images/Logo_Overhang_Roku_SDK_HD.png" class="group1 cboxElement">sample</a>)
        </span>
        <?php if ($url = $data['theme']['overhang_logo_hd']): ?>	
        	<img alt="" src="<?php echo $url ?>" max-width="500" class="thumbnail">
      	<?php endif ?>
      </div>
    </div> 

		<div class="clearfix <?php if (isset($data['error']['overhang_slice_hd'])): ?>error<?php endif ?>">
    	<label for="overhang_slice_hd">Overhang Slice HD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['overhang_slice_hd'] ?>" name="overhang_slice_hd" id="overhang_slice_hd" class="large"><input type="button" name="overhang_slice_hd_button" id="overhang_slice_hd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['overhang_slice_hd'])): ?><span class="help-inline"><?php echo $data['error']['overhang_slice_hd'] ?></span><?php endif ?>
      	<span class="help-block">
        Overhang slice graphic (thin piece of border at the top of the screen in HD size, <a title="Sample" href="<?php print VIDCURA_URL; ?>/images/Overhang_BackgroundSlice_Blue_HD.png" class="group1 cboxElement">sample</a>).
        </span>
        <?php if ($url = $data['theme']['overhang_slice_hd']): ?>	
        	<img alt="" src="<?php echo $url ?>" width="10" class="thumbnail">
      	<?php endif ?>
      </div>
    </div> 

		<div class="clearfix <?php if (isset($data['error']['breadcrumb_text_right'])): ?>error<?php endif ?>">
			<label for="breadcrumb_text_right">BreadcrumbTextRight</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['breadcrumb_text_right'] ?>" size="30" name="breadcrumb_text_right" id="breadcrumb_text_right" class="small <?php if (isset($data['error']['breadcrumb_text_right'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['breadcrumb_text_right'])): ?><span class="help-inline"><?php echo $data['error']['breadcrumb_text_right'] ?></span><?php endif ?>
      	<span class="help-block">
        HTML HEX Color Value
        </span>
      </div>
    </div>  

		<div class="clearfix <?php if (isset($data['error']['breadcrumb_text_left'])): ?>error<?php endif ?>">
			<label for="breadcrumb_text_left">Breadcrumb Text Left</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['breadcrumb_text_left'] ?>" size="30" name="breadcrumb_text_left" id="breadcrumb_text_left" class="small <?php if (isset($data['error']['breadcrumb_text_left'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['breadcrumb_text_left'])): ?><span class="help-inline"><?php echo $data['error']['breadcrumb_text_left'] ?></span><?php endif ?>
      	<span class="help-block">
        HTML HEX Color Value
        </span>
      </div>
    </div>  

		<div class="clearfix <?php if (isset($data['error']['background_color'])): ?>error<?php endif ?>">
			<label for="background_color">Background Color</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['theme']['background_color'] ?>" size="30" name="background_color" id="background_color" class="small <?php if (isset($data['error']['background_color'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['background_color'])): ?><span class="help-inline"><?php echo $data['error']['background_color'] ?></span><?php endif ?>
      	<div class="help-block">HTML HEX Color Value</div>
      </div>
    </div>
	</fieldset>
	
	<div class="actions">
		<input type="submit" value="Save changes" class="btn primary">
  </div>	
  
  <?php wp_nonce_field($data['action_name'], $data['nonce_name']); ?>
</form>

<script type="text/javascript">
jQuery('#breadcrumb_text_right, #breadcrumb_text_left, #background_color').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		jQuery(el).val('#' + hex);
		jQuery(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});

jQuery(document).ready(function($){
	//Examples of how to assign the ColorBox event to elements
	$(".group1").colorbox({});
	
	$('#overhang_logo_sd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#overhang_logo_sd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
	 
	 
	$('#overhang_slice_sd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#overhang_slice_sd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
	 
	 
	
	$('#overhang_logo_hd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#overhang_logo_hd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
	 
	 
	$('#overhang_slice_hd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#overhang_slice_hd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
});
</script>			
