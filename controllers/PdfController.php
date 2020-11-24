<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\PDF;

class PdfController extends Controller
{
    public function actionPdf(){
	
    $pdf = new Yii::$app->pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // spl_autoload_register(array('YiiBase','autoload'));
                    
    // set document information
    $pdf->SetCreator(PDF_CREATOR);  
                    
    // $pdf->SetTitle("Selling Report -2013");                
    // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Selling Report -2013", "selling report for Jun- 2013");
    // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    // remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->SetTextColor(80,80,80);
    $pdf->AddPage();
 
    //Write the html
    //Convert the Html to a pdf document
    // $pdf->writeHTML($html, true, false, true, false, '');    
    // $header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)'); //TODO:you can change this Header information according to your need.Also create a Dynamic Header.
    // $pdf->setFontSubsetting(true);
    $pdf->SetFont('dejavusans', '', 5, '', true);

    // // Add a page
    // // This method has several options, check the source code documentation for more information.
    // $pdf->AddPage();

    // set text shadow effect
    // $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
    // Set some content to print
    $html = "<div><p>K.KALAVI Vani</p>";
    $html .= "<p>SBI</p>";
    $html .= "<p>VEDASANDUR</p>";
    $html .= "<p>35032820971</p>";
    $html .= "<p>SBIN0011941</p></div>";
    // Print text using writeHTMLCell()

    $tbl2 = <<<EOD
<table border="none" cellpadding="0" cellspacing="0">
    <tr>
            <td align="center"></td>
            <td align="left" width="145">K.KALAVI Vani</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
    </tr>
    <tr>
            <td align="center"></td>
            <td align="left" width="145">SBI</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="left" width="145">SBI</td>
        <td align="center"></td>
        <td align="left">02.11.20 TO 08.11.20</td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="left" width="145">SBI</td>
        <td align="center" ></td>
        <td align="left">K.KALAVI Vani</td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="left" width="145">SBI</td>
        <td align="center" ></td>
        <td align="left">CHINNA REDDIYAPATTI</td>
        <td align="center"></td>
    </tr>

    <tr>
        <td align="center"></td>
        <td align="left" width="145"></td>
        <td align="center" ></td>
        <td align="left"></td>
        <td align="center"></td>
    </tr>

    <tr>
        <td align="center"></td>
        <td align="left" width="145"></td>
        <td align="center" ></td>
        <td align="left"></td>
        <td align="center"></td>
    </tr>
</table>
EOD;

    // $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, 'right', true);
    $pdf->writeHTML($tbl2, true, false, false, false, '');




    $tbl = <<<EOD
        <table border="none" cellpadding="2" cellspacing="2">
        <tr>
            <td align="center" width="45"> 1/11/2020 </td>
            <td align="center">20.40</td>
            <td align="center">3.7</td>
            <td align="center">7.9</td>
            <td align="center">26.10</td>
            <td align="center">532.44</td>
            <td align="center">13.30</td>
            <td align="center">4.5</td>
            <td align="center">7.7</td>
            <td align="center">25.33</td>
            <td align="center">363.49</td>
            <td align="center">895.93</td>
        </tr>

        <tr>
            <td align="center" width="45"> 1/11/2020 </td>
            <td align="center">20.40</td>
            <td align="center">3.7</td>
            <td align="center">7.9</td>
            <td align="center">26.10</td>
            <td align="center">532.44</td>
            <td align="center">13.30</td>
            <td align="center">4.5</td>
            <td align="center">7.7</td>
            <td align="center">25.33</td>
            <td align="center">363.49</td>
            <td align="center">895.93</td>
        </tr>

        <tr>
            <td align="center" width="45"> 1/11/2020 </td>
            <td align="center">20.40</td>
            <td align="center">3.7</td>
            <td align="center">7.9</td>
            <td align="center">26.10</td>
            <td align="center">532.44</td>
            <td align="center">13.30</td>
            <td align="center">4.5</td>
            <td align="center">7.7</td>
            <td align="center">25.33</td>
            <td align="center">363.49</td>
            <td align="center">895.93</td>
        </tr>

        <tr>
            <td align="center" width="45"> 1/11/2020 </td>
            <td align="center">20.40</td>
            <td align="center">3.7</td>
            <td align="center">7.9</td>
            <td align="center">26.10</td>
            <td align="center">532.44</td>
            <td align="center">13.30</td>
            <td align="center">4.5</td>
            <td align="center">7.7</td>
            <td align="center">25.33</td>
            <td align="center">363.49</td>
            <td align="center">895.93</td>
        </tr>

        <tr>
            <td align="center" width="45"> 1/11/2020 </td>
            <td align="center">20.40</td>
            <td align="center">3.7</td>
            <td align="center">7.9</td>
            <td align="center">26.10</td>
            <td align="center">532.44</td>
            <td align="center">13.30</td>
            <td align="center">4.5</td>
            <td align="center">7.7</td>
            <td align="center">25.33</td>
            <td align="center">363.49</td>
            <td align="center">895.93</td>
        </tr>

        <tr>
            <td align="center" width="45"> 1/11/2020 </td>
            <td align="center">20.40</td>
            <td align="center">3.7</td>
            <td align="center">7.9</td>
            <td align="center">26.10</td>
            <td align="center">532.44</td>
            <td align="center">13.30</td>
            <td align="center">4.5</td>
            <td align="center">7.7</td>
            <td align="center">25.33</td>
            <td align="center">363.49</td>
            <td align="center">895.93</td>
        </tr>
    </table>
EOD;
    $pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = <<<EOD
    <table border="none" cellpadding="2" cellspacing="2">
        <tr>
                <td align="center" width="45"> </td>
                <td align="center">20.40</td>
                <td align="center">3.7</td>
                <td align="center">7.9</td>
                <td align="center">26.10</td>
                <td align="center">532.44</td>
                <td align="center">13.30</td>
                <td align="center">4.5</td>
                <td align="center">7.7</td>
                <td align="center">25.33</td>
                <td align="center">363.49</td>
                <td align="center">895.93</td>
        </tr>
</table>
EOD;

    // $pdf->writeHTMLCell( 50, 0, 0, 0, $tbl, 0, 0, false, true, '', true );
    $pdf->writeHTML($tbl, true, false, false, false, '');

    // ---------------------------------------------------------

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    // var_dump($pdf);
    $pdf->Output('yii2_tcpdf_example2.pdf', 'I');
    
    //Close and output PDF document
    Yii::$app->end();
    
}
}
?>