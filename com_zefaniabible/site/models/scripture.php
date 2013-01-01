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

require_once(JPATH_ADMIN_ZEFANIABIBLE .DS.'classes'.DS.'jmodel.list.php');

/**
 * Zefaniabible Component Zefaniabible Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelScripture extends ZefaniabibleModelList
{
	var $_name_sing = 'scriptureitem';
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
	function _buildQuery_scripture($str_alias, $str_Bible_book_id, $str_begin_chap, $str_begin_verse, $str_end_chap, $str_end_verse)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query	= "SELECT a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name FROM `#__zefaniabible_bible_text` AS a".
				' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id'.	
				" WHERE a.book_id=".(int)$str_Bible_book_id;
				$query	= $query . " AND b.alias='".trim($str_alias)."'";
				// Genesis 1
				if(($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse))
				{
					$query	= $query . " AND a.chapter_id=".(int)$str_begin_chap;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1-2
				else if(($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse))
				{
					$query	= $query . " AND a.chapter_id>=".(int)$str_begin_chap." AND a.chapter_id<=".(int)$str_end_chap;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1:1
				else if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse))
				{
					$query	= $query . " AND a.chapter_id=".(int)$str_begin_chap." AND a.verse_id=".(int)$str_begin_verse;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1:1-2
				else if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse))
				{
					$query	= $query . " AND a.chapter_id=".(int)$str_begin_chap." AND a.verse_id>=".(int)$str_begin_verse. " AND a.verse_id<=".$str_end_verse;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1:2-2:3
				else if(($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse))
				{
					$str_tmp_old_query = $query;
					$query	= "SELECT * FROM( ".$query . " AND a.chapter_id=".(int)$str_begin_chap." AND a.verse_id>=".(int)$str_begin_verse. " ORDER BY a.verse_id ASC ) as c";
					$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id=".$str_end_chap." AND a.verse_id<=".$str_end_verse." ORDER BY a.verse_id ASC) as d";
					if(($str_end_chap - $str_begin_chap)>1)
					{
						$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id>=".($str_begin_chap+1)." AND a.chapter_id<=".($str_end_chap-1)." ORDER BY a.verse_id ASC) as e";
   					}
					$query  = $query. " ORDER BY chapter_id, verse_id";
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
