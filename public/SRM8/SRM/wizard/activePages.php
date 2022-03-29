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
 * Enable and disable steps to ease navigation , and prevent direct access
 * ################################################################################################
 */

defined ( 'DIRECTACESS' ) or die ( "Error 301: Access denied!" );
	
	// check and set last page in array for navigation reason
    //which step to be disabled
	if(isset($_SESSION['srm_f62014_page_key']) && in_array($_SESSION['srm_f62014_page_key'], $pagesName)){
		if((isset($_SESSION['srm_f62014_active_pages']) && is_array($_SESSION['srm_f62014_active_pages']) 
			&& !in_array($_SESSION['srm_f62014_page_key'], $_SESSION['srm_f62014_active_pages'])) || !isset($_SESSION['srm_f62014_active_pages']))
		{
			if(isset($_SESSION['srm_f62014_active_pages'])) 
			{
				$keyOfLastActivePage = array_search($_SESSION['srm_f62014_active_pages'][(count($_SESSION['srm_f62014_active_pages']) - 1)], $pagesName);
				$keyOfCurrentPage = array_search($_SESSION['srm_f62014_page_key'], $pagesName);
				if($keyOfLastActivePage < $keyOfCurrentPage) $_SESSION['srm_f62014_active_pages'][] = $_SESSION['srm_f62014_page_key'];
			}
			else $_SESSION['srm_f62014_active_pages'][] = $_SESSION['srm_f62014_page_key'];
		}
	}