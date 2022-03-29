<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");
class PDOHandler {
	protected $host, $user, $pass,  

	$debug = false, $numOfRows = '';
	protected $keyTypes = array (
			"NUM",
			"ASSOC",
			"BOTH" 
	);
	public $db, $connection_error, $link;
	public function __construct($host, $user, $pass, $db, $isDebug) {
		$this->is_debug ( $isDebug );
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
		$this->link = $this->connect ();
	}
	
	public function connect() {
		$this->debug_mode ( 'PDO::connect', 'info', '#Attempt connection' );
		try {
			// try{
			// $connection = @new pdo('mysql:host='.$this->host.';dbname='.$this->db, $this->user, $this->pass,
			// array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			
			// }catch(PDOException $ex){
			// $connection = @new pdo('mysql:host='.$this->host.';dbname='.$this->db.';charset=UTF-8', $this->user, $this->pass);
			// }
			$connection = @new pdo ( 'mysql:host=' . $this->host . ';dbname=' . $this->db, $this->user, $this->pass );
			$connection->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			$this->debug_mode ( 'connect', 'error', '#connection failed: ' . $e->getMessage () );
			$this->connection_error = $e->getMessage ();
			return false;
		}
		$this->debug_mode ( 'connect', 'success', '#connected successfully' );
		return $connection;
	}
	
	// this function select another database
	public function select_database($db) {
		// command function >> USE $db
		if ($this->link->query ( 'USE ' . $db )) {
			$this->debug_mode ( 'select_database', 'success', '#Select database successfully' );
			return true;
		} else {
			$this->debug_mode ( 'select_database', 'error', '#Select database failed' );
			$this->connection_error = "Erroe selecting the database";
			return false;
		}
	}
	
	// this function make query to fetch data from database ( Like using SELECT & SHOW ), this function return array and not handler
	public function query($sqlStatement, $keyType = "NUM", $params = array()) // $keyType = ASSOC, NUM, BOTH
{
		if ($keyType === 'BOTH')
			$keyType = PDO::FETCH_BOTH; // PDO::FETCH_BOTH
		else if ($keyType === 'ASSOC')
			$keyType = PDO::FETCH_ASSOC; // PDO::FETCH_ASSOC
		else
			$keyType = PDO::FETCH_NUM; // PDO::FETCH_NUM
		
		$this->debug_mode ( 'query', 'info', $sqlStatement );
		if ($this->link) {
			try {
				$query = @$this->link->prepare ( $sqlStatement );
				if ($query && count ( $params ) > 0)
					$result = @$query->execute ( $params );
				else
					$result = @$query->execute ();
				if (! $result) {
					if ($this->debug) {
						ob_start ();
						var_dump ( $query->errorInfo () );
						$str = ob_get_clean ();
						$this->debug_mode ( 'query', 'error', '#Query Failed<br/>' . $str . '<br />check array of parameters it must be like that array(param1, param2, ...);' );
					}
					return false;
				} else {
					$this->numOfRows = @$query->rowCount ();
					$this->debug_mode ( 'query', 'success', '#Query success : it returns ' . $this->numOfRows . ' rows' );
					
					$fetchedData = $query->fetchAll ( $keyType );
					if ($this->debug) {
						ob_start ();
						var_dump ( $fetchedData );
						$str = ob_get_clean ();
						$this->debug_mode ( 'query', 'info', "#result array : <br/>" . $str );
					}
					$query->closeCursor ();
					return $fetchedData;
				}
			} catch ( PDOException $e ) {
				$this->debug_mode ( 'query', 'error', '#query failed: ' . $e->getMessage () );
				return false;
			}
		}
	}
	
	// this function make command to manipulate data ( Like using INSERT & UPDATE ), this function return true on success
	public function command($sqlStatement, $params = array()) {
		$this->debug_mode ( 'command', 'info', $sqlStatement );
		if ($this->link) {
			try {
				$query = @$this->link->prepare ( $sqlStatement );
				if ($query && count ( $params ) > 0)
					$result = @$query->execute ( $params );
				else
					$result = @$query->execute ();
				if (! $result) {
					if ($this->debug) {
						ob_start ();
						var_dump ( $query->errorInfo () );
						$str = ob_get_clean ();
						$this->debug_mode ( 'command', 'error', '#Command Failed<br/>' . $str . '<br />check array of parameters it must be like that array(param1, param2, ...);' );
					}
					return false;
				} else {
					$this->numOfRows = @$query->rowCount ();
					$this->debug_mode ( 'command', 'success', '#Command success : it returns ' . $this->numOfRows . ' rows' );
					$query->closeCursor ();
					return true;
				}
			} catch ( PDOException $e ) {
				$this->debug_mode ( 'command', 'error', '#command failed: ' . $e->getMessage () );
				return false;
			}
		}
	}
	public function sanitize_values($string) {
		$cleaned_string = $string;
		
		$this->debug_mode ( 'sanitize_values', 'info', '#input string : ' . $string );
		if ($this->link) {
			$cleaned_string = (get_magic_quotes_gpc ()) ? stripslashes ( $string ) : $string;
			// $cleaned_string = $this->link->quote($string);
			
			$this->debug_mode ( 'sanitize_values', 'success', '#Cleaned string : ' . $cleaned_string );
		}
		return $cleaned_string;
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
	
	// this function to check if connection failed or succeeded
	
	
	// this function return number of rows for current query
	public function get_num_rows() {
		return $this->numOfRows;
	}
	
	// this function return database handler type
	public function get_db_handler_type() {
		return 'pdo';
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
			$this->link = null;
		}
	}
}