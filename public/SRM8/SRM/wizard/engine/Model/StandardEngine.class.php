<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft
 * All copyrights are preserved to StarSoftrecords_per_page
 * URL : http://mysqlreports.com/
 */
defined('DIRECTACESS') or die("Error 301: Access denied!");

class StandardEngine extends Engine {

    public function __construct($reports_directory) {
        if (substr(trim($reports_directory), -1) == '/') {
            $this->_reports_directory = $reports_directory;
        } else {
            $this->_reports_directory = $reports_directory . "/";
        }
        if ($_SESSION ['srm_f62014_layout'] == 'Mobile') {
            $_SESSION ['srm_f62014_is_mobile'] = true;
        }
        $this->set_connection_user($_SESSION["srm_f62014_user"]);
        $this->set_connection_pass($_SESSION["srm_f62014_pass"]);
        $this->set_connection_db_name($_SESSION["srm_f62014_db"]);
        $this->set_connection_host($_SESSION["srm_f62014_host"]);
        $this->set_file_name($_SESSION["srm_f62014_file_name"]);
        $this->set_category($_SESSION["srm_f62014_category"]);
        $this->set_date_created($_SESSION["srm_f62014_date_created"]);
        $this->set_language($_SESSION["srm_f62014_language"]);
        $this->set_db_extension($_SESSION["srm_f62014_db_extension"]);
        $this->set_fields($_SESSION["srm_f62014_fields"]);
        $this->set_fields2($_SESSION["srm_f62014_fields2"]);
        $this->set_records_per_page((int)$_SESSION["srm_f62014_records_per_page"]);
        $this->set_layout($_SESSION["srm_f62014_layout"]);
        $this->set_style_name($_SESSION["srm_f62014_style_name"]);
        $this->set_title($_SESSION['srm_f62014_title']);
        $this->set_header($_SESSION["srm_f62014_header"]);
        $this->set_footer($_SESSION["srm_f62014_footer"]);
        $this->set_allow_only_admin($_SESSION["srm_f62014_allow_only_admin"]);
        $this->set_is_public_access($_SESSION["srm_f62014_is_public_access"]);
        $this->set_filters_grouping($_SESSION["srp_filters_grouping"]);
        $_SESSION["srp_sub_totals"] = isset($_SESSION["srp_sub_totals"])?$_SESSION["srp_sub_totals"]:array();
        $this->set_sub_totals($_SESSION["srp_sub_totals"]);
        $_SESSION["srp_subtotals_enabled"] = isset($_SESSION["srp_subtotals_enabled"])?$_SESSION["srp_subtotals_enabled"]:"";
        $this->set_sub_totals_enabled($_SESSION["srp_subtotals_enabled"]);

        if (isset($_SESSION["srm_f62014_sec_pass"]))
            $this->set_sec_pass($_SESSION["srm_f62014_sec_pass"]);
        else
            $this->set_sec_pass("");

        if (isset($_SESSION["srm_f62014_security"]) && $_SESSION["srm_f62014_security"] != "") {
            $this->set_security($_SESSION["srm_f62014_security"]);
            $this->set_sec_email($_SESSION["srm_f62014_sec_email"]);
            $this->set_sec_Username($_SESSION["srm_f62014_sec_Username"]);
        } else {
            $this->set_security("");
            $this->set_sec_email("");
            $this->set_sec_Username("");
        }
        //members
        //if ((isset($_SESSION["srm_f62014_members"]) && $_SESSION["srm_f62014_members"] != "") || ($_SESSION["srm_f62014_sec_Username_Field"] != "" && $_SESSION["srm_f62014_sec_pass_Field"]!= "")) {
        $this->set_members($_SESSION["srm_f62014_members"]);
        $this->set_sec_table($_SESSION["srm_f62014_sec_table"]);
        $this->set_sec_Username_Field($_SESSION["srm_f62014_sec_Username_Field"]);
        $this->set_sec_pass_Field($_SESSION["srm_f62014_sec_pass_Field"]);
        $this->set_sec_email_field($_SESSION["srm_f62014_sec_email_field"]);
        $this->set_sec_pass_hash_type($_SESSION["srm_f62014_sec_pass_hash_type"]);
        /*  } else {
          $this->set_members("");
          $this->set_sec_table("");
          $this->set_sec_Username_Field("");
          $this->set_sec_pass_Field("");
          $this->set_sec_email_field("");
          $this->set_sec_pass_hash_type("");
          } */
        $this->set_Forget_password("enabled");
        $_SESSION["srm_f62014_is_mobile"] = isset($_SESSION["srm_f62014_is_mobile"])?$_SESSION["srm_f62014_is_mobile"]:false;
        $this->set_is_mobile($_SESSION["srm_f62014_is_mobile"]);
        $this->set_cells($_SESSION["srm_f62014_cells"]);
        $_SESSION["srm_f62014_conditional_formating"] = isset($_SESSION["srm_f62014_conditional_formating"])?$_SESSION["srm_f62014_conditional_formating"]:array();
        $this->set_conditional_formating($_SESSION["srm_f62014_conditional_formating"]);
        $this->set_labels($_SESSION["srm_f62014_labels"]);
        $this->set_group_by($_SESSION["srm_f62014_group_by"]);
        $this->set_datasource($_SESSION["srm_f62014_datasource"]);
        $this->set_sort_by($_SESSION["srm_f62014_sort_by"]);
        if (strtolower($_SESSION["srm_f62014_datasource"]) == "sql") {
            $this->set_sql($_SESSION["srm_f62014_sql"]);
            $this->set_chkSearch("no");
        } else {
            $this->set_table($_SESSION["srm_f62014_table"]);
            //$_SESSION["srm_f62014_tables_filters"] = isset($_SESSION["srm_f62014_tables_filters"])?$_SESSION["srm_f62014_tables_filters"]:array();
            $_SESSION["srm_f62014_tables_filters"] = isset($_SESSION["srm_f62014_tables_filters"])?$_SESSION["srm_f62014_tables_filters"]:array();
            $this->set_tables_filters($_SESSION["srm_f62014_tables_filters"]);
            $_SESSION["srm_f62014_relationships"] = isset($_SESSION["srm_f62014_relationships"])?$_SESSION["srm_f62014_relationships"]:array();
            $this->set_relationships($_SESSION["srm_f62014_relationships"]);
            $this->set_chkSearch($_SESSION["srm_f62014_chkSearch"]);
            $_SESSION["srm_f62014_affected_column"] = isset($_SESSION["srm_f62014_affected_column"])?$_SESSION["srm_f62014_affected_column"]:"";
            $this->set_Statestical_affected_column($_SESSION["srm_f62014_affected_column"]);
            $_SESSION["srm_f62014_function"] = isset($_SESSION["srm_f62014_function"])?$_SESSION["srm_f62014_function"]:"";
            $this->set_Statestical_function($_SESSION["srm_f62014_function"]);
            $_SESSION["srm_f62014_groupby_column"] = isset($_SESSION["srm_f62014_groupby_column"])?$_SESSION["srm_f62014_groupby_column"]:"";
            $this->set_Statestical__groupby_column($_SESSION["srm_f62014_groupby_column"]);
        }
        if (isset($_SESSION["'srm_f62014_is_template"]) && $_SESSION["srm_f62014_is_template"] == "enabled") {
            $this->set_Is_template($_SESSION["srm_f62014_is_template"]);
        }
        if (isset($_SESSION["srm_f62014_save_template_name"]) && $_SESSION["srm_f62014_save_template_name"] != "") {
            $this->set_Save_template_name($_SESSION["srm_f62014_save_template_name"]);
        }
    }

