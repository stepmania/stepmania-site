<div id="post{$ID}" class="forum-post">
	<!--<header>
	</header>-->
	<div class="forum-flex post-body">		
		<div class="user-info">
			<% with Author %>
				<a class="author-link" href="$Link" title="<% _t('SinglePost_ss.GOTOPROFILE','Go to this User&rsquo;s Profile') %>">$Nickname</a><br />
				<span class="custom-title">$CustomTitle.LimitCharacters(32)</span>
				<img class="avatar" src="$FormattedAvatar" alt="Avatar" /><br />
				<% if NumPosts %><span class="post-count">Posts: $NumPosts<br /></span><% end_if %>
				<span class="join-date">Joined: $Created.Format(M Y)<br /></span>
				<%--<% if ForumRank %><br /><span class="forum-rank">$ForumRank</span><% end_if %>--%>
			<% end_with %>
		</div>
			<div class="container">
				<div class="mp-pusher">
					<nav class="mp-menu">
						<div class="mp-level">
							<h2 class="icon icon-world">Post Options</h2>
							<ul>
							<% if EditLink %>
								<li class="admin-option">$EditLink</li>
							<% end_if %>
							<% if DeleteLink %>
								<li class="admin-option">$DeleteLink</li>
							<% end_if %>
							<% if MarkAsSpamLink %>
								<li><a href="#">Mark as Spam</a>
									<div class="mp-level">
										<h2>Are you sure?</h2>
										<ul>
											<a class="mp-back" href="#">back</a>
											<li>$MarkAsSpamLink</li>
										</ul>
									</div>
								</li>
							<% end_if %>
							</ul>
						</div>
					</nav>
					<div class="scroller">
						<div class="scroller-inner">
							<div class="content clearfix">
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
								<span class="post-date"><a href="$Link" rel="permalink" title="Permalink to this post">#$ID</a> <% if Created.IsToday %>Today<% else %>$Created.Full<% end_if %>, $Created.Time</span>
								<span class="quick-reply">
									<%-- <a href="#" x-post-id="post{$ID}" class="menu-trigger">Options</a> --%>
									<a href="$Top.ReplyLink" class="replyLink" x-post-data="$Content.XML" x-post-author="$Author.Nickname.XML" x-post-id="post{$ID}">Reply</a>
									<%-- <noscript> --%>
										<% if EditLink %>
										$EditLink
										<% end_if %>
										<% if DeleteLink %>
										$DeleteLink
										<% end_if %>
										<% if MarkAsSpamLink %>
										$MarkAsSpamLink
										<% end_if %>
									<%-- </noscript> --%>
								</span>
							</footer>
						</div>
					</div><!-- /scroller-inner -->
				</div><!-- /scroller -->
			</div>
		</div>
	</div>
</div><!-- forum-post. -->

