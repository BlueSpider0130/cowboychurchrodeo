<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (! defined ( "DIRECTACESS" ))
	exit ( "No direct script access allowed" );
class DbMember extends Member {
	private $sec_table;
	private $sec_Username_Field;
	private $sec_pass_Field;
	private $sec_pass_hash_type;
	private $obj_connection;
	public function __construct($sec_table, $sec_username_field, $sec_pass_field, $sec_pass_hash_type, $obj_connection) {
		$this->sec_table = $sec_table;
		$this->sec_Username_Field = $sec_username_field;
		$this->sec_pass_Field = $sec_pass_field;
		$this->sec_pass_hash_type = $sec_pass_hash_type;
		$this->obj_connection = $obj_connection;
	}
	public function authenticate($submitted_user, $submitted_password) {
		if ($this->sec_table !== "" && $this->sec_Username_Field != "" && $this->sec_Username_Field != "") {
			switch ($this->sec_pass_hash_type) {
				case "md5" :
					$member_pass = md5 ( $submitted_password );
					$member_pass = $this->obj_connection->sanitize_values ( $member_pass );
					break;
				case "sha1" :
					$member_pass = sha1 ( $submitted_password );
					$member_pass = $this->obj_connection->sanitize_values ( $member_pass );
					break;
				case "sha256" :					
					$member_pass = hash ( "sha256",$submitted_password );
					$member_pass = $this->obj_connection->sanitize_values ( $member_pass );
					break;
				// further encryption methods could be added here
				default :
					$member_pass = $this->obj_connection->sanitize_values ( $submitted_password );
			}
			
			$member_user = $this->obj_connection->sanitize_values ( $submitted_user );
			$params = array (
					trim($member_user),
					trim($member_pass) 
			);
			$sql = "select`" . $this->sec_Username_Field . "` from `" . $this->sec_table . "` where `" . $this->sec_Username_Field . "`=? and `" . $this->sec_pass_Field . "` = ?";
			
			
			$results = $this->obj_connection->query ( $sql, 'ASSOC', $params, 'ss' );
		
			if (! $results)
				return false; // invalid sql query
			if ($this->obj_connection->get_num_rows () == 1) {
				$this->obj_connection->close_connection ();
				return true;
			} else {
				// member dosn't exist
				$this->obj_connection->close_connection ();
				return false;
			}
		}
	}
}





