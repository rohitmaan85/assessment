<?php

require_once 'DbConn.php';
require_once 'logging_api.php';

class manageAttendence{


	function getAllAttendenceList()
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`batch_students`";
		log_event( MANAGE_ATTENDENCE, __LINE__."  ". __FILE__."  , SQL to get Attendence List : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$jsonArr[0] =$row['batch_id'];
			$jsonArr[1]=$row['job_role'];
			$jsonArr[2]=$row['name'];
			$jsonArr[3]=$row['enrollment_id'];
			$jsonArr[4]=$row['father_n_husband_name'];
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);

	}

	function getBatchDetailsList($subId, $batch_id,$center_id,$training_partner,$status)
	{
		$conn = DbConn::getDbConn();

		$sql="SELECT * FROM `assessment`.`batch_details`";
		if($subId!="")
		$sql.= " where job_role='".htmlspecialchars($subId,ENT_QUOTES)."'";
		if($batch_id!="")
		$sql.= " and batch_id='".htmlspecialchars($batch_id,ENT_QUOTES)."'";
		if($center_id!="")
		$sql.= " and center_id_n_location='".htmlspecialchars($center_id,ENT_QUOTES)."'";
		if($training_partner!="")
		$sql.= " and training_partner_name='".htmlspecialchars($training_partner,ENT_QUOTES)."'";
		if($status!="")
		$sql.= " and status='".htmlspecialchars($status,ENT_QUOTES)."'";

		log_event( MANAGE_ATTENDENCE, __LINE__."  ". __FILE__."  , SQL to get Batch Details List : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$jsonArr[0] =$row['batch_id'];
			$jsonArr[1]=$row['job_role'];
			$jsonArr[2]=$row['exam_date'];
			//$jsonArr[3]=$row['center_id_n_location'];
			$jsonArr[3]="";
			//$jsonArr[5]=$row['training_partner_name'];
			//$jsonArr[6]=$row['no_of_students_schedule'];
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}

	function getJobRoleFromStudentDetails($batch_id)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT job_role FROM `assessment`.`batch_students`";
		if($subjectId!="")
		$sql.= " where batch_id='".htmlspecialchars($subjectId,ENT_QUOTES)."'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get jobRole from student table : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		$job_role = "";
		while ($row)
		{
			$categoryId = $row['job_role'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		// Free result set
		mysqli_free_result($result);
		return $job_role;
	}

	function getStudentList($batch_id)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`batch_students`";
		if($batch_id!="")
		$sql.= " where batch_id='".htmlspecialchars($batch_id,ENT_QUOTES)."'";
		log_event(MANAGE_ATTENDENCE, __LINE__."  ". __FILE__."  , SQL to get Student List : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$jsonArr[0]=$row['name'];
			$jsonArr[1]=$row['enrollment_id'];
			$jsonArr[2]=$row['father_n_husband_name'];
			$jsonArr1[] =$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		// print json Data.
		echo json_encode($final_array);
	}
	
	
	function getBatchInformationForDialogBox($batch_id)
	{
		$conn = DbConn::getDbConn();

		$sql="SELECT * FROM `assessment`.`batch_details`";
		if($batch_id!="")
			$sql.= " where batch_id='".htmlspecialchars($batch_id,ENT_QUOTES)."'";
		
		log_event( MANAGE_ATTENDENCE, __LINE__."  ". __FILE__."  , SQL to get Batch Information : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$jsonArr[0] =$row['batch_id'];
			$jsonArr[1]=$row['job_role'];
			$jsonArr[2]=$row['exam_date'];
			$jsonArr[3]=$row['center_id_n_location'];
			$jsonArr[4]=$row['training_partner_name'];
			$jsonArr[5]=$row['no_of_students_schedule'];
			$jsonArr[6]=$row['status'];
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

// Handle Requests from UI
$obj = new manageAttendence();

if(isset($_POST['action'])){
	log_event( LOG_DATABASE, " Get Request with action parameter  :'".$_POST['action']."'" );
	// Get defect details to edit question.
	if($_POST['action']=="getAllAttendence"){
		log_event( MANAGE_ATTENDENCE, __LINE__."  ". __FILE__."  Get All attendence ." );
		$obj->getAllAttendenceList();
	}
	else if($_POST['action']=="getBatchDetails"){
		log_event( MANAGE_ATTENDENCE, " Get Request to get batch details." );
		if(isset($_POST['subId'])){
			$batch_id = $_POST['batch_id'];
			$center_id = $_POST['center_id'];
			$training_partner	 = $_POST['training_part'];
			$status = $_POST['status'];
			$obj->getBatchDetailsList($subId, $batch_id,$center_id,$training_partner,$status);
		}
	}
	else if($_POST['action']=="getStudentList"){
		log_event( MANAGE_ATTENDENCE, "Get Request to get Student List." );
		if(isset($_POST['batch_id'])){
			$batch_id = $_POST['batch_id'];
			$obj->getStudentList($batch_id);
		}
	}
	else if($_POST['action']=="getBatchInfo"){
		log_event( MANAGE_ATTENDENCE, "Get Request to get Student List." );
		if(isset($_POST['batch_id'])){
			$batch_id = $_POST['batch_id'];
			$obj->getBatchInformationForDialogBox($batch_id);
		}
	}
}

?>
