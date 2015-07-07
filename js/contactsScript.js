 	
// init variables
var trcopy;
var editing = 0;
var tdediting = 0;
var editingtrid = 0;
var editingtdcol = 0;

$(document).ready(function(){
	// init table
	addNewRow();
});

addNewRow = function(){
	blankrow = '<tr valign="top" class="inputform">';
	for(i=0;i<columns.length;i++){
		// Create input element as per the definition
		input = createInput(i,"");
		blankrow += '<td class="ajaxReq">'+input+'</td>';
	}
	
	// append blank row at the end of table
	$("."+table).append(blankrow);
}

deleteSelectedRow = function(){
	var table = document.getElementById("contactsTable").tBodies[0];
	var rowCount = table.rows.length;
	alert(rowCount);
	// var i=1 to start after header
	for(var i=1; i<rowCount; i++) {
		var row = table.rows[i];
		// index of td contain checkbox is 11
		var chkbox = row.cells[11].getElementsByTagName('input')[0];
		if('checkbox' == chkbox.type && true == chkbox.checked) {
			table.deleteRow(i);
		 }
	}
}

uploadContacts = function(){
	//upload the contacts from here
	var check = document.getElementById();
}

createInput = function(i,str){
	str = typeof str !== 'undefined' ? str : null;
	 
	if(inputType[i] == "checkbox"){
		input = '<input type='+inputType[i]+' name='+columns[i]+' >';
	}
	else if(columns[i] == "con_id[]" || columns[i] == "con_last_name[]" || columns[i] == "con_country[]" || columns[i] == "con_email[]")
		input = '<input required="required" type='+inputType[i]+' name='+columns[i]+' placeholder="'+placeholder[i]+'" maxlength="'+inputLength[i]+'">';
	else
		input = '<input type='+inputType[i]+' name='+columns[i]+' placeholder="'+placeholder[i]+'" maxlength="'+inputLength[i]+'">';
	
	return input;
}

function selectAll(){
	var allcheckbox = document.getElementById("selectall");
	var table = document.getElementById("contactsTable").tBodies[0];
	var rowCount = table.rows.length;
	alert(rowCount);
	for(var i=1; i<rowCount; i++) {
		var row = table.rows[i];
		// index of td contain checkbox is 11
		var chkbox = row.cells[11].getElementsByTagName('input')[0];
		if(allcheckbox.checked) 
			chkbox.checked = true;
		else
			chkbox.checked = false;
	}
}