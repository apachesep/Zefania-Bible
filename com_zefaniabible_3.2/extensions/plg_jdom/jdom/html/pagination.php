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

class JDomHtmlPagination extends JDomHtml
{
	var $pagination;
	var $showLimit;
	var $showCounter;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 * 	@pagination : Joomla pagination object
	 *	@showLimit	: show the selectbox to choose how many elements per page
	 *	@showCounter: show the current number of page and the total (ie: Page 1 of 4)
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('pagination'			, 2, $args);
		$this->arg('showLimit'			, 3, $args, true);
		$this->arg('showCounter'		, 4, $args, true);

	}

	function build()
	{
		$pagination = $this->pagination;

		$app = JFactory::getApplication();

		$list = array();		//ISSET() for warning prevention
		$list['prefix']			= (isset($pagination->prefix)?$pagination->prefix:null);
		$list['limit']			= $pagination->limit;
		$list['limitstart']		= $pagination->limitstart;
		$list['total']			= $pagination->total;
		$list['limitfield']		= $pagination->getLimitBox();
		$list['pagescounter']	= $pagination->getPagesCounter();
		$list['pageslinks']		= $pagination->getPagesLinks();

		$chromePath	= JPATH_THEMES . '/' . $app->getTemplate() . '/html/pagination.php';
		if (file_exists($chromePath))
		{
			require_once $chromePath;
			if (function_exists('pagination_list_footer')) {
				return pagination_list_footer($list);
			}
		}

		$html = "<div class=\"list-footer-pagination\">\n";

		if ($this->showLimit)
		{
			$langDisplayNum = $this->JText('JGLOBAL_DISPLAY_NUM');			
			$html .= "\n<div class=\"limit\">". $langDisplayNum .$list['limitfield']."</div>";
		}

		$html .= $list['pageslinks'];

		if ($this->showCounter)
			$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";

		$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart\" value=\"".$list['limitstart']."\" />";
		$html .= "\n</div>";

		return $html;
	}

}