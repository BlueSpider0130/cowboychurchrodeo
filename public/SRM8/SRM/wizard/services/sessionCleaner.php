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
 *  Mofify session after changes due to moving back and forth using the menu 
 * ################################################################################################
 */
require_once("../request.php");
defined ( 'DIRECTACESS' ) or die ( "Error 301: Access denied!" );

function unsetAllSession()
{
	global $_SESSION;
	$_SESSION["srm_f62014_page_key"] = "step_2";
	$_SESSION["srm_f62014_active_pages"] = array("step_2");
	//--------------------------------
	unset(
		$_SESSION["srm_f62014_host"],
		$_SESSION["srm_f62014_user"],
		$_SESSION["srm_f62014_pass"],
		$_SESSION["srm_f62014_validate_key"],
		$_SESSION["srm_f62014_db"],
		$_SESSION["srm_f62014_datasource"],
		//--------------------------------------
		$_SESSION["srm_f62014_sql"],
		//--------------------------------------
		$_SESSION["srm_f62014_table"],
		$_SESSION["srm_f62014_tables_filters"],
		$_SESSION["srm_f62014_relationships"],
		//-----------------------------------
		$_SESSION["srm_f62014_fields"],
		$_SESSION["srm_f62014_fields2"],
		$_SESSION["srm_f62014_labels"],
		$_SESSION["srm_f62014_function"],
		$_SESSION["srm_f62014_affected_column"],
		$_SESSION["srm_f62014_groupby_column"],
		//---------------------------------------
		$_SESSION["srm_f62014_group_by"],
		$_SESSION["srm_f62014_sort_by"],
		//----------------------------------
		$_SESSION["srm_f62014_title"],
		$_SESSION["srm_f62014_header"],
		$_SESSION["srm_f62014_footer"],
		$_SESSION["srm_f62014_file_name"],
		$_SESSION["srm_f62014_records_per_page"],
		$_SESSION["srm_f62014_chkSearch"],
		$_SESSION["srm_f62014_date_created"],
		//-------------------------------
		$_SESSION["srm_f62014_layout"],
		$_SESSION["srm_f62014_style_name"],
		//----------------------------
		$_SESSION["srm_f62014_security"],
		$_SESSION["srm_f62014_sec_Username"],
		$_SESSION["srm_f62014_sec_pass"],
		$_SESSION["srm_f62014_members"],
		$_SESSION["srm_f62014_sec_table"],
		$_SESSION["srm_f62014_sec_Username_Field"],
		$_SESSION["srm_f62014_sec_pass_Field"],
		$_SESSION["srm_f62014_sec_pass_hash_type"],
		$_SESSION["srm_f62014_sec_pass_hash_key"],
		$_SESSION["srm_f62014_Forget_password"],
		$_SESSION["srm_f62014_sec_email"],
		$_SESSION["srm_f62014_sec_email_field"]

	);
}

function unsetSessionStartFromDB()
{
	global $_SESSION;
	$_SESSION["srm_f62014_page_key"] = "step_2";
	$_SESSION["srm_f62014_active_pages"] = array("step_2");
	unset(
		$_SESSION["srm_f62014_db"],
		$_SESSION["srm_f62014_datasource"],
		//--------------------------------------
		$_SESSION["srm_f62014_sql"],
		//--------------------------------------
		$_SESSION["srm_f62014_table"],
		$_SESSION["srm_f62014_tables_filters"],
		$_SESSION["srm_f62014_relationships"],
		//-----------------------------------
		$_SESSION["srm_f62014_fields"],
		$_SESSION["srm_f62014_fields2"],
		$_SESSION["srm_f62014_labels"],
		$_SESSION["srm_f62014_function"],
		$_SESSION["srm_f62014_affected_column"],
		$_SESSION["srm_f62014_groupby_column"],
		//---------------------------------------
		$_SESSION["srm_f62014_group_by"],
		$_SESSION["srm_f62014_sort_by"],
		//----------------------------------
		$_SESSION["srm_f62014_title"],
		$_SESSION["srm_f62014_header"],
		$_SESSION["srm_f62014_footer"],
		$_SESSION["srm_f62014_file_name"],
		$_SESSION["srm_f62014_records_per_page"],
		$_SESSION["srm_f62014_chkSearch"],
		$_SESSION["srm_f62014_date_created"],
		//-------------------------------
		$_SESSION["srm_f62014_layout"],
		$_SESSION["srm_f62014_style_name"],
		//----------------------------
		$_SESSION["srm_f62014_security"],
		$_SESSION["srm_f62014_sec_Username"],
		$_SESSION["srm_f62014_sec_pass"],
		$_SESSION["srm_f62014_members"],
		$_SESSION["srm_f62014_sec_table"],
		$_SESSION["srm_f62014_sec_Username_Field"],
		$_SESSION["srm_f62014_sec_pass_Field"],
		$_SESSION["srm_f62014_sec_pass_hash_type"],
		$_SESSION["srm_f62014_sec_pass_hash_key"],
		$_SESSION["srm_f62014_Forget_password"],
		$_SESSION["srm_f62014_sec_email"],
		$_SESSION["srm_f62014_sec_email_field"]

	);
}

