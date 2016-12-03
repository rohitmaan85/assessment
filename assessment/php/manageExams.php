<?php
require_once 'DbConn.php';
require_once 'logging_api.php';

class manageExams{

	var	$selectedCategory = "";
	var	$module = "";
	var $global_qstn_counter = 0;


	function getExamQuestionsInJSONString($examId,$examName){
		$examId="";
		$conn = DbConn::getDbConn();
		if($examName!="" && $examId=="")
			$examId= $this->getExamId($examName);
		$sql="SELECT qstn_id FROM `assessment`.`exam_qstn` where exam_id='".$examId."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get all Questions of Exam with name : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row	= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$this->global_qstn_counter++;
			//$jsonArr[0]= $i;
			$qstnId	 =	$row['qstn_id'];
			$qstnDetails  = $this->getQuestionDetail($qstnId);
			//print_r($qstnDetails);

			$jsonArr[$this->global_qstn_counter]=$qstnDetails;
			// $jsonArr[2]=$row['totalquestions'];

			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		
		// Get Exam Details
		$examDetailsArr = $this->getExamDetails($examId);
		
		$final_array = array('exam_details'=>$examDetailsArr,'data' => $jsonArr);
		// Free result set
		mysqli_free_result($result);
		return $final_array;
		//echo json_encode($final_array);
	}
	
	
function getExamDetails($examId){
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam`";
		if(examId!="")
		$sql.= " where id='".htmlspecialchars($examId,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Exam Details : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$jsonArr["examname"]=$row['testname'];
			$jsonArr["subid"]=$row['subid'];
			$jsonArr["batchid"]=$row['batchid'];
			$jsonArr["totalquestions"]=$row['totalquestions'];
			$jsonArr["duration"]=$row['duration'];
			$jsonArr["testfrom"]=$row['testfrom'];
			$jsonArr["testto"]=$row['testto'];
			$jsonArr["total_marks"]=$row['total_marks'];
	
			//$jsonArr[11]=$jsonArr;
			//$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('exam_details' => $jsonArr);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		//echo json_encode($final_array);
		//return $final_array;
		return $jsonArr;
	}


	function getQuestionDetail($question_id){
		$questionDiv="";
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question` where id='".$question_id."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question Detail : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);

		while ($row)
		{

			$qstn=$row['question'];
			$optiona=$row['optiona'];
			$optionb=$row['optionb'];
			$optionc=$row['optionc'];
			$optiond=$row['optiond'];

			$QstnDetail["question"]=$qstn;
			$QstnDetail["optiona"]=$optiona;
			$QstnDetail["optionb"]=$optionb;
			$QstnDetail["optionc"]=$optionc;
			$QstnDetail["optiond"]=$optiond;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			break;
		}
		mysqli_free_result($result);
		return $QstnDetail;
	}


	function getExamQuestionsDivs($examId,$examName){
		$completeDiv = "";
		$conn = DbConn::getDbConn();
		if($examName!="" && $examId=="")
		$examId= $this->getExamId($examName);
		$sql="SELECT qstn_id FROM `assessment`.`exam_qstn` where exam_id='".$examId."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get all Questions of Exam with name : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$qstnId	 =	$row['qstn_id'];
			$qstnDiv  = $this->getQuestionDetailDiv($qstnId);
			$completeDiv.= $qstnDiv;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		//	$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		//echo json_encode($final_array);
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,Complete Qstn Div = '".$completeDiv."'" );
		return $completeDiv;
	}


