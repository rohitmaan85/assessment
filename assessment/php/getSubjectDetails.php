<?php

require_once 'DbConn.php';
require_once 'logging_api.php';

class getSubjectDetails{
	function getSubjectList()
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`jobroles_excel_import`";
		//$result = $mysqli->query($query);
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		while ($row)
		{
			$jsonArr[0]=$row['s.no'];
			$jsonArr[1]=$row['ssc'];
			$jsonArr[2]=$row['job_role'];
			$jsonArr[3]=$row['qp_code'];
			$jsonArr[4]=$row['nsqf_level'];
			$jsonArr[5]=$row['theory'];
			$jsonArr[6]=$row['practical'];
			$jsonArr[7]=$row['add_dur_entr_n_sftskill'];
			$jsonArr[8]=$row['add_dur_digital_literacy'];
			$jsonArr[9]=$row['training_duration'];
			$jsonArr[10]=$row['curriculum_available'];
			$jsonArr[11]=$row['content_available'];
			$jsonArr[12]=$row['common_norms_category'];
			$jsonArr[13]=$row['classfication'];

			$jsonArr1[]=$jsonArr;
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		$final_array = array('data' => $jsonArr1);
		// Free result set
		mysqli_free_result($result);
		echo json_encode($final_array);
	}

	function getSSCList()
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT ssc FROM `assessment`.`jobroles_excel_import`";
		log_event( LOG_DATABASE,"SQL = '".$sql."'" );
		//$result = $mysqli->query($query);
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		while ($row)
		{
			$jsonArr[]=$row['ssc'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		//$final_array = array('ssc' => array_unique($jsonArr));
		//$final_array = array($jsonArr);
		// Free result set
		mysqli_free_result($result);
		echo json_encode(array_unique($jsonArr));
	}

function getJobRoleList($ssc)
	{
		$conn = DbConn::getDbConn();
		$sql="SELECT job_role FROM `assessment`.`jobroles_excel_import`";
		if($ssc!=""){
			$sql.= " where ssc='".$ssc."'";
		}
		log_event( LOG_DATABASE,"SQL = '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		while ($row)
		{
			$jsonArr[]=$row['job_role'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		 //$final_array = array('job_role' => array_unique($jsonArr));
		// Free result set
		mysqli_free_result($result);
		echo json_encode(array_unique($jsonArr));
	}

function getQPCode($job_role)
	{
		$qpCode = "";
		$conn = DbConn::getDbConn();
		$sql="SELECT qp_code FROM `assessment`.`jobroles_excel_import` where job_role='".$job_role."'";
		log_event( LOG_DATABASE,"SQL = '".$sql."'" );

		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		while ($row)
		{
			$qpCode=$row['qp_code'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		}
		 //$final_array = array('job_role' => array_unique($jsonArr));
		// Free result set
		mysqli_free_result($result);
		echo json_encode(array('qpcode' => $qpCode));
	}

}
$obj = new getSubjectDetails();
//$obj->getJobRoleList("add");;
//$obj->getSSCList();
if(isset($_GET['get'])){
	$requestParam = $_GET['get'];
	log_event( LOG_DATABASE, "Get Request with parameter :'".$_GET['get']."'" );

	if($requestParam == "ssc"){
		log_event( LOG_DATABASE, "Get SSC List from database." );
		$obj->getSSCList();
	}

	if($requestParam =="jobrole" && isset($_GET['ssc'])){
		log_event( LOG_DATABASE, "Get Job Roles List with parameter :'".$_GET['get']."' for SSC :'".$_GET['ssc']."'" );
		$obj->getJobRoleList($_GET['ssc']);
	}

	if($requestParam =="qpcode" && isset($_GET['jobrole'])){
		log_event( LOG_DATABASE, "Get QP Code with parameter :'".$_GET['get']."' for Job Role :'".$_GET['jobrole']."'" );
		$obj->getQPCode($_GET['jobrole']);
	}
}

?>
