<div class="forum-topic forum-flex <% if IsSticky || IsGlobalSticky %>sticky<% end_if %> <% if IsGlobalSticky %>global-sticky<% end_if %><% if IsReadOnly %> locked<% end_if %>">
	<div class="forum-flex-left">
		<% if IsSticky || IsGlobalSticky %><span class="sticky">Sticky: </span><% end_if %><a class="topic-title" href="$Link">$Title</a><% if IsReadOnly %><span class="locked"> (locked)</span><% end_if %>
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
	<div class="forum-flex-mid topic-stats">
		<p>Replies: $NumPosts</p>
		<p>Views: $NumViews</p>
	</div>
	<div class="forum-flex-right topic-latest">
		<% if LatestPost %>
			<% with LatestPost %>
				<p class="post-title"><a href="$Link">$Title</a> <% with Author %>by <% if Link %><a href="$Link"><% if Nickname %>$Nickname<% else %>Anon<% end_if %></a><% else %><span>Anon</span><% end_if %><% end_with %></p>
				<p class="post-date">$Created.Ago</p>
			<% end_with %>
		<% end_if %>
	</div>
</div>
