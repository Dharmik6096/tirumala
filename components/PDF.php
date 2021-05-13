<?php

namespace app\components;

/**
 * @abstract This Component Class is created to access TCPDF plugin for generating reports.
 * @example You can refer http://www.tcpdf.org/examples/example_011.phps for more details for this example.
 * @todo you can extend tcpdf class method according to your need here. You can refer http://www.tcpdf.org/examples.php section for 
 * 		 More working examples.
 * @version 1.0.0
 */
use TCPDF;
use yii;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\dynagrid\DynaGrid;
use yii\base\Widget;
use kartik\export\ExportMenu;
use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\User;
use yii\helpers\Url;
use app\modules\verification\models\TblVerification;
use app\modules\verification\models\TblKycRecord;
use yii\web\View;

// Yii::import('ext.tcpdf.*',true);
class PDF extends TCPDF {

    // Load table data from file
    public function LoadData($file) {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach ($lines as $line) {
            $data[] = explode(';', chop($line));
        }
        return $data;
    }

    // Colored table
    public function ColoredTable($header, $data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(40, 35, 40, 45);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    public function generatePdfAtmos($model) {
        $parameters = $model;
        $sp_name = 'sp_rpt_bill_format1';
        $param = [];
        $param[] = $parameters['p_union_code']; //union 
        $param[] = $parameters['p_plant_code']; //union
        $param[] = $parameters['p_mcc_code']; //
        $param[] = $parameters['p_bmc_code'];
        $param[] = $parameters['p_dcsc_code'];
        $param[] = $parameters['p_payment_cycle_code'];
        $output = \Yii::$app->general->getSpData($sp_name, $param);
        $bill_master = [];
        $bill_transaction = [];
        $bill_transaction_am = [];
        $bill_transaction_pm = [];
        $memberWiseDates = [];
        $c = 0;
        $array = [];
        foreach ($output as $key => $value) {
            $array[$value['member_code']] = [];
            $array[$value['member_code']]['basic'] = [];
            $bill_detail = [];
            $bill_detail['payment_cycle'] = $value['payment_cycle'];
            $bill_detail['bank_name'] = $value['bank_name'];
            $bill_detail['branch_name'] = $value['branch_name'];
            $bill_detail['bank_account_no'] = $value['bank_account_no'];
            $bill_detail['member_code'] = $value['member_code'];
            $bill_detail['ifsc'] = $value['ifsc'];
            $bill_detail['member_name'] = $value['member_name'];
            $bill_detail['bmc_name'] = $value['bmc_name'] . '(' . $value['bmc_code'] . ')';
            $bill_detail['dcs_name'] = $value['dcs_name'] . '(' . $value['ref_code'] . ')';

            if (empty($main[$value['member_code']]['basic'])) {
                array_push($array[$value['member_code']]['basic'], $bill_detail);
            } else {
                foreach ($array[$value['member_code']]['basic'] as $key => $master) {
                    if ($master['payment_cycle'] != $bill_detail['payment_cycle']) {
                        array_push($array[$value['member_code']]['basic'], $bill_detail);
                    }
                }
            }
            $bill_transaction['collection_date_php'] = $value['collection_date_php'];
            $bill_transaction['bm_qty'] = $value['bm_qty'];
            $bill_transaction['bm_avgFAT'] = $value['bm_avgFAT'];
            $bill_transaction['bm_avgSNF'] = $value['bm_avgSNF'];
            $bill_transaction['rate'] = $value['rate'];
            $bill_transaction['bm_amount'] = $value['bm_amount'];
            $bill_transaction['shift'] = $value['shift'];
            $bill_transaction['member_code'] = $value['member_code'];
            $bill_transaction['type'] = $value['type'];
            $bill_transaction['total_addition'] = $value['total_addition'];
            $bill_transaction['total_deduction'] = $value['total_deduction'];
            $bill_transaction['final_pay'] = $value['final_pay'];
            $memberCode = $value['member_code'];
            $memberDate = $value['collection_date_php'];
            if (empty($memberWiseDates[$memberCode])) {
                $memberWiseDates[$memberCode] = [];
            }
            $memberWiseDates[$memberCode][$memberDate] = $memberDate;
            if ($value['shift'] == 'AM') {
                if (empty($bill_transaction_am[$memberCode])) {
                    $bill_transaction_am[$memberCode] = [];
                }
                $bill_transaction_am[$memberCode][$memberDate] = $bill_transaction;
//                array_push($bill_transaction_am, $bill_transaction);
            }
            if ($value['shift'] == 'PM') {
                if (empty($bill_transaction_pm[$memberCode])) {
                    $bill_transaction_pm[$memberCode] = [];
                }
                $bill_transaction_pm[$memberCode][$memberDate] = $bill_transaction;
//                array_push($bill_transaction_pm, $bill_transaction);
            }
        }
        echo "AM<br/>";
        echo "<pre>";
        print_r($bill_transaction_am);
        echo "</pre>";
        echo "<br/>";
        echo "PM<br/>";
        echo "<pre>";
        print_r($bill_transaction_pm);
        echo "</pre>";
        die;
        foreach ($array as $key => $value) {
            $member_code = $key;
            if (!empty($memberWiseDates[$member_code])) {
                $bill_transaction = [];
                $bill_transaction['collection_date_php'] = '';
                $bill_transaction['bm_qty'] = 0;
                $bill_transaction['bm_avgFAT'] = 0;
                $bill_transaction['bm_avgSNF'] = 0;
                $bill_transaction['rate'] = 0;
                $bill_transaction['bm_amount'] = 0;
                $bill_transaction['shift'] = 0;
                $bill_transaction['member_code'] = 0;
                $bill_transaction['type'] = 0;
                $bill_transaction['total_addition'] = 0;
                $bill_transaction['total_deduction'] = 0;
                $bill_transaction['final_pay'] = 0;
                foreach ($memberWiseDates[$member_code] as $keyDate => $valueDate) {
                    $bill_transaction['collection_date_php'] = $keyDate;
                    $array[$member_code]['details'][$keyDate]['am'] = [];
                    $array[$member_code]['details'][$keyDate]['pm'] = [];
                    if (!empty($bill_transaction_am[$member_code][$keyDate])) {
                        array_push($array[$member_code]['details'][$keyDate]['am'], $bill_transaction_am[$member_code][$keyDate]);
                    } else {
                        array_push($array[$member_code]['details'][$keyDate]['am'], $bill_transaction);
                    }
                    if (!empty($bill_transaction_pm[$member_code][$keyDate])) {
                        array_push($array[$member_code]['details'][$keyDate]['pm'], $bill_transaction_pm[$member_code][$keyDate]);
                    } else {
                        array_push($array[$member_code]['details'][$keyDate]['pm'], $bill_transaction);
                    }
                }
            }
//            foreach ($bill_transaction_pm as $key => $value) {
//                if ($value['member_code'] == $member_code) {
//                    $array[$member_code]['details'][$value['collection_date_php']]['pm'] = [];
//                    array_push($array[$member_code]['details'][$value['collection_date_php']]['pm'], $value);
//                }
//            }
//            foreach ($bill_transaction_am as $key => $value) {
//                // var_dump($value);
//                if ($value['member_code'] == $member_code) {
//                    $array[$member_code]['details'][$value['collection_date_php']]['am'] = [];
//                    array_push($array[$member_code]['details'][$value['collection_date_php']]['am'], $value);
//                }
//            }
        }

        if (!empty($array)) {
            $width = 297;
            $height = 297;
            $pageLayout = array(231, 154);
            $pdf = new Yii::$app->pdf('L', PDF_UNIT, $pageLayout, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(False, 0);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetMargins(0, 34, 0);
            // for($i=0;$i<count($array);$i++){
            $i = 0;
            foreach ($array as $key => $value) {
                $pdf->AddPage();
                $pdf->SetFont('dejavusans', '', 9, '', true);
                $tableData = '';
                $tableData .= '<table  border="none" cellpadding="1" cellspacing="1">';
                $tableData .= '<tr>';
                $tableData .= '<td align="center" width="60"></td>';
                $tableData .= '<td align="left" width="370">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] . (!empty($value['basic'][0]['member_code']) ? ' - ' . substr($value['basic'][0]['member_code'], -4) : '') : '') . '</td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '</tr>';
                $tableData .= '<tr>';
                $tableData .= '<td align="center" width="60"></td>';
                $tableData .= '<td align="left" width="370">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bank_name'] : '') . '</td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '</tr>';
                $tableData .= '<tr>';
                $tableData .= '<td align="center" width="60"></td>';
                $tableData .= '<td align="left" width="370">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['branch_name'] : '') . '</td>';
                $tableData .= '<td align="left">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['payment_cycle'] : '') . '</td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '</tr>';
                $tableData .= '<tr>';
                $tableData .= '<td align="center" width="60"></td>';
                $tableData .= '<td align="left" width="370">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bank_account_no'] : '') . '</td>';
                $tableData .= '<td align="left">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bmc_name'] : '') . '</td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '</tr>';
                $tableData .= '<tr>';
                $tableData .= '<td align="center" width="60"></td>';
                $tableData .= '<td align="left" width="370">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['ifsc'] : '') . '</td>';
                $tableData .= '<td align="left">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['dcs_name'] : '') . '</td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '</tr>';
                $tableData .= '<tr>';
                $tableData .= '<td align="center" height="28"></td>';
                $tableData .= '<td align="left"></td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '<td align="center"></td>';
                $tableData .= '</tr>';
                $tableData .= '</table>';

                $pdf->writeHTML($tableData, true, false, false, false, '');

                $detailTable = '<table border="none" width="100%" cellpadding="1" cellspacing="1" style="margin-top:100px">';
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
                $total_addition = 0;
                $total_deduction = 0;
                $final_pay = 0;
                $member_type = '';
                $mDevideCount = 0;
                $eDevideCount = 0;
                foreach ($value['details'] as $tbl_key => $tbl_value) {
                    $detailTable .= '<tr>
                            <td align="center" width="60">' .
                            '<table><tr>'
                            . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? '<td  align="right">' . date('d', strtotime($tbl_value['am'][0]['collection_date_php'])) . '  </td><td align="left"> ' . date('D', strtotime($tbl_value['am'][0]['collection_date_php'])) . '</td>' : ((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? '<td  align="right">' . date('d', strtotime($tbl_value['pm'][0]['collection_date_php'])) . '  </td><td align="left"> ' . date('D', strtotime($tbl_value['pm'][0]['collection_date_php'])) . '</td>' : '<td></td><td></td>'))) .
                            '</tr></table>' .
                            '</td>
                            <td align="center" width="50">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? number_format((float) $tbl_value['am'][0]['bm_qty'], 2) : 0) . '</td>
                            <td align="right" width="39">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? number_format((float) $tbl_value['am'][0]['bm_avgFAT'], 2) : 0) . '</td>
                            <td align="right" width="38">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? number_format((float) $tbl_value['am'][0]['bm_avgSNF'], 2) : 0) . '</td>
                            <td align="right" width="50">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? number_format((float) $tbl_value['am'][0]['rate'], 2) : 0) . '</td>
                            <td align="right" width="67">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? number_format((float) $tbl_value['am'][0]['bm_amount'], 2) : 0) . '</td>
                            <td align="right" width="70">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? number_format((float) $tbl_value['pm'][0]['bm_qty'], 2) : 0) . '</td>
                            <td align="right" width="34">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? number_format((float) $tbl_value['pm'][0]['bm_avgFAT'], 2) : 0) . '</td>
                            <td align="right" width="38">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? number_format((float) $tbl_value['pm'][0]['bm_avgSNF'], 2) : 0) . '</td>
                            <td align="right" width="48">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? number_format((float) $tbl_value['pm'][0]['rate'], 2) : 0) . '</td>
                            <td align="right" width="65">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? number_format((float) $tbl_value['pm'][0]['bm_amount'], 2) : 0) . '</td>
                            <td align="right" width="80">' . number_format(((float) (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? $tbl_value['am'][0]['bm_amount'] : 0) + (float) (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? $tbl_value['pm'][0]['bm_amount'] : 0)), 2) . '</td>
                    </tr>';
                    $total_qty_am = (float) (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['bm_qty'] != '-' ? $tbl_value['am'][0]['bm_qty'] : 0) + $total_qty_am;
                    $total_qty_pm = (float) (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['bm_qty'] != '-' ? $tbl_value['pm'][0]['bm_qty'] : 0) + $total_qty_pm;

                    $total_rate_am = (float) (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['rate'] != '-' ? $tbl_value['am'][0]['rate'] : 0) + $total_rate_am;
                    $total_rate_pm = (float) (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['rate'] != '-' ? $tbl_value['pm'][0]['rate'] : 0) + $total_rate_pm;

                    $total_FAT_am = (float) (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['bm_avgFAT'] != '-' ? $tbl_value['am'][0]['bm_avgFAT'] : 0) + $total_FAT_am;
                    $total_FAT_pm = (float) (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['bm_avgFAT'] != '-' ? $tbl_value['pm'][0]['bm_avgFAT'] : 0) + $total_FAT_pm;

                    $total_SNF_am = (float) (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['bm_avgSNF'] != '-' ? $tbl_value['am'][0]['bm_avgSNF'] : 0) + $total_SNF_am;
                    $total_SNF_pm = (float) (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['bm_avgSNF'] != '-' ? $tbl_value['pm'][0]['bm_avgSNF'] : 0) + $total_SNF_pm;

                    $total_bm_amount_am = (float) (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['bm_amount'] != '-' ? $tbl_value['am'][0]['bm_amount'] : 0) + $total_bm_amount_am;
                    $total_bm_amount_pm = (float) (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['bm_amount'] != '-' ? $tbl_value['pm'][0]['bm_amount'] : 0) + $total_bm_amount_pm;
                    $total_amount = (float) (!empty($tbl_value['pm']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['bm_amount'] != '-' ? $tbl_value['am'][0]['bm_amount'] : 0) + (float) (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0] && $tbl_value['pm'][0]['bm_amount'] != '-') ? $tbl_value['pm'][0]['bm_amount'] : 0) + $total_amount;

