<?php
class ScoreUpload extends DataObject {
	static $db = array(
		// Touch is valid for SM Micro, but not currently regular SM.
		"Input" => "Enum(array('Pad', 'Keyboard', 'Arcade', 'Controller', 'Touch'))",
		"ScorePercent" => "Decimal",
		"MaxCombo" => "Int",
	);
	static $has_one = array(
		"Song" => "SongUpload",
		"Chart" => "StepChart",
		"Player" => "Member"
	);
}

class ScoreTracker extends Page {}
class ScoreTracker_Controller extends Page_Controller {}
