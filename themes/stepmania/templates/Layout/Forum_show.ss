<% include ForumBanner %>
<article>
	<% include ForumHeader %>
	<table class="forum-topics">
		<div class="category">
			<div class="page-numbers">
				<span><strong><% _t('Forum_show_ss.PAGE','Page:') %></strong></span>
				<% loop Posts.Pages %>
					<% if CurrentBool %>
						<span><strong>$PageNum</strong></span>
					<% else %>
						<a href="$Link">$PageNum</a>
					<% end_if %>
					<% if not Last %>,<% end_if %>
				<% end_loop %>
			</div>
			<div class="replyButton">
				<% if ForumThread.canCreate %>
					<a href="$ReplyLink" title="<% _t('Forum_show_ss.CLICKREPLY','Click here to reply to this topic') %>"><% _t('Forum_show_ss.REPLY','Reply') %></a>
				<% end_if %>
				<% if CurrentMember %>
					<% include ForumThreadSubscribe %>
				<% end_if %>
			</div>
		</div>
		<div class="author">
			<div class="topic">
				<span><strong><% _t('Forum_show_ss.TOPIC','Topic:') %></strong> $ForumThread.Title</span>
			</div>
			<div class="views">
				<span><strong>$ForumThread.NumViews <% _t('Forum_show_ss.VIEWS','Views') %></strong></span>
			</div>
		</div>
	</table>
	<% loop Posts %>
		<% include SinglePost %>
	<% end_loop %>
	<div class="forum-topics">
		<div class="author">
			<div class="views">
				<span><strong>$ForumThread.NumViews <% _t('Forum_show_ss.VIEWS','Views') %></strong></span>
			</div>
		</div>
		<div class="category">
			<div class="page-numbers">
				<% if Posts.MoreThanOnePage %>
					<% if Posts.NotFirstPage %>
						<a class="prev" href="$Posts.PrevLink" title="<% _t('Forum_show_ss.PREVTITLE','View the previous page') %>"><% _t('Forum_show_ss.PREVLINK','Prev') %></a>
					<% end_if %>
				<% end_if %>
			</div>
			<div class="replyButton">
				<% if ForumThread.canCreate %>
					<a href="$ReplyLink" title="<% _t('Forum_show_ss.CLICKREPLY', 'Click to Reply') %>"><% _t('Forum_show_ss.REPLY', 'Reply') %></a>
				<% end_if %>
				<% if Posts.MoreThanOnePage %>
					<% if Posts.NotLastPage %>
						<a class="next" href="$Posts.NextLink" title="<% _t('Forum_show_ss.NEXTTITLE','View the next page') %>"><% _t('Forum_show_ss.NEXTLINK','Next') %> &gt;</a>
					<% end_if %>
				<% end_if %>
			</div>
		</div>
	</div>
	<% if AdminFormFeatures %>
	<div class="forum-admin-features">
		<h3>Forum Admin Features</h3>
		$AdminFormFeatures
	</div>
	<% end_if %>

	<% include ForumFooter %>
</article>