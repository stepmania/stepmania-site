<?php
// Same as File, but with statistical info (just dl count for now)
class TrackedFile extends File {
	static $db = array(
		"DownloadCount" => "Int",
	);
	function GetTotalDownloadSize() {
		return $this->format_size($this->getAbsoluteSize() * $this->DownloadCount);
	}
}

class DownloadPage extends Page {
	static $has_many = array(
		"Downloads" => "TrackedFile"
	);
}

class DownloadPage_Controller extends Page_Controller {

}

