<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");
class MysqliHandler {
	protected $host, $user, $pass,  $debug = false, $numOfRows = '';
	public $db, $connection_error,$link;
	public function __construct($host, $user, $pass, $isDebug, $db) {
		$this->is_debug ( $isDebug );
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
		$this->link = $this->connect ();
		
	}
	public function connect() {
		$this->debug_mode ( 'Mysqli::connect', 'info', '#Attempt connection' );
	
		
				$connection = @new mysqli ( $this->host, $this->user, $this->pass, $this->db );
			
			 if ($connection->connect_errno || $connection->error !== '') {
			 	$this->debug_mode ( 'connect', 'error', '#connection failed <br/>' . $connection->connect_error  );
			 	$this->errorMsg = $connection->connect_error;
			 	$this->connection_error = '#Connect Error no (' . $connection->connect_errno . ') ' . $connection->connect_error . "<br/>";
			 	return false;
				
			} else {
				$this->debug_mode ( 'connect', 'success', '#connected successfully' );
				return $connection;
			}
		
			
		
			
				
			
		
	}
	public function select_database($db) {
		$this->db = $db;
		if (! @$this->link->select_db ( $this->db )) {
			$this->debug_mode ( 'connect', 'error', '#can\'t select database: ' . $this->link->connect_error ); // $this->errorMsg
			$this->connection_error .= 'Unable to select the database'. "<br/>";
			return false;
		} else {
			$this->debug_mode ( 'Select_database', 'success', '#select database successfully' );
			return true;
		}
	}
	
