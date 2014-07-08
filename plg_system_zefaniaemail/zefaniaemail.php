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


defined('JPATH_BASE') or die;

jimport('joomla.application.component.helper');

/**
 * System plugin to highlight terms.
 *
 * @package     Joomla.Plugin
 * @since       2.5
 */
class plgSystemZefaniaEmail extends JPlugin
{

	private $str_from_email;
	private $str_from_email_name;
	private $arr_reading_plan_publish;
	private $str_verse_send_date;
	private $str_reading_send_date;
	private $str_facebook_send_date;
	private $arr_reading_subscribers;
	private $arr_verse_subscribers;
	private $arr_verse_start_date;
	private $int_verse_day_diff;
	private $str_verse_start_date;
	private $arr_verse_info;
	private $int_max_verses;
	private $int_verse_remainder;
	private $str_Bible_Path;
	private $str_Bible_file_location;
	private $str_verse_name;
	private $str_reading_start_date;
	private $str_reading_version;
	private $int_reading_day_diff;
	private $int_reading_remainder;
	private $int_max_reading_days;
	private $int_reading_plan_id;
	private $arr_plan_info;
	private $str_book_title;
	private $str_unsubscribe_message;
	private $str_verse_of_day_image;
	private $str_reading_plan_image;
	private $str_image_verse_of_day;
	private $str_image_reading_plan;
	private $str_today;
	
