<?php
/*
 * PHP Excel - Read a simple 2007 XLSX Excel file
 */

/** Set default timezone (will throw a notice otherwise) */
//date_default_timezone_set('America/Los_Angeles');


date_default_timezone_set("Asia/Kolkata");
include 'Classes/PHPExcel/IOFactory.php';
include 'DbConn.php';
require_once 'logging_api.php';



class ImportQuestionsClass{

	var $insertSQL = "";
	var $dbConnObj = "";
	var $total_no_of_qstn_upload = "";

	function readExcel($inputFileName,$sheetNo,$startRow,$endROw,$start_column,$no_of_columns,$ssc,$jobRole,$qpcode,$category,$module)
	{
		log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__. " ,  Module : " .$module);

		$row_value = "";
		//Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			log_event( UPLOAD_QUESTION, __LINE__.__FILE__."  , ERROR #" .'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
			. '": ' . $e->getMessage() );
			return  false;
		}
		try{
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet($sheetNo);
			foreach( $sheet->getRowIterator($startRow, $endRow) as $row ){
				$columnIndex = 0;

				$row_value = "'".$columnIndex."','".$ssc."','".$jobRole."','".$qpcode."','".$category."','".$module."',";
				$qstn_type = "";
				foreach( $row->getCellIterator() as $cell ){
					$columnIndex++;
					if($columnIndex > $start_column){
						$value = $cell->getCalculatedValue();
						if($columnIndex == 17){
							if($qstn_type== "image"){
								$image_path = "./images/question/" . $value;
								log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__.", Info : Question type is image  , Going to read image file from :".$value );
								$imgData = file_get_contents($value);
							}else{
								$imgData="";
							}
							// Does not work if size is greater than 1 MB , not recommeneded to store image in DB.
							//$row_value .= "'".$value."','".mysqli_real_escape_string($imgData)."',";
							$row_value .= "'".$image_path."','".$value."',";
							
						}
						else{								
						
							$row_value .= "'".$value."',";
							// Read Image to insert it into Blob type in DB
							if($columnIndex == 7){
								$qstn_type=$value;
								// log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__.", Info : Setting Question Type :"  . $qstn_type );
							}
						}

						if($columnIndex==$no_of_columns)
						break;
					}
				}
				$this->buildInsertSql(substr($row_value, 0,(strlen($row_value)-1)));
			}
		} catch (Exception $e) {
			log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__."  , ERROR # "  . $e );
			return  false;
		}
		return true;
	}

	function buildInsertSql($row_value){
		// $qstnId = mt_rand(1, 50000)."_".$qpCode."_".date("Y-m-d H:i:s", time());
		// $row_value = "'".$qstnId."',".$row_value;
		$row_value.=",'".date("Y-m-d H:i:s", time())."',null,'".date("Y-m-d H:i:s", time())."','active'";

		$sql = "INSERT INTO `assessment`.`question`(`s.no`,`ssc`,`job_role`,`qp_code`,
		`category`,`module`,`type`,`question`,`optiona`,`optionb`,`optionc`,
		`optiond`,`correctanswer`,`marks`,`language`,`no_of_options`,`image_path`,`image`,`uploadDate`,`createDate`,`last_modified_on`,`status`)
		 VALUES(".$row_value.");";		
		
		 log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__.", Info : Setting Question Type :"  . $sql );
					
		
		$this->insertSQL .=$sql;
	}

	function insertInToDatabase()
	{
		
		$conn = DbConn::getDbConn();
		//log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__."  ,  SQL to insert Question '".$this->insertSQL."'" );
		if (mysqli_multi_query($conn, $this->insertSQL)) {
			do{
				//echo array_shift($query_type[1]),": ";
				if($result=mysqli_store_result($conn)){  //if has a record set, like SELECT
					//echo "Selected rows = ".mysqli_num_rows($result)."<br>";
					mysqli_free_result($result);
				}else{  //if only returning true or false, like INSERT/UPDATE/DELETE/etc.
					$cumulative_rows+=$aff_rows=mysqli_affected_rows($conn);
					//echo "Current Query's Affected Rows = $aff_rows, Cumulative Affected Rows = $cumulative_rows<br>";
				}
			} while(mysqli_more_results($conn) && mysqli_next_result($conn));
			
			$this->total_no_of_qstn_upload = $cumulative_rows;

				log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__."  , Success !  Questions inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		}
		else {
			log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function importQuestions($excelFilePath,$sheetNo,$startRow,$endROw,$start_column,$no_of_columns,$ssc,$jobRole,$qpcode,$category,$module)
	{
		$obj_history = new manageHistory();
		if($this->readExcel($excelFilePath,$sheetNo,$startRow,$endROw,$start_column,$no_of_columns,$ssc,$jobRole,$qpcode,$category,$module)){
			if($this->insertInToDatabase()){
				$obj_history->addUploadQuestionHistory("upload",$excelFilePath,$ssc,$jobRole,$category,$module, "1", 0,'Question insrted successfully : '.$this->total_no_of_qstn_upload);
				return true;
			}
			else{
				$obj_history->addUploadQuestionHistory("upload",$excelFilePath,$ssc,$jobRole,$category,$module, "1", 1,'ERROR while inserting questions in database');
				log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__. " ,  ERROR while inserting questions in database." );
				return false;
			}
		}
		else{
			$obj_history->addUploadQuestionHistory("upload",$excelFilePath,$ssc,$jobRole,$category,$module, "1", 1,'ERROR while inserting questions in database');
			log_event( UPLOAD_QUESTION, __LINE__."  ". __FILE__. " ,  ERROR while reading excel file." );
			return false;
		}
	}
}


/*
$excelFilePath='C:/D_Drive/Harendar/Excel_Files/Questions/Qstn_format_Image.xlsx';
$obj = new ImportQuestionsClass();
$obj->importQuestions($excelFilePath,0, 2,20,6,17,'test','test','test','test','test' );
*/

?>
