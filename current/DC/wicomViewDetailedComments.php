<?php
$moduleName = "";
$runNumber = "";
$newComment ="";
$comment= "";
$system=""
if(!$HTTP_POST_VARS) 
	$HTTP_POST_VARS = &$HTTP_GET_VARS;
reset ($HTTP_POST_VARS);
while ( list($key, $val) = each($HTTP_POST_VARS))
{
	if ( $key=="moduleName" ) $moduleName = $val;  //getting the module name
	if ( $key=="runNumber" ) $runNumber = $val;  //getting the module name
	if ( $key=="newComment" ) $newComment = $val;//getting newComment if exists
	if ( $key=="system" ) $system = $val;//getting newComment if exists
}
if (strlen($moduleName) < 5)
	$moduleName = "LB" . $moduleName;

//connecting to mysql database
$mysqlCon = mysql_connect("atlasdev1.cern.ch","lodi","cOAnAd26")
			or die("cannot connect to database server atlasdev1 :(");
mysql_select_db("tbanalysis", $mysqlCon);
?>

<html>
<head>
<title><?php echo"Insert a Summary Comment for $moduleName Module";?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

if($system=="WIS") $css="/afs/cern.ch/user/t/tilecom/public/www/WIS/current/css/standard.css";
if($system=="WICOM") $css="/afs/cern.ch/user/t/tilecom/public/www/WICOM/current/css/standard.css";
if($system=="TILECOM") $css="/afs/cern.ch/atlas/www/GROUPS/SOFTWARE/OO/php/NOVA/TBAnalysis/TileCommAnalysis/version1.3/standard.css";
<link href="<?php echo $css;?>" type="text/css" rel="stylesheet" />
</head>

<body>

	<!-- Top bar -->
	<div id="top-bar">
	<?php
	if($_POST["submit"])
	{
        $modulePrefix = substr($moduleName, 0, 3);
        $moduleNumber = substr($moduleName, -2, 2);
		$selectComment  = "UPDATE tcaRun, idatlas, testsID SET comments='$newComment' ";
       	$selectComment .= "WHERE tcaRun.runNumber=$runNumber ";
       	$selectComment .= "AND tcaRun.idAtlasId=idatlas.id AND idatlas.code='$modulePrefix' ";
        $selectComment .= "AND tcaRun.idTestId=testsID.id AND testsID.testID=$moduleNumber;";
		mysql_query($selectComment, $mysqlCon) or die("FAILURE: " . mysql_error());
		echo "\tSuccessful Operation\n";

	}
	else
		echo "<br />";
	?>
	</div>
	<!-- /Top bar -->

	<!-- Header -->
	<div id="header">
		<h3 align="center">Web Interface for Commissioning Monitoring</h3>
	</div>
	<!-- /Header -->


	<!-- Main Container -->
	<div id="main-container" align="center">
	<div id="contents" align="center">
	<table class="main" width="100%" align="center">
		<tr>
		<!--	<th width="10%">Date</th>-->
			<th width="10%">&nbsp;</th>
			<th width="90%">Detailed Comments</th>

		</tr>	
		<?php
			$i = 0;
			$modulePrefix = substr($moduleName, 0, 3);
			$queryIdatlas = "select id from idatlas where code ='".$modulePrefix."';";
			$resIdatlas = mysql_query( $queryIdatlas, $mysqlCon)
				or die("Could not execute query: " . mysql_error());
			$rowIdatlas = mysql_fetch_array($resIdatlas, MYSQL_BOTH);
			$idatlas = $rowIdatlas[0];

			$moduleNumber = substr($moduleName, -2, 2);
			$selectComment = "SELECT  tcaRun.comments as comments FROM tcaRun, tcaRunType, idatlas WHERE tcaRun.runNumber=$runNumber and idatlas.id = tcaRun.idAtlasId AND tcaRunType.id = tcaRun.runTypeId AND (idatlas.id =$idatlas   AND tcaRun.ModuleNumber = $moduleNumber);"; 
			$resComment = mysql_query($selectComment,$mysqlCon) or die("Failure: " . mysql_error());
			$rowComment = mysql_fetch_row($resComment);
			if($rowComment!==false && $rowComment[0]!=="")
			{
				$comment= $rowComment[0];
				echo "\n\t\t<tr>\n";
				echo "\t\t\t<td>&nbsp;</td>\n";	
				echo "\t\t\t<td>$comment</td>\n";
				echo "\t\t</tr>";
			}
			else
			{
				echo "\n\t\t<tr>\n";
				echo "\t\t\t<td>&nbsp;</td>\n";
				echo "\t\t\t<td>There is no comments for this module</td>\n";
				echo "\t\t</tr>";
			}
			
		  
			echo "\t</table>\n";
	
			if($comment=="")
				echo"\t<h5>Insert your comment about the $moduleName performance in the run $runNumber:</h5>\n";
			else
				echo"\t<h5>Edit this comment about the $moduleName performance in the run $runNumber:</h5>\n";
	?>
	<form action="wicomViewDetailedComments.php" method="post" name="submitComments">
		<input name="moduleName" type="hidden" value="<?php echo"$moduleName";?>">
		<input name="runNumber" type="hidden" value="<?php echo"$runNumber";?>">
		<?php 
			echo "\t\t<textarea name=\"newComment\" cols=\"33\" rows=\"8\">$comment</textarea>";
		?>
		<br />
		
		<input name="submit" type="submit" value="Submit">
		<input name="reset" type="reset" value="Reset">
	</form>
	</div>
	</div>
	<?php
		/* FREE RESULTS*/ 	
		mysql_free_result($resComment);
		/* close connection */
		mysql_close($mysqlCon);?>
	<!-- Footer -->
	<div id="footer">
	&nbsp;Please <a href="mailto: Tile.Commissioning@cern.ch">send us</a> your comments and suggestions.
	</div>
	<!-- /Footer -->
</body>
</html>
