Joomla.orderTable = function()
{
	var table = document.getElementById("sortTable");
	var direction = document.getElementById("directionTable");
	var order = table.options[table.selectedIndex].value;
	var currentListOrder = document.getElementById("filter_order").value;

	if (order != currentListOrder)
		dirn = 'asc';
	else
		dirn = direction.options[direction.selectedIndex].value;
		
	Joomla.tableOrdering(order, dirn, '');
}

Joomla.resetFilters = function()
{
	jQuery('.element-filter').val('');
	jQuery('.element-search').val('');
	Joomla.submitform();
}


// Catch the native function listItemTask
var JoomlaListItemTask = listItemTask;
listItemTask = function(id, task)
{
	var taskParts = task.split('.');

	var controller = '';
	var taskExec = task;
	if (taskParts.length > 1)
	{
		controller = taskParts[0];
		taskExec = taskParts[1];	
	}
	
	switch(taskExec)
	{
		case 'delete':
			if (!window.confirm((PLG_JDOM_ALERT_ASK_BEFORE_REMOVE)))
				return
			break;
			
		case 'trash':
			if (!window.confirm(PLG_JDOM_ALERT_ASK_BEFORE_TRASH))
				return
			break;
	}
	
	return JoomlaListItemTask(id, task);
}