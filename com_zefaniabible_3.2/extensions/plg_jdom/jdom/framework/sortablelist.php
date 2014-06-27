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

class JDomFrameworkSortablelist extends JDomFramework
{
	
	var $assetName = 'sortablelist';
	
	var $attachJs = array();
	var $attachCss = array();
	
	
	var $formId;
	var $listDirn;
	var $listOrder;
	var $ctrl;
	var $orderingKey;
	var $saveOrderingUrl;
	var $nestedList;
	var $proceedSaveOrderButton;
	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->arg('domId'	, null, $args);
		$this->arg('formId'	, null, $args);
		$this->arg('listDirn'	, null, $args);
		$this->arg('listOrder'	, null, $args);
		$this->arg('ctrl'	, null, $args);
		$this->arg('orderingKey'	, null, $args, 'a.ordering');
		$this->arg('saveOrderingUrl'	, null, $args);
		$this->arg('nestedList'	, null, $args, false);
		$this->arg('proceedSaveOrderButton', null, $args, false);
		
		$this->enabled = ((!isset($this->listOrder)) || (($this->listOrder == $this->orderingKey) && ($this->listDirn != 'desc')));


		if (!$this->saveOrderingUrl && $this->ctrl)
		{
			$this->saveOrderingUrl = JDom::getRoute(array(
				'task' => $this->ctrl .'.saveOrderAjax',
				'option' => $this->getExtension(),
				'view' => '',
				'layout' => '',
				'tmpl' => 'component'
			
			));
		}
		
		if (!$this->jVersion('3.0'))
		{
			$this->attachJs = 'sortablelist.js';
			$this->attachCss = 'sortablelist.css';
		}

	}
	
	public function build()
	{
		
		//Trick to avoid issue when saveorder button is not available
		if ($this->proceedSaveOrderButton)
			return '<span class="saveorder"></span>';
	}
	public function buildJs()
	{
		if ($this->jVersion('3.0'))
		{
			if ($this->enabled && $this->domId)
				JHtml::_('sortablelist.sortable', $this->domId, $this->formId, strtolower($this->listDirn), $this->saveOrderingUrl, $this->proceedSaveOrderButton);
			else if ($this->proceedSaveOrderButton)
				$this->buildJsSaveButton();  //JDom only create the saving button
			return;	
		}

		//Legacy
		JDom::_('framework.jquery.ui', array(
			'lib' => 'sortable'
		));
		
		
		$this->buildJsSortables();
		$this->buildJsSaveButton();
	}

	protected function buildJsSortables()
	{
		if (!$this->enabled || !$this->domId)
			return;
				
		
		// Attach sortable to document
		$js = "
			(function ($){
				$(document).ready(function (){
					var sortableList = new $.JSortableList('#" . $this->domId . " tbody','" . $this->formId . "','" . $this->listDirn . "' , '" . $this->saveOrderingUrl . "','','" . $this->nestedList . "');
				});
			})(jQuery);
			";			
	
		
		$this->addScriptInLine($js, true);
		
	}

	protected function buildJsSaveButton()
	{
		if (!$this->proceedSaveOrderButton)
			return;
			
		$js =
			"var saveOrderButton = $('.saveorder');
			saveOrderButton.css({'opacity':'0.2', 'cursor':'default'}).attr('onclick','return false;');
			var oldOrderingValue = '';
			$('.text-area-order').focus(function ()
			{
				oldOrderingValue = $(this).attr('value');
			})
			.keyup(function (){
				var newOrderingValue = $(this).attr('value');
				if (oldOrderingValue != newOrderingValue)
				{
					saveOrderButton.css({'opacity':'1', 'cursor':'pointer'}).removeAttr('onclick')
				}
			});";
			
		$this->addScriptInLine($js, true);
		
		
	}
}