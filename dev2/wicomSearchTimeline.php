<html>
<head>
<title>Web Interface for Commissioning Monitoring - WICOM</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="./css/standard.css" type="text/css" rel="stylesheet" />
<SCRIPT LANGUAGE="JavaScript" SRC="./js/CalendarPopup.js"></SCRIPT>

<SCRIPT>document.write(getCalendarStyles());</SCRIPT>
<script>
	var now = new Date();
	now.setDate(now.getDate()+1);

	var cal = new CalendarPopup('fromDateDiv');
	cal.setYearSelectStartOffset(2);
	cal.showNavigationDropdowns();
	cal.addDisabledDates(formatDate(now,"yyyy-MM-dd"), null);
	var cal2 = new CalendarPopup('toDateDiv');
	cal2.setYearSelectStartOffset(2);
	cal2.showNavigationDropdowns();
	cal2.addDisabledDates(formatDate(now,"yyyy-MM-dd"), null);

	function getCorrectString(form)
	{
		var len=document.getElementById("moduleNames1").length;
		var moduleName;
		var first=1;
		for(i=0; i <len ; i++)
		{
			if(document.getElementById("moduleNames1")[i].selected)
			{
				if(first ==1)
				{
					moduleName = document.getElementById("moduleNames1")[i].value;
					first=0;
				}
				else
				{
					moduleName+="|";
					moduleName+= document.getElementById("moduleNames1")[i].value;
				}

			}
		}

		for(i=0; i <len ; i++)
		{
			if(document.getElementById("moduleNames2")[i].selected)
			{
				if(first)
				{
					moduleName = document.getElementById("moduleNames2")[i].value;

					first=0;
				}
				else
				{
					moduleName+="|";
					moduleName+= document.getElementById("moduleNames2")[i].value;
				}

			}
		}
		for(i=0; i <len ; i++)
		{
			if(document.getElementById("moduleNames3")[i].selected)
			{
				if(first)
				{
					moduleName = document.getElementById("moduleNames3")[i].value;
					first=0;
				}
				else
				{
					moduleName+="|";
					moduleName+= document.getElementById("moduleNames3")[i].value;
				}

			}
		}
		for(i=0; i <len ; i++)
		{
			if(document.getElementById("moduleNames4")[i].selected)
			{
				if(first)
				{
					moduleName = document.getElementById("moduleNames4")[i].value;
					first=0;
				}
				else
				{
					moduleName+="|";
					moduleName+= document.getElementById("moduleNames4")[i].value;
				}

			}
		}
		if(moduleName)
		 document.getElementById("moduleNames").value=moduleName;
		else
		{
			alert("Select at least one module")
			return false;
		}
		//window.alert(moduleName);
		 document.getElementById("runType").value=document.getElementById("runTypeOption").value;
		 document.getElementById("status").value=document.getElementById("statusOption").value;
		start= document.getElementById("fromDate").value;
		document.getElementById("toDate").value;
		end=document.getElementById("toDate").value;
		startDate =  getDateFromFormat(start, "dd/MM/yyyy" )/1000;
		endDate =  getDateFromFormat(end, "dd/MM/yyyy" )/1000;
		daySeconds= 24*60*60;
		numberOfDays = Math.floor(startDate/daySeconds);
		startDate= numberOfDays*daySeconds;

		numberOfDays= Math.ceil(endDate/daySeconds);
		endDate= numberOfDays*daySeconds;

		if(endDate!=0 && startDate!=0)
			if(endDate < startDate)
			{
				alert("The end date must came after the start one.")
				 return false;
			}
		document.getElementById("startDate").value=startDate;
		document.getElementById("endDate").value=endDate;
		return true;
	}


	function resetValues()
	{
		var len=64;
		for(i=0; i <len ; i++)
                {
			document.getElementById("moduleNames4")[i].selected =false;
			document.getElementById("moduleNames3")[i].selected =false;
			document.getElementById("moduleNames2")[i].selected =false;
			document.getElementById("moduleNames1")[i].selected =false;
		}
		for(i=1; i < 5; i++)
		{
			id = "check"+i;
			checkbox= document.getElementById(id);
			text = "text" +i;
			document.getElementById(text).lastChild.nodeValue= "Select all"
			checkbox.checked = false;
		}
		document.getElementById("fromDate").value="";
		document.getElementById("toDate").value="";
		document.getElementById("runTypeOption").selectedIndex=0;
		document.getElementById("statusOption").selectedIndex=0;
	}

	function functionSelect(idnumber)
	{
		var moduleNames="moduleNames" + idnumber;
		var id = "check"+idnumber;
		var text = "text" +idnumber;
		var len=64;
		var checkbox= document.getElementById(id);
		if(checkbox.checked == true)
		{

			for(i=0; i <len ; i++)
				document.getElementById(moduleNames)[i].selected =true;
				document.getElementById(text).lastChild.nodeValue= "Unselect all"
		}
		else
		{
			 for(i=0; i <len ; i++)
                                document.getElementById(moduleNames)[i].selected =false;
				document.getElementById(text).lastChild.nodeValue= "Select all"
		}
	}
