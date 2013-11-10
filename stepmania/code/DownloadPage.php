<?php
// Same as File, but with statistical info (just dl count for now)
class TrackedFile extends File {
	private static $db = array(
		"DownloadCount" => "Int",
		"Architecture" => "Enum(array('Any', 'x86', 'x86_64', 'ARM', 'MIPS', 'PowerPC', 'Other'))",
		"Platform" => "Enum(array('Any', 'Windows', 'Mac OS X', 'Linux', 'Android', 'iOS', 'Other'))"
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

class DownloadPage extends Page {
	function getCMSFields() {
		$fields = parent::getCMSFields();

		$grid_config = GridFieldConfig::create()->addComponents(
			new GridFieldToolbarHeader(),
			new GridFieldAddNewButton('toolbar-header-right'),
			new GridFieldSortableHeader(),
			new GridFieldDataColumns(),
			new GridFieldPaginator(10),
			new GridFieldEditButton(),
			new GridFieldDeleteAction(),
			new GridFieldDetailForm()
		);

		$gridfield = new GridField("Downloads", "Downloads:", $this->Downloads(), $grid_config);

		$fields->addFieldToTab("Root.Downloads", $gridfield);

		return $fields;
	}
	static $has_many = array(
		"Downloads" => "TrackedFile"
	);
}

class DownloadPage_Controller extends Page_Controller {

}

