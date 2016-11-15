<?php

require_once 'DbConn.php';
require_once 'logging_api.php';

class manageQuestions{

	function addQuestionInSubject()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertSQL)) {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Question inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		}
		else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}


	function buildInsertSql($qpCode,$question,$optiona,$optionb,$optionc,$optiond,$correctanswer,$marks){
		
		$row_value="123".",'".$qpCode."','".htmlspecialchars($question,ENT_QUOTES)."','".$optiona."','".$optionb."','".$optionc."','".$optiond."','".
		$correctanswer."','".$marks."'";
		
		$sql = "INSERT INTO `assessment`.`question`
				   (`qnid`,`subId`,`question`,`optiona`,`optionb`,`optionc`,`optiond`,
					`correctanswer`,`marks`)
					 VALUES(".$row_value.");";	
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Question : '".$sql."'" );
		$this->insertSQL .=$sql;
	}

}
$obj = new manageQuestions();

if(isset($_GET['qstn'])){
	log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Get Request to create question." );
	$qpCode = $_GET['qp_code'];
	$question = $_GET['qstn'];
	$optiona = $_GET['optiona'];
	$optionb = $_GET['optionb'];
	$optionc = $_GET['optionc'];
	$optiond = $_GET['optiond'];
	$correctanswer = $_GET['corrans'];
	$marks = $_GET['mark'];
	$obj->buildInsertSql($qpCode, $question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks);
	
	if(!$obj->addQuestionInSubject($qpCode, $question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks))
	{
		$data =  array('error' => 'Error while inserting Question in DB.') ;
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while inserting Question in DB.".json_encode($data) );	
	}
	else
	{
		$data =  array('success' => 'Question Created Successfully.') ;	
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Question created successfully." );
	}
			
	echo json_encode($data);
}
else{
	log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error : ['qstn'] not set" );
	
}

?>