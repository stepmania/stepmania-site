<?php
class CourseUpload extends DataObject {
	static $db = array(
		"Title" => "Text",
		"Content" => "Text",
	);
	static $has_one = array(
		"Banner" => "Image",
		"Pack" => "SongPack"
	);
	static $has_many = array(
		"Songs" => "SongUpload"
	);
}
