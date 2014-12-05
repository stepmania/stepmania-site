<% include ForumHeader %>
<div class="pad">
$Content
<% if Form %>
	<div id="UserNavigation">
		<div class="button float-left"><a href="{$Link}show/$Member.ID">View Profile</a></div>
		<div class="button float-left"><a href="{$Link}subscriptions">Manage Subscriptions</a></div>
	</div>
	<div id="UserProfile">
		$Form
	</div>
<% end_if %>
</div>
<% include ForumFooter %>
