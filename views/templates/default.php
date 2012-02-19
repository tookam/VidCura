<style>
input[type="text"], input[type="password"], textarea {
  -moz-box-sizing: content-box;
}
div.updated, div.error {
  margin: 5px 15px 2px;
  padding: 10px;
}
div.colorpicker_field input {
	width: 30px;
}
div.colorpicker_hex input {
	width: 50px;
}
</style>

<div class="content">
	<div class="row">
		<div class="span14">
			<?php 
			/* ----------------- MAIN PAGE CONTENT -------------------------------*/
			print $data['content']; 
			/* -------------------------------------------------------------------*/
			?>
		</div>
	</div>

	<?php /*--------------- FOOTER --------------------------*/ ?>
	<div id="cctm_footer">
		<p style="margin:10px;">
			<span class="cctm-link">
				<a href="http://vidcura.org/" target="_blank"><?php _e('Support this Plugin', VIDCURA_TXTDOMAIN); ?></a>
			</span>
			<span class="cctm-link">
				<a href="http://vidcura.org/forums/" target="_blank"><?php _e('Help', VIDCURA_TXTDOMAIN); ?></a>
			</span>
			<span class="cctm-link">
				<a href="http://vidcura.org/forums/" target="_blank"><?php _e('Forums', VIDCURA_TXTDOMAIN); ?></a>
			</span>
		</p>
	</div>
</div>	