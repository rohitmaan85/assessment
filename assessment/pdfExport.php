<?php
//ini_set('error_reporting', E_ALL);
include("./php/lib/MPDF_5_7/MPDF57/mpdf.php");

$html_content='<div id="36" class="col-xs-14">
				<p id="qstn_p">1. qstn1</p>
				<div><br></div>
				<div id="optiona"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				a</p></div>
				<div><br></div>
				<div id="optionb"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				b</p>
				</div>
				<div><br></div>
				<div id="optionc"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				c</p>
				</div>
				<div><br></div>
				<div id="optiond"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				d</p>
				</div>
				<div><br></div>
			 </div>
			<div id="examQuestions" class="col-xs-14"><hr></div><div id="44" class="col-xs-14">
				<p id="qstn_p">1. qstn1</p>
				<div><br></div>
				<div id="optiona"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				a</p></div>
				<div><br></div>
				<div id="optionb"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				b</p>
				</div>
				<div><br></div>
				<div id="optionc"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				c</p>
				</div>
				<div><br></div>
				<div id="optiond"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				d</p>
				</div>
				<div><br></div>
			 </div>
			<div id="examQuestions" class="col-xs-14"><hr></div><div id="48" class="col-xs-14">
				<p id="qstn_p">1. qstn1</p>
				<div><br></div>
				<div id="optiona"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				a</p></div>
				<div><br></div>
				<div id="optionb"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				b</p>
				</div>
				<div><br></div>
				<div id="optionc"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				c</p>
				</div>
				<div><br></div>
				<div id="optiond"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				d</p>
				</div>
				<div><br></div>
			 </div>
			<div id="examQuestions" class="col-xs-14"><hr></div><div id="4" class="col-xs-14">
				<p id="qstn_p">1. qstn1</p>
				<div><br></div>
				<div id="optiona"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				a</p></div>
				<div><br></div>
				<div id="optionb"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				b</p>
				</div>
				<div><br></div>
				<div id="optionc"><p id="qstn_p"> <input id="qstn_checkbox" type="checkbox">
				c</p>
				</div>
				<div><br></div>
				<div id="optiond"><p id="qstn_p"><input id="qstn_checkbox" type="checkbox">
				d</p>
				</div>
				<div><br></div>
			 </div>
			<div id="examQuestions" class="col-xs-14"><hr></div>';
$mpdf=new mPDF();
$mpdf->WriteHTML($html_content);
$mpdf->Output();


/*
$url = 'http://localhost/assessment/login.php';
ini_set("allow_url_fopen", 1);
if (ini_get("allow_url_fopen") == 1) {
echo "allow_url_fopen is ON";
} else {
echo "allow_url_fopen is OFF";
}

if (ini_get('allow_url_fopen')) {
   //sleep(10);
    $html = file_get_contents($url);

    $mpdf=new mPDF('');
    echo $html;


    $mpdf=new mPDF('');
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->setBasePath($url);
    $mpdf->WriteHTML($html);
    $mpdf->Output();

}
*/
exit;


//exit;

/*
// set HTTP response headers
header("Content-Type: application/pdf");
header("Cache-Control: max-age=0");
header("Accept-Ranges: none");
header("Content-Disposition: attachment; filename=\"google_com.pdf\"");
*/
// send the generated PDF
//echo $pdf;
?>
