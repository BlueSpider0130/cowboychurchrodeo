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
$file_name = '';
$category = "";
$date_created = '';
$maintainance_email = '';
$images_path = '';
$headers_output_escaping = "Yes";
$default_page_size = "A3";
$output_escaping = "Yes";
$thumnail_max_width = '40';
$thumnail_max_height = '50';
$show_real_size_image = '';
$show_realsize_in_popup = '1';
$chkSearch = 'yes';
// wizard settings
$language = "en";
$db_extension = 'pdo';
$datasource = 'table';
$table = array ();
//param type s,i or d 
$tables_filters = array ();
$fields = array();
$relationships =array();
$sql = '';
$fields2 = array ();
$records_per_page = '10';
$layout = "alignleft";
$style_name = 'blue';
$title = '';
$header = '';
$footer = '';
$allow_only_admin = "yes";
$is_public_access = "no";
$sec_Username = '';
$sec_pass = '';
$security = '';
$sec_email = '';
$members = '';
$sec_table = '';
$sec_Username_Field = '';
$sec_pass_Field = '';
$sec_email_field = "";
$sec_pass_hash_type = '';
$Forget_password = '';
$is_mobile = '';
$cells = array ();
$conditional_formating = array ();;
$labels ="";
$group_by = array();
$sort_by = array ();
$affected_column = "";
$function = "";
$groupby_column = "";