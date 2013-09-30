<div class="forum-header">
	<p class="forum-breadcrumbs">$Breadcrumbs</p>
	<!--<p class="forum-abstract">$ForumHolder.HolderAbstract</p>-->
	<% if Moderators %>
		<p>
			Moderators: 
			<% loop Moderators %>
				<a href="$Link">$Nickname</a>
				<% if not Last %>, <% end_if %>
			<% end_loop %>
		</p>
	<% end_if %>

</div><!-- forum-header. -->
