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
if ($layout == "horizontal") {
    $span = "colspan='" . 2 . "'";
    $actual_columns_count = 2;
    $align = ($language == "ar" || $language == "iw" ) ? "right" : "left";
} else {
    $span = "colspan='" . count($fields) . "'";
    $align = ($language == "ar" || $language == "iw" ) ? "right" : "left";
}


$dataty = array(
    'varchar',
    'char',
    'text',
    'int',
    'decimal',
    'double',
    'smallint',
    'float',
    'datetime',
    'date',
    'time',
    'year',
    'bit',
    'bool'
);
$dataStr = array(
    'varchar',
    'char',
    'text'
);
$dataInt = array(
    'int',
    'decimal',
    'double',
    'smallint',
    'float'
);
$dataDate = array(
    'datetime',
    'date',
    'time',
    'year',
    'timestamp'
);
$dataBool = array(
    'bit',
    'bool',
    'tinyint'
);

$cond = "";
$params = array();
$types = "";
foreach ($table as $value) {
    $cond .= "table_name = '$value' or ";
// array_push($params, $value);
// $types .="s";
}
$cond .= ")";
$cond = str_replace("or )", " ", $cond);
$flush = true; // Last request in the process
// $resultcon = query( "SELECT table_name,COLUMN_NAME ,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS"
// ." WHERE $cond","Menu: Get Data Types",$params,$types);

$resultcon = query("SELECT table_name,COLUMN_NAME ,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS" . " WHERE  TABLE_SCHEMA = '" . $DB_NAME . "' and ( $cond )", "Menu: Get Data Types");

function lower(&$string) {
    $string = strtolower($string);
}

/**
 * * apply the lower function to the array **
 */
array_walk($fields2, 'lower');

$data = array();
if ($datasource == "sql")
    $resultcon = array();
if (is_array($resultcon)) {
    foreach ($resultcon as $row) {
        $fild = array();

// if(in_array($row['DATA_TYPE'],$dataty) && in_array(((count($table)==1)?"":strtolower($row['table_name']).".").strtolower($row['COLUMN_NAME']),$fields2) )
// {

        foreach ($row as $k => $v) {
            $fild [] = $v;
        }
        if (!in_array($fild, $data)) {
            $data [] = $fild;
        }
// }
    }
}

function printOption() {
    global $data, $table, $labels, $fields, $_CLEANED;
    $posted_field = isset($_CLEANED["SearchField"]) ? $fields[$_CLEANED["SearchField"] - 1] : "";
    foreach ($data as $val) {

        $fild = ((count($table) == 1) ? "" : $val [0] . ".") . $val [1];
        $fildval = ((count($table) == 1) ? "" : $val [0] . ".") . $val [1];

        if (!array_key_exists($fild, $labels))
            continue;
        if ($posted_field == $fildval)
            echo " <option value='" . get_numeric_index($fild, $fields) . "' dat='" . map_datatype($val [2]) . "' selected>" . $labels [$fild] . "</option>\n ";
        else
            echo " <option value='" . get_numeric_index($fild, $fields) . "' dat='" . map_datatype($val [2]) . "' >" . $labels [$fild] . "</option>\n ";
    }
}
?>
<?php
if (!($_startRecord_index + $records_per_page < $nRecords))
    $link_next = '#';
if ($_startRecord_index <= 0)
    $link_prev = '#';
