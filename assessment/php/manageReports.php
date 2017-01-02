<?php


require_once 'DbConn.php';
require_once 'logging_api.php';
require_once 'getSubjectDetails.php';
require_once 'manageQuestions.php';
require_once 'manageCategory.php';
require_once 'manageAttendence.php';
require_once 'manageHistory.php';
date_default_timezone_set("Asia/Kolkata");

class manageExams{

	var	$selectedCategory = "";
	var	$module = "";
	var $global_qstn_counter = 0;
	var $Category_no_of_qsnts = array();
	var $exam_date = "";
	var $batch_id = "";
	
	var $insertDownloadHistorySQL="";
	var $marks_each_question="";

	function getExamQuestionsInJSONString($examId,$examName){
		$conn = DbConn::getDbConn();
		if($examName!="" && $examId=="")
		$examId= $this->getExamId($examName);
		$sql="SELECT qstn_id,category,module,mark FROM `assessment`.`exam_qstn` where exam_id='".$examId."' order by category,module,qstn_id";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get JSON String of Exam with name : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row	= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$prev_cat = "";
		$prev_module = "";

		$obj = new manageCategory();
		$atleast2Div = false;
		$catgory_detail_array = array();
		$this->getQuestionCountForEachCategory($examId);

		while ($row)
		{
			$this->global_qstn_counter++;
			$qstnId	 =	$row['qstn_id'];
			$current_category = $row['category'];
			$current_module	  = $row['module'];
			$marks =  $row['mark'];

			if($i!=0){
				if($current_category!=$prev_cat){ // Create New Panel
					// end previous panel
					$moduleName= "";
					$categoryName="";
					$categoryName="";

					// Add question in previous category details.
					$catgory_detail["Questions"] = $qstnDetailsArr;
					$catgory_detail_array[] = $catgory_detail;


					$categoryName = $obj->getCategoryName($current_category);
					if($current_module!="")
					{
						$moduleName= $obj->getModuleName($current_module);
					}
					$no_of_qstns = $this->getNoOfQstns($current_category);


					$catgory_detail["category_name"] = $categoryName;
					$catgory_detail["module_name"]	 = $moduleName ;
					$catgory_detail["no_of_questions"] = $no_of_qstns;

					// Flush Question Array to get details for new categories.
					$qstnDetailsArr = array();
					$qstnDetails  = $this->getQuestionDetail($qstnId,$marks);
					$qstnDetailsArr[$this->global_qstn_counter]=$qstnDetails;

				}else{
					// Get Question Details
					$qstnDetails  = $this->getQuestionDetail($qstnId,$marks);
					$qstnDetailsArr[$this->global_qstn_counter]=$qstnDetails;
				}
			}else{// Create first panel
				$moduleName= "";
				$categoryName="";
				$categoryName="";

				$categoryName= $obj->getCategoryName($current_category);
				if($current_module!="")
				{
					$moduleName= $obj->getModuleName($current_module);

				}
				$no_of_qstns = $this->getNoOfQstns($current_category);
					
				$catgory_detail["category_name"] = $categoryName;
				$catgory_detail["module_name"]	 = $moduleName ;
				$catgory_detail["no_of_questions"] = $no_of_qstns;

				// Get Question Details
				$qstnDetails  = $this->getQuestionDetail($qstnId,$marks);
				$qstnDetailsArr[$this->global_qstn_counter]=$qstnDetails;
			}

			// if end comes then add category details

			$prev_cat=$current_category;
			$prev_module=$crrent_module;
			$i++;

			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}


		$catgory_detail["Questions"] = $qstnDetailsArr;
		$catgory_detail_array[] = $catgory_detail;


		$exam_info = $this->getExamDetailsArray($examId);	
		
		// Get Students details
		$attendence_object = new manageAttendence();		
		$student_details_array = $attendence_object->getStudentListInJSONString($exam_info['batchid']);
		
		// Get Batch Details
		$batch_detail_array = $attendence_object->getBatchDetailsArray($exam_info['batchid']);
		$this->exam_date  = $batch_detail_array['EXAM_DATE'];

		
		// Get Exam Details
		$examDetailsArr = $this->getExamDetails($examId);
		
		$final_array = array('Exam_Details'=>$examDetailsArr,'Batch_Details' => $batch_detail_array,'Student_Details' => $student_details_array,'Exam_Questions' => $catgory_detail_array);
		// Free result set
		mysqli_free_result($result);
		return $final_array;
		//echo json_encode($final_array);
	}

