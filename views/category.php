<?php if (empty($data['category'])): ?>

	<ul>
	<?php wp_list_categories('orderby=name&order=DESC&hide_empty=0&show_count=1'); ?> 
	</ul>

<?php else: ?>
	<style>
	fieldset input {
			display: block;
			float: none;
			width: 150px;
	}
	</style>
	<p></p>
	<form method="post">
		
		<div class="clearfix">
		<label for="name">Name</label>
			<div class="input">
				<input type="text" value="<?php echo $data['category']['name'] ?>" size="30" name="name" id="name">
			</div>
		</div><!-- /clearfix -->
		
		<div class="clearfix">
		<label for="username">Short Description</label>
			<div class="input">
				<input type="text" value="<?php echo $data['category']['short_description_line1'] ?>" class="xlarge" size="30" name="short_description_line1" id="short_description_line1">
			</div>
		</div><!-- /clearfix -->
		
		<div class="clearfix">
		<label for="email">HD Poster Url</label>
			<div class="input">
				<input type="text" value="<?php echo $data['category']['hd_poster_url'] ?>" name="hd_poster_url" id="hd_poster_url" class="large"><input type="button" name="hd_poster_url_button" id="hd_poster_url_button" value="Browse" class="xsmall">
      	<span class="help-block">
				HD Video Artwork
				</span>
				<?php if ($url = $data['category']['hd_poster_url']): ?>	
					<img alt="" src="<?php echo $url ?>" height="100" class="thumbnail">
				<?php endif ?>		
			</div>
		</div><!-- /clearfix -->	
		
		<div class="clearfix">
		<label for="email">SD Poster Url</label>
			<div class="input">
				<input type="text" value="<?php echo $data['category']['sd_poster_url'] ?>" name="sd_poster_url" id="sd_poster_url" class="large"><input type="button" name="sd_poster_url_button" id="sd_poster_url_button" value="Browse" class="xsmall">
      	<span class="help-block">
				SD Video Artwork
				</span>
				<?php if ($url = $data['category']['sd_poster_url']): ?>	
					<img alt="" src="<?php echo $url ?>" height="100" class="thumbnail">
				<?php endif ?>		
			</div>
		</div><!-- /clearfix -->	
	
		<div class="actions">
			<input type="submit" value="Save changes" class="btn primary">
		</div>	
		
		<?php wp_nonce_field($data['action_name'], $data['nonce_name']); ?>
		
	</form>
<?php endif ?>

<script type="text/javascript">
jQuery(document).ready(function($){
	$('.cat-item').prepend('<a href="#" class="btn success cat-meta">Metadata</a>&nbsp&nbsp;');
	
	$('.cat-meta').click(function() {
		var href = $(this).next('a').attr('href');
		var res = new RegExp('[\\?&]cat=([^&#]*)').exec(href);
		var cat = res[1];
		window.location.replace(window.location.href.split("#")[0] + '&cat=' + cat);
	});
	
	$('#hd_poster_url_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#hd_poster_url').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
	 
	 
	$('#sd_poster_url_button').click(function() {
		window.send_to_editor = function(html) {
	 		var imgurl = $('img', html).attr('src');
	 		$('#sd_poster_url').val(imgurl);
	 		tb_remove();
		}
	 	
	 	tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
	 	return false;
	});
});
</script>			