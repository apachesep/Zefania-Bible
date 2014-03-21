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
class Bible 
{

	
	public function __construct($items, $comment, $bibleBooks)
	{
		switch ($int_player_type)
		{
		case 0:
			{
				echo "<div id='mediaspace-".$id."'>Flash needs to be turned on.</div>";
				$this->doc_page->addScript('media/com_zefaniabible/player/jwplayer/jwplayer.js');
				echo "<script type='text/javascript' src='".JURI::root()."media/com_zefaniabible/player/jwplayer/jwplayer.js'></script>";
				echo "<script type='text/javascript'>";
				echo "jwplayer('mediaspace-".$id."').setup({";
				echo "'flashplayer': '". JURI::root()."media/com_zefaniabible/player/jwplayer/player.swf',";
				echo "'file': '".$arr_audio_Full_path[$id]."',";
				echo "'controlbar': 'bottom',";
				echo "'width': '".$int_player_width."',";
				echo "'height': '".$int_player_height."'";
				echo "})";
				echo "</script>";
			}
			break;
		case 1:
			{
				echo "<div id='mediaspace-".$id."'>".JText::_('ZEFANIABIBLE_BIBLE_ENABLE_FLASH')."</div>";
				$this->doc_page->addScript('media/com_zefaniabible/player/audio_player/audio-player.js');
				?>
				<script type="text/javascript">  
					AudioPlayer.setup("<?php echo JURI::root();?>media/com_zefaniabible/player/audio_player/player.swf", {  
						width: <?php echo $int_player_width; ?>,
						transparentpagebg: "yes",
					});  
					AudioPlayer.embed("mediaspace-<?php echo $id;?>", {  
					soundFile: "<?php echo $arr_audio_Full_path[$id];?>",  
					titles: "<?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->int_bibleBookID)." ".$this->int_bibleChapter; ?>",  
					artists: "<?php if($id==1){echo $this->arr_bookInfo['str_nativeFullName'];}else{echo $this->arr_bookInfo2['str_nativeFullName'];} ?>",  
					autostart: "no"  
					});  
				</script>            
			<?php 
			}
			break;
		default:
			// flow player
			$this->doc_page->addScript('media/com_zefaniabible/player/flowplayer/flowplayer-3.2.6.min.js');
			?>
                <a href="<?php echo JURI::root().$this->str_audioPath."/".$this->arr_audioFilePath[$id];?>"
                    style="display:block;width:<?php echo $int_player_width;?>px;height:<?php echo $int_player_height;?>px;"
                    id="mediaspace-<?php echo $id;?>">
                </a>   
				<script language="JavaScript">
                flowplayer("mediaspace-<?php echo $id;?>", "<?php echo JURI::root();?>media/com_zefaniabible/player/flowplayer/flowplayer-3.2.7.swf", {
					plugins: 
					{
						controls: 
						{
							fullscreen: false,
							widith: <?php echo $int_player_width;?>,
							height: <?php echo $int_player_height;?>,
							autoHide: false
						}
					},
					clip: 
					{
						url: "<?php echo $arr_audio_Full_path[$id];?>",
						autoPlay: false,
					}
				});
				</script>                         
            <?php
			break;
		}		
	}
}
?>