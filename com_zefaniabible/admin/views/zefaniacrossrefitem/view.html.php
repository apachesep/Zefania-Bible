<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/zefaniabible.php';

/**
 * ZefaniacrossrefItem item view class.
 *
 * @package     Zefaniabible
 * @subpackage  Views
 */
class ZefaniabibleViewZefaniacrossrefItem extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;

	public function display($tpl = null)
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		if ($this->getLayout() == 'modal')
		{
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= ZefaniabibleHelper::getActions();
		
		JToolBarHelper::title(JText::_('COM_ZEFANIABIBLE_LAYOUT_CROSS_REFERENCE_DETAILS'));

		if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('zefaniacrossrefitem.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('zefaniacrossrefitem.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('zefaniacrossrefitem.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('zefaniacrossrefitem.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('zefaniacrossrefitem.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('zefaniacrossrefitem.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
?>