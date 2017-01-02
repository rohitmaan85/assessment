<?php

date_default_timezone_set("Asia/Kolkata");
require_once 'DbConn.php';
require_once 'logging_api.php';
//error_reporting(E_ALL);


class AuthenticateUser {

	function verifyUser($login, $password)
	{

		$conn = DbConn::getDbConn();
		$sql="SELECT * FROM `assessment`.`user` Where username='".$login."' and password='".$password."' and status='active'";
		log_event( LOG_DATABASE, __LINE__."  ". __FILE__."  , SQL to get Batch List : '".$sql."'" );
		$result = mysqli_query($conn,$sql);
		$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=0;
		while ($row)
		{
			$i++;
			$jsonArr["id"]=$row['id'];
			$jsonArr["name"]=$row['name'];
			$jsonArr["admin"]=$row['role'];
			$row=mysqli_fetch_array($result, MYSQLI_ASSOC);
			 break;
		}
		
		if($i!=0){
			$row_value.= "'".$jsonArr["id"]."','success','".date("Y-m-d H:i:s", time())."'";
			$sql = "INSERT INTO `assessment`.`login_history`(`user_id`,`status`,`login_on`) VALUES(".$row_value.");";
			$this->executeUpdateQuery($sql);
		}else{
			log_event(USER_AUTHENTICATION,"No Row Found" );
		}
		mysqli_free_result($result);
		return $jsonArr;
	}

	function executeUpdateQuery($sql)
	{
		$conn = DbConn::getDbConn();
		if (mysqli_query($conn, $sql)) {
			if(mysqli_affected_rows($conn)>0){
				log_event(USER_AUTHENTICATION, __LINE__."  ". __FILE__."  , Success !  SQL executed '".mysqli_affected_rows($con)."'" );
				return true;
			}else{
				log_event(USER_AUTHENTICATION, __LINE__."  ". __FILE__."  , ERROR #! while executing query. " );
				return false;
			}
		} else {
			log_event( MANAGE_TEST, __LINE__."  ". __FILE__."  , ERROR #! while executing query. " );
			return false;
		}
	}

}

session_start();
ob_start();
$action=$_REQUEST['action'];
$login=$_REQUEST['login'];
$pwd=$_REQUEST['pwd'];

$pwd = urldecode($pwd);
$pwd = utf8_decode($pwd);
$obj = new AuthenticateUser();
//$obj->verifyUser("admin","admin");

if ($action=="login")
{
	log_event(USER_AUTHENTICATION, __LINE__."  ". __FILE__.", Get Request to authenticate user '".$login."' and password = '".$pwd."'" );
	$user_details = $obj->verifyUser($login, $pwd);
	if ($user_details["name"] != "")
	{
		$_SESSION['userName'] = $user_details["name"];
			
		$final_array = array('data' => $user_details);

	}else{
		log_event(USER_AUTHENTICATION, __LINE__."  ". __FILE__.", Invalid username and password" );
		$final_array = array('error' => "Invalid username and password.");
	}

	echo json_encode($final_array);
}


?>