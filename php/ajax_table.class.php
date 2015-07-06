<?php
require_once ('Config.php');

class ajax_table {
	
	public function __construct() {
		$this->dbconnect ();
	}
	
	public $conn;
	private function dbconnect() {
		$element = 'conn';
		$this->$element = new mysqli ( DB_HOST, DB_USER, DB_PASSWORD, DB_DB ) or die ( "<div style='color:red;'><h3>Could not connect to MySQL server</h3></div>" );
		
		return $this->$element;
	}
	
	function getRecords() {
		$sql = "select * from contacts_dump";
		$element = 'conn';
		$conn = $this->$element;
		$this->res = $conn->query ( $sql );
		$result = $this->res;
		if ($result->num_rows > 0) {
			while ( $row = $result->fetch_assoc () ) {
				$record = array_map ( 'stripslashes', $row );
				$this->records [] = $record;
			}
			
			return $this->records;
		} 
	}
	
	function save($data) {
		if (count ( $data )) {
			$values = implode ( "','", array_values ( $data ) );
			mysql_query ( "insert into contacts_dump (" . implode ( ",", array_keys ( $data ) ) . ") values ('" . $values . "')" );
			
			if (mysql_insert_id ())
				return mysql_insert_id ();
			return 0;
		} else
			return 0;
	}
	
	function delete_record($id) {
		if ($id) {
			mysql_query ( "delete from contacts_dump where con_t_id = $id limit 1" );
			return mysql_affected_rows ();
		}
	}
	
	function update_record($data) {
		if (count ( $data )) {
			$id = $data ['rid'];
			unset ( $data ['rid'] );
			$values = implode ( "','", array_values ( $data ) );
			$str = "";
			foreach ( $data as $key => $val ) {
				$str .= $key . "='" . $val . "',";
			}
			$str = substr ( $str, 0, - 1 );
			$sql = "update contacts_dump set $str where con_t_id = $id limit 1";
			
			$res = mysql_query ( $sql );
			
			if (mysql_affected_rows ())
				return $id;
			return 0;
		} else
			return 0;
	}
	
	function update_column($data) {
		if (count ( $data )) {
			$id = $data ['rid'];
			unset ( $data ['rid'] );
			$sql = "update contacts_dump set " . key ( $data ) . "='" . $data [key ( $data )] . "' where con_t_id = $id limit 1";
			$res = mysql_query ( $sql );
			if (mysql_affected_rows ())
				return $id;
			return 0;
		}
	}
	
	function error($act) {
		return json_encode ( array (
				"success" => "0",
				"action" => $act 
		) );
	}
}
?>
