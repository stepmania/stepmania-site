<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>$Title - $SiteConfig.Title</title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<% base_tag %>
	<% require themedCSS(style) %>
</head>
<body>
	<header>
		<nav class="limit-width">
			<img src="$ThemeDir/images/logo-small.png" alt="StepMania">
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
		</div>
		<article class="$ClassName-article">
			$Layout
		</article>
		<% include ForumLogin %>
	</div>
	<footer class="limit-width">
		<p>StepMania is open source software released under the MIT License</p>
	</footer>
	<% if CurrentMember %>
	<%-- Go away! Really! --%>
	$SilverStripeNavigator
	<% end_if %>
</body>
</html>
