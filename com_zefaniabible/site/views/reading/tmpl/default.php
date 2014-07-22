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
require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
$mdl_common 	= new ZefaniabibleCommonHelper;
//$cls_bible_reading_plan = new BibleReadingPlan($this->bibles, $this->reading, $this->arr_reading_plans, $this->plan,$this->arr_commentary, $this->int_max_days, $this->arr_commentary_list, $this->arr_dictionary_list,$this->obj_references);

class BibleReadingPlan
{
		/*
			a = plan
			b = bible
			c = day
			com = commentary
			dict = Dictionary
			strong = Show/Hide Strong Numgers flag			
		*/
	public $str_bibleVersion;
	private $str_primary_bible;
	private $int_reading_day;
	private $str_start_reading_date;
	private $biblePath;
	private $str_nativeLocation;
	private $arr_book_info;
	private $arr_bookXMLFile;
	public $str_page_output;
	public $str_bible_layout;
	public $flg_show_credit;
	private $flg_show_pagination_type;
	public $flg_show_page_top;
	private $arr_reading;
	private $str_primary_reading;
	public $arr_total_bibles_loaded;
	public $str_reading_plan;
	private $str_prmary_commentary;
	private $str_Commentary_XML_Path;
	public $flg_show_commentary;
	public $int_day_number;
	private $str_chapter_headings;
	private $str_curr_read_plan;
	public $str_first_verse;
	private $arr_commentary_File;
	private $str_view;
	public $flg_email_button;
	public $flg_reading_rss_button;
	public $flg_show_audio_player;
	public $flg_use_bible_selection;
	private $str_commentary;
	public $str_tmpl;
	public $str_commentary_width;
	public $str_commentary_height;
	public $flg_show_references;
	private $str_dictionary_height;
	private $str_dictionary_width;
	public $str_primary_dictionary;
	public $flg_show_dictionary;
	public $str_primary_commentary;
	private $str_curr_dict;	
	public $flg_strong_dict;
	private $arr_commentary_list;
	private $arr_dictionary_lis;
	public $str_chapter_output;
	public $str_default_image;
	
	public function __construct($arr_bibles, $arr_reading, $arr_reading_plans, $arr_plan, $arr_commentary, $int_max_days, $arr_commentary_list, $arr_dictionary_list, $obj_references)
	{
		
		$this->arr_reading = $arr_reading;
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );		
		$this->doc_page = JFactory::getDocument();	

		$this->str_view = JRequest::getCmd('view');
		$this->str_primary_reading = 	$this->params->get('primaryReading', 'ttb');
		$this->str_primary_bible = 		$this->params->get('primaryBible', 'kjv');	
		$this->flg_show_audio_player = 	$this->params->get('show_audioPlayer', '0');
		$this->flg_show_commentary = $this->params->get('show_commentary', '0');
		$this->flg_show_references = $this->params->get('show_references', '0');
		$this->str_tmpl = JRequest::getCmd('tmpl');
		$this->str_reading_plan = 	JRequest::getCmd('a', $this->str_primary_reading);	
		$this->str_bibleVersion = 	JRequest::getCmd('b', $this->str_primary_bible);		
		$this->str_commentary_width = $this->params->get('commentaryWidth','800');
		$this->str_commentary_height = $this->params->get('commentaryHeight','500');
		$this->str_dictionary_height = $this->params->get('str_dictionary_height','500');
		$this->str_dictionary_width = $this->params->get('str_dictionary_width','800');	
		$this->str_primary_dictionary  = $this->params->get('str_primary_dictionary','');
		$this->flg_show_dictionary = $this->params->get('flg_show_dictionary', 0);
		$this->str_primary_commentary = $this->params->get('primaryCommentary');
		$this->str_curr_dict = JRequest::getCmd('dict');		
		$this->str_commentary = JRequest::getCmd('com',$this->str_primary_commentary);
		$this->str_default_image = $this->params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		
		$this->flg_show_credit 			= $this->params->get('show_credit','0');
		$this->flg_show_pagination_type = $this->params->get('show_pagination_type','0');
		$this->flg_show_page_top 		= $this->params->get('show_pagination_top', '1');
		$this->flg_show_page_bot 		= $this->params->get('show_pagination_bot', '1');
		$this->flg_use_bible_selection 	= $this->params->get('flg_use_bible_selection', '1');
		$this->flg_strong_dict = 0;
		$this->arr_commentary_list = $arr_commentary_list;
		$this->arr_dictionary_list = $arr_dictionary_list;
				