	/*
	 function getExamQuestionsDivs($examId,$examName){
		$completeDiv = "";
		$conn = DbConn::getDbConn();
		if($examName!="" && $examId=="")
		$examId= $this->getExamId($examName);
		$this->getQuestionCountForEachCategory($examId);
		$sql="SELECT qstn_id,category,module,mark FROM `assessment`.`exam_qstn` where exam_id='".$examId."' order by category,module,qstn_id";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get all Questions of Exam with name : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$prev_cat = "";
		$prev_module = "";


		// Print Exam Details :
		$completeDiv = $this->getExamDetailsDiv($examId);
		//$completeDiv .= $this->getPanelDiv("Exam Name : ".$examName);
		$obj = new manageCategory();
		$atleast2Div = false;
		while ($row)
		{
		//$jsonArr[0]= $i;
		$qstnId	 =	$row['qstn_id'];
		$current_category = $row['category'];
		$current_module	  = $row['module'];
		$marks =  $row['mark'];

		if($i!=0){
		if($current_category!=$prev_cat){ // Create New Panel
		// end previosu panel
		$completeDiv.= "</div></div>";
		$categoryName = $obj->getCategoryName($current_category);
		if($current_module!="")
		{
		$moduleName= $obj->getModuleName($current_module);
		$categoryName .= " : ".$moduleName;
		}
		$no_of_qstns = $this->getNoOfQstns($current_category);
		$completeDiv.=$this->getPanelDiv("Category Name : ".$categoryName." , Number of Questions = ".$no_of_qstns);
		$qstnDiv  = $this->getQuestionDetailDiv($qstnId,$marks);
		$completeDiv.= $qstnDiv;

		}else{
		$qstnDiv  = $this->getQuestionDetailDiv($qstnId,$marks);
		$completeDiv.= $qstnDiv;
		}
		}else{// Add Question in current Panel
		$categoryName= $obj->getCategoryName($current_category);
		if($current_module!="")
		{
		$moduleName= $obj->getModuleName($current_module);
		$categoryName .= " : ".$moduleName;
		}
		$no_of_qstns = $this->getNoOfQstns($current_category);
		$completeDiv.=$this->getPanelDiv("Category Name : ".$categoryName." , Number of Questions = ".$no_of_qstns);
		$qstnDiv  = $this->getQuestionDetailDiv($qstnId,$marks);
		$completeDiv.= $qstnDiv;
		}

		$prev_cat=$current_category;
		$prev_module=$crrent_module;
		$i++;
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);

		}
		//$completeDiv.= "</div></div></div></div>";
		$completeDiv.= "</div></div>";
		// Free result set
		mysqli_free_result($result);
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,Complete Qstn Div = '".$completeDiv."'" );
		return $completeDiv;
		}


		function getExamQuestionsInJSONString($examId,$examName){
		$conn = DbConn::getDbConn();
		if($examName!="" && $examId=="")
		$examId= $this->getExamId($examName);
		$sql="SELECT qstn_id,mark FROM `assessment`.`exam_qstn` where exam_id='".$examId."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get all Questions of Exam with name : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row	= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
		$this->global_qstn_counter++;
		//$jsonArr[0]= $i;
		$qstnId	 =	$row['qstn_id'];
		$marks= $row['mark'];
		$qstnDetails  = $this->getQuestionDetail($qstnId,$marks);
		//print_r($qstnDetails);

		$jsonArr[$this->global_qstn_counter]=$qstnDetails;
		// $jsonArr[2]=$row['totalquestions'];

		$jsonArr1[] =$jsonArr;
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}

		// Get Exam Details
		$examDetailsArr = $this->getExamDetails($examId);

		$final_array = array('exam_details'=>$examDetailsArr,'Questions' => $jsonArr);
		// Free result set
		mysqli_free_result($result);
		return $final_array;
		//echo json_encode($final_array);
		}
		*/

	function getExamDetails($examId){
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam`";
		if(examId!="")
		$sql.= " where id='".htmlspecialchars($examId,ENT_QUOTES)."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Exam Details : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$qstnObj = new getSubjectDetails();		
		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$qp_code=$row['subid'];
			$jsonArr["EXAM_NAME"]=$row['testname'];
			$jsonArr["EXAM_DATE"]=$this->exam_date;
			$jsonArr["SSC"]=$qstnObj->getSSC($qp_code);
			$jsonArr["JOB_ROLE"]= $qstnObj->getJobRole($qp_code)." (".$qp_code.")";
			$jsonArr["QP_CODE"]=$row['subid'];
			$jsonArr["BATCH_ID"]=$row['batchid'];
			$jsonArr["NUMBER_OF_QUESTIONS"]=$row['totalquestions'];
			$jsonArr["DURATION_IN_MINUTES"]=$row['duration'];
			$jsonArr["VALID_FROM"]=$row['testfrom'];
			$jsonArr["VALID_TO"]=$row['testto'];
			$jsonArr["TOTAL_MARKS"]=$row['total_marks'];
		
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


	function getExamDetailsArray($examId){
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam`";
		if(examId!="")
		$sql.= " where id='".htmlspecialchars($examId,ENT_QUOTES)."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Exam Details Array : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$qstnObj = new getSubjectDetails();

		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$qp_code=$row['subid'];
			$jsonArr["examname"]=$row['testname'];
			$jsonArr["subid"]=$row['subid'];
			$jsonArr["batchid"]=$row['batchid'];
			$jsonArr["totalquestions"]=$row['totalquestions'];
			$jsonArr["duration"]=$row['duration'];
			$jsonArr["testfrom"]=$row['testfrom'];
			$jsonArr["testto"]=$row['testto'];
			$jsonArr["total_marks"]=$row['total_marks'];
			$jsonArr["test_date"]=$row['testfrom'];
			$jsonArr["job_role"]= $qstnObj->getJobRole($qp_code)." (".$qp_code.")";
			$jsonArr["ssc"]=$qstnObj->getSSC($qp_code);

			//$jsonArr[11]=$jsonArr;
			//$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);

		return $jsonArr;
	}


