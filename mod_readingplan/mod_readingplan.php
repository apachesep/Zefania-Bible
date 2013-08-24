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
	
	public function __construct($params)
	{
		/*
			a = plan
			b = bible
			c = day
		*/
		$this->str_reading_plan = $params->get('reading_plan', 'ttb');
		$this->str_Bible_alias = $params->get('bibleAlias', 'kjv');
		$this->str_menuItem = $params->get('rp_mo_menuitem', 0);
		$this->str_reading_start_date = new DateTime($params->get('reading_start_date', '1-1-2012'));		

		$jlang = JFactory::getLanguage();
		$jlang->load('mod_readingplan', JPATH_COMPONENT, 'en-GB', true);
		$jlang->load('mod_readingplan', JPATH_COMPONENT, null, true);
		
		// time zone offset.
		$config =& JFactory::getConfig();
		date_default_timezone_set($config->get('offset'));		
		
		$this->str_today = new DateTime(date('Y-m-d'));
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
		foreach ($this->arr_reading_plan as $arr_reading)
		{
			echo '<a rel="nofollow" title="'.JText::_('MOD_ZEFANIABIBLE_READING_PLAN_CLICK_TITLE').'" href="'.JRoute::_("index.php?option=com_zefaniabible&view=reading&Itemid=".$this->str_menuItem."&a=".$this->str_reading_plan."&b=".$this->str_Bible_alias."&c=".$this->int_verse_remainder).'" target="_self">';
			if(($arr_reading->begin_verse == 0)and($arr_reading->end_verse == 0))
			{
				if($arr_reading->begin_chapter == $arr_reading->end_chapter)
				{
					echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter;					
				}
				else
				{
					echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter."-".$arr_reading->end_chapter;
				}
			}
			else
			{
				echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter.":".$arr_reading->begin_verse."-".$arr_reading->end_chapter.":".$arr_reading->end_verse;
			}
			$x++;
			if(($this->cnt_reading_elements > 1)and($x <  $this->cnt_reading_elements))
			{
				echo "<br>";
			}
			echo "</a>";
			
		}
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
}
?>

