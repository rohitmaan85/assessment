<?php

include('manageExams.php');
class EncryptQuestionPaperClass{

	# --- ENCRYPTION ---

	# the key should be random binary, use scrypt, bcrypt or PBKDF2 to
	# convert a string into a key
	# key is specified using hexadecimal

	public $keyString = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
	var $key = "";
	var $iv_size = "";
	
	

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
		$questionPaperJSONString  = $manageExamsObj->getExamQuestions($examId, $examName);
		echo json_encode($questionPaperJSONString);
		return $this->encryptQuestionPaper(json_encode($questionPaperJSONString));

	}



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

	function decodeExamsFromFile($fileName){
		$encryptData = file_get_contents ($fileName);
		return $this->getDecryptedQuestionPaper($encryptData);
	}

	function getDecryptedQuestionPaper($encryptedQuestionPaper){
		$questionPaperJSONString =  $this->decryptQuestionPaper($encryptedQuestionPaper);
		//echo "Decrypt Data = " . $questionPaperJSONString;	
		return $questionPaperJSONString;
	}


	function decodeJSONFile($fileName)
	{
		$questionDiv="";
		$global_qstn_counter=  0;
		$data = file_get_contents ($fileName);
	//	$data = file_get_contents ('cob_details.json');
	
		$json = json_decode($data, true);
		foreach ($json as $key => $value) {
			//echo $key
			/*if (!is_array($value)) {
				echo $key . '=>' . $value . '<br/>';
				foreach ($value as $key1 => $value1) {
				echo $key1 . '=>' . $value1 . '<br/>';
				}
				} else {*/
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
		//echo $questionDiv;
	}
	
function getExamDivsFromJSON($data)
	{
		$questionDiv="";
		//$global_qstn_counter=  0;
		//$data = file_get_contents ($fileName);
	//	$data = file_get_contents ('cob_details.json');

		//echo $data;
	
		$json = json_decode($data, true);
		foreach ($json as $key => $value) {
			//echo $key
			/*if (!is_array($value)) {
				echo $key . '=>' . $value . '<br/>';
				foreach ($value as $key1 => $value1) {
				echo $key1 . '=>' . $value1 . '<br/>';
				}
				} else {*/
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
		//echo $questionDiv;
	}
}

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