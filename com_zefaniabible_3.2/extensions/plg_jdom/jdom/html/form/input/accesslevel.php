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


class JDomHtmlFormInputAccesslevel extends JDomHtmlFormInput
{
	protected $display;
	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *  @display	: How to display the state (radio, combo)
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->arg('display', null, $args, 'list');
		
		//Get list from Joomla
		$this->list = JHtml::_('access.assetgroups');
	}

	function build()
	{
		$namespace = $size = null;
		switch($this->display)
		{
			case 'combo':
				$size = null;
				$namespace = 'html.form.input.select.combo';
				break;


			case 'radio':
				$namespace = 'html.form.input.select.radio';
				break;		

			case 'list':
			default:
				$namespace = 'html.form.input.select';
				$size = 3;
				break;

		}

		$html = JDom::_($namespace, array_merge($this->options, array(
			'list' => $this->list,
			'listKey'  => 'value',
			'size' => $size,
			'viewType' => 'text'
		)));
		
		return $html;
	}

}