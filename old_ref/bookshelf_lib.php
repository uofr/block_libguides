<?php

global $course_books, $bookshelf, $fullpath;

function get_current_books()
  {
  global $CFG, $COURSE;
  
  if(!$books =  get_all_instances_in_course('book', $COURSE))
    {
    return;
    }
  
  return $books;
  }


function get_current_bookshelf()
  {
  global $CFG, $COURSE;
  
  if(!$current_bookshelf = get_records('bookshelf', 'course', $COURSE->id, 'sortorder', 'id, book, sortorder'))
    {
    return;
    }
  
  return $current_bookshelf;
  }


function get_book_byID($id)
  {
  global $course_books;

  foreach($course_books as $book)
    {
    if($book->id == $id)
      {
      $current_book = array();
      $current_book['id'] = $book->id;
      $current_book['name'] = $book->name;
      $current_book['visible'] = $book->visible;
      $current_book['cmod'] = $book->coursemodule;
      return $current_book;
      }
    }

  return;
  }


function get_max_sortorder()
  {
  global $CFG, $COURSE;
  
  $sql = 'SELECT max(sortorder) as maxval FROM ' . $CFG->prefix . 'bookshelf  where course = ' . $COURSE->id;
  
  if(!$max = get_record_sql($sql))
    {
    return;
    }

  if(isset($max->maxval))
    {
    return $max->maxval;
    }
    
  return -1;
  }


function get_shelf_book_byID($id)
  {
  global $course_books, $bookshelf;

  foreach($course_books as $current_book)
    {
    foreach($bookshelf as $book)
      {
      if($current_book->id == $book->book)
        {
        if ($book->book == $id)
          {
          return $book;
          }
        }      
      }
    }
    
  return;
  }


function get_shelf_book_by_sort($sort)
  {
  global $bookshelf;

  foreach($bookshelf as $book)
    {
    if($book->sortorder == $sort)
      {
      return $book;
      }      
    }
      
  return;
  }


function get_course_bookshelf()
  {
  global $CFG, $COURSE, $course_books, $bookshelf, $fullpath;

  $html = '';
  $book_count = 0;

  if( (empty($course_books)) || (empty($bookshelf)) )
    {
    $html.= '<tr><td align="center" class="error">' . get_string('emptybookshelf', 'block_bookshelf') . '</td>';
    $html.= '<td align="center"></td><td align="center"></td></tr>';
    return $html;  
    }

  $bookshelf_count = count($bookshelf);

  foreach($bookshelf as $book)
    {
    $current_book = get_book_byID($book->book);

    if(!empty($current_book))
      {
      $book_count++;
      $html.= '<tr><td><img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/book.gif" alt="book"  />';
      $html.= $current_book['name'] . '</td>';
      $html.= '<td align="center">';
      if ($book_count != 1)
        {
        $html.= '<a title="Move Up" href="' . $fullpath . '&book=' . $book->book . '&move=-1">';
        $html.= '<img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/up.gif" alt="up"  /></a>&nbsp;';
        }
      if ($book_count != $bookshelf_count)
        {
        $html.= '<a title="Move Down" href="' . $fullpath . '&book=' . $book->book . '&move=1">';
        $html.= '<img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/down.gif" alt="down"  /></a>&nbsp;';
        }
      $html.= '<a title="Remove Book" href="' . $fullpath . '&book=' . $book->book . '&add=-1">';
      $html.= '<img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/delete.gif" alt="delete"  /></a>&nbsp</td>';
      $html.= '<td align="center">';

      if ($current_book['visible'] == 1)
        {
        $html.= '<img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/visible.gif" alt="hidden"  />';
        }
      else
        {
        $html.= '<img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/hidden.gif" alt="hidden"  />';
        }
      $html.= '</td></tr>';
      }
    }

  return $html;
  }
  
  
