<div class="banner">
	<h1>$Title</h1>
</div>
<article class="post">
	$Content
	<ul class="subforums">
	<% loop Children %>
		<li>
			<a href="$Link">$Title</a>
			$Content
		</li>
	<% end_loop %>
	</ul>
	$Form
</article>
