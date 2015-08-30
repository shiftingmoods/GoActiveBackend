<html>
<script>
function run(functionName)
{
	if(document.form.code.value=='')
	{
			alert('Please Enter The Pass Code Below');
			return false;
	}
	if(functionName=='clearControl_p_admin')
	{
		if(!confirm('Are You Sure?\n(This will clear the database and the files)'))
		{
			return false;
		}
	}
		document.getElementById("note").innerHTML='loading . . .';
		var xmlhttp;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.open("POST",'../applications/main/run.php',true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");  //used just for POST not GET
		var data='functionName='+functionName+'&name='+document.form.name.value+'&code='+document.form.code.value;
		xmlhttp.send(data);//fill the send like send(name=industry&parent=role) while using POST
		var i=0;
		var dots='';
		xmlhttp.onreadystatechange=function()
								{
										for(var j=0; j<i ; j++)
										{
											dots=dots+' .';
										}
										i++;
										document.getElementById("note").innerHTML="loading "+dots;
										if(i==4) { var i=0; }
										x=xmlhttp.responseText;
										if (xmlhttp.readyState==4 && xmlhttp.status==200)
										{
											document.getElementById("note").innerHTML=x;
										}
										
										
										
								}
}
</script>
<center>
<body>
<center><span id="note" ></span></center>
<br />
<br />
<form name="form" >
<table>
<tr>
<td>
Input Field : <input type="text" name="name" >
</td>
</tr>
<tr>
<td>
<input type="button" style="width:300px" onclick="run('constructAdminAndDB')" value="Construct Admin And DB Basics" >
</td>
</tr>
<tr>
<td>
<input type="button" style="width:300px" onclick="run('createFilesFromDbTablesAuto')" value="Update Admin From DB Tables" >
</td>
</tr>
<tr>
<td>
<input type="button" style="width:300px" onclick="if(document.form.name.value!=''){ run('deleteTableAndItsFiles'); }else{ alert('Fill The Input Field With The Table Name'); }" value="Delete DB Table And Its Files" >
**
</td>
</tr>
<tr>
<td>
<input type="button" style="width:300px" onclick="run('clearAdmin')" value="Clear Admin Index" >
</td>
</tr>
<tr>
<td>
Pass Code:
<br/>
<input type="password" name="code" >
</td>
</tr>
</table>
</form>
</body>
</center>
<table>
<td>** </td><td>: Table Name In Input Field Required</td>
</td>
</tr>
</table>
</html>