	function getExamDetailsForDialogueBox($examName){
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam`";
		if(examId!="")
		$sql.= " where testname='".htmlspecialchars($examName,ENT_QUOTES)."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Exam Details : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$jsonArr[0]=$row['testname'];
			$jsonArr[1]=$row['batchid'];
			$jsonArr[2]=$row['totalquestions'];
			$jsonArr[3]=$row['duration'];
			$jsonArr[4]=$row['testfrom'];
			$jsonArr[5]=$row['testto'];
			$jsonArr[6]=$row['total_marks'];
			$jsonArr[7]=$row['pp'];
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			break;
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}



	function getQuestionDetail($question_id,$marks){
		$questionDiv="";
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question` where id='".$question_id."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Question Detail : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);

		while ($row)
		{

			$qstn=$row['question'];
			$optiona=$row['optiona'];
			$optionb=$row['optionb'];
			$optionc=$row['optionc'];
			$optiond=$row['optiond'];
			$type=$row['type'];
			$image_path=$row['image_path'];


			$QstnDetail["question"]=$qstn;
			$QstnDetail["optiona"]=$optiona;
			$QstnDetail["optionb"]=$optionb;
			$QstnDetail["optionc"]=$optionc;
			$QstnDetail["optiond"]=$optiond;

			$QstnDetail["type"]=$type;
			$QstnDetail["image_path"]=$image_path;
			$QstnDetail["marks"]=$marks;

			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			break;
		}
		mysqli_free_result($result);
		return $QstnDetail;
	}



	function getQuestionCountForEachCategory($exam_id){
		$questionDiv="";
		$conn = DbConn::getDbConn();
		$sql="SELECT category , count(category) as no_of_qstns FROM `assessment`.`exam_qstn` where exam_id='".$exam_id."' group by category";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Question Count for each category : '".$sql."'" );
		$result 	= 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$catgory_qstns_arr = "";
		while ($row)
		{
			$catgory_qstns["cat_id"] = $row['category'];
			$catgory_qstns["no_of_qstns"] = $row['no_of_qstns'];
			$this->Category_no_of_qsnts[] = $catgory_qstns;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		mysqli_free_result($result);
		//return $catgory_qstns_arr;
	}


	function getNoOfQstns($category_id){
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__." , Get No of Qsnts for category: '".$category_id);
		foreach($this->Category_no_of_qsnts as $d){
			if($d["cat_id"]==$category_id)
			return  $d["no_of_qstns"];
		}
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__." , Category: '".$category_id."' Questions Not Found." );
			
	}


	function getExamQuestionsDivs($examId,$examName){
		$completeDiv = "";
		$conn = DbConn::getDbConn();
		if($examName!="" && $examId=="")
		$examId= $this->getExamId($examName);
		$this->getQuestionCountForEachCategory($examId);
		$sql="SELECT qstn_id,category,module,mark FROM `assessment`.`exam_qstn` where exam_id='".$examId."' order by category,module,qstn_id";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get all Questions of Exam with name : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$prev_cat = "";
		$prev_module = "";


		// Print Exam Details :
		$completeDiv = $this->getExamDetailsDiv($examId);
		//$completeDiv .= $this->getPanelDiv("Exam Name : ".$examName);
		$obj = new manageCategory();
		$atleast2Div = false;
		while ($row)
		{
			//$jsonArr[0]= $i;
			$qstnId	 =	$row['qstn_id'];
			$current_category = $row['category'];
			$current_module	  = $row['module'];
			$marks =  $row['mark'];

			if($i!=0){
				if($current_category!=$prev_cat){ // Create New Panel
					// end previosu panel
					$completeDiv.= "</div></div>";
					$categoryName = $obj->getCategoryName($current_category);
					if($current_module!="")
					{
						$moduleName= $obj->getModuleName($current_module);
						$categoryName .= " : ".$moduleName;
					}
					$no_of_qstns = $this->getNoOfQstns($current_category);
					$completeDiv.=$this->getPanelDiv("Category Name : ".$categoryName." , Number of Questions = ".$no_of_qstns);
					$qstnDiv  = $this->getQuestionDetailDiv($qstnId,$marks);
					$completeDiv.= $qstnDiv;

				}else{
					$qstnDiv  = $this->getQuestionDetailDiv($qstnId,$marks);
					$completeDiv.= $qstnDiv;
				}
			}else{// Add Question in current Panel
				$categoryName= $obj->getCategoryName($current_category);
				if($current_module!="")
				{
					$moduleName= $obj->getModuleName($current_module);
					$categoryName .= " : ".$moduleName;
				}
				$no_of_qstns = $this->getNoOfQstns($current_category);
				$completeDiv.=$this->getPanelDiv("Category Name : ".$categoryName." , Number of Questions = ".$no_of_qstns);
				$qstnDiv  = $this->getQuestionDetailDiv($qstnId,$marks);
				$completeDiv.= $qstnDiv;
			}

			$prev_cat=$current_category;
			$prev_module=$crrent_module;
			$i++;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);

		}
		//$completeDiv.= "</div></div></div></div>";
		$completeDiv.= "</div></div>";
		// Free result set
		mysqli_free_result($result);
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,Complete Qstn Div = '".$completeDiv."'" );
		return $completeDiv;
	}

	
	
