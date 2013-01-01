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
<?php ZefaniabibleHelper::headerDeclarations(); ?>
<?php 
JHTML::_('behavior.modal');
?>
<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
<?php 
$cls_bible_reading_plan_overview = new BibleReadingPlanOverview($this->bibles, $this->reading, $this->readingplans);
?>
	<div id="zef_Bible_Main">
    	<div class="zef_legend">
        	<?php if($cls_bible_reading_plan_overview->flg_email_button){?>
			<div class="zef_email_button"><a title="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" target="blank" href="index.php?view=subscribe&option=com_zefaniabible&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x:500,y:400}}" ><img class="zef_email_img" src="<?php echo JURI::root()."components/com_zefaniabible/images/e_mail.png"; ?>" /></a></div>        
            <?php } ?>
    		<div class="zef_reading_label"><?php echo JText::_('ZEFANIABIBLE_READING_PLAN');?></div>
            <div class="zef_reading_plan">
                <select name="a" id="reading" class="inputbox" onchange="this.form.submit()">
                    <?php echo $cls_bible_reading_plan_overview->createReadingDropDown($this->readingplans);?>
                </select>
            </div>
            <div style="clear:both"></div>       
            <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION');?></div>
            <div class="zef_bible">
                <select name="b" id="bible" class="inputbox" onchange="this.form.submit()">
                    <?php echo $cls_bible_reading_plan_overview->createBibleDropDown($this->bibles);?>
                </select>
            </div>
             <div style="clear:both"></div>
        <div class="zef_reading_name"><?php echo $cls_bible_reading_plan_overview->str_plan_name;?></div>
        <div class="zef_reading_desc"><?php echo JText::_($cls_bible_reading_plan_overview->str_plan_description); ?></div>
		<div class="zef_top_pagination">        
				<?php 
					if($cls_bible_reading_plan_overview->flg_show_page_top)
					{?>

								<p class="counter">
									<?php echo $this->pagination->getPagesCounter(); ?>
								</p>
								<?php echo $this->pagination->getPagesLinks(); ?>
				<?php	}
				?>   
		</div>           
	</div>
        <div class="zef_bible_Chapter"> 
        <?php echo $cls_bible_reading_plan_overview->str_page_output; ?>     
        </div>
        <div class="zef_footer">
			<div class="zef_bot_pagination">        
                <?php 
					if($cls_bible_reading_plan_overview->flg_show_page_bot)
					{
						echo $this->pagination->getListFooter();
					}
				?>  
                <div style="clear:both"></div>
                <?php 
				if($cls_bible_reading_plan_overview->flg_show_credit)
				{
					echo JText::_('ZEFANIABIBLE_DEVELOPED_BY')." <a href='http://www.zefaniabible.com/' target='_blank'>Zefania Bible</a>";
				}
            	?>                
            </div>
			     
        </div>
    </div>
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getCmd('view');?>" />
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>"/>    
</form>
<?php 
class BibleReadingPlanOverview
{
		/*
			a = plan
			b = bible
			c = day
		*/
	public $str_bible_layout;
	private $str_primary_bible;
	private $str_bibleVersion;
	private $str_primary_reading;
	private $str_reading_plan;
	public $flg_show_credit;
	public $flg_show_page_bot;
	public $flg_show_page_top;
	public $str_page_output;
	public $str_plan_description;
	public $str_plan_name;
	private $str_Bible_name;
	public $flg_email_button;
	
