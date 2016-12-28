<?php
  //  error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('error_reporting', E_ALL);

date_default_timezone_set("Asia/Kolkata");
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

// Save File Locally for History
$current_timestamp =date("Y-m-d_H_i_s", time());
$file_name = $testName.'_'.$current_timestamp.'.bme';



$download_folder = "../downloads/encrypt_exams/";
if(!is_dir($download_folder)){
	if( @mkdir($download_folder, 0777, true )){
		log_event(DOWNLOAD_ENCRYPT_EXAM,  __FILE__."  Line: ". __LINE__."  , '".$download_folder ."' folder does not exist , so created successfullly." );
	}
}

if (!is_dir($download_folder) or !is_writable($download_folder)) {
    // Error if directory doesn't exist or isn't writable.
    		log_event(DOWNLOAD_ENCRYPT_EXAM,  __FILE__."  Line: ". __LINE__."  , '".$download_folder ."' NO WRITE PERMISSION" );
	
} elseif (is_file($download_folder) and !is_writable($download_folder)) {
    // Error if the file exists and isn't writable.
    		log_event(DOWNLOAD_ENCRYPT_EXAM,  __FILE__."  Line: ". __LINE__."  , '".$download_folder ."'NO WRITE PERMISSION" );
	
}


//$file_name = $testName.'.bme';

$download_folder_file = $download_folder.$file_name;
//log_event(DOWNLOAD_ENCRYPT_EXAM,  __FILE__."  Line: ". __LINE__.",Downloading file in '".$download_folder_file."'" );


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

if (file_put_contents($download_folder_file, $encryptQstn) !== false) {
    log_event(DOWNLOAD_ENCRYPT_EXAM,  __FILE__."  Line: ". __LINE__.", File created (" . basename($download_folder_file).$_SERVER["REMOTE_ADDR"].$obj->jsonFormat);
    $obj->addHistoryInDB($download_folder_file, md5($encryptQstn), $ip, date("Y-m-d H:i:s", time()), $testName, $obj->jsonFormat);
} else {
	  log_event(DOWNLOAD_ENCRYPT_EXAM,  __FILE__."  Line: ". __LINE__.", Cannot create file (" . basename($download_folder_file).")");
	
}



try {
	header("Content-Type: application/txt");
	header("Cache-Control: no-cache");
	header("Accept-Ranges: none");
	header("Content-Disposition: inline; filename=\"".$file_name."\"");
	// For Download
	echo $encryptQstn;

}
catch(PdfcrowdException $why) {
	echo "Can't create Ecnrypted File: ";
}

?>
