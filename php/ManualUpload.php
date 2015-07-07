<?php
session_start ();

require_once ("../model/TempContact.php");

require_once ("Config.php");
require_once ("SQLConnector.php");
require_once ("Matching.php");

$contacts = array ();

$conid = $_POST ['con_id'];
$salutation = $_POST ['con_salutation'];
$firstname = $_POST ['con_first_name'];
$middlename = $_POST ['con_middle_name'];
$lastname = $_POST ['con_last_name'];
$phone = $_POST ['con_phone_no'];
$fax = $_POST ['con_fax_no'];
$country = $_POST ['con_country'];
$zipcode = $_POST ['con_zipcode'];
$email = $_POST ['con_email'];
$extract_date = $_POST['extract_date'];

for($i=0; $i<count( $firstname ); $i++) {
	
	$contact = new TempContact ();
	$contact->src_con_id = $conid [$i];
	$contact->con_salutation = $salutation [$i];
	$contact->con_first_name = $firstname [$i];
	$contact->con_middle_name = $middlename [$i];
	$contact->con_last_name = $lastname [$i];
	$contact->con_email = $email [$i];
	$contact->con_phone_no = $phone [$i];
	$contact->con_fax_no = $fax [$i];
	$contact->con_zipcode = $zipcode [$i];
	$contact->con_country = $country [$i];
	$contact->con_created_date = $extract_date [$i];
	if (is_null ( $contact->con_created_date ) || empty($contact->con_created_date))
		$contact->con_created_date = date ( "m/d/y" );
	$contact->src_id = $_SESSION['username'];
	$contact->con_created_by = $_SESSION['username'];
	
	array_push ( $contacts, $contact );
	}//end of if loop

 

	insertData(getDBConnection(),$contacts);
	runMatching($contacts);
	header ( "Location: ../html/ManualUploadTemplate.html" );
	

?>