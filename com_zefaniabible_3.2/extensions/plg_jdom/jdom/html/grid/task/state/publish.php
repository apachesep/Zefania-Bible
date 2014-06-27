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


class JDomHtmlGridTaskStatePublish extends JDomHtmlGridTaskState
{
	protected $publishUp;
	protected $publishDown;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *  @ctrl		: Prefix for tasks
	 *	@commandAcl		: ACL rights to toggle
	 *  @enabled		: Define if the control is togglable (default : true)
	 *  @tooltip		: Show the tooltip (default : true)
	 *	@taskYes		: task to execute when value is true
	 *	@taskNo			: task to execute when value is no
	 *	@strYES			: text to show when value is true
	 *	@strNO			: text to show when value is no
	 *	@strUndefined	: text to show when value is undefined
	 * 
	 *  @publishUp	: Time to start publish
	 *  @publishDown : Time to unpublish
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('publishUp'		, null, $args, true);
		$this->arg('publishDown'	, null, $args, true);
	}

	function build()
	{		
		$html = JHtml::_('jgrid.published', 
				$this->dataValue, 
				$this->num,
				array(
					'enabled' => $this->enabled,
					'checkbox' => 'cb',
					'prefix' => ($this->ctrl?$this->ctrl . '.':'')
				),
				null,
				null,
				$this->publishUp,
				$this->publishDown
		);

		return $html;
	}

}
