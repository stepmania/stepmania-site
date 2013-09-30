<div id="post{$ID}" class="forum-post">
	<div class="user-info">
		<% with Author %>
			<a class="author-link" href="$Link" title="<% _t('SinglePost_ss.GOTOPROFILE','Go to this User&rsquo;s Profile') %>">$Nickname</a><br />
			<img class="avatar" src="$FormattedAvatar" alt="Avatar" /><br />
			<% if ForumRank %><span class="forum-rank">$ForumRank</span><br /><% end_if %>
			<% if NumPosts %><span class="post-count">Posts: $NumPosts </span><% end_if %>
		<% end_with %>
	</div><!-- user-info. -->

	<div class="user-content">
		<p class="post-date"><a href="$Link">#$ID <img src="forum/images/right.png" alt="Link to this post" title="Link to this post" /></a> on $Created.Long at $Created.Time
		<% if Updated %>
			<strong><% _t('SinglePost_ss.LASTEDITED','Last edited:') %> $Updated.Long <% _t('SinglePost_ss.AT') %> $Updated.Time</strong>
		<% end_if %>
		<span class="quick-reply">
			<% if Thread.canPost %>
			$Top.ReplyLink
			<% end_if %>
		</span>
		<% if EditLink || DeleteLink %>
			<span class="post-modifiers">
				<% if EditLink %>
					$EditLink
				<% end_if %>
				
				<% if DeleteLink %>
					$DeleteLink
				<% end_if %>
				
				<% if MarkAsSpamLink %>
					$MarkAsSpamLink
				<% end_if %>
			</span>
		<% end_if %>
		</p>
		<div class="post-type">
			$Content.Parse(BBCodeParser)
		</div>
		
		<% if Thread.DisplaySignatures %>
			<% with Author %>
				<% if Signature %>
					<div class="signature">
						<p>$Signature</p>
					</div>
				<% end_if %>
			<% end_with %>
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
	<div class="clear"><!-- --></div>
</div><!-- forum-post. -->
