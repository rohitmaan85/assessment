<?php
$url	=	"";
$redirect = false;
if(empty($_SESSION['userName'])){
	if($requestedURL==''){
		$url	=	$indexPageURl;

	}else{
		$redirectToURL	=	$indexPageURl.'?ref='.$requestedURL;
		$url	=	$redirectToURL;
		$redirect = true;
	}
}

if($redirect){
?>
<script>window.location.href = '<?php echo $url ;?>';</script>
<?php } ?>