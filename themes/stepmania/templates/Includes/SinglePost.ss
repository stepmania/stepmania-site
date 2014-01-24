<div id="post{$ID}" class="forum-post">
	<div class="forum-flex post-body">		
		<div class="user-info">
			<% with Author %>
				<a class="author-link" href="$Link" title="<% _t('SinglePost_ss.GOTOPROFILE','Go to this User&rsquo;s Profile') %>">$Nickname</a><br />
				<span class="custom-title">$CustomTitle.LimitCharacters(32)</span>
				<img class="avatar" src="$FormattedAvatar" alt="Avatar" /><br />
				<% if NumPosts %><span class="post-count">Posts: $NumPosts<br /></span><% end_if %>
				<span class="join-date">Joined: $Created.Format(M Y)<br /></span>
			<% end_with %>
		</div>
		<div class="user-content">
			<div class="post-type">
				$Content.Parse(SMBBCodeParser)
			</div>
		<% if Updated %>
			<p class="post-edited"><% _t('SinglePost_ss.LASTEDITED','Last edited:') %> $Updated.Long <% _t('SinglePost_ss.AT') %> $Updated.Time</p>
		<% end_if %>
		
		<% if Thread.DisplaySignatures %>
			<% with Author %>
				<% if Signature %>
					<div class="signature">
						$Signature.Parse(SMBBCodeParser)
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
		<footer>
			<span class="post-date"><a href="$Link" class="li_clip" rel="permalink" title="Permalink to this post"></a> <% if Created.IsToday %>Today<% else %>$Created.Full<% end_if %>, $Created.Time</span>
			<span class="quick-reply">
				<a href="$Top.ReplyLink" class="replyLink" x-post-data="$Content.XML" x-post-author="$Author.Nickname.XML" x-post-id="post{$ID}">Reply</a>
				<% if EditLink %>$EditLink<% end_if %>
				<% if DeleteLink %>$DeleteLink<% end_if %>
				<% if MarkAsSpamLink %>$MarkAsSpamLink<% end_if %>
			</span>
		</footer>
	</div>
</div>
