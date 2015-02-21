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
	private $lang;
	private $str_lang_tag;
	private $user;
	private $sql_access_statement;
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
	public function __construct()
	{
		$this->lang 				= JFactory::getLanguage();
		$this->user 				= JFactory::getUser();
		$this->str_lang_tag 		= $this->lang->getTag();
		$arr_access_groups 			= array_unique($this->user->getAuthorisedViewLevels());
		$x = 1;
		foreach ($arr_access_groups as $group)
		{
			$this->sql_access_statement .= $group;
			if($x < count($arr_access_groups) )
			{
				$this->sql_access_statement .= ",";
			}
			$x++;
		}		
		$this->sql_access_statement = "access IN (".$this->sql_access_statement.")";
		parent::__construct();
	}
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
		// get Database Collation returns string 
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
	public function fnc_next_auto_increment()
	{
		try 
		{		
			$db = $this->getDbo();
			//$query  = $db->getQuery(true);
			 
			//$query->select('Max(id)');
			//$query->from('c');	
			//$query = "SHOW TABLE STATUS LIKE '#__zefaniabible_bible_names'";
				$query = "SELECT AUTO_INCREMENT AS id FROM information_schema.tables WHERE table_name= `#__zefaniabible_bible_names`";
			$db->setQuery($query);
			echo $query;
			$data = $db->loadResult();
			echo $data;
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	public function fnc_make_verse($str_Bible_Version,$int_book_id,$int_bible_chapter,$str_start_verse,$str_end_verse)
	{
		// Make a scripture verse, returns an array object
		try 
		{
			$db = $this->getDbo();
			$str_lang_tag 		= $db->quote($this->str_lang_tag);
			$str_Bible_Version 	= $db->quote($str_Bible_Version);
			$int_book_id 		= $db->quote($int_book_id);
			$int_bible_chapter 	= $db->quote($int_bible_chapter);	
			$str_start_verse 	= $db->quote($str_start_verse);
			$str_end_verse_raw	= $str_end_verse;
			$str_end_verse 		= $db->quote($str_end_verse);
											
			$query  = $db->getQuery(true);
			$query->select('a.verse_id ,a.verse, b.bible_name');
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$query->from('`#__zefaniabible_bible_text` AS a');	
			$query->where("b.alias=".$str_Bible_Version);
			$query->where("a.book_id=".$int_book_id);
			$query->where("a.chapter_id=".$int_bible_chapter);
			if($str_end_verse_raw == 0)
			{
				$query->where("a.verse_id = ".$str_start_verse);
			}
			else
			{
				$query->where("a.verse_id >= ".$str_start_verse);
				$query->where("a.verse_id <= ".$str_end_verse);
			}
			$query->where("b.published=1");
			$query->where("(b.language=".$str_lang_tag." OR b.language='all-ALL')");
			
			$sql_access_statement = str_replace("access","b.access",$this->sql_access_statement);	
			$query->where("(".$sql_access_statement.")");
			
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
		// Get a single verse reference list, returns an array object
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
		// Get a list of references, returns a array object
		try 
		{
			$db = $this->getDbo();
			$int_Bible_Book_ID = $db->quote($int_Bible_Book_ID);
			$int_Bible_Chapter = $db->quote($int_Bible_Chapter);
			$query  = $db->getQuery(true);
			$query->select('a.book_id, a.chapter_id, a.verse_id, reference');
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
		// Get a list of commentaries that are pubished and matched. Returns an array object
		try 
		{
			
			$db = $this->getDbo();
			$str_lang_tag = $db->quote($this->str_lang_tag);
			$query  = $db->getQuery(true);
			$query->select('title,alias');
			$query->from('`#__zefaniabible_zefaniacomment`');
			$query->where('published=1');
			$query->where("(language=".$str_lang_tag." OR language='all-ALL')");
				
			$query->where("(".$this->sql_access_statement.")");
						
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
		// Make a commentary Chapter list, used to a list of available commentaries. Returns array object
		try 
		{
			
			$db = $this->getDbo();
			$str_lang_tag 		= $db->quote($this->str_lang_tag);
			$str_commentary 	= $db->quote($str_commentary);
			$int_Bible_Book_ID 	= $db->quote($int_Bible_Book_ID);
			$int_Bible_Chapter 	= $db->quote($int_Bible_Chapter);
			$query  = $db->getQuery(true);
			$query->select('a.book_id, a.chapter_id, a.verse_id');
			$query->from('`#__zefaniabible_comment_text` AS a');
			$query->innerjoin('`#__zefaniabible_zefaniacomment` AS b ON a.bible_id = b.id');
			$query->where('b.alias='.$str_commentary);
			$query->where('a.book_id='.$int_Bible_Book_ID);
			$query->where('a.chapter_id='.$int_Bible_Chapter);
			$query->where("(b.language=".$str_lang_tag." OR b.language='all-ALL')");
			
			$sql_access_statement = str_replace("access","b.access",$this->sql_access_statement);	
			$query->where("(".$sql_access_statement.")");			
			
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
	function _buildQuery_Dictionary_Names_All()
	{
		// Return array object list of all entered dictionaries names, includes unpublished.
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('alias, name, id');
			$query->from('`#__zefaniabible_dictionary_info`');
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
	function _buildQuery_Commentary_Names_All()
	{
		// Return array object list of all Commentary names, includes unpublished.
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('alias, title, id');
			$query->from('`#__zefaniabible_zefaniacomment`');
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
	function _buildQuery_Bibles_Names_All()
	{
		// Return array object list of all Bible names, includes unpublished.
		try 
		{			
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('alias, bible_name, id');
			$query->from('`#__zefaniabible_bible_names`');
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
	function _buildQuery_Bibles_Names()
	{
		// Get a array list of only published Bibles and those that match langauge and access criteria.
		try 
		{					
			$db = $this->getDbo();
			$str_lang_tag = $db->quote($this->str_lang_tag);
						
			$query  = $db->getQuery(true);
			$query->select('alias, bible_name');
			$query->from('`#__zefaniabible_bible_names`');	
			$query->where("published=1");
			$query->where("(language=".$str_lang_tag." OR language='all-ALL')");
			$query->where("(".$this->sql_access_statement.")");
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
		// Get an array object of particular Bible chapter
		try 
		{
					
			$db = $this->getDbo();
			$str_lang_tag 		= 	$db->quote($this->str_lang_tag);			
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
			$query->where("b.published=1");
			$query->where("(b.language=".$str_lang_tag." OR language='all-ALL')");
			
			$sql_access_statement = str_replace("access","b.access",$this->sql_access_statement);
			$query->where("(".$sql_access_statement.")");
			
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
	function _buildQuery_Bible_meta($str_alias)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$str_alias_clean	= 	$db->quote($str_alias);
			$query->select('metadata, metakey, metadesc');	
			$query->from('`#__zefaniabible_bible_names`');
			$query->where("alias=".$str_alias_clean);
			$db->setQuery($query);
			$data = $db->loadObjectList();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;				
	}
	function _buildQuery_meta($str_alias, $str_table)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$str_alias_clean	= 	$db->quote($str_alias);
			$query->select('metadata, metakey, metadesc');
			switch($str_table)
			{
				case "commentary":
					$query->from('`#__zefaniabible_zefaniacomment`');
					break;
				case "dictionary":
					$query->from('`#__zefaniabible_dictionary_info`');
					break;
				default:
					$query->from('`#__zefaniabible_bible_names`');
					break;
			}
			$query->where("alias=".$str_alias_clean);
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
	public function fnc_count_publications($str_table)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('count(1)');			
			switch($str_table)
			{
				case "comment":
					$query->from('`#__zefaniabible_zefaniacomment`');
					break;
				case "dict":
					$query->from('`#__zefaniabible_dictionary_info`');
					break;
				default:
					$query->from('`#__zefaniabible_bible_names`');
					break;
			}

			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}		
	public function fnc_count_comment_verses($id)
	{
		try 
		{
			$db = $this->getDbo();
			$int_ID 	=	$db->quote($id);
			$query  = $db->getQuery(true);
			$query->select('count(1)');			
			$query->from('`#__zefaniabible_comment_text`');
			$query->where("bible_id=".$int_ID);			
			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}		
	public function fnc_count_dict_verses($id)
	{
		try 
		{
			$db = $this->getDbo();
			$int_ID 	=	$db->quote($id);
			$query  = $db->getQuery(true);
			$query->select('count(1)');			
			$query->from('`#__zefaniabible_dictionary_detail`');
			$query->where("dict_id=".$int_ID);			
			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}		
	public function fnc_count_bible_verses($id)
	{
		try 
		{
			$db = $this->getDbo();
			$int_Bible_Book_ID 	=	$db->quote($id);
			$query  = $db->getQuery(true);
			$query->select('count(1)');			
			$query->from('`#__zefaniabible_bible_text`');
			$query->where("bible_id=".$int_Bible_Book_ID);			
			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}	
	function _buildQuery_Max_Bible_Chapters($alias)
	{
		try 
		{
			$db = $this->getDbo();
			$alias_clean =	$db->quote($alias);
			$query  = $db->getQuery(true);
			$query->select('a.book_id, Max(a.chapter_id) AS max_chapter');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin("`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id");
			$query->where("b.alias=".$alias_clean);		
			$query->group('a.book_id');
			$db->setQuery($query);
			$data = $db->loadObjectList();			
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
			$query  = $db->getQuery(true);
			$query->select('Max(ordering)');
			$query->from('`#__zefaniabible_zefaniaverseofday`');	
			$query->where("published=1");
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
			$query->where("published=1");
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
			$query->where("published=1");
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
			$str_lang_tag = $db->quote($this->str_lang_tag);
			$query  = $db->getQuery(true);
			$query->select('name,alias');
			$query->from('`#__zefaniabible_dictionary_info`');	
			$query->where("published=1");
			$query->where("(language=".$str_lang_tag." OR language='all-ALL')");
		
			$query->where("(".$this->sql_access_statement.")");
						
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
	function _buildQuery_dict_name($str_alias)
	{
		try 
		{							
			$db		= JFactory::getDbo();
			$str_alias_clean 	=	$db->quote($str_alias);
			$query  = $db->getQuery(true);
			$query->select('name');
			$query->from('`#__zefaniabible_dictionary_info`');						
			$query->where('alias ='.$str_alias_clean);
			$db->setQuery($query,0,1);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}
	function _buildQuery_strong($str_alias, $strong_id)
	{
		try 
		{							
			$db		= JFactory::getDbo();
			$str_alias_clean 		=	$db->quote($str_alias);
			$strong_id_clean 		=	$db->quote($strong_id);
			$query  = $db->getQuery(true);
			$query->select('a.item, a.description');
			$query->from('`#__zefaniabible_dictionary_detail` AS a');			
			$query->innerJoin('`#__zefaniabible_dictionary_info` AS b ON a.dict_id = b.id');
			
			$query->where('b.alias ='.$str_alias_clean);	
			$query->where('a.item ='.$strong_id_clean);
			$query->order('a.id ASC');	
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
			$str_lang_tag 		= 	$db->quote($this->str_lang_tag);
			$str_reading_plan 	=	$db->quote($str_reading_plan);
			$int_day_number 	=	$db->quote($int_day_number);
			$query  = $db->getQuery(true);
			$query->select('a.name, a.alias, b.plan, b.book_id, b.begin_chapter, b.begin_verse, b.end_chapter, b.end_verse');
			$query->from('`#__zefaniabible_zefaniareading` AS a');
			$query->innerJoin("`#__zefaniabible_zefaniareadingdetails` AS b ON a.id = b.plan");
			$query->where("a.alias=".$str_reading_plan);
			$query->where("a.published=1");
			$query->where("(a.language=".$str_lang_tag." OR a.language='all-ALL')");
			
			$sql_access_statement = str_replace("access","a.access",$this->sql_access_statement);	
			$query->where("(".$sql_access_statement.")");			
			
			$query->where("b.day_number=".$int_day_number);			
			$query->order('b.plan');
			$query->order('b.book_id');
			$query->order('b.begin_chapter');
			$query->order('b.begin_verse');
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_reading_plan_list_All()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('name, alias, description, id');
			$query->from('`#__zefaniabible_zefaniareading`');
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
	function _buildQuery_reading_plan_list()
	{
		try 
		{
			
			$db = $this->getDbo();
			$str_lang_tag = $db->quote($this->str_lang_tag);
			$query  = $db->getQuery(true);
			$query->select('name, alias, description');
			$query->from('`#__zefaniabible_zefaniareading`');
			$query->where("published=1");
			$query->where("(language=".$str_lang_tag." OR language='all-ALL')");
			
			$query->where("(".$this->sql_access_statement.")");
			
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
			$str_lang_tag 			= $db->quote($this->str_lang_tag);
			$str_commentary 		= $db->quote($str_commentary);
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
			$query->where('b.published=1');
			$query->where("(b.language=".$str_lang_tag." OR b.language='all-ALL')");
			
			$sql_access_statement = str_replace("access","b.access",$this->sql_access_statement);	
			$query->where("(".$sql_access_statement.")");
						
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
			$query->from('`#__zefaniabible_zefaniaverseofday`');
			$query->where("published=1");
			$query->where("ordering=".$int_day_diff);
			$db->setQuery($query,0,1);
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
			
			foreach($arr_verse_info as $obj_verse_info)
			{
				$str_lang_tag 		= $db->quote($this->str_lang_tag);
				$int_book_name 		= $db->quote($obj_verse_info->book_name);
				$int_chapter_number = $db->quote($obj_verse_info->chapter_number);
				$int_begin_verse	= $db->quote($obj_verse_info->begin_verse);
				$int_end_verse_raw 	= $obj_verse_info->end_verse;
				$int_end_verse		= $db->quote($obj_verse_info->end_verse);
			}
			$str_Bible_Version 		= $db->quote($str_Bible_Version);
			$query  = $db->getQuery(true);	
			$query->select('a.verse');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin("`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id");
			$query->where("b.alias=".$str_Bible_Version);
			$query->where("b.published=1");
			$query->where("(b.language=".$str_lang_tag." OR b.language='all-ALL')");
			
			$sql_access_statement = str_replace("access","b.access",$this->sql_access_statement);
			$query->where("(".$sql_access_statement.")");
			
			$query->where("a.book_id=".$int_book_name);
			$query->where("a.chapter_id=".$int_chapter_number);
			if($int_end_verse_raw == '0')
			{
				$query->where("a.verse_id=".$int_begin_verse);
			}
			else
			{
				$query->where("a.verse_id>=".$int_begin_verse);
				$query->where("a.verse_id<=".$int_end_verse);
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
				$str_lang_tag 				= $db->quote($this->str_lang_tag);
				$int_book_id 				= $db->quote($reading->book_id);
				$int_begin_chapter 			= $db->quote($reading->begin_chapter);
				$int_begin_verse_raw		= $reading->begin_verse;
				$int_begin_verse 			= $db->quote($reading->begin_verse);
				$int_end_chapter 			= $db->quote($reading->end_chapter);
				$int_end_verse 				= $db->quote($reading->end_verse);			
				$str_Bible_Version_clean	= $db->quote($str_Bible_Version);
				
				$query  = $db->getQuery(true);	
				$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse');
				$query->from('`#__zefaniabible_bible_text` AS a');
				$query->innerJoin("`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id");
				$query->where("a.book_id=".$int_book_id);
				$query->where("a.chapter_id>=".$int_begin_chapter);
				$query->where("a.chapter_id<=".$int_end_chapter);	
				if($int_begin_verse_raw != 0)
				{
					$query->where("a.verse_id>=".$int_begin_verse);
					$query->where("a.verse_id<=".$int_end_verse);
				}
				$query->where("b.alias=".$str_Bible_Version_clean);
				$query->where("b.published=1");
				$query->where("(b.language=".$str_lang_tag." OR b.language='all-ALL')");
				
				$sql_access_statement = str_replace("access","b.access",$this->sql_access_statement);
				$query->where("(".$sql_access_statement.")");
			
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
	function _get_pagination_verseofday()
	{
		try 
		{
			$mainframe = JFactory::getApplication();			
			$jinput = JFactory::getApplication()->input;
			$lim = $mainframe->getUserStateFromRequest('$option.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$lim0	= $jinput->get('limitstart', 0, 'INT');
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(ordering)');
			$query->from('`#__zefaniabible_zefaniaverseofday`');	
			$query->where("published=1");
			$db->setQuery($query);
			$data = new JPagination( $db->loadResult(), $lim0, $lim );
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_getUserData($int_id)
	{
		try 
		{
			$db = $this->getDbo();
			$int_id_clean	= $db->quote($int_id);
			$query  = $db->getQuery(true);
			$query->select('user.reading_start_date,bible.alias as bible_alias,plan.alias as plan_alias');
			$query->from('`#__zefaniabible_zefaniauser` AS user');	
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS plan ON user.plan = plan.id');
			$query->innerJoin('`#__zefaniabible_bible_names` AS bible ON user.bible_version = bible.id');			
			$query->where("user.user_id=".$int_id_clean);
			$db->setQuery($query,0, 1);
			$data = $db->loadObjectList();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}	
	function _get_pagination_readingplan_overview($alias)
	{
		try 
		{
			
			$db = JFactory::getDBO();
			$str_lang_tag 	= $db->quote($this->str_lang_tag);
			$alias 			= $db->quote($alias);
			$mainframe = JFactory::getApplication();
			$jinput = JFactory::getApplication()->input;			
			$lim = $mainframe->getUserStateFromRequest('$option.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$lim0	= $jinput->get('limitstart', 0, 'INT');	
			$query  = $db->getQuery(true);
			$query->select('Max(details.day_number)');
			$query->from('`#__zefaniabible_zefaniareadingdetails` AS details');	
			$query->innerJoin("`#__zefaniabible_zefaniareading` AS plan ON details.plan = plan.id");
			$query->where("plan.published=1");
			$query->where("(plan.language=".$str_lang_tag." OR plan.language='all-ALL')");
			
			$sql_access_statement = str_replace("access","plan.access",$this->sql_access_statement);	
			$query->where("(".$sql_access_statement.")");
			
			$query->where("plan.alias=".$alias);
						
			$db->setQuery($query);
			$data = new JPagination( $db->loadResult(), $lim0, $lim );
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_readingplan_overview($alias, $pagination)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$alias 			= $db->quote($alias);
			$limitstart		= $db->quote($pagination->limitstart);
			$limit 			= $db->quote($pagination->limitstart + $pagination->limit);
			$query->select('plan.book_id, plan.begin_chapter, plan.begin_verse, plan.end_chapter, plan.end_verse, plan.day_number');
			$query->from('`#__zefaniabible_zefaniareading` AS reading');
			$query->innerJoin("`#__zefaniabible_zefaniareadingdetails` AS plan ON reading.id = plan.plan");
			$query->where("reading.alias=".$alias);
			$query->where("plan.day_number > ".$limitstart);
			$query->where("plan.day_number <=".$limit);
			$query->order('plan.day_number');
			$query->order('plan.book_id');
			$query->order('plan.begin_chapter');
			
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_get_menu_id($str_view)
	{
		try 
		{	
			$db = JFactory::getDBO();
			$str_view_clean	= $db->quote('%view='.$str_view.'&%');
			$query  = $db->getQuery(true);
			$query->select('id');
			$query->from('`#__menu`');
			$query->where("(link LIKE ". $str_view_clean." AND link LIKE '%option=com_zefaniabible%')");
			$db->setQuery($query,0,1);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}	
		return $data;		
	}
	function _buildQuery_verseofday($pagination)
	{
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('a.book_name, a.chapter_number, a.begin_verse, a.end_verse');
			$query->from('`#__zefaniabible_zefaniaverseofday` AS a');
			$query->where("a.published=1");
			$query->order('a.ordering');
			
			$db->setQuery($query, $pagination->limitstart, $pagination->limit);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}	
		return $data;
	}
	function _buildQuery_get_verses($arr_Verse_Of_Day, $str_primary_bible)
	{
		$x=0;
		$arr_data = '';
		try
		{
			foreach($arr_Verse_Of_Day as $arr_verse)
			{
				$db = JFactory::getDBO();
				$str_primary_bible_escapped	= 	$db->quote($str_primary_bible);
				$int_book 					= 	$db->quote($arr_verse->book_name);
				$int_chpater 				= 	$db->quote($arr_verse->chapter_number);
				$int_begin_verse 			=	$db->quote($arr_verse->begin_verse);
				$int_end_verse_raw			=	$arr_verse->end_verse;
				$int_end_verse				=	$db->quote($arr_verse->end_verse);
								
				$query  = $db->getQuery(true);
				$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse');
				$query->from('`#__zefaniabible_bible_text` AS a');
				$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
				$query->where("a.book_id=".$int_book);
				$query->where("a.chapter_id=".$int_chpater);
				$query->where("b.alias=".$str_primary_bible_escapped);
				if($int_end_verse_raw == 0)
				{				
					$query->where("a.verse_id=".$int_begin_verse);
				}
				else
				{
					$query->where("a.verse_id>=".$int_begin_verse);
					$query->where("a.verse_id<=".$int_end_verse);
				}				
				$query->order("a.book_id, a.chapter_id, a.verse_id");
				

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
	function _buildQuery_Chapter_List($alias)
	{
		try 
		{							
			$db		= JFactory::getDbo();
			$query  = $db->getQuery(true);
			$query->select('a.book_id, a.chapter_id, b.alias');
			$query->from('`#__zefaniabible_bible_text` AS a');			
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			if($alias != '')
			{
				$alias 	= $db->quote($alias);
				$query->where('b.alias ='.$alias);
			}
			$query->where('a.verse_id =1');			
			$query->order('b.alias ASC, a.book_id ASC, a.chapter_id ASC, a.verse_id ASC');	
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	 
	}
	function _buildQuery_Bible_info($str_Bible_Alias)
	{
		try 
		{
			
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$str_lang_tag 		= 	$db->quote($this->str_lang_tag);
			$str_Bible_Alias 	= 	$db->quote($str_Bible_Alias);
			$query->select('alias, bible_name, xml_audio_url');
			$query->from('`#__zefaniabible_bible_names`');		
			$query->where("published=1");
			$query->where("(language=".$str_lang_tag." OR language='all-ALL')");
			
			$query->where("(".$this->sql_access_statement.")");
			
			$query->where("alias=".$str_Bible_Alias);
			$query->order('bible_name');	
			
			$db->setQuery($query,0, 1);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function _buildQuery_InsertUser($item)
	{
		try 
		{							
			$db		= JFactory::getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(id)');
			$query->from('`#__zefaniabible_zefaniauser`');
			$db->setQuery($query);
			$int_max_ids = $db->loadResult();
			
			$query = '';
			$db		= JFactory::getDbo();
			$query  = $db->getQuery(true);
			$str_Bible_Version =	$db->quote($item->str_Bible_Version);
			$query->select('id');
			$query->from('`#__zefaniabible_bible_names`');
			$query->where('alias = '.$str_Bible_Version);
			$db->setQuery($query);
			$int_bible_version_id = $db->loadResult();
			
			$query = '';
			$db		= JFactory::getDbo();
			$query  = $db->getQuery(true);
			$str_reading_plan = $db->quote($item->str_reading_plan);
			$query->select('id');
			$query->from('`#__zefaniabible_zefaniareading`');
			$query->where('alias = '.$str_reading_plan);
			$db->setQuery($query);
			$int_reading_plan_id = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
		try 
		{		
			if($int_max_ids == "")
			{
				$int_max_ids = 1;	
			}
			else  
			{
				$int_max_ids++;
			}
			$arr_row 							= new stdClass();
			$str_start_date 					= JHtml::date($item->str_start_date,'Y-m-d', true);
			$arr_row->user_name 				= $item->str_user_name;
			$arr_row->plan 						= $int_reading_plan_id;
			$arr_row->bible_version 			= $int_bible_version_id;
			$arr_row->user_id 					= $item->id;
			$arr_row->email 					= $item->str_email;
			$arr_row->send_reading_plan_email 	= $item->flg_send_reading;
			$arr_row->send_verse_of_day_email 	= $item->flg_send_verse;
			$arr_row->reading_start_date 		= $str_start_date;
			$db->insertObject("#__zefaniabible_zefaniauser", $arr_row);
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('ZEFANIABIBLE_CATCHA_SUBMITED'));		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
	}
	function _buildQuery_UpdateUser($str_email,$flg_send_reading,$flg_send_verse)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query  = $db->getQuery(true);
			$str_email	= 	$db->quote($str_email);
			$query->select('id');
			$query->from('`#__zefaniabible_zefaniauser`');
			$query->where('email = '.$str_email);
			$query->where('(send_reading_plan_email = 1 or send_verse_of_day_email = 1 )');
			$db->setQuery($query);
			$int_user_id = $db->loadResult();
			if($int_user_id != '')
			{
				$arr_row->id = 	$int_user_id;
				$arr_row->send_reading_plan_email 	= $flg_send_reading;
				$arr_row->send_verse_of_day_email 	= $flg_send_verse;
				$db->updateObject("#__zefaniabible_zefaniauser", $arr_row, 'id');
				
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('ZEFANIABIBLE_CATCHA_SUBMITED'));	
			}
			else
			{
				JError::raiseWarning('',JText::_('ZEFANIABIBLE_EMAIL_NOT_FOUND'));	
			}
		}
		catch (JException $e)
		{
			$this->setError($e);
		}			
	}
	
	function _buildQuery_scripture($str_alias, $int_Bible_book_id, $int_begin_chap, $int_begin_verse, $int_end_chap, $int_end_verse)
	{
		try 
		{
			
			$params = JComponentHelper::getParams( 'com_zefaniabible' );
			$int_limit_query = $params->get('int_limit_query', '500');
			$db		= JFactory::getDbo();
			$str_lang_tag 				= 	$db->quote($this->str_lang_tag);
			$int_Bible_book_id_clean	= 	$db->quote($int_Bible_book_id);
			$str_alias_clean			=	$db->quote($str_alias);
			$int_begin_chap_clean		=	$db->quote($int_begin_chap);
			$int_end_chap_clean			=	$db->quote($int_end_chap);
			$int_begin_verse_clean		= 	$db->quote($int_begin_verse);
			$int_end_verse_clean		=	$db->quote($int_end_verse);
			
			$query  = $db->getQuery(true);
			$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$query->where('a.book_id='.$int_Bible_book_id_clean);
			$query->where('b.alias='.$str_alias_clean);
			$query->where('b.published=1');
			$query->where("(b.language=".$str_lang_tag." OR b.language='all-ALL')");
			
			$sql_access_statement = str_replace("access","b.access",$this->sql_access_statement);
			$query->where("(".$sql_access_statement.")");
			
			// Genesis 1
			if(($int_begin_chap)and(!$int_end_chap)and(!$int_begin_verse)and(!$int_end_verse))
			{
				$query->where('a.chapter_id='.$int_begin_chap_clean);
				$query->order('a.book_id, a.chapter_id, a.verse_id');
			}
			// Genesis 1-2
			else if(($int_begin_chap)and($int_end_chap)and(!$int_begin_verse)and(!$int_end_verse))
			{
				$query->where('a.chapter_id>='.$int_begin_chap_clean);
				$query->where('a.chapter_id<='.$int_end_chap_clean);
				$query->order('a.book_id, a.chapter_id, a.verse_id');
			}
			// Genesis 1:1
			else if(($int_begin_chap)and(!$int_end_chap)and($int_begin_verse)and(!$int_end_verse))
			{
				$query->where('a.chapter_id='.$int_begin_chap_clean);
				$query->where('a.verse_id='.$int_begin_verse_clean);
				$query->order('a.book_id, a.chapter_id, a.verse_id');
			}
			// Genesis 1:1-2
			else if(($int_begin_chap)and(!$int_end_chap)and($int_begin_verse)and($int_end_verse))
			{
				$query->where('a.chapter_id='.$int_begin_chap_clean);
				$query->where('a.verse_id>='.$int_begin_verse_clean);
				$query->where('a.verse_id<='.$int_end_verse_clean);
				$query->order('a.book_id, a.chapter_id, a.verse_id');
			}
			// Genesis 1:2-2:3
			else if(($int_begin_chap)and($int_end_chap)and($int_begin_verse)and($int_end_verse))
			{		
				if(($int_end_chap - $int_begin_chap) > 1)
				{
					$str_temp = '';
					$y=0;
					for($x = ($int_begin_chap+1); $x < $int_end_chap; $x++)
					{
						if($y > 0 )
						{
							$str_temp .= " OR ";
						}						
						$str_temp .= "a.chapter_id='".$x."'";
						$y++;
					}
					$query->where('(( a.chapter_id='.$int_begin_chap_clean.' AND a.verse_id>='.$int_begin_verse_clean.')OR('.$str_temp.')OR( a.chapter_id='.$int_end_chap_clean.' AND a.verse_id<='.$int_end_verse_clean.'))');
				}
				else
				{
					$query->where('(( a.chapter_id='.$int_begin_chap_clean.' AND a.verse_id>='.$int_begin_verse_clean.')OR( a.chapter_id='.$int_end_chap_clean.' AND a.verse_id<='.$int_end_verse_clean.'))');
				}
				$query->order('a.book_id, a.chapter_id, a.verse_id');
			}
			
			$db->setQuery($query, 0,$int_limit_query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
		return $data;
	}
}