	function getQuestionDetailDiv($question_id){
		$questionDiv="";
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question` where id='".$question_id."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question Detail : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$this->global_qstn_counter++;
			$qstn=$row['question'];
			$optiona=$row['optiona'];
			$optionb=$row['optionb'];
			$optionc=$row['optionc'];
			$optiond=$row['optiond'];

			$divBackGround = "";
			if($this->global_qstn_counter % 2 == 0){
				$divBackGround="evenQstn";
			}else{
				$divBackGround="oddQstn";
			}

			$questionDiv = ' <div class="form-group"><div id="'.$divBackGround.'"><div id="'.$question_id.'" class="col-xs-14">
				<p id="qstn_p">'.$this->global_qstn_counter.'. '.$qstn.'</p>
				<div><br></div>
				<div id="optiona"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				'.$optiona.'</p></div>
				<div><br></div>
				<div id="optionb"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				'.$optionb.'</p>
				</div>
				<div><br></div>
				<div id="optionc"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				'.$optionc.'</p>
				</div>
				<div><br></div>
				<div id="optiond"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				'.$optiond.'</p>
				</div>
				<div><br></div>
			 </div></div>
			<div class="col-xs-14"><hr></div></div>';
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			break;
		}
		//	$final_array = array('data' => $jsonArr1);
		// Free result set
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Qstn Div = '".$questionDiv."'" );
		mysqli_free_result($result);
		return $questionDiv;
		// print json Data.
		//echo json_encode($final_array);
	}


	function selectQuestions($subjectId,$category,$module,$noOfQstns,$exam_id){
		$conn = DbConn::getDbConn();
		$sql="SELECT id FROM `assessment`.`question` where";
		if($subjectId!=""){
			$sql.= " qp_code='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		}
		if($category!=""){
			$sql.= " and category='".htmlspecialchars($category,ENT_QUOTES)."'";
		}
		if($module!=""){
			$sql.= " and module='".htmlspecialchars($module,ENT_QUOTES)."'";
		}
		$sql.= " ORDER BY RAND() LIMIT ".$noOfQstns;


		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to select Qstns for Exam : '".$sql."'" );

		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		//$i=0;
		while ($row)
		{
			$row_value ="";
			$row_value .= "'".$exam_id."','".$row['id']."','".$subjectId."','".$category."','".$module."',";
			//$i++;
			//$jsonArr[]=$row['id'];
			$this->buildInsertQstnsSql($row_value);
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
	}

	function buildInsertQstnsSql($row_value){
		// Add Upload Date in the end.
		$row_value.="'active','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."'";
		$sql = "INSERT INTO `assessment`.`exam_qstn`(`exam_id`,`qstn_id`,`subid`,`category`,`module`,`status`,`created_on`,`last_modified_on`) VALUES(".$row_value.");";
		$this->insertQstnsSQL .=$sql;
		//log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Qstns in Exam : '".$this->insertQstnsSQL."'" );

	}

	function insertQstnsInExam()
	{
		$conn = DbConn::getDbConn();
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,  SQL to insert Question for Test'".$this->insertQstnsSQL."'" );
		if (mysqli_multi_query($conn, $this->insertQstnsSQL)) {
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



	function getExamsList($subjectId){
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam`";
		if($subjectId!="")
		$sql.= " where subid='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Exam : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$jsonArr[0]=$row['testname'];
			$jsonArr[1]=$row['subid'];
			$jsonArr[2]=$row['batchid'];
			$jsonArr[3]=$row['totalquestions'];
			$jsonArr[4]=$row['duration'];
			$jsonArr[5]=$row['testfrom'];
			$jsonArr[6]=$row['testto'];
			$jsonArr[7]=$row['total_marks'];
			$jsonArr[8]="";


			//$jsonArr[11]=$jsonArr;
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}

