<% with ForumHolder %>
	<div class="forum-footer pad">
		<p>
			<strong><% _t('ForumFooter_ss.CURRENTLYON','Currently Online:') %></strong>
			<% if CurrentlyOnline %>
				<% loop CurrentlyOnline %>
					<% if Link %><a href="$Link" title="<% if Nickname %>$Nickname<% else %>Anon<% end_if %><% _t('ISONLINE',' is online') %>"><% if Nickname %>$Nickname<% else %>Anon<% end_if %></a><% else %><span>Anon</span><% end_if %><% if Last %><% else %>,<% end_if %>
				<% end_loop %>
			<% else %>
				<span><% _t('ForumFooter_ss.NOONLINE','There is nobody online.') %></span>
			<% end_if %>
		</p>
		<p>
			<strong><% _t('ForumFooter_ss.LATESTMEMBER','Welcome to our latest member:') %></strong>
			<% if LatestMembers(1) %>
				<% loop LatestMembers(1) %>
					<% if Link %>
						<a href="$Link" <% if Nickname %>title="$Nickname<% _t('ForumFooter_ss.ISONLINE') %>"<% end_if %>><% if Nickname %>$Nickname<% else %>Anon<% end_if %></a><% if Last %><% else %>,<% end_if %> 
					<% else %>
						<span>Anon</span><% if Last %><% else %>,<% end_if %> 
					<% end_if %>
				<% end_loop %>
			<% end_if %>
		</p>
		<% loop ForumHolder %>
		<div class="forum-header-forms">
			<% if NumPosts %>
				<p class="forum-stats"><span class="forum-post-count">$NumPosts</span> <% _t('ForumHeader_ss.POSTS','Posts') %> <% _t('ForumHeader_ss.IN','in') %> <span class="forum-topic-count">$NumTopics</span> <% _t('ForumHeader_ss.TOPICS','Topics') %> <% _t('ForumHeader_ss.BY','by') %> <span class="forum-author-count">$NumAuthors</span> <% _t('ForumHeader_ss.MEMBERS','members') %></p>
			<% end_if %>
		</div>
		<% end_loop %>
	</div>
<% end_with %>