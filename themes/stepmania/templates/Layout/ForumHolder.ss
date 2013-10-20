<% include ForumHeader %>
<% if GlobalAnnouncements %>
	<div class="subforums">
		<h4 class="category"><% _t('ForumHolder_ss.ANNOUNCEMENTS', 'Announcements') %></h4>
		<% loop GlobalAnnouncements %>
			<% include ForumHolder_List %>
		<% end_loop %>
	</div>
<% end_if %>
$Content
<% if ShowInCategories %>
	<% loop Forums.Sort(StackableOrder) %>
		<div class="subforums">
			<h4 class="category">$Title</h4>
			<% loop CategoryForums %>
				<% include ForumHolder_List %>
			<% end_loop %>
		</div>
	<% end_loop %>
<% else %>
	<% loop Forums %>
		<% include ForumHolder_List %>
	<% end_loop %>
<% end_if %>
<% include ForumFooter %>