</script>
</head>
<body onLoad="resetValues()">

	<!-- Top bar -->
	<div id="top-bar">
	<br>
	</div>
	<!-- /Top bar -->

	<!-- Header -->
	<div id="header">
		<h3 align="center">TileCal Commissioning Offline Shifts</h3>
	</div>
	<!-- /Header -->


	<!-- Main Container -->
	<div id="main-container" align="center">
	<div id="contents" align="center">
	<br>
	<table width="600px" class="main">
	<CAPTION>Select the modules that will be displayed in the Status Timeline</CAPTION>
	<tr>
	<td width="25%">
	<br>
	<select name="moduleNames1" id="moduleNames1" size="10" multiple>
	<?php
		for($i=1; $i<65; $i++)
		{
			echo"<option value='LBA";
			if($i<10)
				echo"0";
			echo$i."'>LBA";
			if($i<10)
				echo"0";
			echo$i."</option>";
		}
	?>
	</select>
	<br />
<input type="checkbox" id="check1" value="1" onClick="javascript:functionSelect('1')"><span id="text1">Select all</span>
	</td>
	<td width="25%">
	<br>
	<select name="moduleNames2" id="moduleNames2" size="10" multiple>
	<?php
		for($i=1; $i<65; $i++)
		{
			echo"<option value='LBC";
			if($i<10)
				echo"0";
			echo$i."'>LBC";
			if($i<10)
				echo"0";
			echo$i."</option>";
		}
	?>
	</select>
	<br />
