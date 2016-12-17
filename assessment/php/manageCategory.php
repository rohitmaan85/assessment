<?php

require_once 'DbConn.php';
require_once 'logging_api.php';
require_once 'manageQuestions.php';


class manageCategory{


	function buildInsertCategorySql($qpCode,$category,$module){
			// Insert new row
			$row_value= "'".htmlspecialchars($qpCode,ENT_QUOTES)."','".htmlspecialchars($category,ENT_QUOTES).
					"','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','active'";

			$sql = "INSERT INTO `assessment`.`subject_category`
				   (`subId`,`category`,`created_on`,`last_modified_on`,`status`)
					 VALUES(".$row_value.");";
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to insert Category : '".$sql."'" );
			$this->insertSQL .=$sql;
	}

	function buildRenameCategorySql($category_id,$new_name){
			$sql = "update `assessment`.`subject_category` set category='".$new_name."' , last_modified_on='".date("Y-m-d H:i:s", time())."' where id='".$category_id."'";
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to rename Category : '".$sql."'" );
			$this->updateSQL .=$sql;
	}


	function buildRenameModuleSql($module_id,$new_name){
			$sql = "update `assessment`.`subject_category_module` set module='".$new_name."' , last_modified_on='".date("Y-m-d H:i:s", time())."' where id='".$module_id."'";
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to rename Module : '".$sql."'" );
			$this->updateModuleSQL .=$sql;
	}

