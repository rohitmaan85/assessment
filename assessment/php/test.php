<?php
// Remove all files from upload directory.
$uploaddir = './test/';

/*
$fileToDelete = glob($uploaddir."*");
foreach($fileToDelete as $file){
	if(is_file($file))
	unlink($file);
}
*/

$fileName = "newfile.php";
$filePath = $uploaddir.$fileName;

if (file_exists($filePath)) {
	echo "The file ". $filePath." exists";
}
else {
	echo "The file ". $filePath." does not exist";
}

?>