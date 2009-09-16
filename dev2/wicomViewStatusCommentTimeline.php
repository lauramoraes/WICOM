<?php

		if (!isset($_GET["startDate"])) $_GET["startDate"]=$_POST["startDate"];
		if (!isset($_GET["endDate"])) $_GET["endDate"]=$_POST["endDate"];
		if (!isset($_GET["status"])) $_GET["status"]=$_POST["status"];
		if (!isset($_GET["runType"])) $_GET["runType"]=$_POST["runType"];
		if (!isset($_GET["moduleNames"])) $_GET["moduleNames"]=$_POST["moduleNames"];

?>

<html>

<head>
<title> Status Comment Timeline </title>
<link href="./css/timeline.css" type="text/css" rel="stylesheet" />

<script>
function matchWidth(elementId, referenceId)
{
    element = document.getElementById(elementId);
    reference = document.getElementById(referenceId);

    if (element.offsetWidth != reference.offsetWidth)
    {
                element.style.width = reference.offsetWidth + 'px';
    }
}

function adjustWidths()
{
	matchWidth('header', 'contents');
	matchWidth('top-bar', 'header');
	matchWidth('footer', 'header');
}
function viewDetailed(moduleName, runNumber)
{
	var URL = "wicomViewDetailedComments.php?moduleName=" + moduleName + "&runNumber=" + runNumber;
	popup= window.open(URL, null , 'width=350,height=375,Scrollbars=YES');
	if(window.focus)
		popup.focus();
}


</script>
</head>

<body>
	<div id="top-bar">
	<form action="wicomViewStatusCommentTimeline.php" name="selectModule" method="post">
	 Select the RunType: 
	 <select name="runType" id="runType">
        <option value='' >All</option>
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
                echo"<option value='".$rowsRunType[0]."'";
		if($_GET["runType"] ==  $rowsRunType[0])
			echo" selected";
		echo ">". $rowsRunType[0]."</option>\n";
        }
        ?>
        </select>

         &nbsp;&nbsp;&nbsp;&nbsp;Select the Module Status:<select name="status" id="status" >
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
                        echo"<option value='".$rowsRunType[0]."' ";
			if($_GET["status"] == $rowsRunType[0])
				echo " selected";
			echo">";
			$value=strtr ($rowsRunType[1],"_", " ");
			echo"$value</option>\n";
                }
        }
         ?>
        </select>
	<input type=hidden name="moduleNames" id="moduleNames" value="<?php echo $_GET["moduleNames"];?>">
	<input type=hidden name="startDate" id="startDate" value="<?php echo $_GET["startDate"];?>">
	<input type=hidden name="endDate" id="endDate" value="<?php echo $_GET["endDate"];?>">
	<input class="btn" value="Apply" type="submit">
	</form>
	</div>
	<div id="header">
	<h1 align="center">TileCal Commissioning Offline Shifts</h1>
	<a href="wicomSearchTimeline.php">Initial page</a>
	</div>
	<!-- /Header -->
        <div id="main-container" align="center">
        <div id="contents" align="center">
	<br>
	<!-- Provide TimeLine table-->
