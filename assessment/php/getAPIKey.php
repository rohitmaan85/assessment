<?php

if(isset($_POST['get'])){
  if($_POST['get'] == "key"){
    $key = "N0dXZjNzVmhiUHRjWlJIT1d4c2dDSnhHTVhqcjI0NVc6";
    echo json_encode(array('key' => $key));
  }
}else{
  echo json_encode(array('key' => $key));
}



if(isset($_POST['get'])){
  if($_POST['get'] == "key"){
    $key = "N0dXZjNzVmhiUHRjWlJIT1d4c2dDSnhHTVhqcjI0NVc6";
    echo json_encode($key);
  }
}else{
  echo json_encode(array('key' => $key));
}




?>
