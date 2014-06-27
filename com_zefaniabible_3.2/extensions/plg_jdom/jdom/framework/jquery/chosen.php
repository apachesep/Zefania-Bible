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


class JDomFrameworkJqueryChosen extends JDomFrameworkJquery
{
	protected $lib;
	protected $scriptPath;
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *  @lib		: Array - jQuery UI libraries
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
	}
	
	function build()
	{
		//Chosen should not be used
		if (!$this->useFramework('chosen'))
			return;
		
		//Chosen is already in the core since Joomla 3.0. Load it from the class.
		if ($this->jVersion('3.0'))
		{
			JHtmlFormbehavior::chosen();
			return;
		}

		JDom::_('framework.jquery.ui');

		// Add chosen.jquery.js language strings
		JText::script('JGLOBAL_SELECT_SOME_OPTIONS');
		JText::script('JGLOBAL_SELECT_AN_OPTION');
		JText::script('JGLOBAL_SELECT_NO_RESULTS_MATCH');
			
		
		$this->attachJs[] = 'chosen.jquery.min.js';
		$this->attachCss[] = 'chosen.css';
	}

}