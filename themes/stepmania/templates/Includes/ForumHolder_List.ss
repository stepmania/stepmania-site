<div class="forum-subforum forum-flex">
	<div class="forum-flex-left subforum-title">
		<a class="topic-title" href="$Link">$Title</a>
		<% if Content || Moderators %>
			<div class="summary">
				<p>$Content.LimitCharacters(80)</p>
			<% if Moderators %>
				<p>Moderators: <% loop Moderators %>
				<a href="$Link">$Nickname</a>
				<% if not Last %>, <% end_if %><% end_loop %></p>
			<% end_if %>
			</div>
		<% end_if %>
	</div>
	<div class="forum-flex-mid subforum-stats">
		<p>Threads: $NumTopics<p>
		<p>Posts: $NumPosts</p>
	</div>
	<div class="forum-flex-right subforum-latest">
		<% if LatestPost %>
			<% with LatestPost %>
				<p class="post-title"><a href="$Link">$Title.LimitCharacters(140)</a> <% with Author %>by <% if Link %><a href="$Link"><% if Nickname %>$Nickname<% else %>Anon<% end_if %></a><% else %><span>Anon</span><% end_if %><% end_with %></p>
				<p class="post-date">$Created.Ago</p>
			<% end_with %>
		<% end_if %>
	</div>
</div>