	// this function make query to fetch data from database ( Like using SELECT & SHOW ), this function return array and not handler
	public function query($sqlStatement, $keyType = "NUM", $params = array()) // $keyType = ASSOC, NUM, BOTH
{
		$this->debug_mode ( 'query', 'info', '#SQL Query > ' . $sqlStatement );
		if ($this->link) {
			if ($keyType === "ASSOC")
				$keyType = MYSQLI_ASSOC;
			else if ($keyType === "BOTH")
				$keyType = MYSQLI_BOTH;
			else
				$keyType = MYSQLI_NUM;
			
			$this->link->set_charset ( 'utf8' );
			$stmt = $this->link->prepare ( $sqlStatement );
			if (! $stmt) {
				$this->debug_mode ( 'query', 'error', '#Query Failed<br/>' . $this->link->error );
				return false;
			}
			
			// this for each to make all items in the params array have new referance to use it in call_user_func_array ...
			foreach ( $params as $key => $value )
				$parameters [$key] = &$params [$key];
			
			if (count ( $params ) > 0 && isset ( $params [0] )) {
				/*
				 * this function do :
				 * ------------------
				 * if i have function like that
				 * function x($z1, $z2){}
				 * and i want to get the value from array and put it into this arguments(parameters) here we use
				 * call_user_func_array(function if it not in class or if it in class array(object, function),array but make sure all
				 * items in this array have reference) and it will bind array value into function arguments ..
				 *
				 * array(type, value, value, .....)
				 * like >> array('is', 1, 'mohamed'); types : i >> integer, s >> string, d >> double, b >> blob
				 *
				 */
				
				$bindparam = @call_user_func_array ( array (
						$stmt,
						"bind_param" 
				), $parameters );
				if (! $bindparam) {
					$this->debug_mode ( 'query', 'error', '#Query Failed<br/>' . $this->link->error );
					$this->debug_mode ( 'query', 'error', '#Query Failed<br/> check array of parameters it must be like that array(types, param)' );
					return false;
				}
			}
			
			$exec = @$stmt->execute ();
			if (! $exec) {
				$this->debug_mode ( 'query', 'error', '#Query Failed<br/>' . $this->link->error );
				return false;
			}
			
			$result = $stmt->get_result ();
			
			if (! $result) {
				$this->debug_mode ( 'query', 'error', '#Query Failed<br/>' . $this->link->error );
				return false;
			} else {
				$this->numOfRows = @$result->num_rows;
				$this->debug_mode ( 'query', 'success', '#Query success : it returns ' . $this->numOfRows . ' rows' );
				$fetchedData = $this->get_result ( $result, $keyType );
				if ($this->debug) {
					ob_start ();
					var_dump ( $fetchedData );
					$str = ob_get_clean ();
					$this->debug_mode ( 'query', 'info', "#result array : <br/>" . $str );
				}
				return $fetchedData;
			}
		}
	}
	public function command($sqlStatement, $params = array()) {
		$this->debug_mode ( 'command', 'info', '#SQL Query > ' . $sqlStatement );
		if ($this->link) {
			$this->link->set_charset ( 'utf8' );
			$stmt = $this->link->prepare ( $sqlStatement );
			if (! $stmt) {
				$this->debug_mode ( 'command', 'error', '#Command Failed<br/>' . $this->link->error );
				return false;
			}
			
			// this for each to make all items in the params array have new referance to use it in call_user_func_array ...
			foreach ( $params as $key => $value )
				$parameters [$key] = &$params [$key];
			
			if (count ( $params ) > 0 && isset ( $params [0] )) {
				/*
				 * this function do :
				 * ------------------
				 * if i have function like that
				 * function x($z1, $z2){}
				 * and i want to get the value from array and put it into this arguments(parameters) here we use
				 * call_user_func_array(function if it not in class or if it in class array(object, function),array but make sure all
				 * items in this array have reference) and it will bind array value into function arguments ..
				 *
				 * array(type, value, value, .....)
				 * like >> array('is', 1, 'mohamed'); types : i >> integer, s >> string, d >> double, b >> blob
				 *
				 */
				
				$bindparam = @call_user_func_array ( array (
						$stmt,
						"bind_param" 
				), $parameters );
				if (! $bindparam) {
					$this->debug_mode ( 'command', 'error', '#Command Failed<br/>' . $this->link->error );
					$this->debug_mode ( 'command', 'error', '#Command Failed<br/> check array of parameters it must be like that array(types, param)' );
					return false;
				}
			}
			
			$exec = @$stmt->execute ();
			if (! $exec) {
				$this->debug_mode ( 'command', 'error', '#Command Failed<br/>' . $this->link->error );
				return false;
			} else {
				$result = $stmt->get_result ();
				$this->numOfRows = @$stmt->affected_rows or @$result->num_rows;
				$this->debug_mode ( 'command', 'success', '#Command success : it returns ' . $this->numOfRows . ' rows' );
				return true;
			}
		}
	}
	private function get_result($result, $keyType) {
		$fetchedData = array ();
		while ( $row = $result->fetch_array ( $keyType ) )
			$fetchedData [] = $row;
		return $fetchedData;
	}
	public function sanitize_values($string) {
		$cleaned_string = $string;
		
		$this->debug_mode ( 'sanitize_values', 'info', '#input string : ' . $string );
		if ($this->link) {
			$cleaned_string = (get_magic_quotes_gpc ()) ? stripslashes ( $string ) : $string;
			$cleaned_string = $this->link->real_escape_string ( $string );
			
			$this->debug_mode ( 'sanitize_values', 'success', '#Cleaned string : ' . $cleaned_string );
		}
		return $cleaned_string;
	}
	
	
	
	// this function return number of rows for current query
	public function get_num_rows() {
		return $this->numOfRows;
	}
	
	// this function display what's happened while object of this class start actions ( Just work when debug = true )
	private function debug_mode($functionName, $type, $msg) {
		if ($this->debug) {
			$color = "black"; // by default
			if ($type === "error")
				$color = "red"; // error
			else if ($type === "success")
				$color = "green"; // success
			else if ($type === "info")
				$color = "blue"; // info
			
			echo "<div style='color:$color;'>Debug-Mode: <strong>" . $functionName . "</strong> " . $msg . "</div>";
		}
	}
	
	// this function return database handler type
	public function get_db_handler_type() {
		return 'mysqli';
	}
	
	// this function set debug mode
	public function is_debug($bool) {
		if ($bool !== false)
			$bool = true;
		$this->debug = $bool;
	}
	
	// this function for close connection
	public function close_connection() {
		if ($this->link) {
			$this->link->close ();
			$this->link = null;
		}
	}
}
?>