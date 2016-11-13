<?php
require_once 'logging_api.php';
include 'ImportSubjects.php';

$data = array();

if(isset($_GET['files']))
{	
	$error = false;	
	$files = array();	
	$uploaddir = '../uploads/';
	
	// 	Remove all files from upload directory.
	$fileToDelete = glob($uploaddir.'*');	
	foreach($fileToDelete as $file){		
		if(is_file($file)){
	 	   	log_event( LOG_DATABASE,"'".$file ."' File already exists in uploads directory, so remove it succesfully." );
 			unlink($file);		
		}
	}
	
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
				if(!$obj->importSubject(5, 10, "fiNAL 212", $excelFilePath)){
					$error = true;
				}
			}	
			else {				
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " , File ". $excelFilePath."does not exist" );
				$error = true;				
			}								
		}		
		else
		{			
			$error = true;			
		}		
	}	
	$data = ($error) ? array('error' => 'Error while uploading  file.') : array('files' => $files);	
}

else
{	
	$data = array('success' => 'File have been uploaded succesfully.', 'formData' => $_POST);	
}

echo json_encode($data);

?>