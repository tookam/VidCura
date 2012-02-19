<div class="alert-message block-message info">
<p>The channel manifest specifies a set of values and images that are displayed on the top-level menu before the application has been started. At system start-up, all of the installed channels on the system are enumerated and the top level menu is configured and displayed. These attributes are configured by setting the values in the channel manifest. Note that each name=value pair must end with a newline character, or it will not be parsed by the firmware.</p>
<br />
<p>Each application provides four images for the main menu. A large, center-focus icon in both SD and HD sizes and a smaller non-focused side image in SD and HD. Images may be .png or .jpg files, but .png is required if alpha channel support is needed.</p>
</div>

<form method="post">
	<fieldset>
		<legend></legend>
		<div class="clearfix <?php if (isset($data['error']['title'])): ?>error<?php endif ?>">
			<label for="title">Title</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['manifest']['title'] ?>" name="title" id="title" class="large <?php if (isset($data['error']['title'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['title'])): ?><span class="help-inline"><?php echo $data['error']['title'] ?></span><?php endif ?>
      	<span class="help-block">
        Name of your channel for display under the focus icon (ex: deviantART)
        </span>
      </div>
    </div>
    
		<div class="clearfix <?php if (isset($data['error']['subtitle'])): ?>error<?php endif ?>">
			<label for="subtitle">Subtitle</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['manifest']['subtitle'] ?>" size="30" name="subtitle" id="subtitle" class="large <?php if (isset($data['error']['subtitle'])): ?>error<?php endif ?>">
      	<?php if (isset($data['error']['subtitle'])): ?><span class="help-inline"><?php echo $data['error']['subtitle'] ?></span><?php endif ?>
      	<span class="help-block">
        Short promotional description of your application for display beneath the title (ex: www.deviantart.com Media RSS Feeds)
        </span>
      </div>
    </div>  

		<div class="clearfix <?php if (isset($data['error']['mm_icon_focus_hd'])): ?>error<?php endif ?>">
    	<label for="mm_icon_focus_hd">MM Icon Focus HD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['manifest']['mm_icon_focus_hd'] ?>" name="mm_icon_focus_hd" id="mm_icon_focus_hd" class="large"><input type="button" name="mm_icon_focus_hd_button" id="mm_icon_focus_hd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['mm_icon_focus_hd'])): ?><span class="help-inline"><?php echo $data['error']['mm_icon_focus_hd'] ?></span><?php endif ?>
      	<span class="help-block">
        Large focused image for HD Size: 336 x 210 (<a title="Sample" href="<?php print VIDCURA_URL; ?>/images/mm_icon_focus_hd.png" class="group1 cboxElement">sample</a>)
        </span>
        <?php if ($url = $data['manifest']['mm_icon_focus_hd']): ?>	
        	<img alt="" src="<?php echo $url ?>" width="336" class="thumbnail">
      	<?php endif ?>
      </div>
    </div> 

		<div class="clearfix <?php if (isset($data['error']['mm_icon_side_hd'])): ?>error<?php endif ?>">
    	<label for="mm_icon_side_hd">MM Icon Side HD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['manifest']['mm_icon_side_hd'] ?>" name="mm_icon_side_hd" id="mm_icon_side_hd" class="large"><input type="button" name="mm_icon_side_hd_button" id="mm_icon_side_hd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['mm_icon_side_hd'])): ?><span class="help-inline"><?php echo $data['error']['mm_icon_side_hd'] ?></span><?php endif ?>
      	<span class="help-block">
        Small side image for HD Size: 108 x 69 (<a title="Sample" href="<?php print VIDCURA_URL; ?>/images/mm_icon_side_hd.png" class="group1 cboxElement">sample</a>)
        </span>
        <?php if ($url = $data['manifest']['mm_icon_side_hd']): ?>	
        	<img alt="" src="<?php echo $url ?>" width="108" class="thumbnail">
      	<?php endif ?>
      </div>
    </div>   

		<div class="clearfix <?php if (isset($data['error']['mm_icon_focus_sd'])): ?>error<?php endif ?>">
    	<label for="mm_icon_focus_sd">MM Icon Focus SD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['manifest']['mm_icon_focus_sd'] ?>" name="mm_icon_focus_sd" id="mm_icon_focus_sd" class="large"><input type="button" name="mm_icon_focus_sd_button" id="mm_icon_focus_sd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['mm_icon_focus_sd'])): ?><span class="help-inline"><?php echo $data['error']['mm_icon_focus_sd'] ?></span><?php endif ?>
      	<span class="help-block">
        Large focused image for SD Size: 248 x 140 (<a title="Sample" href="<?php print VIDCURA_URL; ?>/images/mm_icon_focus_sd.png" class="group1 cboxElement">sample</a>)
        </span>
        <?php if ($url = $data['manifest']['mm_icon_focus_sd']): ?>	
        	<img alt="" src="<?php echo $url ?>" width="248" class="thumbnail">
      	<?php endif ?>
      </div>
    </div> 

		<div class="clearfix <?php if (isset($data['error']['mm_icon_side_sd'])): ?>error<?php endif ?>">
    	<label for="mm_icon_side_sd">MM Icon Side SD</label>
      <div class="input">
      	<input type="text" value="<?php echo $data['manifest']['mm_icon_side_sd'] ?>" name="mm_icon_side_sd" id="mm_icon_side_sd" class="large"><input type="button" name="mm_icon_side_sd_button" id="mm_icon_side_sd_button" value="Browse" class="xsmall">
      	<?php if (isset($data['error']['mm_icon_side_sd'])): ?><span class="help-inline"><?php echo $data['error']['mm_icon_side_sd'] ?></span><?php endif ?>
      	<span class="help-block">
        Small side image for SD Size: 80 x 46 (<a title="Sample" href="<?php print VIDCURA_URL; ?>/images/mm_icon_side_sd.png" class="group1 cboxElement">sample</a>)
        </span>
        <?php if ($url = $data['manifest']['mm_icon_side_sd']): ?>	
        	<img alt="" src="<?php echo $url ?>" width="80" class="thumbnail">
      	<?php endif ?>
      </div>
    </div> 
    
	<div class="actions">
		<input type="submit" value="Save changes" class="btn primary">
  </div>	
  
  <?php wp_nonce_field($data['action_name'], $data['nonce_name']); ?>
</form>

<script>
jQuery(document).ready(function($){
	//Examples of how to assign the ColorBox event to elements
	$(".group1").colorbox({});
	
	$('#mm_icon_focus_hd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#mm_icon_focus_hd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
	 
	 
	$('#mm_icon_side_hd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#mm_icon_side_hd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
	 
	 
	
	$('#mm_icon_focus_sd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#mm_icon_focus_sd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
	 
	 
	$('#mm_icon_side_sd_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#mm_icon_side_sd').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
});
</script>		
