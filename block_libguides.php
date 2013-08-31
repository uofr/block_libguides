<?php

require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

class block_urlibguides extends block_base {
  
  public function init() {
    $this->title = get_string('urlibguides', 'block_urlibguides');
  }

  public function get_content() {
    global $DB, $CFG, $PAGE;
    if ($this->content !== null) {
      return $this->content;
    }

    //$guides = get_listed_libguides($this->page->course->id);
	//var_dump($this->page->course->id);
    $this->content         =  new stdClass;
    //$this->content->text   = ($guides!==false) ? html_list_books_user($guides) : 'This block has no associated libguides.';
    
    $libguides_url = 'http://uregina.libguides.com';
    
    $libguides_src = file_get_contents($libguides_url);
    
    //if nothing returned, display unable to connect
    
    $libguides_browse_by = explode('<div id="browse_by_',$libguides_src);
    
    //if no pieces, something has gone wrong. email developer
    
    $libguides_subject_list = substr($libguides_browse_by[1],9);
    
    //$libguides_subject_list = str_replace('href="/cat.php','href="'.$libguides_url.'/cat.php',$libguides_subject_list);
    //$libguides_author_list = substr($libguides_subject_list,0,-6);
    
    $libguides_subject_list = preg_match_all(
        '/<li><a href="/cat.php?cid=(.*?)">(.*?)<\/a><\/h1>.*?<span class="date">(.*?)<\/span>.*?<div class="section">(.*?)<\/div>.*?<\/li>/s',
        $html,
        $posts, // will contain the blog posts
        PREG_SET_ORDER // formats data into an array of posts
    );

    foreach ($posts as $post) {
        $link = $post[1];
        $title = $post[2];
        $date = $post[3];
        $content = $post[4];

        // do something with data
    }
    
    
    $libguides_author_list = substr($libguides_browse_by[2],30);
    $libguides_author_list = str_replace('href="/cat.php','href="'.$libguides_url.'/cat.php',$libguides_author_list);
    $libguides_author_list = substr($libguides_author_list,0,-6);
    
    
    $this->content->text = '<h3>Browse by subject:</h3>'.$libguides_subject_list;
    
    
    $this->content->text .= '<h3>Browse by author:</h3>'.$libguides_author_list;
    
    return $this->content;
  }

  public function specialization() {
    if (!empty($this->config->title)) {
      $this->title = $this->config->title;
    } else {
      $this->title = get_string('urlibguides', 'block_urlibguides');
    }

    if (empty($this->text)) {
      $this->text = get_string('urlibguides', 'block_urlibguides'); }
  }
	
  /**
  * Serialize and store config data
  */
  public function instance_config_save($data, $nolongerused = false) {
    global $DB;
    
	if (isset($data->config_title)) $this->title = strip_tags($data->config_title);

    // And now forward to the default implementation defined in the parent class
    return parent::instance_config_save($data);
  }

  public function instance_delete() {
    global $DB;
    $DB->delete_records('urlibguides', array('course' => $this->page->course->id));
  }

}
