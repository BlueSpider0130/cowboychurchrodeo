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

class TemplateManager {

    private $templates_dir;
    private $all_templates;
    private $selected_template;
    private $templates_count;
    // the sql user who creates the template , since connection with other sql username wont get this template
    private $sql_username;
    // the database that the template has its options as it can't be loaded for any other db'
    private $sql_database;

    public function __construct($templates_dirctory, $user_name, $db) {
        $this->templates_dir = $templates_dirctory;
        $this->sql_username = $user_name;
        $this->sql_database = $db;
    }

    public function get_all_templates() {
        // must be conncetd to load saved templates .
        // if (! is_connected ())
        // return array ();
        $not_templates = array(
            "shared",
            "index.html",
            "index.php"
        );

        $arr = get_dir_file_info($this->templates_dir);
        $this->all_templates = array();
        $titles = array();
        foreach ($arr as $dir) {
            if (!in_array($dir ["name"], $not_templates) && $dir ["size"] > 1) {
                $url = $this->templates_dir . $dir ["name"] . "/";

                if (file_exists($url . "init.php") && file_exists($url . "config.php") && $this->match_db_and_user($url . "init.php")) {
                    require ($url . "config.php");
                    if (isset($template_title) && $template_title != "") {
                        $template = new Template($url, $template_title);
                        if (!in_array($template->title, $titles)) {
                            $titles[] = $template->title;
                            $this->all_templates [] = $template;
                        }
                    }
                }
            }
        }
        return $this->all_templates;
    }

    public function load_template($template_dir_name) {
        $t = $this->is_exist($template_dir_name, false);
        if ($t) {

            if ($t->load())
                return true;
            else
                return false;
        }else {
            //temaplet to be loaded not found
            return false;
        }
    }

    public function Un_load_template() {
        $keep = array(
            'srm_f62014_host',
            'srm_f62014_user',
            'srm_f62014_pass',
            'srm_f62014_db',
            'srm_f62014_active_pages',
            'srm_f62014_validate_key',
            'all_templates',
            'srm_f62014_db_extension',
            "admin_access_SRM7",
            "timeout_srm7",
            "request_token_wizard"
        );

        foreach ($_SESSION as $key => $value) {
            if (!in_array($key, $keep)) {

                unset($_SESSION [$key]);
            }
        }

        $_SESSION ["srm_f62014_page_key"] = "step_2";
        $_SESSION ["srm_f62014_active_pages"] = array(
            "step_2"
        );

        return true;
    }

    /*
     * is_exist
     *
     * checking weather a temaplet name exists to prevent duplication in template names also to validate before loading
     * @param(template_title_or_name) the title or directory name of the template you want to check about
     * @param(Search_by_title) if true it will search by title otherwise it will search by directory name
     * @return the template as an object if exist or false otherwise .
     */

    public function is_exist($template_title_or_name, $Search_by_title = true) {
        // load templates
        $templates = $this->get_all_templates();
        if (count($templates) > 0) {
            foreach ($templates as $t) {
                if ($Search_by_title && strtolower($t->title) == strtolower($template_title_or_name)) {
                    return $t;
                } elseif (!$Search_by_title && strtolower($t->dir_name) == strtolower($template_title_or_name)) {
                    return $t;
                }
            }
        }
        return false;
    }

    /*
     * match_db_and_user
     *
     * checking weather the stored user and db in a template as the same as of the current connection
     *
     * @return true if it is and false otherwise .
     */

    private function match_db_and_user($url) {
        if (file_exists($url)) {
            require ($url);
            if ($this->sql_username == $DB_USER && $this->sql_database == $DB_NAME) {

                return true;
            } else {

                return false;
            }
        }
    }

}
