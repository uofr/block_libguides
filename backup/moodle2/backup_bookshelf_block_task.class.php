<?php

/**
 * Specialised backup task for the bookshelf block
 * (requires encode_content_links in some configdata attrs)
 */

 require_once($CFG->dirroot . '/blocks/bookshelf/backup/moodle2/backup_bookshelf_stepslib.php'); 
 
class backup_bookshelf_block_task extends backup_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
		// bookshelf has one structure step
		$this->add_step(new backup_bookshelf_block_structure_step('bookshelf_structure', 'bookshelf.xml'));
    }

    public function get_fileareas() {
        return array(); // No associated fileareas
    }

    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata
    }

    static public function encode_content_links($content) {
        return $content; // No special encoding of links
    }
}