<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/zefaniabible.php';

/**
 * Zefaniabibleitem item view class.
 *
 * @package     Zefaniabible
 * @subpackage  Views
 */
class ZefaniabibleViewZefaniabibleitem extends JViewLegacy
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
			$this->form->setFieldAttribute('language', 'readonly', 'true');
		}

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= ZefaniabibleHelper::getActions();
		$bar 		= JToolBar::getInstance('toolbar');
		
		//get the zefaniabibleitem
		$model	= $this->getModel();
		$zefaniabibleitem	= $model->getItem();
		$isNew		= ($zefaniabibleitem->id < 1);
		
		JToolBarHelper::title(JText::_('ZEFANIABIBLE_LAYOUT_ADD_BIBLE'));

		if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		
		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('zefaniabibleitem.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('zefaniabibleitem.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && ($canDo->get('core.create'))){
			JToolBarHelper::custom('zefaniabibleitem.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			JToolBarHelper::custom('zefaniabibleitem.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('zefaniabibleitem.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('zefaniabibleitem.cancel', 'JTOOLBAR_CLOSE');
		}
		JToolBarHelper::divider();
		
		$bar->appendButton( 'Link', 'link', JText::_('ZEFANIABIBLE_FIELD_GET_BIBLES').' 1', 'http://www.churchsw.org/p/bibles.html');
		$bar->appendButton( 'Link', 'link', JText::_('ZEFANIABIBLE_FIELD_GET_BIBLES').' 2', 'http://sourceforge.net/projects/zefania-sharp/files/Zefania%20XML%20Modules%20%28new%29/');
		$bar->appendButton( 'Link', 'link', JText::_('ZEFANIABIBLE_FIELD_GET_BIBLES').' 3', 'http://www.biblesupport.com/e-sword-downloads/category/2-bibles/');
		
		
		$session =  JFactory::getSession();
 		jimport('joomla.environment.uri' );
		$document = JFactory::getDocument();
		$toggle = 'function toggleElement(current, disable) {
				document.getElementById(disable).disabled = true;
				document.getElementById(current).disabled = false;			
				document.getElementById(current + "_icon").className = "btn add-on icon-checkmark";
				document.getElementById(disable + "_icon").className = "btn add-on icon-cancel";

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
								upload_url: "'.$targetURL.'&type=bible",
								flash_url : "'.JURI::root().'media/com_zefaniabible/swfupload/swfupload.swf",
								file_size_limit : "20MB",
								file_types : "*.xml",
								file_types_description : "'.JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_FILE_DESC', 'true').'",
								file_upload_limit : "5",
								file_queue_limit : "5",
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
											document.id("jform_bible_xml_file").value = data.path;
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
						upload2 = new SWFUpload({
							upload_url: "'.$targetURL.'&type=audio",
							flash_url : "'.JURI::root().'media/com_zefaniabible/swfupload/swfupload.swf",
							file_size_limit : "10MB",
							file_types : "*.xml",
							file_types_description : "'.JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_FILE_DESC_AUDIO', 'true').'",
							file_upload_limit : "0",
							file_queue_limit : "0",
							button_image_url : "'.JURI::root().'media/com_zefaniabible/swfupload/XPButtonUploadText_61x22.png",
							button_placeholder_id : "btnUpload2",
							button_width: 61,
							button_height: 22,
							button_window_mode: "transparent",
							debug: false,
							swfupload_loaded_handler: function() {
								document.id("btnCancel2").removeClass("ss-hide");
								document.id("audiopathinfo").removeClass("ss-hide");
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
							upload_success_handler : function uploadSuccess(file, serverData) {
								try {
									var progress = new FileProgress(file, this.customSettings.progressTarget);
									var data = JSON.decode(serverData);
									if (data.status == "1") {
										progress.setComplete();
										progress.setStatus(data.error);
										document.id("jform_xml_audio_url").value = data.path;
									} else {
										progress.setError();
										progress.setStatus(data.error);
									}
									progress.toggleCancel(false);
								} catch (ex) {
									this.debug(ex);
								}
							},
							upload_complete_handler : uploadComplete,
							custom_settings : {
								progressTarget : "infoUpload2",
								cancelButtonId : "btnCancel2"
							}
						});
			}';				
			 
			//add the javascript to the head of the html document
			$document->addScriptDeclaration($toggle);
			$document->addScriptDeclaration($uploader_script);

		}		
	}
}
?>