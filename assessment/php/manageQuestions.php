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
		} else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function buildInsertSql($qpCode,$question,$optiona,$optionb,$optionc,$optiond,$correctanswer,$marks,$lang){
		$qstnId = mt_rand(1, 50000)."_".$qpCode;
		$row_value= "'".htmlspecialchars($qstnId,ENT_QUOTES)."','".htmlspecialchars($qpCode,ENT_QUOTES)."','".htmlspecialchars($question,ENT_QUOTES)."','".htmlspecialchars($optiona,ENT_QUOTES)."','".htmlspecialchars($optionb,ENT_QUOTES)."','".htmlspecialchars($optionc,ENT_QUOTES)."','".htmlspecialchars($optiond,ENT_QUOTES)."','".
		htmlspecialchars($correctanswer,ENT_QUOTES)."','".htmlspecialchars($marks,ENT_QUOTES)."','".htmlspecialchars($lang,ENT_QUOTES)."'";

		$sql = "INSERT INTO `assessment`.`question`
				   (`qnid`,`subId`,`question`,`optiona`,`optionb`,`optionc`,`optiond`,
					`correctanswer`,`marks`,`language`)
					 VALUES(".$row_value.");";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Question : '".$sql."'" );
		$this->insertSQL .=$sql;
	}


	function getQstnList($subjectId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";
		if($subjectId!="")
			$sql.= " where subid='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[0]= $i;
			$jsonArr[1]=$row['subId'];
			$jsonArr[2]=$row['question'];
			$jsonArr[3]=$row['optiona'];
			$jsonArr[4]=$row['optionb'];
			$jsonArr[5]=$row['optionc'];
			$jsonArr[6]=$row['optiond'];
			$jsonArr[7]="";
			$jsonArr[8]=$row['correctanswer'];
			$jsonArr[9]=$row['marks'];
			$jsonArr[10]=$row['language'];
			$jsonArr[11]=$jsonArr;
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}




}
$obj = new manageQuestions();

if(isset($_GET['qstn'])){
	log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Get Request to create question." );
	$qpCode = $_GET['qpcode'];
	$question = $_GET['qstn'];
	$optiona = $_GET['opta'];
	$optionb = $_GET['optb'];
	$optionc = $_GET['optc'];
	$optiond = $_GET['optd'];
	$correctanswer = $_GET['corrans'];
	$marks = $_GET['mark'];
	$lang  = $_GET['language'];
	$obj->buildInsertSql($qpCode, $question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks,$lang);

	if(!$obj->addQuestionInSubject($qpCode, $question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks))
	{
		$data =  array('error' => 'Error while inserting Question in DB.') ;
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while inserting Question in DB.".json_encode($data) );
	} else {
		$data =  array('success' => 'Question Created Successfully.') ;
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Question created successfully." );
	}
	echo json_encode($data);
}
else if(isset($_GET['get'])){
	$requestParam = $_GET['get'];
	log_event( LOG_DATABASE, "Get Request with parameter :'".$_GET['get']."'" );
	//$obj->getQstnList("");

	if($requestParam == "questions"){
		$subjectId="";
		if(isset($_GET['subId']))
		 	 $subjectId = $_GET['subId'];

		log_event( LOG_DATABASE, "Get Questions List for subject  : '".$_GET['get']."'" );
		$obj->getQstnList($subjectId);
	}else{
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."Error : ['qstn'] not set" );

	}


}

?>
