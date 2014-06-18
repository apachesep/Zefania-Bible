<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlIconIcomoon extends JDomHtmlIcon
{
	var $assetName = 'icomoon';

	var $attachCss = array(
		'icomoon.css',
	);
	
		
	/*
	 * Constuctor

	 */
	function __construct($args)
	{
		parent::__construct($args);
	}

	public function getIcons()
	{
		$default = array(
			'address'
			, 'apply', 'edit', 'pencil'
			, 'archive', 'drawer-2'
			, 'arrow-down-3'
			, 'arrow-first'
			, 'arrow-last'
			, 'arrow-left-2'
			, 'arrow-left-3'
			, 'arrow-right-2'
			, 'arrow-right-3'
			, 'arrow-up-2'
			, 'arrow-up-3'
			, 'asterisk', 'star-empty'
			, 'ban-circle', 'minus-sign', 'minus-2'
			, 'bars'
			, 'basket'
			, 'bookmark'
			, 'box-add'
			, 'box-remove'
			, 'briefcase'
			, 'broadcast'
			, 'brush'
			, 'calendar'
			, 'calendar-2'
			, 'camera'
			, 'camera-2'
			, 'cart'
			, 'chart'
			, 'checkbox-partial'
			, 'checkbox-unchecked'
			, 'checkedout', 'lock', 'locked'
			, 'checkin', 'checkbox'
			, 'chevron-down', 'downarrow', 'arrow-down'
			, 'chevron-left', 'arrow-left'
			, 'chevron-right', 'arrow-right'
			, 'chevron-up', 'uparrow', 'arrow-up'
			, 'clock'
			, 'cogs'
			, 'color-palette'
			, 'comment', 'comments'
			, 'comments-2'
			, 'compass'
			, 'contract'
			, 'contract-2'
			, 'cube'
			, 'dashboard'
			, 'database'
			, 'delete', 'remove', 'cancel-2'
			, 'download', 'arrow-down-2'
			, 'envelope', 'mail'
			, 'equalizer'
			, 'expand'
			, 'expand-2'
			, 'eye-close', 'minus'
			, 'eye-open', 'eye'
			, 'featured', 'star'
			, 'feed'
			, 'file'
			, 'file-add'
			, 'file-remove'
			, 'filter'
			, 'first'
			, 'flag'
			, 'flag-2'
			, 'folder-close', 'folder-2'
			, 'folder-open', 'folder'
			, 'grid-view'
			, 'grid-view-2'
			, 'health'
			, 'home'
			, 'key'
			, 'lamp'
			, 'last'
			, 'lightning'
			, 'list', 'list-view'
			, 'location'
			, 'loop'
			, 'mail-2'
			, 'menu'
			, 'menu-2'
			, 'mobile'
			, 'move'
			, 'music'
			, 'new', 'plus'
			, 'next'
			, 'options', 'cog'
			, 'out-2'
			, 'pencil-2'
			, 'pending', 'warning'
			, 'picture'
			, 'pictures'
			, 'pie'
			, 'pin'
			, 'play'
			, 'play-2'
			, 'power-cord'
			, 'previous'
			, 'print', 'printer'
			, 'publish', 'save', 'ok', 'checkmark'
			, 'purge', 'trash'
			, 'puzzle'
			, 'question-sign', 'help'
			, 'quote'
			, 'quote-2'
			, 'save-copy', 'copy'
			, 'save-new', 'plus-2'
			, 'screen'
			, 'search'
			, 'share', 'redo'
			, 'share-alt', 'out'
			, 'shuffle'
			, 'star-2'
			, 'support'
			, 'tablet'
			, 'thumbs-down'
			, 'thumbs-up'
			, 'tools'
			, 'unarchive', 'drawer'
			, 'unblock', 'refresh'
			, 'undo'
			, 'unpublish', 'cancel'
			, 'upload'
			, 'user'
			, 'users'
			, 'vcard'
			, 'wand'
			, 'wrench'
			, 'zoom-in'
			, 'zoom-out'
		);
		
		return $default;
		
	}	
}