var jInsertEditorText = function(tag, field)
{
	var path = tag.replace(/^.+src=\"/, '');

	//Remove the media files root
	path = path.replace(/^images\/?/, ''); //TODO : change here the root if moved
	path = path.replace(/\".+$/, '');

	var fieldData = jInsertFields[field];
	var src = fieldData.url + path;

	if (fieldData.size)
		src += '&size=' + fieldData.size;

	if (fieldData.attrs)
		src += '&attrs=' + fieldData.attrs;

	var fieldObj;
	var previewObj;

	previewObj = jQuery('#' + field + "-preview")[0];
	jQuery('#' + field).val('[IMAGES]' + path);

	if (fieldData.preview)
		previewObj.innerHTML = '<img src="' + src + '" alt=""/>'
	else
		previewObj.innerHTML = path;

};


var jInsertFields = {};