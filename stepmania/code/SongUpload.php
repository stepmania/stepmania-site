<?php
class SongUpload extends DataObject {
	static $db = array(
		"Artist" => "Text",
		"Compatible" => "Enum(array('Any','DWI','StepMania Micro', 'StepMania 3.9','StepMania 3.95','StepMania 5.0'))",
		"QuirksMode" => "Boolean", // Uses version specific crap.
		"Title" => "Text"
	);
	static $has_one = array(
		"Banner" => "Image",
		"Pack" => "SongPack",
		"Uploader" => "Member"
	);
	static $has_many = array(
		"Charts" => "StepChart",
		"Files" => "TrackedFile"
	);
}
