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

require_once(JPATH_ADMIN_ZEFANIABIBLE .DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'jmodel.list.php');

/**
 * Zefaniabible Component Zefaniabible Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelVerseoftheday extends ZefaniabibleModelList
{
	var $_name_sing = 'verseofthedayitem';
	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(

			);
		}
		parent::__construct($config);
		$this->_modes = array_merge($this->_modes, array('publish'));
	}
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
	function _buildQuery_get_verses($arr_Verse_Of_Day, $str_primary_bible)
	{
		$x=0;
		try
		{
			foreach($arr_Verse_Of_Day as $arr_verse)
			{
				$int_book 			= 	$arr_verse->book_name;
				$int_chpater 		= 	$arr_verse->chapter_number;
				$int_begin_verse 	=	$arr_verse->begin_verse;
				$int_end_verse		=	$arr_verse->end_verse;
				$db = JFactory::getDBO();
				$query =  'SELECT a.book_id, a.chapter_id, a.verse_id, a.verse FROM `#__zefaniabible_bible_text` AS a'.
						' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id'.
						' WHERE a.book_id='.$int_book.' AND a.chapter_id='.$int_chpater.
						' AND b.alias="'.$str_primary_bible.'"';	
				if($int_end_verse == 0)
				{				
					$query = $query .' AND a.verse_id='.$int_begin_verse;
				}
				else
				{
					$query = $query .' AND a.verse_id>='.$int_begin_verse. ' AND a.verse_id<='.$int_end_verse;
				}
				$query = $query ." ORDER BY a.book_id, a.chapter_id, a.verse_id";
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
			$db = JFactory::getDBO();
			$mainframe = JFactory::getApplication();			
			$lim = $mainframe->getUserStateFromRequest('$option.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$lim0	= JRequest::getVar('limitstart', 0, '', 'int');			
			$query =  'SELECT a.* FROM `#__zefaniabible_zefaniaverseofday` AS a INNER JOIN `#__zefaniabible_zefaniabiblebooknames` AS b ON a.book_name = b.id WHERE b.publish = 1 AND a.publish = 1 ORDER BY a.book_name, a.chapter_number, a.begin_verse';
			$db->setQuery($query);
			$data = new JPagination( $db->loadResult(), $lim0, $lim );
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_verseofday($pagination )
	{
		try 
		{
			$db = JFactory::getDBO();	
			$query =  'SELECT a.* FROM `#__zefaniabible_zefaniaverseofday` AS a INNER JOIN `#__zefaniabible_zefaniabiblebooknames` AS b ON a.book_name = b.id WHERE b.publish = 1 AND a.publish = 1 ORDER BY a.book_name, a.chapter_number, a.begin_verse';
			$db->setQuery($query, $pagination->limitstart, $pagination->limit);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}	
		return $data;
	}
	function _buildQuery_standard()
	{
		try 
		{
			$db = $this->getDbo();
			$query =  'SELECT a.* FROM `#__zefaniabible_bible_names` AS a WHERE publish = 1 ORDER BY a.bible_name';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}

	/**
	 * Method to Convert the parameter fields into objects.
	 *
	 * @access public
	 * @return void
	 */
	protected function populateParams()
	{
		parent::populateParams();
		$acl = ZefaniabibleHelper::getAcl();
		if (!isset($this->_data))
			return;
		// Convert the parameter fields into objects.
		foreach ($this->_data as &$item)
		{
			if ($acl->get('core.edit.state')
				|| (bool)$item->publish)
				$item->params->set('access-view', true);
			if ($acl->get('core.edit'))
				$item->params->set('access-edit', true);
			if ($acl->get('core.delete'))
				$item->params->set('access-delete', true);
		}
	}
}
