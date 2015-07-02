 <?php
	require_once ("../php/SQlConnector.php");
	
	if (! (isset ( $_POST ['submit'] ))) {
		die("Error login failed.");
	} else {
		if (login()) {
			session_start();
			$_SESSION['username'] = trim ( $_POST ['username'] );
			header ( "Location: ../html/welcome.html" );
		} else
			header ( "Location: ../html/login.html" );
	}
	
	function login() {
		
		if (empty ( $_POST ['username'] )) {
			echo "Username is empty";
			return false;
		}
		
		if (empty ( $_POST ['password'] )) {
			echo "Password is empty";
			return false;
		}
		
		$username = trim ( $_POST ['username'] );
		$password = trim ( $_POST ['password'] );
		
		$conn = getDBConnection();
		$query = "select password from login_details where username = '$username'";
		$res = execSQL($conn, $query);
		while ($row = $res->fetch_assoc()) {
			$pwdhash = $row['password'];
		}
		
		return password_verify($password,$pwdhash);
		
	} // end of function login
	
	?>