<% include ForumHeader %>
	<div id="UserNavigation">
		<div class="button float-left"><a href="{$Link}show/$Member.ID">View Profile</a></div>
		<div class="button float-left"><a href="{$Link}edit">Edit Profile</a></div>
	</div>
	<% if SubscribedThreads %>
	<div id="MemberSubscribedThreads">
		<div class="forum-profile-posts">
			<% loop SubscribedThreads %>
			<div class="forum-profile-post">
				<header>
				<% with Thread %>
				<h2><a href="$Link">$Title</a></h2>
				<a href="$UnsubscribeLink?SecurityID={$SecurityID}">Unsubscribe</a>
				<% end_with %>
				</header>
			</div>
			<% end_loop %>
		</div>
	</div>
	<% end_if %>
<% include ForumFooter %>
