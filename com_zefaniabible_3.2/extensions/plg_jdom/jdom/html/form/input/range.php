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


class JDomHtmlFormInputRange extends JDomHtmlFormInput
{
	var $level = 4;			//Namespace position
	var $last = true;		//This class is last call

	var $rangeNameSpace;
	var $dataValueFrom;
	var $dataValueTo;
	var $labelFrom;
	var $labelTo;
	var $alignHz;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *	@rangeNameSpace	: child JDom namespace classes
	 *	@dataValueFrom	: from value
	 *	@dataValueTo	: to value
	 *	@labelFrom		: label From
	 *	@labelTo		: label TO
	 *	@alignHz		: Horizontal display
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('rangeNameSpace'		, null, $args);
		$this->arg('dataValueFrom'		, null, $args, null);
		$this->arg('dataValueTo'		, null, $args, null);
		$this->arg('labelFrom'			, null, $args, '');
		$this->arg('labelTo'			, null, $args, '');
		$this->arg('alignHz'			, null, $args, false);




	}

	function build()
	{
		if (!isset($this->rangeNameSpace))
			return;

		$hz = $this->alignHz;

		$jDomFrom = JDom::_($this->rangeNameSpace, array_merge($this->options, array(
									'dataKey' => $this->dataKey . '_from',
									'dataValue' => isset($this->dataValueFrom)?$this->dataValueFrom:null
										)));

		$jDomTo = JDom::_($this->rangeNameSpace, array_merge($this->options, array(
									'dataKey' => $this->dataKey . '_to',
									'dataValue' => isset($this->dataValueTo)?$this->dataValueTo:null
										)));



		$html =	"";


//FROM
		$html .= ($hz?'<div style="float:left">' . "\n":'');
		$html .= $this->JText($this->labelFrom) . "\n";
		$html .= $jDomFrom;
		$html .= ($hz?'</div>' . "\n":'');


//TO
		$html .= ($hz?'<div style="float:left">' . "\n":'');
		$html .= $this->JText($this->labelTo) . "\n";
		$html .= $jDomTo;
		$html .= ($hz?'</div>' . "\n":'');


		return $html;
	}


}