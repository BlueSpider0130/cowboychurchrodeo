//**************************** wizard Step 2 **************************************************
var step_2 = new Array();
step_2["host"]=["Smart Report Maker","server name or IP address "];
step_2["user"]=["Smart Report Maker","The user name should have only a “select” permission, any additional permissions are neither recommended nor required"];
step_2["pass"]=["Smart Report Maker","The password of the username that you intend to use to connect to your database "];
step_2["database"]=["Smart Report Maker","the database that you intend to use to create your report."];
step_2["dataSource"]=["Smart Report Maker","table(s) or a SQL query  "];
step_2["Template"] = ["Smart Report Maker","Select the template you want to use, then click the 'Load selected template' button to load it."];
//*************************** wizard step 3 ****************************************************
var step_3 = new Array();
step_3["selectTables"] = ["Smart Report Maker","Choose the table(s) you require for your Report.  You can select Multiple tables by holding the CTRL' key while selecting your tables"];
step_3["rightRelation"] = ["Smart Report Maker","Select the forign key "];
step_3["leftRelation"] = ["Smart Report Maker","Select the parent table, and its primary key "];
step_3["addRelation"] = ["Smart Report Maker","Click to add a relation "];
step_3["removeRelation"] = ["Smart Report Maker","Select the relation which you want to remove"];
step_3["filters"] = ["Smart Report Maker","You can create multiple data filters to get the exact data you wish to include in your report. Your filters will depend on the data type of the filtered columns. For example, if you want to filter a column with a textual data type, you will find only textual filters, such as like, No like, begin with, etc "];
step_3["askusers"] = ["Smart Report Maker","Smart Report Maker supports the ability of creating parameterized reports. Defining report’s parameter is done in this page, the only difference is that instead of entering a fixed filter value, you click the “Ask users” button, then you should click the “Add” button to save this dynamic filter. Finally, When any user open the generatereport they will first see a parameters window to enter the filteration parameters they want. "];
step_3["grouping"] = ["Smart Report Maker","If you have multiple filters, you can combine them with either 'And' or 'or'"];
//*************************** wizard step 3 sql ****************************************************
var step_3_sql = new Array();
step_3_sql["sql"] = ["Smart Report Maker","The SQL statement used to create the report.<br /> <b>Note:</b> avoid using 'order by' because it will be Done visually in a next step."];
step_3_sql["views"] = ["Smart Report Maker","You can load the SQL query from an existing view ."];
//*************************** wizard step 4 ****************************************************
var step_4 = new Array();
step_4["selectFields"] = ["Smart Report Maker","Select The columns that you intend to use in your report (Required )"];
step_4["statisticalFunction"] = ["Smart Report Maker","Select the function which you want to apply"];
step_4["statisticalColumn"] = ["Smart Report Maker","the column on which you want to apply the selected aggregation function. ( it should  have a numeric data type)"];
step_4["statisticalGroupbyColumn"] = ["Smart Report Maker","The 'group by' column. <br/> For example, if you want to generate a report  for  the average salary of male and female employees, “Function”  should be AVG'  Affected column should be 'Salary', Grouped by  'Gender'."];
//*************************** wizard step 5 ****************************************************
var step_5 = new Array();
step_5["groupBy"] = ["Smart Report Maker","For example, you can group your customers by country in order to get the customer details for each country"];
step_5["sortBy"] = ["Smart Report Maker","Sorting data according to any specific column(s) in ascending OR descending order."];
//*************************** wizard step 6 ****************************************************
var step_6 = new Array();
step_6["layout"] = [ "Smart Report Maker","The appearance of your report is very customizable. You can select both the layout and style of your report "];
step_6["style"] = ["Smart Report Maker","This is a list of the available themes"];

step_6["adminUsername"] = ["Smart Report Maker","User name should be between 8 and 16 alphanumeric characters long ( special characters are NOT allowed)"];
step_6["adminPass"] = ["Smart Report Maker","Password should be between 8 and 16 alphanumeric characters long."];
step_6["memberTable"] = ["Smart Report Maker","This feature allows your member’s stored usernames and passwords to access this  report. Simply Select the table that contain the login information of your members "];
step_6["memberUsername"] = ["Smart Report Maker","Select the column that contain the usename information of your members"];
step_6["memberPass"] = ["Smart Report Maker","Select the column that contain the passwords   of your members"];
step_6["memberPassHashType"] = ["Smart Report Maker","If the passwords of your members are encrypted, please select the PHP function.  This will allow for the encryption process."];
step_6["adminEmail"] = ["Smart Report Maker","You can't use the admin email address here."];

