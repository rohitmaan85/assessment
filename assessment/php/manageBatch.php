<?php

require_once 'DbConn.php';
require_once 'logging_api.php';

class manageBatch{

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

	function updateQuestion()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_query($conn, $this->updateSQL)) {
			if(mysqli_affected_rows($conn)>0){
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Question save successfully in Database : '".mysqli_affected_rows($con)."'" );
				return true;
			}else{
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , ERROR #! No records found to update " );
				return false;
			}
		} else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function buildInsertSql($qpCode,$question,$optiona,$optionb,$optionc,$optiond,$correctanswer,$marks,$lang,$noOfOption){
		$qstnId = mt_rand(1, 50000)."_".$qpCode;
		$row_value= "'".htmlspecialchars($qstnId,ENT_QUOTES)."','".htmlspecialchars($qpCode,ENT_QUOTES).
		"','".htmlspecialchars($question,ENT_QUOTES)."','".htmlspecialchars($optiona,ENT_QUOTES).
		"','".htmlspecialchars($optionb,ENT_QUOTES)."','".htmlspecialchars($optionc,ENT_QUOTES).
		"','".htmlspecialchars($optiond,ENT_QUOTES)."','".htmlspecialchars($correctanswer,ENT_QUOTES)."','".htmlspecialchars($marks,ENT_QUOTES).
		"','".htmlspecialchars($lang,ENT_QUOTES)."','".htmlspecialchars($noOfOption,ENT_QUOTES)."'";

		$sql = "INSERT INTO `assessment`.`question`
				   (`qnid`,`subId`,`question`,`optiona`,`optionb`,`optionc`,`optiond`,
					`correctanswer`,`marks`,`language`,`no_of_options`)
					 VALUES(".$row_value.");";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Question : '".$sql."'" );
		$this->insertSQL .=$sql;
	}


	function buildUpdateSql($id,$qstnId,$subId,$question,$optiona,$optionb,$optionc,$optiond,$correctanswer,$marks,$lang,$noOfOption){
		$sql = "update `assessment`.`question` set
				`question`='".htmlspecialchars($question,ENT_QUOTES)."',".
				"`optiona`='".htmlspecialchars($optiona,ENT_QUOTES)."',".
				"`optionb`='".htmlspecialchars($optionb,ENT_QUOTES)."',".
				"`optionc`='".htmlspecialchars($optionc,ENT_QUOTES)."',".
				"`optiond`='".htmlspecialchars($optiond,ENT_QUOTES)."',".
				"`correctanswer`='".htmlspecialchars($correctanswer,ENT_QUOTES)."',".
				"`marks`='".htmlspecialchars($marks,ENT_QUOTES)."',".
				"`language`='".htmlspecialchars($lang,ENT_QUOTES)."',".
				"`no_of_options`='".htmlspecialchars($noOfOption,ENT_QUOTES)."' ";
		$sql.="where qnid='".$qstnId."' and subId='".$subId."' and id=".$id;

		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to update Question : '".$sql."'" );
		$this->updateSQL .=$sql;
	}


	function getBatches($subjectId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`batch` where job_role='".$subjectId."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Batch List : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$jsonArr[]=$row['batch_id'];
			$jsonArr1[]=$row['batch_name'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		 $final_array = array('batch_id' => array_unique($jsonArr),'batch_name' => array_unique($jsonArr1));
	
		// Free result set
		mysqli_free_result($result);
		 // print json Data.
		echo json_encode($final_array);

	}


	function getBatchListForImportPage()
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`batch`";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Batch List : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[0]= $i;
			$jsonArr[1]=$row['batch_id'];
			$jsonArr[2]=$row['batch_name'];
			$jsonArr[3]=$row['no_of_candidates'];
			$jsonArr[4]=$row['job_role'];
			$jsonArr[5]=$row['assessment_date'];
			$jsonArr[6]=$row['center_add'];
			$jsonArr[7]=$row['uploadDate'];
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}


	function getQuestionToEdit($subjectId,$qstnId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";
		if($subjectId!="")
		$sql.= " where subid='".htmlspecialchars($subjectId,ENT_QUOTES)."' and qnid='".htmlspecialchars($qstnId,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question for editing : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr["subId"]=$row['subId'];
			$jsonArr["question"]=$row['question'];
			$jsonArr["optiona"]=$row['optiona'];
			$jsonArr["optionb"]=$row['optionb'];
			$jsonArr["optionc"]=$row['optionc'];
			$jsonArr["optiond"]=$row['optiond'];
			$jsonArr["correctanswer"]=$row['correctanswer'];
			$jsonArr["marks"]=$row['marks'];
			$jsonArr["language"]=$row['language'];
			$jsonArr["noOfOption"]=$row['no_of_options'];
			//$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array($jsonArr);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($jsonArr);
	}
}