//                    $total_addition = !empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_addition'] != '-' ? $tbl_value['pm'][0]['total_addition'] : 0;
                    if (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_addition'] != '-') {
                        $total_addition = $tbl_value['pm'][0]['total_addition'];
                    } else if (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['total_addition'] != '-') {
                        $total_addition = $tbl_value['am'][0]['total_addition'];
                    }

//                    $total_deduction = !empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_deduction'] != '-' ? $tbl_value['pm'][0]['total_deduction'] : 0;
                    if (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_deduction'] != '-') {
                        $total_deduction = $tbl_value['pm'][0]['total_deduction'];
                    } else if (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['total_deduction'] != '-') {
                        $total_deduction = $tbl_value['am'][0]['total_deduction'];
                    }

//                    $final_pay = !empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['final_pay'] != '-' ? $tbl_value['pm'][0]['final_pay'] : 0;
                    if (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['final_pay'] != '-') {
                        $final_pay = $tbl_value['pm'][0]['final_pay'];
                    } else if (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['final_pay'] != '-') {
                        $final_pay = $tbl_value['am'][0]['final_pay'];
                    }
                    $member_type = !empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? $tbl_value['pm'][0]['type'] : 'Member';

                    if ((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['bm_avgFAT'] != '-') || !empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['bm_avgSNF'] != '-') {
                        $mDevideCount++;
                    }
                    if ((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['bm_avgFAT'] != '-') || !empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['bm_avgSNF'] != '-') {
                        $eDevideCount++;
                    }
                }
                for ($j = 0; $j < 11 - count($value['details']); $j++) {
                    $detailTable .= '<tr>
                            <td align="left"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="left"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>';
                }
                $detailTable .= '</table>';
                $pdf->writeHTML($detailTable, true, false, false, false, '');

                $footerTable = '<table border="none"  width="100%" cellpadding="1" cellspacing="1">';

                if ($member_type == 'Member') {
                    $footerTable .= ' <table border="none" cellpadding="1" cellspacing="1">
                    <tr>
                        <td align="right" colspan="6">Deduction : ' . $total_deduction . '</td>
                        <td align="right" colspan="6">Net. Payable : ' . $final_pay . '</td>
                    </tr>';
                } else {
                    $footerTable .= ' <table border="none" cellpadding="1" cellspacing="1">
                    <tr>
                        <td align="right" colspan="4">Incentive : ' . $total_addition . '</td>
                        <td align="right" colspan="4">Deduction : ' . $total_deduction . '</td>
                        <td align="right" colspan="4">Net. Payable : ' . $final_pay . '</td>
                    </tr>';
                }
                $footerTable .= '<tr><td colspan="12"></td></tr>';
                $footerTable .= '
                <tr>
                    <td align="center" width="60"></td>
                    <td align="center" width="50">' . number_format($total_qty_am, 2) . '</td>
                    <td align="right" width="37">' . (!empty($mDevideCount) ? number_format(($total_FAT_am / $mDevideCount), 2) : 0) . '</td>
                    <td align="right" width="38">' . (!empty($mDevideCount) ? number_format(($total_SNF_am / $mDevideCount), 2) : 0) . '</td>
                    <td align="right" width="50">' . (!empty($total_qty_am) ? number_format(($total_bm_amount_am / $total_qty_am), 2) : 0) . '</td>
                    <td align="right" width="67">' . number_format($total_bm_amount_am, 2) . '</td>
                    <td align="right" width="70">' . number_format($total_qty_pm, 2) . '</td>
                    <td align="right" width="32">' . (!empty($eDevideCount) ? number_format(($total_FAT_pm / $eDevideCount), 2) : 0) . '</td>
                    <td align="right" width="38">' . (!empty($eDevideCount) ? number_format(($total_SNF_pm / $eDevideCount), 2) : 0) . '</td>
                    <td align="right" width="48">' . (!empty($total_qty_pm) ? number_format(($total_bm_amount_pm / $total_qty_pm), 2) : 0) . '</td>
                    <td align="right" width="65">' . number_format($total_bm_amount_pm, 2) . '</td>
                    <td align="right" width="80">' . number_format($total_amount, 2) . '</td>
                </tr>
                <tr>
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
                </tr>
            </table>';

                $footerTable .= '</table>';
                $pdf->writeHTML($footerTable, true, false, false, false, '');
            }
//            ob_end_clean();
            $pdf->Output('vendor_bill_' . date('YmdHis') . '.pdf', 'D');
//            $pdf->Output('yii2_tcpdf_example2.pdf', 'D');
            Yii::$app->end();
        }
//        return;
    }

    private function checkFieldVal($val, $retVal = '') {
        return !empty($val) ? $val : $retVal;
    }

}

?>