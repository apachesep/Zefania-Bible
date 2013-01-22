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

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewZefaniabibleitem extends JViewLegacy
{
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'bibleadd':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_bibleadd($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = ZefaniabibleHelper::getACL();

		$model	= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'bibleadd');

		$document	= JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("ZEFANIABIBLE_LAYOUT_ADD_BIBLE") . $document->titleSuffix;


		//Form validator
		JHTML::_('behavior.formvalidation');


		$lists = array();

		//get the zefaniabibleitem
		$zefaniabibleitem	= $model->getItem();
		$isNew		= ($zefaniabibleitem->id < 1);

		//For security, execute here a redirection if not authorized to enter a form
		if (($isNew && !$access->get('core.create'))
		|| (!$isNew && !$zefaniabibleitem->params->get('access-edit')))
		{
				JError::raiseWarning(403, JText::sprintf( "JERROR_ALERTNOAUTHOR") );
				ZefaniabibleHelper::redirectBack();
		}

		//Ordering
		$orderModel = JModelLegacy::getInstance('Zefaniabible', 'ZefaniabibleModel');
		$lists["ordering"] = $orderModel->getItems();

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = JToolBar::getInstance('toolbar');
		//if (!$isNew && ($access->get('core.delete') || $zefaniabibleitem->params->get('access-delete')))
		//	$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", false);
		if ($access->get('core.edit') || ($isNew && $access->get('core.create') || $access->get('core.edit.own')))
			$bar->appendButton( 'Standard', "save", "JTOOLBAR_SAVE", "save", false);
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "apply", "JTOOLBAR_APPLY", "apply", false);
		$bar->appendButton( 'Standard', "cancel", "JTOOLBAR_CANCEL", "cancel", false, false );

		$config	= JComponentHelper::getParams( 'com_zefaniabible' );

		JRequest::setVar( 'hidemainmenu', true );



		$session = & JFactory::getSession();
 		jimport('joomla.environment.uri' );
		$document =& JFactory::getDocument();
		$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/swfupload.js');
		$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/swfupload.queue.js');
		$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/fileprogress.js');
		$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/handlers.js');
		$swfUploadHeadJs ='
		var swfu;
		 
		window.onload = function()
		{
		 
		var settings = 
		{
				flash_url : "'.JURI::root().'media/com_zefaniabible/swfupload/swfupload.swf",
				upload_url: "index.php",
				post_params: 
				{
						"option" : "com_zefaniabible",
						"controller" : "zefaniaupload",
						"task" : "upload",
						"id" : "'.$zefaniabibleitem->id.'",
						"'.$session->getName().'" : "'.$session->getId().'",
						"format" : "raw"
				}, 
				file_size_limit : "20 MB",
				file_types : "*.xml",
				file_types_description : "'.JText::_("ZEFANIABIBLE_FIELD_XML_UPLOAD_FILE_DESC").'",
				file_upload_limit : 1,
				file_queue_limit : 1,
				custom_settings : 
				{
						progressTarget : "fsUploadProgress",
						cancelButtonId : "btnCancel"
				},
				debug: false,
		 
				// Button settings
				button_image_url: "'.JURI::root().'media/com_zefaniabible/swfupload/XPButtonUploadText_61x22.png",
				button_width: 68,
				button_height: 24,
				button_window_mode: "transparent",
				button_placeholder_id: "spanButtonPlaceHolder",		 
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete  // Queue plugin event
		};
		swfu = new SWFUpload(settings);
		};
		 
		';
		 
		//add the javascript to the head of the html document
		$document->addScriptDeclaration($swfUploadHeadJs);


		$user = JFactory::getUser();
		$this->assignRef('user',		$user);
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('zefaniabibleitem',		$zefaniabibleitem);
		$this->assignRef('config',		$config);
		$this->assignRef('isNew',		$isNew);

		parent::display($tpl);
	}




}