function get_available_books()
  {
  global $CFG, $COURSE, $course_books, $bookshelf, $fullpath;
  
  $html = '';
  $book_count = 0;

  if(empty($course_books))
    {
    $html.= '<tr><td align="center" class="error">' . get_string('nobooks', 'block_bookshelf') . '</td>';
    $html.= '<td align="center"></td><td align="center"></td></tr>';
    return $html;  
    }

  foreach($course_books as $current_book)
    {
    $active_book = false;
    
    if (!empty($bookshelf))
      {
      foreach($bookshelf as $book)
        {
        if($current_book->id == $book->book)
          {
          $active_book = true;
          }      
        }
      }
      
    if(!$active_book)
      {
      $html.= '<tr><td><img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/book.gif" alt="book"  />';
      $html.= $current_book->name . '</td>';
      $html.= '<td align="center">';
      $html.= '<a title="Add Book" href="' . $fullpath . '&book=' . $current_book->id . '&add=1">';
      $html.= '<img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/add.gif" alt="add"  /></a></td>';
      $html.= '<td align="center">';
      
      if ($current_book->visible == 1)
        {
        $html.= '<img class="activityicon" src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/visible.gif" alt="hidden"  />';
        }
      else
        {
        $html.= '<img class="activityicon" src="' .$CFG->wwwroot . '/blocks/bookshelf/pix/hidden.gif" alt="hidden"  />';
        }
      $html.= '</td></tr>';
      $book_count++;
      }
    }
    
    if($book_count == 0)
      {
      $html.= '<tr><td align="center" class="error">' . get_string('emptybooks', 'block_bookshelf') . '</td>';
      $html.= '<td align="center"></td><td align="center"></td></tr>';
      }

  return $html;
  }


function add_book($book_id)
  {
  global $COURSE, $fullpath;

  unset($obj);
  $obj->course = $COURSE->id;
  $obj->book = $book_id;
  $obj->sortorder = get_max_sortorder() +1;
  $obj->timemodified = time();

  if(!$new_book = insert_record('bookshelf', $obj, true))
    {
    notify("An error occurred - failed to add new book!");
    retrurn;
    }
 
  redirect($fullpath, 'Added new book to Bookshelf', 1);
  }


function remove_book($book_id)
  {
  global $COURSE, $bookshelf, $fullpath;
  
  $order = 0;
  
  # remove existing records
   delete_records('bookshelf', 'course', $COURSE->id);

  # insert all existing books in correct sort order
  foreach($bookshelf as $book)
    {
    if($book->book != $book_id)
      {
      unset($obj);
      $obj->course = $COURSE->id;
      $obj->book = $book->book;
      $obj->sortorder = $order;
      $obj->timemodified = time();
      $order++;
      
      if(!$new_book = insert_record('bookshelf', $obj, true))
        {
        notify("An error occurred - failed to resort books!");
        return;
        }
      }
    }

  redirect($fullpath, 'Removed book from Bookshelf', 1);
  }


function update_book_sort($book_id, $move_val)
  {
  global $CFG, $COURSE, $bookshelf, $fullpath;

  # check for valid book
  if (!$move_book = get_shelf_book_byID($book_id))
    {
    error('Attempt to move unkown book');
    return;
    }
    
  if( ($move_val != 1) && ($move_val != -1) )
    {
    error('Invalid Move parameter supplied');
    return;
    }    

  if(!$temp_book = get_shelf_book_by_sort($move_book->sortorder + $move_val))
    {
    error('Attempt to move unkown existing book');
    return;
    }

  if($move_val ==1)
    {
    $temp_move = -1;
    }
  else
    {
    $temp_move = 1;
    }

  $max = get_max_sortorder();
  if ( ($temp_book->sortorder + $temp_move < 0) || ($temp_book->sortorder + $temp_move > $max) || 
        ($move_book->sortorder +$move_val < 0) || ($move_book->sortorder +$move_val> $max)  )
    {
    notify("An error occurred - unable to move the book to that postion!");
    return;
    }

  # move book currently in the existing sort order
  unset($obj);
  $obj->id = $temp_book->id;
  $obj->course = $COURSE->id;
  $obj->book = $temp_book->book;
  $obj->sortorder =  $temp_book->sortorder + $temp_move;
  $obj->timemodified = time();

  if (!update_record('bookshelf', $obj))
    {
    notify("An error occurred - failed to move existing book!");
    return;
    }
    
  unset($obj);
  $obj->id = $move_book->id;
  $obj->course = $COURSE->id;
  $obj->book = $move_book->book;
  $obj->sortorder =  $move_book->sortorder +$move_val;
  $obj->timemodified = time();
  
  if (!update_record('bookshelf', $obj))
    {
    notify("An error occurred - failed to move current book!");
    return;
    }

  redirect($fullpath, 'Bookshelf updated', 1);
  }
 
  
