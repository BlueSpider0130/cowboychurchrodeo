<?php
/**
 * Smart Report Engine
 * Version 1.0.0
 * Author : StarSoft 
 * All copyrights are preserved to StarSoft
 * URL : http://mysqlreports.com/
 *
 */
use Sre\SmartReportingEngine\src\Engine\Constants;


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
    global $data, $table, $labels, $fields;

    foreach ($data as $val) {

        $fild = ((count($table) == 1) ? "" : $val [0] . ".") . $val [1];
        $fildval = ((count($table) == 1) ? "" : $val [0] . ".") . $val [1];


        if (in_array(strtolower($fild), array_map('strtolower', array_keys($labels)))) {

            if (isset($_POST ["fields"]) && $_POST ["fields"] == $fildval)
                echo " <option value='" . get_numeric_index($fild, $fields) . "' dat='" . map_datatype($val [2]) . "' selected>" . array_get_insensetive_element($fild, $labels) . "</option>\n ";
            else
                echo " <option value='" . get_numeric_index($fild, $fields) . "' dat='" . map_datatype($val [2]) . "' >" . array_get_insensetive_element($fild, $labels) . "</option>\n ";
        }
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
         style="z-index: 1; text-align: center;">
        <div
            style="position: relative; width: 960px; margin-right: auto; margin-left: auto;">
            <div class="nav-holder">
                <ul class="nav-menu clear">
    <?php
    if ($access_mode == "PRIVATE_REPORT") {
        ?>
                        <li class="logout"><a href=<?php echo "logout.php"; ?> class="menu_hvr"
                                              title="<?php echo escape($log_out_language); ?>"><img
                                    src="../shared/images/icons/logout.png"
                                    style="vertical-align: middle;"></a></li>

                    <?php } ?>



    <?php if ($allow_email == "yes") { ?>
                        <li class="logout"><a href=<?php echo "email_report.php" . "?RequestToken=$request_token_value"; ?> class="menu_hvr"
                                              title="<?php echo escape("Email"); ?>"><img
                                    src="../shared/images/icons/email.png"
                                    style="vertical-align: middle;"></a></li>
    <?php
    }
    if ($allow_change_layout == "yes") {
        ?>

                        <li class="theme"><a href="#" class="menu_hvr"
                                             target_class=".sub10"
                                             title="<?php echo escape($change_layout_lang); ?>"><img
                                    src="../shared/images/icons/layout2.png"
                                    style="vertical-align: middle;"></a>
                            <ul class="sub-menu sub10 first-sub-menu">
                                <li class="menu-item-li"><a href=<?php echo "ChangeLayout.php?setlLayout=AlignLeft" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($AlignLeft_lang); ?></a></li>
                                <li class="menu-item-li"><a href=<?php echo "ChangeLayout.php?setlLayout=Block" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($Block_lang); ?></a></li>
                                <li class="menu-item-li"><a href=<?php echo "ChangeLayout.php?setlLayout=Stepped" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($Stepped_lang); ?></a></li>
                                <li class="menu-item-li"><a href=<?php echo "ChangeLayout.php?setlLayout=Outline" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($OutLine_lang); ?></a></li>
                                <li class="menu-item-li"><a href=<?php echo "ChangeLayout.php?setlLayout=Horizontal" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($Horizontal_lang); ?></a></li>
                            </ul></li>

    <?php
    }
    if ($allow_change_style == "yes") {
        ?>



                        <li class="theme"><a href="#" class="menu_hvr"
                                             target_class=".sub6"
                                             title="<?php echo escape($change_theme_lang); ?>"><img
                                    src="../shared/images/icons/change_theme.png"
                                    style="vertical-align: middle;"></a>
                            <ul class="sub-menu sub6 first-sub-menu">
                                <li class="menu-item-li"><a href=<?php echo "ChangeStyle.php?setStyle=blue" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($blue_lang); ?></a></li>
                                <li class="menu-item-li"><a href=<?php echo "ChangeStyle.php?setStyle=grey" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($grey_lang); ?></a></li>
                                <li class="menu-item-li"><a href=<?php echo "ChangeStyle.php?setStyle=teal" . "&&RequestToken=$request_token_value"; ?> > <?php echo escape($teal_lang); ?></a></li>
                            </ul></li>

    <?php
    }
    if ($allow_print_view == "yes") {
        ?>

                        <li class="print"><a href="#" class="menu_hvr" target_class=".sub1"
                                             title="<?php echo escape($print_lang); ?>"><img
                                    src="../shared/images/icons/print.png"
                                    style="vertical-align: middle;"></a>


                            <ul class="sub-menu sub1 first-sub-menu">
                                <li class="menu-item-li"><a href="<?php echo $link_print2; ?>"><?php echo escape($all_pages_lang); ?></a></li>
                                <li class="menu-item-li"><a href="<?php echo $link_print1; ?>"><?php echo escape($current_page_lang); ?></a></li>
                            </ul></li>

    <?php
    }
    if ($allow_export == "yes") {
        ?>
                        <li class="export"><a href="#" class="menu_hvr" target_class=".sub2"
                                              title="<?php echo escape($export_lang); ?>"><img
                                    src="../shared/images/icons/export.png"
                                    style="vertical-align: middle;"></a>

                            <ul class="sub-menu sub2 first-sub-menu">



                                <li class="menu-item-li"><a class="menu_hvr_sub menu-item-a"
                                                            target_class=".sub4" href="#">CSV</a>
                                    <ul class="sub-menu sub4 sub_sub menu-item-ul">
                                        <li class="menu-item-li"><a
                                                href="<?php echo $link_csv_current; ?>" download><?php echo escape($current_page_lang); ?></a></li>
                                        <li class="menu-item-li"><a href="<?php echo $link_csv_all; ?>"download><?php echo escape($all_pages_lang); ?></a></li>
                                    </ul>

                                </li>

                                <li class="menu-item-li"><a class="menu_hvr_sub menu-item-a"
                                                            target_class=".sub3" href="#">PDF</a>
                                    <ul class="sub-menu sub3 sub_sub menu-item-ul">
                                        <li class="menu-item-li"><a
                                                href="<?php echo $link_pdf_current; ?>" download><?php echo escape($current_page_lang); ?></a></li>
                                        <li class="menu-item-li"><a href="<?php echo $link_pdf_all; ?>" download><?php echo escape($all_pages_lang); ?></a></li>
                                    </ul></li>
                                <li class="menu-item-li"><a class="menu_hvr_sub menu-item-a"
                                                            target_class=".sub5" href="#">XML</a>

                                    <ul class="sub-menu sub5 sub_sub menu-item-ul">
                                        <li class="menu-item-li"><a
                                                href="<?php echo $link_xml_current; ?>" download><?php echo escape($current_page_lang); ?></a></li>
                                        <li class="menu-item-li"><a href="<?php echo $link_xml_all; ?>"download><?php echo escape($all_pages_lang); ?></a></li>
                                    </ul></li>


                            </ul></li>
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

