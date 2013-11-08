<p><% sprintf(_t('ForumMember_TopicNotification_ss.HI',"Hi %s,"),$Nickname) %></p>

<p><% _t('ForumMember_TopicNotification_ss.NEWPOSTMESSAGE',"A new post has been added to a topic you've subscribed to") %> - '$Title' <% if Author %><% _t('BY', "by") %> {$Author.Nickname}.<% end_if %></p>

<ul>
	<li><a href="$Link"><% _t('ForumMember_TopicNotification_ss.REPLYLINK', "View this topic") %></a></li>
	<li><a href="$UnsubscribeLink"><% _t('ForumMember_TopicNotification_ss.UNSUBSCRIBETEXT',"Unsubscribe from this topic") %></a></li>
</ul>

<p>
	Thanks,<br />
	The StepMania Team.
</p>

<p>NOTE: Do not reply to this email. You can manage all your subscriptions from your forum profile.</p>