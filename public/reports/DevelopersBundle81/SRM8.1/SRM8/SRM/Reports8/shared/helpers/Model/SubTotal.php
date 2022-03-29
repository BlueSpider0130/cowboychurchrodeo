<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubTotal
 *
 * @author memad
 */
class SubTotal {

    private $affected_columns = array();  //column
    private $fully_qualified_affected_columns = array(); //table.column
    private $function = "Sum";
    private $group_by_column = "";
    private $fully_qualified_group_by_column = "";
    private $grouped_records = array();
    private $records_count_per_group = array();
    private $sub_total_values_per_group = array();
    private $is_renderd_per_group = array();
    private $grand_totals;

    public function __construct($config_array, $all_records) {

        if (isset($config_array["affected_columns"])) {
            $this->fully_qualified_affected_columns = $config_array["affected_columns"];
            foreach ($config_array["affected_columns"] as $column) {

                $this->affected_columns[] = $this->get_short_name($column);
            }
        }

        $this->function = isset($config_array["function"]) ? strtolower($config_array["function"]) : "";

        if (isset($config_array["group_by"])) {
            $this->fully_qualified_group_by_column = $config_array["group_by"];
            $this->group_by_column = $this->get_short_name($config_array["group_by"]);
        }
        
              

        if (!empty($this->affected_columns) && !empty($this->function) && !empty($this->group_by_column)) {

            $this->calculate_sub_groups($all_records);
        }
    }

    private function get_short_name($fully_qualified_name) {
        if (strstr($fully_qualified_name, ".")) {
            $tmp = explode(".", $fully_qualified_name);
            return $tmp[1];
        } else {
            return $fully_qualified_name;
        }
    }

    public function get_grouped_records() {
        return $this->grouped_records;
    }

    public function get_last_sub_group() {
        $all_keys = array_keys($this->records_count_per_group);
        return $all_keys[count($all_keys) - 1];
    }

    private function calculate_sub_groups($all_records) {

         $key = $this->group_by_column;
        foreach ($all_records as $record) {
            
            if (array_key_exists($key, $record)) {
                if (is_null($record[$key]) || $record[$key] == "" || trim($record[$key]) == "" || empty($record[$key]) || $record[$key] == Null) {
                    $this->grouped_records[""][] = $record;
                    $this->records_count_per_group[""] = count($this->grouped_records[$record[$key]]);
                    $this->is_renderd_per_group[""] = -1;
                } else {
                    $this->grouped_records[$record[$key]][] = $record;
                    $this->records_count_per_group[$record[$key]] = count($this->grouped_records[$record[$key]]);
                    $this->is_renderd_per_group[$record[$key]] = -1;
                }
            } else {
                $this->grouped_records[""][] = $record;
            }
        }


        $this->calculate_grand_totals($all_records);
        $_SESSION["is_group_renderd"] = $this->is_renderd_per_group;
       
    }

    public function get_records_count($group) {
        if (isset($this->records_count_per_group[$group]))
            return $this->records_count_per_group[$group];
        else
            return 0;
    }

    function get_row_index_in_group($row, $group) {
        return array_search($row, array_values($this->grouped_records[$group]));
    }

    private function get_sub_total_results($column, $group, $is_html = true) {
        if (isset($this->sub_total_values_per_group[$group][$this->get_short_name($column)])) {

            return $this->sub_total_values_per_group[$group][$this->get_short_name($column)];
        } else {
            if ($is_html)
                return "&nbsp;";
            else
                return "";
        }
    }

    public function render_sub_total($actual_fields, $group, $layout) {
        if (isset($_SESSION["is_group_renderd"][$group])) {
            $is_renderd_before = $_SESSION["is_group_renderd"][$group];

            if ($is_renderd_before === 1)
                return false;
            else {
                $this->draw_sub_total($actual_fields, $group, $layout);
            }
        } else {
            return false;
        }
    }

