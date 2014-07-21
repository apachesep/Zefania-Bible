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
	function _buildQuery_References($item)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('a.verse_id');
			$query->from('`#__zefaniabible_crossref` AS a');	
			$query->where("a.book_id=".$item->int_Bible_Book_ID);
			$query->where("a.chapter_id=".$item->int_Bible_Chapter);
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
			$query  = $db->getQuery(true);
			$query->select('a.verse_id, a.verse');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin("`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id");
			$query->where("a.chapter_id=".$int_Bible_Chapter);
			$query->where("a.book_id=".$int_Bible_Book_ID);
			$query->where("b.alias='".$str_Bible_Version."'");
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
	function _buildQuery_Max_Verse($item)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(verse_id)');
			$query->from('`#__zefaniabible_bible_text`');	
			$query->where("book_id=".$item->int_Bible_Book_ID);
			$query->where("chapter_id=".$item->int_Bible_Chapter);				
			$db->setQuery($query);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}

	function _buildQuery_Max_Chapter($item)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(chapter_id)');
			$query->from('`#__zefaniabible_bible_text`');	
			$query->where("book_id=".$item->int_Bible_Book_ID);			
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
}
