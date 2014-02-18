<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>$Title - $SiteConfig.Title</title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico?v=1" />
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<% base_tag %>
	<% require themedCSS(style) %>
	<% require javascript("stepmania/javascript/jquery-2.0.3.min.js") %>
	<% require javascript("stepmania/javascript/bitcoinate.min.js") %>
	<% require javascript("stepmania/javascript/rainbow.js") %>
	<% require javascript("stepmania/javascript/site.js") %>
</head>
<body>
	<% if CurrentMember %>
	<%-- Go away! Really! --%>
	<%-- $SilverStripeNavigator --%>
	<% end_if %>
	<header>
		<nav class="limit-width">
			<a href="$BaseHref"><img src="$ThemeDir/images/logo-small.png" alt="StepMania"></img></a>
			<ul>
				<% loop Menu(1) %>
				<li><a href="$Link" class="$LinkingMode">$MenuTitle</a></li>
				<% end_loop %>
			</ul>
		</nav>
	</header>
	<div id="container" class="limit-width">
		<div class="$ClassName-banner banner">
			<% if BannerContent %>$BannerContent<% else %><h1>$Title</h1><% end_if %>
			<% if canEdit %><a href="admin/pages/edit/show/$ID" rel="edit" class="float-right edit-button li_pen"> <span>Edit</span></a><% end_if %>
		</div>
		<article class="$ClassName-article">
			$Layout
		</article>
		<% include ForumLogin %>
	</div>
	<footer class="limit-width">
		<p>StepMania is open source software released under the MIT License. <a href="policies">Privacy Policy</a></p>
	</footer>
	<% with SiteConfig %>
	<% if AnalyticsID %>
	<!-- Hey there, if you are concerned about privacy, we do have all of the data sharing disabled, except for anonymized statistics (no data shared with advertisers). If you don't like Google Analytics, we won't be upset if you block it. -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', '$AnalyticsID', '$AnalyticsDomain');
		ga('send', 'pageview');
	</script>
	<% end_if %>
	<% end_with %>
</body>
</html>
