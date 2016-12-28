<?php
date_default_timezone_set("Asia/Kolkata");
require_once 'logging_api.php';
include 'ImportSubjects.php';

$data = array();

if(isset($_GET['files']))
{	
	$error = false;	
	$files = array();	
	$uploaddir = '../uploads/ssc/';
	
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
				$obj = new ImportSubjects();
				if(!$obj->importSubject(5, 220, "fiNAL 212", $excelFilePath)){
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
	$data = array('success' => 'SSC and Job Roles have been uploaded succesfully.', 'formData' => $_POST);	
}

echo json_encode($data);

?>