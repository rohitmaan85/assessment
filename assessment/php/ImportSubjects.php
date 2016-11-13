<?php
/*
 * PHP Excel - Read a simple 2007 XLSX Excel file
 */

/** Set default timezone (will throw a notice otherwise) */
//date_default_timezone_set('America/Los_Angeles');

include 'Classes/PHPExcel/IOFactory.php';
include 'DbConn.php';
require_once 'logging_api.php';

class ImportSubjects{

	var $insertSQL = "";
	var $dbConnObj = "";

	function readExcel($startRow , $endRow , $sheetName , $inputFileName)
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
			$sheet = $objPHPExcel->getSheet(0);
			foreach( $sheet->getRowIterator($startRow, $endRow) as $row ){
				$columnIndex = 0;
				$row_value ="";
				foreach( $row->getCellIterator() as $cell ){
					$columnIndex++;
					$value = $cell->getCalculatedValue();
					//echo $value."		";
					$row_value .= "'".$value."',";
					if($columnIndex==14)
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
		$sql = "INSERT INTO `assessment`.`jobroles_excel_import`
				   (`s.no`,`ssc`,`job_role`,`qp_code`,`nsqf_level`,`theory`,`practical`,`add_dur_entr_n_sftskill`,
					`add_dur_digital_literacy`,`training_duration`,`curriculum_available`,
					`content_available`,`common_norms_category`,`classfication`)
					 VALUES(".$row_value.");";		
		$this->insertSQL .=$sql;
	}

	function insertInToDatabase()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertSQL)) {
			do{
				//echo array_shift($query_type[1]),": ";
				if($result=mysqli_store_result($conn)){  //if has a record set, like SELECT
					echo "Selected rows = ".mysqli_num_rows($result)."<br>";
					mysqli_free_result($result);
				}else{  //if only returning true or false, like INSERT/UPDATE/DELETE/etc.
					$cumulative_rows+=$aff_rows=mysqli_affected_rows($conn);
					//echo "Current Query's Affected Rows = $aff_rows, Cumulative Affected Rows = $cumulative_rows<br>";
				}
			} while(mysqli_more_results($conn) && mysqli_next_result($conn));
				
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Records inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		}
		else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function importSubject($startRow, $endROw , $sheetName , $excelFilePath)
	{
		if($this->readExcel($startRow, $endROw, $sheetName, $excelFilePath)){
			if($this->insertInToDatabase())
			return true;
			else{
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " ,  ERROR while inserting data in database." );
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
