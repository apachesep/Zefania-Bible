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
	JError::raiseWarning('5', 'ZefaniaBible - Subscribe Module - ZefaniaBible component is not installed or not enabled.');
	return;
}

JHTML::stylesheet('mod_zefaniasubscribe.css', 'modules/mod_zefaniasubscribe/css/'); 
$cls_zefania_subscribe = new zefSubscibe($params);
class zefSubscibe
{
	public function __construct($params)
	{
		$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));	
		JFactory::getLanguage()->load('com_zefaniabible', '/language', null, true);
		$jlang = JFactory::getLanguage();
		$config = JFactory::getConfig();
				
		$jlang->load('mod_zefaniasubscribe', JPATH_BASE."/language", 'en-GB', true);
		$jlang->load('mod_zefaniasubscribe', JPATH_BASE."/language", null, true);
				
		require_once('components/com_zefaniabible/helpers/common.php');	
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		require_once('components/com_zefaniabible/models/default.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
				
		$user = JFactory::getUser();
		$item = new stdClass();
		$item->flg_show_overlay 	= $params->get('flg_overlay', 0);
		$item->flg_use_image 		= $params->get('flg_use_image', 0);
		$item->int_overlay_width 	= $params->get('overlay_width', 640);
		$item->int_overlay_height 	= $params->get('overlay_height', 480);
		
		$item->flg_catcha_correct 	= 0;
		$item->flg_email_valid 		= 0;
		if($item->flg_show_overlay)
		{
			if(!$item->flg_use_image)
			{
				echo '<div class="zef_email_button">';
				echo '<a title="'. JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE').'" target="blank" href="index.php?view=subscribe&option=com_zefaniabible&tmpl=component" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->int_overlay_width.',y:'.$item->int_overlay_height.'}}" >';
				echo JText::_('MOD_ZEFANIASUBSCRIBE_SUBSCRIBE').'</a></div>';
			}
			else
			{
				echo '<div class="zef_email_button">';
				echo '<a title="'. JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE').'" target="blank" href="index.php?view=subscribe&option=com_zefaniabible&tmpl=component" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->int_overlay_width.',y:'.$item->int_overlay_height.'}}" >';
				echo '<img src="'.JRoute::_('/media/com_zefaniabible/images/e_mail.png').'">'.'</a></div>';				
			}
		}
		else
		{
			$item->str_primary_bible 	= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
			$item->str_primary_reading 	= $params->get('primaryReading', $mdl_default->_buildQuery_first_plan());	
			$item->flg_use_catcha 		= $params->get('flg_use_catcha', 0);	
			$item->str_admin_email 		= $params->get('adminEmail');
			$item->str_from_email 		= $config->get( 'mailfrom' );
	    	$item->str_from_email_name	= $config->get( 'fromname' );
			
			
			$jinput = JFactory::getApplication()->input;
			$item->str_user_name 	= 	$jinput->get('mod_zef_subs_name', null,'USERNAME');
			$item->str_email 		= 	$jinput->get('mod_zef_subs_email', $user->email, 'STRING');
			if(trim($item->str_user_name) !='')
			{
				if($item->flg_use_catcha)
				{
					$item->flg_catcha_correct 	= 	$mdl_common->fnc_check_catcha('subscribe-form');
				}
				else
				{
					$item->flg_catcha_correct = 1;
				}
				if($item->str_email)
				{
					$item->flg_email_valid 		=	$mdl_common->fnc_validate_email($item->str_email);
				}					
			}
			elseif($user->name)
			{
				$item->str_user_name 		= 	$user->name;
				
			}	
			$item->flg_send_reading 				=	$jinput->get('mod_zef_sub_flg_send_reading' , '0', 'BOOL');
			$item->flg_send_verse					=	$jinput->get('mod_zef_sub_flg_send_verse' , '0', 'BOOL');
			$item->str_Bible_Version 				= 	$jinput->get('mod_zef_sub_bible_id', $item->str_primary_bible, 'CMD');	
			$item->str_reading_plan 				= 	$jinput->get('mod_zef_sub_reading_plan_id', $item->str_primary_reading,'CMD');	
			$item->str_start_reading_date 			= 	JHtml::date($params->get('reading_start_date', '1-1-2012'),'d-m-Y',true);
			$item->str_start_date					= 	JHtml::date($jinput->get('mod_zef_sub_calendar_date', $item->str_start_reading_date, 'CMD'),'d-m-Y',true);
			
			$item->arr_Bibles 						= 	$mdl_default->_buildQuery_Bibles_Names();
			$item->arr_reading_plan_list			= 	$mdl_default->_buildQuery_reading_plan_list($item);			
			$item->obj_reading_plan_dropdown		=	$mdl_common->fnc_reading_plan_drop_down($item);
			$item->obj_bible_Bible_dropdown			= 	$mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Bible_Version);
			$item->id								=	$user->id;
						
			$this->fnc_contruct_form($item);	
			if(($item->flg_catcha_correct)and($item->flg_email_valid))
			{
				if(($item->flg_send_reading)or($item->flg_send_verse))
				{
					$mdl_default->_buildQuery_InsertUser($item);
					$mdl_common->sendSignUpEmail($item);				}
				else
				{
					JError::raiseWarning('',JText::_('ZEFANIABIBLE_SELECT_EMAIL'));
				}
			}
		}
	}
	protected function fnc_contruct_form($item)
	{	
		require_once('components/com_zefaniabible/helpers/common.php');	
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		echo '<form action="'. JFactory::getURI()->toString().'" method="post" id="zefania_subscribe" name="zefania_subscribe">';
		echo '<div><div class="zef_bible_label">'.JText::_('ZEFANIABIBLE_USER_NAME_LABEL').'</div>';
		echo '<div><input type="text" name="mod_zef_subs_name" id="mod_zef_subs_name" size="25" maxlength="50" value="'.$item->str_user_name.'"></div></div>';

		echo '<div><div class="zef_bible_label">'.JText::_('ZEFANIABIBLE_EMAIL_LABEL').'</div>';
        echo '<div><input type="text" name="mod_zef_subs_email" id="mod_zef_subs_email" maxlength="50" size="25" value="'. $item->str_email .'"></div></div>';
		echo '<div style="clear:both"></div>';
		
		echo '<div><div class="mod_zefsend_bible_label">'. JText::_('ZEFANIABIBLE_BIBLE_VERSION').'</div>';
		echo '<div class="mod_zefsend_bible"><select name="mod_zef_sub_bible_id" id="zef_mod_send_bible" class="inputbox" >';
		echo $item->obj_bible_Bible_dropdown;
		echo '</select></div></div>';
		echo '<div style="clear:both"></div>';
		
		echo '<div><div class="mod_zefsend_readingplan_label">'. JText::_('ZEFANIABIBLE_READING_PLAN').'</div>';
		echo '<div class="mod_zefsend_readingplan"><select name="mod_zef_sub_reading_plan_id" id="zef_mod_send_readingplan" class="inputbox">';
		echo $item->obj_reading_plan_dropdown;
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
		if($item->flg_use_catcha)
		{
			$mdl_common->fnc_create_catcha('subscribe-form');
		}
		echo '<div><input class="button" type="submit" value="submit" name="'.JText::_('JSAVE').'"/></div></form>';		
	}
}
?>



