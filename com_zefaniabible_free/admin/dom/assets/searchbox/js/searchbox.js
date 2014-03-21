/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook       (by Jocelyn HUARD) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo----------------------------------------------------- +
* @version		1.5
* @package		JDom
* @subpackage	Searchbox
* @copyright	Copyright 2011 - 100% vitamin
* @author		100% Vitamin - www.cpcv.net - info@cpcv.net
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

var JDomSearchbox = new Class(
{
	Extends: JDom,

	domInput:null,
	domSearch:null,
	domEmpty:null,
	domLabel:null,

	render: function()
	{
		var thisp = this;

		this.domInput = $(this.params.name);
		this.domSearch = $('search_input_' + this.params.name)

		//Specifics
		this.domEmpty = $('search_empty_' + this.params.name);
		this.domLabel = $('search_label_' + this.params.name);

		var fctChange = function()
		{

			if (thisp.domEmpty)
			{
				if ((thisp.domSearch.value.trim() != thisp.domLabel.value.trim())
				&& (thisp.domSearch.value.trim() != ""))
					thisp.domEmpty.value = 0;


				if ((thisp.domEmpty.value == 1))
					thisp.domInput.value = '';
				else
					thisp.domInput.value = thisp.domSearch.value;
			}
			else
				thisp.domInput.value = thisp.domSearch.value;

		};


		this.domSearch.addEvent('keypress', function()
		{
			fctChange();
		});

		this.domSearch.addEvent('change', function()
		{
			fctChange();
		});


		this.emptySearchText();


	},

	emptySearchText: function()
	{
		if (!this.domEmpty)
			return;

		var thisp = this;


		var fctRefreshLabel = function()
		{
			if (thisp.domEmpty.value == 1)
			{
				thisp.domSearch.value = "";
				thisp.domSearch.removeClass('search_default');
			}
		};

		this.domSearch.addEvent('mousedown', function()
		{
			fctRefreshLabel();
		});

		this.domSearch.addEvent('blur', function()
		{
			if (thisp.domInput.value.trim() == "")
			{
				thisp.domSearch.value = thisp.domLabel.value;
				thisp.domEmpty.value = 1;
				thisp.domSearch.addClass('search_default');

			}
			else
			{
				thisp.domEmpty.value = 0;
			}
		});


	},
});