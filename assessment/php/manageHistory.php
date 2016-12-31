<?php

date_default_timezone_set("Asia/Kolkata");
require_once 'DbConn.php';
require_once 'logging_api.php';

class manageHistory{

	function addExamHistory($action,$exam_id,$exam_name,$user_id,$status,$old_value,$new_value,$comments)
	{
		$conn = DbConn::getDbConn($exam_name,$created_by,$status,$comments);
		$row_value= "'".htmlspecialchars($exam_id,ENT_QUOTES)."','".htmlspecialchars($exam_name,ENT_QUOTES).
					"','".htmlspecialchars($user_id,ENT_QUOTES)."','".htmlspecialchars($action,ENT_QUOTES)."','".htmlspecialchars($status,ENT_QUOTES).
					"','".htmlspecialchars($old_value,ENT_QUOTES)."','".htmlspecialchars($new_value,ENT_QUOTES).
					"','".htmlspecialchars($comments,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."'";

		$sql = "INSERT INTO `assessment`.`exam_history`
				   (`exam_id`,`exam_name`,`user_id`,`action`,`status`,`old_value`,`new_value`,`comments`,`action_date`)
					 VALUES(".$row_value.");";

		log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,SQL  #" .$sql );
	
		if (mysqli_multi_query($conn, $sql)) {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  , Success!!  Create Exam History added successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}


	/*
	 * 
	 * Implement after fixing Update Question Page.
	 * Crete question is not working as of now.
	 * 
	 */
	function addQuestionHistory($action,$qstn_id,$user_id,$status,$old_value,$new_value,$comments)
	{
		$conn = DbConn::getDbConn($exam_name,$created_by,$status,$comments);
		$row_value= "'".htmlspecialchars($qstn_id,ENT_QUOTES)."','".htmlspecialchars($user_id,ENT_QUOTES).
					"','".htmlspecialchars($action,ENT_QUOTES)."','".htmlspecialchars($status,ENT_QUOTES).
					"','".htmlspecialchars($old_value,ENT_QUOTES)."','".htmlspecialchars($new_value,ENT_QUOTES).
					"','".htmlspecialchars($comments,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."'";
		

		$sql = "INSERT INTO `assessment`.`qstn_history`
				   (`qstn_id`,`user_id`,`action`,`status`,`comments`,`action_date`)
					 VALUES(".$row_value.");";

		if (mysqli_multi_query($conn, $sql)) {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  , Success!!  Create Question History added successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}

	
	/* 
	 * Implement it later
	 */
	function addSubjectHistory($action,$sub_id,$user_id,$status,$comments)
	{
		$conn = DbConn::getDbConn($exam_name,$created_by,$status,$comments);
		$row_value= "'".htmlspecialchars($sub_id,ENT_QUOTES)."','".htmlspecialchars($user_id,ENT_QUOTES).
					"','".htmlspecialchars($action,ENT_QUOTES)."','".htmlspecialchars($status,ENT_QUOTES).
					"','".htmlspecialchars($old_value,ENT_QUOTES)."','".htmlspecialchars($new_value,ENT_QUOTES).
					"','".htmlspecialchars($comments,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."'";
	
		$sql = "INSERT INTO `assessment`.`qstn_history`
				   (`qstn_id`,`user_id`,`action`,`status`,`comments`,`action_date`)
					 VALUES(".$row_value.");";

		if (mysqli_multi_query($conn, $sql)) {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  , Success!!  Create Subject History added successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}

	}
	
	function addCatModHistory($action,$sub_id,$cat_id,$mod_id,$user_id,$status,$old_value,$new_value,$comments)
	{
		$conn = DbConn::getDbConn($exam_name,$created_by,$status,$comments);
		$row_value= "'".htmlspecialchars($sub_id,ENT_QUOTES)."','".htmlspecialchars($cat_id,ENT_QUOTES)."','".htmlspecialchars($mod_id,ENT_QUOTES).
					"','".htmlspecialchars($user_id,ENT_QUOTES)."','".htmlspecialchars($action,ENT_QUOTES)."','".htmlspecialchars($status,ENT_QUOTES).
					"','".htmlspecialchars($old_value,ENT_QUOTES)."','".htmlspecialchars($new_value,ENT_QUOTES).
					"','".htmlspecialchars($comments,ENT_QUOTES)."','".date("Y-m-d H:i:s", time())."'";
	
		$sql = "INSERT INTO `assessment`.`cat_mod_history`
				   (`sub_id`,`cat_id`,`mod_id`,`user_id`,`action`,`status`,`old_value`,`new_value`,`comments`,`action_date`)
					 VALUES(".$row_value.");";
		log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,SQL  #" .$sql );
	
		if (mysqli_multi_query($conn, $sql)) {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  , Success !! Create category and Module History added successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}
	
	function addUploadQuestionHistory($action,$file_name,$ssc,$job_role,$cat_id,$mod_id,$user_id,$status,$comments)
	{
		$conn = DbConn::getDbConn($exam_name,$created_by,$status,$comments);
		$row_value= "'".htmlspecialchars($file_name,ENT_QUOTES)."','".htmlspecialchars($ssc,ENT_QUOTES)."','".htmlspecialchars($job_role,ENT_QUOTES).
					"','".htmlspecialchars($cat_id,ENT_QUOTES).
					"','".htmlspecialchars($mod_id,ENT_QUOTES)."','".htmlspecialchars($user_id,ENT_QUOTES)."','".htmlspecialchars($action,ENT_QUOTES).
					"','".htmlspecialchars($status,ENT_QUOTES)."','".htmlspecialchars($comments,ENT_QUOTES).
					"','".date("Y-m-d H:i:s", time())."'";
	
		$sql = "INSERT INTO `assessment`.`upload_qstn_history`
				   (`file_name`,`ssc`,`job_role`,`cat_id`,`mod_id`,`user_id`,`action`,`status`,`comments`,`action_date`)
					 VALUES(".$row_value.");";
		log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,SQL  #" .$sql );
	
		if (mysqli_multi_query($conn, $sql)) {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  , Success !! Create Upload Question History added successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}
	
	function addUploadBatchHistory($action,$file_name,$ssc,$job_role,$user_id,$status,$comments)
	{
		$conn = DbConn::getDbConn($exam_name,$created_by,$status,$comments);
		$row_value= "'".htmlspecialchars($file_name,ENT_QUOTES)."','".htmlspecialchars($ssc,ENT_QUOTES)."','".htmlspecialchars($job_role,ENT_QUOTES).
					"','".htmlspecialchars($user_id,ENT_QUOTES)."','".htmlspecialchars($action,ENT_QUOTES).
					"','".htmlspecialchars($status,ENT_QUOTES)."','".htmlspecialchars($comments,ENT_QUOTES).
					"','".date("Y-m-d H:i:s", time())."'";
	
		$sql = "INSERT INTO `assessment`.`upload_batch_history`
				   (`file_name`,`ssc`,`job_role`,`user_id`,`action`,`status`,`comments`,`action_date`)
					 VALUES(".$row_value.");";
		log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,SQL  #" .$sql );
	
		if (mysqli_multi_query($conn, $sql)) {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  , Success !! Create Upload Question History added successfully in Database : '".$cumulative_rows."'" );
			return true;
		} else {
			log_event(CREATE_EXAM_HISTORY, __LINE__."  ". __FILE__."  ,ERROR #" .mysqli_error($conn) );
			return false;
		}
	}


}


?>