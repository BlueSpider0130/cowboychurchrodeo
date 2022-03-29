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
class Template {
	public $path; //full path to the templates directory
	public $title;
	public $dir_name; //directory name of the template

	
	public function __construct($template_path,$title){
		$this->path = $template_path;
		$this->dir_name = basename($this->path);		
		$this->title = ( $title != "") ? $title : "Untitled";		
	}
	
	public function load() {
		
		defined("DIRECTACESS") or define("DIRECTACESS",1);
		require_once("config-init.php");
		require ($this->path."config.php");
		$_SESSION ['srm_f62014_datasource'] = $datasource;
		$_SESSION ['srm_f62014_table'] = $table;
		$_SESSION ['srm_f62014_tables_filters'] = $tables_filters;
			
		
			foreach ( $fields as $k => $field ) {
				if (strstr ( $field, '(' )) {
					$realValue = substr ( substr ( $field, strpos ( $field, '(`' ) + 2 ), 0, strpos ( substr ( $field, strpos ( $field, '(`' ) + 2 ), '`)' ) );
					$_SESSION ["srm_f62014_affected_column"] = $affected_column;
					$_SESSION ["srm_f62014_function"] = $function;
					$_SESSION ["srm_f62014_groupby_column"] = $groupby_column;
					$field = $realValue;
					$fields [$k] = $field;
				}
			}
			$_SESSION ['srm_f62014_fields'] = $fields;
		
		$_SESSION ['srm_f62014_relationships'] = $relationships;
		$_SESSION ['srm_f62014_sql'] = $sql;
		$_SESSION ['srm_f62014_fields2'] = $fields2;
		$_SESSION ['srm_f62014_labels'] =  $labels ;
			
		$_SESSION ['srm_f62014_group_by'] = $group_by;
		$_SESSION ['srm_f62014_sort_by'] = $sort_by;
		$_SESSION ['srm_f62014_records_per_page'] = $records_per_page;
		$_SESSION ['srm_f62014_layout'] = $layout;
		$_SESSION ['srm_f62014_style_name'] = $style_name;
			
		$_SESSION ['srm_f62014_title'] = $title;
		$_SESSION ['srm_f62014_header'] = $header;
		$_SESSION ['srm_f62014_footer'] = $footer;
		$_SESSION ['srm_f62014_category'] =$category;
		$_SESSION ['srm_f62014_chkSearch'] = $chkSearch;
		$_SESSION["srm_f62014_allow_only_admin"] = $allow_only_admin;
		$_SESSION ['srm_f62014_security'] = $security;
		$_SESSION ['srm_f62014_sec_Username'] = $sec_Username;
                $_SESSION ['srm_f62014_affected_column'] = $affected_column;
			
		$_SESSION ['srm_f62014_members'] = $members;
		$_SESSION["srm_f62014_is_public_access"] = $is_public_access;
		$_SESSION ['srm_f62014_sec_table'] = $sec_table;
		$_SESSION ['srm_f62014_sec_Username_Field'] = $sec_Username_Field;
		$_SESSION ['srm_f62014_sec_pass_Field'] = $sec_pass_Field;
		$_SESSION ['srm_f62014_sec_pass_hash_type'] = $sec_pass_hash_type;
		$_SESSION ['srm_f62014_sec_email_field']  = $sec_email_field;
		$_SESSION ['srm_f62014_Forget_password'] = $Forget_password;
		$_SESSION ['srm_f62014_sec_email'] = $sec_email;
		$_SESSION ['srm_f62014_is_mobile'] = $is_mobile;
		$_SESSION ['srm_f62014_images_path'] = $images_path;
		$_SESSION ['srm_f62014_thumnail_max_width'] = $thumnail_max_width;
		$_SESSION ['srm_f62014_thumnail_max_height'] = $thumnail_max_height;
		$_SESSION ['srm_f62014_show_real_size_image'] = $show_real_size_image;
		$_SESSION ['srm_f62014_show_realsize_in_popup'] = $show_realsize_in_popup;
		$_SESSION ['srm_f62014_language'] = $language;
                if(!empty($cells)){
		$_SESSION ['srm_f62014_cells'] = $this->reverse_cells ( $cells );
                }
		$_SESSION ['srm_f62014_conditional_formating'] = $conditional_formating;
			
		$_SESSION ['srm_f62014_page_key'] = 'step_6';
		$_SESSION ['srm_f62014_active_pages'] = array (
				'step_5_1',
				'step_6'
		);
		$_SESSION['srp_subtotals_enabled']=$sub_totals_enabled;
		$_SESSION['srp_sub_totals'] = $sub_totals;
		$_SESSION['srp_filters_grouping'] = $filters_grouping;
		

		return true;
	}
	
	//prepare the cells array to be loaded in the session . 
	private function reverse_cells($cells) {
		$arr = array (
				array () 
		);
		$index = 0;
		foreach ( $cells as $k => $v ) {
			$arr [$index] ["column"] = $k;
			if (strstr ( $v, "append" )) {
				$temp = explode ( "-", $v );
				$arr [$index] ["appendedText"] = $temp [2];
				$arr [$index] ["cellType"] = $temp [0] . "-" . $temp [1];
			} else {
				$arr [$index] ["cellType"] = $v;
				$arr [$index] ["appendedText"] = "";
			}
			$index ++;
		}
		return $arr;
	}
}