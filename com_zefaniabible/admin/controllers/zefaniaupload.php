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

class ZefaniabibleControllerZefaniaupload extends JControllerLegacy
{
	function __construct($config = array())
	{

		parent::__construct($config);

		$layout = JRequest::getCmd('layout');
		$render	= JRequest::getCmd('render');

		$this->context = strtolower('com_' . $this->getName() . '.' . $this->ctrl
					. ($layout?'.' . $layout:'')
					. ($render?'.' . $render:'')
					);

		$app = JFactory::getApplication();
		$this->registerTask( 'upload',  'upload' );
	}	 
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
			$fieldName = 'Filedata';
			 
			//any errors the server registered on uploading
			$fileError = $_FILES[$fieldName]['error'];
			if ($fileError > 0) 
			{
					switch ($fileError) 
					{
					case 1:
					echo JText::_( 'FILE TO LARGE THAN PHP INI ALLOWS' );
					return;
			 
					case 2:
					echo JText::_( 'FILE TO LARGE THAN HTML FORM ALLOWS' );
					return;
			 
					case 3:
					echo JText::_( 'ERROR PARTIAL UPLOAD' );
					return;
			 
					case 4:
					echo JText::_( 'ERROR NO FILE' );
					return;
					}
			}
			 
			//check the file extension is ok
			$fileName = $_FILES[$fieldName]['name'];
			$uploadedFileNameParts = explode('.',$fileName);
			$uploadedFileExtension = array_pop($uploadedFileNameParts);
			 
			$validFileExts = explode(',', 'xml');
			 
			//assume the extension is false until we know its ok
			$extOk = false;
			 
			//go through every ok extension, if the ok extension matches the file extension (case insensitive)
			//then the file extension is ok
			foreach($validFileExts as $key => $value)
			{
					if( preg_match("/$value/i", $uploadedFileExtension ) )
					{
							$extOk = true;
					}
			}
			 
			if ($extOk == false) 
			{
					echo JText::_( 'INVALID EXTENSION' );
					return;
			}
			 
			//the name of the file in PHP's temp directory that we are going to move to our folder
			$fileTemp = $_FILES[$fieldName]['tmp_name'];
			 
			//for security purposes, we will also do a getimagesize on the temp file (before we have moved it 
			//to the folder) to check the MIME type of the file, and whether it has a width and height
			$imageinfo = getimagesize($fileTemp);
			 
			//we are going to define what file extensions/MIMEs are ok, and only let these ones in (whitelisting), rather than try to scan for bad
			//types, where we might miss one (whitelisting is always better than blacklisting) 
			$okMIMETypes = 'application/xml';
			$validFileTypes = explode(",", $okMIMETypes);           
			 
			//if the temp file does not have a width or a height, or it has a non ok MIME, return
			if( !is_int($imageinfo[0]) || !is_int($imageinfo[1]) ||  !in_array($imageinfo['mime'], $validFileTypes) )
			{
					echo JText::_( 'INVALID FILETYPE' );
					return;
			}
			 
			//lose any special characters in the filename
			$fileName = preg_replace("/[^A-Za-z0-9]/i", "-", $fileName);
			 
			//always use constants when making file paths, to avoid the possibilty of remote file inclusion
			$uploadPath = JPATH_SITE.DS.'images'.DS.'stories'.DS.$fileName;
			 
			if(!JFile::upload($fileTemp, $uploadPath)) 
			{
					echo JText::_( 'ERROR MOVING FILE' );
					return;
			}
			else
			{
			   // success, exit with code 0 for Mac users, otherwise they receive an IO Error
			   exit(0);
			}
	}
}
