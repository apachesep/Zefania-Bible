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
class BibleAtom{

	public function __construct($item)
	{
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		
		$str_verse = '';											
		$str_url_link = substr(JURI::root(),0,-1).JRoute::_("index.php?option=com_zefaniabible&view=standard&bible=".$item->str_Bible_Version."&book=".
			$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID)]))."&chapter=".$item->int_Bible_Chapter."-chapter&Itemid=".$item->int_menu_item."&ord=".date("mdy").'&Itemid='.$item->str_view_plan, false);	
		$str_url_escaped = 	str_replace('&', '&amp;',$str_url_link);
		$str_admin_email = $this->params->get('adminEmail', 'admin@'.substr(JURI::root(),7,-1));
		
		foreach($item->arr_Chapter as $obj_chapter)
		{
				$str_verse .= '				'.strip_tags($obj_chapter->verse).''.PHP_EOL;
		}
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<feed xmlns="http://www.w3.org/2005/Atom">'.PHP_EOL;
        	echo '	<title>'.$item->str_bible_name.'</title>'.PHP_EOL;
			echo '  <subtitle>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter.'</subtitle>'.PHP_EOL;
			echo '  <link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" />'.PHP_EOL;
			echo '  <link href="'.$str_url_escaped.'" />'.PHP_EOL;
			echo '  <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').'</id>'.PHP_EOL;
			echo '  <updated>'.date('Y-m-d\TH:i:sP').'</updated>'.PHP_EOL;
			echo '  	<entry>'.PHP_EOL;
			echo '      	<title>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter.'</title>'.PHP_EOL;
			echo '          <link href="'.$str_url_escaped.'" />'.PHP_EOL;
			echo '          <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').'</id>'.PHP_EOL;
			echo '          <updated>'.date('Y-m-d\TH:i:sP').'</updated>'.PHP_EOL;
			echo '          <summary>'.PHP_EOL;
			echo 				$str_verse;
			echo '			</summary>'.PHP_EOL;
			echo '          <author>'.PHP_EOL;
			echo '          	<name>'.$mainframe->getCfg('sitename').'</name>'.PHP_EOL;
			echo '              <email>'.$str_admin_email.'</email>'.PHP_EOL;
			echo '			</author>'.PHP_EOL;
			echo '	</entry>'.PHP_EOL;
			echo '</feed>';				
	}
}
?>