<html>
<head>
<% base_tag %>
<link rel="stylesheet" href="simplewiki/css/simplewiki.css"></link>
<script type="text/javascript" src="jsparty/tiny_mce/tiny_mce_popup.js"></script>
<script type="text/javascript" src="jsparty/jquery/jquery.js"></script>
<script type="text/javascript" src="simplewiki/javascript/sslinks/link_selector.js"></script>

<script type="text/javascript">
jQuery().ready(function () {
	setTimeout(function () {
		//  bit of a hack for now to get around the problems of having prototype
		// and jquery running alongside each other .
		new SimpleWiki.LinkSelector('Form_LinkSelectForm', '$LinkingType');
	}, 300);
});
</script>

<style type="text/css">
/* Deliberate hard coding to prevent overrides */
#ContentPreview { width: 680px; margin: 0px auto; }
#ContentPreview img { max-width: 680px; max-height: 500px; }
#ContentPreview iframe { width: 680px; height: 500px; border: 1px solid #999; }
</style>

</head>
<body>
$LinkSelectForm
<div id="ContentPreview">
</div>
</body>
</html>