<?php
date_default_timezone_set("Asia/Kolkata");
include('manageExams.php');
class EncryptQuestionPaperClass{

	# --- ENCRYPTION ---

	# the key should be random binary, use scrypt, bcrypt or PBKDF2 to
	# convert a string into a key
	# key is specified using hexadecimal

	public $keyString = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
	var $key = "";
	var $iv_size = "";
	var $jsonFormat = "";



	function encryptQuestionPaper($questionPaperJSONString){
		$this->key = pack('H*', $this->keyString);

		# show key size use either 16, 24 or 32 byte keys for AES-128, 192
		# and 256 respectively
		$key_size =  strlen($key);

		# create a random IV to use with CBC encoding
		$this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
		# creates a cipher text compatible with AES (Rijndael block size = 128)
		# to keep the text confidential
		# only suitable for encoded input that never ends with value 00h
		# (because of default zero padding)
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key,$questionPaperJSONString, MCRYPT_MODE_CBC, $iv);
		# prepend the IV for it to be available for decryption
		$ciphertext = $iv . $ciphertext;
		# encode the resulting cipher text so it can be represented by a string
		$ciphertext_base64 = base64_encode($ciphertext);

		//echo  $ciphertext_base64 . "\n";
		return $ciphertext_base64;
	}


	function getEncryptedQuestionPaper($examId, $examName){

		$manageExamsObj = new manageExams();
		$questionPaperJSONString  = $manageExamsObj->getExamQuestionsInJSONString($examId, $examName);
		log_event( LOG_ENCRYPTION, __LINE__."  ". __FILE__.", JSON String for Exam Encryption '".$questionPaperJSONString."'" );
		//echo json_encode($questionPaperJSONString);
		$this->jsonFormat = json_encode($questionPaperJSONString);
		return $this->encryptQuestionPaper(json_encode($questionPaperJSONString));

	}


	function addHistoryInDB($file_name, $file_md5, $remote_ip, $downloaded_at, $exam_name, $json_format){
		$manageExamsObj = new manageExams();
		$manageExamsObj->buildInsertDownloadEncryptExamSql($file_name, $file_md5, $remote_ip, $downloaded_at, $exam_name, $this->jsonFormat);
	}



	// -------------------- Decryption Start here ----------------- //

	function decryptQuestionPaper($encryptedQuestionPaper)
	{
		# --- DECRYPTION ---

		$this->key = pack('H*', $this->keyString);

		# show key size use either 16, 24 or 32 byte keys for AES-128, 192
		# and 256 respectively
		$key_size =  strlen($key);

		# create a random IV to use with CBC encoding
		$this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);


		$ciphertext_dec = base64_decode($encryptedQuestionPaper);

		# retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
		$iv_dec = substr($ciphertext_dec, 0, $this->iv_size);

		# retrieves the cipher text (everything except the $iv_size in the front)
		$ciphertext_dec = substr($ciphertext_dec, $this->iv_size);

		# may remove 00h valued characters from end of plain text
		$plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
		return $plaintext_dec;

		//echo  $plaintext_dec . "\n";
	}

	function getExamInJSONFormatFromEncryptFile($encrypt_file_name){
		$encryptData = file_get_contents ($encrypt_file_name);
		return $this->getExamInJSONFormatFromEncryptData($encryptData);
	}

	function  getExamInJSONFormatFromEncryptData($encryptedExam){
		$examInJSONString =  $this->decryptQuestionPaper($encryptedExam);
		//echo "Decrypt Data = " . $questionPaperJSONString;
		return $examInJSONString;
	}

	function getExamDivFromJSONFile($fileName)
	{
		$questionDiv="";
		$global_qstn_counter=  0;
		$data = file_get_contents ($fileName);
		//	$data = file_get_contents ('cob_details.json');

		$json_data = json_decode($data, true);
		return $this->getExamDivsFromJSONData($json_data);
		/*
		 foreach ($json as $key => $value) {
			foreach ($value as $key => $val) {

			$qstn="";
			$optiona="";
			$optionb="";
			$optionc="";
			$optiond="";

			//echo $key;
			//echo $key . '=>' . $val . '<br/>';
			foreach ($val as $key1 => $value1) {
			//echo $key1 . '=>' . $value1 . '';
			if($key1=="question")
			$qstn = $value1;
			if($key1=="optiona")
			$optiona = $value1;
			if($key1=="optionb")
			$optionb = $value1;
			if($key1=="optionc")
			$optionc = $value1;
			if($key1=="optiond")
			$optiond = $value1;
			//}
			}

			$divBackGround = "";
			if($key% 2 == 0){
			$divBackGround="evenQstn";
			}else{
			$divBackGround="oddQstn";
			}

			$questionDiv.= ' <div class="form-group"><div id="'.$divBackGround.'"><div id="'.$question_id.'" class="col-xs-14">
			<p id="qstn_p">'.$key.'. '.$qstn.'</p>
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
			}
			}
			return $questionDiv;
			*/
		//echo $questionDiv;
	}

	function getExamDivsFromJSONData($exam_in_json_format)
	{
		$questionDiv="";
		$json = json_decode($exam_in_json_format, true);

		// Print Exam Details
		echo "------------------------------------------------Exam Details ------------------------------------------------";
		echo "\n";
		foreach($json['Exam_Details'] as $key => $exam_detail) {
			echo $key. "  ==> " .$exam_detail ." , ";
			echo "\n";
		}

		echo "\n";

		echo "------------------------------------------------Batch Details ------------------------------------------------";
		echo "\n";
		// Print batch details :
		foreach($json['Batch_Details'] as $key =>$batch) {
			echo $key. "  ==> " .$batch . " ,";
			echo "\n";
		}


		echo "\n";
		echo "------------------------------------------------Student Details ------------------------------------------------";
		echo "\n";
		// Print batch details :
		foreach($json['Student_Details'] as $key =>$student) {
			foreach($student as $key1 =>$student_detail) {
				echo $key1. "  ==> " .$student_detail;
				echo " , ";
			}
			echo "\n";
		}

		echo "------------------------------------------------Questions Details ------------------------------------------------";
		echo "\n";
		// Print Questions details :
		foreach($json['Exam_Questions'] as $category_details ){
			//echo $student . " ,";
			foreach($category_details as $key=>$category) {
				//echo $categry."     ,     ";
				if($key == "Questions"){
					foreach($category as $sno=>$qstn_details ){
						$qstn="";
						$optiona="";
						$optionb="";
						$optionc="";
						$optiond="";
						/// echo $qstn_details;
						foreach($qstn_details as $key=>$value ){
							echo $key. "  ==> " .$value." \n";
							// Print in Divs
							if($key=="question")
							$qstn = $value;
							if($key=="optiona")
							$optiona = $value;
							if($key=="optionb")
							$optionb = $value;
							if($key=="optionc")
							$optionc = $value;
							if($key=="optiond")
							$optiond = $value;
					 }

					 $divBackGround = "";
					 if($key% 2 == 0){
					 	$divBackGround="evenQstn";
					 }else{
					 	$divBackGround="oddQstn";
					 }

					 $questionDiv.= ' <div class="form-group"><div id="'.$divBackGround.'"><div id="'.$question_id.'" class="col-xs-14">
									<p id="qstn_p">'.$key.'. '.$qstn.'</p>
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
					 echo "-----------------------------------------";
					 echo "\n";
					}
				}
			}
		}
	 return $questionDiv;

	 /*
	  foreach ($value as $key => $val) {

			$qstn="";
			$optiona="";
			$optionb="";
			$optionc="";
			$optiond="";

			//echo $key;
			//echo $key . '=>' . $val . '<br/>';
			foreach ($val as $key1 => $value1) {
			//echo $key1 . '=>' . $value1 . '';
			if($key1=="question")
			$qstn = $value1;
			if($key1=="optiona")
			$optiona = $value1;
			if($key1=="optionb")
			$optionb = $value1;
			if($key1=="optionc")
			$optionc = $value1;
			if($key1=="optiond")
			$optiond = $value1;
			//}
			}

			$divBackGround = "";
			if($key% 2 == 0){
			$divBackGround="evenQstn";
			}else{
			$divBackGround="oddQstn";
			}

			$questionDiv.= ' <div class="form-group"><div id="'.$divBackGround.'"><div id="'.$question_id.'" class="col-xs-14">
			<p id="qstn_p">'.$key.'. '.$qstn.'</p>
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
			}

			return $questionDiv;
			//echo $questionDiv;*/
	}
}


/*

$manageExamsObj = new manageExams();
//echo json_encode($manageExamsObj->getExamQuestionsInJSONString(1,""));

$obj = new EncryptQuestionPaperClass();
$encryptText = $obj->getEncryptedQuestionPaper(1,"");
//echo $encryptText."\n";

$exam_in_json_format = $obj->getExamInJSONFormatFromEncryptData($encryptText);
//Remove junk characters from the end from JSON data
$pos = strripos($exam_in_json_format, '}'); // $pos = 7, not 0
$exam_in_json_format=substr($exam_in_json_format,0,$pos+1);

echo $exam_in_json_format;

// Get Exam divs from JSON data to print on UI.
$obj->getExamDivsFromJSONData($exam_in_json_format);

*/





//$obj = new EncryptQuestionPaperClass();
//$obj->decodeJSONFile('cob_details.json');

//$encryptText = $obj->getEncryptedQuestionPaper("","rohit_123");
//echo $encryptText."\n";

/*
 $handle = fopen("EncryptQstnPaper.enq", "w");
 fwrite($handle, $encryptText);
 fclose($handle);
 */

/*
 $decryptText = $obj->decryptQuestionPaper($encryptText);
 echo $decryptText;
 */

?>