    private function draw_sub_total($actual_fields, $group, $layout) {

        echo "<tr class='data-row'>";
        $class = ($layout === "block" || $layout == "stepped")? "SubTotalBlock":"SubTotal";
        $count = 0;

        foreach ($actual_fields as $key => $val) {
              $val= $this->get_short_name($val);
            if (in_array(strtolower($val), array_map('strtolower', $this->affected_columns))) {
                echo "<td class='SubTotal'>"
                . $this->apply_sub_total_function($group, $val) . "</td>";
            } else {
                $empty_content = ($count == 0) ? $this->get_title("sub", $group) . ":" : "&nbsp;";
                echo "<td class='SubTotal'>"
                . str_replace(array("<br>","<br />","<br/>"), " ",$empty_content ) . "</td>";
            }
            $count++;
        }
        echo "</tr>";
        $_SESSION["is_group_renderd"][$group] = 1;
    }

    public function apply_sub_total_function($group, $column, $is_html = true, $is_first = false) {

        if (!isset($this->sub_total_values_per_group[$group])) {
            switch ($this->function) {
                case "count":
                    $this->sub_total_values_per_group[$group] = $this->handle_count($group);
                    break;
                case "sum":
                    $this->sub_total_values_per_group[$group] = $this->handle_sum($group);
                    break;
                case "average":
                    $this->sub_total_values_per_group[$group] = $this->handle_average($group);
                    break;
                case "max":
                    $this->sub_total_values_per_group[$group] = $this->handle_max($group);
                    break;
                case "min":
                    $this->sub_total_values_per_group[$group] = $this->handle_min($group);
                    break;
            }
        }
        if (isset($this->sub_total_values_per_group[$group][$this->get_short_name($column)]))
            return $this->sub_total_values_per_group[$group][$this->get_short_name($column)];
        else {
            if ($is_html)
                return "&nbsp;";
            elseif ($is_first)
                return $this->get_title("sub", $group);
            else
                return "";
        }
    }

    private function handle_count($group) {
        $sub_totals = array();
        foreach ($this->affected_columns as $column) {
            
            $sub_totals[$column] = count(array_filter(array_column($this->grouped_records[$group], $column), "strlen"));
        }
        return $sub_totals;
    }

    private function handle_sum($group) {
        $sub_totals = array();
        foreach ($this->affected_columns as $column) {
          
            $sub_totals[$column] = array_sum(array_column($this->grouped_records[$group], $column));
            $fractions = $sub_totals[$column] - (int) $sub_totals[$column];
            $sub_totals[$column] = ($fractions == 0) ? number_format($sub_totals[$column], 2) : $sub_totals[$column];
        }
        return $sub_totals;
    }

    private function handle_average($group) {
        $sub_totals = array();
        foreach ($this->affected_columns as $column) {
           
            $sum = array_sum(array_column($this->grouped_records[$group], $column));
            $count = count(array_filter(array_column($this->grouped_records[$group], $column), "strlen"));
            if ($count != 0) {
                $sub_totals[$column] = round($sum / $count, 4);
                $fractions = $sub_totals[$column] - (int) $sub_totals[$column];
                $sub_totals[$column] = ($fractions == 0) ? number_format($sub_totals[$column], 2) : $sub_totals[$column];
            } else {
                $sub_totals[$column] = 0;
            }
        }
        return $sub_totals;
    }

    private function handle_max($group) {
        $sub_totals = array();
        foreach ($this->affected_columns as $column) {
            
            $sub_totals[$column] = max(array_column($this->grouped_records[$group], $column));
        }
        return $sub_totals;
    }

    private function handle_min($group) {
        $sub_totals = array();
        foreach ($this->affected_columns as $column) {
          
            $sub_totals[$column] = min(array_column($this->grouped_records[$group], $column));
        }
        return $sub_totals;
    }