function getExamQuestionsDivsForPDF($examId,$examName){
		$completeDiv = "";
		$conn = DbConn::getDbConn();
		if($examName!="" && $examId=="")
		$examId= $this->getExamId($examName);
		$this->getQuestionCountForEachCategory($examId);
		$sql="SELECT qstn_id,category,module,mark FROM `assessment`.`exam_qstn` where exam_id='".$examId."' order by category,module,qstn_id";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get all Questions of Exam with name : '".$sql."'" );
		$result = 	mysqli_query($conn,$sql);
		$row		= 	mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$prev_cat = "";
		$prev_module = "";


		// Print Exam Details :
		$completeDiv = $this->getExamDetailsDiv($examId);
		//$completeDiv .= $this->getPanelDiv("Exam Name : ".$examName);
		$obj = new manageCategory();
		$atleast2Div = false;
		while ($row)
		{
			//$jsonArr[0]= $i;
			$qstnId	 =	$row['qstn_id'];
			$current_category = $row['category'];
			$current_module	  = $row['module'];
			$marks =  $row['mark'];

			if($i!=0){
				if($current_category!=$prev_cat){ // Create New Panel
					// end previosu panel
					$completeDiv.= "</div></div>";
					$categoryName = $obj->getCategoryName($current_category);
					if($current_module!="")
					{
						$moduleName= $obj->getModuleName($current_module);
						$categoryName .= " : ".$moduleName;
					}
					$no_of_qstns = $this->getNoOfQstns($current_category);
					$completeDiv.=$this->getPanelDiv("Category Name : ".$categoryName." , Number of Questions = ".$no_of_qstns);
					$qstnDiv  = $this->getQuestionDetailDivForPDF($qstnId,$marks);
					$completeDiv.= $qstnDiv;

				}else{
					$qstnDiv  = $this->getQuestionDetailDivForPDF($qstnId,$marks);
					$completeDiv.= $qstnDiv;
				}
			}else{// Add Question in current Panel
				$categoryName= $obj->getCategoryName($current_category);
				if($current_module!="")
				{
					$moduleName= $obj->getModuleName($current_module);
					$categoryName .= " : ".$moduleName;
				}
				$no_of_qstns = $this->getNoOfQstns($current_category);
				$completeDiv.=$this->getPanelDiv("Category Name : ".$categoryName." , Number of Questions = ".$no_of_qstns);
				$qstnDiv  = $this->getQuestionDetailDivForPDF($qstnId,$marks);
				$completeDiv.= $qstnDiv;
			}

			$prev_cat=$current_category;
			$prev_module=$crrent_module;
			$i++;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);

		}
		//$completeDiv.= "</div></div></div></div>";
		$completeDiv.= "</div></div>";
		// Free result set
		mysqli_free_result($result);
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,Complete Qstn Div = '".$completeDiv."'" );
		return $completeDiv;
	}
	
	
	

	function getPanelDiv($categoryName){

		return '<div class="panel panel-default">
     				<div class="panel-heading">
              			<h2 class="panel-title pull-left"><strong>'.$categoryName.' </strong></h2>
    			 	</div>
    			<div class="panel-body">';
	}

	function getExamDetailsDiv($exam_id){		
					
		$examDetailsArray = $this->getExamDetailsToDisplayOnExamQuestionsPage($exam_id);
		
		// log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , exam Details Array ".print_r($examDetailsArray)."'" );
		$examDetailsDiv = '<form id="manageExamQuestionsForm" class="form-horizontal" >
              <div id="manageExamQuestionDiv" class="col-xs-18">

              <div class="form-group">
                       <div class="form-group-inline required">
                         <label for="examName"  class="col-xs-2 control-label">Exam Name </label>
                       </div>
                         <div class="col-xs-3">
                           <input type="text" id="examNameText" class="form-control" value="'.$examDetailsArray[0].'" disabled="true" >
                         </div>
                         <div class="col-xs-2"></div>
                         <div class="form-group-inline required">
                           <label for="noOfQstns"  class="col-xs-2 control-label">No. of Questions </label>
                         </div>
                         <div class="col-xs-3">
                           <input type="text" id="noOfQstnsText" class="form-control" value="'.$examDetailsArray[1].'" disabled="true">
                        </div>
                   </div>

                   <div class="form-group">
                      <div class="form-group-inline required">
                        <label for="batch"  class="col-xs-2 control-label">Batch Name </label>
                      </div>
                        <div class="col-xs-3">
                          <input type="text" id="batchText" class="form-control" value="'.$examDetailsArray[2].'" disabled="true" >
                        </div>
                        <div class="col-xs-2"></div>
                        <div class="form-group-inline required">
                          <label for="examDate"  class="col-xs-2 control-label">Exam Date</label>
                        </div>
                        <div class="col-xs-3">
                          <input type="text" id="examDateText" class="form-control" value="'.$examDetailsArray[3].'" disabled="true">
                       </div>
                  </div>

                  <div class="form-group">
                     <div class="form-group-inline required">
                       <label for="duration"  class="col-xs-2 control-label">Duration </label>
                     </div>
                       <div class="col-xs-3">
                         <input type="text" id="durationText" class="form-control"  value="'.$examDetailsArray[4].' Minutes" disabled="true">
                       </div>
                       <div class="col-xs-2"></div>
                       <div class="form-group-inline required">
                         <label for="tm"  class="col-xs-2 control-label">Total Marks</label>
                       </div>
                       <div class="col-xs-3">
                         <input type="text" id="tmText" class="form-control" value="'.$examDetailsArray[5].'" disabled="true">
                      </div>
                </div>

                <div class="form-group">
                   <div class="form-group-inline required">
                     <label for="validFrom"  class="col-xs-2 control-label">Valid From Date</label>
                   </div>
                     <div class="col-xs-3">
                       <input type="text" id="validFromText" class="form-control"  value="'.$examDetailsArray[6].'" disabled="true">
                     </div>
                     <div class="col-xs-2"></div>
                     <div class="form-group-inline required">
                       <label for="validTo"  class="col-xs-2 control-label">Valid To Date </label>
                     </div>
                     <div class="col-xs-3">
                       <input type="text" id="validToText" class="form-control" value="'.$examDetailsArray[7].'" disabled="true">
                    </div>
               </div>

               <div class="form-group">
                  <div class="form-group-inline required">
                    <label for="ssc"  class="col-xs-2 control-label">SSC</label>
                  </div>
                    <div class="col-xs-3">
                      <input type="text" id="sscText" class="form-control"  value="'.$examDetailsArray[9].'" disabled="true" >
                    </div>
                    <div class="col-xs-2"></div>
                    <div class="form-group-inline required">
                      <label for="Jobrole"  class="col-xs-2 control-label">Job Role</label>
                    </div>
                    <div class="col-xs-3">
                      <input type="text" id="JobroleText" class="form-control"  value="'.$examDetailsArray[8].'" disabled="true">
                   </div>
              </div>
             </div>
        </form>';		    
		return $examDetailsDiv;
	}

	function getQuestionDetailDiv($question_id,$marks){
		if($marks == null || $marks ==""){
			$marks = 4;
		}
		$questionDiv="";
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question` where id='".$question_id."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Question Detail : '".$sql."'" );
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
			// 27-12-2016
			$type=$row['type'];
			$image_path = $row['image_path'];

			$divBackGround = "";
			if($this->global_qstn_counter % 2 == 0){
				$divBackGround="evenQstn";
			}else{
				$divBackGround="oddQstn";
			}



			$imageDiv="";
			if($type=="image"){
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , IMAGE TYPE QUESTION : '".$image_path."'" );
				$imageDiv='
                        <div id="qstnImageDiv" class="form-group col-xs-12">
                          <hr>
				          <img id="qstn_img" width="504" height="236" src="'.$image_path.'" alt="question Image" class="img-responsive center-block" />            
                          <hr>
                        </div>';
			}

			$questionDiv = ' <div class="form-group">
				<div id="'.$divBackGround.'">
				<div id="'.$question_id.'" class="col-xs-14">
				<div id="marks" class="pull-right"><p id="qstn_marks">Marks :	'.$marks.'</p></div>
				<p id="qstn_p">'.$this->global_qstn_counter.'. '.$qstn.'</p>
				<div><br></div>'.$imageDiv.'
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
		//	log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Qstn Div = '".$questionDiv."'" );
		mysqli_free_result($result);
		return $questionDiv;
		// print json Data.
		//echo json_encode($final_array);
	}

	
	
function getQuestionDetailDivForPDF($question_id,$marks){
		if($marks == null || $marks ==""){
			$marks = 4;
		}
		$questionDiv="";
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question` where id='".$question_id."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Question Detail : '".$sql."'" );
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
			// 27-12-2016
			$type=$row['type'];
			$image_path = ".".$row['image_path'];

			$divBackGround = "";
			if($this->global_qstn_counter % 2 == 0){
				$divBackGround="evenQstn";
			}else{
				$divBackGround="oddQstn";
			}



			$imageDiv="";
			if($type=="image"){
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , IMAGE TYPE QUESTION : '".$image_path."'" );
				$imageDiv='
                        <div id="qstnImageDiv" class="form-group col-xs-12">
                          <hr>
				          <img id="qstn_img" width="504" height="236" src="'.$image_path.'" alt="question Image" class="img-responsive center-block" />            
                          <hr>
                        </div>';
			}

			$questionDiv = ' <div class="form-group">
				<div id="'.$divBackGround.'">
				<div id="'.$question_id.'" class="col-xs-14">
				<div id="marks" class="pull-right"><p id="qstn_marks">Marks :	'.$marks.'</p></div>
				<p id="qstn_p">'.$this->global_qstn_counter.'. '.$qstn.'</p>
				<div><br></div>'.$imageDiv.'
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
		//	log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Qstn Div = '".$questionDiv."'" );
		mysqli_free_result($result);
		return $questionDiv;
		// print json Data.
		//echo json_encode($final_array);
	}
	

	function selectQuestions($subjectId,$category,$module,$noOfQstns,$exam_id){
		$conn = DbConn::getDbConn();
		$sql="SELECT id FROM `assessment`.`question` where status ='active' and ";
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


		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to select Qstns for Exam : '".$sql."'" );

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
		$row_value.="'active','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','".$this->marks_each_question ."'";
		$sql = "INSERT INTO `assessment`.`exam_qstn`(`exam_id`,`qstn_id`,`subid`,`category`,`module`,`status`,`created_on`,`last_modified_on`,`mark`) VALUES(".$row_value.");";
		$this->insertQstnsSQL .=$sql;
		//log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to insert Qstns in Exam : '".$this->insertQstnsSQL."'" );

	}
	
	function buildInsertDownloadEncryptExamSql($file_name,$file_md5,$remote_ip,$downloaded_at,$exam_name,$json_format){
		// Add Upload Date in the end.
		
		$row_value= "'".htmlspecialchars($file_name,ENT_QUOTES)."','".htmlspecialchars($file_md5,ENT_QUOTES)."','".htmlspecialchars($remote_ip,ENT_QUOTES).
		"','".htmlspecialchars($downloaded_at,ENT_QUOTES)."','".htmlspecialchars($exam_name,ENT_QUOTES)."','".htmlspecialchars($json_format,ENT_QUOTES);
		
		$row_value.="','".date("Y-m-d H:i:s", time())."'";
		$sql = "INSERT INTO `assessment`.`download_encrypt_history`(`file_name`,`file_md5`,`remote_ip`,`downloaded_at`,`exam_name`,`json_format`,`creation_date`) VALUES(".$row_value.");";
		$this->insertDownloadHistorySQL .=$sql;
		log_event(DOWNLOAD_ENCRYPT_EXAM, __LINE__."  ". __FILE__."  , SQL to insert History for encrytion Exam : '".$this->insertDownloadHistorySQL."'" );

		return $this->insertDownloadEncryptExamSql();
	}
		
		
	function insertDownloadEncryptExamSql()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertDownloadHistorySQL)) {
			log_event( DOWNLOAD_ENCRYPT_EXAM, __LINE__."  ". __FILE__."  , Success !  History inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event( DOWNLOAD_ENCRYPT_EXAM, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}	
		
	}

	function insertQstnsInExam()
	{
		$conn = DbConn::getDbConn();
		log_event( DOWNLOAD_ENCRYPT_EXAM, __LINE__."  ". __FILE__."  ,  SQL to insert Question for Test'".$this->insertQstnsSQL."'" );
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

			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Success !  Questions inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		}
		else {
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}



	function getExamsList($subjectId){
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam` where status='active'";
		if($subjectId!="")
		$sql.= " and subid='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Exam : '".$sql."'" );
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
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Exam  Id : '".$sql."'" );
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
		//log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Categories Details  : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$category = "";
		$module = "";
		while ($row)
		{
			//log_event();
			//log_event(MANAGE_TEST, __LINE__."  ". __FILE__.$row['category'].$row['module']);
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
		$this->marks_each_question = $totalMarks/$noOfQstns;
		if($atmptCount == "")
		$atmptCount=10;
		// Handle number of questions:
		$moduleIds = "";
		$moduleNoOfQstns = "";
		$moduleNoOfQstns = "";
		foreach($noOfModuleQstsArr as $d){
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__. " ,  Number of Questions in each Module : " . $d['id']. "=>" .$d['noOfQstns']. " , isCategory =". $isCategory);
			$moduleIds .= $d['id'].",";
			$moduleNoOfQstns .=$d['noOfQstns'].",";
			$isCategory.=$d['isCategory'].",";
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
			"','".$testcode."','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','active','".$moduleIds."','".$moduleNoOfQstns."','".$isCategory."'";

			$sql = "INSERT INTO `assessment`.`exam`(`subid`,`testid`,`testname`,`totalquestions`,
				  `duration`,`attemptedstudents`,`testfrom`,`testto`,`declareResult`,`batchid`,`negativemarking`,
				   `total_marks`,`randomqstn`,`pp`,`testdesc`,`testcode`,`createDate`,`last_modified_on`,`status`,`moduleIds`,`moduleNoOfQsnts`,`isCategory`)
					VALUES(".$row_value.");";

			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to insert Exam : '".$sql."'" );
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
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Success !  Exam inserted successfully in Database : '".$cumulative_rows."'" );
			$exam_id = $this->getExamId($examName);
			if($exam_id != ""){
				foreach($noOfModuleQstsArr as $d){
					$moduleId = $d['id'];
					$moduleNoOfQstns =$d['noOfQstns'];
					$isCategory =$d['isCategory'];
					if($isCategory==0 )
					// Get category details
					//	$details = $this->getModuleDetails($moduleId);
					$this->selectQuestions($subjectId,$moduleId,"",$moduleNoOfQstns,$exam_id);
					else{
						// Get Module Details
						$obj = new manageCategory();
						$category_id = $obj->getModuleCategory($moduleId);
						$this->selectQuestions($subjectId,$category_id,$moduleId,$moduleNoOfQstns,$exam_id);
					}

					// Get Module details
					//	$details = $this->getModuleDetails($moduleId);
					/*
						if($details[0]!="")
						//$subjectId,$category,$module,$noOfQstns,$exam_id
						$this->selectQuestions($subjectId,$details[0],$details[1],$moduleNoOfQstns,$exam_id);
						else
						log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Error !  'selectedCategory' and 'module' does not exist  in Database '" );
						*/
				}
				return $this->insertQstnsInExam();
			}
			else{
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Error !  Exam Id does not exist with name in Database : '".$examName."'" );
				return false;
			}
			return true;
		} else {
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	/*
	 function createExam()
	 {
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertSQL)) {
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Success !  Exam inserted successfully in Database : '".$cumulative_rows."'" );
		return true;
		} else {
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
		return false;
		}
		}

		*/

	function updateExam()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_query($conn, $this->updateSQL)) {
			if(mysqli_affected_rows($conn)>0){
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Success !  Question save successfully in Database : '".mysqli_affected_rows($con)."'" );
				return true;
			}else{
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , ERROR #! No records found to update " );
				return false;
			}
		} else {
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}



	function executeUpdateQuery($sql)
	{
		$conn = DbConn::getDbConn();
		if (mysqli_query($conn, $sql)) {
			if(mysqli_affected_rows($conn)>0){
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , Success !  SQL executed '".mysqli_affected_rows($con)."'" );
				return true;
			}else{
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , ERROR #! while executing query. " );
				return false;
			}
		} else {
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , ERROR #! while executing query. " );
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

		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to update Question : '".$sql."'" );
		$this->updateSQL .=$sql;
	}

	function deleteExam($exam_id){
		$obj_history = new manageHistory();
		$sql = "update `assessment`.`exam` set
				`status`='inactive' , `last_modified_on`='".date("Y-m-d H:i:s", time())."' ";
		$sql.="where id='".$exam_id."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to delete Exam : '".$sql."'" );
		if(!$this->executeUpdateQuery($sql))
		{
		 	$obj_history->addExamHistory("delete", $exam_id, "", 1, 1, "", $exName,'Error while delete Exam , Please try again later!' );
			$data =  array('message' => 'Error while delete Exam , Please try again later!') ;
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while deleting Exam.".json_encode($data) );
		} else {
		 	$obj_history->addExamHistory("delete", $exam_id, "", 1, 0, "", $exName, 'Exam deleted Successfully , Status is inactive now !!!.' );
			$data =  array('message' => 'Exam deleted Successfully , Status is inactive now !!!.') ;
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Exam deleting Successfully !!!." );
		}

		echo json_encode($data);
		//return $this->();
	}

	function getQuestionToEdit($subjectId,$qstnId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";
		if($subjectId!="")
		$sql.= " where subid='".htmlspecialchars($subjectId,ENT_QUOTES)."' and qnid='".htmlspecialchars($qstnId,ENT_QUOTES)."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Question for editing : '".$sql."'" );
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

	function getExamDetailsToEditQstns($exam_name)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam`";
		if($exam_name!="")
		$sql.= " where testname='".htmlspecialchars($exam_name,ENT_QUOTES)."'";
		log_event(MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Exam Details : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$qstnObj = new getSubjectDetails();
		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$jsonArr[0]=$row['testname'];
			$jsonArr[1]=$row['totalquestions'];
			$jsonArr[2]=$row['batchid'];
			$jsonArr[3]=$row['testfrom'];
			$jsonArr[4]=$row['duration']." Minutes";
			$jsonArr[5]=$row['total_marks'];
			$jsonArr[6]=$row['testfrom'];
			$jsonArr[7]=$row['testto'];
			$qp_code=$row['subid'];
			$jsonArr[8]= $qstnObj->getJobRole($qp_code)." (".$qp_code.")";
			$jsonArr[9]=$qstnObj->getSSC($qp_code);
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			break;
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}

	function getExamDetailsToDisplayOnExamQuestionsPage($exam_id)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`exam`";
		if($exam_id!="")
		$sql.= " where id='".htmlspecialchars($exam_id,ENT_QUOTES)."'";
		log_event(MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to get Exam Details : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$qstnObj = new getSubjectDetails();
		while ($row)
		{
			$i++;
			//$jsonArr[0]= $i;
			$jsonArr[0]=$row['testname'];
			$jsonArr[1]=$row['totalquestions'];
			$jsonArr[2]=$row['batchid'];
			
			// Get Batch Details
			// Get Students details
			$attendence_object = new manageAttendence();
			$batch_detail_array = $attendence_object->getBatchDetailsArray($row['batchid']);
			$this->exam_date  = $batch_detail_array['EXAM_DATE'];
			
			$jsonArr[3]=$this->exam_date;
			$jsonArr[4]=$row['duration'];
			$jsonArr[5]=$row['total_marks'];
			$jsonArr[6]=$row['testfrom'];
			$jsonArr[7]=$row['testto'];
			$qp_code=$row['subid'];
			$jsonArr[8]= $qstnObj->getJobRole($qp_code)." (".$qp_code.")";
			$jsonArr[9]=$qstnObj->getSSC($qp_code);
			//$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			break;
		}
		// Free result set
		mysqli_free_result($result);
		return $jsonArr;
	}



	function getExamQstnListToEdit($exam_name,$exam_id){
		$conn = DbConn::getDbConn();
		$qstn_id="";
		$sql="SELECT qstn_id,category,module,mark FROM `assessment`.`exam_qstn` where exam_id = '".$exam_id."'";
		log_event(MANAGE_QUESTIONS, __LINE__."  ". __FILE__."  , SQL to get Exam Questions List: '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$qstnObj = new manageQuestions();
		$obj = new manageCategory();
		$i=0;
		while ($row)
		{
			//$jsonArr[0]="";
			$category_name	= $obj->getCategoryName($row['category']);
			$module_name	= $obj->getModuleName($row['module']);
			$jsonArr[0]=$category_name;
			$jsonArr[1]=$module_name;
			// Get Questions Details
			$qstn_details = $qstnObj->getQuestionDetails($row['qstn_id']);
			//log_event(MANAGE_QUESTIONS, __LINE__."  ". __FILE__."  , Question Details: '".print_r($qstn_details)."'" );
			$jsonArr[2]=$qstn_details[1];
			$jsonArr[3]=$qstn_details[2];
			$jsonArr[4]=$qstn_details[3];
			$jsonArr[5]=$qstn_details[4];
			$jsonArr[6]=$qstn_details[5];
			$jsonArr[7]=$qstn_details[6];
			$jsonArr[8]="";
			$jsonArr[9]=$row['mark'];
			$jsonArr[10]=$row['qstn_id'];
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

$obj = new manageExams();
$obj_history = new manageHistory();

/*
// Handle Requests from UI

echo json_encode($obj->getExamQuestionsInJSONString("1",""));
//echo $obj->getNoOfQstns("1");
*/


if(isset($_POST['get'])){
	$requestParam = $_POST['get'];
	log_event( MANAGE_TEST, "Get Request with parameter :'".$_POST['get']."'" );
	if($requestParam == "exams"){
		$subjectId="";
		if(isset($_POST['subid']))
		$subjectId = $_POST['subid'];
		log_event( MANAGE_TEST, "Get Exam List for subject  : '".$_POST['qpCode']."'" );
		$obj->getExamsList($subjectId);
	}

	else if($requestParam == "examDetailsForInfo"){
		$subjectId="";
		if(isset($_POST['exam_name']))
		$examName = $_POST['exam_name'];
		log_event( MANAGE_TEST, "Get Exam Information for exam  : '".$examName."'" );
		$obj->getExamDetailsForDialogueBox($examName);
	}

}

if(isset($_POST['action'])){
	log_event( MANAGE_TEST, " Get Request in ManageExams Page." );
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

	 if($obj->getExamId($exName) != ""){
	 	$obj_history->addExamHistory("create", "", $exName, 1, 1, "", $exName,'Exam with same name "'.$exName.'" already exist , Pleae select different name.' );
	 	
	 	$data =  array('error' => 'Exam with same name "'.$exName.'" already exist , Pleae select different name.') ;
	 	echo json_encode($data);
	 }else{
	 
	 // log_event( MANAGE_TEST, __LINE__."  ". __FILE__. " ,  Number of Questions in each Module : " . print_r($noOfModuleQstsArr));
	 log_event( MANAGE_TEST, __LINE__."  ". __FILE__." ,  Get Request to create Exam with Values : '".
	 $subId."','".$exName."','".$noOfQstns."','".$examDesc."','".$examDur."','".$atmptCount.
		"','".$startDate."','"
		.$endDate."','".$decResult."','".$batchId."','".$negMarking."','".$randomQstn."','".$totalMarks."','".$pp."'");

	 $obj->buildInsertSql($subId,$exName,$noOfQstns,$examDesc,$examDur,$atmptCount,$startDate,$endDate,$decResult,$batchId,$negMarking,$randomQstn,$totalMarks,$pp,$_POST['noOfModuleQstsArr']);

	 if(!$obj->createExam($exName,$subId,$_POST['noOfModuleQstsArr']))
		{
		 	$obj_history->addExamHistory("create", "", $exName, 1, 1, "", $exName,'Exam with same name "'.$exName.'" already exist , Pleae select different name.' );
			$data =  array('error' => 'Error while creating exam , Please check all details and try again.') ;
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__." Error while inserting Question in DB.".json_encode($data) );
		} else {
		 	$obj_history->addExamHistory("create", "", $exName, 1, 0, "", $exName,'Exam created successfully' );
			$data =  array('success' => 'Exam Created Successfully.') ;
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__." Exam created successfully." );
		}
		echo json_encode($data);
	 }
	}
	else if($_GET['action']=="edit"){
		log_event( MANAGE_TEST, " Get Request to get question details." );
		if(isset($_GET['subId']) && isset($_GET['qstnId'])){
			$subId = $_GET['subId'];
			$qstnId = $_GET['qstnId'];
			$obj->getQuestionToEdit($subId, $qstnId);
		}else{
			log_event( MANAGE_TEST, "Error : 'subId' && 'qstnId' are not set to edit Question.");
			$data =  array('error' => "Error : 'subId' && 'qstnId' are not set to edit Question.") ;
			echo json_encode($data);
		}
	}
	else if($_POST['action']=="getExamInfo"){
		log_event( MANAGE_TEST, "Get Request to get Exam Details to edit Questions." );
		if(isset($_POST['exam_name'])){
			$exam_name = $_POST['exam_name'];
			$obj->getExamDetailsToEditQstns($exam_name);
		}
	}
	else if($_POST['action']=="getExamQstns"){
		log_event( MANAGE_TEST, "Get Request to get Exam Questions to edit Questions." );
		if(isset($_POST['examName'])){
			$examName = $_POST['examName'];
			$obj->getExamQstnListToEdit("",$obj->getExamId($examName));
		}
	}
	else if($_POST['action']=="delete"){
		log_event( MANAGE_TEST, "Get Request to delete Exam." );
		if(isset($_POST['examName'])){
			$examName = $_POST['examName'];
			$obj->deleteExam($obj->getExamId($examName));
		}
	}

	// Update defect details.
	else if($_GET['action']=="update"){
		log_event( MANAGE_TEST, " Get Request to update question." );
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
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__." Error while inserting Question in DB.".json_encode($data) );
			} else {
				$data =  array('success' => 'Question saved Successfully.') ;
				log_event( MANAGE_TEST, __LINE__."  ". __FILE__." Question saved successfully." );
			}
			echo json_encode($data);

		}else{
			log_event( MANAGE_TEST, "Error : 'subId' && 'qstnId' are not set to edit Question.");
			$data =  array('error' => "Error : 'subId' && 'qstnId' are not set to edit Question.") ;
			echo json_encode($data);
		}
	}
}

?>
