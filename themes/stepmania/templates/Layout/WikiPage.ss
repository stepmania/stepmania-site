<div class="pad">$Content
	<div class="wiki-meta">
		<% if Parent %>
		<h3 class="wiki-parent-label">Parent:</h3>
		<ul class="wiki-parent">
			<li><a href="$Parent.RelativeLink">$Parent.Title</a></li>
		</ul>
		<% end_if %>
		<% if Children %>
		<h3 class="wiki-children-label">Subpages:</h3>
		<ul class="wiki-children">
		<% loop Children %>
			<li><a href="$RelativeLink">$Title</a></li>
		<% end_loop %>
		</ul>
		<% end_if %>
	</div>
	$Form
</div>