<?php
require_once ("../php/SQlConnector.php");

	$conn = DBConnect ();
	if (! $conn) {
		die ( "unable to connect to the database" );
	} 
	
	else {
		$encryptedPassword = password_hash ( 'testpassword1', PASSWORD_DEFAULT );
		$query = "Insert into login_details(user_type,username,password) 
		values('client','IRS','$encryptedPassword')";
		execSQL ( $conn, $query );
		
		$encryptedPassword = password_hash ( 'testpassword2', PASSWORD_DEFAULT );
		$query = "Insert into login_details(user_type,username,password)
		values('client','BOA','$encryptedPassword')";
		execSQL ( $conn, $query );
		
		$encryptedPassword = password_hash ( 'testpassword3', PASSWORD_DEFAULT );
		$query = "Insert into login_details(user_type,username,password)
		values('client','USCIS','$encryptedPassword')";
		execSQL ( $conn, $query );
		
		$encryptedPassword = password_hash ( 'testpassword4', PASSWORD_DEFAULT );
		$query = "Insert into login_details(user_type,username,password)
		values('admin','admin','$encryptedPassword')";
		execSQL ( $conn, $query );
	}
	
?>