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
class ZefaniabibleViewZefaniadictionaryitem extends JViewLegacy
{
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'dictionaryadd':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_dictionaryadd($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		//$access = ZefaniabibleHelper::getACL();
		$mdl_access =  new ZefaniabibleHelper;
		$access = $mdl_access->getACL();

		$model	= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'dictionaryadd');

		$document	= JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("ZEFANIABIBLE_LAYOUT_ADD_DICTIONARY") . $document->titleSuffix;


		//Form validator
		JHTML::_('behavior.formvalidation');


		$lists = array();

		//get the zefaniabibleitem
		$zefaniadictionaryitem	= $model->getItem();
		$isNew		= ($zefaniadictionaryitem->id < 1);

		//For security, execute here a redirection if not authorized to enter a form
		if (($isNew && !$access->get('core.create'))
		|| (!$isNew && !$zefaniadictionaryitem->params->get('access-edit')))
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
	
		if (!$isNew && ($access->get('core.delete') || $zefaniabibleitem->params->get('access-delete')))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", false);
		if ($access->get('core.edit') || ($isNew && $access->get('core.create') || $access->get('core.edit.own')))
			$bar->appendButton( 'Standard', "save", "JTOOLBAR_SAVE", "save", false);
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "apply", "JTOOLBAR_APPLY", "apply", false);
		$bar->appendButton( 'Standard', "cancel", "JTOOLBAR_CANCEL", "cancel", false, false );

		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Link', 'export', JText::_('ZEFANIABIBLE_FIELD_GET_DICTIONARIES').' 1', 'http://www.biblesupport.com/e-sword-downloads/category/7-dictionaries/');
		
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$str_file_path = '/'.$config->get('xmlDictionaryPath', 'media/com_zefaniabible/dictionary/');
		
		jimport( 'joomla.filesystem.folder' );
		$arr_file_list = JFolder::files('..'.$str_file_path,'.xml');


		JRequest::setVar( 'hidemainmenu', true );

		if(ini_get('max_execution_time') < 30)
		{
			JError::raiseWarning(1, JText::_('ZEFANIABIBLE_INSTALL_MAX_EXECUTION_TIME'));
		}
		if(!ini_get('allow_url_fopen'))
		{
			JError::raiseWarning(1, JText::_('ZEFANIABIBLE_INSTALL_FOPEN'));
		}
		if((substr(ini_get('upload_max_filesize'),0,-1))<10)
		{
			JError::raiseWarning(1, JText::_('ZEFANIABIBLE_INSTALL_MAX_FILE_SIZE'));		
		}
		if((substr(ini_get('post_max_size'),0,-1))<10)
		{
			JError::raiseWarning(1, JText::_('ZEFANIABIBLE_INSTALL_MAX_POST_SIZE'));		
		}

		$session =  JFactory::getSession();
 		jimport('joomla.environment.uri' );
		$document = JFactory::getDocument();
		
		$toggle = 'function toggleElement(current, disable) {
				document.getElementById(current + "_icon").className = "btn add-on icon-checkmark";
				document.getElementById(disable + "_icon").className = "btn add-on icon-cancel";
				document.getElementById(disable + "_text").disabled = true;
				document.getElementById(current + "_text").disabled = false;
		}';
				
		if($isNew)
		{
			$targetURL 	= JURI::root().'administrator/index.php?option=com_zefaniabible&task=zefaniaupload.upload&'.$session->getName().'='.$session->getId().'&'.JSession::getFormToken().'=1&format=json';
			$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/swfupload.js');
			$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/swfupload.queue.js');
			$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/fileprogress.js');
			$document->addScript(JURI::root().'media/com_zefaniabible/swfupload/handlers.js');
			
			$uploader_script = '
				window.onload = function() 
				{
						upload1 = new SWFUpload
						(
							{
								upload_url: "'.$targetURL.'&type=dictionary",
								flash_url : "'.JURI::root().'media/com_zefaniabible/swfupload/swfupload.swf",
								file_size_limit : "20MB",
								file_types : "*.xml",
								file_types_description : "'.JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_FILE_DESC_DICTIONARY', 'true').'",
								file_upload_limit : "1",
								file_queue_limit : "1",
								button_image_url : "'.JURI::root().'media/com_zefaniabible/swfupload/XPButtonUploadText_61x22.png",
								button_placeholder_id : "btnUpload1",
								button_width: 61,
								button_height: 22,
								button_window_mode: "transparent",
								debug: false,
								swfupload_loaded_handler: function() 
								{
									document.id("btnCancel1").removeClass("ss-hide");
									document.id("biblepathinfo").removeClass("ss-hide");
									if(document.id("upload-noflash")){
										document.id("upload-noflash").destroy();
										document.id("loading").destroy();
									}
								},
								file_dialog_start_handler : fileDialogStart,
								file_queued_handler : fileQueued,
								file_queue_error_handler : fileQueueError,
								file_dialog_complete_handler : fileDialogComplete,
								upload_start_handler : uploadStart,
								upload_progress_handler : uploadProgress,
								upload_error_handler : uploadError,
								upload_success_handler : function uploadSuccess(file, serverData) 
								{
									try 
									{
										var progress = new FileProgress(file, this.customSettings.progressTarget);
										var data = JSON.decode(serverData);
										if (data.status == "1") 
										{
											progress.setComplete();
											progress.setStatus(data.error);
											document.id("xml_file_url_text").value = data.path;
										} else 
										{
											progress.setError();
											progress.setStatus(data.error);
										}
										progress.toggleCancel(false);
									} catch (ex) 
									{
										this.debug(ex);
									}
								},
								upload_complete_handler : uploadComplete,
								custom_settings : 
								{
									progressTarget : "infoUpload1",
									cancelButtonId : "btnCancel1"
								}
							}
						);
			}';				
			 
			//add the javascript to the head of the html document
			$document->addScriptDeclaration($uploader_script);
		}
		$document->addScriptDeclaration($toggle);		
		$str_lang = 'var str_special_char = "'.JText::_('COM_ZEFANIABIBLE_VALIDATION_SPECIAL_CHARACTERS').'";';
		$str_lang = $str_lang.' var str_spaces_char = "'.JText::_('COM_ZEFANIABIBLE_VALIDATION_SPECIAL_SPACES').'";';
		$str_lang = $str_lang.' var str_blank_char = "'.JText::_('COM_ZEFANIABIBLE_VALIDATION_SPECIAL_BLANK').'";';
		$document->addScriptDeclaration($str_lang);		

		$user = JFactory::getUser();
		$this->assignRef('user',		$user);
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('zefaniadictionaryitem',		$zefaniadictionaryitem);
		$this->assignRef('config',		$config);
		$this->assignRef('isNew',		$isNew);
		$this->assignRef('arr_file_list', $arr_file_list);
		
		parent::display($tpl);
	}
}
