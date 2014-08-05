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
	private $str_Bible_books;
	private $str_Bible_book_id;		
	private $str_begin_chap;
	private $str_begin_verse;
	private $str_end_verse;
	private $str_end_chap;	
	private $flg_search_by_scripture;
	private $flg_search_one_bible;
	private $int_limit_query;
	private $flg_search_commentary;
	private $flg_search_dictionary;
	private $cnt_area_searched;
	private $flg_search_one_dictionary;
	private $flg_search_one_commentary;
	private $str_primary_commentary;
	private $str_primary_dictionary;
	private $flg_strong;
	private $arr_english_book_names;
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$this->flg_search_commentary 		= $this->params->get('flg_search_commentary', 0);
		$this->flg_search_dictionary 		= $this->params->get('flg_search_dictionary', 0);
		$this->flg_search_one_dictionary 	= $this->params->get('flg_search_one_dictionary', 0);
		$this->flg_search_one_commentary 	= $this->params->get('flg_search_one_commentary', 0);
		
		$comp_params = JComponentHelper::getParams( 'com_zefaniabible' );
		$this->str_primary_commentary = $comp_params->get('primaryCommentary');
		$this->str_primary_dictionary = $comp_params->get('str_primary_dictionary');
		$this->flg_strong = 0;
		for($z = 1; $z <= 66; $z ++)
		{
			$this->str_Bible_books = $this->str_Bible_books . mb_strtolower(JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_BOOK_NAME_'.$z,'UTF-8'))."|";
		}
	}

	/**
	 * @return array An array of search areas
	 */
	function onContentSearchAreas()
	{
		$areas['Bible'] = 'PLG_ZEFANIABIBLE_SEARCH_BIBLE_BIBLE';
		$this->cnt_area_searched = 1;
		if($this->flg_search_commentary)
		{
			$areas['Commentary'] = 'PLG_ZEFANIABIBLE_SEARCH_BIBLE_COMMENTARY';
			$this->cnt_area_searched ++;
		}
		if($this->flg_search_dictionary)
		{
			$areas['Dictionary'] = 'PLG_ZEFANIABIBLE_SEARCH_BIBLE_DICTIONARY';
			$this->cnt_area_searched ++;
		}
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
		$arr_result = '';		
		$flg_search_area = 0;
		$arr_data = '';
		$arr_result = array();

		
		$jlang = JFactory::getLanguage();
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', 'en-GB', true);
		$jlang->load('plg_search_zefaniabible', JPATH_BASE."/plugins/search/zefaniabible", 'en-GB', true);		
		for($i = 1; $i <=66; $i++)
		{
			$this->arr_english_book_names[$i] = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$i);
		}
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);
		$jlang->load('plg_search_zefaniabible', JPATH_BASE."/plugins/search/zefaniabible", null, true);
		
		
		$this->params_zefania_comp = JComponentHelper::getParams( 'com_zefaniabible' );
		$biblePath = $this->params_zefania_comp->get('xmlBiblesPath', 'media/com_zefaniabible/bibles/');
		$str_bible_alias = $this->params->get('search_Bible_alias', 'kjv');
		$str_menuItem = $this->params->get('zb_search_mo_menuitem', 0);
		$this->int_limit_query= $this->params->get('int_limit_query', 100);
		
		$this->flg_search_one_bible = $this->params->get('flg_search_one_bible', '0');
		$this->str_primary_bible = $this->params_zefania_comp->get('primaryBible', 'kjv');
		if($text != "")
		{
			preg_replace_callback( "/(?=\S)([HG](\d{1,4}))/iu", array( &$this, 'fnc_Make_Strong_Scripture'),  $text);	// STRONG number
			preg_replace_callback( "/^(".$this->str_Bible_books.")(\.)?(\s)(\d{1,3})([:,](?=\d))?(\d{1,3})?[-]?(\d{1,3})?$/", array( &$this, 'fnc_Make_Scripture'),  $text);		
			
			if(count($areas) >= 1)
			{
				$this->int_limit_query = ($this->int_limit_query / count($areas));
				foreach ($areas as $area)
				{					
					switch (true)
					{
						case $area == 'Bible':
						case $area == 'Commentary':
						case $area == 'Dictionary':
							if($this->flg_search_by_scripture == 0)
							{			
								$arr_data =  $this->fnc_make_bible_search_request($text,$area);
							}
							else
							{
								$arr_data = $this->fnc_make_bible_verse_request($text,$area);
							}					
							$arr_result = array_merge($arr_result, $this->fnc_make_Search_Query($arr_data,$str_menuItem,$area));						
							break;
						default:
							break;
					}
	
				}
			}
			else
			{
				$arr_areas = array("Bible", "Commentary", "Dictionary");
				$this->int_limit_query = ($this->int_limit_query / $this->cnt_area_searched);			
				for($x = 0; $x < 3; $x++)
				{
					if($this->flg_search_by_scripture == 0)
					{			
						$arr_data =  $this->fnc_make_bible_search_request($text,$arr_areas[$x]);
						$arr_result = array_merge($arr_result, $this->fnc_make_Search_Query($arr_data,$str_menuItem,$arr_areas[$x]));						
					}
					else
					{
						switch(true)
						{
							case (($this->flg_strong != 1)and($arr_areas[$x] =="Bible")):
							case (($this->flg_strong != 1)and($arr_areas[$x] =="Commentary")):
							case (($this->flg_strong == 1)and($arr_areas[$x] =="Dictionary")):
								$arr_data = $this->fnc_make_bible_verse_request($text,$arr_areas[$x]);
								$arr_result = array_merge($arr_result, $this->fnc_make_Search_Query($arr_data,$str_menuItem,$arr_areas[$x]));						
								break;
							default:
								break;
						}
					}	

				}				
			}
			if(count(	$arr_result) > 0)
			{
				return $arr_result;				
			}
		}
	}

	private function fnc_make_bible_search_request($str_text,$area)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query  = $db->getQuery(true);
			$str_text 	= $db->quote('%'.$str_text.'%');
			switch($area)
			{
				case 'Bible':
					$str_primary_bible 	= $db->quote($this->str_primary_bible);					
					$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name as bible_name, b.alias');
					$query->from('`#__zefaniabible_bible_text` AS a');	
					$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');	
					$query->where("a.verse LIKE ".$str_text);
					$query->order('bible_name, a.book_id, a.chapter_id, a.verse_id');		
					if($this->flg_search_one_bible)
					{
						$query->where("b.alias=".$str_primary_bible);
					}								
					break;
				case 'Commentary':
					$str_primary_commentary 	= $db->quote($this->str_primary_commentary);
					$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse, b.title as bible_name, b.alias');
					$query->from('`#__zefaniabible_comment_text` AS a');	
					$query->innerJoin('`#__zefaniabible_zefaniacomment` AS b ON a.bible_id = b.id');					
					$query->where("a.verse LIKE ".$str_text);
					$query->order('bible_name, a.book_id, a.chapter_id, a.verse_id');		
					if($this->flg_search_one_commentary)
					{
						$query->where("b.alias=".$str_primary_commentary);
					}					
					break;
				case 'Dictionary':
					$str_primary_dictionary 	= $db->quote($this->str_primary_dictionary);
					$query->select('a.dict_id, a.item, a.description, b.name, b.alias');
					$query->from('`#__zefaniabible_dictionary_detail` AS a');	
					$query->innerJoin('`#__zefaniabible_dictionary_info` AS b ON a.dict_id = b.id');	
					$query->where("a.description LIKE ".$str_text);
					if($this->flg_search_one_dictionary)
					{
						$query->where("b.alias=".$str_primary_dictionary);
					}					
					$query->order('a.dict_id');					
					break;
				default:
					"";
					break;
			}
			if($this->int_limit_query> 1)
			{
				$db->setQuery($query,0, $this->int_limit_query);
			}
			else
			{
				$db->setQuery($query);
			}
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
		return $data;					
	}
	private function fnc_make_bible_verse_request($text,$area)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query  = $db->getQuery(true);
			switch ($area)
			{
				case 'Bible':
					$str_primary_bible 	= $db->quote($this->str_primary_bible);
					$str_Bible_book_id 	= $db->quote($this->str_Bible_book_id);
					$str_begin_chap 	= $db->quote($this->str_begin_chap);
					$str_begin_verse 	= $db->quote($this->str_begin_verse);
					$str_end_verse 		= $db->quote($this->str_end_verse);
					
					$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name as bible_name, b.alias');
					$query->from('`#__zefaniabible_bible_text` AS a');	
					$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
					$query->where("a.book_id=".$str_Bible_book_id);
					$query->where("a.chapter_id=".$str_begin_chap);
					if($this->str_end_verse)
					{
						$query->where("a.verse_id>=".$str_begin_verse);
						$query->where("a.verse_id<=".$str_end_verse);
					}
					else
					{
						$query->where("a.verse_id=".$str_begin_verse);
					}
					$query->order('bible_name, a.book_id, a.chapter_id, a.verse_id');		
					if($this->flg_search_one_bible)
					{
						$query->where("b.alias=".$str_primary_bible);
					}		
					break;
				case 'Commentary':
					$str_primary_commentary 	= $db->quote($this->str_primary_commentary);
					$str_Bible_book_id 			= $db->quote($this->str_Bible_book_id);
					$str_begin_chap 			= $db->quote($this->str_begin_chap);
					$str_begin_verse 			= $db->quote($this->str_begin_verse);
					$str_end_verse 				= $db->quote($this->str_end_verse);
					
					$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse, b.title as bible_name, b.alias');
					$query->from('`#__zefaniabible_comment_text` AS a');	
					$query->innerJoin('`#__zefaniabible_zefaniacomment` AS b ON a.bible_id = b.id');					
					$query->where("a.book_id=".$str_Bible_book_id);
					$query->where("a.chapter_id=".$str_begin_chap);
					if($this->str_end_verse)
					{
						$query->where("a.verse_id>=".$str_begin_verse);
						$query->where("a.verse_id<=".$str_end_verse);
					}
					else
					{
						$query->where("a.verse_id=".$str_begin_verse);
					}
					$query->order('bible_name, a.book_id, a.chapter_id, a.verse_id');		
					if($this->flg_search_one_commentary)
					{
						$query->where("b.alias=".$str_primary_commentary);
					}					
					
					break;
				case 'Dictionary':
					$str_primary_dictionary 	= $db->quote($this->str_primary_dictionary);
					$str_text 					= $db->quote($text);
					
					$query->select('a.dict_id, a.item,a.description, b.name, b.alias');
					$query->from('`#__zefaniabible_dictionary_detail` AS a');	
					$query->innerJoin('`#__zefaniabible_dictionary_info` AS b ON a.dict_id = b.id');	
					$query->where("a.item =".$str_text);	
					if($this->flg_search_one_dictionary)
					{
						$query->where("b.alias=".$str_primary_dictionary);
					}
					$query->order('a.dict_id');					
					break;
				default:
					break;	
			}

			if($this->int_limit_query> 1)
			{
				$db->setQuery($query,0, $this->int_limit_query);
			}
			else
			{
				$db->setQuery($query);
			}
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
		return $data;
	}
	private function fnc_Make_Strong_Scripture(&$arr_matches)
	{
		 $this->flg_strong =1;
		 $this->flg_search_by_scripture = 1;
	}
	private function fnc_Make_Scripture(&$arr_matches)
	{
		$str_scripture = $arr_matches[0];
		$this->str_Bible_book_id = 0;
		$str_passages = '';
		$this->str_begin_chap = '';
		$this->str_begin_verse = '';
		$this->str_end_verse = '';
		$this->str_end_chap = '';
		$str_proper_name = '';
		$str_look_up;
		$arr_look_up_orig = '';
		$str_scripture_book_name = $arr_matches[1];
							
		for($z = 1; $z <= 66; $z ++)
		{
			$arr_multi_query = '';
			$str_look_up = mb_strtolower(JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_BOOK_NAME_'.$z,'UTF-8'));
			$arr_look_up_orig = explode('|',JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_BOOK_NAME_'.$z));
			if(preg_match('/^('.$str_look_up.')$/', mb_strtolower($str_scripture_book_name,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$this->str_Bible_book_id = $z;
				$str_passages = trim(str_replace($str_scripture_book_name ,'',$str_scripture));
				switch (true)
				{										
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})$/',$str_passages):   				// Gen 1:1
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3})$/',$str_passages): 	// Gen 1:1-4
					 	$arr_split_verses = preg_split('#[:-]+#',$str_passages); 					// split on colon and hyphen
						if(count($arr_split_verses) == 3)
						{
							list($this->str_begin_chap,$this->str_begin_verse,$this->str_end_verse) = $arr_split_verses;
						}
						else
						{
							list($this->str_begin_chap,$this->str_begin_verse) = $arr_split_verses;
							$this->str_end_verse = '0';
						}
						$this->str_end_chap = '0';
						$this->flg_search_by_scripture = 1;
						break;			
	
					default:
						break;	
				}
				break;
			}
		}
	}
	private function fnc_make_Search_Query($data,$str_menuItem, $area)
	{
		foreach($data as $y => $datum)
		{
			switch($area)
			{
				case 'Bible':
					$str_temp_title = strtolower(str_replace(" ","-",$this->arr_english_book_names[$datum->book_id]));
					// strip stong verses.
					$datum->verse = preg_replace("/(?=\S)([HG](\d{1,4}))/iu",'',$datum->verse);				
					$data[$y]->title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$datum->book_id)." ".$datum->chapter_id.":".$datum->verse_id;				
					$data[$y]->text = $datum->verse;				
					$data[$y]->href = JRoute::_("index.php?option=com_zefaniabible&view=standard&Itemid=".$str_menuItem."&bible=".$datum->alias.
						"&book=".$datum->book_id."-".str_replace(" ","-",$str_temp_title).
						"&chapter=".$datum->chapter_id.'-chapter');
					$data[$y]->section = $datum->bible_name." - ".JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_COMPONENT_NAME');
					break;
				case 'Commentary':
					$str_temp_title = strtolower(str_replace(" ","-",$this->arr_english_book_names[$datum->book_id]));
					// strip stong verses.
					$datum->verse = preg_replace("/(?=\S)([HG](\d{1,4}))/iu",'',$datum->verse);				
				
					$data[$y]->title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$datum->book_id)." ".$datum->chapter_id.":".$datum->verse_id;				
					$data[$y]->text = $datum->verse;								
					$data[$y]->href = JRoute::_("index.php?option=com_zefaniabible&view=commentary&Itemid=".$str_menuItem."&com=".$datum->alias.
						"&book=".$datum->book_id."-".str_replace(" ","-",$str_temp_title).
						"&chapter=".$datum->chapter_id.'-chapter'.'&verse='.$datum->verse_id.'-verse');				
					$data[$y]->section = $datum->bible_name." - ".JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_COMPONENT_NAME');						
					break;
				case 'Dictionary':
					$datum->description = preg_replace("/(?=\S)(&lt;tw\:\/\/\[self\]\?(.*?)&gt;)/iu",'',$datum->description); // remove <tw://[self]?.. code
					$data[$y]->title = $datum->item;				
					$data[$y]->text = $datum->description;								
					$data[$y]->href = JRoute::_("index.php?option=com_zefaniabible&view=strong&Itemid=".$str_menuItem."&dict=".$datum->alias.
						"&item=".$datum->item);		
					$data[$y]->section = $datum->name." - ".JText::_('PLG_ZEFANIABIBLE_SEARCH_BIBLE_COMPONENT_NAME');					
					break;
				default:
					break;
			}
			$data[$y]->created = $this->params_zefania_comp->get('reading_start_date', '1-1-2012');
			$data[$y]->browsernav = '2';
		}		
		return $data;	
	}
}