	public function __construct($arr_bibles, $arr_reading, $arr_readingplans)
	{
		$this->params = &JComponentHelper::getParams( 'com_zefaniabible' );	
		$this->str_bible_layout = JRequest::getCmd('layout','default');
		$this->str_primary_bible = $this->params->get('primaryBible', 'kjv');
		$this->str_bibleVersion = JRequest::getCmd('b', $this->str_primary_bible);		
		
		$this->str_primary_reading = $this->params->get('primaryReading', 'ttb');
		$this->str_reading_plan = JRequest::getCmd('a', $this->str_primary_reading);
		$this->flg_show_credit = $this->params->get('show_credit','0');
		$this->flg_show_page_top = $this->params->get('show_pagination_top', '1');
		$this->flg_show_page_bot = $this->params->get('show_pagination_bot', '1');	
		$this->flg_email_button 	= $this->params->get('flg_email_button', '1');	
		$this->createReadingDesc($arr_readingplans);
		$this->createChapterOutput($arr_reading);
		$this->getBibleName($arr_bibles);
		
		$this->doc_page =& JFactory::getDocument();	
		$app_site = JFactory::getApplication();	
		$this->doc_page->setTitle($this->str_plan_name);
		$this->doc_page->setMetaData( 'keywords', '');
		$this->doc_page->setMetaData( 'description', JText::_($this->str_plan_description));
		$this->doc_page->setMetaData( 'og:url', JFactory::getURI()->toString());		
		$this->doc_page->setMetaData( 'og:type', "article" );	
		$this->doc_page->setMetaData( 'og:image', JURI::root()."components/com_zefaniabible/images/bible_100.jpg" );	
		$this->doc_page->setMetaData( 'og:description', JText::_($this->str_plan_description) );
		$this->doc_page->setMetaData( 'og:site_name', $app_site->getCfg('sitename') );				
	}
	private function createReadingDesc($arr_readingplans)
	{
		foreach ($arr_readingplans as $reading_plan)
		{
			if($this->str_reading_plan == $reading_plan->alias)
			{
				$this->str_plan_description = strip_tags($reading_plan->description);
				$this->str_plan_name = $reading_plan->name; 
				break;
			}
		}
	}
	public function getBibleName($items)
	{		
		$tempVersion = $this->str_bibleVersion;
		$tmp = ""; 
		foreach($items as $item)
		{
			if($tempVersion == $item->alias)
			{
				$this->str_Bible_name = $item->bible_name;
			}
		}
	}			
	private function createChapterOutput($arr_reading)
	{
		$temp_day = 0;
		
		$this->str_page_output = $this->str_page_output. '<div class="odd">';
		$x = 0;
		foreach($arr_reading as $reading)
		{			
			if($temp_day != $reading->day_number)
			{
				$temp_day = $reading->day_number;
				if($x != 0)
				{
					$this->str_page_output = $this->str_page_output. '<div style="clear:both"></div></div>';
					if ($reading->day_number % 2)
					{
						$this->str_page_output = $this->str_page_output. '<div class="odd">';
					}
					else
					{
						$this->str_page_output = $this->str_page_output. '<div class="even">';
					}
					
				}
					$this->str_page_output = $this->str_page_output. '<div class="zef_day_number">'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$reading->day_number."</div>";
			}			
			$x++;
			$this->str_page_output = $this->str_page_output. '<div class="zef_reading">';
			$link = '<a title="'.JText::_('ZEFANIABIBLE_VERSE_READING_PLAN_OVERVIEW_CLICK_TITLE').'" href="'.JRoute::_("index.php?option=com_zefaniabible&view=reading&a=".$this->str_reading_plan."&b=".$this->str_bibleVersion."&c=".$reading->day_number).'" target="_self">';
			$this->str_page_output = $this->str_page_output. $link.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$reading->book_id)." ";
			$this->str_page_output = $this->str_page_output. $reading->begin_chapter;
			if(($reading->begin_verse == 0)and($reading->end_verse == 0))
			{
				if($reading->end_chapter != $reading->begin_chapter )
				{
					$this->str_page_output = $this->str_page_output. "-".$reading->end_chapter;
				}
			}
			else
			{
				$this->str_page_output = $this->str_page_output. ":".$reading->begin_verse."-".$reading->end_chapter.":".$reading->end_verse;
			}
			$this->str_page_output = $this->str_page_output. "</a></div>";
		}
		$this->str_page_output = $this->str_page_output. '</div><div style="clear:both"></div>';
		
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
}?>