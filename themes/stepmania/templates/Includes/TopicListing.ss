<div class="forum-topic <% if IsSticky || IsGlobalSticky %>sticky<% end_if %> <% if IsGlobalSticky %>global-sticky<% end_if %>">
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
