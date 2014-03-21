<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniaverseofday
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
 * Zefaniabible Component Zefaniaverseofdayitem Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelZefaniaverseofdayitem extends ZefaniabibleModelItem
{
	var $_name_plur = 'zefaniaverseofday';
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
	 * Method to initialise the zefaniaverseofdayitem data
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
			$data->book_name = JRequest::getInt('filter_book_name', $this->getState('filter.book_name'));
			$data->chapter_number = null;
			$data->begin_verse = null;
			$data->end_verse = null;
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

		if ($search_search = $app->getUserState($this->context.'.search.search'))
			$this->setState('search.search', $search_search, null, 'varchar');



		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Zefaniaverseofdayitem
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'addverseofday': return $this->_buildQuery_addverseofday(); break;

		}



			$query = 'SELECT a.*'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__zefaniabible_zefaniaverseofday` AS a'
					. 	$this->_buildQueryJoin()

					. 	$this->_buildQueryWhere()

					.	'';

		return $query;
	}

	function _buildQuery_addverseofday()
	{

			$query = 'SELECT a.*'
					.	' , _book_name_.bible_book_name AS `_book_name_bible_book_name`'
					.	' , _book_name_.bible_book_name AS `_book_name_bible_book_name`'
					. 	$this->_buildQuerySelect()

					.	' FROM `#__zefaniabible_zefaniaverseofday` AS a'
					.	' LEFT JOIN `#__zefaniabible_zefaniabiblebooknames` AS _book_name_ ON _book_name_.id = a.book_name'
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
	 * Method to update zefaniaverseofdayitem in mass
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
	 * Method to save the zefaniaverseofdayitem
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



		return true;
	}
	/**
	 * Method to delete a zefaniaverseofdayitem
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

			$query = 'DELETE FROM `#__zefaniabible_zefaniaverseofday`'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				JError::raiseWarning(1000, $this->_db->getErrorMsg());
				return false;
			}



		}

		return true;
	}
	/**
	 * Method to (un)publish a zefaniaverseofdayitem
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

			$query = 'UPDATE #__zefaniabible_zefaniaverseofday'
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
	 * Method to move a zefaniaverseofdayitem
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
	 * Method to save the order of the zefaniaverseofday
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