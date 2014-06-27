<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.6.3
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


class JDomHtmlGridTaskToggle extends JDomHtmlGridTask
{
	var $level = 4;			//Namespace position
	var $last = true;		//This class is last call

	protected $max;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *	@commandAcl : ACL rights to toggle
	 *
	 *	@max		: Max value for toggle (not used yet)
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('max' , null, $args, 1);
	}

	function build()
	{
		$togglable = true;
		if ($this->commandAcl)
			$togglable = $this->access($this->commandAcl);


		switch($this->max)
		{
			case 1:
				//Bool
				$html = JDom::_('html.grid.bool', array_merge($this->options, array()));
				break;

			default:
				//Raw value
				$html = JDom::_('html.fly', array_merge($this->options, array()));
				break;

		}


		if ($togglable)
		{
			$ctrl = ($this->ctrl?$this->ctrl.'.':'');
			$this->task = $ctrl . 'toggle_' . $this->dataKey;

			$html = $this->embedTaskLink($html);

		}


		return $html;
	}

}