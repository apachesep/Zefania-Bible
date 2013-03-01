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
//ZefaniabibleHelper::headerDeclarations();
$mdl_zef_bible_helper = new ZefaniabibleHelper();
$mdl_zef_bible_helper->headerDeclarations();
jimport( 'joomla.html.html' );
jimport( 'joomla.error.error' );
jimport( 'joomla.mail.mail' );
jimport( 'joomla.application.application' );

 ?>
<?php 
JHTML::_('behavior.modal');

$cls_unsubcribe = new BibleUnsubscribe($this->user);


class BibleUnsubscribe
{
	private $str_email;
	private $flg_email_valid;
	public $flg_use_catcha;
	private $str_admin_email;
	private $str_from_email ;
	private $str_from_email_name;
	private $str_catcha_public_key;
	private $str_catcha_private_key;
	private $flg_no_sql_inection;
	private $flg_catcha_correct;
	public $str_catcha_color;
	
	public function __construct($user)
	{
		$this->str_email =	JRequest::getString('d');
		$this->flg_email_valid = 0;
		$this->flg_catcha_correct = 0;
		$this->flg_no_sql_inection = 1;
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		$this->flg_use_catcha = $this->params->get('flg_use_catcha', '0');
		$this->str_admin_email = $this->params->get('adminEmail');
		$this->str_catcha_color = $this->params->get('catcha_color', 'red');
		
		$config =& JFactory::getConfig();
		$this->str_from_email 		= $config->get( 'config.mailfrom' );
    	$this->str_from_email_name	= $config->get( 'config.fromname' );	
		
		$this->str_user_name = $user->name;
		$this->str_user_id = $user->id;
		
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
		if($this->flg_use_catcha)
		{
			$this->checkCatcha();
			if(($this->flg_email_valid)and($this->flg_catcha_correct)and($this->flg_no_sql_inection))
			{
				$this->updateSubscription();
			}
		}
		else
		{
			$this->checkCatcha();
			if(($this->flg_email_valid)and($this->flg_no_sql_inection))
			{
				$this->updateSubscription();
			}			
		}
	}
	private function updateSubscription()
	{
		$db = JFactory::getDBO();
		$query_user_id = "SELECT id FROM `#__zefaniabible_zefaniauser` WHERE email = '".$this->str_email."' and (send_reading_plan_email = 1 or send_verse_of_day_email = 1 )";
		$db->setQuery($query_user_id);
		$int_user_id = $db->loadResult();
		if($int_user_id !='')
		{
			$arr_row->id = 	$int_user_id;
			$arr_row->send_reading_plan_email 	= '0';
			$arr_row->send_verse_of_day_email 	= '0';
			$db->updateObject("#__zefaniabible_zefaniauser", $arr_row, 'id');
			
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('ZEFANIABIBLE_CATCHA_SUBMITED'));	
		}
		else
		{
			JError::raiseWarning('',JText::_('ZEFANIABIBLE_EMAIL_NOT_FOUND'));	
		}
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
		$str_message = $str_message . JText::_('ZEFANIABIBLE_EMAIL_LABEL').": ".$this->str_email."<br>";
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
            theme : '<?php echo $cls_unsubcribe->str_catcha_color; ?>'
        };
</script>
 
<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<div>
    	<div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_EMAIL_LABEL');?></div> 
        <div>
            <input type="text" name="d" id="zef_subs_email" maxlength="50" size="40" value="<?php echo $cls_unsubcribe->getEmail();?>">            
        </div>
	</div>
         <div>
        <?php
			if($cls_unsubcribe->flg_use_catcha)
			{
				$cls_unsubcribe->createCatcha();
			}
        ?>
         </div>    
    <div><input class="zef_submit_subscribtion" type="submit" value="submit" name="<?php echo JText::_('JSAVE') ?>"/></div>
</form>