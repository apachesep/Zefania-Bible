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


class JDomHtmlGridCheckedout extends JDomHtmlGrid
{
	
	var $assetName = 'joomla';

	var $attachJs = array(
		'joomla.js'
	);
	
	protected $keyCheckedOut = null;
	protected $keyCheckedOutTime = null;
	protected $keyEditor = null;
	protected $user = null;
	protected $allow = null;
	protected $listKey;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *
	 */
	function __construct($args)
	{

		parent::__construct($args);
		$this->arg('dataKey'			, null, $args, "cid");
		$this->arg('listKey'			, null, $args, "id");
		$this->arg('keyCheckedOut'		, null, $args, "checked_out");
		$this->arg('keyEditor'			, null, $args, "_checked_out_name");
		$this->arg('keyCheckedOutTime'	, null, $args, "checked_out_time");

		$this->arg('allow'				, null, $args, false);
	}

	function build()
	{
		$html = '';

		$dataKey = $this->dataKey;
		$keyChecked = $this->keyCheckedOut;
		if (property_exists($this->dataObject, $keyChecked))
			$this->dataValue = $this->dataObject->$keyChecked;

		$isLocked = (!empty($this->dataValue) && ($this->dataValue != JFactory::getUser()->get('id')));
		if ($isLocked)
			$html .= $this->checkedOut();
		
		$listKey = $this->listKey;
		if (!$isLocked || $this->allow)
			$html .= JHtml::_('grid.id', $this->num, $this->dataObject->$listKey, ($isLocked && !$this->allow), $dataKey);


		return $html;
	}

	function checkedOut($tip = true)
	{
		$hover = '';

		if ($tip)
		{
			$keyTime = $this->keyCheckedOutTime;
			$checked_out_time = $this->dataObject->$keyTime;

			$text = '';
			$keyEditor = $this->keyEditor;
			if (isset($this->dataObject->$keyEditor))
			{
				$editor = $this->dataObject->$keyEditor;
				$text .= addslashes(htmlspecialchars($editor, ENT_COMPAT, 'UTF-8'));
			}


			$date = JHtml::_('date', $checked_out_time, JText::_('DATE_FORMAT_LC1'));
			$time = JHtml::_('date', $checked_out_time, 'H:i');

			$hover = '<span class="editlinktip hasTip" title="' . JText::_('JLIB_HTML_CHECKED_OUT') . '::' . $text . '<br />' . $date . '<br />'
				. $time . '">';
		}

		$checked = $hover . JHtml::_('image', 'admin/checked_out.png', null, null, true) . '</span>';

		return $checked;
	}

}