<?php

//parsing configuration file
/*$conf = parse_ini_file("conf/config.php");

$db_name = $conf['db_name'];
$db_host = $conf['db_host'];
$db_login = $conf['db_login'];
$db_pass = $conf['db_pass'];
*/

// Temperory fix
$db_name = "daimler";
$db_host = "localhost";
$db_login = "root";
$db_pass = "root";


//connection to database
$dbo = mysql_connect($db_host, $db_login, $db_pass);

//selecting database
mysql_select_db($db_name, $dbo);

$cycleid = array();
$eventid = array();
$filename = array();
$jsonArr = array();
$jsonArr1 = array();

$sql="SELECT * FROM daimler.rfts2cg_x_basket_c;";
$query=mysql_query($sql);
$row=mysql_fetch_array($query, MYSQL_ASSOC);
$i = 0;
while ($row)
{
	$i++;
	// echo $row['CycleId']."\n";
	$cycleid[]=$row['CycleId'];
	$eventid[]=$row['EventId'];
	$filename[]=$row['FILENAME'];

	$jsonArr[0]=$row['CycleId'];
	$jsonArr[1]=$row['EventId'];
	$jsonArr[2]=$row['FILENAME'];
	$jsonArr1[]=$jsonArr;
	
	if($i==10)
	break;
	$row=mysql_fetch_array($query, MYSQL_ASSOC);
}


$final_array = array('data' => $jsonArr1);

echo json_encode($final_array);

?>