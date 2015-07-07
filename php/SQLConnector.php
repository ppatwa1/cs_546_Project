	<?php
	require_once ('Config.php');
	
	/* Function used to establish connection to the Database */
	function getDBConnection()
	{
		$con = mysqli_connect ( DB_HOST, DB_USER, DB_PASSWORD, DB_DB );
		
		// Check connection
		if (mysqli_connect_errno()) {
			die("Failed to connect to MySQL: ".mysqli_connect_error());
		}
		return $con;
	}
	
	/* Function to query the database and does not have any output*/
	function execSQL($conn, $query)
	{ 	
	  /* Querying the database with the query defined in $query */
	  $queryRes = mysqli_query($conn,$query);
	   if (!$queryRes)
	   {
	    die("Error occured while executing the query".mysqli_error($conn).$query);
	   }
	  return $queryRes;
	}
	
	function insertData($conn,$contacts){
		$insertSql = "";
			foreach ($contacts as $contact){
				$insertSql .= "INSERT INTO contacts_dump(`src_id`, `src_con_id`, `con_first_name`,
													`con_middle_name`, `con_last_name`, `con_salutation`, `con_phone_no`,
													`con_fax_no`, `con_country`, `con_zipcode`, `con_email`,
													`con_created_date`, `con_created_by`)
						VALUES ('".$contact->src_id."','".$contact->src_con_id."','".$contact->con_first_name."','".
							$contact->con_middle_name."','".$contact->con_last_name."','".$contact->con_salutation."','".$contact->con_phone_no."','".$contact->con_fax_no."','
									".$contact->con_country."','".$contact->con_zipcode."','".$contact->con_email."',STR_TO_DATE('$contact->con_created_date', '%c/%d/%y'),'".$contact->con_created_by."');";
	} 
	//echo "Insert query : ".$insertSql;
	if($conn->multi_query($insertSql) === FALSE){
		die("Error occured while executing the query".mysqli_error($conn));
	}
	
	function closeDBConnection($conn){
		mysqli_close($conn);
	}
	}
	
 
	?>