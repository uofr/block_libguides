<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package blocks/bookshelf
 * @subpackage backup-moodle2
 * @copyright 2012 University of Regina
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Specialised restore task for the html block
 * (requires encode_content_links in some configdata attrs)
 *
 * TODO: Finish phpdocs
 */

require_once($CFG->dirroot . '/blocks/bookshelf/backup/moodle2/restore_bookshelf_stepslib.php'); 
 
class restore_bookshelf_block_task extends restore_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
        $this->add_step(new restore_bookshelf_block_structure_step('bookshelf_structure',
            'bookshelf.xml'));
    }
    

    public function get_fileareas() {
        return array(); // No associated fileareas
    }

    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata
    }

    /**
     * This function, executed after all the tasks in the plan
     * have been executed, will perform the recode of the
     * target books for the block. This must be done here
     * and not in normal execution steps because the book
     * may be restored after the block.
     */
	 
	public function after_restore() {
        global $DB;

        // Get the blockid
        $blockid = $this->get_blockid();

        // Extract block configdata and update it to point to the new bookshelf
        if ($configdata = $DB->get_field('block_instances', 'configdata', array('id' => $blockid))) {
            $config = unserialize(base64_decode($configdata));
			
			
            if (!empty($config->bookshelf)) {
                // Get bookshelf mapping and replace it in config
                if ($bookshelfmap = restore_dbops::get_backup_ids_record($this->get_restoreid(), 'bookshelf', $config->bookshelf)) {
                    $config->bookshelf = $bookshelfmap->newitemid;
                    $configdata = base64_encode(serialize($config));
                    $DB->set_field('block_instances', 'configdata', $configdata, array('id' => $blockid));
                }
            }
        }
		
		
		// Update the bookshelf items to point to the new instances
        $sql = 'SELECT id,book FROM {bookshelf}
                WHERE course = :course
				ORDER BY sortorder ASC
        ';
		$params = array('course' => $this->get_courseid());
        $books = $DB->get_records_sql($sql, $params);
        
		foreach ($books as $book) {
            // Get book mapping and replace it
            if ($bookmap = restore_dbops::get_backup_ids_record($this->get_restoreid(), 'book', $book->book)) {
                $DB->set_field('bookshelf', 'book', $bookmap->newitemid, array('id' => $book->id));
				//$params = array('course' => $this->get_courseid(), 'map' => $bookmap->newitemid, 'config' => $book->id);
				//$DB->insert_record('bookshelf_debug', $params);
            }
		}
		
    }
	
    static public function define_decode_contents() {
        return array();
    }

    static public function define_decode_rules() {
        return array();
    }
    
}
