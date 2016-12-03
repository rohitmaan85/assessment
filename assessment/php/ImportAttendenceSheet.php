<?php
/*
 * PHP Excel - Read a simple 2007 XLSX Excel file
 */

/** Set default timezone (will throw a notice otherwise) */
//date_default_timezone_set('America/Los_Angeles');

include 'Classes/PHPExcel/IOFactory.php';
include 'DbConn.php';
require_once 'logging_api.php';

class ImportAttendenceSheet{

	var $insertSQL = "";
	var $dbConnObj = "";
	var $readActualDataRow = 8;
	var $batch_id="";
	var $headerRowIndex =  array("center_skill_counsil" => 2, "date" => 2,
						 "training_partner_name" => 3, "no_of_students_schedule" => 3,
						 "batch_id" => 4, "center_id_n_location" => 5);

	var $headerColumnIndex =  array("center_skill_counsil" => 3, "date" => 10,
						 "training_partner_name" => 3, "no_of_students_schedule" => 10,
						 "batch_id" => 3, "center_id_n_location" => 3);

	//var $headerAttributes = array("center_skill_counsil" => "","date" => "","training_partner_name" => ""
	//,"no_of_students_schedule" => "","batch_id" => "", "center_id_n_location" => "");
	
	var $headerAttributes = array("batch_id" => "","date" => "","no_of_students_schedule" => ""
		,"center_id_n_location" => "","training_partner_name" => "","center_skill_counsil" => "");

	function readExcel($inputFileName,$sheetNo,$startRow,$endROw,$no_of_columns)
	{
		$row_value = "";
		//Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			log_event( READ_ATTENDENCE, __LINE__.__FILE__."  , ERROR #" .'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
			. '": ' . $e->getMessage() );
			return  false;
		}
		try{
			$commitBatchDetails = false;

			// Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet($sheetNo);
			$rowIndex  = $startRow;
			$row_value ="";
			foreach( $sheet->getRowIterator($startRow, $endRow) as $row ){
				if($rowIndex<$this->readActualDataRow){
					//log_event( READ_ATTENDENCE,__FILE__. __LINE__. "  , Skip this row , belongs to Header" );
					if (array_search($rowIndex,$this->headerRowIndex)){
						//log_event( READ_ATTENDENCE,__FILE__. __LINE__. "  , Read Header value from this row." );
						//$columnKey = array_search($rowIndex,$this->headerRowIndex);
						$columnIndex = 0;
						foreach( $row->getCellIterator() as $cell ){
							$columnIndex++;
							$value = $cell->getCalculatedValue();
							//echo $value;
							foreach ($this->headerRowIndex as $key => $attrValue) {
								if($attrValue==$rowIndex){
									if($this->headerColumnIndex[$key] == $columnIndex){
										//echo $value."		";
										//log_event( READ_ATTENDENCE,__FILE__. __LINE__. " , Read Column value from this Column for arrtibute '".$key."'" );
										$this->headerAttributes[$key]=$value;
									}
								}
							}
							if($columnIndex==$no_of_columns)
							break;
						}
					}
				}else{
					//log_event( READ_ATTENDENCE,__FILE__. __LINE__. "  , Start Reading Actual Data from this row." );
					$columnIndex = 0;
					// Add Header Values
					if(!$commitBatchDetails){
						foreach ($this->headerAttributes as $key => $attrValue) {
							// Insert batch Header details in Database.							
							$row_value .= "'".$attrValue."',";
							if($key=="batch_id"){
								$this->batch_id = $attrValue;
							}
						}
						log_event( READ_ATTENDENCE,__FILE__. __LINE__. "  , Going to commit batch details in Database." );
						$this->buildInsertSqlForBatchDetails(substr($row_value, 0,(strlen($row_value)-1)));
						if($this->insertBatchInDatabase())
							$commitBatchDetails = true;
						else
							throw new Exception('Batch Details not commited in Database.');
					}
					$row_value ="'".$this->batch_id."',";
					foreach( $row->getCellIterator() as $cell ){
						$columnIndex++;
						$value = $cell->getCalculatedValue();
						$row_value .= "'".$value."',";
						if($columnIndex==$no_of_columns)
						break;
					}
					$this->buildInsertSqlForStudents(substr($row_value, 0,(strlen($row_value)-1)));
				}
				$rowIndex++;
			}
		} catch (Exception $e) {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , ERROR #"  . $e );
			return  false;
		}
		return true;
	}

	function buildInsertSqlForBatchDetails($row_value){
		// Add Upload Date in the end.
		$row_value.=",'".date("Y-m-d H:i:s", time())."','active','".date("Y-m-d H:i:s", time())."'";
		$sql = "INSERT INTO `assessment`.`batch_details`(`batch_id`,`exam_date`,`no_of_students_schedule`,`center_id_n_location`,`training_partner_name`,
				`center_skill_counsil`,`upload_date`,`status`,`last_updated_at`) VALUES(".$row_value.");";		
		$this->insertSqlForBatchDetails .=$sql;
	}

	function buildInsertSqlForStudents($row_value){
		// Add Upload Date in the end.
		$row_value.=",'".date("Y-m-d H:i:s", time())."','active','".date("Y-m-d H:i:s", time())."'";
		$sql = "INSERT INTO `assessment`.`batch_students`(`batch_id`,`s.no`,`name`,`job_role`,`enrollment_id`,`father_n_husband_name`,`aadhar_number`,
				`gender`,`mobile_no`,`address`,`email`,`uploadDate`,`status`,`last_modified_on`) VALUES(".$row_value.");";		
		$this->insertSqlForStudents .=$sql;
	}

	function insertBatchInDatabase()
	{
		$conn = DbConn::getDbConn();
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,  SQL to insert Batch Information '".$this->insertSQL."'" );
		if (mysqli_multi_query($conn, $this->insertSqlForBatchDetails)) {
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

			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Batch Information inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		}
		else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR # " .mysqli_error($conn) );
			return false;
		}
	}

	function insertBatchStudentsInDatabase()
	{
		$conn = DbConn::getDbConn();
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,  SQL to insert Student Information '".$this->insertSqlForStudents."'" );
		if (mysqli_multi_query($conn, $this->insertSqlForStudents)) {
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

			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Batch Students Information inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		}
		else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR # " .mysqli_error($conn) );
			return false;
		}
	}

	function importBatchStudents($excelFilePath,$sheetNo,$startRow,$endROw,$no_of_columns)
	{
		if($this->readExcel($excelFilePath,$sheetNo,$startRow,$endROw,$no_of_columns)){
			if($this->insertBatchStudentsInDatabase())
			return true;
			else{
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " ,  ERROR while inserting Batch Information in database." );
				return false;
			}
		}
		else{
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " ,  ERROR while reading excel file." );
			return false;
		}
	}
}


$excelFilePath='Attendance Sheet -  Batch 325068.xls';
$obj = new ImportAttendenceSheet();
$obj->importBatchStudents($excelFilePath,0,2,10,10);


?>
