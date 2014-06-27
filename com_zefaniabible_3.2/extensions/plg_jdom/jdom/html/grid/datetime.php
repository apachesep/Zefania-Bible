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


class JDomHtmlGridDatetime extends JDomHtmlGrid
{
	var $level = 3;			//Namespace position
	var $last = true;		//This class is last call

	var $format;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *
	 *	@format		: Date format  -   default='%Y-%m-%d'
	 *
	 */
	function __construct($args)
	{

		parent::__construct($args);
		$this->arg('dateFormat' , null, $args, "%Y-%m-%d");
	}

	function build()
	{
		
		return JDom::_('html.fly.datetime', $this->options);
		
//DEPRECATED : Use fly.datetime		
		$formatedDate = "";

		if ($this->dataValue
			&& ($this->dataValue != "0000-00-00")
			&& ($this->dataValue != "00:00:00")
			&& ($this->dataValue != "0000-00-00 00:00:00"))
		{
			jimport("joomla.utilities.date");
			$date = new JDate($this->dataValue);
			$formatedDate = $date->toFormat($this->dateFormat);
		}

		$this->addClass('grid-date');

		$html = '<span <%STYLE%><%CLASS%><%SELECTORS%>>'
			.	$formatedDate
			.	'</span>';

		return $html;
	}

}