<input type="checkbox" id="check2" value="2" onClick="javascript:functionSelect('2')"><span id="text2"> Select all </span>
	</td>
	<td width="50%" rowspan='2'>
	<table class="submain" border=1>
        <CAPTION>filter by</CAPTION>
	<tr>
        <th>RunType: </th>
	<td>
	<select name="runTypeOption" id="runTypeOption">
	<option value='' selected>All</option>
	<?php

	$link = mysql_connect("atlasdev1","atlas","") // Connecting with Database MySQL
                or die("Could not connect: " . mysql_error());
        mysql_select_db('tbanalysis', $link) // Selecting data base to work
                or die ("Can't use tbanalysis: " . mysql_error());


	$queryRunType=" select name  from tcaRunType;";
	$resultRunType=mysql_query($queryRunType, $link)
		or die("Could not execute query: " . mysql_error());
	$count=mysql_num_rows($resultRunType);
	for($i=0; $i<$count; $i++)
	{
		$rowsRunType=mysql_fetch_row($resultRunType);
		echo"<option value='".$rowsRunType[0]."'>". $rowsRunType[0]."</option>\n";
	}
	?>
	</select>
	</td></tr>
	<tr>
        <th>Module Status: </th>
        <td>
	<select name="statusOption" id="statusOption">
	<option value='' selected>All</option>
       <?php
	 $queryRunType=" select *  from tcaStatCommentsValues;";
	$resultRunType=mysql_query($queryRunType, $link)
                or die("Could not execute query: " . mysql_error());
        $count=mysql_num_rows($resultRunType);
        for($i=0; $i<$count; $i++)
        {
		$rowsRunType=mysql_fetch_row($resultRunType);
		if($rowsRunType[0]!= 0)
		{
                	echo"<option value='".$rowsRunType[0]."'>";
			$value=strtr (rtrim($rowsRunType[1]),"_", " ");
                        echo"$value</option>\n";
        	}
	}
	 ?>
        </select>
	</td></tr>


	<form name="dateForm">
	<tr>
		<th colspan=3><center>Date</center></th>
	</tr>
	<tr>
	<td><b><em>from:</em></b></td><td><INPUT TYPE="text" ID="fromDate" NAME="fromDate" VALUE="" SIZE=13 READONLY></td>
	<td>
	<A HREF="#"
	onClick="cal.select(document.forms['dateForm'].fromDate,'anchor1','dd/MM/yyyy'); return false;"
	NAME="anchor1" ID="anchor1">select</A></td>
	</tr><tr><td><b><em>to:</em></b></td><td><INPUT TYPE="text" ID="toDate" NAME="toDate" VALUE="" SIZE=13 READONLY></td>
	<td><A HREF="#"
	onClick="cal2.select(document.forms['dateForm'].toDate,'anchor2','dd/MM/yyyy'); return false;"
	NAME="anchor2" ID="anchor2">select</A></td>
	</tr>
	</table>
	</form>



	</td>
	</tr>
	<tr>
	<td width="25%">
	<br>
	<select name="moduleNames3" id="moduleNames3" size="10" multiple>
	<?php
		for($i=1; $i<65; $i++)
		{
			echo"<option value='EBA";
			if($i<10)
				echo"0";
			echo$i."'>EBA";
			if($i<10)
				echo"0";
			echo$i."</option>";
		}
	?>
	</select>
	<br />
<input type="checkbox" id="check3" value="3" onClick="javascript:functionSelect('3')"> <span id="text3">Select all </span>
	</td>
	<td width="25%">
	<br>
	<select name="moduleNames4" id="moduleNames4"  size="10" multiple>
	<?php
		for($i=1; $i<65; $i++)
		{
			echo"<option value='EBC";
			if($i<10)
				echo"0";
			echo$i."'>EBC";
			if($i<10)
				echo"0";
			echo$i."</option>";
		}
	?>
	</select><br />
<input type="checkbox" id="check4" value="4" onClick="javascript:functionSelect('4')"><span id="text4"> Select all </span>
	</td></tr>
	<form name="selectModules" id="selectModules" method="post" action="./wicomViewStatusCommentTimeline.php" onSubmit="return getCorrectString(selectModules);">
	<tr><td colspan="100%"><input type=hidden name="moduleNames" id="moduleNames">
	<input type=hidden name="runType" id="runType">
	<input type=hidden name="status" id="status">
	<input type=hidden name="startDate" id="startDate">
	<input type=hidden name="endDate" id="endDate">
	<input type="submit" value="Submit"></input> <input type="reset" Value="Reset" onCLick="resetValues();"></input></tr></td>
	</form>
	</table>
	<p><font face="arial" size="2">Hint: Use CTRL+ Mouse left click to select more than one module. <br>To unselect a module use CTRL+ Mouse left button too.</p></font>
	</div>
	</div>
	<!-- Footer -->
	<div id="footer">
	&nbsp;Please <a href="mailto: TileCommissioning@cern.ch">send us</a> your comments and suggestions.
	</div>
	<!-- /Footer -->
	<DIV ID="fromDateDiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
	<DIV ID="toDateDiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
</body>
</html>
