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

$cls_strong = new StrongBible($this->arr_passage,$this->str_dict_name);
class StrongBible
{
	private $str_primary_dictionary;
	private $flg_show_credit;
	private $str_tmpl;
	private $str_dictionary_height;
	private $str_dictionary_width;
	private $str_curr_dict;
	private $str_dict_default_image;
	
	public function __construct($arr_passage,$str_dict_name)
	{
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );		
		$this->str_primary_dictionary  = $this->params->get('str_primary_dictionary','');
		$this->flg_show_credit = $this->params->get('show_credit','0');	
		$this->str_tmpl 		= 		JRequest::getCmd('tmpl');
		$this->str_dictionary_height = $this->params->get('str_dictionary_height','500');
		$this->str_dictionary_width = $this->params->get('str_dictionary_width','800');	
		$this->str_dict_default_image =	$this->params->get('str_dict_default_image','media/com_zefaniabible/images/dictionary.jpg');
		
		JHTML::stylesheet('zefaniascripturelinks.css', 'plugins/content/zefaniascripturelinks/css/');
		$str_verse = "";	
		$this->str_curr_dict = JRequest::getWord('a');
		if(!$this->str_curr_dict)
		{
			$this->str_curr_dict = $this->str_primary_dictionary;
		}
		foreach ($arr_passage as $obj_passage)
		{
			$str_verse = '<div class="zef_strong">';
			$str_verse = $str_verse.'<div class="zef_strong_dic_image"><img src="'.$this->str_dict_default_image.'"></div>';
			$str_verse = $str_verse.'<div style="clear:both;"></div>';
			$str_verse = $str_verse.'<div class="zef_strong_dic_name">'.$str_dict_name.'</div>';
			$str_verse = $str_verse.'<div style="clear:both;"></div>';			
			$str_verse = $str_verse.'<div class="zef_strong_id">'.$obj_passage->item.'</div>';
			

			$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
			$obj_passage->description = preg_replace("/(?=\S)(&lt;tw\:\/\/\[self\]\?(.*?)&gt;)/iu",'',$obj_passage->description); // remove <tw://[self]?.. code
			$obj_passage->description = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $obj_passage->description);
					
			$str_verse = $str_verse.'<div class="zef_strong_desc">'.JHtml::_('content.prepare',$obj_passage->description)."</div><br>";
			$str_verse = $str_verse.'</div>';
		}
		echo $str_verse;
		if($this->flg_show_credit)
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
		$temp = 'a='.$this->str_curr_dict.'&b='.trim(strip_tags($arr_matches[0]));
		if($this->str_tmpl == "component")
		{
			$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" >';		
		}
		else
		{
			$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" target="blank" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->str_dictionary_width.',y:'.$this->str_dictionary_height.'}}" >';						
		}
		$str_verse = $str_verse. trim(strip_tags($arr_matches[0]));			
		$str_verse = $str_verse. '</a> ';
		
		return $str_verse;
	}	
	

}

?>