$Content
<% if Downloads %>
<div class="downloads-container forum-flex">
	<div class="downloads forum-flex-left">
		<% loop Downloads %>
		<div class="download">
			<h2><a href="$Link">$Title</a></h2>
			<div class="download-info">
				<p>Platform: $Platform</p>
				<p>Architecture: $Architecture</p>
				<p>Size: $Size</p>
				<p>Downloads: $DownloadCount <small>($TotalDownloadSize Total)</small></p>
			</div>
		</div>
		<% end_loop %>
	</div>
	<% end_if %>
	<div class="i-cant-afford-new-dance-mats forum-flex-right">
		<h2>Donate</h2>
		<div class="content">
			<p>We, uh, can't afford hardware to test with. It kinda sucks.</p>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
				">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			Some stuff we use any donations for:
			<ul>
				<li>Getting dance mats/etc for testing</li>
				<li>Making sure we all actually have functional computers</li>
				<li>Giving something back to developers periodically for their hard work</li>
			</ul>
		</div>
	</div>
</div>
$Form