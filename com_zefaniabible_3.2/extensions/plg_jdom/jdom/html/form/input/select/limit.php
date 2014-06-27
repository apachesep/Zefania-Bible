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


class JDomHtmlFormInputSelectLimit extends JDomHtmlFormInputSelect
{
	protected $pagination;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *  @pagination : Joomla pagination
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('pagination', 	null, $args);
	}

	function build()
	{
		if (!$pagination = $this->pagination)
			return "<strong>Error</strong> : Pagination not found";
		
		
		$app = JFactory::getApplication();
		$limits = array();

		$list = array();
		// Make the option list.
		for ($i = 5; $i <= 30; $i += 5)
		{
			$list[] = array(
				'value' => (string)$i,
				'text' => $i
			);
		}

		$list[] = array('value' => '50','text' =>  JText::_('J50'));
		$list[] = array('value' => '100','text' =>  JText::_('J100'));
		$list[] = array('value' => '0','text' =>  JText::_('JALL'));
				


		$onChange = 'Joomla.submitform();';		
		if (!$app->isAdmin())
			$onChange = 'this.form.submit();';

		$dataValue = (string)($pagination->get('viewall') ? 0 : $pagination->get('limit'));

		$html = JDom::_('html.form.input.select.combo', array_merge($this->options, array(
			'domClass' => 'input-mini ' . $this->options['domClass'],
			'size' => '1',
			'domId' => $pagination->prefix . 'limit',
			'selectors' => array(
				'onchange' => $onChange
			),
			'list' => $list,
			'listKey' => 'value',
			'dataValue' => (string)$dataValue,
		
		)));
		
		
		
		return $html;
	}
}