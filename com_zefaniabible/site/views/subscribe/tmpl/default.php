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

defined('_JEXEC') or die('Restricted access'); ?>
<?php 
jimport( 'joomla.html.html' );
jimport( 'joomla.error.error' );
jimport( 'joomla.mail.mail' );
jimport( 'joomla.application.application' );

 ?>
<?php 
JHTML::_('behavior.modal');

$cls_subcribe = new BibleSubscribe($this->bibles, $this->readingplans, $this->user);


class BibleSubscribe
{
	/*
	a = User Name
	b = Plan
	c = Bible Version
	d = Email
	e = Send Reading
	f = Send Verse
	g = Start Reading Date
	h = User ID
	*/
	public $flg_use_catcha;
	private $str_catcha_public_key;
	private $str_catcha_private_key;
	private $flg_catcha_correct;
	private $str_reading_plan;
	private $str_bibleVersion;
	private $str_email;
	private $str_user_id;
	private $str_user_name;
	public $flg_send_reading;
	public $flg_send_verse;
	public $str_start_reading_date;
	private $flg_email_valid;
	private $str_from_email;
	private $str_from_email_name;
	private $str_admin_email;
	private $flg_date_valid;
	private $flg_no_sql_inection;
	private $int_bible_version_id;
	private $int_reading_plan;
	public $str_catcha_color;
	public function __construct($arr_bibles, $arr_reading_plans, $user)
	{
		$this->flg_catcha_correct = 0;
		$this->flg_email_valid = 0;
		$this->flg_date_valid = 0;
		$this->flg_no_sql_inection = 1;
		
		$config = JFactory::getConfig();
		$this->str_from_email 		= $config->get( 'config.mailfrom' );
    	$this->str_from_email_name	= $config->get( 'config.fromname' );		
		
		$this->str_user_name 			=	JRequest::getString('a');
		$this->str_reading_plan		 	= 	JRequest::getWord('b');
		$this->str_bibleVersion 		= 	JRequest::getWord('c');
		$this->str_email 				=	JRequest::getString('d');
		$this->flg_send_reading 		= 	JRequest::getWord('e');
		$this->flg_send_verse			=	JRequest::getWord('f'); 
		$this->str_start_reading_date 	= 	JRequest::getString('g');
		
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );	
		$this->flg_use_catcha = $this->params->get('flg_use_catcha', '0');
		$this->str_admin_email = $this->params->get('adminEmail');
		$this->str_catcha_color = $this->params->get('catcha_color', 'red');
	
		$this->str_user_id =  $user->id;
		
		if($this->str_start_reading_date == "")
		{
			$this->str_start_reading_date = date('Y-m-d',strtotime('now'));
		}
		else
		{
			// check date fomat dd-mm-yyyy
			$this->checkForSQLInjection($this->str_start_reading_date);

			if (preg_match('((19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01]))', $this->str_start_reading_date)) 			{
				$this->flg_date_valid = 1;
			}
			else
			{
				JError::raiseWarning('',JText::_('ZEFANIABIBLE_DATE_NOT_VALID'));
			}
			
		}
		
