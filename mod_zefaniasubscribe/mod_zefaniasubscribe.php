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
JHTML::stylesheet('mod_zefaniasubscribe.css', 'modules/mod_zefaniasubscribe/css/'); 
$cls_zefania_subscribe = new zefSubscibe($params);
class zefSubscibe
{
	public function __construct($params)
	{
		$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));	
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);
		
		$int_bible_id =	JRequest::getInt('mod_zef_sub_bible_id');	
		$int_reading_plan_id =	JRequest::getInt('mod_zef_sub_reading_plan_id');
		$flg_send_reading =	JRequest::getBool('mod_zef_sub_flg_send_reading' , 0);
		$flg_send_verse =	JRequest::getBool('mod_zef_sub_flg_send_verse' , 0);
		$str_start_date =	JRequest::getCmd('mod_zef_sub_calendar_date');
		$str_user_name = JRequest::getString('mod_zef_subs_name');
		$str_user_email = JRequest::getString('mod_zef_subs_email');
		if(!$int_bible_id)
		{
			$this->fnc_contruct_form();	
		}
		else
		{
			if(($str_user_email != "")and($str_user_name != '')and(($flg_send_reading)or($flg_send_verse)))
			{
				if($this->fnc_validate_email($str_user_email))
				{
					$arr_bible = $this->fnc_get_bibles($int_bible_id);
					$arr_reading_plan = $this->fnc_get_reading_plans($int_reading_plan_id);		
								
					$this->fnc_insert_data($int_bible_id,$int_reading_plan_id,$flg_send_reading,$flg_send_verse,$str_start_date,$str_user_name,$str_user_email);
					$this->fnc_send_signup_email($arr_bible,$arr_reading_plan,$flg_send_reading,$flg_send_verse,$str_start_date,$str_user_name,$str_user_email);
				}
				else
				{
					JError::raiseWarning('',JText::_('ZEFANIABIBLE_EMAIL_NOT_VALID'));
				}
			}
			else
			{
				JError::raiseWarning('',JText::_('MOD_ZEFANIABIBLE_SUBSCRIBE_NOT_VALID_NAME_EMAIL'));
			}
		}
		
	}
	private function fnc_send_signup_email($arr_bible,$arr_reading_plan,$flg_send_reading,$flg_send_verse,$str_start_date,$str_user_name,$str_user_email)
	{ 
		$config = JFactory::getConfig();
		$str_from_email 		= $config->get( 'config.mailfrom' );
    	$str_from_email_name	= $config->get( 'config.fromname' );	
		foreach($arr_bible as $obj_bible)
		{
			$str_bible_name = $obj_bible->bible_name;
		}
		foreach($arr_reading_plan as $obj_reading_plan)
		{
			$str_reading_plan_name = $obj_reading_plan->name;
		}
		$str_message = str_replace('%n',$str_user_name,JText::_('ZEFANIABIBLE_SIGNUP_EMAIL_BODY'))."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_START_READING_DATE').": ".$str_start_date."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_VERSION').": ".$str_bible_name."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_READING_PLAN').": " . $str_reading_plan_name."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_SEND_READING_EMAIL').": ";
		if($flg_send_reading) 
		{ 
			$str_message = $str_message . JText::_('JYES'); 
		}
		else 
		{ 
			$str_message = $str_message . JText::_('JNO'); 
		} 
		$str_message = $str_message . "<br>"; 
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_SEND_VERSE_EMAIL').": ";
		if($flg_send_verse) 
		{ 
			$str_message = $str_message . JText::_('JYES'); 
		} 
		else 
		{ 
			$str_message = $str_message . JText::_('JNO'); 
		} 
		$str_message = $str_message . "<br>";	
		$str_message = $str_message . JText::_('ZEFANIABIBLE_IP_ADDRESS').": ". $_SERVER['REMOTE_ADDR']."<br>";	

		$mailer =& JFactory::getMailer();
		$str_sender = array($str_from_email,$str_from_email_name);
		$mailer->setSender($str_sender);
		$mailer->addRecipient($str_user_email);
		//$mailer->addBCC($this->str_admin_email);
		$mailer->setSubject(JText::_('ZEFANIABIBLE_SIGNUP_EMAIL_SUBJECT'));
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($str_message);
		$send =& $mailer->Send();			
	}	
	function fnc_validate_email($x)
	{
    	if(preg_match('/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+$/',$x))return true;
	    return false;
	}  
	protected function fnc_insert_data($int_bible_id,$int_reading_plan_id,$flg_send_reading,$flg_send_verse,$str_start_date,$str_user_name,$str_user_email)
	{
		$db = JFactory::getDBO();
		$arr_row->user_name = $str_user_name;
		$arr_row->plan = $int_reading_plan_id;
		$arr_row->bible_version = $int_bible_id;
		$arr_row->user_id = '';
		$arr_row->email = $str_user_email;
		$arr_row->send_reading_plan_email = $flg_send_reading;
		$arr_row->send_verse_of_day_email = $flg_send_verse;
		$arr_row->reading_start_date = $str_start_date;
		
		$db->insertObject("#__zefaniabible_zefaniauser", $arr_row);
        $app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('ZEFANIABIBLE_CATCHA_SUBMITED'));			
	}
	protected function fnc_contruct_form()
	{	
		$arr_bibles = $this->fnc_get_bibles(0);
		$arr_reading_plans = $this->fnc_get_reading_plans(0);
		echo '<form action="'. JFactory::getURI()->toString().'" method="post" id="zefania_subscribe" name="zefania_subscribe">';
		
		echo '<div><div class="zef_bible_label">'.JText::_('ZEFANIABIBLE_USER_NAME_LABEL').'</div>';
		echo '<div><input type="text" name="mod_zef_subs_name" id="mod_zef_subs_name" size="25" maxlength="50"></div></div>';
         
		
		echo '<div><div class="zef_bible_label">'.JText::_('ZEFANIABIBLE_EMAIL_LABEL').'</div>';
        echo '<div><input type="text" name="mod_zef_subs_email" id="mod_zef_subs_email" maxlength="50" size="25"></div></div>';
		echo '<div style="clear:both"></div>';
		
		echo '<div><div class="mod_zefsend_bible_label">'. JText::_('ZEFANIABIBLE_BIBLE_VERSION').'</div>';
		echo '<div class="mod_zefsend_bible"><select name="mod_zef_sub_bible_id" id="zef_mod_send_bible" class="inputbox" >';
		foreach ($arr_bibles as $obj_bible)
		{
			echo '<option value="'.$obj_bible->id.'" >'.$obj_bible->bible_name.'</option>';	
		}
		echo '</select></div></div>';
		echo '<div style="clear:both"></div>';
		
		echo '<div><div class="mod_zefsend_readingplan_label">'. JText::_('ZEFANIABIBLE_READING_PLAN').'</div>';
		echo '<div class="mod_zefsend_readingplan"><select name="mod_zef_sub_reading_plan_id" id="zef_mod_send_readingplan" class="inputbox">';
		foreach ($arr_reading_plans as $obj_reading_plan)
		{
			echo '<option value="'.$obj_reading_plan->id.'" >'.$obj_reading_plan->name.'</option>';	
		}
		echo '</select></div></div>';
		echo '<div style="clear:both"></div>';
		
		echo '<div><div class="mod_zefsend_reading_email_label">'.JText::_('ZEFANIABIBLE_BIBLE_SEND_READING_EMAIL').'</div>';
        echo '<div class="mod_zefsend_reading_email">';
        echo '<select name="mod_zef_sub_flg_send_reading" id="zef_subs_send_reading" class="inputbox">';
        echo ' <option value="0" >'.JText::_('JNO').'</option>';
       	echo ' <option value="1" >'.JText::_('JYES').'</option>';
        echo '</select></div></div>';
		echo '<div style="clear:both"></div>';
         
        echo '<div><div class="mod_zefsend_verse_label">'.JText::_('ZEFANIABIBLE_BIBLE_SEND_VERSE_EMAIL').'</div>';
        echo '<div class="mod_zefsend_verse">';
        echo '<select name="mod_zef_sub_flg_send_verse" id="zef_subs_send_verse" class="inputbox">';
        echo ' <option value="0" >'.JText::_('JNO').'</option>';
       	echo ' <option value="1" >'.JText::_('JYES').'</option>';
		echo '</select></div></div>';
		echo '<div style="clear:both"></div>';
		
		echo '<div><div class="mod_zefsend_calendar_label">'. JText::_('ZEFANIABIBLE_BIBLE_START_READING_DATE').'</div>';
        JHTML::calendar(date('d-m-Y',strtotime('now')),'mod_zef_sub_calendar_date','mod_zefsend_start_date','%Y-%m-%d','');
        echo '<div><input type="text" name="mod_zef_sub_calendar_date" id="mod_zefsend_start_date" maxlength="10" size="10" value="'.date("Y-m-d").'"></div></div>';
		echo '<div style="clear:both"></div>';
		
		echo '<div><input class="button" type="submit" value="submit" name="'.JText::_('JSAVE').'"/></div></form>';		
	}
	protected function fnc_get_bibles($id)
	{
		$db		= JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select("a.id, a.bible_name, a.alias FROM `#__zefaniabible_bible_names` AS a");
		$query->where('a.publish =1');
		if($id)
		{
			$query->where('a.id ='.$id);
		}
		$query->order('a.bible_name ASC');
		$db->setQuery($query);
		$data = $db->loadObjectList();
		return $data;
	}
	protected function fnc_get_reading_plans($id)
	{
		$db		= JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select("b.id, b.name, b.alias FROM `#__zefaniabible_zefaniareading` AS b");
		$query->where('b.publish =1');
		if($id)
		{
			$query->where('b.id ='.$id);
		}		
		$query->order('b.name');
		$db->setQuery($query);
		$data = $db->loadObjectList();
		return $data;		
	}
}
?>

