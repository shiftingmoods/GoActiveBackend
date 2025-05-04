
function isValid() 
{
	missinginfo = "";
	
	if(document.form.name)
	{
		if (document.form.name.value == "") {
		missinginfo += "\n     -  Name";
		}
		if(!alphanumeric(document.form.name.value))
		{
		missinginfo += "\n     -  Invalid Name";
		}
	}
	if(document.form.item)
	{
		if (document.form.item.value == "0") {
		missinginfo += "\n     -  Item To Be Edited";
		}
	}
	if(document.form.newParentTable)
	{
		if (document.form.newParentTable.value== "0") {
		missinginfo += "\n     -  The Root Item";
		}
	}
	if (missinginfo != "") 
	{
		missinginfo ="_____________________________\n" +
		"You failed to correctly fill in the:\n" +
		missinginfo + "\n_____________________________" +
		"\nPlease re-enter and submit again!";
		alert(missinginfo);
		return false;
	}
	else return true;
	
}
function alphanumeric(alphane)
{ return true; 
	var numaric = alphane;
	for(var j=0; j<numaric.length; j++)
		{
			var alphaa = numaric.charAt(j);
			var hh = alphaa.charCodeAt(0);
			if((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || hh==32 || hh==45 || hh==58 || hh==95)
			{
			}
			else	
			{
				 return false;
			}
 		}
 return true;
}
function getFileExtension(filename)
{
  var ext = /^.+\.([^.]+)$/.exec(filename);
  return ext == null ? "" : ext[1];
}
function check(input)
{
	var form =eval('document.'+input);
	var all=form.elements.length;
	var missinginfo='';
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	for(i=0 ; i<all-1 ; i++ )
	{
			if((form.elements[i].type=='file'))
			{
				if(form.elements[i].value!="" && getFileExtension(form.elements[i].value)!='jpg' )
				{
						missinginfo += "\n     -Invalid Image Type ( Only .jpg Allowed )";
						$('textarea[name="'+form.elements[i].name+'"]').attr('class','missing');
				}
				else if(getFileExtension(form.elements[i].value))
				{
					
				}
				else
				{
					$('textarea[name='+form.elements[i].name+']').attr('class','');
				}
			}
			if((form.elements[i].type=='textarea'))
			{
				if(form.elements[i].value=="")
				{
					if(form.elements[i].id.indexOf('_optional')=='-1')
					{
						missinginfo += "\n     -Missing  "+form.elements[i].id;
						$('textarea[name="'+form.elements[i].name+'"]').attr('class','missing');
					}
				}
				else
				{
					$('textarea[name='+form.elements[i].name+']').attr('class','');
				}
			}
			
			if((form.elements[i].type=='text') || (form.elements[i].type=='password'))
			{
				$('input[name="'+form.elements[i].name+'"]').attr('class','');
				if(form.elements[i].value=="")
				{
					if(form.elements[i].id.indexOf('_optional')=='-1')
					{
						missinginfo += "\n     -Missing  "+form.elements[i].id;
						$('input[name="'+form.elements[i].name+'"]').attr('class','missing');
					}
				}
				else//restrictions placed here  like email phone
				{	
					
					if(form.elements[i].name.indexOf("email")!=-1 && reg.test(form.elements[i].value) == false) 
					{
						missinginfo += "\n     -Invalid  "+form.elements[i].id;
						$('input[name="'+form.elements[i].name+'"]').attr('class','missing');
					}
				}
				
			}
			if((form.elements[i].type=='select-one'))
			{
				if(form.elements[i].value=="null")
				{
					if(form.elements[i].id.indexOf('_optional')=='-1')
					{
						missinginfo += "\n     -Missing  "+form.elements[i].id;
						$('select[name="'+form.elements[i].name+'"]').attr('class','missing');
					}
				}
				else
				{
					$('select[name="'+form.elements[i].name+'"]').attr('class','');
				}
			}
			if(!alphanumeric(form.elements[i].value))
			{
				if(form.elements[i].name.indexOf("email")!=-1 || form.elements[i].type=="file" || form.elements[i].name=="url")//place exeptions inside if (x==x || y==y)
				{
				}
				else
				{
					missinginfo += "\n     -Invalid  "+form.elements[i].id;
				}
			}
	}
	if (missinginfo != "") 
	{
		missinginfo ="_____________________________\n" +
		"Sorry you have:\n" +
		missinginfo + "\n_____________________________" +
		"\nPlease re-enter and submit again!";
		alert(missinginfo);
		return false;
	}
	else return true;
}
function validateFileType(input,extensions)
{
	var form =eval('document.'+input);
	var all=form.elements.length;
	var missinginfo='';
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	for(i=0 ; i<all-1 ; i++ )
	{
			if((form.elements[i].type=='file'))
			{ 
				var ext='.'+getFileExtension(form.elements[i].value)+' ';
				if(form.elements[i].value!="" && extensions.toLowerCase().indexOf(ext.toLowerCase())==-1)
				{
						missinginfo += "\n     -"+ext+"File Type Not Allowed( Only "+extensions+" Allowed )";
						$('textarea[name="'+form.elements[i].name+'"]').css('background','#fec9f5');
				}
				else if(getFileExtension(form.elements[i].value))
				{
					
				}
				else
				{
					$('textarea[name='+form.elements[i].name+']').css('background','white');
				}
				
			}
			if((form.elements[i].type=='textarea'))
			{
				if(form.elements[i].value=="")
				{
					if(form.elements[i].id.indexOf('_optional')=='-1')
					{
						missinginfo += "\n     -Missing  "+form.elements[i].id;
						$('textarea[name="'+form.elements[i].name+'"]').css('background','#fec9f5');
					}
				}
				else
				{
					$('textarea[name='+form.elements[i].name+']').css('background','white');
				}
			}
			
			if((form.elements[i].type=='text') || (form.elements[i].type=='password'))
			{
				$('input[name="'+form.elements[i].name+'"]').css('background','white');
				if(form.elements[i].value=="")
				{
					if(form.elements[i].id.indexOf('_optional')=='-1')
					{
						missinginfo += "\n     -Missing  "+form.elements[i].id;
						$('input[name="'+form.elements[i].name+'"]').css('background','#fec9f5');
					}
				}
				else//restrictions placed here  like email phone
				{	
					
					if(form.elements[i].name.indexOf("email")!=-1 && reg.test(form.elements[i].value) == false) 
					{
						missinginfo += "\n     -Invalid  "+form.elements[i].id;
						$('input[name="'+form.elements[i].name+'"]').css('background','#fec9f5');
					}
				}
				
			}
			if((form.elements[i].type=='select-one'))
			{
				if(form.elements[i].value=="")
				{
					if(form.elements[i].id.indexOf('_optional')=='-1')
					{
						missinginfo += "\n     -Missing  "+form.elements[i].id;
						$('select[name="'+form.elements[i].name+'"]').css('background','#fec9f5');
					}
				}
				else
				{
					$('select[name="'+form.elements[i].name+'"]').css('background','white');
				}
			}
			if(!alphanumeric(form.elements[i].value))
			{
				if(form.elements[i].name.indexOf("email")!=-1 || form.elements[i].type=="file" || form.elements[i].name=="url")//place exeptions inside if (x==x || y==y)
				{
				}
				else
				{
					missinginfo += "\n     -Invalid  "+form.elements[i].id;
				}
			}
	}
	if (missinginfo != "") 
	{
		missinginfo ="_____________________________\n" +
		"Sorry you have:\n" +
		missinginfo + "\n_____________________________" +
		"\nPlease re-enter and submit again!";
		alert(missinginfo);
		return false;
	}
	else return true;
}
function changeFormMethod(method)
{	
	if(method=="GET" || method=="POST")
	{
		if(method=="GET")
		{
			document.form.action='';
		}
		if(method=="POST")
		{	
			if(check('form'))
			{ 
				document.form.action='../applications/item/saveGeneralItemEdit.php';
			}
			else
			{
				return false;
			}
		}
	}
	if(method=="newLang")
	{
		if(check('form'))
		{
			document.form.action='../applications/itemAjax/saveGeneralItemEdit.php?newLang=true';
		}
		else
		{
			return false;
		}
	}
	form.submit();
}
function sub()
{
	document.form.submit();
}
function setActualEndDate()
{ 
	var d= new Date();
	if(document.form.complete.value=='YES')
	{
		
		document.form.actual_end_date.value=d.getFullYear()+'-'+parseInt(d.getMonth()+1)+'-'+d.getDate()+' '+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
	}
	else
	{
		document.form.actual_end_date.value='0000-00-00 00:00:00';
	}
}
function setEndDate()
{ 
	var d= new Date();
	if(document.form.complete.value=='YES')
	{
		if(confirm('Are you sure? this could not be undo'))
		{
			document.form.end_date.value=d.getFullYear()+'-'+parseInt(d.getMonth()+1)+'-'+d.getDate()+' '+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
		}
		else
		{
			document.form.complete.value='NO';
		}
	}
	else
	{
		document.form.end_date.value='0000-00-00 00:00:00';
	}
}
function setComplete(id)
{ //alert(eval('document.form.complete'+id+'.checked'));
	var value=eval('document.form.complete'+id+'.checked');
	if(value)
	{
		complete='YES';
	}
	else
	{
		complete='NO';
	}
	if(confirm('Are you sure? this could not be undo'))
	{
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("GET","../applications/item/setComplete.php?id="+id+'&complete='+complete,true);
		//xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
		xmlhttp.send();//fill the send like send(table=industry&parent=role) while using POST
		xmlhttp.onreadystatechange=function()
									{
										if (xmlhttp.readyState==4 && xmlhttp.status==200)
										{
											x=xmlhttp.responseText;
											document.getElementById("msg"+id).innerHTML=x;
											changeFormMethodJO("GET");
										}
									}
	}
	else
	{
		eval('document.form.item'+id+'.checked=true');
	}
	
	
}
var schema=new Array();
function showHide(project)
{
	if(document.getElementById('proj_'+project).style.display=='block')
	{
		document.getElementById('proj_'+project).style.display='none';
		document.getElementById('span_'+project).innerHTML='+ ';
		schema[project]='none';
		
	}
	else
	{
		document.getElementById('proj_'+project).style.display='block';
		document.getElementById('span_'+project).innerHTML='- ';
		schema[project]='block';
	}
}
function validateJO()
{
	missinginfo = "";
	if (document.form.actor_id.value == "0") {
	missinginfo += "\n     -  Actor";
	}
	
	if (missinginfo != "") 
	{
		missinginfo ="_____________________________\n" +
		"You failed to correctly fill in the:\n" +
		missinginfo + "\n_____________________________" +
		"\nPlease re-enter and submit again!";
		alert(missinginfo);
		return false;
	}
	else return true;
}
function postForm()
{
	if(check('form'))
	{
		document.getElementById("note").innerHTML='loading . . .';
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		var action=document.form.action;
		xmlhttp.open("POST",action,true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
		var data='';
		var all=form.elements.length;
		for(i=0 ; i<all ; i++ )
		{
			if(form.elements[i].type=="radio")
			{
				if(form.elements[i].checked==true)
				{
					data=data+form.elements[i].name;
					data=data+'=';
					data=data+form.elements[i].value;
				}
			}
			else
			{
				data=data+form.elements[i].name;
				data=data+'=';
				data=data+form.elements[i].value;
			}
			if(i!=all-1)
			{
				data=data+'&';
			}
		}
		xmlhttp.send(data);//fill the send like send(table=industry&parent=role) while using POST
		xmlhttp.onreadystatechange=function()
								{
									if (xmlhttp.readyState==4 && xmlhttp.status==200)
									{
										x=xmlhttp.responseText;
										document.getElementById("note").innerHTML=x;
										if(x.length>100)
										{
											document.getElementById("bigNote").innerHTML=x;
										}
									}
								}
		
	}
}
function postCustomForm(next,dir)
{
	if(dir=='prev')
	{
		window.location=next+".php";
	}
	else
	{
		if(check('form'))
		{
			document.getElementById("note").innerHTML='loading . . .';
			var xmlhttp;
			xmlhttp=new XMLHttpRequest();
			var action=document.form.action;
			xmlhttp.open("POST",action,true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
			var data='';
			var all=form.elements.length;
			for(i=0 ; i<all ; i++ )
			{
				if(form.elements[i].type=="radio")
				{
					if(form.elements[i].checked==true)
					{
						data=data+form.elements[i].name;
						data=data+'=';
						data=data+form.elements[i].value;
					}
				}
				else
				{
					data=data+form.elements[i].name;
					data=data+'=';
					data=data+form.elements[i].value;
				}
				if(i!=all-1)
				{
					data=data+'&';
				}
			}
			xmlhttp.send(data);//fill the send like send(table=industry&parent=role) while using POST
			xmlhttp.onreadystatechange=function()
									{
										if (xmlhttp.readyState==4 && xmlhttp.status==200)
										{
											x=xmlhttp.responseText;
											document.getElementById("note").innerHTML=x;
											if(x=='next')
											{
												if(next=='end')
												{
													 postCustomFormEnd();
												}
												else
												{
													//alert(next);
													window.location=next+".php";
												}
											}
											if(x.length>100)
											{
												document.getElementById("bigNote").innerHTML=x;
											}
										}
									}
			
		}
	}
}
function postCustomFormEnd()
{
			xmlhttp=new XMLHttpRequest();
			var action="../applications/itemCustom/saveGeneralItemEnd.php";
			xmlhttp.open("POST",action,true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
			xmlhttp.send();//fill the send like send(table=industry&parent=role) while using POST
			xmlhttp.onreadystatechange=function()
									{
										if (xmlhttp.readyState==4 && xmlhttp.status==200)
										{
											x=xmlhttp.responseText;
											document.getElementById("note").innerHTML=x;
											if(x!='')
											{
												window.location='../index/index.php?note='+x;
											}
											else
											{
												document.getElementById("note").innerHTML='Please Try Again';
											}
											if(x.length>100)
											{
												document.getElementById("bigNote").innerHTML=x;
											}
										}
									}
			
}
function postMultiFormsEnd()
{
			xmlhttp=new XMLHttpRequest();
			var action="../applications/itemCustom2/saveGeneralItemEnd.php";
			xmlhttp.open("POST",action,true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
			xmlhttp.send();//fill the send like send(table=industry&parent=role) while using POST
			xmlhttp.onreadystatechange=function()
									{
										if (xmlhttp.readyState==4 && xmlhttp.status==200)
										{
											x=xmlhttp.responseText;
											document.getElementById("note").innerHTML=x;
											if(x!='')
											{
												window.location='../index/index.php?note='+x;
											}
											else
											{
												document.getElementById("note").innerHTML='Please Try Again';
											}
											if(x.length>100)
											{
												document.getElementById("bigNote").innerHTML=x;
											}
										}
									}
}
function postMultiForms(tables)
{
var valid=true;
var allTables=tables.length;
var Az=0;
for (var z=0; z<allTables;z++ )
	{
		if(!check(tables[z]))
		{
			valid=false;
		}
	}
	if(valid)
	{
		for (var z=0; z<allTables;z++)
		{
			var form=tables[z]; 
			document.getElementById("note").innerHTML='loading . . .';
			var xmlhttp;
			xmlhttp=new XMLHttpRequest();
			var action=eval('document.'+form+'.action');
			form=eval('document.'+form);
			
			xmlhttp.open("POST",action,true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
			var data='';
			var all=form.elements.length;
			for(var j=0 ; j<all ; j++ )
			{
				if(form.elements[j].type=="radio")
				{
					if(form.elements[j].checked==true)
					{
						data=data+form.elements[j].name;
						data=data+'=';
						data=data+form.elements[j].value;
					}
				}
				else
				{
					data=data+form.elements[j].name;
					data=data+'=';
					data=data+form.elements[j].value;
				}
				if(j!=all-1)
				{
					data=data+'&';
				}
			}
			xmlhttp.send(data);//fill the send like send(table=industry&parent=role) while using POST
			xmlhttp.onreadystatechange=function()
									{	//x=xmlhttp.responseText; alert(tables[Az-1]+'--'+x);
										if (xmlhttp.readyState==4 && xmlhttp.status==200)
										{
											Az++;
											x=xmlhttp.responseText;
											document.getElementById("note").innerHTML=x;
											if(x=='next')
											{
												if(Az==(allTables-1))
												{	alert('end');
													 postMultiFormsEnd();
												}
											}
											//alert('added');
											if(x.length>100)
											{
												document.getElementById("bigNote").innerHTML=x;
											}
										}
									}
		}
	}
}
/*
function postDeleteForm()
{
	if(confirm('Are you sure you want to delete all selected'))
	{
		document.getElementById("note").innerHTML='loading . . .';
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		var action=document.form.action;
		xmlhttp.open("POST",action,true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
		var data='';
		var all=form.elements.length;
		for(i=0 ; i<all ; i++ )
		{
			if((form.elements[i].type=='checkbox' && form.elements[i].checked)|| form.elements[i].name=='table')
			{
				data=data+form.elements[i].name;
				data=data+'=';
				data=data+form.elements[i].value;
				data=data+'&';
			}
		}
		if(data.charAt(data.length-1)=='&')
		{
			data=data.substring(0,data.length-2);
		}
		alert(data); return false;
		xmlhttp.send(data);//fill the send like send(table=industry&parent=role) while using POST
		xmlhttp.onreadystatechange=function()
								{
										x=xmlhttp.responseText;
										document.getElementById("note").innerHTML=x;
								}
	}
}
*/
