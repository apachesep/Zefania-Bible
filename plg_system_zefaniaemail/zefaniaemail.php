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
if (!JComponentHelper::getComponent('com_zefaniabible', true)->enabled)
{
	JError::raiseWarning('5', 'ZefaniaBible - Email Plugin - ZefaniaBible component is not installed or not enabled.');
	return;
}
/**
 * System plugin to highlight terms.
 *
 * @package     Joomla.Plugin
 * @since       2.5
 */
class plgSystemZefaniaEmail extends JPlugin
{	
	public function  onAfterRender()
	{
		// don't run anythinig below for admin section
		if((strrpos(JURI::base(),'administrator',0) > 0)or(strrpos(JURI::base(),'administrator',0) !=''))
		{
			return;
		}
		
		require_once('components/com_zefaniabible/models/default.php');
		require_once('components/com_zefaniabible/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		$document	= JFactory::getDocument();
		$docType = $document->getType();
		$config = JFactory::getConfig();
		
		// stop processing if you are not html
		if($docType != 'html'){ return;}
			
		// load plugin langauges first english then native
		$jlang = JFactory::getLanguage();
		$jlang->load('plg_system_zefaniaemail', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('plg_system_zefaniaemail', JPATH_ADMINISTRATOR, null, true); 

		$item = new stdClass();	
		$item->arr_english_book_names 			= $mdl_common->fnc_load_languages();
		$item->str_from_email 					= $config->get('mailfrom');
    	$item->str_from_email_name				= $config->get('fromname'); 
		$item->str_verse_start_date 			= $this->params->get('verse_of_day_start_date', '2012-01-01');
		$item->str_image_verse_of_day			= $this->params->get('verse_of_day_image');
		$item->str_image_reading_plan 			= $this->params->get('reading_plan_image');
		$item->int_request_sent_hour 			= $this->params->get('int_hour');
		$item->str_image_verse_of_day_absolute 	= JRoute::_(JUri::base().$item->str_image_verse_of_day);
		$item->str_image_reading_plan_absolute 	= JRoute::_(JUri::base().$item->str_image_reading_plan);
		$item->arr_last_publish_dates			= $mdl_default->fnc_get_last_publish_date();		// get all dates at once to reduce extra DB calls
		$item->str_verse_send_date = "";
		$item->str_reading_send_date = "";
		$item->int_current_hour = 0;
		foreach($item->arr_last_publish_dates as $obj_publish_dates)
		{
			if(strpos($obj_publish_dates->title, "COM_ZEFANIABIBLE_READING_PLAN_EMAIL") > 0)
			{
				$item->str_reading_send_date = $obj_publish_dates->last_send_date;
			}
			if(strpos($obj_publish_dates->title, "COM_ZEFANIABIBLE_VERSE_OF_DAY_EMAIL") > 0)
			{
				$item->str_verse_send_date = $obj_publish_dates->last_send_date;
			}
		}
		// time zone offset.
		$JDate = JFactory::getDate('now', new DateTimeZone($config->get('offset')));
		$item->str_today = $JDate->format('Y-m-d', true);
		$item->arr_today = new DateTime($item->str_today);
		$item->int_current_hour = $JDate->format('G', true);
		$link = '<a href="'.JRoute::_(JUri::base().'index.php?view=unsubscribe&option=com_zefaniabible').'" target="blank">'.JText::_('PLG_ZEFANIABIBLE_READING_UNSUBSCRIBE_WORD').'</a>';
		$item->str_unsubscribe_message = '<br><div style="border-top-color: #BFC3C6;color:#999;border-top: 1px dotted;">'.JText::_('PLG_ZEFANIABIBLE_READING_UNSUBSCRIBE_MESSAGE')." ".$link.'.</div>';
		if((($item->str_reading_send_date != $item->str_today)or($item->str_verse_send_date != $item->str_today))and($item->int_current_hour >= $item->int_request_sent_hour))
		{
			$item->arr_subscribers 	= $mdl_default->fnc_get_subsribers();
			foreach($item->arr_subscribers as $arr_subscriber)
			{
				$item->str_message = "";
				$item->arr_reading = "";
				$item->str_plan_id = "";		
				$item->verse = "";
				if(($arr_subscriber->send_reading_plan_email)and($item->str_reading_send_date != $item->str_today))
				{
					$mdl_default->fnc_Update_Dates('COM_ZEFANIABIBLE_READING_PLAN_EMAIL', 2);
					$this->str_reading_start_date 			=  	new DateTime($arr_subscriber->reading_start_date);
					$item->int_max_reading_days 			= 	$mdl_default->_buildQuery_max_reading_days($arr_subscriber->plan_alias);
					$item->int_reading_remainder			= 	$mdl_common->fnc_calcualte_day_diff($arr_subscriber->reading_start_date, $item->int_max_reading_days);		
					$item->str_plan_id						= 	$arr_subscriber->plan;				
					$item->arr_reading						=	$mdl_default->_buildQuery_reading_plan($arr_subscriber->plan_alias,$item->int_reading_remainder);
					$item->arr_plan							= 	$mdl_default->_buildQuery_current_reading($item->arr_reading, $arr_subscriber->bible_alias);

					$item->str_message						= 	$this->fnc_Build_Bible_reading($item);
					$item->str_subject 						= 	$arr_subscriber->plan_name." - ". $arr_subscriber->bible_name." - ".JText::_('PLG_ZEFANIABIBLE_READING_DAY')." ".$item->int_reading_remainder;
					if($item->str_message)
					{
						$this->fnc_Send_SignUp_Email($arr_subscriber->user_name,$arr_subscriber->email,$item->str_message, $item->str_subject, $item->str_from_email,$item->str_from_email_name);
					}
				}
				else if(($arr_subscriber->send_verse_of_day_email)and($item->str_verse_send_date != $item->str_today))
				{		
					$mdl_default->fnc_Update_Dates('COM_ZEFANIABIBLE_VERSE_OF_DAY_EMAIL', 1);
					$item->int_max_days						=  	$mdl_default->_buildQuery_max_verse_of_day_verse();
					$item->int_day_number					= 	$mdl_common->fnc_calcualte_day_diff($item->str_verse_start_date, $item->int_max_days);	
					$item->arr_verse_info					= 	$mdl_default->_buildQuery_get_verse_of_the_day_info($item->int_day_number);
					$item->arr_verse_of_day					=	$mdl_default->_buildQuery_get_verse_of_the_day($item->arr_verse_info, $arr_subscriber->bible_alias);
					foreach($item->arr_verse_info as $arr_verse_info)
					{
						$item->str_verse_name 				= 	JText::_('PLG_ZEFANIABIBLE_BIBLE_EMAIL_SUBJECT')." - ".$arr_subscriber->bible_name." - ".$mdl_common->fnc_make_scripture_title($arr_verse_info->book_name, $arr_verse_info->chapter_number, $arr_verse_info->begin_verse, 0, $arr_verse_info->end_verse);
					}
					$item->verse = $this->fnc_Make_Bible_Verse($item);
					$this->fnc_Send_SignUp_Email($arr_subscriber->user_name,$arr_subscriber->email,$item->verse, $item->str_verse_name, $item->str_from_email,$item->str_from_email_name);
				}
			}
		}
	}
	private function fnc_Build_Bible_reading($item)
	{
		$str_message = '';
		$x=0;
		try
		{
			$book = 0;
			$chap = 0;
			$y = 1;
			if($item->str_image_reading_plan)
			{
				$str_message .=  '<table><img src="'.$item->str_image_reading_plan_absolute.'" border="0" /></table>'.PHP_EOL;
			}
			foreach($item->arr_plan as $reading)
			{
				foreach($reading as $plan)
				{
					if (($plan->book_id > $book)or($plan->chapter_id > $chap))
					{
						if($y > 1)
						{
							$str_message .=  '</table>'.PHP_EOL;
						}
						$book = $plan->book_id;
						$chap = $plan->chapter_id;
						$str_message .= '<table><tr><td style="font-weight:bold">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ";
						$str_message .=  mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id."</td></tr></table>".PHP_EOL;
						$str_message .=  "<table>".PHP_EOL;		
					}
					if ($y % 2)
					{
						$str_message .= '<tr>'.PHP_EOL;
					}
					else
					{
						$str_message .= '<tr style="background-color:#CCC;border:none;">'.PHP_EOL;
					}
					$str_message .=  '<td style="float:left;margin-right:3px;font-size:10px;color:#FF0000;">'.$plan->verse_id."</td>".PHP_EOL;
					$str_message .=  "<td style='float:left;width:95%;'>".$plan->verse."</td></tr>".PHP_EOL;				
					$y++;
				}
				$str_message .= '</table>'.PHP_EOL;
			}
			$str_message .=  $item->str_unsubscribe_message;			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
			
		return $str_message; 
	}
	
	
	protected function fnc_Make_Bible_Verse($item)
	{
		$str_verse ='';
		if($item->str_image_verse_of_day)
		{
			$str_verse .=  '<table><img src="'.$item->str_image_verse_of_day_absolute.'" border="0" /></table>';			
		}
		foreach($item->arr_verse_info as $arr_verse_info)
		{
			if($arr_verse_info->end_verse == 0)
			{
				$str_verse .= '<div style="font-weight:bold">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_verse_info->book_name)." ".$arr_verse_info->chapter_number.":".$arr_verse_info->begin_verse."</div><div>";
				foreach($item->arr_verse_of_day as $verse)
				{
					$str_verse .= '<div>' . $verse->verse.'</div><div style="clear:both"></div></div>';
				}
			}
			else
			{
				$str_verse .= '<div style="font-weight:bold">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_verse_info->book_name)." ". $arr_verse_info->chapter_number.":".$arr_verse_info->begin_verse."-".$arr_verse_info->end_verse."</div>";
				foreach($item->arr_verse_of_day as $verse)
				{
					$str_verse .= '<div><div style="float:left;margin-right:3px;font-size:10px;color:#FF0000;">'.$verse->verse_id .'</div><div style="float:left;width:95%;">'.$verse->verse.'</div></div><div style="clear:both"></div>';
				}				
			}
		}
		$str_verse .=  $item->str_unsubscribe_message;
		return $str_verse;
	}
	private function fnc_Send_SignUp_Email($str_to_name,$str_to_email,$str_message, $str_subject,$str_from_email,$str_from_email_name)
	{ 
		$mailer = JFactory::getMailer();
		$str_sender = array($str_from_email,$str_from_email_name);
		$mailer->setSender($str_sender);
		$mailer->addRecipient($str_to_email);
		$mailer->setSubject($str_subject);
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($str_message);
		$send = $mailer->Send();			
	}	
}
