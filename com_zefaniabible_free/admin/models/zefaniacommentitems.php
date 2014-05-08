<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniacomment
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


jimport('joomla.application.component.model');
require_once(JPATH_ADMIN_ZEFANIABIBLE .DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'jmodel.item.php');

/**
 * Zefaniabible Component Zefaniacommentitems Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelZefaniacommentitems extends ZefaniabibleModelItem
{
	var $_name_plur = 'zefaniacomment';
	var $params;



	/**
	 * Constructor
	 *
	 */
	function __construct()
	{
		parent::__construct();
		$this->_modes = array_merge($this->_modes, array(''));

	}

	/**
	 * Method to initialise the zefaniacommentitems data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 */
	function _initData()
	{
		if (empty($this->_data))
		{
			//Default values shown in the form for new item creation
			$data = new stdClass();

			$data->id = 0;
			$data->attribs = null;
			$data->title = null;
			$data->alias = null;
			$data->full_name = null;
			$data->file_location = null;
			$data->ordering = null;
			$data->publish = null;
			$this->_data = $data;

			return (boolean) $this->_data;
		}
		return true;
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

		if ($filter_publish = $app->getUserState($this->context.'.filter.publish'))
			$this->setState('filter.publish', $filter_publish, null, 'cmd');



		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Zefaniacommentitems
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'commentaryadd': return $this->_buildQuery_commentaryadd(); break;

		}



			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__zefaniabible_zefaniacomment` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}

	function _buildQuery_commentaryadd()
	{

			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__zefaniabible_zefaniacomment` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}
	private function fnc_Loop_Thorugh_File($str_bible_xml_file_url, $int_max_ids)
	{ 		
		$params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$str_path_commentary_folder	= $params->get('xmlCommentaryPath', 'media/com_zefaniabible/commentary/');
		$str_subfolder_commentary = str_replace($str_path_commentary_folder,'',$str_bible_xml_file_url);
		$str_subfolder_commentary = substr_replace(str_replace(basename($str_subfolder_commentary),'',$str_subfolder_commentary),'',0,1);
		$str_commentary_path 		= JURI::root().$str_path_commentary_folder.$str_subfolder_commentary;
		
		// check if file exists
		if(!get_headers($str_commentary_path))
		{
			JError::raiseWarning('',str_replace('%s',$str_commentary_path,JText::_('ZEFANIABIBLE_UPLOAD_ERROR')));
		}
				
		$arr_xml_main_commentary 	= simplexml_load_file(substr_replace(JURI::root(),"",-1).$str_bible_xml_file_url);
		$int_bible_book_id = 1;
		$x = 1;
		
		foreach($arr_xml_main_commentary->BIBLEBOOK as $obj_commentary_book)
		{
			$int_bible_book_id = (int)$obj_commentary_book['bnumber'];
			$int_bible_chapter = 1;
			foreach($obj_commentary_book->CHAPTER as $obj_commenary_chapter)
			{
				$int_bible_chapter = (int)$obj_commenary_chapter['cnumber'];
				$int_bible_verse = 1;
				$str_verse = '';
				foreach($obj_commenary_chapter->VERS as $obj_commenary_verse)
				{
					$int_bible_verse = (int)$obj_commenary_verse['vnumber'];
					$str_verse = $obj_commenary_verse->asXML();
					$obj_commenary_verse->asXML();
					$this->fnc_insert_commentary_verses(
							$int_max_ids, 
							$int_bible_book_id,
							$int_bible_chapter,
							$int_bible_verse,
							$str_verse);
						$x++;									
				}
				
			}
		}		
		if($x == 1)
		{
			$this->fnc_insert_commentary_verses($int_max_ids,1,1,1,'Failed to load commentary');
		}	
		return $x;
	}
	
	private function fnc_insert_commentary_verses($int_bible_id, $int_bible_book_id, $int_bible_chapter, $int_bible_verse, $str_verse)
	{
		try
		{
			$db = JFactory::getDBO();
			$arr_row->bible_id		= (int)$int_bible_id;
			$arr_row->book_id 		= (int)$int_bible_book_id;
			$arr_row->chapter_id 	= (int)$int_bible_chapter;
			$arr_row->verse_id 		= (int)$int_bible_verse;
			$arr_row->verse 		= (string)strip_tags(html_entity_decode(html_entity_decode($str_verse)),'<b><em><br><i><span><div><hr><h1><h2><h3><h4><h5><h6><li><ol><ul><table><tr><td><u><th>');
			$db->insertObject("#__zefaniabible_comment_text", $arr_row, 'id');
		}
		catch (JException $e)
		{
			print_r($this->setError($e));
		}			
	}

	function _buildQueryWhere($where = array())
	{
		$app = JFactory::getApplication();
		//$acl = ZefaniabibleHelper::getAcl();
		$mdl_acl = new ZefaniabibleHelper;
		$acl = $mdl_acl->getAcl();

		$where[] = 'a.id = '.(int) $this->_id;



		return parent::_buildQueryWhere($where);
	}

	/**
	 * Method to update zefaniacommentitems in mass
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function update($cids, $data)
	{
		foreach($cids as $cid)
		{
			if ($cid == 0)
				continue;
			$data['id'] = $cid;
			if (!$this->save($data))
				return false;
		}
		return true;
	}

	/**
	 * Method to save the zefaniacommentitems
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function save($data)
	{
		$params	= JComponentHelper::getParams( 'com_zefaniabible' );
		$row = $this->getTable();


		$str_folder_file = JRequest::getCmd('file_location_folder');
		$arr_file_info = pathinfo($str_folder_file);
		
		if(($row->file_location == "")and($arr_file_info['extension'] == 'xml'))
		{
			$str_path = $params->get('xmlCommentaryPath', 'media/com_zefaniabible/audio/');
			$row->file_location = '/'.$str_path.$str_folder_file;				
		}
		//Convert data from a stdClass
		if (is_object($data)){
			if (get_class($data) == 'stdClass')
				$data = JArrayHelper::fromObject($data);
		}

		//Current id if unspecified
		if ($data['id'] != null)
			$id = $data['id'];
		else if (($this->_id != null) && ($this->_id > 0))
			$id = $this->_id;


		//Load the current object, in order to process an update
		if (isset($id))
			$row->load($id);

		//Some security checks
		//$acl = ZefaniabibleHelper::getAcl();
		$mdl_acl = new ZefaniabibleHelper;
		$acl = $mdl_acl->getAcl();

		//Secure the published tag if not allowed to change
		if (isset($data['publish']) && !$acl->get('core.edit.state'))
			unset($data['publish']);


		// Bind the form fields to the zefaniabible table
		$ignore = array();
		if (!$row->bind($data, $ignore)) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}





		// Make sure the zefaniabible table is valid
		if (!$row->check()) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}



		// Store the zefaniabible table to the database
		if (!$row->store())
        {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}



		$this->_id = $row->id;
		$this->_data = $row;
		if(!$id)
		{	
			$app = JFactory::getApplication();
			
			$int_rows_inserted = $this->fnc_Loop_Thorugh_File($row->file_location, $row->id);
			if($int_rows_inserted > 1)
			{
				$app->enqueueMessage($int_rows_inserted." ".JText::_( 'ZEFANIABIBLE_FIELD_VERSES_ADDED'));
			}
			else
			{
				JError::raiseWarning('',JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UNABLE_TO_UPLOAD_FILE'));
			}	
		}
		return true;
	}
	/**
	 * Method to delete a zefaniacommentitems
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'DELETE a.*, b.* FROM `#__zefaniabible_comment_text` AS a'
				. ' INNER JOIN `#__zefaniabible_zefaniacomment` AS b ON a.bible_id = b.id'
				. ' WHERE b.id = "'.$cids.'"';	
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}



		}

		return true;
	}
	/**
	 * Method to move a zefaniacommentitems
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function move($direction)
	{
		$row = $this->getTable();
		if (!$row->load($this->_id)) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}

		$condition = "1";


		if (!$row->move( $direction,  $condition)) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to save the order of the zefaniacomment
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function saveorder($cid = array(), $order)
	{
		$row = $this->getTable();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseWarning(1000, $this->_db->getErrorMsg());
					return false;
				}
			}
		}

		$row->reorder();


		return true;
	}
	/**
	 * Method to (un)publish a zefaniacommentitems
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	= JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__zefaniabible_zefaniacomment'
				. ' SET `publish` = '.(int) $publish
				. ' WHERE id IN ( '.$cids.' )'


			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
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

		if (!isset($this->_data))
			return;

		$item = $this->_data;
		//$acl = ZefaniabibleHelper::getAcl();
		$mdl_acl = new ZefaniabibleHelper;
		$acl = $mdl_acl->getAcl();

		if ($acl->get('core.edit.state')
			|| (bool)$item->publish)
			$item->params->set('access-view', true);

		if ($acl->get('core.edit'))
			$item->params->set('access-edit', true);

		if ($acl->get('core.delete'))
			$item->params->set('access-delete', true);



	}




}