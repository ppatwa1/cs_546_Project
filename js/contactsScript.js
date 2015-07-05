 	
// init variables
var trcopy;
var editing = 0;
var tdediting = 0;
var editingtrid = 0;
var editingtdcol = 0;
var inputs = ':checked,:selected,:text,textarea';

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

	// var i=1 to start after header
	for(var i=1; i<rowCount; i++) {
		var row = table.rows[i];
		// index of td contain checkbox is 9
		var chkbox = row.cells[9].getElementsByTagName('input')[0];
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
	if(inputType[i] == "text"){
		input = '<input type='+inputType[i]+' name='+columns[i]+' placeholder="'+placeholder[i]+'" value='+str+' >';
	}else if(inputType[i] == "textarea"){
		input = '<textarea name='+columns[i]+' placeholder="'+placeholder[i]+'">'+str+'</textarea>';
	}else if(inputType[i] == "checkbox"){
		input = '<input type='+inputType[i]+' name='+columns[i]+' value='+str+' >';
		
	}
	return input;
}

/*
ajax = function (params,action){
	$.ajax({
		type: "POST", 
		url: "ajax.php", 
		data : params+"&action="+action,
		dataType: "json",
		success: function(response){
		  switch(action){
			case "save":
				var seclastRow = $("."+table+" tr").length;
				if(response.success == 1){
					var html = "";
					
					html += "<td>"+parseInt(seclastRow - 1)+"</td>";
					for(i=0;i<columns.length;i++){
						html +='<td class="'+columns[i]+'">'+response[columns[i]]+'</td>';
					}
					html += '<td><a href="javascript:;" id="'+response["id"]+'" class="ajaxEdit"><img src="'+editImage+'"></a> <a href="javascript:;" id="'+response["id"]+'" class="'+deletebutton+'"><img src="'+deleteImage+'"></a></td>';
					// Append new row as a second last row of a table
					$("."+table+" tr").last().before('<tr id="'+response.id+'">'+html+'</tr>');
					
					if(effect == "slide"){
						// Little hack to animate TR element smoothly, wrap it in div and replace then again replace with td and tr's ;)
						$("."+table+" tr:nth-child("+seclastRow+")").find('td')
						 .wrapInner('<div style="display: none;" />')
						 .parent()
						 .find('td > div')
						 .slideDown(700, function(){
							  var $set = $(this);
							  $set.replaceWith($set.contents());
						});
					}
					else if(effect == "flash"){
					   $("."+table+" tr:nth-child("+seclastRow+")").effect("highlight",{color: '#acfdaa'},100);
					}else
					   $("."+table+" tr:nth-child("+seclastRow+")").effect("highlight",{color: '#acfdaa'},1000);

					// Blank input fields
					$(document).find("."+table).find(inputs).filter(function() {
						// check if input element is blank ??
						this.value = "";
						$(this).removeClass("success").removeClass("error");
					});
				}
			break;
			case "del":
				var seclastRow = $("."+table+" tr").length;
				if(response.success == 1){
					$("."+table+" tr[id='"+response.id+"']").effect("highlight",{color: '#f4667b'},500,function(){
						$("."+table+" tr[id='"+response.id+"']").remove();
					});
				}
			break;
			case "update":
				$("."+cancelbutton).trigger("click");
				for(i=0;i<columns.length;i++){
					$("tr[id='"+response.id+"'] td[class='"+columns[i]+"']").html(response[columns[i]]);
				}
			break;
			case "updatetd":
				//$("."+cancelbutton).trigger("click");
				var newval = $("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").find(inputs).val();
				
				//alert($("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html());
				$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html(newval);

				//$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html(newval);
				// remove editing flag
				tdediting = 0;
				$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").effect("highlight",{color: '#acfdaa'},1000);
			break;
		  }
		},
		error: function(){
			alert("Unexpected error, Please try again");
		}
	});
}
*/