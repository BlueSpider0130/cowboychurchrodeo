<?php
/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 */
/*
 * #################################################################################################
 * Check that necessary info thta should be stored in session before step 4 and 5 are existed otherwise system would crash
 * ################################################################################################
 */
	defined('DIRECTACESS') or die ("Error 301: Access denied!");
        
	function sessionBe4Step4()
	{
		global $_SESSION;
		if(isset(
			$_SESSION["srm_f62014_table"])
			) return true;
		else if(isset(
			$_SESSION["srm_f62014_sql"])
			) return true;
		else return false;
	}
	
	function sessionBe4Step5()
	{
		global $_SESSION;
		if(isset(
			$_SESSION["srm_f62014_fields"],
			$_SESSION["srm_f62014_fields2"])
			) return true;
		else return false;
	}