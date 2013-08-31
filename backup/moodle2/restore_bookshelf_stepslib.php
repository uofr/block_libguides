<?php

/**
 * Define the complete structure for the backup, with file and id annotations
 */
class restore_bookshelf_block_structure_step extends restore_structure_step {

    protected function define_structure() {
        $paths = array();
        $paths[] = new restore_path_element('bookshelf', '/block/bookshelf');
        return $paths;
    }

    public function process_bookshelf($data) {
        global $DB;

        $data = (object)$data;

        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

		$newitemid = $DB->insert_record('bookshelf', $data);
    }

}
