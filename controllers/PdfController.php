<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\PDF;

class PdfController extends Controller
{
    public function actionPdf(){
        // PDF_PAGE_FORMAT
    $width = 297;  
    $height = 297; 
    // $pageLayout = array(250,345);
    $pageLayout = array(240,335);
    $pdf = new Yii::$app->pdf('P', PDF_UNIT, $pageLayout, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(False, 0);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTextColor(80,80,80);
    $pdf->SetMargins(0, 37, 0);
    for($i=0;$i<2;$i++){
    if($i%2 == 0){
        $pdf->AddPage();
        // $tbl_padding = '<table border="none" cellpadding="1" cellspacing="1">';
        // $tbl_padding .='<tr><td height="63"></td></tr>';
        // $tbl_padding .= '</table>';
        // $pdf->writeHTML($tbl_padding, true, false, false, false, '');
    }
    else{
        $tbl_padding = '<table border="none" cellpadding="1" cellspacing="1">';
        $tbl_padding .='<tr><td height="150"></td></tr>';
        $tbl_padding .= '</table>';
        $pdf->writeHTML($tbl_padding, true, false, false, false, '');
    }
        // $pdf->SetAutoPageBreak(TRUE, 0);
        // if($i%2 != 0){
        // }
        // else{
        //
        // }
    $pdf->SetFont('dejavusans', '', 9, '', true);
    $tbl2 = '
<table border="none" cellpadding="1" cellspacing="1">
    <tr>
            <td align="center" width="60"></td>
            <td align="left" width="370">K.KALAVI Vani</td>
            <td align="center"></td>
            <td align="center"></td>
    </tr>
    <tr>
            <td align="center" width="60"></td>
            <td align="left" width="370">SBI</td>
            <td align="center"></td>
            <td align="center"></td>
    </tr>
    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370">SBI</td>
        <td align="left">02.11.20 TO 08.11.20</td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370">SBI</td>
        <td align="left">K.KALAVI Vani</td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370">SBI</td>
        <td align="left">CHINNA REDDIYAPATTI</td>
        <td align="center"></td>
    </tr>

    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370"></td>
        <td align="left"></td>
        <td align="center"></td>
    </tr>

    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370"></td>
        <td align="left"></td>
        <td align="center"></td>
    </tr>
</table>';

    // $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'right', true);
    $pdf->writeHTML($tbl2, true, false, false, false, '');
    // $pdf->writeHTMLCell( 50, 500, 0, 0, $tbl2, 0, 0, false, true, '', false);


    $pdf->SetFont('dejavusans', '', 8, '', true);
    $table = '<table border="none"  width="100%" cellpadding="1" cellspacing="1">';
        for($j = 0 ; $j<15; $j++){
            $table .='<tr>
                        <td align="left" width="65">1/11/2020</td>
                        <td align="center" width="50">20.40</td>
                        <td align="center" width="40">3.7</td>
                        <td align="center">7.9</td>
                        <td align="left">26.10</td>
                        <td align="center">532.44</td>
                        <td align="center" width="70">13.30</td>
                        <td align="center" width="40">4.5</td>
                        <td align="center" width="40">7.7</td>
                        <td align="center">25.33</td>
                        <td align="center" width="60">363.49</td>
                        <td align="center">895.93</td>
                    </tr>';
        }
    $table .= '</table>';
    $pdf->writeHTML($table, true, false, false, false, '');

$tbl = '
    <table border="none" cellpadding="1" cellspacing="1">
        <tr>
            <td align="left" width="65"></td>
            <td align="center" width="50">20.40</td>
            <td align="center" width="40">3.7</td>
            <td align="center">7.9</td>
            <td align="left">26.10</td>
            <td align="center">532.44</td>
            <td align="center" width="70">13.30</td>
            <td align="center" width="40">4.5</td>
            <td align="center" width="40">7.7</td>
            <td align="center">25.33</td>
            <td align="center" width="60">363.49</td>
            <td align="center">895.93</td>
        </tr>
    </table>';

    // $pdf->writeHTMLCell( 50, 0, 0, 0, $tbl, 0, 0, false, true, '', true );
    $pdf->writeHTML($tbl, true, false, false, false, '');

    // ---------------------------------------------------------

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    // var_dump($pdf);
    
    }
    $pdf->Output('yii2_tcpdf_example2.pdf', 'I');
    Yii::$app->end();
}

    public function actionPdf2(){
        // PDF_PAGE_FORMAT
        $width = 297;  
        $height = 297; 
        $pageLayout = array(210,305);
        $pdf = new Yii::$app->pdf('P', PDF_UNIT, $pageLayout, true, 'UTF-8', false);


    }
}
?>