<table cellpadding="0" cellspacing="0"   width="100%" class="main">
<?php
	$color = array("#d3d3d3","green","yellow","red","white", "#ffffbb");
	if($_GET["moduleNames"] && $_GET["moduleNames"]!="")
	{
		$moduleStatVal=array();
		$moduleNames=explode("|", $_GET["moduleNames"]);//Taking module names
		$queryString = "SELECT tcaRun.id as runId, tcaRun.runNumber as runNumber, tcaRunType.id,  idatlas.id, idatlas.code as code, tcaRun.ModuleNumber as idAtlas,  DATE_FORMAT(tcaRun.date ,'%b&nbsp;%D,&nbsp;%Y %k:%i:%s'), LTRIM(tcaRun.comments) as comments, tcaRun.statusCommentsId as statusCommentsId FROM tcaRun, tcaRunType,  idatlas  WHERE idatlas.id = tcaRun.idAtlasId AND tcaRunType.id = tcaRun.runTypeId";
		sort($moduleNames);
		for($i =0; $moduleNames[$i]!=NULL; $i++)
		{
			$moduleStatVal[$moduleNames[$i]]=0;
			$part = substr ($moduleNames[$i], 0,3);
			$idatlasNumber = substr ($moduleNames[$i], 3,2);
			$queryIdatlas = "select id from idatlas where code ='".$part."';";
			$resIdatlas = mysql_query( $queryIdatlas, $link)
				or die("Could not execute query: " . mysql_error());
			$rowIdatlas = mysql_fetch_array($resIdatlas, MYSQL_BOTH);
			$idatlas = $rowIdatlas[0];
			if(!$i)
				$queryString .= " AND (";
			else 
				$queryString .= " OR ";
			$queryString .= "idatlas.id = " . $idatlas;
			$queryString .= " AND tcaRun.ModuleNumber = " . $idatlasNumber;
		}
	$queryString .=") AND ((comments IS NOT NULL AND comments!= \"\") OR statusCommentsId != 0) ";
		if($_GET["runType"])
		{
			$queryRunType2=" select id  from tcaRunType where name='".$_GET["runType"]."';";
			$resultRunType2=mysql_query($queryRunType2, $link)
				or die("Could not execute query: " . mysql_error());
			$rowsRunType2=mysql_fetch_row($resultRunType2);
			$queryString .= " AND tcaRunType.id=\"";
			$queryString .=$rowsRunType2[0];
			$queryString .="\" ";
		}
		 if($_GET["status"])
		{
			
			$queryString .= " AND statusCommentsId=\"".$_GET["status"]."\" ";
		}
		if($_GET["startDate"])
		{
			$temp = $_GET["startDate"];
			$startDate= date("Y/m/d", $temp);
			$queryString .= " AND tcaRun.date >= '$startDate' " ; 
		}
		if($_GET["endDate"])
		{
			 $temp = $_GET["endDate"];
			$endDate= date("Y/m/d", $temp);
			$queryString .= " AND tcaRun.date <= '$endDate' " ; 
		}
		$orderBy =  " ORDER BY tcaRun.date desc, tcaRun.runNumber desc, idatlas.id, tcaRun.ModuleNumber;";
		$queryString = stripslashes($queryString);
		$queryString = $queryString . $orderBy ;
		$result = mysql_query($queryString, $link)
			or die("Could not execute query: " . mysql_error());
		$count=mysql_num_rows($result);
		if($count==0)
		{
			echo "<h3> No Status and Detailed Comments </h3>";
		}
		else
		{
			echo "<CAPTION> Comments (modules x run)";
			if($_GET["startDate"])
			{
				$temp=$_GET["startDate"]*1000;
				echo "<script> var data= new Date(".$temp.");</script>";
				echo " from: <script>document.write(data.getDate()+\"/\"+(data.getMonth()+1)+\"/\"+ data.getFullYear());</script> ";
			}
			if($_GET["endDate"])
			{
				$temp=$_GET["endDate"]*1000;
				echo "<script> var data= new Date(".$temp.");</script>";
				echo " to: <script>document.write(data.getDate()+\"/\"+(data.getMonth()+1)+\"/\"+ data.getFullYear());</script> ";
			}
			echo"</CAPTION>\n";
			for($i=0;$i<$count;$i++)
			{
				if (($rows=mysql_fetch_row($result)) == 0)//taking comment about module/run
					die("<H3>Your search didn't return any data!</H3>");
				//splitting query response on arrays
				list(,$runNumber[$i],$runType[$i] ,, $modulePrefix[$i], $moduleNumber[$i], $runDate[$i],$detailedComment[$i], $statusComment[$i] )=$rows;
			}
			echo"<TR><TH ROWSPAN=\"3\">&nbsp;</TH>";
			for( $i=0, $j=-1;$i<$count;$i++,$j++)
				if(($runNumber[$j]!=$runNumber[$i]))//doesn't write the same RunNumber twice on the table
					echo"<TH>".$runNumber[$i]."</TH>\n";
			echo "<th>Performance Coefficient</th>";
			echo"</TR>";
			echo"<TR>";
			for( $i=0, $j=-1;$i<$count;$i++,$j++)
				if(($runNumber[$j]!=$runNumber[$i]))
					echo"<TD>".$runDate[$i]."</TD>\n";
			echo "<td>Negative: Better<br>Positive: Worse</td>";		
			echo"</TR>";
			echo"<TR>";
			for( $i=0, $j=-1;$i<$count;$i++,$j++)
			{
				if(($runNumber[$j]!=$runNumber[$i]))
				{
					$queryRunType=" select name  from tcaRunType where id=" .$runType[$i].";";
					$resultRunType=mysql_query($queryRunType, $link)
						or die("Could not execute query: " . mysql_error());
					$rowsRunType=mysql_fetch_row($resultRunType);
					echo"<TD>". $rowsRunType[0]."</TD>\n";		
				}
			}
			echo"</TR>";
			$j=0;
			for( $moduleIndex=0; $moduleNames[$moduleIndex]!=NULL; $moduleIndex++)
			{
				echo"<TR><TH>".$moduleNames[$moduleIndex]."</TH>";
				for($i=0;$i<$count; $i=$j)
				{
					$found=false;
					$j=$i;
					for( ; $runNumber[$i]==$runNumber[$j] ;$j++)//agroup comments about the same runNumber on the same column
					{	
						$tempModuleName = $modulePrefix[$j];
						if($moduleNumber[$j]<10)
							$tempModuleName .= 0;
						$tempModuleName .= $moduleNumber[$j];
						if($moduleNames[$moduleIndex]== $tempModuleName)
						{
							if(($statusComment[$j]==0) && ($detailedComment[$j]==NULL)) 
								continue;
							if( ($detailedComment[$j]==NULL && $detailedComment[$j]=="") || $statusComment[$j]==4)
							{
								echo"\n<TD bgcolor=\"".$color[$statusComment[$j]]."\">";
								if($statusComment[$j]<4) 
									if($statusComment[$j]==1) $moduleStatVal[$moduleNames[$moduleIndex]]-=2;
									if($statusComment[$j]==2) $moduleStatVal[$moduleNames[$moduleIndex]]+=1;
									if($statusComment[$j]==3) $moduleStatVal[$moduleNames[$moduleIndex]]+=2;
								echo"<a href=\"javascript:window.location.reload();\" alt=\"$tempModuleName x $runNumber[$j]  -> No detailed comment\" title=\"$tempModuleName x $runNumber[$j] -> No detailed comment\">&nbsp;</a></TD>";
							}
							else
							{
								echo"\n<TD bgcolor=\"".$color[$statusComment[$j]]."\">";
								echo"<a href=\"javascript:viewDetailed('";
								echo $tempModuleName."',". $runNumber[$j].");\" alt=\"$tempModuleName x $runNumber[$j] -> No detailed comment\" title=\"$tempModuleName x $runNumber[$j] ->  There are detailed comments\"alt=\"$tempModuleName x $runNumber[$j] -> No detailed comment\" title=\"$tempModuleName x $runNumber[$j] -> There are detailed comments\"><img width='15' height='15' src=\"./images/editIcon.png\" border='0'> </a></TD>";
							}
							$found=true;
						}

					}
					if($found==false)//if there is no comment about a especific run on this module
					{
						echo"\n<TD bgcolor=\"$color[5]\">";
						echo"&nbsp;</TD>";
					}
				}
				echo "<td>".$moduleStatVal[$moduleNames[$moduleIndex]]."</td>";
				echo"</TR>";	
			}
		}
		
	}
	else echo"No Module Name informed";
?>
</table>
<p>
<br><br>
<table class="main" width="400" border="1">
  <tr> 
    <th colspan="8">&nbsp;</td> Color Code</tr>
  <tr> 
    <td bgcolor="green" width="15">&nbsp;</td>
    <td>OK</td>
    <td width="18" bgcolor="yellow" width="15">&nbsp;</td>
    <td width="116">Some Problems</td>
    <td bgcolor="red" width="15">&nbsp;</td>
    <td>Bad</td>
    <td bgcolor="#FFFFFF" width="15">&nbsp;</td>
    <td>Not to be analized</td>
  </tr> 
  <tr> 
    <td bgcolor="#d3d3d3" width="15">&nbsp;</td>
    <td colspan="8">No Status Comment</td>
  </tr>
  <tr> 
    <td bgcolor="#ffffbb" width="15">&nbsp;</td>
    <td colspan="8">Not relationated to this module</td>
  </tr>
</table>
</p>
</div>
</div>
<!-- Footer -->
<div id="footer">
&nbsp;Please <a href="mailto: TileCommissioning@cern.ch">send us</a> your comments and suggestions.
</div>
<!-- /Footer -->
<script>
	 setTimeout("adjustWidths();", 1*400);
</script>
</body>
</html>
