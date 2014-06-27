<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniauser
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
* @subpackage	Zefaniauseritem
*/
class ZefaniabibleCkViewZefaniauseritem extends ZefaniabibleClassView
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
		if (!in_array($layout, array('adduser')))
			JError::raiseError(0, $layout . ' : ' . JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'));

		$fct = "display" . ucfirst($layout);

		$this->addForkTemplatePath();
		$this->$fct($tpl);			
		$this->_parentDisplay($tpl);
	}

	/**
	* Execute and display a template : Add User
	*
	* @access	protected
	* @param	string	$tpl	The name of the template file to parse; automatically searches through the template paths.
	*
	* @return	mixed	A string if successful, otherwise a JError object.
	*
	* @since	11.1
	*/
	protected function displayAdduser($tpl = null)
	{
		$document	= JFactory::getDocument();
		$this->title = JText::_("ZEFANIABIBLE_LAYOUT_ADD_USER");
		$document->title = $document->titlePrefix . $this->title . $document->titleSuffix;

		// Initialiase variables.
		$this->model	= $model	= $this->getModel();
		$this->state	= $state	= $this->get('State');
		$state->set('context', 'zefaniauseritem.adduser');
		$this->item		= $item		= $this->get('Item');
		$this->form		= $form		= $this->get('Form');
		$this->canDo	= $canDo	= ZefaniabibleHelper::getActions($model->getId());
		$lists = array();
		$this->lists = &$lists;

		$user		= JFactory::getUser();
		$isNew		= ($model->getId() == 0);

		//Check ACL before opening the form (prevent from direct access)
		if (!$model->canEdit($item, true))
			$model->setError(JText::_('JERROR_ALERTNOAUTHOR'));

		// Check for errors.
		if (count($errors = $model->getErrors()))
		{
			JError::raiseError(500, implode(BR, array_unique($errors)));
			return false;
		}
		$jinput = JFactory::getApplication()->input;

		//Hide the component menu in item layout
		$jinput->set('hidemainmenu', true);

		//Toolbar initialization

		JToolBarHelper::title(JText::_('ZEFANIABIBLE_LAYOUT_ADD_USER'), 'zefaniabible_zefaniauser');
		// Delete
		if (!$isNew && $item->params->get('access-delete'))
			JToolbar::getInstance('toolbar')->appendButton('Confirm', JText::_('ZEFANIABIBLE_JTOOLBAR_ARE_YOU_SURE_TO_DELETE'), 'delete', "ZEFANIABIBLE_JTOOLBAR_DELETE", 'zefaniauseritem.delete', false);

		// Save & Close
		if (($isNew && $model->canCreate()) || (!$isNew && $item->params->get('access-edit')))
			CkJToolBarHelper::save('zefaniauseritem.save', "ZEFANIABIBLE_JTOOLBAR_SAVE_CLOSE");
		// Save
		if (($isNew && $model->canCreate()) || (!$isNew && $item->params->get('access-edit')))
			CkJToolBarHelper::apply('zefaniauseritem.apply', "ZEFANIABIBLE_JTOOLBAR_SAVE");
		// Cancel
		CkJToolBarHelper::cancel('zefaniauseritem.cancel', "ZEFANIABIBLE_JTOOLBAR_CANCEL");
		$model_bible_version = CkJModel::getInstance('Biblenames', 'ZefaniabibleModel');
		$model_bible_version->addGroupOrder("a.bible_name");
		$lists['fk']['bible_version'] = $model_bible_version->getItems();

		$model_plan = CkJModel::getInstance('Zefaniareading', 'ZefaniabibleModel');
		$model_plan->addGroupOrder("a.name");
		$lists['fk']['plan'] = $model_plan->getItems();

		$model_user_id = CkJModel::getInstance('ThirdUsers', 'ZefaniabibleModel');
		$model_user_id->addGroupOrder("a.name");
		$lists['fk']['user_id'] = $model_user_id->getItems();
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleViewZefaniauseritem')){ class ZefaniabibleViewZefaniauseritem extends ZefaniabibleCkViewZefaniauseritem{} }

