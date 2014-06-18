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


class JDomHtmlFlyDatetime extends JDomHtmlFly
{
	var $level = 3;			//Namespace position
	var $last = true;

	protected $format;
	protected $timezone;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 *	@format		: Date format  -   default='Y-m-d'
	 *
	 */
	function __construct($args)
	{

		parent::__construct($args);
		$this->arg('dateFormat' , null, $args, "Y-m-d");
		$this->arg('timezone' , null, $args);
		
	//JDate::toFormat() is deprecated. CONVERT Legacy Joomla Format
		//Minutes : ‰M > i
		$this->dateFormat = str_replace("%M", "i", $this->dateFormat);
		//remove the %
		$this->dateFormat = str_replace("%", "", $this->dateFormat);
	}

	function build()
	{
		$formatedDate = "";

		if (!empty($this->dataValue)
			&& ($this->dataValue != "0000-00-00")
			&& ($this->dataValue != "00:00:00")
			&& ($this->dataValue != "0000-00-00 00:00:00"))
		{
			jimport("joomla.utilities.date");
			$date = new JDate($this->dataValue);
			
			if ($tz = $this->timezone)
			{
				if ($tz == 'server')
				{
					//TODO : Get the server timezone
					$tz = 0; //DBG
				}
				else if ($tz == 'local')
				{
					//TODO : Get the current user local timezone
					$tz = 0; //DBG
				}
				$date->setOffset((int)$tz);
			}
			$formatedDate = $date->format($this->dateFormat, !empty($tz));
		}
		
		$this->addClass('fly-date');

		$html = '<span <%STYLE%><%CLASS%><%SELECTORS%>>'
			.	$formatedDate
			.	'</span>';

		return $html;
	}

}