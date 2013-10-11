<h1>$Title</h1>
$Content
<div class="forum-news">
	<% if NewsForum %>
	<% with NewsForum %>
		<% if Topics %>
			<% loop Topics.Limit(15) %>
				<% loop Posts(1).Limit(1) %>
				<div class="news-post">
					<div class="user-content">
						<h2><a href="$Link">$Title</a></h2>
						<div class="post-type">
							$Content.Parse(BBCodeParser)
						</div>
						<%--<a href="$Link">Read more &raquo;</a>--%>
						<% if Updated %>
							<p class="post-edited"><% _t('SinglePost_ss.LASTEDITED','Last edited:') %> $Updated.Long <% _t('SinglePost_ss.AT') %> $Updated.Time</p>
						<% end_if %>
					</div>
					<footer>
						<p class="post-date">Posted $Created.Long at $Created.Time by <a href="$Author.Link">$Author.Nickname</a> with <a class="post-reply-count" href="$Link">$Up.Up.Posts.Count <% if Up.Up.Posts.Count = 1 %>reply<% else %>replies<% end_if %></a>.
						<span class="quick-reply">
							<% if Thread.canPost %>
							$Top.ReplyLink
							<% end_if %>
						</span>
						<% if EditLink || DeleteLink %>
							<span class="post-modifiers">
								<% if EditLink %>$EditLink<% end_if %>
								<% if DeleteLink %>$DeleteLink<% end_if %>
								<% if MarkAsSpamLink %>$MarkAsSpamLink<% end_if %>
							</span>
						<% end_if %>
						</p>
					</footer>
				</div>
				<% end_loop %>
			<% end_loop %>
		<% end_if %>
	<% end_with %>
	<% else %>
	<p class="message bad">No news forum :(</p>
	<% end_if %>
</div>
<% if NewsForum %>
<p>The news forum is <a href="$NewsForum.Link">over here</a> if you need it!</p>
<% end_if %>
