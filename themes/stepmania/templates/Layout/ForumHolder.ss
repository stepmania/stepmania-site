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
		<!--<li class="category">$Title</li>
		<ul class="category">
			<li><% if Count = 1 %><% _t('ForumHolder_ss.FORUM','Forum') %><% else %><% _t('ForumHolder_ss.FORUMS', 'Forums') %><% end_if %></li>
			<li><% _t('ForumHolder_ss.THREADS','Threads') %></li>
			<li><% _t('ForumHolder_ss.POSTS','Posts') %></li>
			<li><% _t('ForumHolder_ss.LASTPOST','Last Post') %></li>
		</ul>-->
		<% loop CategoryForums %>
			<% include ForumHolder_List %>
		<% end_loop %>
	<% end_loop %>
<% else %>
	<!--<ul class="category">
		<li><% _t('ForumHolder_ss.FORUM','Forum') %></li>
		<li><% _t('ForumHolder_ss.THREADS','Threads') %></li>
		<li><% _t('ForumHolder_ss.POSTS','Posts') %></li>
		<li><% _t('ForumHolders_s.LASTPOST','Last Post') %></li>
	</ul>-->
	<% loop Forums %>
		<% include ForumHolder_List %>
	<% end_loop %>
<% end_if %>
<% include ForumFooter %>
