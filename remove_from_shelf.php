<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$courseid = required_param('id', PARAM_INT);

// Require login, capability and sesskey
require_login($courseid, true);
//require_capability('block/bookshel:editbookshelf', $PAGE->context);
require_sesskey();

$bui_editid = optional_param('bui_editid', null, PARAM_INT);

$url = $CFG->wwwroot."/course/view.php?id=".$courseid."&sesskey=".sesskey();
if (!empty($bui_editid)) $url .= "&bui_editid=".$bui_editid;

if (isset($_POST["book_listed"])) {
	foreach ($_POST["book_listed"] as $bid) {
	  $DB->delete_records('bookshelf', array('book' => $bid, 'course' => $courseid)); 
	}
}

redirect($url);
?>
