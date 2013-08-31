<?php

/**
 * Define all the backup steps that wll be used by the backup_bookshelf_block_task
 */
class backup_bookshelf_block_structure_step extends backup_block_structure_step {

    protected function define_structure() {
        global $DB;

        //$userinfo = $this->get_setting_value('userinfo');
        
        // Build the tree
        $bookshelf = new backup_nested_element('bookshelf', array('id'), array(
            'course',
            'book',
            'sortorder',
            'timemodified'));

        $bookshelf->set_source_table('bookshelf',
            array('course' => backup::VAR_COURSEID));

        return $this->prepare_block_structure($bookshelf);
    }
    
}