	function getExamId($examName){
		$conn = DbConn::getDbConn();
		$sql="SELECT id FROM `assessment`.`exam`";
		if($examName!="")
		$sql.= " where testname='".htmlspecialchars($examName,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Exam  Id : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$examId = "";
		while ($row)
		{
			$examId = $row['id'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		return $examId;
	}

	function getModuleDetails($categoryId){

		$catDetails = array();
		$conn = DbConn::getDbConn();
		$sql="SELECT category,module FROM `assessment`.`question_category`";
		if($categoryId!="")
		$sql.= " where id='".htmlspecialchars($categoryId,ENT_QUOTES)."'";
		//log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Categories Details  : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$category = "";
		$module = "";
		log_event(LOG_DATABASE, __LINE__."  ". __FILE__."Rohit Maan");
		while ($row)
		{
			//log_event();
			//log_event(LOG_DATABASE, __LINE__."  ". __FILE__.$row['category'].$row['module']);
			$catDetails[0] = $row['category'];
			$catDetails[1] = $row['module'];
			// set global variable;
			//$this->selectedCategory = $category;
			//$this->module = $module;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		return $catDetails;
		//return $examId;
	}


	function buildInsertSql($subId,$exName,$noOfQstns,$examDesc,$examDur,$atmptCount,$startDate,$endDate,$decResult,$batchId,$negMarking,$randomQstn,$totalMarks,$pp,$noOfModuleQstsArr)
	{
		if($atmptCount == "")
		$atmptCount=10;
		// Handle number of questions:
		$moduleIds = "";
		$moduleNoOfQstns = "";
		foreach($noOfModuleQstsArr as $d){
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " ,  Number of Questions in each Module : " . $d['id']. "=>" .$d['noOfQstns']);
			$moduleIds .= $d['id'].",";
			$moduleNoOfQstns .=$d['noOfQstns'].",";
		}

		try{
			$testcode = mt_rand(1, 50000)."_".$exName;
			$testId = $exName."_".date("Y-m-d H:i:s", time());
			$row_value= "'".htmlspecialchars($subId,ENT_QUOTES)."','".htmlspecialchars($testId,ENT_QUOTES)."','".htmlspecialchars($exName,ENT_QUOTES).
			"','".htmlspecialchars($noOfQstns,ENT_QUOTES).
			"','".htmlspecialchars($examDur,ENT_QUOTES)."','".htmlspecialchars($atmptCount,ENT_QUOTES).
			"','".htmlspecialchars($startDate,ENT_QUOTES)."','".htmlspecialchars($endDate,ENT_QUOTES)."','".htmlspecialchars($decResult,ENT_QUOTES).
			"','".htmlspecialchars($batchId,ENT_QUOTES)."','".htmlspecialchars($negMarking,ENT_QUOTES).
			"','".htmlspecialchars($totalMarks,ENT_QUOTES)."','".htmlspecialchars($randomQstn,ENT_QUOTES)."','".htmlspecialchars($pp,ENT_QUOTES)."','".htmlspecialchars($examDesc,ENT_QUOTES).
			"','".$testcode."','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','active','".$moduleIds."','".$moduleNoOfQstns."'";

			$sql = "INSERT INTO `assessment`.`exam`(`subid`,`testid`,`testname`,`totalquestions`,
				  `duration`,`attemptedstudents`,`testfrom`,`testto`,`declareResult`,`batchid`,`negativemarking`,
				   `total_marks`,`randomqstn`,`pp`,`testdesc`,`testcode`,`createDate`,`last_modified_on`,`status`,`moduleIds`,`moduleNoOfQsnts`)
					VALUES(".$row_value.");";

			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Exam : '".$sql."'" );
			$this->insertSQL .=$sql;
		}
		catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	function createExam($examName,$subjectId,$noOfModuleQstsArr)
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertSQL)) {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Exam inserted successfully in Database : '".$cumulative_rows."'" );
			$exam_id = $this->getExamId($examName);
			if($exam_id != ""){
				foreach($noOfModuleQstsArr as $d){
					$moduleId = $d['id'];
					$moduleNoOfQstns =$d['noOfQstns'];
					// Get category details
					$details = $this->getModuleDetails($moduleId);
					if($details[0]!="")
					//$subjectId,$category,$module,$noOfQstns,$exam_id
					$this->selectQuestions($subjectId,$details[0],$details[1],$moduleNoOfQstns,$exam_id);
					else
					log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Error !  'selectedCategory' and 'module' does not exist  in Database '" );

				}
				return $this->insertQstnsInExam();
			}
			else{
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Error !  Exam Id does not exist with name in Database : '".$examName."'" );
				return false;
			}
			return true;
		} else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	/*
	 function createExam()
	 {
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertSQL)) {
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Exam inserted successfully in Database : '".$cumulative_rows."'" );
		return true;
		} else {
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
		return false;
		}
		}

		*/

	function updateExam()
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
$obj = new manageExams();
//$obj->getExamQuestions("","rohit_123");

if(isset($_GET['get'])){
	$requestParam = $_GET['get'];
	log_event( LOG_DATABASE, "Get Request with parameter :'".$_GET['get']."'" );
	if($requestParam == "exams"){
		$subjectId="";
		if(isset($_GET['subid']))
		$subjectId = $_GET['subid'];
		log_event( LOG_DATABASE, "Get Exam List for subject  : '".$_GET['qpCode']."'" );
		$obj->getExamsList($subjectId);
	}else{
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."Error : ['qstn'] not set" );
	}
}

if(isset($_POST['action'])){
	log_event( LOG_DATABASE, " Get Request to Create Exam  with action parameter." );
	// Get defect details to edit question.
	if($_POST['action']=="create"){
	 $subId			= $_POST['subId'];
	 $exName		= $_POST['exName'];
	 $noOfQstns		= $_POST['noOfQstns'];
	 $examDesc		= $_POST['examDesc'];
	 $examDur		= $_POST['examDur'];
	 $atmptCount	= $_POST['atmptCount'];
	 $startDate		= $_POST['startDate'];
	 $endDate		= $_POST['endDate'];
	 $decResult		= $_POST['decResult'];
	 $batchId		= $_POST['batchId'];
	 $negMarking	= $_POST['negMarking'];
	 $randomQstn	= $_POST['randomQstn'];
	 $totalMarks	= $_POST['totalMarks'];
	 $pp			= $_POST['pp'];
	 $noOfModuleQstsArr = json_decode(stripslashes($_POST['noOfModuleQstsArr']));

	 //$data = explode(",", $_POST['noOfModuleQstsArr']);


	 // $noOfModuleQstsArr = $_GET['noOfModuleQstsArr'];

	 // log_event( LOG_DATABASE, __LINE__."  ". __FILE__. " ,  Number of Questions in each Module : " . print_r($noOfModuleQstsArr));
	 log_event( LOG_DATABASE, __LINE__."  ". __FILE__." ,  Get Request to create Exam with Values : '".
	 $subId."','".$exName."','".$noOfQstns."','".$examDesc."','".$examDur."','".$atmptCount.
		"','".$startDate."','"
		.$endDate."','".$decResult."','".$batchId."','".$negMarking."','".$randomQstn."','".$totalMarks."','".$pp."'");

	 $obj->buildInsertSql($subId,$exName,$noOfQstns,$examDesc,$examDur,$atmptCount,$startDate,$endDate,$decResult,$batchId,$negMarking,$randomQstn,$totalMarks,$pp,$_POST['noOfModuleQstsArr']);

	 if(!$obj->createExam($exName,$subId,$_POST['noOfModuleQstsArr']))
		{
			$data =  array('error' => 'Error while creating exam.') ;
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while inserting Question in DB.".json_encode($data) );
		} else {
			$data =  array('success' => 'Exam Created Successfully.') ;
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
