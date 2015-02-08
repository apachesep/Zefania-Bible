<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Zefaniauser list controller class.
 *
 * @package     Zefaniabible
 * @subpackage  Controllers
 */
class ZefaniabibleControllerZefaniauser extends JControllerAdmin
{
	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'Zefaniauser';
	
	public function __construct($config = array())
	{
		parent::__construct($config);
        $this->registerTask('reading_unpublish', 'reading_publish');
		$this->registerTask('verse_unpublish', 'verse_publish');
	}	
	/**
	 * Get the admin model and set it to default
	 *
	 * @param   string           $name    Name of the model.
	 * @param   string           $prefix  Prefix of the model.
	 * @param   array			 $config  The model configuration.
	 */
	public function getModel($name = 'Zefaniauseritem', $prefix='ZefaniabibleModel', $config = array())
	{
		$config['ignore_request'] = true;
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
 	function reading_publish()
	{
		// 
		$ids        = JRequest::getVar('cid', array(), '', 'array');
        $values = array('reading_publish' => 1, 'reading_unpublish' => 0);
        $task     = $this->getTask();
		$value   = JArrayHelper::getValue($values, $task, 0, 'int');
		$model = $this->getModel();
	
        if (!$model->mdl_reading_publish($ids, $value)) 
		{
        	JError::raiseWarning(500, $model->getError());
		}
		$this->setRedirect(JRoute::_('index.php?option=com_zefaniabible&view=zefaniauser', false));
	}
 	function verse_publish()
	{		
		$ids        = JRequest::getVar('cid', array(), '', 'array');
        $values = array('verse_publish' => 1, 'verse_unpublish' => 0);
        $task     = $this->getTask();
		$value   = JArrayHelper::getValue($values, $task, 0, 'int');
		$model = $this->getModel();

        if (!$model->mdl_verse_publish($ids, $value)) 
		{
        	JError::raiseWarning(500, $model->getError());
		}
		$this->setRedirect(JRoute::_('index.php?option=com_zefaniabible&view=zefaniauser', false));
	}	
}
?>