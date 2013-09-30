<div class="banner">
	<h1>$Title</h1>
</div>
<article>
	$Content
	<div class="forum-news">
		<% if NewsForum %>
		<% with NewsForum %>
			<% if Topics %>
				<% loop Topics.Limit(15) %>
					<% include TopicListing %>
				<% end_loop %>
			<% end_if %>
		<% end_with %>
		<% else %>
		No news forum :(
		<% end_if %>
	</div>
	<% if NewsForum %>
	<p>The news forum is <a href="$Link">over here</a> if you need it!</p>
	<% end_if %>
</article>
