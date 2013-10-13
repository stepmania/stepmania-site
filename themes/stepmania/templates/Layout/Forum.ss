<% include ForumHeader %>
<% if ForumAdminMsg %>
	<p class="forum-message-admin">$ForumAdminMsg</p>
<% end_if %>
<% if CurrentMember.isSuspended %>
	<p class="forum-message-suspended">
		$CurrentMember.ForumSuspensionMessage
	</p>
<% end_if %>
<% if ForumPosters = NoOne %>
	<p class="message error"><% _t('Forum_ss.READONLYFORUM', 'This Forum is read only. You cannot post replies or start new threads') %></p>
<% end_if %>
<% if canPost %>
	<p class="new-topic button"><a href="{$Link}starttopic" title="<% _t('Forum_ss.NEWTOPIC','Click here to start a new topic') %>"><% _t('Forum_ss.NEWTOPICIMAGE','Start new topic') %></a></p>
<% end_if %>
<div class="forum-features">
	<div class="forum-topics" summary="List of topics in this forum">
		<h4 class="category"><% _t('Forum_ss.THREADS', 'Threads') %></h4>
		<% if getStickyTopics(0) %>
			<% loop getStickyTopics(0) %>
				<% include TopicListing %>
			<% end_loop %>
		<% end_if %>
		<% if Topics %>
			<% loop Topics %>
				<% include TopicListing %>
			<% end_loop %>
		<% else %>
			<div class="message bad">
				<div class="forum-category"><% _t('Forum_ss.NOTOPICS','There are no topics in this forum, ') %><a href="{$Link}starttopic" title="<% _t('Forum_ss.NEWTOPIC') %>"><% _t('Forum_ss.NEWTOPICTEXT','click here to start a new topic') %>.</a></div>
			</div>
		<% end_if %>
	</div>
	<% if Topics.MoreThanOnePage %>
		<p>
			<% if Topics.PrevLink %><a style="float: left" href="$Topics.PrevLink">	&lt; <% _t('Forum_ss.PREVLNK','Previous Page') %></a><% end_if %>
			<% if Topics.NextLink %><a style="float: right" href="$Topics.NextLink"><% _t('Forum_ss.NEXTLNK','Next Page') %> &gt;</a><% end_if %>
			
			<% loop Topics.Pages %>
				<% if CurrentBool %>
					<strong>$PageNum</strong>
				<% else %>
					<a href="$Link">$PageNum</a>
				<% end_if %>
			<% end_loop %>
		</p>
	<% end_if %>
</div>
<% include ForumFooter %>