	function buildInsertModuleSql($qpCode,$category,$module){
		  $category_id = $this->getCategoryId($qpCode,$category);
			// Insert new row
			$row_value= "'".htmlspecialchars($qpCode,ENT_QUOTES)."','".htmlspecialchars($category_id,ENT_QUOTES).
					"','".htmlspecialchars($module,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','active'";

			$sql = "INSERT INTO `assessment`.`subject_category_module`
					 (`subId`,`category_id`,`module`,`created_on`,`last_modified_on`,`status`)
					 VALUES(".$row_value.");";
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to create Module : '".$sql."'" );
			$this->insertModuleSQL .=$sql;
	}

	function addCategory()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertSQL)) {
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , Success !  Category inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}


	function addModule()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_multi_query($conn, $this->insertModuleSQL)) {
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , Success!!  Module inserted successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function updateCategory()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_query($conn, $this->updateSQL)) {
			if(mysqli_affected_rows($conn)>0){
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , Success !!  Category update successfully in Database : '".mysqli_affected_rows($con)."'" );
				return true;
			}else{
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , ERROR #! No Category found to update " );
				return false;
			}
		} else {
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}



	function updateModule()
	{
		$conn = DbConn::getDbConn();
		if (mysqli_query($conn, $this->updateModuleSQL)) {
			if(mysqli_affected_rows($conn)>0){
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , Success !  Module update successfully in Database : '".mysqli_affected_rows($con)."'" );
				return true;
			}else{
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , ERROR #! No Module found to update " );
				return false;
			}
		} else {
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	function addModuleInDatabase($qpCode,$category,$module)
	{
		$categoryId = "";
		if($module!=""){
			log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." . Get Category ID" );
			$categoryId = $this->getCategoryId($qpCode,$category);
			if($categoryId!=""){
				// Update existing row
				$sql = "update `assessment`.`question_category` set `module`='".$module."',`last_modified_on`='".date("Y-m-d H:i:s", time())."' where id='".$categoryId."'" ;
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to insert module in existing row : '".$sql."'" );
				$this->updateSQL = $sql;
				return $this->updateCategory();
			}else{
				// Insert new row
				$row_value= "'".htmlspecialchars($qpCode,ENT_QUOTES)."','".htmlspecialchars($category,ENT_QUOTES).
					"','".htmlspecialchars($module,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."','".date("Y-m-d H:i:s", time())."','active'";

				$sql = "INSERT INTO `assessment`.`question_category`
				   (`subId`,`category`,`module`,`created_on`,`last_modified_on`,`status`)
					 VALUES(".$row_value.");";
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to insert Module : '".$sql."'" );
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
		$sql="SELECT category FROM `assessment`.`subject_category`";
		//if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Categories : '".$sql."'" );
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
		$sql="SELECT id FROM `assessment`.`subject_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category='".htmlspecialchars($category,ENT_QUOTES)."' and status='active'";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Category Id : '".$sql."'" );
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

	
	function getCategoryName($category_id)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT category FROM `assessment`.`subject_category`";
		if($category_id!="")
			$sql.= " where id='".htmlspecialchars($category_id,ENT_QUOTES)."'";
		else
			return "";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Category Id : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$categoryId = "";
		while ($row)
		{
			$categoryName = $row['category'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		return $categoryName;
	}
	
	function getModuleName($module_id)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT module FROM `assessment`.`subject_category_module`";
		if($module_id!="")
			$sql.= " where id='".htmlspecialchars($module_id,ENT_QUOTES)."'";
		else
			return "";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Module Id : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$module_name = "";
		while ($row)
		{
			$module_name = $row['module'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		return $module_name;
	}

	function getModuleId($subjectId,$module)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT id FROM `assessment`.`subject_category_module`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and module='".htmlspecialchars($module,ENT_QUOTES)."'";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Module Id : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$categoryId = "";
		while ($row)
		{
			$moduleId = $row['id'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		return $moduleId;
	}



	function getModuleList($subjectId,$category)
	{
		$conn = DbConn::getDbConn();
		$category_id = $this->getCategoryId($subjectId,htmlspecialchars($category,ENT_QUOTES));
		$sql="SELECT module FROM `assessment`.`subject_category_module`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category_id='".$category_id."'";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Categories : '".$sql."'" );
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

	function getModuleListForTestCreation($subjectId,$category_id)
	{
		$conn = DbConn::getDbConn();
		$manageQstnsObj = new manageQuestions();
		$sql="SELECT id,module FROM `assessment`.`subject_category_module` where status='active'";
		if($subjectId!="")
		$sql.= " and subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category_id='".$category_id."'";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Module and its Questions : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr["id"]= $row['id'];
			$jsonArr["moduleName"]= $row['module'];
			$jsonArr["noOfQstns"] = $manageQstnsObj->getQuestionCount($subjectId,$category_id,$row['id']);
			$jsonArrFinal[]=$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		return $jsonArrFinal;
	}

	function getModuleCategoryForTestCreation($subjectId)
	{
		$conn = DbConn::getDbConn();
		$manageQstnsObj = new manageQuestions();
		$sql="SELECT id,category FROM `assessment`.`subject_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and status='active'";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Categories '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$moduleExist = false;
		//$categoryArray =
		while ($row)
		{
			// Get all Modules
			$moduleArray = $this->getModuleListForTestCreation($subjectId,$row['id']);
			$catModArray["id"] = $row['id'];
			$catModArray["category"] = $row['category'];
			$catModArray["modules"] = $moduleArray;
			$catModArray["noOfQstnsInCategory"] = $manageQstnsObj->getQuestionCount($subjectId,$row['id'],"");
			$jsonArr[]=$catModArray;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);

/*
		// Get Category with Modules
		$catModArray= array();
		$sql="SELECT distinct(category) FROM `assessment`.`subject_category`";
		if($subjectId!="")
		$sql.= " where subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and status='active'";
		log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Categories  without Modules : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$moduleExist = false;
		//$categoryArray =
		while ($row)
		{
			// Get all Modules
			$moduleArray = $this->getModuleListForTestCreation($subjectId,$row['id']);
			$catModArray["id"] = "";
			$catModArray["category"] = $row['category'];
			$catModArray["modules"] = $moduleArray;
			$catModArray["noOfQstnsInCategory"] = $manageQstnsObj->getQuestionCount($subjectId,$row['id'],"");
			$jsonArr[]=$catModArray;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		*/
		$final_array = array('data' => $jsonArr);
		// print json Data.
		echo json_encode($final_array);
	}


	function getCategoryListWithId($subjectId)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT id,category FROM `assessment`.`subject_category` where status='active'";
		$sql.= " and subId='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		log_event(MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Categories : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArrId[]= $row['id'];
			$jsonArrCategory[]= $row['category'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('ids' => $jsonArrId,'category' => $jsonArrCategory);
		//log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Categories : '".	print_r($final_array)."'" );

	;
			// Free result set
		mysqli_free_result($result);
		// print json Data.

		echo json_encode($final_array);
	}

	function getModuleListWithId($subjectId,$category_id)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT id,module FROM `assessment`.`subject_category_module` where status='active'";
		if($subjectId!="")
		$sql.= " and subId='".htmlspecialchars($subjectId,ENT_QUOTES)."' and category_id='".$category_id."'";
		log_event(MANAGE_CATEGORY, __LINE__."  ". __FILE__."  , SQL to get Modules : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArrId[]= $row['id'];
			$jsonArrModule[]= $row['module'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
	//	$final_array = array('data' => $jsonArr);
		$final_array = array('ids' => $jsonArrId,'module' => $jsonArrModule);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}
	
	function deleteCategory($cat_id){
		$sql = "update `assessment`.`subject_category` set
				`status`='inactive' , `last_modified_on`='".date("Y-m-d H:i:s", time())."' ";
		$sql.="where id='".$cat_id."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to delete Category : '".$sql."'" );
		if(!$this->executeUpdateQuery($sql))
		{
			$data =  array('message' => 'Error while delete Category , Please try again later!') ;
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while deleting Category.".json_encode($data) );
		} else {
			$data =  array('message' => 'Category deleted Successfully , Status is inactive now !!!.') ;
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Category deleted Successfully !!!." );
		}
	
		echo json_encode($data);
	//return $this->();
  }
  
	function deleteModule($mod_id){
		$sql = "update `assessment`.`subject_category_module` set
				`status`='inactive' , `last_modified_on`='".date("Y-m-d H:i:s", time())."' ";
		$sql.="where id='".$mod_id."'";
		log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , SQL to delete Module : '".$sql."'" );
		if(!$this->executeUpdateQuery($sql))
		{
			$data =  array('message' => 'Error while delete Module , Please try again later!') ;
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Error while deleting Module.".json_encode($data) );
		} else {
			$data =  array('message' => 'Module deleted Successfully , Status is inactive now !!!.') ;
			log_event( LOG_DATABASE, __LINE__."  ". __FILE__." Module deleted Successfully !!!." );
		}
	
		echo json_encode($data);
	//return $this->();
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

}


// Handle Requests from UI
$obj = new manageCategory();

//$obj->getModuleCategoryForTestCreation("AGR/Q4804");

if(isset($_GET['get'])){
	$requestParam = $_GET['get'];
	log_event( MANAGE_CATEGORY, "Get Request with parameter : '".$_GET['get']."'" );
	//$obj->getQstnList("");

	if($requestParam == "categories"){
		$subjectId="";
		if(isset($_GET['subId']) && $_GET['subId']!=""){
			$subjectId = $_GET['subId'];
			log_event( MANAGE_CATEGORY, "Get Categories List for subject  : '".$_GET['subId']."'" );
			$obj->getCategoryList($subjectId);
		}
	}else if($requestParam == "modules"){
		if(isset($_GET['subId']) && isset($_GET['category'])){
			$subjectId = $_GET['subId'];
			log_event( MANAGE_CATEGORY, "Get Module List for subject : '".$_GET['subId']."' and category '".$_GET['category']."'" );
			$obj->getModuleList($subjectId,$_GET['category']);
		}
	} else if ($requestParam == "catNModules"){
		if(isset($_GET['subId'])){
			$subjectId = $_GET['subId'];
			log_event( MANAGE_CATEGORY, "Get Category and Module List for subject : '".$_GET['subId']."' and category '".$_GET['category']."'" );
			$obj->getModuleCategoryForTestCreation($subjectId);
		}
	}

}
else if($_GET['action']=="createCat"){
	log_event( MANAGE_CATEGORY, "Get Request to create category or module." );
	if(isset($_GET['subId']) && isset($_GET['category']) &&  $_GET['subId']!="" && $_GET['category']!="" ){
		$subId = $_GET['subId'];
		if(isset($_GET['module'])){
			log_event( MANAGE_CATEGORY, "Get Request to create Module." );
			if($_GET['category'] ==""){
					$data =  array('message' => 'Error while creating Module in DB.') ;
			}else{			
			//$obj->buildInsertSql($subId, $_GET['category'],$_GET['module']);
			$obj->buildInsertModuleSql($subId, $_GET['category'],$_GET['module']);
			if(!$obj->addModule())
			{
				$data =  array('message' => 'Error while inserting Module in DB.') ;
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Error while inserting Module in DB.".json_encode($data) );
			} else {
				$data =  array('message' => 'Module Created Successfully.') ;
				log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Module created successfully." );
			}
		  }

		}else{
			log_event( MANAGE_CATEGORY, "Get Request to create Category." );
			$obj->buildInsertCategorySql($subId, $_GET['category'],"");

			if( $obj->getCategoryId($subId,$_GET['category'])!=""){
					log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Category already Exist  !!" );
						$data =  array('message' => 'Error while creating Category in DB, Category already Exists.') ;
			}

			else{
					if(!$obj->addCategory())
					{
						$data =  array('message' => 'Error while inserting Category in DB.') ;
						log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Error while inserting Category in DB.".json_encode($data) );
					} else {
						$data =  array('message' => 'Category Created Successfully.') ;
						log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Category created successfully." );
					}

				}
		}
		echo json_encode($data);
	}else{
		log_event( MANAGE_CATEGORY, "Error : 'category' or 'subid' not set in request.");
		$data =  array('message' => "Error : 'category' or 'subid' is not set to create Category.") ;
		echo json_encode($data);
	}
}
// Update defect details.
else if($_POST['action']=="getCategories")
	{
		$subjectId="";
		if(isset($_POST['subId']) && $_POST['subId']!=""){
			$subjectId = $_POST['subId'];
			log_event(MANAGE_CATEGORY, "Get Categories List with Ids for subject  : '".$_POST['subId']."'" );
			$obj->getCategoryListWithId($subjectId);
		}
 }
 else if($_POST['action']=="getModules")
 	{
 		$subjectId="";
 		if(isset($_POST['subId']) && $_POST['subId']!="" && $_POST['cat_id']!=""){
 			$subjectId = $_POST['subId'];
			$category_id = $_POST['cat_id'];
 			log_event(MANAGE_CATEGORY, "Get Modules List with Ids for subject  : '".$_POST['subId']."' and category id =". $_POST['cat_id']);
 			$obj->getModuleListWithId($subjectId,$category_id);
 		}
  }
  
  
  else if($_POST['action']=="deleteCategory")
  	{
  		$subjectId="";
  		if(isset($_POST['cat_id'])&& $_POST['cat_id']!="" ){
				log_event(MANAGE_CATEGORY, "Delete Category with Id =". $_POST['cat_id']);
				if($_POST['cat_id']==""){
					$data =  array('message' => "Error : Category can not be blank , please select valid Category.") ;
					echo json_encode($data);
				}
				else
				{
					$category_id = $_POST['cat_id'];
  		  			$obj->deleteCategory($category_id);
                }
  		}
  		else{
  							$data =  array('message' => "Error : Category can not be blank , please select valid Category.") ;
					echo json_encode($data);
	
  		}
	}
	
  else if($_POST['action']=="deleteModule")
  	{
  		if(isset($_POST['mod_id'])&& $_POST['mod_id']!="" ){
				log_event(MANAGE_CATEGORY, "Delete Module with Id =". $_POST['mod_id']);
				if($_POST['mod_id']==""){
					$data =  array('message' => "Error : Module can not be blank , please select valid Category.") ;
					echo json_encode($data);
				}
				else
				{
					$mod_id = $_POST['mod_id'];
  		  			$obj->deleteModule($mod_id);
                }
  		}
  		else{
  							$data =  array('message' => "Error : Module can not be blank , please select valid Category.") ;
					echo json_encode($data);
	
  		}
	}
  
  
	else if($_POST['action']=="renameCategory")
  	{
  		$subjectId="";
  		if(isset($_POST['subId']) && $_POST['subId']!="" && $_POST['cat_id']!="" ){
				log_event(MANAGE_CATEGORY, "Rename Category with Id for subject  : '".$_POST['subId']."' and category id =". $_POST['cat_id']);
				if($_POST['new_name']==""){
					$data =  array('message' => "Error : 'New Name' can not be blank , please enter valid name.") ;
					echo json_encode($data);
				}
				else
				{
					$subId=$_POST['subId'];
					if( $obj->getCategoryId($subId,$_POST['new_name'])!="")
					  {
									log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Category already Exist  !!" );
									$data =  array('message' => 'Error while creating Category in DB, Category already Exists "'.$_POST['new_name'].'"') ;
						}
						else
						{
			  			$category_id = $_POST['cat_id'];
							$new_name = $_POST['new_name'];
			  			$obj->buildRenameCategorySql($category_id,$new_name);
							if(!$obj->updateCategory())
							{
								$data =  array('message' => 'Error while renaming Category in DB.') ;
								log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Error while renaming Category in DB.".json_encode($data) );
							} else {
								$data =  array('message' => 'Category renamed Successfully to "'.$new_name.'"') ;
								log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Category rnamed successfully." );
							}
  	  	   }
					 echo json_encode($data);
		  	  }
    }
	}

	else if($_POST['action']=="renameModule")
		{
			$subjectId="";
			if(isset($_POST['subId']) && $_POST['subId']!="" && $_POST['cat_id']!="" ){
				log_event(MANAGE_CATEGORY, "Rename Module with Id for subject  : '".$_POST['subId']."' and category id =". $_POST['cat_id']);
				if($_POST['new_name']==""){
					$data =  array('message' => "Error : 'New Name' can not be blank , please enter valid name.") ;
					echo json_encode($data);
				}
				else
				{
					$subId=$_POST['subId'];
					// If Module already Exist.
					if( $obj->getModuleId($subId,$_POST['new_name'])!="")
						{
									log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Module with this name already Exist  !!" );
									$data =  array('message' => 'Error while rename module , Module with this name already Exist "'.$_POST['new_name'].'"') ;
						}
						else
						{
							$module_id = $_POST['mod_id'];
							$new_name = $_POST['new_name'];
							$obj->buildRenameModuleSql($module_id,$new_name);
							if(!$obj->updateModule())
							{
								$data =  array('message' => 'Error while renaming Module , Please try again later.') ;
								log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Error while renaming Module in DB.".json_encode($data) );
							} else {
								$data =  array('message' => 'Module renamed Successfully to "'.$new_name.'"') ;
								log_event( MANAGE_CATEGORY, __LINE__."  ". __FILE__." Module renamed successfully." );
							}
					 }
					 echo json_encode($data);
					}
		}
	}

?>
