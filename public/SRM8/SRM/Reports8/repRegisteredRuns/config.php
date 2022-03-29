<?php
//Rodeo Runs,09-Feb-2022 10:31:05
if (! defined("DIRECTACESS")) exit("No direct script access allowed"); 
$file_name = "repRegisteredRuns";
//  customization settings
$template_title = "";
$category = "User Reports";
$date_created = "09-Feb-2022 10:31:05";
$maintainance_email = "";
$images_path = "images/";
$headers_output_escaping = "Yes";
$default_page_size = "A3";
$output_escaping = "Yes";
$thumnail_max_width = "40";
$thumnail_max_height = "50";
$show_real_size_image = "";
$show_realsize_in_popup = "1";
$chkSearch = "Yes";
//  wizard settings
$language = "en";
$db_extension = "pdo";
$datasource = "table";
$sql = "";
$table = array(
"0" => "competition_entries",
"1" => "competitions",
"2" => "contestants",
"3" => "events",
"4" => "groups",
"5" => "rodeos");
$tables_filters = array();
$relationships = array(
"0" => "`contestants`.`id` = `competition_entries`.`contestant_id`",
"1" => "`competitions`.`id` = `competition_entries`.`competition_id`",
"2" => "`rodeos`.`id` = `competitions`.`rodeo_id`",
"3" => "`groups`.`id` = `competitions`.`group_id`",
"4" => "`events`.`id` = `competitions`.`event_id`");
$affected_column = "";
$function = "";
$groupby_column = "";
$labels = array(
"contestants.last_name" => "Last",
"contestants.first_name" => "First",
"groups.name" => "Group",
"events.name" => "Event");
$cells = array(
"contestants.last_name" => "value",
"contestants.first_name" => "value",
"groups.name" => "value",
"events.name" => "value");
$conditional_formating = array();
$fields = array(
"0" => "contestants.last_name",
"1" => "contestants.first_name",
"2" => "groups.name",
"3" => "events.name");
$fields2 = array(
"0" => "contestants.last_name",
"1" => "contestants.first_name",
"2" => "groups.name",
"3" => "events.name");
$group_by = array(
"0" => "contestants.last_name",
"1" => "contestants.first_name",
"2" => "groups.name",
"3" => "events.name");
$sort_by = array();
$records_per_page = "25";
$layout = "Stepped";
$style_name = "grey";
$title = "Rodeo Runs";
$header = "";
$footer = "";
$allow_only_admin = "yes";
$sec_Username = "";
$sec_pass = "";
$security = "";
$is_public_access = "no";
$sec_email = "";
$members = "";
$sec_table = "";
$sec_Username_Field = "";
$sec_pass_Field = "";
$sec_email_field = "";
$sec_pass_hash_type = "";
$Forget_password = "enabled";
$is_mobile = "";
$sub_totals_enabled = "";
$filters_grouping = "null";
$sub_totals = array();
