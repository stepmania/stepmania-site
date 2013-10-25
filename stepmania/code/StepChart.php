<?php
class StepChart extends DataObject {
	static $db = array(
		"Game" => "Enum(array('Dance','Pump','Popn','Beat','Kb7'))",
		"Style" => "Enum(array('Single','Double','Couple','Routine','Half Double'))",
		"UnlistedAuthor" => "Text" // in case it was by someone who is not registered here
	);
	static $has_one = array(
		"Song" => "SongUpload",
		"Author" => "Member"
	);
}
