<?php
require_once(dirname(__FILE__).'/locallib.php');

class block_bookshelf_edit_form extends block_edit_form {
 
	protected function specific_definition($mform) {
		global $DB, $CFG; 
		$bookshelf_books = get_listed_books($this->block->page->course->id, $DB);
		$course_books = $DB->get_records('book', array('course' => $this->block->page->course->id));
		$unlisted_books = get_unlisted_books($course_books, $bookshelf_books);

		// Section header title according to language file.
		$mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

		//set title
		$mform->addElement('text', 'config_title', get_string('blocktitlelabel', 'block_bookshelf'));
		$mform->setDefault('config_title', get_string('blocktitle', 'block_bookshelf'));
		$mform->setType('config_title', PARAM_MULTILANG);

		//display bookshelf books
		$html_str = html_list_books(get_string('listed_books', 'block_bookshelf'), get_string('listed_books_help', 'block_bookshelf'), $bookshelf_books, $CFG->wwwroot, 'book_checkbox', true, $this->block->page->course->id);
        $html_str .= html_add_rm_js_button($CFG->wwwroot, true, $bookshelf_books);
		
		//display un-listed books
		$html_str .= html_list_books(get_string('unlisted_books', 'block_bookshelf'), get_string('unlisted_books_help', 'block_bookshelf'), $unlisted_books, $CFG->wwwroot, 'book_checkbox', false, $this->block->page->course->id);
        $html_str .= html_add_rm_js_button($CFG->wwwroot, false, $unlisted_books);
        $mform->addElement('html', $html_str);
	}
}

