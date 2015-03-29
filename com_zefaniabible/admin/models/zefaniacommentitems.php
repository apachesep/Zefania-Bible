<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Item Model for zefaniacommentitems.
 *
 * @package     Zefaniabible
 * @subpackage  Models
 */
class ZefaniabibleModelZefaniacommentitems extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_ZEFANIABIBLE';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_zefaniabible.zefaniacommentitems';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			$user = JFactory::getUser();
			return $user->authorise('core.delete', $this->typeAlias . '.' . (int) $record->id);
		}
	}		

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable    A JTable object.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('id');
		$this->setState($this->getName() . '.id', $pk);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_zefaniabible');
		$this->setState('params', $params);
	}

	/**
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		// Set some needed variables.
		$this->user = JFactory::getUser();
		$this->table = $this->getTable();
		$this->tableClassName = get_class($this->table);
		$this->contentType = new JUcmType;
		$this->type = $this->contentType->getTypeByTable($this->tableClassName);
		$this->batchSet = true;

		if ($this->type == false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);

		}
		if ($this->type === false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);
			$typeAlias = $this->type->type_alias;
		}
		else
		{
			$typeAlias = $this->type->type_alias;
		}
		$this->tagsObserver = $this->table->getObserverOfClass('JTableObserverTags');

		if (!empty($commands['assetgroup_id']))
		{
			if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
	function delete(&$pks)
	{
		$result = false;
		foreach($pks as $pk)
		{
			try
			{
				$db = $this->getDbo();
				$pk_clean 	= $db->quote($pk);							
							
				$query = 'DELETE FROM `#__zefaniabible_comment_text` '
				. ' WHERE bible_id = '.$pk_clean;	
				
				$db->setQuery($query);
				$result = $db->execute();
			}
			catch (JException $e)
			{
				print_r($this->setError($e));
			}
		}
		parent::delete($pks);
		return true;
	}			
	/**
	 * Alias for JTable::getInstance()
	 *
	 * @param   string  $type    The type (name) of the JTable class to get an instance of.
	 * @param   string  $prefix  An optional prefix for the table class name.
	 * @param   array   $config  An optional array of configuration values for the JTable object.
	 *
	 * @return  mixed    A JTable object if found or boolean false if one could not be found.
	 */
	public function getTable($type = 'Zefaniacommentitems', $prefix = 'ZefaniabibleTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		JForm::addRulePath(JPATH_COMPONENT_ADMINISTRATOR.'/models/rules');		
		
		$options = array('control' => 'jform', 'load_data' => $loadData);
		$form = $this->loadForm($this->typeAlias, $this->name, $options);
		
		if(empty($form))
		{
			return false;
		}


		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 */
	protected function loadFormData()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState($this->option . '.edit.' . $this->name . '.data', array());
		
		if(empty($data))
		{
			$data = $this->getItem();
		}
		
		return $data;
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if (!$item = parent::getItem($pk))
		{			
			throw new Exception('Failed to load item');
		}

		if (!$item->id)
		{
			$item->created_by = JFactory::getUser()->get('id');
			$item->modified_by = JFactory::getUser()->get('id');
		}
		
		return $item;
	}
	function save($data)
	{
		$params	= JComponentHelper::getParams( 'com_zefaniabible' );
		$row = $this->getTable();

		//Convert data from a stdClass
		if (is_object($data)){
			if (get_class($data) == 'stdClass')
				$data = JArrayHelper::fromObject($data);
		}

		//Current id if unspecified
		if ($data['id'] != null)
			$id = $data['id'];
		else if (($this->_id != null) && ($this->_id > 0))
			$id = $this->_id;


		//Load the current object, in order to process an update
		if (isset($id))
			$row->load($id);

		//Secure the published tag if not allowed to change
		if (isset($data['publish']) && !$acl->get('core.edit.state'))
			unset($data['publish']);


		// Bind the form fields to the zefaniabible table
		$ignore = array();
		if (!$row->bind($data, $ignore)) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}





		// Make sure the zefaniabible table is valid
		if (!$row->check()) {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}



		// Store the zefaniabible table to the database
		if (!$row->store())
        {
			JError::raiseWarning(1000, $this->_db->getErrorMsg());
			return false;
		}

		$this->_id = $row->id;
		$this->_data = $row;
		if(!$id)
		{	
			$str_folder_file = $data['file_location_list'];
			
			if($data['file_location'] == "")
			{
				$str_path = $params->get('xmlCommentaryPath', 'media/com_zefaniabible/commentary/');
				$data['file_location'] = '/'.$str_path.$str_folder_file;				
			}		
			$app = JFactory::getApplication();
			
			$int_rows_inserted = $this->fnc_Loop_Thorugh_File($row->file_location, $row->id);
			if($int_rows_inserted > 1)
			{
				$app->enqueueMessage($int_rows_inserted." ".JText::_( 'ZEFANIABIBLE_FIELD_VERSES_ADDED'));
			}
			else
			{
				JError::raiseWarning('',JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UNABLE_TO_UPLOAD_FILE'));
			}	
		}
		return true;
	}
	private function fnc_Loop_Thorugh_File($str_bible_xml_file_url, $int_max_ids)
	{ 		
		$app = JFactory::getApplication();
		jimport( 'joomla.filesystem.folder' );		
		jimport('joomla.filesystem.file');
		
		$params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$str_path_commentary_folder	= $params->get('xmlCommentaryPath', 'media/com_zefaniabible/commentary/');
		$str_subfolder_commentary = str_replace($str_path_commentary_folder,'',$str_bible_xml_file_url);
		$str_subfolder_commentary = substr_replace(str_replace(basename($str_subfolder_commentary),'',$str_subfolder_commentary),'',0,1);
		$str_commentary_path 		= JPATH_SITE.$str_path_commentary_folder.$str_subfolder_commentary;
		
		// check if file exists
		if(JFile::exists($str_commentary_path) != true)
		{
			JError::raiseWarning('',str_replace('%s',$str_commentary_path,JText::_('ZEFANIABIBLE_UPLOAD_ERROR')));
		}
				
		$arr_xml_main_commentary 	= simplexml_load_file(substr_replace(JURI::root(),"",-1).$str_bible_xml_file_url);
		$int_bible_book_id = 1;
		$x = 1;
		
		foreach($arr_xml_main_commentary->BIBLEBOOK as $obj_commentary_book)
		{
			$int_bible_book_id = (int)$obj_commentary_book['bnumber'];
			$int_bible_chapter = 1;
			foreach($obj_commentary_book->CHAPTER as $obj_commenary_chapter)
			{
				$int_bible_chapter = (int)$obj_commenary_chapter['cnumber'];
				$int_bible_verse = 1;
				$str_verse = '';
				foreach($obj_commenary_chapter->VERS as $obj_commenary_verse)
				{
					$int_bible_verse = (int)$obj_commenary_verse['vnumber'];
					$str_verse = $obj_commenary_verse->asXML();
					$obj_commenary_verse->asXML();
					$this->fnc_insert_commentary_verses(
							$int_max_ids, 
							$int_bible_book_id,
							$int_bible_chapter,
							$int_bible_verse,
							$str_verse);
						$x++;									
				}
				
			}
		}		
		if($x == 1)
		{
			$this->fnc_insert_commentary_verses($int_max_ids,1,1,1,'Failed to load commentary');
		}	
		return $x;
	}
	
	private function fnc_insert_commentary_verses($int_bible_id, $int_bible_book_id, $int_bible_chapter, $int_bible_verse, $str_verse)
	{
		try
		{
			$db = JFactory::getDBO();
			$arr_row->bible_id		= (int)$int_bible_id;
			$arr_row->book_id 		= (int)$int_bible_book_id;
			$arr_row->chapter_id 	= (int)$int_bible_chapter;
			$arr_row->verse_id 		= (int)$int_bible_verse;
			$arr_row->verse 		= (string)strip_tags(html_entity_decode(html_entity_decode($str_verse)),'<b><em><br><i><span><div><hr><h1><h2><h3><h4><h5><h6><li><ol><ul><table><tr><td><u><th>');
			$db->insertObject("#__zefaniabible_comment_text", $arr_row, 'id');
		}
		catch (JException $e)
		{
			print_r($this->setError($e));
		}			
	}
}
?>