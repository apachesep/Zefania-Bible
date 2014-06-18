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

class JDomHtmlFlyEnum extends JDomHtmlFly
{
	var $list;
	var $listKey;
	var $labelKey;


	private $text;
	private $images;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 * 	@list		: Possibles values list (array of objects)
	 * 	@listKey	: ID key name of the list
	 * 	@labelKey	: Caption key name of the list
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('list'		, 5, $args);
		$this->arg('listKey'	, 6, $args, 'id');
		$this->arg('labelKey'	, 7, $args);

	}

	function build()
	{
		$html = "";

		if (isset($this->dataValue) && isset($this->list[$this->dataValue][$this->labelKey]))
			$html = $this->list[$this->dataValue][$this->labelKey];

		//Embed in a bootstrap label
		$html = JDom::_('html.label', array(
			'content' => $html
		));

		return $html;
	}

}