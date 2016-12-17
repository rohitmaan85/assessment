<?php
require_once 'logging_api.php';
include 'ImportBatch.php';

$data = array();

if(isset($_GET['files']))
{	
	$error = false;	
	$files = array();	
	$uploaddir = '../uploads/batch/';
	$archiveDir ='../uploads/batch/archives/';
	
	if(!is_dir($uploaddir)){
	 if( mkdir($uploaddir,0777, true) ){
	 	log_event( UPLOAD_BATCH,  __FILE__."  Line: ". __LINE__."  , '".$uploaddir ."' does not exist , so created successfullly." );

	 }
	 else{
	 	$error = true;
	 	$error_msg = "Unable to create upload Directory , ". $uploaddir." does not exist";
	 	log_event( UPLOAD_BATCH,  __FILE__."  Line: ". __LINE__."  , '".$uploaddir ."' does not exist , Error While Creating." );
	 }
	}
	if(!is_dir($archiveDir)){
	 if( @mkdir($archiveDir, 0777, true )){
	 	log_event( UPLOAD_BATCH,  __FILE__."  Line: ". __LINE__."  , '".$archiveDir ."' archive does not exist , so created successfullly." );
	 }
	 else{
	 	$error = error_get_last();
	 	//	echo $error['message'];
	 	log_event( UPLOAD_BATCH,  __FILE__."  Line: ". __LINE__."  , '".$archiveDir ."' archive does not exist , Error While Creating : " .  $error['message']);
	 }
	}
	$error_msg = "";
	foreach($_FILES as $file)
	{		
		if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
		{								
			$files[] = $uploaddir .$file['name'];
			$excelFilePath = $uploaddir .basename($file['name']);
			log_event( UPLOAD_BATCH, __LINE__."  ". __FILE__. " , Excel File '". $excelFilePath."' has been uploaded successfully." );
	
			// Parse excel file and save in database.			
			if (file_exists($excelFilePath)) {				
                 // Parse the excel file and save it in database.
				 $obj = new ImportBatch();
				// Get start and end row from Configuration File or table
				 $connConf = parse_ini_file('config.ini.php');
				 
				 $sheet_no  		= $connConf['batch_sheet_no'];
				 $startRow  		= $connConf['batch_start_row'];
				 $endRow 			= $connConf['batch_end_row'];				 
				 $no_of_columns		= $connConf['batch_no_of_columns'];
			 				 
		  		 log_event( UPLOAD_BATCH, __LINE__."  ". __FILE__. " , Going to Read Excel from Sheet No".$sheet_no."' , start row : '".$startRow."' , endRow='".$endRow."',' no_of
		  		 col : '".$no_of_columns."'" );
	
 				if(!$obj->importBatch($excelFilePath,$sheet_no,$startRow,$endRow,$no_of_columns)){
					log_event( UPLOAD_BATCH, __LINE__."  ". __FILE__. " Set Error to true to send the response to page." );
					$error = true;
				}
			}	
			else {	
				$error_msg = "File ". $excelFilePath." does not exist";					
				log_event( UPLOAD_BATCH, __LINE__."  ". __FILE__. " , File ". $excelFilePath." does not exist" );
				$error = true;				
			}								
		}		
		else
		{	
			log_event( UPLOAD_BATCH, __LINE__."  ". __FILE__. " , Unable to move file to Directory , ". $uploaddir." does not exist" );			
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