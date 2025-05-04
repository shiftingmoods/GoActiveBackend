
	function pageDir(dir)
	{
		if((parseInt(cur)+1)>document.pagingForm.all.value || (parseInt(cur)-1)<0 )
		{
			document.pagingForm.page.value='0';
		}
		var cur=document.pagingForm.page.value;
		if( cur == 'NaN')
		{
			var cur='0';
		}
		if(dir=='prev')
		{
			if((parseInt(cur)-1)<0)
			{
				document.pagingForm.page.value='0';
			}
			else
			{
				document.pagingForm.page.value=parseInt(cur)-1;
			}
		}
		if(dir=='next')
		{
			if((parseInt(cur)+1)>document.pagingForm.all.value )
			{
				document.pagingForm.page.value=document.pagingForm.all.value;
			}
			else
			{
				document.pagingForm.page.value=parseInt(cur)+1;
			}
		}
		//alert(cur);
		if(dir=='')
		{
			document.pagingForm.page.value='0';
		}
		document.pagingForm.submit();
	}
	function order(col)
	{
		document.pagingForm.orderBy.value=col;
		var order=document.pagingForm.order.value;
		if(order=='DESC')
		{
			document.pagingForm.order.value='ASC';
		}
		else
		{
			document.pagingForm.order.value='DESC';
		}
		pageDir('');
	}
	function checkAllItems()
	{ 
		var all=document.form.elements.length;
		for(i=0 ; i<all ; i++ )
		{
			if((document.form.elements[i].type=='checkbox' && document.form.elements[i].name!="checkAll"))
				{
					document.form.elements[i].checked= document.form.checkAll.checked;
				}
		}
	}
	