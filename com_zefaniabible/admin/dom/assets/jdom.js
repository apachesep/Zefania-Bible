/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook       (by Jocelyn HUARD) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo----------------------------------------------------- +
* @version		1.0
* @package		Cook Self Service
* @subpackage	JDom
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


var JDom = new Class(
{
	params: null,
	wrapper: null,

	imagesDir:null,
	root:null,

	initialize: function(wrapper, params)
	{
		this.params = params;
		this.wrapper = wrapper;

		this.init();

		if (this.root)
			this.imagesDir = this.urlBack(this.root, 2) + 'images/';
	},

	/*
	*	Abstract functions
	*/

	init: function()
	{

	},

	render: function()
	{
	},



	urlBack: function(url, backslashes)
	{
		var parts = url.split('/');

		var result = '';
		for(var i = 0 ; i < parts.length - backslashes ; i++)
		{
			result += parts[i] + '/';
		}

		return result;

	}




});




var callback = new Object();

var JDomRequest = new Class(
{

	url:'index.php?tmpl=component',
	request:null,
	post:null,
	dom:null,
	token:null,

	/**
	* MooTools lib - Change initialize function to change framework
	*/
	initialize: function(post, id)
	{


		this.token = parseInt(Math.random() * 9999999999);

		this.setPost(post);
		this.setDom($(id));



		if (this.requiredMooToolsVersion("1.2"))
			this.initMooTools2();
		else
			this.initMooTools1();			//Legacy for Joomla 1.5


	},

	initMooTools1: function()
	{
		var thisp = this;

		this.request = new Ajax(this.url, {

			method: 'post',
			data: thisp.post,

			async:false, //For scripts on domready
			evalScripts:true,
			//evalResponse:true,

			onComplete:function(responseText, responseXML)
			{
				thisp.onSuccess(responseText, responseXML);
			}

		});


	},

	initMooTools2: function()
	{

		var thisp = this;

		this.request = new Request({

			url:this.url,
			method: 'post',


			async:false, //For scripts on domready
			evalScripts:true,
			//evalResponse:true,

			onSuccess:function(responseText, responseXML)
			{
				thisp.onSuccess(responseText, responseXML);
			},

			onTimeout:function()
			{
				thisp.onTimeout();
			},

			onFailure:function(xhr)
			{
				thisp.onFailure(xhr);
			}

		});


	},


	setPost: function(post)
	{
		post.token = this.token;
		this.post = post;
	},

	setDom: function(dom)
	{
		this.dom = dom;
	},

	execute: function()
	{
		if (jDomBase != undefined)
			new Element('img', {'src':jDomBase + "/assets/ajax/images/spinner.gif"}).inject(this.dom);


		if (this.requiredMooToolsVersion("1.2"))
			this.request.send({'data':this.post});
		else
			this.request.request();


	},

	onSuccess: function(responseText, responseXML)
	{
		this.dom.innerHTML = responseText;

		var thisp = this;

		this.dom.addEvent('domready', function()
		{
			if (callback['_' + thisp.token] != undefined)
			{

				var fct = callback['_' + thisp.token];
				fct();
				callback['_' + this.token] = null;
			}

		});

	},

	onTimeout:function()
	{

	},

	onFailure:function(xhr)
	{

	},



	requiredMooToolsVersion: function(required)
	{

		var a = MooTools.version.split('.');
		var b = required.split('.');




		for (var i = 0; i < a.length; ++i) {
			//a[i] = Number(a[i]);

			//Correct the second mootools second num
			//	1.11   <  1.2  !!!
			a[i] = a[i].substr(0,1);   //Limit to only one char
		}
		for (var i = 0; i < b.length; ++i) {
			b[i] = Number(b[i]);
		}
		if (a.length == 2) {
			a[2] = 0;
		}




		if (a[0] > b[0]) return true;
		if (a[0] < b[0]) return false;


		if (a[1] > b[1]) return true;
		if (a[1] < b[1]) return false;


		if (a[2] > b[2]) return true;
		if (a[2] < b[2]) return false;

		return true;
	}


});

var registerCallback = function(token, fct)
{
	callback['_' + token] = fct;
}

var callAjax = function(namespace, id, options)
{
	if (options == undefined)
		options = null;


	var url = namespace.split('.');

	var post = {
			'option':'com_' + url[0],
			'view':url[1],
			'layout':url[2],
			'render':(url[3]?url[3]:'')
		};



	if (options.vars)
	{

		for (var key in options.vars)
		{

			if (key.substr(0, 1) == '$')
				break;
			var value = options.vars[key];

			post[key] = value;
		}

	}


	var request = new JDomRequest(post, id);




	request.execute();

}



var jdomFileUrl = function()
{
	var scripts = document.getElementsByTagName('script');
	var script = scripts[scripts.length - 1].src;
	return script.substring(0, script.lastIndexOf('/')) + '/';

};


var jdomLoad = function(jdomObject)
{
	var serialized = jdomObject.getProperty('rel');
	var params = typeof JSON !='undefined' ?  JSON.parse(serialized) : eval('('+serialized+')');
	var klass = 'JDom' + params.asset.substr(0,1).toUpperCase() + params.asset.substr(1);

	if (window[klass] == undefined)
		return;

	var jdom = new window[klass](jdomObject, params);

	if (jdom.getInstance != undefined)
		jdomClass = jdom.getInstance();
	else
		jdomClass = jdom;

	jdomClass.render();
}

window.addEvent('domready', function()
{
	$$('.jdom').each(function(jdomObject)
	{
		jdomLoad(jdomObject);
	});
});