<% include ForumHeader %>
<% if GlobalAnnouncements %>
	<tr class="category">
		<td colspan="4"><% _t('ForumHolder_ss.ANNOUNCEMENTS', 'Announcements') %></td>
	</tr>
	<% loop GlobalAnnouncements %>
		<% include ForumHolder_List %>
	<% end_loop %>
<% end_if %>
$Content
<% if ShowInCategories %>
	<% loop Forums %>
		<li class="category">$Title</li>
		<% loop CategoryForums %>
			<% include ForumHolder_List %>
		<% end_loop %>
	<% end_loop %>
<% else %>
	<% loop Forums %>
		<% include ForumHolder_List %>
	<% end_loop %>
<% end_if %>
<% include ForumFooter %>
