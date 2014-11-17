<?php
// Same as File, but with statistical info (just dl count for now)
/*
class TrackedFile extends File {
	private static $db = array(
		"DownloadCount" => "Int",
	);
	private static $allowed_extensions = array(
		'','avi','bz2','csv','dmg','gif','gz','jar','jpeg','jpg','mkv','mp3',
		'mp4','mpa','mpeg','mpg','ogg','ogv','pdf','pkg','exe','opk', 'apk',
		'deb','png','rtf','tar','tgz','txt','wav','webm','xml','zip', 'xz',
		'sm', 'ssc', 'dwi', 'rsm', 'rs', 'smzip',
	);
	function TotalDownloadSize() {
		return $this->format_size($this->getAbsoluteSize() * $this->DownloadCount);
	}
	function Link() {
		return parent::Link();
	}
}
*/

class DownloadPage extends Page {
	static $db = array(
		"Repository" => "Text",
		"GibMoneyPls" => "HTMLText"
	);
	function getCMSFields() {
		$fields = parent::getCMSFields();

		// $grid_config = GridFieldConfig::create()->addComponents(
		// 	new GridFieldToolbarHeader(),
		// 	new GridFieldAddNewButton('toolbar-header-right'),
		// 	new GridFieldSortableHeader(),
		// 	new GridFieldDataColumns(),
		// 	new GridFieldPaginator(10),
		// 	new GridFieldEditButton(),
		// 	new GridFieldDeleteAction(),
		// 	new GridFieldDetailForm()
		// );

		//$gridfield = new GridField("Downloads", "Downloads:", $this->Downloads(), $grid_config);

		//$fields->addFieldToTab("Root.Downloads", $gridfield);

		$fields->addFieldToTab("Root.Special", new HTMLEditorField("GibMoneyPls", "Donation box"));

		$fields->addFieldToTab("Root.Special", new TextField("Repository", "GitHub Repository"));

		return $fields;
	}
}

class DownloadPage_Controller extends Page_Controller {
	static function mime2platform($mime) {
		$types = array(
			"application/octet-stream" => "Windows",
			"application/x-apple-diskimage" => "Mac"
		);
		if (array_key_exists($mime, $types))
			return $types[$mime];
		return "Any";
	}

	static function munge($thing) {
		$ret = strtolower($thing);
		$ret = str_replace(" ", "-", $ret);
		return $ret;
	}

	function getDownloads() {
		$downloads = new ArrayList();

		// I don't actually like it being here as much as in ~, but this makes it accessible from the admin panel!
		$data = file_get_contents("../assets/stepmania-releases.json");
		$gh_data = json_decode($data);

		$owner = "stepmania";
		$repo = "stepmania";

		foreach ($gh_data as $release) {
			if (empty($release->assets))
				continue;
			$tag = $release->tag_name;
			$url = "https://github.com/$owner/$repo/releases/download/$tag";

			$date = date_create($release->published_at);

			foreach ($release->assets as $asset) {
				$downloads->add(new ArrayData(array(
					"Name" => $release->name,
					"Link" => $url . "/" . $asset->name,
					"Platform" => self::mime2platform($asset->content_type),
					"Icon" => self::munge(self::mime2platform($asset->content_type)),
					"Size" => File::format_size($asset->size),
					"ContentType" => $asset->content_type,
					"PublishedAt" => date_format($date, "Y-m-d")
				)));
			}

			$downloads->add(new ArrayData(array(
				"Name" => $release->name . " Source (tar.gz)",
				"Link" => $release->tarball_url,
				"Platform" => "Any",
				"Icon" => "source",
				"Size" => false,
				"ContentType" => false,
				"PublishedAt" => date_format($date, "Y-m-d")
			)));

			// TODO: n>1 of these things, sorting, etc.
			break;
		}

		return $downloads;
	}
}