step_6["reportTitle"] = ["Smart Report Maker","It Will be displayed at the header of the report"];
step_6["reportLanguage"] = ["Smart Report Maker","Select the language of your generated report."];
step_6["reportIsTemplate"] = ["Smart Report Maker","If you want to save the options  of this report as a template so  you can edit later, Please check the 'Save as template' box."];
step_6["reportTemplate"] = ["Smart Report Maker","If you want to save the options aof this report as a template so  you can edit later, Please enter a template name ."];
step_6["repCategory"] = ["Smart Report Maker","Please select a category for your report"];
step_6["reportFooter"] = ["Smart Report Maker","Report Footer. It could contain HTML tags."];
step_6["reportHeader"] = ["Smart Report Maker","Report Header. It could contain HTML tags"];
step_6["reportName"] = ["Smart Report Maker","Report name."];
    step_6["recordPerPage"] = ["Smart Report Maker","Max number of records that could be displayed in one page. 'Next' and 'Previous' links will be shown in your report to navigate between pages."];

    
    var step_formatting = new Array();
    step_formatting["conditionalFormatting"] = ["Smart Report Maker","This feature allows you to apply special formats to a field, and have that formatting change depending on the value of the field. For example, you can have a field filled in a certain color only when its value is greater than 100."];
    step_formatting["celltype"] = ["Smart Report Maker","Select the cell type that you want to use for each field in your report. Available types are : <br/> <ul><li>Standard Cell : The default type, will just display the data stored in your field without any modifications. </li><li>Image Cell : Can be used in the fields that store image filenames. This type will load the actual images as thumnails in your report. </li> <li> Rating Star Cell : Can be used in the fields that store ratings, which you want to show  as stars in your report. </li> <li> Link Cell : can be used in the fields that store links  </li> <li>True Or False Cell : This type can be used for fields with 'BIT' data type </li> <li>Country Flag Cell : Can be used in fields that store country names. It should display the country's  flag as a thumnail  beside its  name. </li> <li>Append a text : It should add a fixed text such as (miles, pounds,..etc) at the end of the saved data. </li> </ul>"];
	var Sutotals = new Array();
	Sutotals["subtotal"] = ["Smart Report Maker","To include subtotals in your report, please check the 'Allow Subtotals' box then select the the desired summary function and the column(s) for which you want to calculate subtotals.  "];
	// ----------------------------------------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------------------------------------------------------------
// this function to make help system
function help(arr_item, id)
{
	// this attribute is bootstrap popover requirements
	$(id).attr('data-toggle', 'popover');
	
	var title = arr_item[0]; // title
	var content = arr_item[1]; // help msg
	
	// here set up all config about popover bootstrap
	$(id).popover({
		trigger: 'hover',
		container: 'body',
		placement: 'right',
		title: title,
		content: content,
		html: true
	});
}
// here execute the help function for every help icon
help(step_2["host"], "#hostHelp");
help(step_2["user"], "#userHelp");
help(step_2["pass"], "#passHelp");
help(step_2["database"], "#dbHelp");
help(step_2["dataSource"], "#dsHelp");
help(step_2["Template"], "#TemplateHelp");
help(step_formatting["conditionalFormatting"],"#conditionalFormattingHelp");
help(step_formatting["celltype"],"#celltypeHelp");

help(step_3["selectTables"], "#stHelp");
help(step_3["rightRelation"], "#rRelHelp");
help(step_3["leftRelation"], "#lRelHelp");
help(step_3["addRelation"], "#addHelp");
help(step_3["removeRelation"], "#rmHelp");
help(step_3["filters"], "#flHelp");
help(step_3["askusers"], "#askHelp");
help(step_3["grouping"], "#grHelp");

help(step_3_sql["sql"], "#sqlHelp");
help(step_3_sql["views"], "#viewsHelp");

help(step_4["selectFields"], "#sfHelp");
help(step_4["statisticalFunction"], "#statisticalFuncHelp");
help(step_4["statisticalColumn"], "#statisticalColHelp");
help(step_4["statisticalGroupbyColumn"], "#statisticalGroHelp");

help(step_5["groupBy"], "#groupbyHelp");
help(step_5["sortBy"], "#sortHelp");
help(Sutotals['subtotal'],"#subtotalhelp");

help(step_6["layout"], "#layoutHelp");
help(step_6["style"], "#styleHelp");
help(step_6["adminUsername"], "#adminUserHelp");
help(step_6["adminPass"], "#adminPassHelp");
help(step_6["memberTable"], "#memberTableHelp");
help(step_6["memberUsername"], "#memberUserHelp");
help(step_6["memberPass"], "#memberPassHelp");
help(step_6["memberPassHashType"], "#memberPassHashTypeHelp");
help(step_6["adminEmail"], "#adminEmailHelp");
help(step_6["reportTitle"], "#rTitleHelp");
help(step_6["repCategory"], "#CategoryHelp");

help(step_6["reportLanguage"], "#rLanguageHelp");
help(step_6["reportIsTemplate"], "#rIstemplate");
help(step_6["reportTemplate"], "#rtemplate");
help(step_6["reportFooter"], "#rFooterHelp");
help(step_6["reportHeader"], "#rHeaderHelp");
help(step_6["reportName"], "#rNameHelp");
help(step_6["recordPerPage"], "#rPPHelp");