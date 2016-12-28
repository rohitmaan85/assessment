<?php
//ini_set('error_reporting', E_ALL);
include("./lib/MPDF_5_7/MPDF57/mpdf.php");
include('manageExams.php');

if(isset($_GET['examname'])){
	$testName   =   $_GET['examname'];
	$obj = new manageExams();
	log_event(EXPORT_EXAM, "Get Request to export Exam :'".$testName."'" );
	$qstnDivs = $obj->getExamQuestionsDivs($testId,$testName);
}else{
	log_event(EXPORT_EXAM, "Exam Name is Missing :'".$testName."'" );
	$qstnDivs="Exam Name is Missing";
}


$stylesheet = file_get_contents('../css/bootstrap.min.css'); // Get css content
$stylesheet .= file_get_contents('../css/main.css'); // Get css content



$html_content='<!DOCTYPE HTML>
<html>
<head>
 <title>BriskMindTest EMS</title>
 </head>
<body>
<form id="exportAsPDF" class="form-horizontal col-xs-14"><div id="examQuestions" class="col-xs-14">';

//log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,Complete Qstn Div to export PDF = '".$qstnDivs."'" );

$html_content.=$qstnDivs;

//$html_content.='</form></div></body></html>';
$html_content.='</div></form></body></html>';

$mpdf=new mPDF();
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($qstnDivs,2);
//$mpdf->Output();
// Save copy locally

/*$handle = fopen('test.test', "w");
fwrite($handle, $qstnDivs);
fclose($handle);
 */
$mpdf->Output($testName.'_'.date("Y-m-d H:i:s", time()).'.pdf','D');
//$mpdf->Output($testName.'_'.date("Y-m-d H:i:s", time()).'.pdf','D'); // For Download

?>
