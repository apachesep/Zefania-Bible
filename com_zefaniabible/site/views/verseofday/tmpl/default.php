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

defined('_JEXEC') or die('Restricted access');
JHTML::stylesheet('zefaniabible.css', 'components/com_zefaniabible/css/'); 
?>
<div class="pagination">
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
<?
$cls_verses_of_day = new VersesOfTheDay($this->arr_verses);
class VersesOfTheDay
{
	private $str_primary_bible;
	private $str_biblePath;
	private $arr_bible_info;
	private $arr_bookXMLFile;
	private $arr_book_paths;
	public function __construct($arr_verses)
	{
		$this->params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$this->doc_page =& JFactory::getDocument();	
		$this->str_primary_bible = $this->params->get('primaryBible', 'kjv');
		$this->str_biblePath = $this->params->get('xmlBiblesPath', 'media/com_zefaniabible/bibles/');
		$this->fnc_Get_Bible_Info($arr_verses);
		
	}

	private function fnc_Get_Bible_Info($arr_verses)
	{
		
		foreach ($arr_verses as $arr_verse)
		{
			
			$int_verse_cnt = count($arr_verse);
			$x=0;
			$str_verse_output = '';
			foreach($arr_verse as $obj_verse)
			{
				if($int_verse_cnt == 1)
				{
					echo '<div class="zef_verse_of_day"><div class="zef_verse_header">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_verse->book_id)." ".$obj_verse->chapter_id.":".$obj_verse->verse_id;										
					echo '</div><div class="zef_verse_verse">'.$obj_verse->verse.'</div></div><div style="clear:both"></div><hr>';
				}
				else
				{
					if($x == 0)
					{
						$str_verse_output = $str_verse_output. '<div class="zef_verse_of_day">';
						$str_verse_output = $str_verse_output. '<div class="zef_verse_header">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_verse->book_id)." ".$obj_verse->chapter_id.":".$obj_verse->verse_id."-{zefania_end_verse}</div>";										
					}
					if ($x % 2)
					{
						$str_verse_output = $str_verse_output. '<div class="odd">';
					}
					else
					{
						$str_verse_output = $str_verse_output. '<div class="even">'; 
					}
					$str_verse_output = $str_verse_output. '<div class="zef_verse_number" >'.$obj_verse->verse_id.'</div><div class="zef_verse_verse">'.$obj_verse->verse.'</div></div>';												
					if(($int_verse_cnt-1) == $x)
					{						
						$str_verse_output = $str_verse_output. '</div><hr><div style="clear:both"></div>';
						echo str_replace('{zefania_end_verse}',$obj_verse->verse_id,$str_verse_output);
					}
					$x++;				
				}
			}
		}
	}
}
?>

    <div class="pagination">
    	<?php echo $this->pagination->getListFooter(); ?>
    </div>
</form>