// Handle Requests from UI
$obj = new manageBatch();
if(isset($_GET['get'])){
	$requestParam = $_GET['get'];
	log_event( LOG_DATABASE, "Get Request with parameter :'".$_GET['get']."'" );
	//$obj->getQstnList("");

	if($requestParam == "batches"){
		$subjectId="";
		if(isset($_GET['subId']))
			$subjectId = $_GET['subId'];
		log_event( LOG_DATABASE, "Get Batches for subject  : '".$_GET['subId']."'" );
		$obj->getBatches($subjectId);
	}else if($requestParam == "batchList"){
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."Get Batch List" );
		$obj->getBatchListForImportPage();

	}else{
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."Error : Invalid Request" );
		$data =  array('error' => "Error :  Invalid Request parameter.") ;
		echo json_encode($data);
	}
}

if(isset($_GET['action'])){
	log_event( LOG_DATABASE, " Get Request with action parameter." );
	// Get defect details to edit question.
	if($_GET['action']=="create"){
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
		$noOfOption  = $_GET['noOfOptions'];
		$obj->buildInsertSql($qpCode, $question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks,$lang,$noOfOption);

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


	else if($_GET['action']=="edit"){
		log_event( LOG_DATABASE, " Get Request to get question details." );
		if(isset($_GET['subId']) && isset($_GET['qstnId'])){
			$subId = $_GET['subId'];
			$qstnId = $_GET['qstnId'];
			$obj->getQuestionToEdit($subId, $qstnId);
		}else{
			log_event( LOG_DATABASE, "Error : 'subId' && 'qstnId' are not set to edit Question.");
			$data =  array('error' => "Error : 'subId' && 'qstnId' are not set to edit Question.") ;
			echo json_encode($data);
		}
	}
	// Update defect details.
	else if($_GET['action']=="update"){
		log_event( LOG_DATABASE, " Get Request to update question." );
		if(isset($_GET['subId']) && isset($_GET['qstnId'])){
			$id = $_GET['id'];
			$subId = $_GET['subId'];
			$qstnId = $_GET['qstnId'];
			$question = $_GET['qstn'];
			$optiona = $_GET['opta'];
			$optionb = $_GET['optb'];
			$optionc = $_GET['optc'];
			$optiond = $_GET['optd'];
			$correctanswer = $_GET['corrans'];
			$marks = $_GET['mark'];
			$lang  = $_GET['language'];
			$noOfOption  = $_GET['noOfOptions'];
			$obj->buildUpdateSql($id,$qstnId,$subId,$question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks,$lang,$noOfOption);
			if(!$obj->updateQuestion())
			{
				$data =  array('error' => 'Error while updating Question in DB.') ;
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while inserting Question in DB.".json_encode($data) );
			} else {
				$data =  array('success' => 'Question saved Successfully.') ;
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Question saved successfully." );
			}
			echo json_encode($data);

		}else{
			log_event( LOG_DATABASE, "Error : 'subId' && 'qstnId' are not set to edit Question.");
			$data =  array('error' => "Error : 'subId' && 'qstnId' are not set to edit Question.") ;
			echo json_encode($data);
		}
	}
}

?>
