 <?php
	
	require_once("../php/ajax_table.class.php");
	$obj = new ajax_table();
	$records = $obj->getRecords();
?>
<!DOCTYPE">
<html>
 <head>
  <title>Contacts Form</title>
  <script>
	 // Column names must be identical to the actual column names in the database, if you dont want to reveal the column names, you can map them with the different names at the server side.
	 var columns = new Array("con_salutation","con_first_name","con_middle_name","con_last_name","con_phone_no","con_fax_no","con_country","con_zipcode","con_email");
	 var placeholder = new Array("Salutation","First Name","Middle Name","Last Name", "Phone No", "Fax No", "Country", "Zip Code", "Email");
	 var inputType = new Array("text","text","text","text","text","text","text","text","text");
	 var table = "tableDemo";
	 
	 // Set button class names 
	 var savebutton = "ajaxSave";
	 var deletebutton = "ajaxDelete";
	 var editbutton = "ajaxEdit";
	 var updatebutton = "ajaxUpdate";
	 var cancelbutton = "cancel";
	 
	 var saveImage = "images/save.png"
	 var editImage = "images/edit.png"
	 var deleteImage = "images/remove.png"
	 var cancelImage = "images/back.png"
	 var updateImage = "images/save.png"

	 // Set highlight animation delay (higher the value longer will be the animation)
	 var saveAnimationDelay = 3000; 
	 var deleteAnimationDelay = 1000;
	  
	 // 2 effects available available 1) slide 2) flash
	 var effect = "flash"; 
  
  </script>
  <script src="../js/jquery-1.11.0.min.js"></script>	
  <script src="../js/jquery-ui.js"></script>	
  <script src="../js/contactsScript.js"></script>	
  <link rel="stylesheet" href="../css/manualTemplate.css">
 </head>
 <body>
 	<div class="container">
 		<div class="container_upload"/>
		<div class="contr"><h2>Please fill the contact details</h2></div>
	<table border="0" class="tableDemo bordered">
		<tr class="ajaxTitle">
			<th width="2%">Salutation</th>
			<th width="15%">First Name</th>
			<th width="9%">Middle Name</th>
			<th width="15%">Last Name</th>
			<th width="10%">Phone No</th>
			<th width="10%">Fax No</th>
			<th width="7%">Country</th>
			<th width="7%">Zip Code</th>
			<th width="25%">Email</th>
		</tr>
		<?php
		if(count($records)){
		 $i = 1;	
		 foreach($records as $key=>$eachRecord){
		?>
		<tr id="<?php echo$eachRecord['con_t_id']; ?>">
			<td class="salution"><?php echo $eachRecord['con_salutation'];?></td>
			<td class="fname"><?php echo $eachRecord['con_first_name'];?></td>
			<td class="mname"><?php echo $eachRecord['con_middle_name'];?></td>
			<td class="lname"><?php echo $eachRecord['con_last_name'];?></td>
			<td class="phone"><?php echo $eachRecord['con_phone_no'];?></td>
			<td class="fax"><?php echo $eachRecord['con_fax_no'];?></td>
			<td class="country"><?php echo $eachRecord['con_country'];?></td>
			<td class="zipcode"><?php echo $eachRecord['con_zipcode'];?></td>
			<td class="email"><?php echo $eachRecord['con_email'];?></td>
		</tr>
		<?php }
		}
		?>
	</table> 
	<div id="content">
		<input class="leftNav" type="button" onclick="addNewRow()" value="Add new row">
		<input class="rightPara" type="button" onclick="uploadContacts()" value="Save Contacts">
	</div> 	
	</div>
	</div>
 </body>
</html>
 