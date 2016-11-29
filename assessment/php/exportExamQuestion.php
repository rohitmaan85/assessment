<?php
//ini_set('error_reporting', E_ALL);
include("./lib/MPDF_5_7/MPDF57/mpdf.php");
include('manageExams.php');

if(isset($_GET['examname'])){
	$testName   =   $_GET['examname'];
	$obj = new manageExams();
	$qstnDivs = $obj->getExamQuestionsDivs($testId,$testName);
}else{
	$qstnDivs="Exam Name is Missing";
}

$stylesheet = file_get_contents('../css/main.css'); // Get css content

$html_content='<!DOCTYPE HTML>
<html>
<head>
 <title>BriskMindTest EMS</title>
    <link rel="stylesheet" type="text/css" href="../css/main.css" />
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css"  href="../css/main.css">
		<link rel="stylesheet" type="text/css"  href="../css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css"  href="../css/dataTables.responsive.css">
  </head>
<body><div id="examQuestions" class="col-xs-12">
<form id="exportAsPDF" class="form-horizontal">';

$html_content.=$qstnDivs;

$html_content.='</form></div></body></html>';

$mpdf=new mPDF();
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($qstnDivs,2);
//$mpdf->Output();
$mpdf->Output('qstns.pdf','D'); // For Download

?>