		if($this->str_email == "")
		{
			$this->str_email = $user->email;
		} 
		else
		{
			// check email
			$this->checkForSQLInjection($this->str_email);
			if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $this->str_email)) 
			{
				$this->flg_email_valid = 1;
    		}
			else
			{
				JError::raiseWarning('',JText::_('ZEFANIABIBLE_EMAIL_NOT_VALID'));
			}
		}
		
		if($this->str_user_name == "")
		{
			$this->str_user_name = $user->name;
		}
		else
		{
			$this->checkForSQLInjection($this->str_user_name);		
		}

		if($this->flg_use_catcha)
		{
			$this->checkCatcha();
			
				if(($this->flg_email_valid)and($this->flg_date_valid)and($this->flg_catcha_correct)and($this->flg_no_sql_inection))
				{
					if(($this->flg_send_reading)or($this->flg_send_verse))
					{						
						$this->insertData();
						$this->sendSignUpEmail();
					}
					else
					{
						JError::raiseWarning('',JText::_('ZEFANIABIBLE_SELECT_EMAIL'));
					}
				}
		} 
		else
		{
			if(($this->flg_email_valid)and($this->flg_date_valid)and($this->flg_no_sql_inection))
			{
				if(($this->flg_send_reading)or($this->flg_send_verse))
				{						
					$this->insertData();
					$this->sendSignUpEmail();
				}
				else
				{
					JError::raiseWarning('',JText::_('ZEFANIABIBLE_SELECT_EMAIL'));
				}
			}
		}		
	}
	private function insertData()
	{
		$db = JFactory::getDBO();
		$query_max = "SELECT Max(id) FROM `#__zefaniabible_zefaniauser`";	
		$db->setQuery($query_max);	
		$int_max_ids = $db->loadResult();	
		
		$query_bible_id = "SELECT id FROM `#__zefaniabible_bible_names` WHERE alias = '".$this->str_bibleVersion."'";
		$db->setQuery($query_bible_id);
		$this->int_bible_version_id = $db->loadResult();
		
		$qeery_plan_id = "SELECT id FROM `#__zefaniabible_zefaniareading` WHERE alias = '".$this->str_reading_plan."'";
		$db->setQuery($qeery_plan_id);
		$this->int_reading_plan = $db->loadResult();

		if($int_max_ids == "")
		{
			$int_max_ids = 1;	
		}
		else  
		{
			$int_max_ids++;
		}
		$arr_row->user_name = $this->str_user_name;
		$arr_row->plan = $this->int_reading_plan;
		$arr_row->bible_version = $this->int_bible_version_id;
		$arr_row->user_id = $this->str_user_id;
		$arr_row->email = $this->str_email;
		$arr_row->send_reading_plan_email = $this->flg_send_reading;
		$arr_row->send_verse_of_day_email = $this->flg_send_verse;
		$arr_row->reading_start_date = $this->str_start_reading_date;
		
		$db->insertObject("#__zefaniabible_zefaniauser", $arr_row, $int_max_ids);
        $app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('ZEFANIABIBLE_CATCHA_SUBMITED'));		
	
	}
	private function sendSignUpEmail()
	{ 
		$str_message = str_replace('%n',$this->str_user_name,JText::_('ZEFANIABIBLE_SIGNUP_EMAIL_BODY'))."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_START_READING_DATE').": ".$this->str_start_reading_date."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_VERSION').": ".$this->str_bibleVersion."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_READING_PLAN').": " . $this->str_reading_plan."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_SEND_READING_EMAIL').": ";
		if($this->flg_send_reading) 
		{ 
			$str_message = $str_message . JText::_('JYES'); 
		}
		else 
		{ 
			$str_message = $str_message . JText::_('JNO'); 
		} 
		$str_message = $str_message . "<br>"; 
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_SEND_VERSE_EMAIL').": ";
		if($this->flg_send_verse ) 
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
		$str_sender = array($this->str_from_email,$this->str_from_email_name);
		$mailer->setSender($str_sender);
		$mailer->addRecipient($this->str_email);
		$mailer->addBCC($this->str_admin_email);
		$mailer->setSubject(JText::_('ZEFANIABIBLE_SIGNUP_EMAIL_SUBJECT'));
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($str_message);
		$send =& $mailer->Send();			
	}
	private function checkForSQLInjection($id) 
	{		
			if(preg_match('/[\'"]/', $id))
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));
			}
			if(preg_match('/[\/\\\\]/', $id))
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));
			}
			if(preg_match('/(null|where|limit)/i', $id)) 
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));				
			}
			if(preg_match('/(union|select|from|having|delete|insert|update)/i', $id))
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));
			}
			if(preg_match('/(group|order|having|limit)/i', $id))
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));
			}
			if(preg_match('/(into|file|case)/i', $id)) 
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));
			}
			if(preg_match('/(--|#|\/\*)/', $id))
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));		
			}
			if(preg_match('/(=|&|\|)/', $id)) 
			{
				$this->sendEmailAdmin();
				$this->flg_no_sql_inection = 0;
				JError::raiseError('403',JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED'));	
			}
	}
	private function sendEmailAdmin()
	{
		$str_message = $str_message . JText::_('ZEFANIABIBLE_USER_NAME_LABEL').": ".$this->str_user_name."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_READING_PLAN').": " . $this->str_reading_plan."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_VERSION').": ".$this->str_bibleVersion."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_EMAIL_LABEL').": ".$this->str_email."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_SEND_READING_EMAIL').": ".$this->flg_send_reading."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_SEND_VERSE_EMAIL').": ".$this->flg_send_verse."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_BIBLE_START_READING_DATE').": ".$this->str_start_reading_date."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_FIELD_USER_ID').": ".$this->str_user_id."<br>";
		$str_message = $str_message . JText::_('ZEFANIABIBLE_IP_ADDRESS').": ". $_SERVER['REMOTE_ADDR']."<br>";
			
		$mailer =& JFactory::getMailer();
		$str_sender = array($this->str_from_email,$this->str_from_email_name);
		$mailer->setSender($str_sender);
		$mailer->addRecipient($this->str_admin_email);
		$mailer->setSubject(JText::_('ZEFANIABIBLE_SQL_INJECTION_DETECTED_EMAIL_SUBJECT'));
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($str_message);
		$send =& $mailer->Send();	
	}
	private function checkCatcha()
	{
		$resp = null;
		$error = null;
		require_once(JPATH_COMPONENT_SITE.'/helpers/recaptchalib.php');
		$this->str_catcha_public_key = $this->params->get('catchaPublicKey');
		$this->str_catcha_private_key = $this->params->get('catchaPrivateKey');
		if (isset($_POST["recaptcha_response_field"])) {
				$resp = recaptcha_check_answer ($this->str_catcha_private_key,
												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);
		
				if ($resp->is_valid) {
						$this->str_system_message =  JText::_('ZEFANIABIBLE_CATCHA_SUBMITED'); 
						$this->flg_catcha_correct = 1;
						
				} else {
						JError::raiseWarning('',JText::_('ZEFANIABIBLE_CATCHA_ERROR'));
				}
		}	
	}

	public function createCatcha()
	{
			echo recaptcha_get_html($this->str_catcha_public_key);
	}
	public function createBibleDropDown($items)
	{		
		$tempVersion = $this->str_bibleVersion;
		$tmp = ""; 
		foreach($items as $item)
		{			
				if($tempVersion == $item->alias)
				{
					$tmp = $tmp.'<option value="'.$item->alias.'" selected>'.$item->bible_name.'</option>';
				}
				else
				{
					$tmp = $tmp.'<option value="'.$item->alias.'" >'.$item->bible_name.'</option>';
				}
		}
		return $tmp;
	}				
	public function createReadingDropDown($readingplans)
	{		
		$tempVersion = $this->str_reading_plan;
		$tmp = ""; 
		foreach($readingplans as $readingplan)
		{
			if($readingplan->publish == 1)
			{								
				if($tempVersion == $readingplan->alias)
				{
					$tmp = $tmp.'<option value="'.$readingplan->alias.'" selected>'.$readingplan->name.'</option>';
				}
				else
				{
					$tmp = $tmp.'<option value="'.$readingplan->alias.'" >'.$readingplan->name.'</option>';
				}
			}
		}
		return $tmp;
	}
	private function encode($string,$key) {
		$key = sha1($key);
		$strLen = strlen($string);
		$keyLen = strlen($key);
		$j=0;
		$hash = "";
		for ($i = 0; $i < $strLen; $i++) {
			$ordStr = ord(substr($string,$i,1));
			if ($j == $keyLen) { $j = 0; }
			$ordKey = ord(substr($key,$j,1));
			$j++;
			$hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
		}
		return $hash;
	}

	private function decode($string,$key) {
		$key = sha1($key);
		$strLen = strlen($string);
		$keyLen = strlen($key);
		$hash = "";
		$j = 0;
		for ($i = 0; $i < $strLen; $i+=2) {
			$ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
			if ($j == $keyLen) { $j = 0; }
			$ordKey = ord(substr($key,$j,1));
			$j++;
			$hash .= chr($ordStr - $ordKey);
		}
		return $hash;
	}	
	public function getUserName()
	{
		return $this->str_user_name;
	}
	public function getUserID()
	{
		return $this->encode($this->str_user_id,$this->str_catcha_private_key);
	}
	public function getEmail()
	{
		return $this->str_email;
	}	
}
?> 
<script type="text/javascript">
        var RecaptchaOptions = 
		{
			custom_translations : 
			{
                        instructions_visual : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_TWO_WORD');?>",
                        instructions_audio : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_TYPE');?>", 
                        play_again : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_PLAY_AGAIN');?>",
                        cant_hear_this : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_DOWNLOAD');?>", 
                        visual_challenge : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_NEW_VISUAL');?>", 
                        audio_challenge : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_NEW_AUDIO');?>",
                        refresh_btn : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_NEW_CHALLANGE');?>",
                        help_btn : "<?php echo JText::_('COM_ZEFANIABIBLE_CATCHA_TRANS_HELP');?>",
                        incorrect_try_again : "<?php echo JText::_('ZEFANIABIBLE_CATCHA_ERROR');?>",
			},
            theme : '<?php echo $cls_subcribe->str_catcha_color; ?>'
        };
