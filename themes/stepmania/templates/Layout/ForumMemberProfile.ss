<header>
	<h1>$Title</h1>
</header>
<article>
	<% include ForumHeader %>
	$Content
	<% if Form %>
		<div id="UserProfile">
			$Form
		</div>
	<% end_if %>
	<% include ForumFooter %>
</article>