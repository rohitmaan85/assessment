<?php
/*
 * PHP Excel - Read a simple 2007 XLSX Excel file
 */

/** Set default timezone (will throw a notice otherwise) */
//date_default_timezone_set('America/Los_Angeles');

include 'Classes/PHPExcel/IOFactory.php';
include 'DbConn.php';
require_once 'logging_api.php';

class ImportQuestions{

	var $insertSQL = "";
	var $dbConnObj = "";

	function readExcel($inputFileName,$sheetNo,$startRow,$endROw,$no_of_columns)
	{
		$row_value = "";
		//Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch (Exception $e) {
			log_event( LOG_DATABASE, __LINE__.__FILE__."  , ERROR #" .'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
			. '": ' . $e->getMessage() );
			return  false;
		}
		try{
			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet($sheetNo);
			foreach( $sheet->getRowIterator($startRow, $endRow) as $row ){
				$columnIndex = 0;
				$row_value ="";
				foreach( $row->getCellIterator() as $cell ){
					$columnIndex++;
					$value = $cell->getCalculatedValue();
					//echo $value."		";
					$row_value .= "'".$value."',";
					if($columnIndex==$no_of_columns)
						break;
				}
				$this->buildInsertSql(substr($row_value, 0,(strlen($row_value)-1)));
			}
		} catch (Exception $e) {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , ERROR #"  . $e );
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
		`optiond`,`correctanswer`,`marks`,`language`,`no_of_options`,`uploadDate`,`createDate`,`last_modified_on`,`status`)
		 VALUES(".$row_value.");";		
		$this->insertSQL .=$sql;
	}

	function insertInToDatabase()
	{
		$conn = DbConn::getDbConn();
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,  SQL to insert Question '".$this->insertSQL."'" );		
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
				
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Questions inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		}
		else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function importQuestions($excelFilePath,$sheetNo,$startRow,$endROw,$no_of_columns)
	{
		if($this->readExcel($excelFilePath,$sheetNo,$startRow,$endROw,$no_of_columns)){
			if($this->insertInToDatabase())
				return true;
			else{
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " ,  ERROR while inserting questions in database." );
				return false;
			}
		}
		else{
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " ,  ERROR while reading excel file." );
			return false;
		}
	}
}

/*
 $excelFilePath='../uploads/Final List - PMKVY-2 Job Roles 23-08-16 ver 2.3.xlsx';
 $obj = new ImportSubjects();
 $obj->importSubject(5, 10, "fiNAL 212", $excelFilePath);
*/

?>
