<?php
/*
 * PHP Excel - Read a simple 2007 XLSX Excel file
 */

/** Set default timezone (will throw a notice otherwise) */
//date_default_timezone_set('America/Los_Angeles');
date_default_timezone_set("Asia/Kolkata");
include 'Classes/PHPExcel/IOFactory.php';
include 'DbConn.php';

class ParseExcelFile{

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
			die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
			. '": ' . $e->getMessage());
		}

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
			//echo $row_value;
			//echo substr($row_value, 0,(strlen($row_value)-1));
			$this->buildInsertSql(substr($row_value, 0,(strlen($row_value)-1)));
			//break;
			//echo "\n";
		}
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
			echo "New records created successfully";
		} else {
			 echo "Error: " . mysqli_error($conn);
			//echo "Error: " .  $this->insertSQL . "<br>" . mysqli_error($conn);
		}
	}


}
$excelFilePath='Final List - PMKVY-2 Job Roles 23-08-16 ver 2.3.xlsx';
$obj = new ParseExcelFile();
$obj->readExcel(5, 10, "fiNAL 212", $excelFilePath);
$obj->insertInToDatabase();

?>
