$(function(){
	var i;
	var pickerOpts = {showAnim: 'fold',hideIfNoPrevNext: true,nextText: 'Later',dateFormat:"yy-mm-dd",changeFirstDay: false,changeMonth: false,changeYear: true,closeAtTop: false,showOtherMonths: true,showStatus: true,showWeeks: true,duration: "fast",yearRange: "-100:"};
	//alert($('input[type=text]').length);
	for(i=0;i<$('input[type=text]').length;i++)
	{
		if($('input[type=text]:eq(' + i + ')').attr('alt')=="date")
		{	
			$('input[type=text]:eq(' + i + ')').attr('class',' ');
			$('input[type=text]:eq(' + i + ')').attr('class','datepickera'+i);
			$('.datepickera'+i).datepicker(pickerOpts);
		}
	}
});
function getTypeContent()
{
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("POST","../applications/item/getTypeContent.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
		xmlhttp.send('id='+document.form.type_id.value);//fill the send like send(table=industry&parent=role) while using POST
		xmlhttp.onreadystatechange=function()
								{
										x=xmlhttp.responseText;
										if(x!='')
										{
											document.getElementById('content_code').innerHTML=x;
										}
								}
}
function editTypeContent()
{	
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("POST","../applications/item/getTypeContent.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
		xmlhttp.send('id='+document.form.type_id.value+'&rows='+document.getElementById('rows_optional').value+'&cols='+document.getElementById('cols_optional').value);//fill the send like send(table=industry&parent=role) while using POST
		xmlhttp.onreadystatechange=function()
								{
										x=xmlhttp.responseText;
										if(x!='')
										{
											document.getElementById('content_code').innerHTML=x;
										}
								}
}
function hideDate(td)
{
	$('input[name="'+td+'"]').parent().parent().hide();
}
function hoverStyle(image_name,id)
	{
			$("."+id).mouseover(function(){
				$(this).attr("src","../public/design-images/"+image_name+"_h.png");
			}).mouseout(function(){
				$(this).attr("src","../public/design-images/"+image_name+".png");
			});
	}
