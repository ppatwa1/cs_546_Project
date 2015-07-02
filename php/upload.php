<?php

include('../model/TempContact.php');
require_once("SQlConnector.php");
require_once("config.php");

function readCSV($csvFile){
	$file_handle = fopen($csvFile,'r');
	while(!feof($file_handle)){
		$line_of_text[] = fgetcsv($file_handle,1024);
	}	//end of while loop
	fclose($file_handle);
	return $line_of_text;
}//end of the readCSV function


function insertData($conn,$contacts,$filename){
	foreach($contacts as $contact){
		$insertSql = "INSERT INTO contacts_dump(`src_id`, `src_con_id`, `con_first_name`, 
												`con_middle_name`, `con_last_name`, `con_salutation`, `con_phone_no`, 
												`con_fax_no`, `con_country`, `con_zipcode`, `con_email`, 
												`con_created_date`, `con_created_by`) 
					VALUES ('".$filename."','".$contact->src_con_id."','".$contact->con_first_name."','".
							$contact->con_middle_name."','".$contact->con_last_name."','".$contact->con_salutation."','".$contact->con_phone_no."','".
							$contact->con_fax_no."','".$contact->con_country."','".$contact->con_zipcode."','".$contact->con_email."',
							STR_TO_DATE('$contact->con_created_date', '%c/%d/%y'),'test');";
		if(execSQL($conn, $insertSql) === FALSE)
			die("Error: " . $insertSql . "<br>" . $conn->error);
	}
	
}
 

if (! empty ( $_FILES ["myFile"] )) {
	$myFile = $_FILES ["myFile"];
	
	//print_r($myFile);
	// check for error in file upload process
	if ($myFile ["error"] !== UPLOAD_ERR_OK) {
		echo "<p>An error occured.</p>";
		exit ();
	} // end of if loop
	  
	// check for file size
	
	if (($_FILES ["myFile"] ["size"]/1024*1024) > $_POST ["MAX_FILE_SIZE"]) {
		die("<p>File exceeded in size<p>");
	}
	
	// ensure a safe filename
	$name = preg_replace ( "/[^A-Z0-9._-]/i", "_", $myFile ["name"] );
	
	// prevent overwriting a existing file
	$i = 0;
	$parts = pathinfo ( $name );
	$filename = $parts ["filename"];
	$extensionOfFile = $parts ['extension'];
	if ($extensionOfFile == 'csv') {
		
		date_default_timezone_set ( "America/New_York" );
		$name = $parts ["filename"] . "_" . time () . "." . $extensionOfFile;
		
		// preserve file from temporary directory
		$success = move_uploaded_file ( $myFile ["tmp_name"], UPLOAD_DIR . $name );
		$csvFile = UPLOAD_DIR.$name;
		
		if (! $success) {
			die("<p>Unable to save file.</p>"); 	
		}
		else{
			// set proper permissions on the new file
			chmod ($csvFile, 0644 );
		 	$csv = readCSV($csvFile);//$csv is a array
		 
		 	array_shift($csv);//Remove header from the array
		 	$contacts = array();
		 	foreach ($csv as $rowValues) {
		 		$contact = new TempContact();
		 		$contact->src_con_id = $rowValues[0];
		 		$contact->con_first_name = $rowValues[1];
		 		$contact->con_middle_name = $rowValues[2];
		 		$contact->con_last_name = $rowValues[3];
		 		$contact->con_salutation = $rowValues[4];
		 		$contact->con_phone_no = $rowValues[5];
		 		$contact->con_fax_no = $rowValues[6];
		 		$contact->con_country = $rowValues[7];
		 		$contact->con_zipcode = $rowValues[8];
		 		$contact->con_email = $rowValues[9];
		 		$contact->con_created_date = $rowValues[10];
		 		array_push($contacts,$contact);
		 	}
		 	
		 	$conn = getDBConnection();
		 	if(!$conn){
		 		die("unable to connect to the database");
		 	}
		 	else{
		 		insertData($conn,$contacts,$filename);
		 	}
		 	
	}//end of else loop for success
			
	}  // end of the extension if loop
	else {
		echo "Invalid file type or size exceeds the limit";
	}
	

	
} // end of is file empty loop
?>