function unsetSessionStartFromDataSource()
{
	global $_SESSION;
	$_SESSION["srm_f62014_page_key"] = "data_source";
	$_SESSION["srm_f62014_active_pages"] = array("data_source");
	unset(
		$_SESSION["srm_f62014_sql"],
		//--------------------------------------
		$_SESSION["srm_f62014_table"],
		$_SESSION["srm_f62014_tables_filters"],
		$_SESSION["srm_f62014_relationships"],
		//-----------------------------------
		$_SESSION["srm_f62014_fields"],
		$_SESSION["srm_f62014_fields2"],
		$_SESSION["srm_f62014_labels"],
		$_SESSION["srm_f62014_function"],
		$_SESSION["srm_f62014_affected_column"],
		$_SESSION["srm_f62014_groupby_column"],
		//---------------------------------------
		$_SESSION["srm_f62014_group_by"],
		$_SESSION["srm_f62014_sort_by"],
		//----------------------------------
		$_SESSION["srm_f62014_title"],
		$_SESSION["srm_f62014_header"],
		$_SESSION["srm_f62014_footer"],
		$_SESSION["srm_f62014_file_name"],
		$_SESSION["srm_f62014_records_per_page"],
		$_SESSION["srm_f62014_chkSearch"],
		$_SESSION["srm_f62014_date_created"],
		//-------------------------------
		$_SESSION["srm_f62014_layout"],
		$_SESSION["srm_f62014_style_name"],
		//----------------------------
		$_SESSION["srm_f62014_security"],
		$_SESSION["srm_f62014_sec_Username"],
		$_SESSION["srm_f62014_sec_pass"],
		$_SESSION["srm_f62014_members"],
		$_SESSION["srm_f62014_sec_table"],
		$_SESSION["srm_f62014_sec_Username_Field"],
		$_SESSION["srm_f62014_sec_pass_Field"],
		$_SESSION["srm_f62014_sec_pass_hash_type"],
		$_SESSION["srm_f62014_sec_pass_hash_key"],
		$_SESSION["srm_f62014_Forget_password"],
		$_SESSION["srm_f62014_sec_email"],
		$_SESSION["srm_f62014_sec_email_field"]

	);
}

function unsetSessionStartFromColumns()
{
	global $_SESSION;
	$_SESSION["srm_f62014_page_key"] = "step_4";
	$_SESSION["srm_f62014_active_pages"] = array("step_4");
	unset(
		$_SESSION["srm_f62014_fields"],
		$_SESSION["srm_f62014_fields2"],
		$_SESSION["srm_f62014_labels"],
		$_SESSION["srm_f62014_function"],
		$_SESSION["srm_f62014_affected_column"],
		$_SESSION["srm_f62014_groupby_column"],
		//---------------------------------------
		$_SESSION["srm_f62014_group_by"],
		$_SESSION["srm_f62014_sort_by"],
		//----------------------------------
		$_SESSION["srm_f62014_title"],
		$_SESSION["srm_f62014_header"],
		$_SESSION["srm_f62014_footer"],
		$_SESSION["srm_f62014_file_name"],
		$_SESSION["srm_f62014_records_per_page"],
		$_SESSION["srm_f62014_chkSearch"],
		$_SESSION["srm_f62014_date_created"],
		//-------------------------------
		$_SESSION["srm_f62014_layout"],
		$_SESSION["srm_f62014_style_name"],
		//----------------------------
		$_SESSION["srm_f62014_security"],
		$_SESSION["srm_f62014_sec_Username"],
		$_SESSION["srm_f62014_sec_pass"],
		$_SESSION["srm_f62014_members"],
		$_SESSION["srm_f62014_sec_table"],
		$_SESSION["srm_f62014_sec_Username_Field"],
		$_SESSION["srm_f62014_sec_pass_Field"],
		$_SESSION["srm_f62014_sec_pass_hash_type"],
		$_SESSION["srm_f62014_sec_pass_hash_key"],
		$_SESSION["srm_f62014_Forget_password"],
		$_SESSION["srm_f62014_sec_email"],
		$_SESSION["srm_f62014_sec_email_field"]

	);
}