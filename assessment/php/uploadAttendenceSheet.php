<?php
date_default_timezone_set("Asia/Kolkata");
require_once 'logging_api.php';
include 'ImportAttendenceSheet.php';

$data = array();

if(isset($_GET['files']))
{	
	$error = false;
	$files = array();
	$uploaddir = '../uploads/Attendance/';
	$archiveDir ='../uploads/Attendance/archives/';

	if(!is_dir($uploaddir)){
	 if( mkdir($uploaddir,0777, true) ){
	 	log_event( READ_ATTENDENCE,  __FILE__."  Line: ". __LINE__."  , '".$uploaddir ."' does not exist , so created successfullly." );

	 }
	 else{
	 	$error = true;
	 	$error_msg = "Unable to create upload Directory , ". $uploaddir." does not exist";
	 	log_event( READ_ATTENDENCE,  __FILE__."  Line: ". __LINE__."  , '".$uploaddir ."' does not exist , Error While Creating." );
	 }
	}
	if(!is_dir($archiveDir)){
	 if( @mkdir($archiveDir, 0777, true )){
	 	log_event( READ_ATTENDENCE,  __FILE__."  Line: ". __LINE__."  , '".$archiveDir ."' archive does not exist , so created successfullly." );
	 }
	 else{
	 	$error = error_get_last();
	 	//	echo $error['message'];
	 	log_event( READ_ATTENDENCE,  __FILE__."  Line: ". __LINE__."  , '".$archiveDir ."' archive does not exist , Error While Creating : " .  $error['message']);
	 }
	}


	
	// Get other details from dataType
	$ssc = $_POST['ssc'];
	$jobRole = $_POST['jobRole'];
	$qpcode = $_POST['qpcode'];
	
	
	// Check if file already exist ,if yes then rename it and  move it to archive directory.
	foreach($_FILES as $file)
	{
		if(file_exists($uploaddir.basename($file['name']))){
			log_event( READ_ATTENDENCE,  __FILE__."  Line: ". __LINE__."  , '".$uploaddir.basename($file['name']) ."' already exist , Moving it to archive dir '".$archiveDir.basename($file['name']).'_'.date("Y-m-d H-i-s", time())."'" );
			if(!@rename($uploaddir.basename($file['name']), $archiveDir.basename($file['name']).'_'.date("Y-m-d").'_'.date("H_i_s"))){
				$error = error_get_last();	
				log_event( READ_ATTENDENCE," , Error while moving file to archive directory . ".$error['message']);
			}
		}
	}
	
	{		
		if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
		{								
			$files[] = $uploaddir .$file['name'];
			$excelFilePath = $uploaddir .basename($file['name']);
			log_event( READ_ATTENDENCE, __LINE__."  ". __FILE__. " , Excel File '". $excelFilePath."' has been uploaded successfully." );
	
			// Parse excel file and save in database.			
			if (file_exists($excelFilePath)) {				
                 // Parse the excel file and save it in database.
				 $obj = new ImportAttendenceSheet();
				// Get start and end row from Configuration File or table
				 $connConf = parse_ini_file('config.ini.php');
				 
				 $sheet_no  		= $connConf['attendence_sheet_no'];
				 $startRow  		= $connConf['attendence_start_row'];
				 $endRow 			= $connConf['attendence_end_row'];				 
				 $no_of_columns		= $connConf['attendence_no_of_columns'];
			 				 
		  		 log_event( READ_ATTENDENCE, __LINE__."  ". __FILE__. " , Going to Read Excel from Sheet No".$sheet_no."' , start row : '".$startRow."' , endRow='".$endRow."',' no_of
		  		 col : '".$no_of_columns."'" );
	
 				if(!$obj->importBatchStudents($excelFilePath,$sheet_no,$startRow,$endRow,$no_of_columns,$ssc,$jobRole,$qpcode)){
					log_event( READ_ATTENDENCE, __LINE__."  ". __FILE__. " Set Error to true to send the response to page." );
					$error = true;
				}
			}	
			else {	
				$error_msg = "File ". $excelFilePath." does not exist";					
				log_event( READ_ATTENDENCE, __LINE__."  ". __FILE__. " , File ". $excelFilePath." does not exist" );
				$error = true;				
			}								
		}		
		else
		{	
			log_event( READ_ATTENDENCE, __LINE__."  ". __FILE__. " , Unable to move file to Directory , ". $uploaddir." does not exist" );			
			$error = true;	
			$error_msg = "Unable to move file to Directory , ". $uploaddir." does not exist";		
		}
	}	
	$data = ($error) ? array('error' => "'".$error_msg."'") : array('files' => $files);	
}

else
{	
	$data = array('success' => 'Attendence Sheet have been uploaded succesfully.', 'formData' => $_POST);	
}

echo json_encode($data);

?>