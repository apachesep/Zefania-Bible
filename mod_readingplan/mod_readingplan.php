<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabiblebooknames
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
defined('_JEXEC') or die('Restricted access');
if (!JComponentHelper::getComponent('com_zefaniabible', true)->enabled)
{
	JError::raiseWarning('5', 'ZefaniaBible - Reading Plan Module - ZefaniaBible component is not installed or not enabled.');
	return;
}
JHTML::stylesheet('verse.css', 'modules/mod_verseoftheday/css/'); 
$cls_bible_reading_plan = new zefReadingPlan($params);
class zefReadingPlan
{
	private $str_reading_plan;
	private $str_reading_start_date;
	private $str_today;
	private $int_day_diff;
	private $int_verse_remainder;
	private $int_max_verses;
	private $int_plan_id;
	private $arr_reading_plan;
	private $cnt_reading_elements;
	private $str_Bible_alias;
	public $str_menuItem;
	private $str_custom_code;
	private $flg_custom_code;
	public function __construct($params)
	{
		/*
			a = plan
			b = bible
			c = day
		*/
		$user 	= JFactory::getUser();
		$this->str_reading_plan 		= $params->get('reading_plan', $this->fnc_first_plan_record());
		$this->str_Bible_alias 			= $params->get('bibleAlias', $this->fnc_first_bible_record());
		$this->str_menuItem 			= $params->get('rp_mo_menuitem', 0);
		$this->str_reading_start_date 	= new DateTime($params->get('reading_start_date', '1-1-2012'));
		$this->flg_custom_code 			= $params->get('flg_use_custom_code',0);		
		$this->str_custom_html 			= $params->get('str_custom_html');
		$this->flg_import_user_data 	= $params->get('flg_import_user_data', '0');

		if(($user->id > 0)and($this->flg_import_user_data))
		{
			$arr_user_data = $this->fnc_Get_User_Data($user->id);
			foreach($arr_user_data as $obj_user_data)
			{
				$this->str_reading_start_date = new DateTime($obj_user_data->reading_start_date);
				$this->str_Bible_alias = $obj_user_data->bible_alias;
				$this->str_reading_plan = $obj_user_data->plan_alias;
			}
		}
		
		$jlang = JFactory::getLanguage();
		$jlang->load('mod_readingplan', JPATH_BASE."/modules/mod_readingplan", 'en-GB', true);
		$jlang->load('mod_readingplan', JPATH_BASE."/modules/mod_readingplan", null, true);
		 
		// time zone offset.
		$config = JFactory::getConfig();
		$JDate = JFactory::getDate('now', new DateTimeZone($config->get('offset')));
		$this->str_today = $JDate->format('Y-m-d', true);
		$this->str_today = new DateTime($this->str_today);
		$this->int_day_diff = round(abs($this->str_today->format('U') - $this->str_reading_start_date->format('U')) / (60*60*24));	
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);

		$this->get_max_verses();
		$this->int_verse_remainder = $this->int_day_diff % $this->int_max_verses;
		$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
		
		if($this->int_verse_remainder == 0)
		{
			$this->int_verse_remainder = $this->int_max_verses;
		}
		$this->get_reading_plan();
		$this->cnt_reading_elements = count($this->arr_reading_plan);
		$x = 0;
		$str_link = JRoute::_("index.php?option=com_zefaniabible&view=reading&Itemid=".$this->str_menuItem."&plan=".$this->str_reading_plan."&bible=".$this->str_Bible_alias."&day=".$this->int_verse_remainder);
		$str_verse_output_link = '<a rel="nofollow" title="'.JText::_('MOD_ZEFANIABIBLE_READING_PLAN_CLICK_TITLE').'" href="'.$str_link.'" target="_self">';
		$str_scripture = '';
		foreach ($this->arr_reading_plan as $arr_reading)
		{
			if(($arr_reading->begin_verse == 0)and($arr_reading->end_verse == 0))
			{
				if($arr_reading->begin_chapter == $arr_reading->end_chapter)
				{
					$str_scripture .= JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter;					
				}
				else
				{
					$str_scripture .= JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter."-".$arr_reading->end_chapter;
				}
			}
			else
			{
				$str_scripture .=  JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter.":".$arr_reading->begin_verse."-".$arr_reading->end_chapter.":".$arr_reading->end_verse;
			}
			if(($this->cnt_reading_elements > 1)and($x <  $this->cnt_reading_elements))
			{
				$str_scripture .=  "<br>";
			}
			$x++;
		}
		if($this->flg_custom_code == 0)
		{				
			$str_verse_output = $str_verse_output_link.$str_scripture ."</a>";
		}
		else
		{
			$str_verse_output = str_replace('{link}',$str_verse_output_link,$this->str_custom_html);
			$str_verse_output = str_replace('{/link}','</a>',$str_verse_output);
			$str_verse_output = str_replace('{scripture}',trim($str_scripture),$str_verse_output);
		}					
		echo $str_verse_output;		
	}
	protected function get_reading_plan()
	{
		try 
		{
			$db = JFactory::getDBO();
			$query = "SELECT a.* FROM `#__zefaniabible_zefaniareadingdetails` AS a WHERE a.plan='".$this->int_plan_id."' AND a.day_number = ".$this->int_verse_remainder." ORDER BY a.plan, a.book_id" ;			
			$db->setQuery($query);
			$this->arr_reading_plan = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
	}
	protected function get_max_verses()
	{
		try 
		{
			$db = JFactory::getDBO();
			$query_plan = "SELECT * FROM `#__zefaniabible_zefaniareading` AS c WHERE c.alias='".$this->str_reading_plan."'";	
			$db->setQuery($query_plan);
			$this->int_plan_id = $db->loadResult();	
			
			$query_max = "SELECT Max(b.day_number) FROM `#__zefaniabible_zefaniareadingdetails` AS b WHERE b.plan='".$this->int_plan_id."'";	
			$db->setQuery($query_max);
			$this->int_max_verses = $db->loadResult();

		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
	}
	protected function fnc_first_bible_record()
	{
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('alias');
			$query->from('`#__zefaniabible_bible_names`');	
			$query->where("publish = 1");
			$query->order('id');		
			$db->setQuery($query,0, 1);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}	
	protected function fnc_first_plan_record()
	{
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('alias');
			$query->from('`#__zefaniabible_zefaniareading`');	
			$query->where("publish = 1");
			$query->order('id');		
			$db->setQuery($query,0, 1);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	protected function fnc_Get_User_Data($int_id)
	{
		try 
		{
			$db = JFactory::getDBO();
			$query  = $db->getQuery(true);
			$query->select('user.reading_start_date,bible.alias as bible_alias,plan.alias as plan_alias');
			$query->from('`#__zefaniabible_zefaniauser` AS user');	
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS plan ON user.plan = plan.id');
			$query->innerJoin('`#__zefaniabible_bible_names` AS bible ON user.bible_version = bible.id');			
			$query->where("user.user_id='".$int_id."'");
			$db->setQuery($query,0, 1);
			$data = $db->loadObjectList();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	
}
?>