function validate_bookshelf()
  {
  global $COURSE, $course_books, $bookshelf;
  
  $course_books = get_current_books();
  $bookshelf = get_current_bookshelf();
  
    if(empty( $bookshelf))
    {
    return;
    }
  
  $update_bookshelf = false;
  $valid_books = array();

 foreach($bookshelf as $book)
    {
    if(!$valid_book = get_book_byID($book->book))
      {
      $update_bookshelf = true;
      }
    else
      {
      $valid_books[]= $book->book;
      }
    }
    
  if($update_bookshelf)
    {
    $order = 0;
  
    # remove existing records
    delete_records('bookshelf', 'course', $COURSE->id);

    # insert existing book in correct sort order
    foreach($valid_books as $id)
      {
      unset($obj);
      $obj->course = $COURSE->id;
      $obj->book = $id;
      $obj->sortorder = $order;
      $obj->timemodified = time();
      $order++;
      
      if(!$new_book = insert_record('bookshelf', $obj, true))
        {
        notify("An error occurred - failed to resort books!");
        return;
        }
      }
    }

  return;
  }

function get_bookshelf_list()
  {
  global $CFG, $COURSE, $bookshelf;
    
  $html = '';

  if(empty($bookshelf))
    {
    $html.= '<span>' . get_string('configbookshelf','block_bookshelf') . '<span>';
    return $html;
    }

  $html.= '<script type="text/javascript">function showBookshelf_element(layer)';
  $html.= '{var myLayer = document.getElementById(layer); ';
  $html.= 'if( (myLayer.style.display=="none") || (myLayer.style.display=="") ){myLayer.style.display="block"; myLayer.backgroundPosition="top";} ';
  $html.= 'else {myLayer.style.display="none"; } }</script>';
  
  $img_src1 = $CFG->wwwroot . '/blocks/bookshelf/pix/chapter.gif';
  $img_src2 = $CFG->wwwroot . '/blocks/bookshelf/pix/subchapter.gif';
  
  foreach($bookshelf as $book)
    {
    $current_book = get_book_byID($book->book);
    $chapters = get_book_chapters($book->book);    
    $count_chapters = count($chapters);

    if(!empty($current_book)  && ($current_book['visible'] ==1) && ($count_chapters > 0) )
      {
      $html.= '<div class="bookshelf" onclick="javascript:showBookshelf_element(\'book' . $book->book .'\')">';
      $html.= '<img src="' . $CFG->wwwroot . '/blocks/bookshelf/pix/book.gif" class="icon" alt="book"/>';
	  $html.= $current_book['name'] . '</div>';

      $html.= '<div id="book' . $book->book .'" class="bookshelf_contents">';
      $html.= '<ul class="bookshelf_chapter">';
      foreach($chapters as $chapter)
        {
        $chapter_url = $CFG->wwwroot . '/mod/book/view.php?id=' . $current_book['cmod'] . '&chapterid=' .  $chapter->id;
          
        if ($chapter->subchapter == 0)
          {
          $html.= '<li class="bookshelf_chapterlist"><img src="' . $img_src1 . '" class="iconsmall" />';
          $html.= '<a href="' . $chapter_url . '"><u>' . $chapter->title . '</u></a></li>';
          }
        elseif ($chapter->subchapter == 1)
          {
          $html.= '<li class="bookshelf_subchapterlist"><img src="' . $img_src2 . '" class="iconsmall" />';
          $html.= '<a href="' . $chapter_url . '">' . $chapter->title . '</a></li>';
          }
        else
          {
          $html.= '<li class="bookshelf_subchapterpage"><img src="' . $img_src2 . '" class="iconsmall" />';
          $html.= '<a href="' . $chapter_url . '">' . $chapter->title . '</a></li>';
          }
        }
      $html.= '</ul></div>';
       
	  }
    }
	
  return $html;
  }


function get_book_chapters($book_id)
  {
  global $CFG;
 
   $select = 'bookid = ' . $book_id . ' AND hidden = 0';
   
   if(!$chapters = get_records_select('book_chapters', $select, 'pagenum', 'id, title, subchapter'))
      {
      return;   
      }
	
  return $chapters;
  }


?>