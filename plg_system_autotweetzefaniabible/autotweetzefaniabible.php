<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.propoved.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.helper');
// Check for component
if (!JComponentHelper::getComponent('com_autotweet', true)->enabled)
{
	JError::raiseWarning('5', 'AutoTweet NG ZefaniaBible - Verse Plugin - AutoTweet NG Component is not installed or not enabled.');
	return;
}

include_once JPATH_ROOT . '/administrator/components/com_autotweet/helpers/autotweetbase.php';

/**
 * PlgSystemAutotweetZefaniaBible class.
 *
 * @package     Extly.Plugins
 * @subpackage  com_autotweet
 * @since       1.0
 */
class PlgSystemAutotweetZefaniaBible  extends plgAutotweetBase
{
	const TYPE_VERSEOFDAY = 1;	
	const TYPE_BIBLEREADING = 1;
	protected $autopublish;
	protected $interval = 60;
	
	private $flg_verse_publish;
	private $str_Bible;
	private $str_Reading;
		
	private $flg_use_year_date;	
	private $str_verse_start_date;	
	private $arr_verse_start_date;
	private $str_reading_start_date;
	private $arr_reading_start_date;
		
	private $str_social_verse;
	private $str_social_reading;	
	private $str_today;
	
	private $int_verse_day_diff;
	private $int_reading_day_diff;
		
	private $int_max_verses;
	private $int_verse_remainder;
	private $str_verse_title;
	private $str_verse_body;
	private $book_name;
	private $chapter_number;
	private $begin_verse;
	private $end_verse;
	private $str_verse_link;
	private $int_max_reading_days;
	private $str_verse_of_day_image;
	
	private $verse_menuitem;
	private $reading_menuitem;	
	private $id;
	
	private $int_reading_remainder;
	private $arr_reading;
	private $arr_reading_data;
	private $str_reading_title;
	private $str_reading_body;
	private $str_reading_link;
	private $str_reading_plan_image;
	private $str_verse_prepend;
	private $str_reading_prepend;
	
	/**
	 * PlgSystemautotweetzefaniabibleverse.
	 *
	 * @param   string  $subject  Params
	 * @param   array   $params   Params
	 */
	public function __construct(&$subject, $params)
	{
		parent::__construct($subject, $params);
		// don't run anythinig below for admin section
		if((strrpos(JURI::base(),'administrator',0) > 0)or(strrpos(JURI::base(),'administrator',0) !=''))
		{
			return;
		}
		$document	= JFactory::getDocument();
		$docType = $document->getType();
		if($docType != 'html') return; 
		
			
		$this->loadLanguage();
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);
		$jlang = JFactory::getLanguage();
		$jlang->load('zefaniabible', 'components/com_zefaniabible', 'en-GB', true);
		$jlang->load('zefaniabible', 'components/com_zefaniabible', null, true);	
		
		// Joomla event specific params
		$pluginParams = $this->pluginParams;
		
		$this->autopublish = $pluginParams->get('autopublish', 0);
		$this->interval = (int) $pluginParams->get('interval', 60);
		$this->flg_verse_publish = $pluginParams->get('flg_verse_publish', 0);
		$this->flg_plan_publish = $pluginParams->get('flg_plan_publish', 0);
		
		$this->str_Bible = $pluginParams->get('str_Bible', 'kjv');
		$this->str_Reading = $pluginParams->get('str_Reading', 'nt365');
		
		$this->flg_use_year_date = $pluginParams->get('flg_use_year_date', 0);
		$this->arr_verse_start_date = $pluginParams->get('str_verse_start_date');
		$this->arr_reading_start_date = $pluginParams->get('str_reading_start_date');
				
		$this->str_verse_of_day_image = JRoute::_(JUri::base().$pluginParams->get('verse_of_day_image', 'media/com_zefaniabible/images/bible_100.jpg'));
		$this->str_reading_plan_image = JRoute::_(JUri::base().$pluginParams->get('reading_plan_image','media/com_zefaniabible/images/bible_100.jpg'));	
			
