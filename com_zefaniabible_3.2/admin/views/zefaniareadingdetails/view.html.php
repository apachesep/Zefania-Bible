<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniareadingdetails
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.zefaniabible.com - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');



/**
* HTML View class for the Zefaniabible component
*
* @package	Zefaniabible
* @subpackage	Zefaniareadingdetails
*/
class ZefaniabibleCkViewZefaniareadingdetails extends ZefaniabibleClassView
{
	/**
	* Execute and display a template script.
	*
	* @access	public
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	public function display($tpl = null)
	{
		$layout = $this->getLayout();
		if (!in_array($layout, array('default', 'modal')))
			JError::raiseError(0, $layout . ' : ' . JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'));

		$fct = "display" . ucfirst($layout);

		$this->addForkTemplatePath();
		$this->$fct($tpl);			
		$this->_parentDisplay($tpl);
	}

	/**
	* Execute and display a template : Reading Plan Details
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displayDefault($tpl = null)
	{
		$document	= JFactory::getDocument();
		$this->title = JText::_("ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS");
		$document->title = $document->titlePrefix . $this->title . $document->titleSuffix;

		$this->model		= $model	= $this->getModel();
		$this->state		= $state	= $this->get('State');
		$state->set('context', 'zefaniareadingdetails.default');
		$this->items		= $items	= $this->get('Items');
		$this->canDo		= $canDo	= ZefaniabibleHelper::getActions();
		$this->pagination	= $this->get('Pagination');
		$this->filters = $filters = $model->getForm('default.filters');
		$this->menu = ZefaniabibleHelper::addSubmenu('zefaniareadingdetails', 'default');
		$lists = array();
		$this->lists = &$lists;

		

		//Filters
		// Sort by
		$filters['sortTable']->jdomOptions = array(
			'list' => $this->getSortFields('default')
		);

		// Limit
		$filters['limit']->jdomOptions = array(
			'pagination' => $this->pagination
		);

		//Toolbar initialization

		JToolBarHelper::title(JText::_('ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS'), 'zefaniabible_zefaniareadingdetails');
		// New
		if ($model->canCreate())
			CkJToolBarHelper::addNew('zefaniareadingdetailsitem.add', "ZEFANIABIBLE_JTOOLBAR_NEW");

		// Edit
		if ($model->canEdit())
			CkJToolBarHelper::editList('zefaniareadingdetailsitem.edit', "ZEFANIABIBLE_JTOOLBAR_EDIT");

		// Delete
		if ($model->canDelete())
			CkJToolBarHelper::deleteList(JText::_('ZEFANIABIBLE_JTOOLBAR_ARE_YOU_SURE_TO_DELETE'), 'zefaniareadingdetailsitem.delete', "ZEFANIABIBLE_JTOOLBAR_DELETE");

		// Config
		if ($model->canAdmin())
			CkJToolBarHelper::preferences('com_zefaniabible');
	}

	/**
	* Execute and display a template : Reading Plan Details
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displayModal($tpl = null)
	{
		$document	= JFactory::getDocument();
		$this->title = JText::_("ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS");
		$document->title = $document->titlePrefix . $this->title . $document->titleSuffix;

		$this->model		= $model	= $this->getModel();
		$this->state		= $state	= $this->get('State');
		$state->set('context', 'zefaniareadingdetails.modal');
		$this->items		= $items	= $this->get('Items');
		$this->canDo		= $canDo	= ZefaniabibleHelper::getActions();
		$this->pagination	= $this->get('Pagination');
		$this->filters = $filters = $model->getForm('modal.filters');
		$this->menu = ZefaniabibleHelper::addSubmenu('zefaniareadingdetails', 'modal');
		$lists = array();
		$this->lists = &$lists;

		

		//Filters
		// Limit
		$filters['limit']->jdomOptions = array(
			'pagination' => $this->pagination
		);

		//Toolbar initialization

		JToolBarHelper::title(JText::_('ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS'), 'zefaniabible_zefaniareadingdetails');
		// New
		if ($model->canCreate())
			CkJToolBarHelper::addNew('zefaniareadingdetailsitem.add', "ZEFANIABIBLE_JTOOLBAR_NEW");

		// Edit
		if ($model->canEdit())
			CkJToolBarHelper::editList('zefaniareadingdetailsitem.edit', "ZEFANIABIBLE_JTOOLBAR_EDIT");

		// Delete
		if ($model->canDelete())
			CkJToolBarHelper::deleteList(JText::_('ZEFANIABIBLE_JTOOLBAR_ARE_YOU_SURE_TO_DELETE'), 'zefaniareadingdetailsitem.delete', "ZEFANIABIBLE_JTOOLBAR_DELETE");

		// Config
		if ($model->canAdmin())
			CkJToolBarHelper::preferences('com_zefaniabible');
	}

	/**
	* Returns an array of fields the table can be sorted by.
	*
	* @access	protected
	* @param	string	$layout	The name of the called layout. Not used yet
	*
	* @return	array	Array containing the field name to sort by as the key and display text as value.
	*
	* @since	3.0
	*/
	protected function getSortFields($layout = null)
	{
		return array(
			'_plan_.name' => JText::_('ZEFANIABIBLE_FIELD_BIBLE_PLAN'),
			'a.begin_chapter' => JText::_('ZEFANIABIBLE_FIELD_BEGIN_CHAPTER'),
			'a.begin_verse' => JText::_('ZEFANIABIBLE_FIELD_BEGIN_VERSE'),
			'a.end_chapter' => JText::_('ZEFANIABIBLE_FIELD_END_CHAPTER'),
			'a.end_verse' => JText::_('ZEFANIABIBLE_FIELD_END_VERSE'),
			'a.day_number' => JText::_('ZEFANIABIBLE_FIELD_DAY_NUMBER'),
			'a.ordering' => JText::_('ZEFANIABIBLE_FIELD_ORDERING')
		);
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleViewZefaniareadingdetails')){ class ZefaniabibleViewZefaniareadingdetails extends ZefaniabibleCkViewZefaniareadingdetails{} }

