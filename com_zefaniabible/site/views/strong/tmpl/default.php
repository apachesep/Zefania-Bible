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

$cls_strong = new StrongBible($this->item);
class StrongBible
{
	private $arr_item;
	
	public function __construct($item)
	{		
		$this->arr_item = $item;
		JHTML::stylesheet('zefaniascripturelinks.css', 'plugins/content/zefaniascripturelinks/css/');
		$str_verse = "";	

		foreach ($item->arr_passage as $obj_passage)
		{
			$str_verse = '<div class="zef_strong">';
			$str_verse .= '<div class="zef_strong_dic_image"><img src="'.$item->str_default_image.'"></div>';
			$str_verse .= '<div style="clear:both;"></div>';
			$str_verse .= '<div class="zef_strong_dic_name">'.$item->str_dict_name.'</div>';
			$str_verse .= '<div style="clear:both;"></div>';			
			$str_verse .= '<div class="zef_strong_id">'.$obj_passage->item.'</div>';
			

			$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
			$obj_passage->description = preg_replace("/(?=\S)(&lt;tw\:\/\/\[self\]\?(.*?)&gt;)/iu",'',$obj_passage->description); // remove <tw://[self]?.. code
			$obj_passage->description = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $obj_passage->description);
					
			$str_verse .= '<div class="zef_strong_desc">'.JHtml::_('content.prepare',$obj_passage->description)."</div><br>";
			$str_verse .= '</div>';
		}
		echo $str_verse;
		if($item->flg_show_credit)
		{
			require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
			$mdl_credits = new ZefaniabibleCredits;
			echo '<div class="zef_footer">';
			echo $mdl_credits->fnc_credits();
			echo '</div>';
		}
    }
	private function fnc_Make_Scripture(&$arr_matches)
	{
		$temp = 'dict='.$this->arr_item->str_curr_dict.'&item='.trim(strip_tags($arr_matches[0]));
		if($this->arr_item->str_tmpl == "component")
		{
			$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" >';		
		}
		else
		{
			$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" target="blank" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->arr_item->str_dictionary_width.',y:'.$this->arr_item->str_dictionary_height.'}}" >';						
		}
		$str_verse .=  trim(strip_tags($arr_matches[0]));			
		$str_verse .=  '</a> ';
		
		return $str_verse;
	}	
	

}

?>