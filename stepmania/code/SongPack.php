<?php
class SongPack extends DataObject {
	static $db = array(
		"Title" => "Text"
	);
	static $has_one = array(
		"Banner" => "Image"
	);
	static $has_many = array(
		"Courses" => "CourseUpload",
		"Songs" => "SongUpload",
		"Files" => "TrackedFile"
	);
}
