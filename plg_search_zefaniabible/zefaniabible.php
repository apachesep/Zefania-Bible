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

// no direct access
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

/**
 * Categories Search plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	Search.categories
 * @since		1.6
 */
class plgSearchZefaniaBible extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.6
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @return array An array of search areas
	 */
	function onContentSearchAreas()
	{
		static $areas = array(
		'Bible' => 'PLG_ZEFANIABIBLE_SEARCH_BIBLE_BIBLE'
		);
		return $areas;
	}

	/**
	 * Categories Search method
	 *
	 * The sql must return the following fields that are
	 * used in a common display routine: href, title, section, created, text,
	 * browsernav
	 * @param string Target search string
	 * @param string mathcing option, exact|any|all
	 * @param string ordering option, newest|oldest|popular|alpha|category
	 * @param mixed An array if restricted to areas, null if search all
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$flg_proceed = 0;
		if(($areas)and($text))
		{
			foreach($areas as $area)
			{
				if($area == "Bible")
				{
					$flg_proceed = 1;
				}
			}
		}
		elseif(($areas==null)and($text))
		{
			$flg_proceed = 1;
		}
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);

		
		if($flg_proceed)
		{
			$this->params_zefania_comp = &JComponentHelper::getParams( 'com_zefaniabible' );
			$biblePath = $this->params_zefania_comp->get('xmlBiblesPath', 'media/com_zefaniabible/bibles/');
			$str_bible_alias = $this->params->get('search_Bible_alias', 'kjv');
			$str_menuItem = $this->params->get('zb_search_mo_menuitem', 0);
			
			$flg_search_one_bible = $this->params->get('flg_search_one_bible', '0');
			$params_zefania_comp = JComponentHelper::getParams( 'com_zefaniabible' );
			$str_primary_bible = $params_zefania_comp->get('primaryBible', 'kjv');
			
			$flg_search_by_scripture = 0;
			$arr_result[0]->href = "";
			$arr_result[0]->title = "";
			$arr_result[0]->text = "";
			$arr_result[0]->section = "";
			$arr_result[0]->created = "";
			$arr_result[0]->browsernav = '2';	
					
			$arr_book_id = '';
			$arr_chapter_id = '';
			$arr_verse_id = '';
			$arr_verse = '';
			
			for($z = 1; $z <= 66; $z ++)
			{
				$arr_look_up[$z] = mb_strtolower(JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_BOOK_NAME_'.$z,'UTF-8'));
				if(preg_match('/(?=\S)(('.$arr_look_up[$z].')[\.]?\s?(\d{1,3}):(\d{1,3}))/siU', mb_strtolower($text,'UTF-8')))	
				{
					$arr_bible_Book_id = $z;
					$arr_bible_Book_name = explode("|",$arr_look_up[$z]);
					$arr_scripture =  explode(":",preg_replace('/^('.$arr_look_up[$z].')[\.]?\s?/', '', mb_strtolower($text,'UTF-8')));
					$flg_search_by_scripture = 1;
				} 
			} 			
			
			if($flg_search_by_scripture == 0)
			{
				
				$db		= JFactory::getDbo();
				$query  = $db->getQuery(true);
				$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name, b.alias');
				$query->from('`#__zefaniabible_bible_text` AS a');	
				$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
				$query->where("a.verse LIKE '%".$text."%'");
				$query->order('b.bible_name, a.book_id, a.chapter_id, a.verse_id');		
				if($flg_search_one_bible)
				{
					$query->where("b.alias='".$str_primary_bible."'");
				}
				
				$db->setQuery($query);
				$data = $db->loadObjectList();	
			}
			else
			{
				$db		= JFactory::getDbo();
				$query  = $db->getQuery(true);
				$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name, b.alias');
				$query->from('`#__zefaniabible_bible_text` AS a');	
				$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
				$query->where("a.book_id='".(int)$arr_bible_Book_id."' AND a.chapter_id='".(int)$arr_scripture[0]."' AND a.verse_id='".(int)$arr_scripture[1]."'");
				$query->order('b.bible_name, a.book_id, a.chapter_id, a.verse_id');		
				if($flg_search_one_bible)
				{
					$query->where("b.alias='".$str_primary_bible."'");
				}
				$db->setQuery($query);
				$data = $db->loadObjectList();					
			}
			$arr_result = $this->fnc_make_Search_Query($data,$str_menuItem);	
			
			if($arr_result[0]->title != "")
			{
				return $arr_result;				
			}
		}
	}
	private function fnc_make_Search_Query($data,$str_menuItem)
	{
		$x = 0;
		$y = 0;
		
		foreach($data as $datum)
		{
			$arr_book_id[$x] = $datum->book_id;
			$arr_chapter_id[$x] = $datum->chapter_id;
			$arr_verse_id[$x] = $datum->verse_id;
			$arr_verse[$x] = $datum->verse;
			$arr_bible_name[$x] = $datum->bible_name;
			$arr_bible_alias[$x] = $datum->alias;
			$x++;
		}
		foreach($arr_book_id as $item)
		{
			$arr_temp_title = explode("|",JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_book_id[$y]));
			$str_temp_title = mb_convert_case($arr_temp_title[0], MB_CASE_TITLE, "UTF-8");
			$arr_result[$y]->href = JRoute::_("index.php?option=com_zefaniabible&view=standard&Itemid=".$str_menuItem."&a=".$arr_bible_alias[$y].
				"&b=".$arr_book_id[$y]."-".str_replace(" ","-",$str_temp_title).
				"&c=".$arr_chapter_id[$y].'-'.mb_strtolower(JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_CHAPTER'),'UTF-8'));
			$arr_result[$y]->title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_book_id[$y])." ".$arr_chapter_id[$y].":".$arr_verse_id[$y];
			$arr_result[$y]->text = $arr_verse[$y];
			$arr_result[$y]->section = $arr_bible_name[$y]." - ".JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_COMPONENT_NAME');
			$arr_result[$y]->created = $this->params_zefania_comp->get('reading_start_date', '1-1-2012');
			$arr_result[$y]->browsernav = '2';	
			$y++;	
		}		
		return $arr_result;	
	}
}
