Joomla.tableOrdering=function(a,b,c,d)
{
	"undefined"===typeof d&&(d=document.getElementById("adminForm"));
	
	d.filter_order.value=a;
	d.filter_order_Dir.value=b;

	Joomla.submitform(c,d)
};