?>
<?php
if ($_print_option == 0) {
    ?>

    <div lang="<?php echo escape($language); ?>" class="menu"
         style="z-index: 1; text-align: center;" >
        <div class="menu-container" style="position: relative; width: 100%; margin-right: auto; margin-left: auto;">
            <div class="nav-holder">


                <?php
                if (isset($allow_admin_home_icon) && $allow_admin_home_icon === "yes" && get_profile() === "admin" && isset($admin_home_url) && $admin_home_url !== "") {
                    ?>
                    <a   class="menu_hvr home-icon menu-icon"   href="<?php echo "$admin_home_url" . "?RequestToken=$request_token_value"; ?>"
                         title="<?php echo escape($lang_home); ?>"><img
                            src="../shared/images/menu/home.png"
                            style="vertical-align: middle;"><span class="home-title"><?php echo escape($lang_home); ?></span></a>

                    <?php
                }
                ?>
                <ul class="nav-menu clear">
                    <?php
                    if ($security == "enabled" || $members == "enabled" || $allow_only_admin == "yes" || !empty($security) || !empty($members) || $sec_pass != "" || get_profile() !== "public" || isset($_SESSION [$admin_login_key]) || isset($_SESSION [$user_login_key])) {
                        ?>
                        <li class="logout"><a class="menu_hvr menu-icon" href=<?php echo "logout.php"; ?>
                                              <span class="menu-icon"><?php echo escape($log_out_language); ?></span>
                                <img src="../shared/images/menu/logout-2.png" style="vertical-align: middle;"></a> </li>

                        <?php
                    }

                    if ($allow_email == "yes") {
                        ?>
                        <li class="logout"><a class="menu_hvr menu-icon" href=<?php echo "email_report.php" . "?RequestToken=$request_token_value"; ?> >
                                <span class="menu-icon">Email</span>
                                <img src="../shared/images/menu/email.png" style="vertical-align: middle;"></a> </li>
                        <?php
                    }

                    if (isset($param) && $param->is_parameter_report() && isset($allow_delete_filter) && $allow_delete_filter == "yes") {
                        ?>
                        <li class="logout"><a
                                href=<?php echo "filter.php?RequestToken=$request_token_value" ; ?>
                                class="menu_hvr menu-icon" title="Filter">
                                <span><?php echo escape($filter_lang); ?></span>
                                <img src="../shared/images/menu/filter.png" style="vertical-align: middle;">
                            </a></li>
                    <?php }
                    if($allow_change_style === "yes"){
                    
                    ?>

                    <li class="theme theme-icon"><a
                            href="#"
                            class="menu_hvr changeTheme" target_class=".sub6" title="Change theme">
                            <!-- <img
                                src="./TEST REPORT_files/change_theme.png" style="vertical-align: middle;"> -->
                            <img src="../shared/images/menu/settings.png"
                                 style="vertical-align: middle;margin-bottom: 5px;">
                            <span class="themesTxt">
                                <img src="../shared/images/menu/left-arrow.png" style="vertical-align: middle;width: 4px;">
                                <?php echo escape($change_theme_lang); ?>
                            </span>
                        </a>
                        <ul class="sub-menu sub6 first-sub-menu special-dropdown">
                            <li class="menu-item-li">

                                <a
                                    href='<?php echo "ChangeStyle.php?setStyle=default" . "&&RequestToken=$request_token_value"; ?>'>
                                    <span class="style-one square-theme"></span>
                                    <?php echo escape($default_lang); ?>
                                </a>
                            </li>
                            <li class="menu-item-li">

                                <a
                                    href='<?php echo "ChangeStyle.php?setStyle=blue" . "&&RequestToken=$request_token_value"; ?>'>
                                    <span class="style-two square-theme"></span>
                                    <?php echo escape($blue_lang); ?>
                                </a>
                            </li>
                            <li class="menu-item-li">

                                <a
                                    href='<?php echo "ChangeStyle.php?setStyle=grey" . "&&RequestToken=$request_token_value"; ?>'>
                                    <span class="style-three square-theme"></span>
                                    <?php echo escape($grey_lang); ?>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <?php
                    }
                    if (!empty($chkSearch) && strtolower($chkSearch) == "yes" && $datasource == 'table') {
                        ?>
                        <li class="header-search">
                            <form action="<?php echo basename($link_home); ?>" method="post">
                                <?php if ($url_param === 1701) { ?>
                                    <input type="hidden" name="DebugMode7"  value="1701" />
                                <?php } ?>
                                <input type="hidden" name="RequestToken"  value=<?php echo $request_token_value; ?> />
                                <input type="text" class="search-txtbox" name="txtordnarySearch"
                                       value="<?php echo get_default_value('txtordnarySearch'); ?>"
                                       id="txtordnarySearch"
                                       placeholder="<?php echo escape($Enter_your_search_lang); ?>" /> <input
                                       type="submit" class="srch-btn" name="btnordnarySearch"
                                       value="<?php echo escape($search_lang); ?>"
                                       id="txtordnarySearch" /> <a href="#" id="SearchAdvanced"
                                       class="srch-btn advanced-link"
                                       title="<?php $advanced_search_lang; ?>"><img
                                        src="../shared/images/icons/tridown.gif" alt=""></a> <input
                                    type="submit" class="srch-btn"
                                    value="<?php echo escape($show_all_lang); ?> " id="btnShowAll"
                                    name="btnShowAll" />


                            </form>
                            <?php
                            if (!empty($chkSearch)) {
                                require_once ("search.php");
                            }
                            ?>

                        </li>
                    <?php } ?>


                </ul>




                <!----2 level sub--->

            </div>


        </div>

    </div>



    <br />



    <script type="text/javascript">
        //fix ie menu
        if (navigator.appName == 'Microsoft Internet Explorer')
        {
            $('.search').css('margin-top', '0px');
            $('.search').css('z-index', '-1');
        }

        var close = false;
        $(function () {
            $('.menu_hvr').mouseover(function () {
                $('.sub-menu').not($($(this).attr('target_class'))).hide();
                $($(this).attr('target_class')).slideDown();
                close = false;
            });

            $('.menu_hvr').mouseleave(function () {
                close = true;
                setTimeout(function () {
                    if (close)
                    {
                        $('.sub-menu').not($($(this).attr('target_class'))).hide();
                    }

                }, 500);
            });
            $('.sub-menu').mouseover(function () {
                close = false;
            });
            $('.sub-menu').mouseleave(function () {
                $('.menu_hvr').mouseleave();
            });

            $('.menu_hvr_sub').mouseover(function () {
                $('.sub_sub').not($($(this).attr('target_class'))).hide();
                $($(this).attr('target_class')).slideDown();
            });
        });
    </script>

    <?php
}
?>

