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
										<span class="post-date"><a href="$Link" rel="permalink" title="Permalink to this post" class="li_clip"><span>#$ID</span></a> Posted <% if Created.IsToday %>Today<% else %>$Created.Date<% end_if %>, $Created.Time by <a href="$Author.Link">$Author.Nickname</a></span>
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