    public function slice_grouped_array_from_row($start_row, $take) {
        if (array_key_exists($this->group_by_column, $start_row)) {
            $group = $start_row[$this->group_by_column];
        } 
        if (array_key_exists($group, $this->grouped_records)) {
            $seek = array_search($start_row, array_values($this->grouped_records[$group]));
            $remainig_in_group = (count($this->grouped_records[$group]) - ((int) $seek ));
            if ($remainig_in_group >= $take) {

                $sliced_array[$group] = array_slice($this->grouped_records[$group], $seek, $take);
            } else {

                $taken_records = 0;
                $sliced_array = array();
                foreach ($this->grouped_records as $k => $grouped_array) {
                    if ($taken_records != 0 && $taken_records < $take) {
                        $still_needed_records = $take - $taken_records;

                        if (count($grouped_array) > $still_needed_records) {
                            $sliced_array[$k] = array_slice($grouped_array, 0, $still_needed_records);
                            $taken_records = $take;
                        } else {
                            $sliced_array[$k] = $grouped_array;
                            $taken_records = $taken_records + count($grouped_array);
                        }
                    } elseif ($k == $group) {
                        $sliced_array[$k] = array_slice($grouped_array, $seek, $remainig_in_group);

                        $taken_records = $remainig_in_group;
                    }
                }
            }
            return $sliced_array;
        }
    }

    private function calculate_grand_totals($all_records) {

        foreach ($this->affected_columns as $col) {
           
            switch ($this->function) {
                case "sum":
                    $sum = array_sum(array_column($all_records, $col));
                    $fractions = $sum - (int) $sum;
                    $this->grand_totals[$col] = ($fractions == 0) ? number_format($sum, 2) : $sum;
                    break;
                case "average":
                    $sum = array_sum(array_column($all_records, $col));
                    $count = count(array_filter(array_column($all_records, $col), "strlen"));
                    if ($count != 0) {
                        $average = round($sum / $count, 4);
                        $fractions = $average - (int) $average;
                        $this->grand_totals[$col] = ($fractions == 0) ? number_format($average, 2) : $average;
                    } else {
                        $this->grand_totals[$col] = 0;
                    }
                    break;
                case "count":
                    $this->grand_totals[$col] = count(array_filter(array_column($all_records, $col), "strlen"));
                    break;
                case "min":
                    $this->grand_totals[$col] = min(array_column($all_records, $col));
                    break;
                case "max":
                    $this->grand_totals[$col] = max(array_column($all_records, $col));
                    break;
                default:
                    $this->grand_totals[$col] = 0;
            }
        }
    }

    public function get_grand_totals($key, $is_first_column = false) {
         $key = $this->get_short_name($key);
        $all_keys = array_keys($this->grand_totals);
        if ($is_first_column && !in_array($key, $this->affected_columns))
            return $this->get_title();
        else {

            if (!isset($this->grand_totals[$key]))
                return "";
            else
                return $this->grand_totals[$key];
        }
    }

    public function draw_grand_total($actual_fields,$style="default") {

        echo "<tr class='data-row' $style>";

        $count = 0;

        foreach ($actual_fields as $key => $val) {
            $val = $this->get_short_name($val);
            if (in_array(strtolower($val), array_map('strtolower', $this->affected_columns))) {
                echo "<td class='GrandTotal'>"
                . $this->grand_totals[$val] . "</td>";
            } else {
                $empty_content = ($count == 0 && $style=='default') ? $this->get_title() . ":" : "&nbsp;";
                echo "<td class='GrandTotal'>"
                . $empty_content . "</td>";
            }
            $count++;
        }
        echo "</tr>";
    }

    public function get_title($type = "grand", $group = "") {
        if($group == "")
        $group = "&nbsp;";
        switch ($this->function) {
            case "sum":
                if ($type == "grand")
                    return "Grand Total";
                else
                    return "$group <br /> Total";
                break;
            case "average":
                if ($type == "grand")
                    return "Grand Average";
                else
                    return "$group <br /> Average";
                break;
            case "count":
                if ($type == "grand")
                    return "Total Count";
                else
                    return "$group <br /> Count";
                break;
            case "min":
                if ($type == "grand")
                    return "Total Minimum";
                else
                    return "$group <br /> Minimum";
                break;
            case "max":
                if ($type == "grand")
                    return "Total Maximum";
                else
                    return "$group <br /> Maximum";
                break;
        }
    }

}
