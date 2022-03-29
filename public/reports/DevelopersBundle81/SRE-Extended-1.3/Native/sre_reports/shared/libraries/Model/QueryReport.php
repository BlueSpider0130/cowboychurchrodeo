<?php
/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (! defined ( "DIRECTACESS" ))
	exit ( "No direct script access allowed" );
class QueryReport extends Report {
	protected $sql; // the source sql query
	public function set_sql($sql) {
		$this->sql = $sql;
	}
	public function __Construct($sql) {
		$this->set_sql ( $sql );
	}
	public function Prepare_Sql() {
		$new_sql = $this->sql;
		
		$i = 0;
		
		// if statestical options (removed from query data source
		/*
		 * if (!empty($groupby_column))
		 * $new_sql .= " group by (`".$groupby_column ."`) ";
		 *
		 */
		
		if (count ( $this->sort_by ) > 0 || count ( $this->group_by ) > 0) {
			
			$new_sql .= " order by ";
		}
		
		$group_by_sort = array ();
		
		foreach ( $this->group_by as $g ) {
			$flag = 0;
			$i = 0;
			
			foreach ( $this->sort_by as $arr ) {
				if ($g == $arr [0]) {
					$group_by_sort [] = array (
							$arr [0],
							$arr [1] 
					);
					$flag = 1;
					$this->sort_by [$i] [0] = '~xxx~';
					break;
				}
				$i ++;
			}
			
			if ($flag == 0) {
				$group_by_sort [] = array (
						$g,
						'0' 
				);
			}
		}
		
		// ************* dump ****************
		// foreach($group_by_sort as $arr)
		// /{
		
		// /}
		// **************************************
		
		foreach ( $this->sort_by as $arr_sort ) {
			if ($arr_sort [0] != '~xxx~') {
				$group_by_sort [] = array (
						$arr_sort [0],
						$arr_sort [1] 
				);
			}
		}
		
		$i = 0;
		
		foreach ( $group_by_sort as $arr ) {
			
			$new_sql .= "`$arr[0]` ";
			
			if ($arr [1] == '1')
				$new_sql .= "desc";
			
			if ($i < (count ( $group_by_sort ) - 1)) {
				$new_sql .= ",";
			}
			$i ++;
		}
		
		
		$arr_sql = array ();
		$arr_sql [0] = $new_sql;
		$arr_sql [1] = array ();
		$arr_sql [2] = "";
		return $arr_sql;
	}
}
?>