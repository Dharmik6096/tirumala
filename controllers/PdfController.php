<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\PDF;
use yii\helpers\ArrayHelper;

class PdfController extends Controller
{
    public function actionPdf(){
    $parameters = $_GET['param'];
    // var_dump($param);die;
    $sp_name = 'sp_rpt_bill_format1';
    $param = [];
    $param[] = $parameters['p_union_code']; //union 
    $param[] = $parameters['p_plant_code']; //union
    $param[] = $parameters['p_mcc_code']; //
    $param[] = $parameters['p_bmc_code'];
    $param[] = $parameters['p_payment_cycle_code'];
    $output = \Yii::$app->general->getSpData($sp_name,$param);
    $bill_master = [];
    $bill_transaction = [];
    $bill_transaction_am = [];
    $bill_transaction_pm = [];
    $c=0;
    $array=[];
    foreach ($output as $key => $value) {
        $array[$value['member_code']] = [];
        $array[$value['member_code']]['basic'] = [];
        $bill_detail = [];
        $bill_detail['payment_cycle'] = $value['payment_cycle'];
        $bill_detail['bank_name'] = $value['bank_name'];
        $bill_detail['branch_name'] = $value['branch_name']; 
        $bill_detail['bank_account_no'] = $value['bank_account_no'];
        $bill_detail['ifsc'] = $value['ifsc'];
        $bill_detail['member_name'] = $value['member_name'];
        $bill_detail['bmc_name'] = $value['bmc_name'];
        $bill_detail['dcs_name'] = $value['dcs_name'];

        if(empty($main[$value['member_code']]['basic'])){
            array_push($array[$value['member_code']]['basic'],$bill_detail);
        }
        else{
            foreach ($array[$value['member_code']]['basic'] as $key => $master) {
                if($master['payment_cycle'] != $bill_detail['payment_cycle']){
                    array_push($array[$value['member_code']]['basic'],$bill_detail);
                }
            }
        }
        
        
            $bill_transaction['collection_date'] = $value['collection_date'];
            $bill_transaction['bm_qty'] = $value['bm_qty'];
            $bill_transaction['bm_avgFAT'] = $value['bm_avgFAT'];
            $bill_transaction['bm_avgSNF'] = $value['bm_avgSNF'];
            $bill_transaction['rate'] = $value['rate'];
            $bill_transaction['bm_amount'] = $value['bm_amount'];
            $bill_transaction['shift'] = $value['shift'];
            $bill_transaction['member_code'] = $value['member_code'];
            if($value['shift'] == 'AM'){
                array_push($bill_transaction_am,$bill_transaction);
            }
            if($value['shift'] == 'PM'){
                array_push($bill_transaction_pm,$bill_transaction);
            }
            
            $array[$value['member_code']]['details'][$value['collection_date']]['am'] = [];
            $array[$value['member_code']]['details'][$value['collection_date']]['pm'] = [];
            // if(empty($main[$value['member_code']]['details'][$value['collection_date']])){
            //     $array[$value['member_code']]['details'][$value['collection_date']][$value['shift']] = [];
            //     array_push($array[$value['member_code']]['details'][$value['collection_date']][$value['shift']],$bill_transaction);
            // }
            // else{
            //     foreach ($array[$value['member_code']]['details'] as $key => $master) {
            //             if($key == $value['collection_date']){
            //                 $array[$value['member_code']]['details'][$value['collection_date']][$value['shift']] = [];
            //                 array_push($array[$value['member_code']]['details'][$value['collection_date']][$value['shift']],$bill_transaction);
            //         }
            //     }
            // }

        // if($value['shift'] == 'PM'){
        //     $bill_transaction['collection_date'] = $value['collection_date'];
        //     $bill_transaction['bm_qty'] = $value['bm_qty'];
        //     $bill_transaction['bm_avgFAT'] = $value['bm_avgFAT'];
        //     $bill_transaction['bm_avgSNF'] = $value['bm_avgSNF'];
        //     $bill_transaction['rate'] = $value['rate'];
        //     $bill_transaction['bm_amount'] = $value['bm_amount'];
        //     $bill_transaction['shift'] = $value['shift'];
        //     array_push($array[$value['member_code']]['details'][$value['collection_date']]['pm'],$bill_transaction);
        // }
        // array_push($main,$array);
        // $bill_master[$value['payment_cycle']] = [];
    
    }
    // $array = array_merge_recursive($bill_transaction_pm, $bill_transaction_am);

    // var_dump($bill_transaction_pm);
    // var_dump($bill_transaction_am);
    // var_dump($array);
    foreach ($array as $key => $value) {
        $member_code = $key;
        foreach ($bill_transaction_pm as $key => $value) {
            if($value['member_code'] == $member_code){
                array_push($array[$member_code]['details'][$value['collection_date']]['pm'],$value);
            }
        }
        foreach ($bill_transaction_am as $key => $value) {
            // var_dump($value);
            if($value['member_code'] == $member_code){
                array_push($array[$member_code]['details'][$value['collection_date']]['am'],$value);
            }
        }
    }
    // print_r(count($array));
    // die;
    if(!empty($array))
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
    // for($i=0;$i<count($array);$i++){
    $i=0;
    foreach ($array as $key => $value) {
    if($i%2 == 0){
        $pdf->AddPage();
    }
    else{
        $tbl_padding = '<table border="none" cellpadding="1" cellspacing="1">';
        $tbl_padding .='<tr><td height="150"></td></tr>';
        $tbl_padding .= '</table>';
        $pdf->writeHTML($tbl_padding, true, false, false, false, '');
    }

    $pdf->SetFont('dejavusans', '', 9, '', true);
    // "'.$value['basic'][0]['bank_name'].'"
    $bank_name = $value['basic'][0]['bank_name'];
    // die;
    $tbl2 = '
<table border="none" cellpadding="1" cellspacing="1">
    <tr>
            <td align="center" width="60"></td>
            <td align="left" width="370">'.$value['basic'][0]['member_name'].'</td>
            <td align="center"></td>
            <td align="center"></td>
    </tr>
    <tr>
            <td align="center" width="60"></td>
            <td align="left" width="370">'.$value['basic'][0]['bank_name'].'</td>
            <td align="center"></td>
            <td align="center"></td>
    </tr>
    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370">'.$value['basic'][0]['branch_name'].'</td>
        <td align="left">'.$value['basic'][0]['payment_cycle'].'</td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370">'.$value['basic'][0]['bank_account_no'].'</td>
        <td align="left">'.$value['basic'][0]['bmc_name'].'</td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center" width="60"></td>
        <td align="left" width="370">'.$value['basic'][0]['ifsc'].'</td>
        <td align="left">'.$value['basic'][0]['dcs_name'].'</td>
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
    // var_dump(count($value['details']));
    $total_qty_am = 0;
    $total_qty_pm = 0;

    $total_bm_amount_am = 0;
    $total_bm_amount_pm = 0;

    $total_amount = 0;

    $total_rate_am = 0;
    $total_rate_pm = 0;
    $total_FAT_am = 0;
    $total_FAT_pm = 0;
    $total_SNF_am = 0;
    $total_SNF_pm = 0;
    foreach ($value['details'] as $tbl_key => $tbl_value) {
        $table .='<tr>
                        <td align="left" width="65">'.$tbl_value['am'][0]['collection_date'].'</td>
                        <td align="center" width="50">'.number_format((float)$tbl_value['am'][0]['bm_qty'],2).'</td>
                        <td align="center" width="40">'.number_format((float)$tbl_value['am'][0]['bm_avgFAT'],2).'</td>
                        <td align="center">'.number_format((float)$tbl_value['am'][0]['bm_avgSNF'],2).'</td>
                        <td align="left">'.number_format((float)$tbl_value['am'][0]['rate'],2).'</td>
                        <td align="center">'.number_format((float)$tbl_value['am'][0]['bm_amount'],2).'</td>
                        <td align="center" width="70">'.number_format((float)$tbl_value['pm'][0]['bm_qty'],2).'</td>
                        <td align="center" width="40">'.number_format((float)$tbl_value['pm'][0]['bm_avgFAT'],2).'</td>
                        <td align="center" width="40">'.number_format((float)$tbl_value['pm'][0]['bm_avgSNF'],2).'</td>
                        <td align="center">'.number_format((float)$tbl_value['pm'][0]['rate'],2).'</td>
                        <td align="center" width="60">'.number_format((float)$tbl_value['pm'][0]['bm_amount'],2).'</td>
                        <td align="center">'.number_format(((float)$tbl_value['am'][0]['bm_amount']+(float)$tbl_value['pm'][0]['bm_amount']),2).'</td>
                    </tr>';
        $total_qty_am = (float)$tbl_value['am'][0]['bm_qty'] +$total_qty_am;
        $total_qty_pm = (float)$tbl_value['pm'][0]['bm_qty'] +$total_qty_pm;
        
        $total_rate_am = (float)$tbl_value['am'][0]['rate'] +$total_rate_am;
        $total_rate_pm = (float)$tbl_value['pm'][0]['rate'] +$total_rate_pm;

        $total_FAT_am = (float)$tbl_value['am'][0]['bm_avgFAT'] +$total_FAT_am;
        $total_FAT_pm = (float)$tbl_value['pm'][0]['bm_avgFAT'] +$total_FAT_pm;

        $total_SNF_am = (float)$tbl_value['am'][0]['bm_avgSNF'] +$total_SNF_am;
        $total_SNF_pm = (float)$tbl_value['pm'][0]['bm_avgSNF'] +$total_SNF_pm;

        $total_bm_amount_am = (float)$tbl_value['am'][0]['bm_amount'] +$total_bm_amount_am;
        $total_bm_amount_pm = (float)$tbl_value['pm'][0]['bm_amount'] +$total_bm_amount_pm;

        $total_amount = (float)$tbl_value['am'][0]['bm_amount']+(float)$tbl_value['pm'][0]['bm_amount'] + $total_amount;
    }
    for($j = 0 ; $j<15-count($value['details']); $j++){
        $table .='<tr>
                    <td align="left" width="65"></td>
                    <td align="center" width="50"></td>
                    <td align="center" width="40"></td>
                    <td align="center"></td>
                    <td align="left"></td>
                    <td align="center"></td>
                    <td align="center" width="70"></td>
                    <td align="center" width="40"></td>
                    <td align="center" width="40"></td>
                    <td align="center"></td>
                    <td align="center" width="60"></td>
                    <td align="center"></td>
                </tr>';
    }
    $table .= '</table>';
    $pdf->writeHTML($table, true, false, false, false, '');

$tbl = '
    <table border="none" cellpadding="1" cellspacing="1">
        <tr>
            <td align="left" width="65"></td>
            <td align="center" width="50">'.number_format($total_qty_am,2).'</td>
            <td align="center" width="40">'.number_format(($total_FAT_am/count($value['details'])),2).'</td>
            <td align="center">'.number_format(($total_SNF_am/count($value['details'])),2).'</td>
            <td align="left">'.number_format(($total_rate_am/count($value['details'])),2).'</td>
            <td align="center">'.number_format($total_bm_amount_am,2).'</td>
            <td align="center" width="70">'.number_format($total_qty_pm,2).'</td>
            <td align="center" width="40">'.number_format(($total_FAT_pm/count($value['details'])),2).'</td>
            <td align="center" width="40">'.number_format(($total_SNF_pm/count($value['details'])),2).'</td>
            <td align="center">'.number_format(($total_rate_pm/count($value['details'])),2).'</td>
            <td align="center" width="60">'.number_format($total_bm_amount_pm,2).'</td>
            <td align="center">'.number_format($total_amount,2).'</td>
        </tr>
    </table>';

    // $pdf->writeHTMLCell( 50, 0, 0, 0, $tbl, 0, 0, false, true, '', true );
    $pdf->writeHTML($tbl, true, false, false, false, '');

    // ---------------------------------------------------------

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    // var_dump($pdf);
    $i++;
    }
    $pdf->Output('yii2_tcpdf_example2.pdf', 'I');
    Yii::$app->end();

    return;
}
}
?>