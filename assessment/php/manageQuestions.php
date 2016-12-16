<?php

require_once 'DbConn.php';
require_once 'logging_api.php';
require_once 'manageCategory.php';

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

	function buildInsertSql($ssc,$job_role,$qp_code,$category,$module,$type,$question,$optiona,$optionb,
	$optionc,$optiond,$correctanswer,$marks,$lang,$noOfOption){

		$sNo = "";

		$row_value= "'".htmlspecialchars($sNo,ENT_QUOTES)."','".htmlspecialchars($ssc,ENT_QUOTES).
		"','".htmlspecialchars($job_role,ENT_QUOTES)."','".htmlspecialchars($qp_code,ENT_QUOTES).
		"','".htmlspecialchars($category,ENT_QUOTES)."','".htmlspecialchars($module,ENT_QUOTES).
		"','".htmlspecialchars($type,ENT_QUOTES).
		"','".htmlspecialchars($question,ENT_QUOTES)."','".htmlspecialchars($optiona,ENT_QUOTES).
		"','".htmlspecialchars($optionb,ENT_QUOTES)."','".htmlspecialchars($optionc,ENT_QUOTES).
		"','".htmlspecialchars($optiond,ENT_QUOTES)."','".htmlspecialchars($correctanswer,ENT_QUOTES).
		"','".htmlspecialchars($marks,ENT_QUOTES).
		"','".htmlspecialchars($lang,ENT_QUOTES)."','".htmlspecialchars($noOfOption,ENT_QUOTES).
		"','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time()).
		"','active'";

		$sql = "INSERT INTO `assessment`.`question`
				   (`s.no`,`ssc`,`job_role`,`qp_code`,`category`,`module`,`type`,`question`,`optiona`,`optionb`,
				     `optionc`,`optiond`,`correctanswer`,`marks`,`language`,`no_of_options`,`uploadDate`,`createDate`,
				     `last_modified_on`,`status`)
					 VALUES(".$row_value.");";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Question : '".$sql."'" );
		$this->insertSQL .=$sql;
	}


	function buildUpdateSql($id,$ssc,$job_role,$qp_code,$category,$module,$type,$question,$optiona,$optionb,$optionc,$optiond,$correctanswer,$marks,$lang,$noOfOption){
		$sql = "update `assessment`.`question` set
				`question`='".htmlspecialchars($question,ENT_QUOTES)."',".
				"`optiona`='".htmlspecialchars($optiona,ENT_QUOTES)."',".
				"`optionb`='".htmlspecialchars($optionb,ENT_QUOTES)."',".
				"`optionc`='".htmlspecialchars($optionc,ENT_QUOTES)."',".
				"`optiond`='".htmlspecialchars($optiond,ENT_QUOTES)."',".
				"`correctanswer`='".htmlspecialchars($correctanswer,ENT_QUOTES)."',".
				"`marks`='".htmlspecialchars($marks,ENT_QUOTES)."',".
				"`language`='".htmlspecialchars($lang,ENT_QUOTES)."',".
				"`no_of_options`='".htmlspecialchars($noOfOption,ENT_QUOTES)."',".
				"`last_modified_on`='".date("Y-m-d H:i:s", time())."'";
		$sql.="where id=".$id;

		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to update Question : '".$sql."'" );
		$this->updateSQL .=$sql;
	}


	function getQuestionCount($subjectId,$category_id,$module_id){
		try{
			$conn = DbConn::getDbConn();
			$sql="SELECT * FROM `assessment`.`question` where";

			if($subjectId!=""){
				$sql.= " qp_code='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
			}
			if($category!=""){
				$sql.= " and category='".htmlspecialchars($category_id,ENT_QUOTES)."'";
			}
			if($module!=""){
				$sql.= " and module='".htmlspecialchars($module_id,ENT_QUOTES)."'";
			}
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question Count : '".$sql."'" );
			$result = mysqli_query($conn,$sql);
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			$i=0;
			while ($row)
			{
				$i++;
				$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			}
			mysqli_free_result($result);
			return $i;
		}
		catch(Exception $e) {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Exception while getting Question Count : '".$sql."'" );
			return 0;
		}
	}

	function getQstnList($ssc,$subjectId,$category,$module)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";

		if($ssc!=""){
			$sql.= " where ssc='".htmlspecialchars($ssc,ENT_QUOTES)."'";
		}
		if($subjectId!=""){
			$sql.= " and qp_code='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		}
		if($category!=""){
			$sql.= " and category='".htmlspecialchars($category,ENT_QUOTES)."'";
		}
		if($module!=""){
			$sql.= " and module='".htmlspecialchars($module,ENT_QUOTES)."'";
		}
		$obj = new manageCategory();
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question List: '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[0]= $i;
			$jsonArr[1]=$row['ssc'];
			$jsonArr[2]=$row['job_role'];
			$category_name	= $obj->getCategoryName($row['category']);
			$module_name	= $obj->getModuleName($row['module']);
			$jsonArr[3]=$category_name;
			$jsonArr[4]=$module_name;
			$jsonArr[5]=$row['question'];
			$jsonArr[6]="";
			$jsonArr[7]=$row['optiona'];
			$jsonArr[8]=$row['optionb'];
			$jsonArr[9]=$row['optionc'];
			$jsonArr[10]=$row['optiond'];
			$jsonArr[11]=$row['correctanswer'];
			$jsonArr[12]=$row['type'];
			$jsonArr[13]=$row['id'];
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}


	function getQstnListForImportPage()
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$obj = new manageCategory();
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[0]= $i;
			$jsonArr[1]=$row['ssc'];
			$jsonArr[2]=$row['job_role'];
			$category_name	= $obj->getCategoryName($row['category']);
			$module_name	= $obj->getModuleName($row['module']);
			$jsonArr[3]=$category_name;
			$jsonArr[4]=$module_name;
			$jsonArr[5]=$row['question'];
			$jsonArr[6]=$row['optiona'];
			$jsonArr[7]=$row['optionb'];
			$jsonArr[8]=$row['optionc'];
			$jsonArr[9]=$row['optiond'];
			$jsonArr[10]=$row['correctanswer'];
			$jsonArr[11]=$row['type'];
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}


	function getQuestionToEdit($qstnId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";
		$sql.= " where id='".htmlspecialchars($qstnId,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question for editing : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr["ssc"]=$row['ssc'];
			$jsonArr["job_role"]=$row['job_role'];
			$jsonArr["qp_code"]=$row['qp_code'];
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

	function getQuestionDetails($qstnId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";
		if($qstnId!="")
		$sql.= " where id='".htmlspecialchars($qstnId,ENT_QUOTES)."'";
		log_event(LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question for editing : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		while ($row)
		{
			$jsonArr[0]=$row['subId'];
			$jsonArr[1]=$row['question'];
			$jsonArr[2]=$row['optiona'];
			$jsonArr[3]=$row['optionb'];
			$jsonArr[4]=$row['optionc'];
			$jsonArr[5]=$row['optiond'];
			$jsonArr[6]=$row['correctanswer'];
			$jsonArr[7]=$row['marks'];
			$jsonArr[8]=$row['language'];
			$jsonArr[9]=$row['no_of_options'];
			//$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			break;
		}
		// Free result set
		mysqli_free_result($result);
		//log_event(LOG_DATABASE, __LINE__."  ". __FILE__."  , Array '".print_r($jsonArr)."'" );

		return $jsonArr;
	}

}

// Handle Requests from UI
$obj = new manageQuestions();
if(isset($_GET['get'])){
	$requestParam = $_GET['get'];
	log_event( LOG_DATABASE, "Get Request with parameter :'".$_GET['get']."'" );
	//$obj->getQstnList("");

	if($requestParam == "questions"){
		$ssc="";
		$subjectId="";
		$category="";
		$module="";

		if(isset($_GET['ssc']))
		$ssc = $_GET['ssc'];

		if(isset($_GET['subId']))
		$subjectId = $_GET['subId'];

		if(isset($_GET['category']))
		$category = $_GET['category'];

		if(isset($_GET['module']))
		$module = $_GET['module'];

		log_event( LOG_DATABASE, "Get Questions List for ssc :".$ssc."' and subject  : '".$subjectId."'
		and category :'".$category."' and module : '".$module."'" );
		$obj->getQstnList($ssc,$subjectId,$category,$module);
	}else if($requestParam == "qsntsList"){
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Get Questions List for import Page" );
		$obj->getQstnListForImportPage();

	}else{
		//log_event( LOG_DATABASE, __LINE__."  ". __FILE__."Error : Invalid Request" );
		//$data =  array('error' => "Error :  Invalid Request parameter.") ;
		//echo json_encode($data);
	}
}

if(isset($_GET['action'])){
	log_event( LOG_DATABASE, " Get Request with action parameter." );
	// Get defect details to edit question.
	if($_GET['action']=="create"){
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Get Request to create question.".print_r($_GET) );

		$ssc = $_GET['ssc'];
		$job_role = $_GET['job_role'];
		$qp_code = $_GET['qpcode'];
		$question = $_GET['qstn'];
		$optiona = $_GET['opta'];
		$optionb = $_GET['optb'];
		$optionc = $_GET['optc'];
		$optiond = $_GET['optd'];
		$correctanswer = $_GET['corrans'];
		$marks = $_GET['mark'];
		$lang  = $_GET['language'];
		$noOfOption  = $_GET['noOfOptions'];

		// Implement Later
		$category="";
		$module="";
		$type="normal";


		$obj->buildInsertSql($ssc,$job_role,$qp_code,$category,$module,$type, $question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks,$lang,$noOfOption);

		if(!$obj->addQuestionInSubject())
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
		log_event( LOG_DATABASE, " Request to get question details for editing." );
		if(isset($_GET['qstnId'])){
			$qstnId = $_GET['qstnId'];
			$obj->getQuestionToEdit($qstnId);
		}else{
			log_event( LOG_DATABASE, "'qstnId' are not set to edit Question.");
			$data =  array('error' => "'qstnId' are not set to edit Question.") ;
			echo json_encode($data);
		}
	}
	// Update defect details.
	else if($_GET['action']=="update"){
		log_event( LOG_DATABASE, " Get Request to update question." );
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			$ssc = $_GET['ssc'];
			$job_role = $_GET['job_role'];
			$qp_code = $_GET['qp_code'];
			$question = $_GET['qstn'];
			$optiona = $_GET['opta'];
			$optionb = $_GET['optb'];
			$optionc = $_GET['optc'];
			$optiond = $_GET['optd'];
			$correctanswer = $_GET['corrans'];
			$marks = $_GET['mark'];
			$lang  = $_GET['language'];
			$noOfOption  = $_GET['noOfOptions'];

			// Implement Later in GET Request
			$category="";
			$module="";
			$type="";

			$obj->buildUpdateSql($id,$ssc,$job_role,$qp_code,$category,$module,$type,$question, $optiona, $optionb, $optionc, $optiond, $correctanswer, $marks,$lang,$noOfOption);
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
			log_event( LOG_DATABASE, "Error : 'qstnId' are not set to edit Question.");
			$data =  array('error' => "Error : 'qstnId' are not set to edit Question.") ;
			echo json_encode($data);
		}
	}
}

?>
