<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

class ZefaniabibleController extends JControllerLegacy
{
	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $default_view = 'cpanel';
	
	/**
	 * Checks whether a user can see this view.
	 *
	 * @param   string	$view	The view name.
	 *
	 * @return  boolean
	 * @since   1.6
	 */
	protected function canView($view)
	{
		$canDo	= ZefaniabibleHelper::getActions();
		return $canDo->get('core.admin');
	}

	/**
     * Method to display a view.
     *
     * @param   boolean If true, the view output will be cached
     * @param   array   An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController	This object to support chaining.
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false)
	{
        // Get the document object.
		$document	= JFactory::getDocument();
		$document->addStyleSheet('/administrator/components/com_zefaniabible/css/zefaniabible.css');
		// Set the default view name and format from the Request.
		$vName   = $this->input->getCmd('view', 'cpanel');
		$vFormat = $document->getType();
		$lName   = $this->input->getCmd('layout', 'default');
		
		// Check whether user is allowed to admin component 
		if (!$this->canView($vName))
		{
			JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}
		
		// Get the model and the view
		$model = $this->getModel($vName);
		$view = $this->getView($vName, $vFormat);
		
		// Push the model into the view (as default).
		$view->setModel($model, true);
		$view->setLayout($lName);
		
		// Push document object into the view.
		$view->document = $document;

		// Display the view
		$view->display();
    }
}
?>