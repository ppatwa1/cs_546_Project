<?php
require_once ('config.php');

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
    die("Error occured while executing the query".mysqli_error($con));
   }
  return $queryRes;
}

?>