    /**
     * This function validate the request and make sure it's sent by an admin who is connected to the database
     * @return boolean if the request is sent by a connected admin
     * @throws Exception
     */
    protected function validate_request() {
        //request must be from admin
        if (isset($_SESSION["admin_access_SRM7"]) && is_array($_SESSION["admin_access_SRM7"]) && $_SESSION["admin_access_SRM7"]["role"] == "admin") {
            //a connection with the db is established
            if (isset($_SESSION ["srm_f62014_validate_key"])) {
                return true;
            } else {
                throw new Exception('Error 601: Invalid request as no connection detected.');
            }
        } else {
            throw new Exception('Error 602:Invalid request , only admin can create new reports.');
        }

        //validate security rules
        if ($_SESSION ["srm_f62014_is_public_access"] == "yes") {
            if ($_SESSION ["srm_f62014_allow_only_admin"] = "yes") {
                throw new Exception('Error :Conflicting Access rules, report is defined as public and admin only in the same time!.');
            } elseif ($_SESSION["srm_f62014_security"] != "" || $_SESSION ["srm_f62014_members"] != "") {
                throw new Exception('Error :Conflicting Access rules, report is defined as public yet either the security or members features is enabled! !.');
            } elseif ($_SESSION ["srm_f62014_sec_table"] != "" || $_SESSION ["srm_f62014_sec_Username_Field"] != "" || $_SESSION ["srm_f62014_sec_pass_Field"] != "") {
                throw new Exception('Error :Conflicting Access rules, report is defined as public yet some members settings are defined! !.');
            } elseif ($_SESSION ["srm_f62014_sec_Username"] != "" || $_SESSION ["srm_f62014_sec_pass"] != "") {
                throw new Exception('Error :Conflicting Access rules, report is defined as public yet some user settings are defined! !.');
            }
        } elseif ($_SESSION ["srm_f62014_allow_only_admin"] == "yes") {
            if ($_SESSION ["srm_f62014_sec_Username"] != "" || $_SESSION ["srm_f62014_sec_pass"] != "") {
                throw new Exception('Error :Conflicting Access rules, report is defined as admin only yet some user settings are defined! !.');
            } elseif ($_SESSION ["srm_f62014_sec_table"] != "" || $_SESSION ["srm_f62014_sec_Username_Field"] != "" || $_SESSION ["srm_f62014_sec_pass_Field"] != "") {
                throw new Exception('Error :Conflicting Access rules, report is defined as admin only some members settings are defined! !.');
            } elseif ($_SESSION ["srm_f62014_security"] != "" || $_SESSION ["srm_f62014_members"] != "") {
                throw new Exception('Error :Conflicting Access rules, report is defined as admin only yet either the security or members features is enabled! !.');
            }
        } elseif ($_SESSION ["srm_f62014_members"] != "") {
            if ($_SESSION ["srm_f62014_sec_table"] == "" || $_SESSION ["srm_f62014_sec_Username_Field"] == "" || $_SESSION ["srm_f62014_sec_pass_Field"] == "" || $_SESSION ['srm_f62014_sec_email_field'] == "" || $_SESSION ["srm_f62014_sec_pass_hash_type"] == "") {
                throw new Exception('Error :Conflicting Access rules. The Members feature is enabled yet not all its settings are defined correctly! !.');
            }
        } elseif ($_SESSION["srm_f62014_security"] != "") {
            if ($_SESSION ["srm_f62014_sec_Username"] == "" || $_SESSION ["srm_f62014_sec_pass"] == "" || $_SESSION ["srm_f62014_sec_email"] == "") {
                throw new Exception('Error :Conflicting Access rules. The user account feature is enabled yet not all its settings are defined correctly! !.');
            }
        }
    }

    /**
     * This function keeps the admin key in the session yet remove any other key from it after creating the report.
     * @return boolean
     */
    protected function clear_sessions() {
        $keep = array(
            "admin_access_SRM7"
        );

        foreach ($_SESSION as $key => $value) {
            if (!in_array($key, $keep)) {

                unset($_SESSION [$key]);
            }
        }

        return true;
    }

}

?>