	public function  onAfterRender()
	{
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
		$jlang->load('zefaniabible', JPATH_COMPONENT, 'en-GB', true);
		$jlang->load('zefaniabible', JPATH_COMPONENT, null, true);
				
		$config = JFactory::getConfig();
		$this->params_zefania_comp 			= JComponentHelper::getParams( 'com_zefaniabible' );
		$this->str_from_email 				= $config->get('mailfrom');
    	$this->str_from_email_name			= $config->get('fromname'); 
		$this->arr_verse_start_date 		= $this->params->get('verse_of_day_start_date', '2012-01-01');
		$this->str_Bible_Path 				= $this->params_zefania_comp->get('xmlBiblesPath', 'media/com_zefaniabible/bibles/');
		$this->str_image_verse_of_day		= $this->params->get('verse_of_day_image');
		$this->str_image_reading_plan 		= $this->params->get('reading_plan_image');
		$this->str_verse_of_day_image 		= JRoute::_(JUri::base().$this->str_image_verse_of_day);
		$this->str_reading_plan_image 		= JRoute::_(JUri::base().$this->str_image_reading_plan);
		$this->str_reading_send_date		= $this->fnc_get_last_publish_date('COM_ZEFANIABIBLE_READING_PLAN_EMAIL');
		$this->str_verse_send_date 			= $this->fnc_get_last_publish_date('COM_ZEFANIABIBLE_VERSE_OF_DAY_EMAIL');
		
		$link = '<a href="'.JRoute::_(JUri::base().'index.php?view=unsubscribe&option=com_zefaniabible').'" target="blank">'.JText::_('PLG_ZEFANIABIBLE_READING_UNSUBSCRIBE_WORD').'</a>';
		$this->str_unsubscribe_message = '<br><div style="border-top-color: #BFC3C6;color:#999;border-top: 1px dotted;">'.JText::_('PLG_ZEFANIABIBLE_READING_UNSUBSCRIBE_MESSAGE')." ".$link.'.</div>';
		
		// time zone offset.
		$config = JFactory::getConfig();
		$JDate = JFactory::getDate('now', new DateTimeZone($config->get('offset')));
		$this->str_today = $JDate->format('Y-m-d', true);
		$this->str_today = new DateTime($this->str_today);
		
		if($this->str_reading_send_date != $this->str_today)
		{
			$this->fnc_Update_Dates('COM_ZEFANIABIBLE_READING_PLAN_EMAIL', 2);
			$this->arr_reading_subscribers = $this->fnc_get_subsribers_reading();

			foreach($this->arr_reading_subscribers as $arr_subscriber)
			{
				$str_message = "";
				$arr_book_info = "";
				$this->arr_plan_info  = '';
				$this->str_reading_start_date =  new DateTime($arr_subscriber->reading_start_date);
				$this->int_reading_day_diff = round(abs($this->str_today->format('U') - $this->str_reading_start_date->format('U')) / (60*60*24));
				$this->int_reading_plan_id =  $arr_subscriber->plan;
				
				$arr_book_info = $this->fnc_Get_Book_Info($arr_subscriber->bible_version);
			
				$this->int_max_reading_days = $this->fnc_Find_Max_Reading_Days($this->int_reading_plan_id);
				$this->int_reading_remainder = $this->int_reading_day_diff % $this->int_max_reading_days;
				
				if($this->int_reading_remainder == 0)
				{
					$this->int_reading_remainder = $this->int_max_reading_days;
				}
				
				foreach($arr_book_info as $arr_info)
				{
					$str_plan_title = $arr_info->bible_name;
					$str_plan_id = $arr_info->id;
				}
				$arr_reading_info = $this->fnc_Find_Plan_Name($this->int_reading_plan_id);
				foreach($arr_reading_info as $str_reading_info)
				{
					$str_reading_name = $str_reading_info->name;
				}
				
				$arr_reading = $this->fnc_get_reading_plan($this->int_reading_plan_id, $this->int_reading_remainder);
				$str_message = $this->fnc_Build_Bible_reading($arr_reading,$arr_book_info,$str_plan_id);
				$str_subject = $str_reading_name." - ". $str_plan_title." - ".JText::_('PLG_ZEFANIABIBLE_READING_DAY')." ".$this->int_reading_remainder;
				if($str_message)
				{
					$this->fnc_Send_SignUp_Email($arr_subscriber->user_name,$arr_subscriber->email,$str_message, $str_subject);
				}
			}
		}
		else if($this->str_verse_send_date != $this->str_today)
		{
			$this->fnc_Update_Dates('COM_ZEFANIABIBLE_VERSE_OF_DAY_EMAIL', 1);
			$this->arr_verse_subscribers = $this->fnc_get_subsribers_verse();
			$this->str_verse_start_date = new DateTime($this->arr_verse_start_date);
			
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) 
			{
				$interval = $this->str_verse_start_date->diff($this->str_today);	
				$this->int_verse_day_diff = $interval->format('%a');
			}
			else
			{
				$this->int_verse_day_diff = round(abs($this->str_today->format('U') - $this->str_verse_start_date->format('U')) / (60*60*24));	
			}
			$this->fnc_Get_Verse_Of_The_Day_Info();
			$this->int_verse_remainder = $this->int_verse_day_diff % $this->int_max_verses;
			if($this->int_verse_remainder == 0)
			{
				$this->int_verse_remainder = $this->int_max_verses;
			}
			$this->fnc_Get_Verse_Of_The_Day_Info();

			foreach($this->arr_verse_subscribers as $arr_subscriber)
			{
				$arr_book_info = "";
				$arr_book_paths = "";
				$this->str_book_title = "";
				$arr_book_info = $this->fnc_Get_Book_Info($arr_subscriber->bible_version);
				if($arr_book_info)
				{	
					$arr_reading_info = $this->fnc_Find_Plan_Name($this->int_reading_plan_id);
					foreach($arr_book_info as $arr_info)
					{
						$this->str_book_title = $arr_info->bible_name;
					}
					$verse =  $this->fnc_Make_Bible_Verse($arr_subscriber->bible_version);

					$this->fnc_Send_SignUp_Email($arr_subscriber->user_name,$arr_subscriber->email,$verse, $this->str_verse_name);
				}
			}		
		}
	}
	private function fnc_Build_Bible_reading($arr_reading,$arr_book_info,$str_plan_id)
	{
		$str_message = '';
		$x=0;
		try
		{
			$db = JFactory::getDBO();
			foreach($arr_reading as $reading)
			{
				$int_book_id = $reading->book_id;
				$int_begin_chapter =  $reading->begin_chapter;
				$int_begin_verse =  $reading->begin_verse;
				$int_end_chapter =  $reading->end_chapter;
				$int_end_verse =  $reading->end_verse;
								
				$query = 'SELECT a.book_id, a.chapter_id, a.verse_id, a.verse FROM `#__zefaniabible_bible_text` AS a ';
				$query = $query. ' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id';
				$query = $query. " WHERE a.book_id=".$int_book_id." AND a.chapter_id>=".$int_begin_chapter." AND a.chapter_id<=".$int_end_chapter;
				if($int_begin_verse != 0)
				{
					$query = $query. " AND a.verse_id>=".$int_begin_verse." AND a.verse_id<=".$int_end_verse;
				}
				$query = $query. " AND b.id='".$str_plan_id."'";
				$query = $query." ORDER BY a.book_id ASC, a.chapter_id ASC, a.verse_id ASC";
				
				$db->setQuery($query);
				$data = $db->loadObjectList(); 
				$arr_data[$x] = $data;
				
				$x++;
			}
			$book = 0;
			$chap = 0;
			$y = 1;
			if($this->str_image_reading_plan)
			{
				$str_message = $str_message. '<table><img src="'.$this->str_reading_plan_image.'" border="0" /></table>';
			}
			foreach($arr_data as $reading)
			{
				foreach($reading as $plan)
				{
					
					if (($plan->book_id > $book)or($plan->chapter_id > $chap))
					{
						if($y > 1)
						{
							$str_message = $str_message. '</table>';
						}
						$book = $plan->book_id;
						$chap = $plan->chapter_id;
						$str_message = $str_message.'<table><tr><td style="font-weight:bold">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ";
						$str_message = $str_message. mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id."</td></tr></table><table>";		
					}
					if ($y % 2)
					{
						$str_message = $str_message.'<tr>';
					}
					else
					{
						$str_message = $str_message.'<tr style="background-color:#CCC;border:none;">';
					}
					$str_message = $str_message. '<td style="float:left;margin-right:3px;font-size:10px;color:#FF0000;">'.$plan->verse_id."</td>";
					$str_message = $str_message. "<td style='float:left;width:95%;'>".$plan->verse."</td></tr>";				
					$y++;
				}
				$str_message = $str_message.'</table>';
			}
			$str_message = $str_message. $this->str_unsubscribe_message;			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
			
		return $str_message; 
	}
	private function fnc_Update_Dates($str_title, $int_id)
	{
		try
		{
			$db = JFactory::getDBO();
			$arr_row->id = 	$int_id;
			$arr_row->title = 	$str_title;
			$arr_row->last_send_date 	= $this->str_today;
			$db->updateObject("#__zefaniabible_zefaniapublish", $arr_row, 'id');
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
	}
	
	protected function fnc_Make_Bible_Verse($int_id)
	{
		$str_verse ='';
		try
		{
			$db = JFactory::getDBO();			
			$query = "SELECT a.book_id, a.chapter_id, a.verse_id, a.verse FROM `#__zefaniabible_bible_text` AS a ".
					" WHERE a.book_id=".$this->arr_verse_info['book_name']." AND a.chapter_id=".$this->arr_verse_info['chapter_number'];
				if($this->arr_verse_info['end_verse'] == 0)
				{
					$query = $query ." AND a.verse_id=".$this->arr_verse_info['begin_verse'];
				}
				else
				{
					$query = $query . " AND a.verse_id>=".$this->arr_verse_info['begin_verse']." AND a.verse_id<=".$this->arr_verse_info['end_verse'];
				}
				$query = $query. " AND a.bible_id=".$int_id;
			$db->setQuery($query);
			$data = $db->loadObjectList(); 
			if($this->str_image_verse_of_day)
			{
				$str_verse = $str_verse . '<table><img src="'.$this->str_verse_of_day_image.'" border="0" /></table>';			
			}
			if($this->arr_verse_info['end_verse'] == 0)
			{
				$this->str_verse_name = JText::_('PLG_ZEFANIABIBLE_BIBLE_EMAIL_SUBJECT')." - ".$this->str_book_title." - ".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->arr_verse_info['book_name'])." ". $this->arr_verse_info['chapter_number'].":".$this->arr_verse_info['begin_verse'];
				$str_verse = $str_verse .'<div style="font-weight:bold">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->arr_verse_info['book_name'])." ".$this->arr_verse_info['chapter_number'].":".$this->arr_verse_info['begin_verse']."</div><div>";
				foreach($data as $datum)
				{
					$str_verse = $str_verse .'<div>' . $datum->verse.'</div><div style="clear:both"></div></div>';
				}
			}
			else
			{
				$this->str_verse_name = JText::_('PLG_ZEFANIABIBLE_BIBLE_EMAIL_SUBJECT')." - ".$this->str_book_title." - ".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->arr_verse_info['book_name'])." ". $this->arr_verse_info['chapter_number'].":".$this->arr_verse_info['begin_verse']."-".$this->arr_verse_info['end_verse'];
				$str_verse = $str_verse .'<div style="font-weight:bold">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->arr_verse_info['book_name'])." ". $this->arr_verse_info['chapter_number'].":".$this->arr_verse_info['begin_verse']."-".$this->arr_verse_info['end_verse']."</div>";
				foreach($data as $datum)
				{
					$str_verse = $str_verse .'<div><div style="float:left;margin-right:3px;font-size:10px;color:#FF0000;">'.$datum->verse_id .'</div><div style="float:left;width:95%;">'.$datum->verse.'</div></div><div style="clear:both"></div>';
				}				
			}			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}				
		$str_verse = $str_verse. $this->str_unsubscribe_message;
		return $str_verse;
	}
	protected function fnc_Get_Verse_Of_The_Day_Info()
	{
		$db = JFactory::getDBO();
		$query 	= "SELECT * FROM #__zefaniabible_zefaniaverseofday ".
				  " WHERE publish=1";
		$db->setQuery($query);
		$arr_rows = $db->loadObjectList();	
		$x = 0;
		foreach($arr_rows as $arr_row)
		{
			if($this->int_verse_remainder == $x)
			{
				$this->arr_verse_info['book_name'] = $arr_row->book_name;
				$this->arr_verse_info['chapter_number'] = $arr_row->chapter_number;
				$this->arr_verse_info['begin_verse'] = $arr_row->begin_verse;
				$this->arr_verse_info['end_verse'] = $arr_row->end_verse;		
			}
			$x++;
		}
		
		$this->int_max_verses = $x;
	}
	private function fnc_Find_Plan_Name($int_id)
	{
		try 
		{	
			$db		= JFactory::getDbo();
			$query =  "SELECT a.* FROM `#__zefaniabible_zefaniareading` AS a WHERE a.id='".$int_id."'";
			$db->setQuery($query);
			$data = $db->loadObjectList();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}	
		return 	$data;		
	}	
	private function fnc_Get_Book_Info($int_id)
	{
		$db		= JFactory::getDbo();
		$query	= "SELECT * FROM `#__zefaniabible_bible_names` AS c WHERE c.id='".$int_id."' and c.publish=1";	
		$db->setQuery($query);
		$data = $db->loadObjectList();
		return 	$data;
	}	
	private function fnc_get_subsribers_reading()
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query =  'SELECT a.* FROM `#__zefaniabible_zefaniauser` AS a WHERE send_reading_plan_email=1 and reading_start_date <= CURDATE()';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}
	private function fnc_get_subsribers_verse()
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query =  'SELECT a.* FROM `#__zefaniabible_zefaniauser` AS a WHERE send_verse_of_day_email=1 and reading_start_date <= CURDATE()';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}
	private function fnc_get_last_publish_date($str_title)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query =  "SELECT a.last_send_date FROM `#__zefaniabible_zefaniapublish` AS a WHERE a.title='".$str_title."' ";
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
	private function fnc_Send_SignUp_Email($str_to_name,$str_to_email,$str_message, $str_subject)
	{ 
		$mailer =& JFactory::getMailer();
		$str_sender = array($this->str_from_email,$this->str_from_email_name);
		$mailer->setSender($str_sender);
		$mailer->addRecipient($str_to_email);
		$mailer->setSubject($str_subject);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($str_message);
		$send =& $mailer->Send();			
	}

	private function fnc_Find_Max_Reading_Days($int_reading_plan_id)
	{
		try 
		{
			$db = JFactory::getDBO();
			$query_max = "SELECT Max(b.day_number) FROM `#__zefaniabible_zefaniareadingdetails` AS b".	
						" INNER JOIN `#__zefaniabible_zefaniareading` AS c ON b.plan = c.id".
						" WHERE c.id='".$int_reading_plan_id."'";
			$db->setQuery($query_max);
			$int_max_days = $db->loadResult();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}	
		return 	$int_max_days;
	}	
	protected function fnc_get_reading_plan($int_id, $int_day)
	{
		try 
		{
			$db = JFactory::getDBO();
			$query = "SELECT a.* FROM `#__zefaniabible_zefaniareadingdetails` AS a WHERE a.plan='".$int_id."' AND a.day_number = ".$int_day." ORDER BY a.plan, a.book_id" ;			
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}	
}
