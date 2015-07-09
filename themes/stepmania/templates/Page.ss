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
			<% include ForumLogin %>
			$Layout
		</article>
		<%--<% include ForumLogin %>--%>
		<footer>
			<p>StepMania and its website are open source software released under the MIT License. <a href="https://github.com/stepmania" class="github">GitHub</a>. <a href="policies">Privacy Policy</a></p>
		</footer>
	</div>
	<% include GoogleAnalytics %>
</body>
</html>