		$this->verse_menuitem = $pluginParams->get('verse_menuitem', 0);
		$this->reading_menuitem = $pluginParams->get('reading_menuitem', 0);
	
		$this->str_verse_prepend = $pluginParams->get('str_verse_prepend');
		$this->str_reading_prepend = $pluginParams->get('str_reading_prepend');
		
		// Correct value if value is under the minimum
		if ($this->interval < 60)
		{
			$this->interval = 60;
		}
		
		$this->str_social_verse		= $this->fnc_get_last_publish_date('COM_ZEFANIABIBLE_VERSE');	
		$this->str_social_reading	= $this->fnc_get_last_publish_date('COM_ZEFANIABIBLE_READING');	
		
		// time zone offset.
		$config = JFactory::getConfig();
		$JDate = JFactory::getDate('now', new DateTimeZone($config->get('offset')));
		$this->str_today = $JDate->format('Y-m-d', true);
		$str_today = $JDate->format('Y-m-d', true);
		$this->str_today = new DateTime($this->str_today); 
		if(($this->flg_verse_publish)and($this->str_social_verse != $str_today))
		{
			$this->fnc_Update_Dates('COM_ZEFANIABIBLE_VERSE', 4, $this->str_social_verse, $str_today);				
			$this->str_verse_start_date = new DateTime($this->arr_verse_start_date);		
			$this->int_verse_day_diff = round(abs($this->str_today->format('U') - $this->str_verse_start_date->format('U')) / (60*60*24));	

			$this->int_max_verses = $this->fnc_get_max_verses();
			$this->int_verse_remainder = $this->int_verse_day_diff % $this->int_max_verses;
			if(($this->int_verse_remainder == 0)or($this->int_verse_remainder ==''))
			{
				$this->int_verse_remainder = $this->int_max_verses;
			}
			if($this->flg_use_year_date)
			{
				$this->int_verse_remainder = (date('z')+1);
			}
			$this->fnc_Get_Verse_Of_The_Day_Info($this->int_verse_remainder);
			$this->fnc_Make_Bible_Verse();

			$item->verse_title 	= $this->str_verse_title;
			$item->verse_link 	= $this->str_verse_link;
			$item->verse_image 	= $this->str_verse_of_day_image;
			$item->verse_text 	= $this->str_verse_body;
			$item->type				= 'verse';			
			$native_object = json_encode($item);
			$this->postStatusMessage('verse-'.$this->id.$str_today, $str_today, $this->str_verse_title, self::TYPE_VERSEOFDAY, $this->str_verse_link, $this->str_verse_of_day_image, $native_object);				
		}
		if(($this->flg_plan_publish)and($this->str_social_reading != $str_today))
		{
			$this->fnc_Update_Dates('COM_ZEFANIABIBLE_READING', 5,$this->str_social_reading, $str_today);				
			$this->str_reading_start_date = new DateTime($this->arr_reading_start_date);
			$this->int_reading_day_diff = round(abs($this->str_today->format('U') - $this->str_reading_start_date->format('U')) / (60*60*24));	
			$this->int_max_reading_days = $this->fnc_Find_Max_Reading_Days();
			$this->int_reading_remainder = $this->int_reading_day_diff % $this->int_max_reading_days;
			if(($this->int_reading_remainder == 0)or($this->int_reading_remainder == ''))
			{
				$this->int_reading_remainder = $this->int_max_reading_days;
			}
			$this->arr_reading = $this->fnc_get_todays_reading();
			$this->fnc_Build_Bible_reading();
			$this->fnc_make_reading_plan();
			$item->reading_title 	= $this->str_reading_title;
			$item->reading_link 	= $this->str_reading_link;
			$item->reading_image 	= $this->str_reading_plan_image;
			$item->reading_text 	= $this->str_reading_body;
			$item->type				= 'reading';
			$native_object = json_encode($item);

			$this->postStatusMessage('biblereading-'.$this->id.$str_today, $str_today, $this->str_reading_title, self::TYPE_BIBLEREADING, $this->str_reading_link, $this->str_reading_plan_image, $native_object);
		}		
	}
	/**
	 * getExtendedData
	 *
	 * @param   string  $id              Param.
	 * @param   string  $typeinfo        Param.
	 * @param   string  &$native_object  Param.
	 *
	 * @return	array
	 */
	public function getExtendedData($id, $typeinfo, &$native_object)
	{	
		$item = json_decode($native_object);
		if($item->type == 'verse')
		{			
			$title 	= $item->verse_title;
			$link 	= $item->verse_link;
			$image 	= $item->verse_image;
			$text 	= $this->str_verse_prepend.' '. $item->verse_title;
			$full_text = trim(mb_substr($item->verse_text,0,140));
		}
		else
		{
			$title 	= $item->reading_title;
			$link 	= $item->reading_link;
			$image 	= $item->reading_image;
			$text 	= $this->str_reading_prepend.' '.$item->reading_title;
			$full_text = trim(mb_substr($item->reading_text,0,140));
		}
		$hashtags .= $this->getHashtags($title, 1);
		$data = array(
						'title' => $title,
						'text' => $text,
						'hashtags' => '',

						// Already done when msg is inserted in queue
						'url' => '',

						// Already done when msg is inserted in queue
						'image_url' => '',

						'fulltext' => $full_text,
						'catids' => '',
						'cat_names' => '',
						'author' => 'zefaniaBible',
						'language' => '',
						'access' => '',
						'is_valid' => true
		);
		return $data;
	}
	private function fnc_make_reading_plan()
	{
		if((count($this->arr_reading) >=1)and(count($this->arr_reading_data)>=1))
		{
			foreach($this->arr_reading_data as $reading)
			{
				$this->str_reading_body = $this->str_reading_body.' '.$reading->verse;
			}
		}
	}
	private function fnc_Build_Bible_reading()
	{
		try
		{
			if(count($this->arr_reading) >=1)
			{
				$db = JFactory::getDBO();
				$query  = $db->getQuery(true);
				$cnt_readings = count($this->arr_reading);	
				$x = 0;
				foreach($this->arr_reading as $reading)
				{
					$this->id = $reading->book_id;
					$int_book_id = $reading->book_id;
					$int_begin_chapter =  $reading->begin_chapter;
					$int_begin_verse =  $reading->begin_verse;
					$int_end_chapter =  $reading->end_chapter;
					$int_end_verse =  $reading->end_verse;
					$this->str_reading_link = substr_replace(JURI::base(),'',-1).JRoute::_("index.php?option=com_zefaniabible&view=reading&a=".$this->str_Reading."&b=".$this->str_Bible."&c=".$this->int_reading_remainder.'&Itemid='.$this->reading_menuitem);
					$this->str_reading_title = $this->str_reading_title. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_id).' '.$int_begin_chapter;				
					if($int_begin_verse)
					{
						$this->str_reading_title = $this->str_reading_title.':'.$int_begin_verse.'-'.$int_end_chapter.'-'.$int_end_verse;
					}
					if($int_end_chapter != $int_begin_chapter)
					{
						$this->str_reading_title = $this->str_reading_title.'-'.$int_end_chapter;
					}
					if(($cnt_readings > 1)and($x < $cnt_readings-1))
					{
						$this->str_reading_title .= ', ';
						
					}						
					$x++;
				}
				$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse');
				$query->from('`#__zefaniabible_bible_text` AS a');
				$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
				$query->where("b.alias='".$this->str_Bible."'");
				$query->where("a.book_id=".$int_book_id);
				$query->where("a.chapter_id>=".$int_begin_chapter);			
				$query->where("a.chapter_id<=".$int_end_chapter);	
				if(($int_begin_verse != 0)and($int_begin_verse != ''))
				{
					$query->where("a.verse_id>=".$int_begin_verse);	
					$query->where("a.verse_id<=".$int_end_verse);	
				}
				$query->order('a.book_id ASC, a.chapter_id ASC, a.verse_id ASC');	
							
				$db->setQuery($query);
				$this->arr_reading_data = $db->loadObjectList(); 
			}
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
	}	
	protected function fnc_get_todays_reading()
	{
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);			
			$query->select('a.id, a.book_id, a.begin_chapter, a.begin_verse, a.end_chapter, a.end_verse');
			$query->from('`#__zefaniabible_zefaniareadingdetails` AS a');
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS b ON a.plan = b.id');
			$query->where("b.alias='".$this->str_Reading."'");
			$query->where("a.day_number = ".$this->int_reading_remainder);
				
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}		
	private function fnc_Find_Max_Reading_Days()
	{
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('Max(b.day_number)');
			$query->from('`#__zefaniabible_zefaniareadingdetails` AS b');
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS c ON b.plan = c.id');
			$query->where("c.alias='".$this->str_Reading."'");
			$db->setQuery($query);
			$int_max_days = $db->loadResult();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}	
		return 	$int_max_days;
	}		
	protected function fnc_Make_Bible_Verse()
	{
		try
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('a.book_id, a.chapter_id, a.verse_id, a.verse');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$query->where("a.book_id=".$this->book_name);
			$query->where("a.chapter_id=".$this->chapter_number);
			$query->where("b.alias='".$this->str_Bible."'");
			if($this->end_verse == 0)
			{
				$query->where("a.verse_id=".$this->begin_verse);
			}
			else
			{
				$query->where("a.verse_id>=".$this->begin_verse);
				$query->where("a.verse_id<=".$this->end_verse);
			}
			$db->setQuery($query);		
			
			$data = $db->loadObjectList(); 
			$this->str_verse_title = 	JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->book_name)." ". $this->chapter_number.":".$this->begin_verse;
			if($this->end_verse)
			{
				$this->str_verse_title = $this->str_verse_title .'-'.$this->end_verse; 
			}
			foreach($data as $datum)
			{
				$this->str_verse_body = $this->str_verse_body .$datum->verse;
			}
			$this->str_verse_link = substr_replace(JURI::base(),'',-1).JRoute::_('index.php?option=com_zefaniabible&view=verserss&a='.$this->str_Bible.'&b=0&c='.$this->int_verse_remainder.'&Itemid='.$this->verse_menuitem.'&ord='.date("mdy"));
		}
		catch (JException $e)
		{
			$this->setError($e);
		}				
	}
	protected function fnc_get_max_verses()
	{
		try 
		{
			$db = JFactory::getDBO();
			$query = "SELECT COUNT(a.ordering) FROM `#__zefaniabible_zefaniaverseofday` AS a WHERE publish=1" ;		
			$db->setQuery($query);
			$result = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $result;
	}
	protected function fnc_Get_Verse_Of_The_Day_Info($id)
	{
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('id, book_name, chapter_number, begin_verse, end_verse');
			$query->from('`#__zefaniabible_zefaniaverseofday`');
			$query->where("publish=1");
			$query->where("ordering=".$id);			
			$db->setQuery($query, 0, 1);		
			
			$arr_rows = $db->loadObjectList();
			foreach($arr_rows as $arr_row)
			{
				$this->id = $arr_row->id;
				$this->book_name = $arr_row->book_name;
				$this->chapter_number = $arr_row->chapter_number;
				$this->begin_verse = $arr_row->begin_verse;
				$this->end_verse = $arr_row->end_verse;
			}			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
	}	
	private function fnc_get_last_publish_date($str_title)
	{
		$str_date = '';
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('last_send_date');
			$query->from('`#__zefaniabible_zefaniapublish`');
			$query->where("title='".$str_title."'");	
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		foreach( $data as $datum)
		{
				$str_date = $datum->last_send_date;
		}
		return $str_date;	
	}		
	private function fnc_Update_Dates($str_title, $int_id, $last_date, $str_today)
	{
		try
		{
			$db = JFactory::getDBO();			
			if($last_date == '')
			{
				$arr_row->title = 	$str_title;
				$arr_row->last_send_date 	= $str_today;						
				$db->insertObject("#__zefaniabible_zefaniapublish", $arr_row, $int_id);
			}
			else
			{
				$arr_row->id = 	$int_id;
				$arr_row->title = 	$str_title;
				$arr_row->last_send_date 	= $str_today;				
				$db->updateObject("#__zefaniabible_zefaniapublish", $arr_row, 'id');
			}
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
	}
}