<?php

require_once 'DbConn.php';
require_once 'logging_api.php';
require_once 'manageQuestions.php';


class manageCategory{


	function buildInsertCategorySql($qpCode,$category,$module){
			// Insert new row
			$row_value= "'".htmlspecialchars($qpCode,ENT_QUOTES)."','".htmlspecialchars($category,ENT_QUOTES).
					"','".htmlspecialchars($module,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','active'";

			$sql = "INSERT INTO `assessment`.`question_category`
				   (`subId`,`category`,`module`,`created_on`,`last_modified_on`,`status`)
					 VALUES(".$row_value.");";
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Category : '".$sql."'" );
			$this->insertSQL .=$sql;
	}

	function addCategory()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertSQL)) {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Category inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function updateCategory()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_query($conn, $this->updateSQL)) {
			if(mysqli_affected_rows($conn)>0){
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Category save successfully in Database : '".mysqli_affected_rows($con)."'" );
				return true;
			}else{
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , ERROR #! No Category found to update " );
				return false;
			}
		} else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function addModule()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertModuleSQL)) {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , Success !  Module inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}


	function addModuleInDatabase($qpCode,$category,$module)
	{
		$categoryId = "";
		if($module!=""){
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." . Get Category ID" );
			$categoryId = $this->getCategoryId($qpCode,$category);
			if($categoryId!=""){
				// Update existing row
				$sql = "update `assessment`.`question_category` set `module`='".$module."',`last_modified_on`='".date("Y-m-d H:i:s", time())."' where id='".$categoryId."'" ;
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert module in existing row : '".$sql."'" );
				$this->updateSQL = $sql;
				return $this->updateCategory();
			}else{
				// Insert new row
				$row_value= "'".htmlspecialchars($qpCode,ENT_QUOTES)."','".htmlspecialchars($category,ENT_QUOTES).
					"','".htmlspecialchars($module,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','active'";

				$sql = "INSERT INTO `assessment`.`question_category`
				   (`subId`,`category`,`module`,`created_on`,`last_modified_on`,`status`)
					 VALUES(".$row_value.");";
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to insert Module : '".$sql."'" );
				$this->insertModuleSQL =$sql;
				return $this->addModule();
			}
		}
		else
		return false;
	}




	function getCategoryList($subjectId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT category FROM `assessment`.`question_category`";
		//if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Categories : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[]= $row['category'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode(array_unique($jsonArr));
	}


	function getCategoryId($subjectId,$category)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT id FROM `assessment`.`question_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category='".htmlspecialchars($category,ENT_QUOTES)."' and module=''";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Category Id : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$categoryId = "";
		while ($row)
		{
			$categoryId = $row['id'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		return $categoryId;
	}


	function getModuleList($subjectId,$category)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT module FROM `assessment`.`question_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category='".htmlspecialchars($category,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Categories : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[]= $row['module'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($jsonArr);
	}

	function getModuleListForTestCreation($subjectId,$category)
	{
		$conn = DbConn::getDbConn();
		$manageQstnsObj = new manageQuestions();
		$sql="SELECT id,module FROM `assessment`.`question_category` where module!=''";
		if($subjectId!="")
		$sql.= " and subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category='".htmlspecialchars($category,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Categories : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr["id"]= $row['id'];
			$jsonArr["moduleName"]= $row['module'];
			$jsonArr["noOfQstns"] = $manageQstnsObj->getQuestionCount($subjectId,$category,$row['module']);
			$jsonArrFinal[]=$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		return $jsonArrFinal;
	}

	function getModuleCategoryForTestCreation($subjectId)
	{
		$conn = DbConn::getDbConn();
		$manageQstnsObj = new manageQuestions();
		$sql="SELECT id,category FROM `assessment`.`question_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and status='active' and  module=''";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Categories  without Modules : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$moduleExist = false;
		//$categoryArray =
		while ($row)
		{
			// Get all Modules
			$moduleArray = $this->getModuleListForTestCreation($subjectId,$row['category']);
			$catModArray["id"] = $row['id'];
			$catModArray["category"] = $row['category'];
			$catModArray["modules"] = $moduleArray;
			$catModArray["noOfQstnsInCategory"] = $manageQstnsObj->getQuestionCount($subjectId,$row['category'],"");
			$jsonArr[]=$catModArray;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);



		// Get Category with Modules
		$catModArray= array();
		$sql="SELECT distinct(category) FROM `assessment`.`question_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and status='active' and module!=''";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Categories  without Modules : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$moduleExist = false;
		//$categoryArray =
		while ($row)
		{
			// Get all Modules
			$moduleArray = $this->getModuleListForTestCreation($subjectId,$row['category']);
			$catModArray["id"] = "";
			$catModArray["category"] = $row['category'];
			$catModArray["modules"] = $moduleArray;
			$catModArray["noOfQstnsInCategory"] = $manageQstnsObj->getQuestionCount($subjectId,$row['category'],"");
			$jsonArr[]=$catModArray;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);



		$final_array = array('data' => $jsonArr);

		//$final_array = array('category' => array_unique($final_array));
		// print json Data.
		echo json_encode($final_array);
	}




	function displayModulesRows(){
		$conn = DbConn::getDbConn();
		$sql="SELECT module FROM `assessment`.`question_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category='".htmlspecialchars($category,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Categories : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[]= $row['module'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($jsonArr);
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


	function getQstnListForImportPage()
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`question`";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Question : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr[0]= $i;
			$jsonArr[1]=$row['ssc'];
			$jsonArr[2]=$row['job_role'];
			$jsonArr[3]=$row['category'];
			$jsonArr[4]=$row['module'];
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
$obj = new manageCategory();

//$obj->getModuleCategoryForTestCreation("AGR/Q4804");

if(isset($_GET['get'])){
	$requestParam = $_GET['get'];
	log_event( LOG_DATABASE, "Get Request with parameter : '".$_GET['get']."'" );
	//$obj->getQstnList("");

	if($requestParam == "categories"){
		$subjectId="";
		if(isset($_GET['subId']) && $_GET['subId']!=""){
			$subjectId = $_GET['subId'];
			log_event( LOG_DATABASE, "Get Categories List for subject  : '".$_GET['subId']."'" );
			$obj->getCategoryList($subjectId);
		}
	}else if($requestParam == "modules"){
		if(isset($_GET['subId']) && isset($_GET['category'])){
			$subjectId = $_GET['subId'];
			log_event( LOG_DATABASE, "Get Module List for subject : '".$_GET['subId']."' and category '".$_GET['category']."'" );
			$obj->getModuleList($subjectId,$_GET['category']);
		}
	} else if ($requestParam == "catNModules"){
		if(isset($_GET['subId'])){
			$subjectId = $_GET['subId'];
			log_event( LOG_DATABASE, "Get Category and Module List for subject : '".$_GET['subId']."' and category '".$_GET['category']."'" );
			$obj->getModuleCategoryForTestCreation($subjectId);
		}
	}

	else{
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."Error : Invalid Request" );
		$data =  array('error' => "Error :  Invalid Request parameter.") ;
		echo json_encode($data);
	}
}
else if($_GET['action']=="createCat"){
	log_event( LOG_DATABASE, "Get Request to create category or module." );
	if(isset($_GET['subId']) && isset($_GET['category']) &&  $_GET['subId']!="" && $_GET['category']!="" ){
		$subId = $_GET['subId'];
		if(isset($_GET['module'])){
			log_event( LOG_DATABASE, "Get Request to create Module." );
			//$obj->buildInsertSql($subId, $_GET['category'],$_GET['module']);
			if(!$obj->addModuleInDatabase($subId, $_GET['category'],$_GET['module']))
			{
				$data =  array('message' => 'Error while inserting Module in DB.') ;
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while inserting Module in DB.".json_encode($data) );
			} else {
				$data =  array('message' => 'Module Created Successfully.') ;
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Module created successfully." );
			}

		}else{
			log_event( LOG_DATABASE, "Get Request to create Category." );
			$obj->buildInsertCategorySql($subId, $_GET['category'],"");
			if(!$obj->addCategory())
			{
				$data =  array('message' => 'Error while inserting Category in DB.') ;
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while inserting Category in DB.".json_encode($data) );
			} else {
				$data =  array('message' => 'Category Created Successfully.') ;
				log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Category created successfully." );
			}
		}
		echo json_encode($data);
	}else{
		log_event( LOG_DATABASE, "Error : 'category' or 'subid' not set in request.");
		$data =  array('message' => "Error : 'category' or 'subid' is not set to create Category.") ;
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


?>
