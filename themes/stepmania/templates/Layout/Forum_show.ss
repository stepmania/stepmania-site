<% include ForumHeader %>
<div class="forum-header pad">
	<div class="float-left replyButton button">
		<a href="$ReplyLink" title="<% _t('Forum_show_ss.CLICKREPLY', 'Click to Reply') %>"><% _t('Forum_show_ss.REPLY', 'Reply') %></a>
	</div>
	<div class="float-right">
		<div class="page-numbers button">
			<% if Posts.MoreThanOnePage %>
				<% if Posts.NotFirstPage %>
					<a class="prev" href="$Posts.PrevLink" title="<% _t('Forum_show_ss.PREVTITLE','View the previous page') %>"><% _t('Forum_show_ss.PREVLINK','Prev') %></a>
				<% end_if %>
			<% end_if %>
				<% loop Posts.Pages %>
					<% if CurrentBool %>
						<span class="topic-page current"><strong>$PageNum</strong></span>
					<% else %>
						<span class="topic-page"><a href="$Link">$PageNum</a></span>
					<% end_if %>
				<% end_loop %>
			<% if Posts.MoreThanOnePage %>
				<% if Posts.NotLastPage %>
					<a class="next" href="$Posts.NextLink" title="<% _t('Forum_show_ss.NEXTTITLE','View the next page') %>"><% _t('Forum_show_ss.NEXTLINK','Next') %> &gt;</a>
				<% end_if %>
			<% end_if %>
		</div>
	</div>
	<div class="clear"></div>
</div>
<% loop Posts %>
	<% include SinglePost %>
<% end_loop %>
<div class="topic-footer pad">
	<div class="float-left replyButton button">
		<a href="$ReplyLink" title="<% _t('Forum_show_ss.CLICKREPLY', 'Click to Reply') %>"><% _t('Forum_show_ss.REPLY', 'Reply') %></a>
	</div>
	<div class="float-right">
		<div class="page-numbers button">
			<% if Posts.MoreThanOnePage %>
				<% if Posts.NotFirstPage %>
					<a class="prev" href="$Posts.PrevLink" title="<% _t('Forum_show_ss.PREVTITLE','View the previous page') %>"><% _t('Forum_show_ss.PREVLINK','Prev') %></a>
				<% end_if %>
			<% end_if %>
				<% loop Posts.Pages %>
					<% if CurrentBool %>
						<span class="topic-page current"><strong>$PageNum</strong></span>
					<% else %>
						<span class="topic-page"><a href="$Link">$PageNum</a></span>
					<% end_if %>
				<% end_loop %>
			<% if Posts.MoreThanOnePage %>
				<% if Posts.NotLastPage %>
					<a class="next" href="$Posts.NextLink" title="<% _t('Forum_show_ss.NEXTTITLE','View the next page') %>"><% _t('Forum_show_ss.NEXTLINK','Next') %> &gt;</a>
				<% end_if %>
			<% end_if %>
		</div>
	</div>
	<div class="clear"></div>
	<div class="topic-tools topic-stats button">
		<% with ForumThread %>
		<% if HasSubscribed %>
		<a href="$UnsubscribeLink?SecurityID={$SecurityID}">Unsubscribe</a>
		<% else %>
		<a href="$SubscribeLink?SecurityID={$SecurityID}">Subscribe</a>
		<% end_if %>
		<% end_with %>
	</div>
	<div class="topic-stats">
		<strong>$ForumThread.NumViews <% _t('Forum_show_ss.VIEWS','Views') %></strong>
	</div>
</div>

<% if CanPost %>
<div class="forum-quick-reply" id="reply">
	<h3>Quick Reply</h3>
	$PostMessageForm(true)
</div>
<% end_if %>

<% if AdminFormFeatures %>
<div class="forum-admin-features">
	<h3>Forum Admin</h3>
	$AdminFormFeatures
</div>
<% end_if %>
<% include ForumFooter %>
