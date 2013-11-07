<% include ForumHeader %>
	<% loop Member %>
		<div class="user-profile">
			<h2><% if Nickname %>$Nickname<% else %>Anon<% end_if %>&#39;s <% _t('ForumMemberProfile_show_ss.PROFILE','Profile') %></h2>
			<% if isSuspended %>
				<div class="message warning suspensionWarning">
					<p>This user has been canned.</p>
				</div>
			<% end_if %>
			<div id="ForumProfileCreationDate">
				<label class="left">Member since:</label>
				<p class="readonly">$Created.Format(F Y)</p>
			</div>
			<% if EmailPublic %>
			<div id="ForumProfileEmail"><label class="left"><% _t('ForumMemberProfile_show_ss.EMAIL','Email') %>:</label> <p class="readonly"><a href="mailto:$Email">$Email</a></p></div>
			<% end_if %>
			<% if OccupationPublic %>
			<div id="ForumProfileOccupation"><label class="left"><% _t('ForumMemberProfile_show_ss.OCCUPATION','Occupation') %>:</label> <p class="readonly">$Occupation</p></div>
			<% end_if %>
			<% if CityPublic %>
			<div id="ForumProfileCity"><label class="left"><% _t('ForumMemberProfile_show_ss.CITY','City') %>:</label> <p class="readonly">$City</p></div>
			<% end_if %>
			<% if CountryPublic %>
			<div id="ForumProfileCountry"><label class="left"><% _t('ForumMemberProfile_show_ss.COUNTRY','Country') %>:</label> <p class="readonly">$FullCountry</p></div>
			<% end_if %>
			<div id="ForumProfilePosts">
				<label class="left">Post count:</label>
				<p class="readonly">$NumPosts</p>
			</div>
			<div id="ForumProfileRank"><label class="left"><% _t('ForumMemberProfile_show_ss.FORUMRANK','Forum ranking') %>:</label> <% if ForumRank %><p class="readonly">$ForumRank</p><% else %><p><% _t('ForumMemberProfile_show_ss.NORANK','No ranking') %></p><% end_if %></div>

			<div id="ForumProfileAvatar">
				<label class="left"><% _t('ForumMemberProfile_show_ss.AVATAR','Avatar') %>:</label> 
				<p><img class="userAvatar" src="$FormattedAvatar" alt="<% if Nickname %>$Nickname<% else %>Anon<% end_if %><% _t('ForumMemberProfile_show_ss.USERSAVATAR','&#39;s avatar') %>" /></p>
			</div>
		</div>
	<% end_loop %>
	<% if LatestPosts %>
		<div id="MemberLatestPosts">
			<h2><% _t('ForumMemberProfile_show_ss.LATESTPOSTS','Latest Posts') %></h2>
			<div class="forum-profile-posts">
				<% loop LatestPosts %>
				<div class="forum-profile-post">
					<header>
						<h2><a href="$Link">$Title</a></h2>
						<span class="post-date">Posted: $Created.Ago</span>
					</header>
				</div>
				<% end_loop %>
		</div>
	<% end_if %>
<% include ForumFooter %>
