<% with ForumHolder %>
	<div class="forum-footer">
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
		<!--
		<% loop ForumHolder %>
		<div class="forum-header-forms">
			<% if NumPosts %>
				<p class="forumStats">
					$NumPosts 
					<strong><% _t('ForumHeader_ss.POSTS','Posts') %></strong> 
					<% _t('ForumHeader_ss.IN','in') %> $NumTopics <strong><% _t('ForumHeader_ss.TOPICS','Topics') %></strong> 
					<% _t('ForumHeader_ss.BY','by') %> $NumAuthors <strong><% _t('ForumHeader_ss.MEMBERS','members') %></strong>
				</p>
			<% end_if %>
		</div><%-- forum-header-forms. --%>
		<% end_loop %>
	-->
	</div><%-- forum-footer. --%>
<% end_with %>