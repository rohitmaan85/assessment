<?php
//ini_set('error_reporting', E_ALL);
include("./lib/MPDF_5_7/MPDF57/mpdf.php");
include('EncryptQuestionPaper.php');

if(isset($_GET['examname'])){
	$testName   =   $_GET['examname'];
	$obj = new EncryptQuestionPaperClass();
	$encryptQstn = $obj->getEncryptedQuestionPaper($testId,$testName);
}else{
	$encryptQstn="Exam Name is Missing";
	log_event( LOG_ENCRYPTION, __LINE__."  ". __FILE__.",Exam Name is Missing" );	
}


try {
    header("Content-Type: application/txt");
    header("Cache-Control: no-cache");
    header("Accept-Ranges: none");
    header("Content-Disposition: inline; filename=\"".$testName.'_'.date("Y-m-d H:i:s", time()).".bme\"");
	// For Download
    echo $encryptQstn;

}
catch(PdfcrowdException $why) {
    echo "Can't create Ecnrypted File: ";
}

?>