		if(!$this->str_curr_dict)
		{
			$this->str_curr_dict = $this->str_primary_dictionary;
		}		 
		foreach($arr_reading as $obj_reading)
		{
			$this->int_day_number = $obj_reading->day_number;
		}
		foreach ($arr_reading_plans as $plan)
		{
			if($this->str_reading_plan == $plan->alias)
			{
				$this->str_curr_read_plan = $plan->name;
			}
		}
		$this->getMetaData($arr_plan);
		$this->str_chapter_output = $this->fnc_output_chapter($arr_plan, $arr_commentary, $obj_references);
	}
	public function fnc_output_chapter($arr_plan, $arr_commentary, $arr_references)
	{
			$book = 0;
			$chap = 0;
			$x = 1;
			$y = 1;		
			$str_chapter = '';
		if($this->flg_show_audio_player)
		{
			require_once(JPATH_COMPONENT_SITE.'/helpers/audioplayer.php');	
			$mdl_audio = new ZefaniaAudioPlayer;
		}			
			foreach($arr_plan as $reading)
			{
				$cnt_verse_count = count($reading);
				$z = 1;
				foreach($reading as $plan)
				{
					if($plan->verse_id == 1)
					{
						$this->str_first_verse = $plan->verse;
					}		
					if (($plan->book_id > $book)or($plan->chapter_id > $chap))
					{
						$book = $plan->book_id;
						$chap = $plan->chapter_id;
						if($y > 1)
						{
							$str_chapter = $str_chapter. '</div>';
						}
						$str_chapter = $str_chapter. '<div class="zef_bible_Header_Label_Plan"><h1 class="zef_bible_Header_Label_h1"><a name="'.$y.'" id="'.$y.'"></a>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ";
						$str_chapter = $str_chapter. mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id.'</h1></div>';
						$str_chapter = $str_chapter. '<div class="zef_bible_Chapter">';
						$arr_single_commentary  = $arr_commentary[($y-1)];
						if($this->flg_show_audio_player)
						{
							$obj_player = $mdl_audio->fnc_audio_player($this->str_primary_bible,$plan->book_id,$plan->chapter_id, $y);
							$str_chapter = $str_chapter. '<div class="zef_player-'.$y.'">';
							$str_chapter = $str_chapter. $obj_player;
        					$str_chapter = $str_chapter. "</div>";
							$str_chapter = $str_chapter. '<div style="clear:both"></div>';
						}
						$x = 1;
						$y++;			
					}

					if ($x % 2)
					{
						$str_chapter = $str_chapter. '<div class="odd">';
					}
					else
					{
						$str_chapter = $str_chapter. '<div class="even">'; 
					}
					$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
					$plan->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $plan->verse);
					
					$str_chapter = $str_chapter. "<div class='zef_verse_number'>".$plan->verse_id."</div><div class='zef_verse'>".$plan->verse."</div>";
					if($this->flg_show_references)
					{
						foreach($arr_references as $obj_references)
						{
							if(($plan->book_id == $obj_references->book_id)and($plan->chapter_id == $obj_references->chapter_id)and($plan->verse_id == $obj_references->verse_id))
							{
								$temp = 'a='.$this->str_primary_bible.'&b='.$plan->book_id.'&c='.$plan->chapter_id.'&d='.$plan->verse_id;
								$str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->str_commentary_width.',y:'.$this->str_commentary_height.'}}">';
								$str_chapter = $str_chapter. '<div class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></div>';									
								break;
							}
						}							
					}
					if($this->flg_show_commentary)
					{

						
						foreach($arr_single_commentary as $int_verse_commentary)
						{
							if($plan->verse_id == $int_verse_commentary->verse_id)
							{
								$str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&a=".$this->str_commentary."&b=".$plan->book_id."&c=".$plan->chapter_id."&d=".$plan->verse_id."&tmpl=component");
								$str_chapter = $str_chapter. '<div class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->str_commentary_width.',y:'.$this->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></div>";
							}
						}
					}	
					$str_chapter = $str_chapter. '<div style="clear:both"></div></div>';		
					$x++;
					$z++;
				}
			}
		return $str_chapter;
	}
	
	
}
?>


