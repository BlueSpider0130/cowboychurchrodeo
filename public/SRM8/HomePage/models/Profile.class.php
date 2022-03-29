<?php

/**
 * Smart Report Maker
 * Version 8.1.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
if (!defined("DIRECTACESS"))
    exit("No direct script access allowed");

class Profile {
    /*
     * #################################################################################################
     * Attributes of the saved profile . These attributes can only be get from out side the class
     * ################################################################################################
     */

    private $username;
    private $pass;
    private $email;
    private $security_question;
    private $answer_of_security_question;
    private $is_fixed_ip;
    private $is_home;
    private $home_url;
    private $admin_ip;
    private $is_captcha;
    private $is_access_from_user_login;
    private $admin_file_path;
    private $default_mysql_host = "localhost";
    private $default_mysql_user = "";
    private $default_mysql_pass = "";
    private $default_mysql_db = "";

    /*
     * #################################################################################################
     * Attributes of the edited / new profile sent by the admin to be saved .
     * ################################################################################################
     */
    public $new_username;
    public $is_change_password;
    public $new_password;
    public $new_email;
    public $new_security_question_index;
    public $new_answer_of_security_question;
    public $new_is_fixed_ip;
    public $new_is_home;
    public $new_is_captcha;
    public $new_admin_ip;
    public $new_home_url;

    // constractor
    public function __construct($file_path) {
        $this->admin_file_path = $file_path;
        if (file_exists($this->admin_file_path)) {
            require ($this->admin_file_path);
            $this->username = $admin_username;

            $this->pass = $admin_password;

            $this->email = $admin_email;
            $this->security_question = $admin_security_question_index;
            $this->answer_of_security_question = $admin_security_answer;
            $this->is_fixed_ip = $fixed_ip_address;
            $this->is_home = $allow_admin_home_icon;
            $this->home_url = $admin_home_url;
            $this->admin_ip = $admin_ip;
            $this->is_captcha = $allow_captcha;
            $this->is_access_from_user_login = $admin_accessFrom_userLogin;
            $this->default_mysql_host = $default_mysql_host;
            $this->default_mysql_user = $default_mysql_user;
            $this->default_mysql_pass = $default_mysql_pass;
            $this->default_mysql_db = $default_mysql_db;
        } else {
            //no existing admin file
            $this->is_access_from_user_login = "yes";
            $this->is_captcha = "yes";
            $this->is_fixed_ip = "no";
            $this->is_home = "yes";
        }
    }

    /*
     * is_profile_changed
     *
     * This function compares the stored profile with the edited profile sent by the user and decide westher there is any differeance
     *
     * @return true if there is a change in the profile and false otherwise
     */

    public function is_profile_changed() {
        if (strtolower(trim($this->username)) != strtolower(trim($this->new_username))) {
            return true;
        } elseif (strtolower(trim($this->is_change_password)) == "yes" && trim($this->pass) != trim($this->new_password))
            return true;
        elseif (strtolower(trim($this->email)) != strtolower(trim($this->new_email))) {
            return true;
        } elseif ($this->security_question != $this->new_security_question_index) {
            return true;
        } elseif (strtolower(trim($this->answer_of_security_question)) != strtolower(trim($this->new_answer_of_security_question))) {
            return true;
        } elseif (strtolower(trim($this->is_captcha)) != strtolower(trim($this->new_is_captcha))) {
            return true;
        } elseif (strtolower(trim($this->is_fixed_ip)) != strtolower(trim($this->new_is_fixed_ip))) {
            return true;
        } elseif (strtolower(trim($this->is_home)) != strtolower(trim($this->is_home))) {
            return true;
        } elseif (strtolower(trim($this->home_url)) != strtolower(trim($this->new_home_url))) {
            return true;
        } elseif (strtolower(trim($this->admin_ip)) != strtolower(trim($this->new_admin_ip))) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * update
     *
     * This function creats or recreats the admmin file
     * @param(is_new) set to true when creating the admin file for the first time (add) and false otherwise (update)
     *
     * @return true if there is a change in the profile and false otherwise
     */

    public function update($is_new = false) {
        if (!$is_new) {

            // create a tmp admin file
            $fp = fopen(dirname($this->admin_file_path) . "admin_backup.php", "w+");
        } else {

            $fp = fopen($this->admin_file_path, "w+");
        }
        if ($fp) {
            $result = $this->save_config($fp, $is_new);
            if ($result) {
                // case $fp is oki and file is writable

                if (!$is_new) {
                    if (!copy(dirname($this->admin_file_path) . "admin_backup.php", dirname($this->admin_file_path) . "/admin.php")) {
                        // couldn't rename the old file

                        return false;
                    }

                    unlink(dirname($this->admin_file_path) . "admin_backup.php");
                }
                // operation went oki
                // send email to the admin email (old and new when updating only!
                if (!$is_new)
                    $this->notify_admin_for_updates($is_new);

                return true;
            } else {
                // $fp is oki but not writable

                return false;
            }
            fclose($fp);
        } else {
            // $fp is false so no changes

            return false;
        }
    }

    private function save_config($fp, $is_new = false) {
        if (fwrite($fp, "<?php" . PHP_EOL)) {
            fwrite($fp, 'if (! defined ( "DIRECTACESS" )) 	exit ( "No direct script access allowed" );' . PHP_EOL);
            fwrite($fp, '$admin_username = ' . "'" . $this->new_username . "';" . PHP_EOL);

            if (strtolower(trim($this->is_change_password)) == "yes" || $is_new == true)
                fwrite($fp, '$admin_password = ' . "'" . $this->new_password . "';" . PHP_EOL);
            else
                fwrite($fp, '$admin_password = ' . "'" . $this->pass . "';" . PHP_EOL);
            fwrite($fp, '$admin_email = ' . "'" . $this->new_email . "';" . PHP_EOL);
            fwrite($fp, '$admin_security_question_index = ' . $this->new_security_question_index . ";" . PHP_EOL);
            fwrite($fp, '$admin_security_answer = ' . "'" . $this->new_answer_of_security_question . "';" . PHP_EOL);
            fwrite($fp, '$fixed_ip_address = ' . "'" . $this->new_is_fixed_ip . "';" . PHP_EOL);
            fwrite($fp, '$allow_admin_home_icon = ' . "'" . $this->new_is_home . "';" . PHP_EOL);
            fwrite($fp, '$admin_home_url = ' . "'" . $this->new_home_url . "';" . PHP_EOL);
            fwrite($fp, '$admin_ip = ' . "'" . $this->new_admin_ip . "';" . PHP_EOL);
            fwrite($fp, '$allow_captcha = ' . "'" . $this->new_is_captcha . "';" . PHP_EOL);
            if (!$is_new)
                fwrite($fp, '$admin_accessFrom_userLogin = ' . "'" . $this->is_access_from_user_login . "';" . PHP_EOL);
            else
                fwrite($fp, '$admin_accessFrom_userLogin = ' . "'yes';" . PHP_EOL);
            fwrite($fp, '$default_mysql_host =' . "'" . $this->default_mysql_host . "';" . PHP_EOL);
            fwrite($fp, '$default_mysql_user =' . "'" . $this->default_mysql_user . "';" . PHP_EOL);
            fwrite($fp, '$default_mysql_pass =' . "'" . $this->default_mysql_pass . "';" . PHP_EOL);
            fwrite($fp, '$default_mysql_db =' . "'" . $this->default_mysql_db . "';" . PHP_EOL);
            return true;
        } else {

            return false;
        }
    }

    /*
     * #################################################################################################
     * Properties to access the stored admin attributes .
     * ################################################################################################
     */

    public function get_username() {


        return $this->username;
    }

    public function get_email() {
        return $this->email;
    }

    public function get_security_question_index() {
        return $this->security_question;
    }

    public function get_security_answer() {
        return $this->answer_of_security_question;
    }

    public function get_is_fixed_ip() {
        return $this->is_fixed_ip;
    }

    public function get_is_captcha() {
        return $this->is_captcha;
    }

    public function get_is_home_icon() {
        return $this->is_home;
    }

    public function get_admin_ip() {
        return $this->admin_ip;
    }

    public function get_home_url() {
        return $this->home_url;
    }

    public function get_current_password() {


        return $this->pass;
    }

    protected function notify_admin_for_updates() {
        if ($this->email != $this->new_email) {
            $notify_list = array(
                $this->email,
                $this->new_email
            );
        } else {
            $notify_list = array(
                $this->email
            );
        }

        require ("email_templates/notify_profile_updates.php");
        $message = $update_profile_message;

        foreach ($notify_list as $to) {

            mail($to, "Atten: Smart Report Maker Profile Update Notification!", $message);
        }
    }

}
