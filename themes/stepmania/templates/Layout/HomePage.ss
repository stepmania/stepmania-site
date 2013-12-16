<!--<h1>$Title</h1>-->
$Content
<div class="forum-news">
	<% if NewsForum %>
	<% with NewsForum %>
		<% if Topics %>
		<% loop Topics.Limit(15) %>
			<% loop Posts(1).Limit(1) %>
	<div id="post{$ID}" class="forum-post">
		<header>
			<h2><a href="$Link">$Title</a></h2>
		</header>
		<div class="forum-flex post-body">
			<div class="user-content">
				<div class="post-type">
					$Content.Parse(SMBBCodeParser)
				</div>

				<% if Updated %>
					<p class="post-edited"><% _t('SinglePost_ss.LASTEDITED','Last edited:') %> $Updated.Long <% _t('SinglePost_ss.AT') %> $Updated.Time</p>
				<% end_if %>

				<% if Attachments %>
					<div class="attachments">
						<strong><% _t('SinglePost_ss.ATTACHED','Attached Files') %></strong> 
						<ul class="post-attachments">
						<% loop Attachments %>
							<li>
								<a href="$Link"><img src="$Icon"></a>
								<a href="$Link">$Name</a><br />
								<% if ClassName = "Image" %>$Width x $Height - <% end_if %>$Size
							</li>
						<% end_loop %>
						</ul>
					</div>
				<% end_if %>
			</div>
		</div>
		<footer>
			<span class="post-date">Posted <% if Created.IsToday %>Today<% else %>$Created.Full<% end_if %>, $Created.Time by <a href="$Author.Link">$Author.Nickname</a></span>
			<% if EditLink || DeleteLink %>
				<span class="post-modifiers">
					<% if MarkAsSpamLink %>
						$MarkAsSpamLink
					<% end_if %>
					<% if EditLink %>
						$EditLink
					<% end_if %>
					<% if DeleteLink %>
						$DeleteLink
					<% end_if %>
				</span>
			<% end_if %>
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
