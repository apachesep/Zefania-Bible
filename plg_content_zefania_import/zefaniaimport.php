<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Search Plugin
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

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
class plgContentZefaniaImport extends JPlugin
{

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$document	= JFactory::getDocument();
		$docType = $document->getType();
		if($docType != 'html') return; 
		$this->loadLanguage();
		JHTML::stylesheet('zefaniascripturelinks.css', 'plugins/content/zefaniascripturelinks/css/');
		JHTML::_('behavior.modal');
	}
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{ 		
		$str_bible_xml_file  	= $this->params->get('str_bible_xml_file');
		$str_bible_name 		= $this->params->get('str_bible_name');
		$str_bible_alias 		= $this->params->get('str_bible_alias');
		$str_bible_desc 		= $this->params->get('str_bible_desc');
		$str_audio_xml_file_url	= $this->params->get('str_audio_xml_file');
		$str_insert_method		= $this->params->get('flg_insert_method'); 
		$str_bible_xml_file_url	= 'media/com_zefaniabible/bibles/'.$str_bible_xml_file;
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);
		
		$int_bible_id = 0;
		$flg_bible_exists = $this->fnc_Check_If_Bible_Exists($str_bible_xml_file_url);
		$arr_xml_bible = simplexml_load_file($str_bible_xml_file_url);
		$x=1;
		if((!$flg_bible_exists)and($str_insert_method == 'insert'))
		{
			//$int_bible_id = $this->fnc_Find_Last_Row_Names();
			$int_bible_id = $this->fnc_Create_New_Bible_Book(
				$str_bible_name,
				$str_bible_alias,
				$str_bible_desc,
				$str_bible_xml_file_url,
				$str_audio_xml_file_url);
				
			foreach($arr_xml_bible->BIBLEBOOK as $arr_bible_book)
			{
				foreach($arr_bible_book->CHAPTER as $arr_bible_chapter)
				{
					foreach($arr_bible_chapter->VERS as $arr_bible_verse)
					{
						$this->fnc_Update_Bible_Verses(
							($int_bible_id), 
							$arr_bible_book['bnumber'],
							$arr_bible_chapter['cnumber'],
							$arr_bible_verse['vnumber'],
							$arr_bible_verse, 
							$str_insert_method);
							$x++;
					}
				}
			}
			echo $x." rows inserted.";
		}
		elseif($str_insert_method == 'update')
		{
			
		}
		elseif($str_insert_method == 'delete')
		{
			$this->fnc_Delete_Bible($str_bible_alias);
		}
	
      	return true;
	}
	protected function fnc_Delete_Bible($str_bible_alias)
	{
		try 
		{
			$db = JFactory::getDBO();	
			$query = 'DELETE a.*, b.* FROM `#__zefaniabible_bible_text` AS a'
				. ' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id'
				. ' WHERE b.alias = "'.$str_bible_alias.'"';		
			$db->setQuery($query);
			$db->query();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}				
	}
	protected function fnc_Find_Last_Row_Names()
	{
		try 
		{
			$db = JFactory::getDBO();			
			$query_max = "SELECT Max(id) FROM `#__zefaniabible_bible_names`";	
			$db->setQuery($query_max);	
			$int_max_ids = $db->loadResult();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return 	$int_max_ids;
	}
	protected function fnc_Check_If_Bible_Exists($str_bible_xml_file_url)
	{
		$str_xml_url = '';
		$flg_bible_exists = 0;
		try 
		{
			$db = JFactory::getDBO();
			$query = "SELECT a.xml_file_url FROM `#__zefaniabible_bible_names` AS a WHERE a.xml_file_url='".$str_bible_xml_file_url."'";			
			$db->setQuery($query);
			$arr_bible_xml_url = $db->loadObjectList();	
			foreach($arr_bible_xml_url as $str_bible_xml_url)
			{
				$str_xml_url = $str_bible_xml_url->xml_file_url;
			}
			if($str_xml_url != '')
			{
				$flg_bible_exists = 1;
			}
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $flg_bible_exists;
	}
	protected function fnc_Get_Verses($str_bible_alias)
	{
		try
		{
			$db = JFactory::getDBO();			
			$query =  'SELECT a.book_id, a.chapter_id, a.verse_id, a.verse FROM `#__zefaniabible_bible_text` AS a '
			. ' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id'
			.' WHERE b.alias ="'.$str_bible_alias.'"';			
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}			
		return $data;
	}
	protected function fnc_Create_New_Bible_Book($str_bible_name, $str_bible_alias, $str_bible_desc, $str_bible_xml_file_url, $str_audio_xml_file_url)
	{
		try
		{
			$db = JFactory::getDBO();
			$arr_row->bible_name  	= 	(string)$str_bible_name;
			$arr_row->alias 		= 	(string)$str_bible_alias;
			$arr_row->desc 			=	(string)$str_bible_desc;
			$arr_row->xml_file_url 	=	(string)$str_bible_xml_file_url;
			$arr_row->xml_audio_url  =	(string)$str_audio_xml_file_url; // need to modify table
			$arr_row->publish 		= 	(int)'1' ;
			$arr_row->ordering		= 	(int)'99';
			$db->insertObject("#__zefaniabible_bible_names", $arr_row, 'id');	
			$int_bible_id = $this->fnc_Find_Last_Row_Names();
		}
		catch (JException $e)
		{
			print_r($this->setError($e));
		}
		return $int_bible_id;
	}
	protected function fnc_Update_Bible_Verses($int_bible_id,$int_book_id,$int_chapter_id,$int_verse_id,$str_verse, $str_insert_method)
	{
		try
		{
			$db = JFactory::getDBO();
			$arr_row->bible_id		= (int)$int_bible_id;
			$arr_row->book_id 		= (int)$int_book_id;
			$arr_row->chapter_id 	= (int)$int_chapter_id;
			$arr_row->verse_id 		= (int)$int_verse_id;
			$arr_row->verse 		= (string)$str_verse;
			if($str_insert_method == 'insert')
			{
				$db->insertObject("#__zefaniabible_bible_text", $arr_row, 'id');
			}
			elseif($str_insert_method = 'update')
			{
				$arr_row->id = 	$id;				
				$db->updateObject("#__zefaniabible_bible_text", $arr_row, 'id');
			}
		}
		catch (JException $e)
		{
			print_r($this->setError($e));
		}			
	}
}
?>