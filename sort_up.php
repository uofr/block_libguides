<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$cid = $_REQUEST["cid"];
$bid = $_REQUEST["bid"];
$sesskey=$_REQUEST["sesskey"];
$bui_editid=$_REQUEST["bui_editid"];

global $DB, $CFG; 

//require_login($cid, true);
$url = $CFG->wwwroot."/course/view.php?id=".$cid."&sesskey=".$sesskey."&bui_editid=".$bui_editid;
$book = $DB->get_record('bookshelf', array('course'=>$cid, 'book'=>$bid));
$book_ = $DB->get_record('bookshelf', array('course'=>$cid, 'sortorder'=>($book->sortorder-1)));
if (isset($book_->sortorder)) {
  $swap = $book->sortorder;
  $book->sortorder = $book_->sortorder;
  $book_->sortorder = $swap;
  $DB->update_record('bookshelf', $book);
  $DB->update_record('bookshelf', $book_);
}
echo js_redirect($url);
?>
