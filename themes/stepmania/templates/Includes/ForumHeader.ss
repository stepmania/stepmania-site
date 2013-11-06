<div class="forum-header<% if Parent.ID = 0 %> forum-header-toplevel<% end_if %>">
	<% if Parent.ID != 0 %>
	<p class="forum-breadcrumbs">$Breadcrumbs</p>
	<% end_if %>
	<% if Moderators %>
		<p>
			Moderators: 
			<% loop Moderators %>
				<a href="$Link">$Nickname</a>
				<% if not Last %>, <% end_if %>
			<% end_loop %>
		</p>
	<% end_if %>
</div>
