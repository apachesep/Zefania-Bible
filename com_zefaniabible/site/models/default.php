<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabible
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.missionarychurchofgrace.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
* /!\  Joomla! is free software.
* This version may have been modified pursuant to the GNU General Public License,
* and as distributed it includes or is derivative of works licensed under the
* GNU General Public License or other free or open source software licenses.
*
*             .oooO  Oooo.     See COPYRIGHT.php for copyright notices and details.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.modelitem');
/**
 * Zefaniabible Component Zefaniabible Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelDefault extends JModelItem
{

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.

		return parent::getStoreId($id);
	}
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		parent::populateState();
	}

	/**
	 * Method to build a the query string for the Zefaniabibleitem
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery_collation()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->from('`#__zefaniabible_bible_text`');
			$db->setQuery($query);
			$data = $db->getCollation();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	public function fnc_make_verse($str_Bible_Version,$int_book_id,$int_bible_chapter,$str_start_verse,$str_end_verse)
	{
		try 
		{
			$db = $this->getDbo();
			$str_Bible_Version 	= $db->quote($str_Bible_Version);
			$int_book_id 		= $db->quote($int_book_id);
			$int_bible_chapter 	= $db->quote($int_bible_chapter);	
			$str_start_verse 	= $db->quote($str_start_verse);
			$str_end_verse 		= $db->quote($str_end_verse);
								
			$query  = $db->getQuery(true);
			$query->select('a.verse_id ,a.verse, b.bible_name');
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$query->from('`#__zefaniabible_bible_text` AS a');	
			$query->where("b.alias=".$str_Bible_Version);
			$query->where("a.book_id=".$int_book_id);
			$query->where("a.chapter_id=".$int_bible_chapter);
			if($str_end_verse == 0)
			{
				$query->where("a.verse_id = ".$str_start_verse);
			}
			else
			{
				$query->where("a.verse_id >= ".$str_start_verse);
				$query->where("a.verse_id <= ".$str_end_verse);
			}
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}	
	public function _buildQuery_Single_Reference($int_Bible_Book_ID, $int_Bible_Chapter, $int_Bible_Verse )
	{
		try 
		{
			$db = $this->getDbo();
			$int_Bible_Book_ID 	= $db->quote($int_Bible_Book_ID);
			$int_Bible_Chapter 	= $db->quote($int_Bible_Chapter);
			$int_Bible_Verse 	= $db->quote($int_Bible_Verse);
					
			$query  = $db->getQuery(true);
			$query->select('a.verse_id, a.word, a.reference');
			$query->from('`#__zefaniabible_crossref` AS a');	
			$query->where("a.book_id=".$int_Bible_Book_ID);
			$query->where("a.chapter_id=".$int_Bible_Chapter);
			$query->where("a.verse_id=".$int_Bible_Verse);
			$query->order('a.sort_order');		
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}	
	function _buildQuery_References($int_Bible_Book_ID,$int_Bible_Chapter)
	{
		try 
		{
			$db = $this->getDbo();
			$int_Bible_Book_ID = $db->quote($int_Bible_Book_ID);
			$int_Bible_Chapter = $db->quote($int_Bible_Chapter);
			$query  = $db->getQuery(true);
			$query->select('a.verse_id');
			$query->from('`#__zefaniabible_crossref` AS a');	
			$query->where("a.book_id=".$int_Bible_Book_ID);
			$query->where("a.chapter_id=".$int_Bible_Chapter);
			$query->order('a.verse_id, a.sort_order');		
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_commentary_list()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('title,alias');
			$query->from('`#__zefaniabible_zefaniacomment`');
			$query->where('publish=1');
			$query->order('title');
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}		
	function _buildQuery_commentary_chapter($str_commentary,$int_Bible_Book_ID,$int_Bible_Chapter)
	{
		try 
		{
			$db = $this->getDbo();
			$str_commentary 	= $db->quote($str_commentary);
			$int_Bible_Book_ID 	= $db->quote($int_Bible_Book_ID);
			$int_Bible_Chapter 	= $db->quote($int_Bible_Chapter);
			$query  = $db->getQuery(true);
			$query->select('a.verse_id');
			$query->from('`#__zefaniabible_comment_text` AS a');
			$query->innerjoin('`#__zefaniabible_zefaniacomment` AS b ON a.bible_id = b.id');
			$query->where('b.alias="'.$str_commentary.'"');
			$query->where('a.book_id='.$int_Bible_Book_ID);
			$query->where('a.chapter_id='.$int_Bible_Chapter);
			$query->order('a.verse_id');
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}		
	function _buildQuery_Bibles_Names()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('alias, bible_name');
			$query->from('`#__zefaniabible_bible_names`');	
			$query->where("publish = 1");
			$query->order('bible_name');			
			$db->setQuery($query);
			$data = $db->loadObjectList();			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}	
	function _buildQuery_Chapter($int_Bible_Chapter,$int_Bible_Book_ID,$str_Bible_Version)
	{
		try 
		{
			$db = $this->getDbo();
			$int_Bible_Chapter 	=	$db->quote($int_Bible_Chapter);
			$int_Bible_Book_ID	=	$db->quote($int_Bible_Book_ID);
			$str_Bible_Version	=	$db->quote($str_Bible_Version);
			$query  = $db->getQuery(true);
			$query->select('a.verse_id, a.verse');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin("`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id");
			$query->where("a.chapter_id=".$int_Bible_Chapter);
			$query->where("a.book_id=".$int_Bible_Book_ID);
			$query->where("b.alias=".$str_Bible_Version);
			$query->order('a.verse_id ASC');			
			$db->setQuery($query);
			$data = $db->loadObjectList();			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}	
	function _buildQuery_Max_Verse($int_Bible_Book_ID,$int_Bible_Chapter)
	{
		try 
		{
			$db = $this->getDbo();
			$int_Bible_Book_ID 	=	$db->quote($int_Bible_Book_ID);
			$int_Bible_Chapter 	=	$db->quote($int_Bible_Chapter);
			$query  = $db->getQuery(true);
			$query->select('Max(verse_id)');
			$query->from('`#__zefaniabible_bible_text`');	
			$query->where("book_id=".$int_Bible_Book_ID);
			$query->where("chapter_id=".$int_Bible_Chapter);
			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}

	function _buildQuery_Max_Chapter($int_Bible_Book_ID)
	{
		try 
		{
			$db = $this->getDbo();
			$int_Bible_Book_ID =	$db->quote($int_Bible_Book_ID);
			$query  = $db->getQuery(true);
			$query->select('Max(chapter_id)');
			$query->from('`#__zefaniabible_bible_text`');	
			$query->where("book_id=".$int_Bible_Book_ID);			
			$db->setQuery($query);
			$data = $db->loadResult();			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function _buildQuery_max_reading_days($str_alias)
	{
		try 
		{
			$db = $this->getDbo();
			$str_alias = $db->quote($str_alias);
			$query  = $db->getQuery(true);
			$query->select('Max(a.day_number)');
			$query->from('`#__zefaniabible_zefaniareadingdetails` AS a');	
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS b ON a.plan = b.id');
			$query->where("b.alias=".$str_alias);
			$db->setQuery($query);
			$data = $db->loadResult();	
			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}
	function _buildQuery_max_verse_of_day_verse()
	{
		try 
		{
			$db = $this->getDbo();
			$str_alias = $db->quote($str_alias);
			$query  = $db->getQuery(true);
			$query->select('Max(ordering)');
			$query->from('`#__zefaniabible_zefaniaverseofday`');	
			$query->where("publish=1");
			$db->setQuery($query);
			$data = $db->loadResult();	
			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}
	
	function _buildQuery_first_record()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('alias');
			$query->from('`#__zefaniabible_bible_names`');	
			$query->where("publish = 1");
			$query->order('id');		
			$db->setQuery($query,0, 1);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	function _buildQuery_first_plan()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('alias');
			$query->from('`#__zefaniabible_zefaniareading`');	
			$query->where("publish = 1");
			$query->order('id');		
			$db->setQuery($query,0, 1);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	function _buildQuery_dictionary_list()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('name,alias');
			$query->from('`#__zefaniabible_dictionary_info`');	
			$query->where("publish = 1");
			$query->order('name');		
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	function _buildQuery_reading_plan($str_reading_plan,$int_day_number) 
	{
		try 
		{
			$db = $this->getDbo();
			$str_reading_plan 	=	$db->quote($str_reading_plan);
			$int_day_number 	=	$db->quote($int_day_number);
			$query  = $db->getQuery(true);
			$query->select('a.name, a.alias, b.plan, b.book_id, b.begin_chapter, b.begin_verse, b.end_chapter, b.end_verse');
			$query->from('`#__zefaniabible_zefaniareading` AS a');
			$query->innerJoin("`#__zefaniabible_zefaniareadingdetails` AS b ON a.id = b.plan");
			$query->where("a.alias=".$str_reading_plan);
			$query->where("a.publish=1");
			$query->where("b.day_number=".$int_day_number);
			$query->order('b.plan');
			$query->order('b.book_id');
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_reading_plan_list()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('name,alias');
			$query->from('`#__zefaniabible_zefaniareading`');
			$query->where("publish=1");
			$query->order('name');
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_commentary_name($str_commentary)
	{
		try 
		{
			$db = $this->getDbo();
			$str_commentary 			= $db->quote($str_commentary);
			$query  = $db->getQuery(true);
			$query->select('a.title');
			$query->from('`#__zefaniabible_zefaniacomment` AS a');
			$query->where('a.alias="'.$str_commentary.'"');
			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}	
	function _buildQuery_commentary_verse($str_commentary, $int_Bible_Book_ID, $int_Bible_Chapter, $int_Bible_Verse)
	{
		try 
		{
			$db = $this->getDbo();
			$str_commentary 			= $db->quote($str_commentary);
			$int_Bible_Book_ID 		= $db->quote($int_Bible_Book_ID);
			$int_Bible_Chapter 		= $db->quote($int_Bible_Chapter);
			$int_Bible_Verse 		= $db->quote($int_Bible_Verse);			
			$query  = $db->getQuery(true);
			$query->select('a.verse');
			$query->from('`#__zefaniabible_comment_text` AS a');
			$query->innerjoin('`#__zefaniabible_zefaniacomment` AS b ON a.bible_id = b.id');
			$query->where('b.alias='.$str_commentary);
			$query->where('a.book_id='.$int_Bible_Book_ID);
			$query->where('a.chapter_id='.$int_Bible_Chapter);
			$query->where('a.verse_id='.$int_Bible_Verse);
			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		
		return $data;			
	}
	function _buildQuery_get_verse_of_the_day_info($int_day_diff)
	{
		try
		{
			$db = JFactory::getDBO();
			$int_day_diff 	= $db->quote($int_day_diff);
			$query  = $db->getQuery(true);	
			$query->select('book_name, chapter_number, begin_verse, end_verse');
			$query->from('`#__zefaniabible_zefaniaverseofday` AS a');
			$query->where("ordering=".$int_day_diff);
			$query->where("publish=1");
			$db->setQuery($query,0, 1);
			$data = $db->loadObjectList(); 
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function _buildQuery_get_verse_of_the_day($arr_verse_info, $str_Bible_Version)
	{
		try
		{
			$db = JFactory::getDBO();
			$str_Bible_Version 		= $db->quote($str_Bible_Version);
			$query  = $db->getQuery(true);	
			$query->select('a.verse');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin("`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id");
			$query->where("b.alias=".$str_Bible_Version);
			$query->where("a.book_id=".$arr_verse_info->book_name);
			$query->where("a.chapter_id=".$arr_verse_info->chapter_number);
			if($arr_verse_info->end_verse = 0)
			{
				$query->where("a.verse_id=".$arr_verse_info->begin_verse);
			}
			else
			{
				$query->where("a.verse_id>=".$arr_verse_info->begin_verse);
				$query->where("a.verse_id<=".$arr_verse_info->end_verse);
			}
			$query->order('a.verse_id ASC');
			
			$db->setQuery($query);
			$data = $db->loadObjectList(); 
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function _buildQuery_current_reading($arr_reading, $str_Bible_Version)
	{
		$data = '';
		$x = 0;
		$arr_data = array();
		try 
		{
			foreach($arr_reading as $reading)
			{
				$db = $this->getDbo();
				$int_book_id 			= $db->quote($reading->book_id);
				$int_begin_chapter 		= $db->quote($reading->begin_chapter);
				$int_begin_verse 		= $db->quote($reading->begin_verse);
				$int_end_chapter 		= $db->quote($reading->end_chapter);
				$int_end_verse 			= $db->quote($reading->end_verse);
				$str_Bible_Version 		= $db->quote($str_Bible_Version);
				
				$query  = $db->getQuery(true);	
				$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse');
				$query->from('`#__zefaniabible_bible_text` AS a');
				$query->innerJoin("`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id");
				$query->where("a.book_id=".$int_book_id);
				$query->where("a.chapter_id>=".$int_begin_chapter);
				$query->where("a.chapter_id<=".$int_end_chapter);	
				if($int_begin_verse != 0)
				{
					$query->where("a.verse_id>=".$int_begin_verse);
					$query->where("a.verse_id<=".$int_end_verse);
				}
				$query->where("b.alias=".$str_Bible_Version);
				$query->order('a.book_id ASC');
				$query->order('a.chapter_id ASC');
				$query->order('a.verse_id ASC');
								
				$db->setQuery($query);
				$data = $db->loadObjectList(); 		
				$arr_data[$x] = $data;
				$x++;
			}
		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $arr_data;		
	}	
}
