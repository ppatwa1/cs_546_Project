	<?php
	include ('../model/TempContact.php');
	require_once ("SQLConnector.php");
	require_once ("Config.php");
	session_start ();
	
	function readCSV($csvFile) {
		$file_handle = fopen ( $csvFile, 'r' );
		while ( ! feof ( $file_handle ) ) {
			$line_of_text [] = fgetcsv ( $file_handle );
		} // end of while loop
		fclose ( $file_handle );
		return $line_of_text;
	} // end of the readCSV function
	
	function validateContact($contact) {
		
		if (strlen ( trim ( $contact->src_con_id ) ) > 45)
			return false;
		if (strlen ( trim ( $contact->con_first_name ) ) > 100)
			return false;
		if (strlen ( trim ( $contact->con_middle_name ) ) > 25)
			return false;
		if (strlen ( trim ( $contact->con_last_name ) ) > 100)
			return false;
		if (strlen ( trim ( $contact->con_salutation ) ) > 5)
			return false;
		if (strlen ( trim ( $contact->con_phone_no ) ) > 15 || ! preg_match ("/[0-9]/", $contact->con_phone_no ))
			return false;
		if (strlen ( trim ( $contact->con_fax_no ) ) > 15 || ! preg_match ("/[0-9]/", $contact->con_fax_no ))
			return false;
		if (strlen ( trim ( $contact->con_country ) ) > 50)
			return false;
		if (strlen ( trim ( $contact->con_zipcode ) ) > 11 || ! preg_match ("/[0-9]/", $contact->con_zipcode ))
			return false;
		if (strlen ( trim ( $contact->con_email ) ) > 50 || ! filter_var ( $contact->con_email, FILTER_VALIDATE_EMAIL ))
			return false;
		if (strlen ( trim ( $contact->con_created_date ) ) > 8)
			return false;
		
		return true;
	}
	
	if (! empty ( $_FILES ["myFile"] )) {
		$myFile = $_FILES ["myFile"];
		
		// check for error in file upload process
		if ($myFile ["error"] !== UPLOAD_ERR_OK) {
			echo "<p>An error occured.</p>";
			exit ();
		} // end of if loop
		  
		// check for file size
		
		/*
		 * if ($_FILES ["myFile"] ["size"] > $_POST ["MAX_FILE_SIZE"]) {
		 * echo "<p>File size exceeds 2MB.</p>";
		 * exit ();
		 * }
		 */
		
		// ensure a safe filename
		$name = preg_replace ( "/[^A-Z0-9._-]/i", "_", $myFile ["name"] );
		
		// prevent overwriting a existing file
		$i = 0;
		$parts = pathinfo ( $name );
		$filename = $parts ["filename"];
		$extensionOfFile = $parts ['extension'];
		if ($extensionOfFile == 'csv') {
			
			date_default_timezone_set ( "America/New_York" );
			$name = $parts ["filename"] . "_" . date ( 'm-d-Y' ) . time () . "." . $extensionOfFile;
			
			// preserve file from temporary directory
			$success = move_uploaded_file ( $myFile ["tmp_name"], UPLOAD_DIR . $name );
			$csvFile = UPLOAD_DIR . $name;
			
			if (! $success) {
				echo "<p>Unable to save file.</p>";
				exit ();
			} else {
				// set proper permissions on the new file
				chmod ( $csvFile, 0644 );
				$csv = readCSV ( $csvFile ); // $csv is a array
				$headers = array_shift ( $csv );
				if (count ( $headers ) != 11)
					die ( "File Invalid" );
				
				$contacts = array ();
				$recordsInvalid = false;
				foreach ( $csv as $rowValues ) {
					
					if (! empty ( $rowValues )) {
						$contact = new TempContact ();
						$contact->con_salutation = $rowValues [0];
						$contact->con_first_name = $rowValues [1];
						$contact->con_middle_name = $rowValues [2];
						$contact->con_last_name = $rowValues [3];
						$contact->con_email = $rowValues [4];
						$contact->con_phone_no = $rowValues [5];
						$contact->con_fax_no = $rowValues [6];
						$contact->con_src_con_id = $rowValues [7];
						$contact->con_zipcode = $rowValues [8];
						$contact->con_country = $rowValues [9];
						$contact->con_created_date = $rowValues [10];
						if (is_null ( $contact->con_created_date ) || empty($contact->con_created_date))
							$contact->con_created_date = date ( "m/d/y" );
						$contact->src_id = $_SESSION ['username'];
						$contact->con_created_by = $_SESSION ['username'];
						
						if (validateContact ( $contact ))
							array_push ( $contacts, $contact );
						else 
							$recordsInvalid = true;;
					}
				}
				
				if (! empty ( $contacts )) {
					$DBResource = getDBConnection ();
					insertData ( $DBResource, $contacts );
					closeDBConnection ( $DBResource );
					if($recordsInvalid)
						echo "File uploaded successfully (discarding invalid data)";
					else 
						echo "File uploaded successfully.";
				}
				// end of if loop
			} // end of else loop for success
		}  // end of the extension if loop
		else {
			echo "Invalid file type or size exceeds the limit";
		}
	} // end of is file empty loop
	?>