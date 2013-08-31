<?php 

class block_bookshelf extends block_base
  {

  function init()
    {
    // Required paramaters
    $this->title = get_string('blockname','block_bookshelf');
    $this->version = 2008052000;

    // Optional paramaters
    $this->hideheader = 0;
    }

  # Overide init() variables
  function specialization()
    {
    if (isset($this->config->hideheader))
      {
      $this->hideheader = $this->config->hideheader;
      }
    else
      {
      $this->hideheader = 0;
      }

    if (isset($this->config->title))
      {
      $this->title = format_string($this->config->title);
      }
    else
      {
      $this->title = format_string(get_string('blockname','block_bookshelf'));
      }
    }

  function applicable_formats()
    {
    return array('course-view' => true);
    }

  function instance_allow_multiple()
    {
    return false;
    }
    
  # Allow user to configure block
  function instance_allow_config()
    {
    return true;
    }

  # Block can hide block header
  function hide_header()
    {
    return $this->hideheader;
    }
    
 # Set block width
  function preferred_width()
    {
    return 200;
    }

  function get_content()
    {
    require_once('bookshelf_lib.php');

    validate_bookshelf();

    $book_list = get_bookshelf_list();

    $this->content = new stdClass;
    $this->content->text = $book_list;
    $this->content->footer = '';

    return $this->content;
    }

  function instance_delete()
    {
	global $COURSE;
	
	# remove existing records for this course
    delete_records('bookshelf', 'course', $COURSE->id);
	}

}
?>
