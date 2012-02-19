<div class="alert-message block-message warning">
	<a href="#" class="close">×</a>
	<p>When a Channel is created, a developer has an <strong>immutable</strong> choice between a public or private channel. There will be no opportunity to change your mind later. Public channels can be made available to end users through the Roku Channel Store after being approved by Roku. Private channels will only be made available to end users via a channel code, but do not require approval by Roku.</p>
	<p>The <strong><a class="btn" target="_blank" href="<?php print VIDCURA_URL; ?>/docs/ChannelPackagingAndPublishing.pdf">Channel Packaging and Publishing Guide</a></strong> has complete information about the procedure to follow to publish your channel publicly or privately.</p>
</div>

<div class="alert-message block-message error">
	<a href="#" class="close">×</a>
	<p><strong>Before publishing your channel as a public or private channel, it is recommended to test it first using your Roku!</strong> 
	<br />The process is called sideloading:</p>
	<ul>
		<li>Save the provided <strong>Channel zip file</strong> to your computer using the link below</li>
		<li>Go to the Roku Home screen</li>
		<li>Click the following sequence on your remote to enable testing mode so that you can install your channel:
			<ol>
				<li>Home button x 3</li>
				<li>Up x 2</li>
				<li>Right, Left, Right, Left, Right</li>
			</ol>
			You will be presented with the Developer Settings page where you can enable developer mode on the box. 
		</li>
		<li>Go to the Roku Home screen and navigate to “Roku Player Settings”, “player info” to find the IP address of your box.</li>
		<li>From your development workstation, open a standard web browser and type the following URL: http://&lt;IP address of your box&gt; for example: http://192.168.1.100</li>
		<li>Select Browse... And select your <strong>Channel zip file</strong></li>
		<li>Select Install (or Replace)</li>
	</ul>
	The channel should now appear on the far right of your Roku home screen
	<div class="alert-actions">
		<a href="/api/roku/-/deployment-0" class="btn">Download Channel zip file</a>
	</div>
</div>