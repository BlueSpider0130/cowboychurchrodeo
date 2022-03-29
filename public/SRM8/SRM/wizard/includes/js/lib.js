function nextToPage(nextPage)
{
	$("body").css({ overflow: "hidden" });
	$('#container').animate({left: "10px"}, 'slow', function(){
		$.ajax({
			url: "index.php",
			type: "post",
			data: "lastActivePage=clear",
			success: function(){
				location.replace(currentURL+"?id="+nextPage);
			}
		});
	});
}

function backToPage(previousPage)
{
	$("body").css({ overflow: "hidden" });
	$('#container').animate({right: "10px"}, 'slow', function(){
		location.replace(currentURL+"?id="+previousPage);
	});
}

function SwitchStatusError()
{
	$("#switchStatus").remove();
	$("#"+currentPage).append( "<span id='switchStatus' style='position: absolute;right: 0px;top: 10px;' class='glyphicon glyphicon-remove'></span>" );
}

function SwitchStatusDone()
{
	$("#switchStatus").remove();
	$("#"+currentPage).append( "<span id='switchStatus' style='position: absolute;right: 0px;top: 10px;' class='glyphicon glyphicon-ok'></span>" );
}

function add(from, to){
	from = "#"+from;
	to = "#"+to;
	var options = new Array();
	var comingOptions = $(from).val();
	if(comingOptions !== "" && comingOptions !== null && typeof comingOptions !== "undefined" && $.isArray(comingOptions) && 
		comingOptions[0] !== "" && comingOptions[0] !== null && typeof comingOptions[0] !==  "undefined") 
			options = comingOptions;
	else return;
	
	$(from+" option:selected").remove();
	
	for(var i = 0; i < comingOptions.length; i++) $(to).append("<option value='"+comingOptions[i]+"'>"+comingOptions[i]+"</option>");
}

function remove(from, to, originalOptions){
	from = "#"+from;
	to = "#"+to;
	var options = new Array();
	var comingOptions = $(from).val();
	var nonComingOption = new Array();
	var count = 0;
	$(from+' option').not(':selected').each(function(){
		nonComingOption[count] = $(this).val();
		count++;
	});
	if(comingOptions !== "" && comingOptions !== null && typeof comingOptions !== "undefined" && $.isArray(comingOptions) && 
		comingOptions[0] !== "" && comingOptions[0] !== null && typeof comingOptions[0] !==  "undefined")
	{
		if(nonComingOption.length === 0) options = originalOptions;
		else
		{
			count = 0;
			for(var x = 0; x < originalOptions.length; x++)
			{
				if($.inArray(originalOptions[x], nonComingOption) === -1)
				{
					options[count] = originalOptions[x];
					count++;
				}
			}
		}
	}else return;
	
	
	
	$(from+" option:selected").remove();
	$(to).empty();
	for(var i = 0; i < options.length; i++) $(to).append("<option value='"+options[i]+"'>"+options[i]+"</option>");
}

function addAll(from, to){
	from = "#"+from;
	to = "#"+to;
	$(from+" option").each(function(){
		$(to).append("<option value='"+$(this).val()+"'>"+$(this).val()+"</option>");
		$(this).remove();
	});
}

function removeAll(from, to, originalOptions){
	from = "#"+from;
	to = "#"+to;
	$(from).empty();
	$(to).empty();
	for(var x = 0; x < originalOptions.length; x++)
	{
		$(to).append("<option value='"+originalOptions[x]+"'>"+originalOptions[x]+"</option>");
	}
}


function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  return s;
}