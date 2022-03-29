(function() {
    var app = angular.module("app", []);
    app
            .controller(
                    "controller",
                    function($scope, $http, cellCount, cells, formatting) {
                        $scope.loading = true;

                        // the array that stores the selected types
                        $scope.SelecetdcellTypes = new Array();
                        $scope.mode = "cells";
                        // ***************************88////////////////////////////////////////
                        // intialize cell types

                        $scope.disableAppendedText = [];

                        // loading intial data and settings

                        load_data(cells, formatting);

                        // Factory for the conditional formatting
                        function conditionalFormattingRule(filter, column,
                                filterValue1, filterValue2, color) {
                            this.filter = filter;
                            this.column = column;
                            this.filterValue1 = filterValue1;
                            this.filterValue2 = filterValue2;
                            this.color = color;
                        }
                        // Factory For the cell types
                        function SelectedCellType(column, cellType, text) {
                            this.column = column;
                            this.cellType = cellType;
                            this.appendedText = text;

                        }

                        $scope
                                .$watch(
                                        'conditionalFormattingRules.length',
                                        function(newValue, oldValue) {

                                            if ($scope.conditionalFormattingRules.length != 0) {

                                                // $scope.rules = [];
                                                // $scope.rules = JSON
                                                // .stringify($scope.conditionalFormattingRules);
                                                $scope.jsonConditionalFormattingRules = [];
                                                $scope.conditionalFormattingRules
                                                        .forEach(function(elem,
                                                                i) {
                                                            $scope.jsonConditionalFormattingRules
                                                                    .push({
                                                                        value: JSON
                                                                                .stringify(elem),
                                                                        id: i
                                                                    });
                                                        });

                                            } else {
                                                // $scope.rules = JSON;
                                                $scope.jsonConditionalFormattingRules = [];
                                            }

                                        });

                        $scope.selectedFilter = "between";

                        $scope.AddValidationRule = function() {
                            // a filter is selected which means not null and
                            // existed in the
                            // filters array
                       
                            if (!check_var($scope.selectedFilter.value)) {

                                alertify.error("Please select a filter");
                                return;

                            }

                            if (!check_var($scope.column)) {
                                alertify.error("Please select a column");
                                return;

                            }
                            if (!check_var($scope.color)) {
                                alertify.error("Please select a color");
                                return;
                            }

                            var selectedColor = $scope.color.toString();
                            if (selectedColor.indexOf("hsv") != -1) {
                                alertify
                                        .error("Error in color formats, 'hex' is expected not 'hsv' ");
                                return;

                            }

                            if (!check_var($scope.filterValue1)
                                    || ($scope.selectedFilter.value == "between" && !check_var($scope.filterValue2))) {
                                alertify.error("Filter Value can't be empty");
                                return;
                            }

                            var ConditionalFormattingRule = new conditionalFormattingRule(
                                    $scope.selectedFilter.value, $scope.column,
                                    $scope.filterValue1, $scope.filterValue2,
                                    $scope.color);
                            $scope.conditionalFormattingRules
                                    .push(ConditionalFormattingRule);
                            var obj = {
                                formatting: $scope.conditionalFormattingRules
                            };
                            if (send_ajax("services/step_formatting.php", obj) == false) {
                              
                                alertify.error("Server error : Conditional formatting couldn't be saved");
                            } else {

                                alertify.success("Conditional Formatting rule is added successfully");
                            }

                            $scope.column = "";
                            $scope.selectedFilter.value = "";
                            $scope.color = "";
                            $scope.filterValue1 = "";
                            $scope.filterValue2 = "";
                            $("#colorpicker").spectrum(
                                    {
                                        color: "#fff",
                                        preferredFormat: "hex",
                                        showPaletteOnly: true,
                                        togglePaletteOnly: true,
                                        togglePaletteMoreText: 'more',
                                        togglePaletteLessText: 'less',
                                        hideAfterPaletteSelect: true,
                                        palette: [
                                            ["#000", "#444", "#666",
                                                "#999", "#ccc", "#eee",
                                                "#f3f3f3", "#fff"],
                                            ["#f00", "#f90", "#ff0",
                                                "#0f0", "#0ff", "#00f",
                                                "#90f", "#f0f"],
                                            ["#f4cccc", "#fce5cd",
                                                "#fff2cc", "#d9ead3",
                                                "#d0e0e3", "#cfe2f3",
                                                "#d9d2e9", "#ead1dc"],
                                            ["#ea9999", "#f9cb9c",
                                                "#ffe599", "#b6d7a8",
                                                "#a2c4c9", "#9fc5e8",
                                                "#b4a7d6", "#d5a6bd"],
                                            ["#e06666", "#f6b26b",
                                                "#ffd966", "#93c47d",
                                                "#76a5af", "#6fa8dc",
                                                "#8e7cc3", "#c27ba0"],
                                            ["#c00", "#e69138", "#f1c232",
                                                "#6aa84f", "#45818e",
                                                "#3d85c6", "#674ea7",
                                                "#a64d79"],
                                            ["#900", "#b45f06", "#bf9000",
                                                "#38761d", "#134f5c",
                                                "#0b5394", "#351c75",
                                                "#741b47"],
                                            ["#600", "#783f04", "#7f6000",
                                                "#274e13", "#0c343d",
                                                "#073763", "#20124d",
                                                "#4c1130"]]
                                    });

                        }

                        // ng-click="setview('cells')" setview('cells')"
                        $scope.setview = function(mode) {

                            if (mode == "cells") {
                                $scope.mode = "cells";
                            } else {
                                $scope.mode = "conditional";
                            }

                        }

                        function check_var(obj) {

                            if (obj == undefined || obj == null || obj == "")
                                return false;
                            else
                                return true;

                        }


                        function get_time() {
                            var currentdate = new Date();
                            var datetime = "Last Sync: " + currentdate.getDate() + "/"
                                    + (currentdate.getMonth() + 1) + "/"
                                    + currentdate.getFullYear() + " @ "
                                    + currentdate.getHours() + ":"
                                    + currentdate.getMinutes() + ":"
                                    + currentdate.getSeconds();
                            return datetime;
                        }

                        $scope.remove_rule = function(rule) {

                            $scope.conditionalFormattingRules.splice(rule, 1);
                             var obj = {
                                formatting: $scope.conditionalFormattingRules
                            };
                            if (send_ajax("services/step_formatting.php", obj) == false) {
                                alertify.error("Server error : Conditional formatting couldn't be Updated");
                            } else {

                                alertify.success("Conditional Formatting rule is removed successfully");
                            }


                        }

                        $scope.CellTypesClicked = function(cellIndex) {

                            if (check_var($scope.SelecetdcellTypes[cellIndex]) == false) {
                                return;
                            } else {
                                cellType = $scope.SelecetdcellTypes[cellIndex].cellType;
                                if (cellType == "append-l"
                                        || cellType == "append-r") {
                                    $scope.disableAppendedText[cellIndex] = false;

                                } else {
                                    $scope.disableAppendedText[cellIndex] = true;
                                }
                            }

                        }

                        $scope.next = function() {
                            // validating cell types
                            $scope.SelecetdcellTypes
                                    .forEach(function(elt, i) {

                                        if ((elt.cellType == "append-r" || elt.cellType == "append-l")
                                                && elt.appendedText == "")
                                            alertify
                                                    .error("One or more cells has a type of 'Append a text' or 'Prepend a text' yet text is empty");
                                        return;
                                    })

                            //if ($scope.conditionalFormattingRules.length > 0) {

                            //	var obj = {
                            //		cells : $scope.SelecetdcellTypes,
                            //		formatting : $scope.conditionalFormattingRules
                            //	}

                            //} else {

                            var obj = {
                                cells: $scope.SelecetdcellTypes
                            }

                            //	}

                            if (send_ajax("services/step_formatting.php", obj) == false)
                                return;

                            // navigate "next" direction

                            if ($scope.mode == "cells") {
                                $scope.mode = "conditional";
                                $scope.changeMode("#filters-nav");
                            } else {
                                nextToPage("4");
                                SwitchStatusDone();
                            }

                        }
                        // navigation back direction
                        $scope.back = function() {
                            if ($scope.mode == "cells") {
                                backToPage("2");
                            } else {
                                $scope.mode = "cells";
                                $scope.changeMode("#tables-nav");

                            }

                        }

                        $scope.changeMode = function(id) {

                            setTimeout(function() {
                                angular.element(id).triggerHandler('click');
                            }, 100);
                        };

                        $scope.filters = [{
                                key: "Equal",
                                value: "equal"
                            }, {
                                key: "Not Equal",
                                value: "notequal"
                            }, {
                                key: "Greater than",
                                value: "more"
                            }, {
                                key: "Less than",
                                value: "less"
                            }, {
                                key: "Greater than or Equal",
                                value: "moreorequal"
                            }, {
                                key: "Less than or Equal",
                                value: "lessorequal"
                            }, {
                                key: "Between",
                                value: "between"
                            }, {
                                key: "Contain",
                                value: "contain"
                            }, {
                                key: "Not Contain",
                                value: "notcontain"
                            }, {
                                key: "Begin With",
                                value: "beginwith"
                            }, {
                                key: "End With",
                                value: "endwith"
                            }];

                        $scope.cellTypes = [{
                                key: "Standard Cell",
                                value: "value"
                            }, {
                                key: "Image Cell",
                                value: "image"
                            }, {
                                key: "Rating Stars Cell",
                                value: "stars"
                            }, {
                                key: "link cell",
                                value: "link"
                            }, {
                                key: "True Or False Cell",
                                value: "bit"
                            }, {
                                key: "Country Flag Cell",
                                value: "country"
                            }, {
                                key: "Append a text",
                                value: "append-r"
                            }, {
                                key: "prepend a text",
                                value: "append-l"
                            }];

                        function load_data(cells, formatting) {

                            // case no saved settings

                            for (var i = 0; i < cellCount; i++) {

                                if (cells.length > 0 && typeof cells[i] != "undefined") {

                                    var obj = new SelectedCellType(cells[i].column, cells[i].cellType, cells[i].appendedText);
                                    $scope.SelecetdcellTypes.push(obj);
                                    if (cells[i].appendedText.length > 0)
                                        $scope.disableAppendedText.push(false);
                                    else
                                        $scope.disableAppendedText.push(true);

                                } else {

                                    var obj = new SelectedCellType("", "value",
                                            "");
                                    $scope.SelecetdcellTypes.push(obj);

                                    $scope.disableAppendedText.push(true);
                                }

                            }
                            // loading settings for conditional formattings
                            $scope.conditionalFormattingRules = new Array();
                            // The list appended to the text atea on the acreen,
                            $scope.jsonConditionalFormattingRules = new Array();
                            if (formatting.length > 0) {

                                $scope.conditionalFormattingRules = formatting;
                            }

                        }

                        function send_ajax(url, obj) {
                            // value = JSON.stringify(value);

                            $http({
                                url: url,
                                method: "POST",
                                data: JSON.stringify(obj),
                                transformRequest: false,
                                headers: {
                                    'Content-Type': undefined
                                }
                            }).success(function(data, status, headers, config) {
                                if (data == "success") {
                                    return true;
                                } else {
                                    alertify.error(data);
                                    return false;

                                }

                            }).error(function(data, status, headers, config) {
                                alertify.error(data);
                                return false;
                            });

                        }
                        $scope.loading = false;
                        $("#cellsDiv").show();
                        $("#buttonsDIV").show();


                    });

})();
