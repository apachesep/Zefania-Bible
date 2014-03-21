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


jimport('joomla.application.component.model');
require_once(JPATH_ADMIN_ZEFANIABIBLE .DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'jmodel.item.php');

/**
 * Zefaniabible Component Zefaniabibleitem Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelZefaniadictionaryitem extends ZefaniabibleModelItem
{
	var $_name_plur = 'zefaniadictionary';
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
	 * Method to initialise the zefaniabibleitem data
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
			$data->name = null;
			$data->alias = null;
			$data->xml_file_url = null;
			$data->publish = null;
			$data->ordering = null;			

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
	protected function fnc_Find_Last_Row_Names()
	{
		try 
		{
			$db = JFactory::getDBO();			
			$query_max = "SELECT Max(id) FROM `#__zefaniabible_dictionary_info`";	
			$db->setQuery($query_max);	
			$int_max_ids = $db->loadResult();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return 	$int_max_ids;
	}
	protected function fnc_Loop_Thorugh_File($str_bible_xml_file_url, $int_max_ids)
	{
		$x = 1;
		$params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$str_xml_bibles_path = substr_replace(JURI::root(),"",-1).$str_bible_xml_file_url;	
		
		// check if file exists
		if(!get_headers($str_xml_bibles_path))
		{
			JError::raiseWarning('',str_replace('%s',$str_xml_bibles_path,JText::_('ZEFANIABIBLE_UPLOAD_ERROR')));
		}
		
		$arr_xml_bible = simplexml_load_file($str_xml_bibles_path);	
	
		try
		{
			$t = 0;
			foreach($arr_xml_bible->dictionary as $arr_dictionary)
			{
				foreach($arr_dictionary->item as $arr_dictionary_item)
				{
					foreach($arr_dictionary_item->description as $obj_description)
					{

						$this->fnc_Update_Bible_Verses(
							$int_max_ids,
							$arr_dictionary_item['id'],
							strip_tags($obj_description->asXML(),'<b><em><br><i><span><div><hr><h1><h2><h3><h4><h5><h6><li><ol><ul><table><tr><td><u><th><gr>')
							);
							$x++;
					}
				}
				$t++;
			}

			if($x ==1)
			{
				$this->fnc_Update_Bible_Verses($int_max_ids,'','Failed to load Bible');
			}
		}
		catch (JException $e)
		{
			print_r($this->setError($e));
		}
		return $x;	
	}
	protected function fnc_Update_Bible_Verses($int_dict_id = 1,$str_item = '',$str_desc = '')
	{
		$app = JFactory::getApplication();		
		try
		{
			$db = JFactory::getDBO();
			$arr_row->dict_id		= (int)$int_dict_id;
			$arr_row->item 		= (string)$str_item;
			$arr_row->description 	= (string)$str_desc;

			$db->insertObject("#__zefaniabible_dictionary_detail", $arr_row, 'id');

		}
		catch (JException $e)
		{
			print_r($this->setError($e));
		}			
	}	
	/**
	 * Method to build a the query string for the Zefaniabibleitem
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'dictionaryadd': return $this->_buildQuery_dictionaryadd(); break;

		}
			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__zefaniabible_dictionary_info` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';
		return $query;
	}

	function _buildQuery_dictionaryadd()
	{

			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					//.	' FROM `#__zefaniabible` AS a'
					.	' FROM `#__zefaniabible_dictionary_info` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';				
		return $query;
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
	 * Method to update zefaniabibleitem in mass
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
	 * Method to save the zefaniabibleitem
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function save($data)
	{

		$row = $this->getTable();
		
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
	        
			$int_max_ids = $this->fnc_Find_Last_Row_Names();
			$int_rows_inserted = $this->fnc_Loop_Thorugh_File($row->xml_file_url, $int_max_ids);
			$app = JFactory::getApplication();
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
	 * Method to delete a zefaniabibleitem
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
			
			$query = 'DELETE a.*, b.* FROM `#__zefaniabible_dictionary_detail` AS a'
				. ' INNER JOIN `#__zefaniabible_dictionary_info` AS b ON a.dict_id = b.id'
				. ' WHERE b.id = "'.$cids.'"';					
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}
		}
		JError::raiseWarning(1000, $query);

		return true;
	}
	/**
	 * Method to (un)publish a zefaniabibleitem
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

			//$query = 'UPDATE #__zefaniabible'
			$query = 'UPDATE `#__zefaniabible_dictionary_info`'
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
	 * Method to move a zefaniabibleitem
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
	 * Method to save the order of the zefaniabible
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