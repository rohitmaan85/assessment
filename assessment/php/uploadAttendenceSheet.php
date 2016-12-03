<?php
require_once 'logging_api.php';
include 'ImportBatch.php';

$data = array();

if(isset($_GET['files']))
{	
	$error = false;	
	$files = array();	
	$uploaddir = '../uploads/attendence/';
	
	// 	Remove all files from upload directory.
	$fileToDelete = glob($uploaddir.'*');	
	foreach($fileToDelete as $file){		
		if(is_file($file)){
	 	   	log_event( LOG_DATABASE,"'".$file ."' File already exists in uploads directory, so remove it succesfully." );
 			unlink($file);		
		}
	}
	$error_msg = "";
	foreach($_FILES as $file)
	{		
		if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
		{								
			$files[] = $uploaddir .$file['name'];
			$excelFilePath = $uploaddir .basename($file['name']);
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " , Excel File '". $excelFilePath."' has been uploaded successfully." );
	
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
			 				 
		  		 log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " , Going to Read Excel from Sheet No".$sheet_no."' , start row : '".$startRow."' , endRow='".$endRow."',' no_of
		  		 col : '".$no_of_columns."'" );
	
 				if(!$obj->ImportAttendenceSheet($excelFilePath,$sheet_no,$startRow,$endRow,$no_of_columns)){
					log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " Set Error to true to send the response to page." );
					$error = true;
				}
			}	
			else {	
				$error_msg = "File ". $excelFilePath." does not exist";					
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " , File ". $excelFilePath." does not exist" );
				$error = true;				
			}								
		}		
		else
		{	
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " , Unable to move file to Directory , ". $uploaddir." does not exist" );			
			$error = true;	
			$error_msg = "Unable to move file to Directory , ". $uploaddir." does not exist";		
		}
	}	
	$data = ($error) ? array('error' => "'".$error_msg."'") : array('files' => $files);	
}

else
{	
	$data = array('success' => 'Batch Information have been uploaded succesfully.', 'formData' => $_POST);	
}

echo json_encode($data);

?>