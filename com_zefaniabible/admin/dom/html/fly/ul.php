<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  JDom Class - Cook librarie    (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.0.0
* @package		Cook
* @subpackage	JDom
* @license		GNU General Public License
* @author		100% Vitamin - Jocelyn HUARD
*
*	-> You can reuse this framework for all purposes. Keep this signature. <-
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFlyUl extends JDomHtmlFly
{
	var $level = 3;			//Namespace position
	var $last = true;

	var $list;
	var $displayKeys;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 *	@list		: List of values
	 *	@displayKeys: Display key names in this list
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('list'	, null, $args);
		$this->arg('displayKeys'	, null, $args);

	}

	function build()
	{
		if (!$list = $this->list)
			return;

		if (!$this->displayKeys)
			return;

		$html = "<ul>";



		foreach($list as $item)
		{
			$html .= "<li>";

			$html .= $this->buildItem($item);

			$html .= "</li>";
		}

		$html .= "</ul>";
		return $html;
	}


	function buildItem($item)
	{
		$li = "";
		$displayKeys = $this->displayKeys;
		if (!is_array($displayKeys))
		{
			$displayKeys = explode(",", $displayKeys);
		}

		switch (count($displayKeys))
		{
			case 0:
				return;
				break;

			case 1:
				$k = trim($displayKeys[0]);
				$li .= $item->$k;

				break;

			default:
				$li .= "<ul>";
				foreach($displayKeys as $displayKey)
				{
					$k = trim($displayKey);
					if (!$k)
						continue;
					$li .= "<li>";
					$li .= $item->$k;
					$li .= "</li>";

				}
				$li .= "</ul>";
				break;
		}

		return $li;

	}

}