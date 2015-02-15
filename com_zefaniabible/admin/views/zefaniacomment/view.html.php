<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/zefaniabible.php';

/**
 * Zefaniacomment list view class.
 *
 * @package     Zefaniabible
 * @subpackage  Views
 */
class ZefaniabibleViewZefaniacomment extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->authors = $this->get('Authors');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		ZefaniabibleHelper::addSubmenu('zefaniacomment');
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}
	
	/**
	 *	Method to add a toolbar
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= ZefaniabibleHelper::getActions();
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('ZEFANIABIBLE_LAYOUT_COMMENTARIES'));
		
		if ($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('zefaniacommentitems.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit') || $canDo->get('core.edit.own')) && isset($this->items[0]))
		{
			JToolBarHelper::editList('zefaniacommentitems.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.edit.state'))
		{
            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
				JToolbarHelper::publish('zefaniacomment.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('zefaniacomment.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            } 
			else if (isset($this->items[0]))
			{
                // Show a direct delete button
                JToolBarHelper::deleteList('', 'zefaniacomment.delete','JTOOLBAR_DELETE');
            }
            
			if (isset($this->items[0]->checked_out))
			{
				JToolbarHelper::checkin('zefaniacomment.checkin');
            }
			if ($canDo->get('core.delete') && isset($this->items[0]))
			{
				JToolBarHelper::deleteList('', 'zefaniacomment.delete','JTOOLBAR_DELETE');
			}
		}
				
		
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_zefaniabible');
		}
	}
}
?>