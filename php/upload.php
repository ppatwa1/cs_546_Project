<?php
	include ('../model/TempContact.php');
	require_once ("SQlConnector.php");
	require_once ("config.php");
	session_start();
	
	function readCSV($csvFile){
	$file_handle = fopen($csvFile,'r');
	while(!feof($file_handle)){
		$line_of_text[] = fgetcsv($file_handle,1024);
	}	//end of while loop
	fclose($file_handle);
	return $line_of_text;
}//end of the readCSV function


if (! empty ( $_FILES ["myFile"] )) {
	$myFile = $_FILES ["myFile"];
	
	//print_r($myFile);
	// check for error in file upload process
	if ($myFile ["error"] !== UPLOAD_ERR_OK) {
		echo "<p>An error occured.</p>";
		exit ();
	} // end of if loop
	  
	// check for file size
	
	if ($_FILES ["myFile"] ["size"] > $_POST ["MAX_FILE_SIZE"]) {
		echo "<p>File size exceeds 2MB.</p>";
		exit ();
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
		$name = $parts ["filename"] . "_" . date ('m-d-Y') . time() . "." . $extensionOfFile;
        
        echo "<BR>Filename: ".$name;
		
		// preserve file from temporary directory
		$success = move_uploaded_file ( $myFile ["tmp_name"], UPLOAD_DIR . $name );
		$csvFile = UPLOAD_DIR.$name;
		
		if (! $success) {
			echo "<p>Unable to save file.</p>";
			exit (); 	
		}
		else{
			// set proper permissions on the new file
			chmod ($csvFile, 0644 );
		 	$csv = readCSV($csvFile);//$csv is a array
		 
		 	array_shift($csv);//Remove header from the array
		 	$contacts = array();
		 	foreach ($csv as $rowValues) {
		 		$contact = new TempContact();
		 		$contact->salutaion = $rowValues[0];
		 		$contact->con_first_name = $rowValues[1];
		 		$contact->con_middle_name = $rowValues[2];
		 		$contact->con_last_name = $rowValues[3];
		 		$contact->con_email = $rowValues[4];
		 		$contact->con_phone_no = $rowValues[5];
		 		$contact->con_fax_no = $rowValues[6];
		 		$contact->con_src_con_id = $rowValues[7];
		 		$contact->con_zipcode = $rowValues[8];
		 		$contact->con_country = $rowValues[9];
		 		$contact->con_created_date = $rowValues[10];
		 		array_push($contacts,$contact);
		 	}
            
            
                foreach($contacts as $contact)
                {
                
		 $insertSql = "INSERT INTO contacts_dump(`src_id`, `src_con_id`, `con_first_name`, 
												`con_middle_name`, `con_last_name`, `con_salutation`, `con_phone_no`, 
												`con_fax_no`, `con_country`, `con_zipcode`, `con_email`, 
												`con_created_date`, `con_created_by`,`filename`) 
					VALUES ('".$filename."','".$contact->src_con_id."','".$contact->con_first_name."','".
							$contact->con_middle_name."','".$contact->con_last_name."','".$contact->con_salutation."','".$contact->con_phone_no."','".$contact->con_fax_no."','".$contact->con_country."','".$contact->con_zipcode."','".$contact->con_email."',now(),'".$filename."','".$name."');";
		$result = execSQL($DBResource,$insertSql); 
                    
	}
            
            //Read data from jva_contacts table and store it in a object
            $jva_data = array();
            $jvaQuery = "SELECT * FROM jva_contacts";
            $jva_resultSet= $resultSet = execSQL($DBResource,$jvaQuery);
            if(mysql_num_rows($jva_resultSet)>0)
            {
                while($jva_r = mysql_fetch_row($jva_resultSet))
                {
                    $jva_row = new TempContact();
                    $jva_row->jva_id = $jva_r[0];
                    $jva_row->jva_first_name = $jva_r[1];
                    $jva_row->jva_middle_name = $jva_r[2];
                    $jva_row->jva_last_name = $jva_r[3];
                    $jva_row->jva_salutation = $jva_r[4];
                    $jva_row->jva_phone_no = $jva_r[5];
                    $jva_row->jva_fax_no = $jva_r[6];
                    $jva_row->jva_country = $jva_r[7];
                    $jva_row->jva_zipcode = $jva_r[8];
                    $jva_row->jva_email = $jva_r[9];
                    array_push($jva_data,$jva_row);
                }
            }
            
		//Read data from contacts_dump for the current file that was uploaded for matching
            $contacts_data = array();
            $contactsQuery = "SELECT * FROM contacts_dump where filename = '".$name."'";
            $contacts_resultSet= $resultSet = execSQL($DBResource,$contactsQuery);
            if(mysql_num_rows($contacts_resultSet)>0)
            {
                while($contacts_r = mysql_fetch_row($contacts_resultSet))
                {
                    $contacts_row = new TempContact();
                    $contacts_row->con_t_id = $contacts_r[0];
                    $contacts_row->src_id = $contacts_r[1];
                    $contacts_row->src_con_id = $contacts_r[2];
                    $contacts_row->con_first_name = $contacts_r[3];
                    $contacts_row->con_middle_name = $contacts_r[4];
                    $contacts_row->con_last_name = $contacts_r[5];
                    $contacts_row->con_salutation = $contacts_r[6];
                    $contacts_row->con_phone_no = $contacts_r[7];
                    $contacts_row->con_fax_no = $contacts_r[8];
                    $contacts_row->con_country = $contacts_r[9];
                    $contacts_row->con_zipcode = $contacts_r[10];
                    $contacts_row->con_email = $contacts_r[11];
                    array_push($contacts_data,$contacts_row);
                }           
            }	 	
		 	
	}//end of else loop for success
			
	}  // end of the extension if loop
else {
		echo "Invalid file type or size exceeds the limit";
	}
		
} // end of is file empty loop
?>