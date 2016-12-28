<?php

date_default_timezone_set("Asia/Kolkata");
require_once 'DbConn.php';
require_once 'logging_api.php';

class Subjects{
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

}
$obj = new Subjects();
$obj->getSubjectList();

if(isset($_POST['get'])){
	$requestParam = $_POST['get'];
	if(strcasecmp($requestParam,"subject")){

	}
}

?>