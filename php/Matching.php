  <?php
include ('../model/TempContact.php');
require_once ("SQLConnector.php");
require_once ("Config.php");
session_start ();

	function readCSV($csvFile) {
		$file_handle = fopen ( $csvFile, 'r' );
		while ( ! feof ( $file_handle ) ) {
			$line_of_text [] = fgetcsv ($file_handle);
		} // end of while loop
		fclose ( $file_handle );
		return $line_of_text;
	} // end of the readCSV function
	
	function validateContact($contact){
		
	}//end of validateContact functiona
	
	function sanityCheck($string,$type,$length){
		$type = 'is_'.$type;
	
	
	}//end of the function sanityCheck
	
	
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
			$dt = date('m-d-Y').time();
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
				
				array_shift ( $csv ); // Remove header from the array
				$contacts = array ();
				
				foreach ( $csv as $rowValues ) {
					if($rowValues [6] !== null && $rowValues [3] !== null && $rowValues [4] !== null){
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
					$contact->src_id = $_SESSION ['username'];
					$contact->con_created_by = $_SESSION ['username'];
					//validateContact($contact);//check length and datatype of each field
					array_push ( $contacts, $contact );
				}//end of if loop
				 
				}
				
				$DBResource = getDBConnection ();
				insertData ($DBResource, $contacts);
				closeDBConnection($DBResource);
				
			 	
			            //Read data from jva_contacts table and store it in a object
            $jva_data = array();
            $jvaQuery = "SELECT * FROM jva_contacts";
            $DBResource = getDBConnection ();
            $jva_resultSet = $resultSet = execSQL($DBResource,$jvaQuery);
            if(mysqli_num_rows($jva_resultSet)>0)
            {
                while($jva_r = mysqli_fetch_row($jva_resultSet))
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
            }closeDBConnection($DBResource);
            
		//Read data from contacts_dump for the current file that was uploaded for matching
            $contacts_data = array();
            $contactsQuery = "SELECT * FROM contacts_dump where created_dttm = '".$dt."'";
            $DBResource = getDBConnection ();
            $contacts_resultSet = $resultSet = execSQL($DBResource,$contactsQuery);
            if(mysqli_num_rows($contacts_resultSet)>0)
            {
                while($contacts_r = mysqli_fetch_row($contacts_resultSet))
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
            }	closeDBConnection($DBResource);
            
           //Fetching key datapoints for comparison 
            $keypointsQuery = "select field_name from key_datapoints";
            $DBResource = getDBConnection ();
            $keypointsResult = execSQL($DBResource,$keypointsQuery);
            if(mysqli_num_rows($keypointsResult)>0)
            {
                $count= mysqli_num_rows($keypointsResult);
                echo "<BR>Key Count: ".$count;
                $i=0;
                    while($key=mysqli_fetch_row($keypointsResult))
                    {
                        if($i<$count)
                        {
                            $keypoints[$i]=$key[0];
                            $i++;
                        }
                    }
                
            }closeDBConnection($DBResource);
            
            function superExplode ($str, $sep)
            {
                $i = 0;
                $arr[$i++] = strtok($str, $sep);
                while ($token = strtok($sep))
                $arr[$i++] = $token;
                return $arr;
            }
            
            
            $condition = "if( ";
                    for($i=0;$i<count($keypoints);$i++)
                    {
                        if($i == (count($keypoints)-1))
                        {
                            $condition = $condition." '".$keypoints[$i]."' ";
                        }
                        else
                        {
                            $condition = $condition." '".$keypoints[$i]."' && ";
                        }
                    }
                    
                    $condition = $condition." )";
                    
                    echo "<BR>Condition is: ".$condition;
            
            
            
           //To compare each record in jva_contacts with all records in the file
           foreach($jva_data as $jva_row)
            {
                foreach($contacts_data as $contacts_row)
                {
                    $c_last=$contacts_row->con_last_name;
                    $j_last=$jva_row->jva_last_name;
                    $phone = $jva_row->jva_phone_no;
                    $j_phone = str_replace("-","",$phone);
                    $jva_p = substr($j_phone,-10);
                    echo "<BR>Phone no: ".$jva_p;
                    $sep = " -";
                    $c_last = superExplode($c_last,$sep);
                   // $j_first = superExplode($j_first,$sep);
                    /*for($i=0;$i<count($j_last);$i++)
                    {
                        echo "<BR>Name: ".$j_last[$i];
                    }
                    
                    $src_con= $contacts_row->src_con_id;
                    echo "<BR>Source contact id: ".$src_con;
                    
                    echo "<BR>Jva last: ".$jva_row->jva_last_name." Con_last: ".$contacts_row->con_last_name;
                    echo "<BR>Jva country: ".$jva_row->jva_country." Con_country: ".$contacts_row->con_country;
                    echo "<BR>Jva email: ".$jva_row->jva_email." Con_last: ".$contacts_row->con_email; */
                    $arr_size = count($c_last);
                    $cond ="";
                    for($i=0;$i<$arr_size;$i++)
                    {
                               if($i<$arr_size-1)
                               {
                                 $cond=  $cond." strpos(".$j_last.",".$c_last[$i].") !== FALSE ||";
                               }
                               else
                               {
                                  $cond=$cond." strpos(".$j_last.",".$c_last[$i].") !== FALSE";
                               }
                    }
                    
                    echo "<BR>Condition: ".$cond;
                    
                    $DBResource = getDBConnection ();
                    echo "connection successfull";
                    if(($jva_row->jva_last_name == $contacts_row->con_last_name) && ($jva_row->jva_country == $contacts_row->con_country)  && ($jva_row->jva_email == $contacts_row->con_email))
                    {
                    	//$DBResource = getDBConnection ();
                        $nQuery = "INSERT INTO notifications(src_id,src_con_id,jva_id,match_case,pending_notification) values('".$contacts_row->src_id."','".$contacts_row->src_con_id."',".$jva_row->jva_id.",'Perfect',1)";
                      	
                        //echo "connection successful";
                        $resultSet = execSQL($DBResource,$nQuery);
                        //closeDBConnection($DBResource);
                    }
                   else if(($jva_row->jva_country == $contacts_row->con_country)  && ($jva_row->jva_email == $contacts_row->con_email))
                    {
                       if($cond)
                       {
                       	//$DBResource = getDBConnection ();
                             $nQuery = "INSERT INTO notifications(src_id,src_con_id,jva_id,match_case,pending_notification) values('".$contacts_row->src_id."','".$contacts_row->src_con_id."',".$jva_row->jva_id.",'Partial',1)";
                          //   echo "connection successful";
                             $resultSet = execSQL($DBResource,$nQuery);
                            // closeDBConnection($DBResource);
                       }
                        
                    }
                    else
                    {
                    	//$DBResource = getDBConnection ();
                        $nQuery = "INSERT INTO notifications(src_id,src_con_id,jva_id,match_case,pending_notification) values('".$contacts_row->src_id."','".$contacts_row->src_con_id."',0,'New',1)";
                        //echo "connection successful";
                        $resultSet = execSQL($DBResource,$nQuery);
                        //closeDBConnection($DBResource);
                    }
                    closeDBConnection($DBResource);
                    
                }//end of inner for each loop
            }//end of outer for each loop
				 
			} // end of else loop for success
		}  // end of the extension if loop
	else {
			echo "Invalid file type or size exceeds the limit";
		}
	} // end of is file empty loop
?>