</script>
 
<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<div>       
		<div>
             <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_USER_NAME_LABEL');?></div> 
             <div><input type="text" name="a" id="zef_subs_name" size="40" maxlength="50" value="<?php echo $cls_subcribe->getUserName();?>"></div>
         </div>
         <div style="clear:both"></div>         
        
        <div>
            <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_READING_PLAN');?></div>
            <div class="zef_reading_plan">
                <select name="b" id="zef_subs_plans" class="inputbox">
                    <?php echo $cls_subcribe->createReadingDropDown($this->readingplans);?>
                </select>
            </div>
        </div>
        <div style="clear:both"></div> 
        
		<div>
            <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION');?></div>
            <div class="zef_bible">
                <select name="c" id="zef_subs_bibles" class="inputbox" >
                    <?php  echo $cls_subcribe->createBibleDropDown($this->bibles);?>
                </select>
             </div>
         </div>
         <div style="clear:both"></div>
         
         <div>
             <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_EMAIL_LABEL');?></div> 
             <div>
                <input type="text" name="d" id="zef_subs_email" maxlength="50" size="40" value="<?php echo $cls_subcribe->getEmail();?>">
             </div>
         </div>
         <div style="clear:both"></div> 
         
         <div>
             <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_SEND_READING_EMAIL');?></div> 
             <div class="zef_reading_email">
                <select name="e" id="zef_subs_send_reading" class="inputbox">
                    <option value="0" ><?php echo JText::_('JNO');?></option>
                    <option value="1" <?php if($cls_subcribe->flg_send_reading){?>selected<?php }?> ><?php echo JText::_('JYES');?></option>            
                </select>
             </div>
         </div>
         <div style="clear:both"></div> 
         
         <div>
             <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_SEND_VERSE_EMAIL');?></div> 
             <div class="zef_reading_verse">
                <select name="f" id="zef_subs_send_verse" class="inputbox">
                    <option value="0" ><?php echo JText::_('JNO');?></option>
                    <option value="1" <?php if($cls_subcribe->flg_send_verse){?>selected<?php }?> ><?php echo JText::_('JYES');?></option>
                </select>
             </div>  
         </div>
         <div style="clear:both"></div> 
         
         <div>
             <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_START_READING_DATE');?></div>
             <?php JHTML::calendar(date('d-m-Y',strtotime('now')),'g','zef_subs_start_date','%Y-%m-%d','');?>
             <div><input type="text" name="g" id="zef_subs_start_date" maxlength="10" size="10" value="<?php echo $cls_subcribe->str_start_reading_date; ?>"></div>
         </div>
         <div style="clear:both"></div> 
         <div>
        <?php
			if($cls_subcribe->flg_use_catcha)
			{
				$cls_subcribe->createCatcha();
			}
        ?>
         </div>
         <div style="clear:both"></div>
		<div><input class="zef_submit_subscribtion" type="submit" value="submit" name="<?php echo JText::_('JSAVE') ?>"/></div>
	</div>
</form>