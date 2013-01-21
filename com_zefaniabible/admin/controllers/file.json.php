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

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * File Zefaniabible Controller
 * Copied and adapted from File Media Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_media
 * @since		1.6
 */
class ZefaniabibleControllerFile extends JControllerLegacy
{
	/**
	 * Upload a file
	 *
	 * @since 1.5
	 */
	function upload()
	{
		// Check for request forgeries
		if (!JRequest::checkToken('request')) {
			$response = array(
				'status' => '0',
				'error' => JText::_('JINVALID_TOKEN'),
			);
			echo json_encode($response);
			
			return;
		}

		// Authorize User
		$user		= JFactory::getUser();
		if (!$user->authorise('core.create', 'com_zefaniabible')) {
			$response = array(
				'status' => '0',
				'error' => JText::_('JGLOBAL_AUTH_ACCESS_DENIED')
			);
			echo json_encode($response);
			return;
		}

		// Initialise variables.
		$params	= JComponentHelper::getParams('com_zefaniabible');
		$jinput	= JFactory::getApplication()->input;

		// Get some data from the request
		$file	= JRequest::getVar('Filedata', '', 'files', 'array');
		$type	= $jinput->get('type', 'audio', 'word');

		if (!$file['name']) {
			$response = array(
				'status' => '0',
				'error' => JText::_('COM_SERMONSPEAKER_FU_FAILED')
			);
			echo json_encode($response);
			return;
		}

		// Make the filename safe
		$file['name']	= JFile::makeSafe($file['name']);
		$file['name']	= str_replace(' ', '_', $file['name']); // Replace spaces in filename as long as makeSafe doesn't do this.

		// Check if filename has more chars than only underscores, making a new filename based on current date/time if not.
		if (count_chars(JFile::stripExt($file['name']), 3) == '_') {
			$file['name'] = JFactory::getDate()->format("Y-m-d-H-i-s").'.'.JFile::getExt($file['name']);
		}
		// Regular Upload
			$path	= ($type == 'addfile') ? $params->get('path_addfile') : $params->get('path');
			$path	= trim($path, '/');
			$date	= $jinput->get('date', '', 'string');
			$time	= ($date) ? strtotime($date) : time();
			$append	= ($params->get('append_path', 0)) ? '/'.date('Y', $time).'/'.date('m', $time) : '';
			if($params->get('append_path_lang', 0)){
				$lang	= $jinput->get('select-language');
				if(!$lang || $lang == '*'){
					$jlang	= JFactory::getLanguage();
					$lang	= $jlang->getTag();
				}
				$append	.= '/'.$lang;
			}
			$folder	= JPATH_ROOT.'/'.$path.$append;

			// Set FTP credentials, if given
			jimport('joomla.client.helper');
			JClientHelper::setCredentialsFromRequest('ftp');

			$err = null;
			$filepath = JPath::clean($folder.'/'.strtolower($file['name']));

			$object_file = new JObject($file);
			$object_file->filepath = $filepath;

			if (JFile::exists($filepath)) {
				// File exists
				$response = array(
					'status' => '0',
					'error' => JText::_('COM_SERMONSPEAKER_FU_ERROR_EXISTS')
				);
				echo json_encode($response);
				return;
			}

			$file = (array) $object_file;
			if (!JFile::upload($file['tmp_name'], $file['filepath'])) {
				// Error in upload
				$response = array(
					'status' => '0',
					'error' => JText::_('COM_SERMONSPEAKER_FU_ERROR_UNABLE_TO_UPLOAD_FILE')
				);
				echo json_encode($response);
				return;
			} else {
				$response = array(
					'status' => '1',
					'filename' => strtolower($file['name']),
					'path' => str_replace('\\', '/', '/'.$path.$append.'/'.strtolower($file['name'])),
					'error' => JText::sprintf('COM_SERMONSPEAKER_FU_FILENAME', substr($file['filepath'], strlen(JPATH_ROOT)))
				);
				echo json_encode($response);
				return;
			}
	}
}