<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<div id="zef_Bible_Main<?php if($this->item->str_tmpl == "component"){?>_tmpl_comp<?php }?>">
    	<div class="zef_legend">
        		<?php if(($this->item->flg_email_button)and($this->item->str_tmpl != "component")){?>
        		<div class="zef_email_button"><a title="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" target="blank" href="index.php?view=subscribe&option=com_zefaniabible&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x:500,y:400}}" ><img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/e_mail.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" /></a></div>
                <?php } 
					if(($this->item->flg_reading_rss_button)and($this->item->str_tmpl != "component")){
				?>
					<div class="zef_reading_rss">
                    	<a rel="nofollow" title="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" target="blank" href="index.php?option=com_zefaniabible&view=readingrss&format=raw&plan=<?php echo $this->item->str_reading_plan; ?>&bible=<?php echo $this->item->str_Bible_Version; ?>&day=<?php echo $this->item->int_day_diff;?>" target="_blank" >
                			<img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/feeds.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" />
						</a>
					</div>                
				<?php }?>                
                <div class="zef_reading_label"><?php echo JText::_('ZEFANIABIBLE_READING_PLAN');?></div>
                <div class="zef_reading_plan">
                    <select name="plan" id="reading" class="inputbox" onchange="this.form.submit()">
                        <?php echo $this->item->obj_reading_plan_dropdown;?>
                    </select>
                </div>
                <div style="clear:both"></div>     
                 <?php if(($this->item->flg_use_bible_selection)and(count($this->item->arr_Bibles) > 1)){?>                
                <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION');?></div>
                <div class="zef_bible">
                    <select name="bible" id="bible" class="inputbox" onchange="this.form.submit()">
                        <?php echo $this->item->obj_bible_Bible_dropdown;?>
                    </select>
                </div>
				 <?php }else {
					echo '<input type="hidden" name="bible" value="'.$this->item->str_Bible_Version.'" />';
				} ?>
                <div style="clear:both;"></div>
				<?php if($this->item->flg_show_commentary){ 
						if(count($this->item->arr_commentary_list)> 1){
				?>
                    <div>
                        <div class="zef_commentary_label"><?php echo JText::_('COM_ZEFANIABIBLE_COMMENTARY_LABEL');?></div>
                        <div class="zef_commentary">
                            <select name="com" id="commentary" class="inputbox" onchange="this.form.submit()">
                                <?php echo $this->item->obj_commentary_dropdown;?>
                             </select>
                        </div>
                    </div>
					<?php }else{?>
                        	<!--<input type="hidden" name="com" value="<?php echo $this->item->str_primary_commentary;?>" />-->
                <?php }} ?>
				 <?php if($this->item->flg_show_dictionary){
						if(count($this->item->arr_dictionary_list) > 1){?>
                            <div id="zef_dictionary_div">
                                <div class="zef_dictionary_label"><?php echo JText::_('COM_ZEFANIABIBLE_DICTIONARY_LABEL');?></div>
                                <div class="zef_dictionary">
                                    <select name="dict" id="dictionary" class="inputbox" onchange="this.form.submit()">
                                        <?php echo $this->item->fnc_dictionary_dropdown($this->arr_dictionary_list);?>
                                     </select>
                                </div>
                            </div>
					<?php }?>
                    	<?php if($this->item->flg_use_strong){?>
							<div class="zef_dictionary_strong_box">
                            	<div class="zef_dictionary_strong_label"><?php echo JText::_('COM_ZEFANIABIBLE_HIDE_STRONG');?></div>
								<div class="zef_dictionary_strong_input">
	                                <input type='hidden' value='0' name='strong'>
                                	<input type='checkbox' name='strong' value="1" id='zef_hide_strong' <?php if(JRequest::getCmd('strong') == 1){ echo 'checked="checked"';}?> onchange="this.form.submit()" />
								</div>
							</div>
	                    <?php } ?>
                    <?php } ?>                
                 <div style="clear:both"></div>      
            <div class="zef_top_pagination">        
                    <?php 
                        if($this->item->flg_show_page_top)
                        {
						 	$mdl_common->fnc_Pagination_Buttons_day($this->item);
							if($this->item->str_tmpl == "component"){ echo "<br>";}
							$mdl_common->fnc_jump_button($this->item);
                        }
                    ?>              
          </div>
	</div> 
	<article><?php //echo $cls_bible_reading_plan->str_chapter_output ?></article>
        <div class="zef_footer">
			<div class="zef_bot_pagination">        
                <?php 
					if($this->item->flg_show_page_bot)
					{
						$mdl_common->fnc_Pagination_Buttons_day($this->item);
						if($this->item->str_tmpl == "component"){ echo "<br>";}
						$mdl_common->fnc_jump_button($this->item);
					}
				?>   
                <div style="clear:both"></div>
                <?php  
				if(($this->item->flg_show_credit)or(JRequest::getInt('Itemid') == 0 ))
				{ 
					require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
					$mdl_credits = new ZefaniabibleCredits;
					$obj_player_one = $mdl_credits->fnc_credits();
				}
            	?>               
            </div> 
			     
        </div>
    </div>
	<input type="hidden" name="option" value="<?php echo $this->item->str_option;?>" />
	<input type="hidden" name="view" value="<?php echo $this->item->str_view;?>" />
    <input type="hidden" name="day" value="<?php echo $this->item->int_day_diff; ?>" />
    <input type="hidden" name="Itemid" value="<?php echo $this->item->int_menu_item_id; ?>"/>
</form>
