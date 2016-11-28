<?php

include("./php/lib/MPDF_5_7/MPDF57/mpdf.php");
ob_clean();
/*
$mpdf=new mPDF();
$mpdf->WriteHTML($html_content);
$mpdf->Output();
*/

$url = 'http://www.google.com';
if (ini_get('allow_url_fopen')) {
    $html = file_get_contents($url);

    $mpdf=new mPDF('');
    $mpdf->setBasePath($url);
    $mpdf->WriteHTML($html);
    $mpdf->Output();
}
//exit;

// set HTTP response headers
header("Content-Type: application/pdf");
header("Cache-Control: max-age=0");
header("Accept-Ranges: none");
header("Content-Disposition: attachment; filename=\"google_com.pdf\"");

// send the generated PDF
echo $pdf;
?
