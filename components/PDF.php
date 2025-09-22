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

    public function generatePdfMMd($model) {
//        echo $amtInWords = $this->AmountInWords(931.13);
//        die;
//        $file = "test.txt";
//        $txt = fopen($file, "w") or die("Unable to open file!");
//        $textContent = 'asd';
//        $textContent .= "\n";
//        $textContent .= "\n";
//        $textContent .= "\n";
//        $textContent .= "qwe";
//        fwrite($txt, $textContent);
//        fclose($txt);
//
//        header('Content-Description: File Transfer');
//        header('Content-Disposition: attachment; filename=' . basename($file));
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//        header('Content-Length: ' . filesize($file));
//        header("Content-Type: text/plain");
//        readfile($file);
//        die;
        $parameters = $model;
        $sp_name = 'rpt_slip_milk_collection_date_shift_mt_wise_mmd';
        $param = [];
        $param[] = $parameters['p_union_code']; //union 
        $param[] = $parameters['p_plant_code']; //union
        $param[] = $parameters['p_mcc_code']; //
        $param[] = $parameters['p_bmc_code'];
        $param[] = $parameters['p_billing_for'];
        $param[] = $parameters['route_code'];
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
            $bill_detail['from_date'] = $value['from_date'];
            $bill_detail['to_date'] = $value['to_date'];
            $bill_detail['bmc_name'] = $value['bmc_name'] . '(' . $value['bmc_code'] . ')';
            $bill_detail['dcs_name'] = $value['dcs_name'] . '(' . $value['ref_code'] . ')';
            $bill_detail['route'] = $value['route_name'];

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
            $animalType = $value['animal_type_name'];
            if (strtolower($animalType) == 'cow') {
                if (empty($bill_transaction_am[$memberCode])) {
                    $bill_transaction_am[$memberCode] = [];
                }
                $bill_transaction_am[$memberCode][$memberDate] = $bill_transaction;
            }
            if (strtolower($animalType) == 'buffalo') {
                if (empty($bill_transaction_pm[$memberCode])) {
                    $bill_transaction_pm[$memberCode] = [];
                }
                $bill_transaction_pm[$memberCode][$memberDate] = $bill_transaction;
            }
        }
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
                    $array[$member_code]['details'][$keyDate]['cow'] = [];
                    $array[$member_code]['details'][$keyDate]['buffalo'] = [];
                    if (!empty($bill_transaction_am[$member_code][$keyDate])) {
                        array_push($array[$member_code]['details'][$keyDate]['cow'], $bill_transaction_am[$member_code][$keyDate]);
                    } else {
                        array_push($array[$member_code]['details'][$keyDate]['cow'], $bill_transaction);
                    }
                    if (!empty($bill_transaction_pm[$member_code][$keyDate])) {
                        array_push($array[$member_code]['details'][$keyDate]['buffalo'], $bill_transaction_pm[$member_code][$keyDate]);
                    } else {
                        array_push($array[$member_code]['details'][$keyDate]['buffalo'], $bill_transaction);
                    }
                }
            }
        }

        if (!empty($array)) {
            $width = 297;
            $height = 297;
            $pageLayout = array(231, 154); //22.86  15.24
            $pageLayout = array(255, 156); //25.30  15.20
//            $pdf = new Yii::$app->pdf('L', PDF_UNIT, $pageLayout, true, 'UTF-8', false);
//            $pdf->SetCreator(PDF_CREATOR);
//            $pdf->setPrintHeader(false);
//            $pdf->setPrintFooter(false);
//            $pdf->SetAutoPageBreak(False, 0);
//            $pdf->SetFont('helvetica', '', 8); //8
//            $pdf->SetTextColor(0, 0, 0);
//            $pdf->SetMargins(0, 16, 0);
            $i = 0;

            $file = 'Milktype_Wise_Bill_' . date('YmdHis') . ".txt";
            $txt = fopen($file, "w") or die("Unable to open file!");
            foreach ($array as $key => $value) {
                $textContent = '';
                $textContent .= "\n";
//                $pdf->AddPage();
//                $pdf->SetFont('dejavusans', '', 9, '', true); //9
//                $tableData = '';
//                $tableData .= '<table  border="none" cellpadding="0" cellspacing="0">';
//                // From Date
//                $tableData .= '<tr>';
//                $tableData .= '<td align="center" width="80"></td>';
//                $tableData .= '<td align="center" width="430"></td>';
//                $tableData .= '<td align="left" width="100">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['from_date'] : '') . '<br/><br/>';
//                $tableData .= (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['to_date'] : '') . '</td>';
//                $tableData .= '<td align="center"></td>';
//                $tableData .= '</tr>';
                $fDate = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['from_date'] : '');
                $tDate = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['to_date'] : '');
                $textContent .= str_pad('', 77, ' ', STR_PAD_LEFT);
                $textContent .= $fDate;
                $textContent .= "\n";
                $textContent .= "\n";
                $textContent .= "\n";
                $textContent .= str_pad('', 77, ' ', STR_PAD_LEFT);
                $textContent .= $tDate;
                $textContent .= "\n";
                $textContent .= "\n";
                $textContent .= "\n";
//                // To Date
//                $tableData .= '<tr>';
//                $tableData .= '<td align="center" width="80"></td>';
//                $tableData .= '<td align="center" width="430"></td>';
//                $tableData .= '<td align="right" width="100"></td>';
//                $tableData .= '<td align="center"></td>';
//                $tableData .= '</tr>';
//                 Ac No and IFSC
//                $tableData .= '<tr>';
//                $tableData .= '<td align="center" width="80"></td>';
//                $tableData .= '<td align="left" width="430">AC.No: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bank_account_no'] : '') . '&nbsp;&nbsp;&nbsp;IFSC: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['ifsc'] : '') . '<br/>';
//                $tableData .= (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] : '') . '<br/>';
//                $tableData .= 'Route Code: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['route'] : '') . '</td>';
//                $tableData .= '<td align="left" width="100">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . ++$i . '<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . date('d.m.Y') . '</td>';
//                $tableData .= '<td align="center"></td>';
//                $tableData .= '</tr>';
//                $tableData .= '</table>';
//                $pdf->writeHTML($tableData, true, false, false, false, '');
                $acNo = 'AC.No:' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bank_account_no'] : '');
                $acIfsc = 'IFSC:' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['ifsc'] : '');

                $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
                $memberName = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] : '');
                $textContent .= $memberName;
                $textContent .= ' ' . $acNo; //str_pad($acNo, 20, ' ', STR_PAD_RIGHT);
                $inPad = 82 - (13 + strlen($memberName) + 1 + strlen($acNo));
                $textContent .= str_pad( ++$i, $inPad, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
                $textContent .= $acIfsc; //str_pad($acIfsc, 22, ' ', STR_PAD_RIGHT);
                $routeName = 'R.CODE:' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['route'] : '');
                $textContent .= ' ' . $routeName;
                $datePad = 90 - (13 + strlen($acIfsc) + 1 + strlen($routeName));
                $textContent .= str_pad(date('d.m.Y'), $datePad, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 89, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= "\n";
                $textContent .= "\n";
                $textContent .= "\n";


//                $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
//                $textContent .= $acNo; //str_pad($acNo, 20, ' ', STR_PAD_RIGHT);
//                $textContent .= str_pad('', 5, ' ', STR_PAD_LEFT);
//                $textContent .= $acIfsc; //str_pad($acIfsc, 22, ' ', STR_PAD_RIGHT);
//                $inPad = 82 - (13 + strlen($acNo) + 5 + strlen($acIfsc));
//                $textContent .= str_pad( ++$i, $inPad, ' ', STR_PAD_LEFT);
//                $textContent .= "\n";
//                $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
//                $textContent .= (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] : '');
//                $datePad = 90 - (13 + strlen((!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] : '')));
////                $textContent .= date('d.m.Y');
//                $textContent .= str_pad(date('d.m.Y'), $datePad, ' ', STR_PAD_LEFT);
//                $textContent .= "\n";
//                $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
//                $textContent .= 'ROUTE CODE: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['route'] : '');
////                $datePad = 91 - (13 + strlen('Route Code: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['route'] : '')));
//////                $textContent .= date('d.m.Y');
////                $textContent .= str_pad(date('d.m.Y'), $datePad, ' ', STR_PAD_LEFT);
//                $textContent .= "\n";
//                $textContent .= "\n";
//                $textContent .= "\n";
//                $textContent .= "\n";
//                $textContent .= '&nbsp;' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bank_account_no'] : '') . '&nbsp;&nbsp;&nbsp;IFSC: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['ifsc'] : '');
//                $detailTable = '<table border="none" width="100%" cellpadding="0" cellspacing="0" style="margin-top:170px">';
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
//                $detailTable .= '<tr>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                        </tr>';
//                $detailTable .= '<tr>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                        </tr>';
                foreach ($value['details'] as $tbl_key => $tbl_value) {
                    $collDate = (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) ? $tbl_value['cow'][0]['collection_date_php'] : ((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) ? $tbl_value['buffalo'][0]['collection_date_php'] : '')));
                    $textContent .= str_pad($collDate, 12, ' ', STR_PAD_LEFT); //$collDate;
                    $textContent .= str_pad((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_qty']) ? number_format((float) $tbl_value['buffalo'][0]['bm_qty'], 2) : ''), 6, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['buffalo'][0]['bm_avgFAT'], 2) : ''), 7, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['buffalo'][0]['bm_avgSNF'], 2) : ''), 7, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['rate']) ? number_format((float) $tbl_value['buffalo'][0]['rate'], 2) : ''), 10, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_amount']) ? (float) $tbl_value['buffalo'][0]['bm_amount'] : ''), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_qty']) ? number_format((float) $tbl_value['cow'][0]['bm_qty'], 2) : ''), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['cow'][0]['bm_avgFAT'], 2) : ''), 6, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['cow'][0]['bm_avgSNF'], 2) : ''), 7, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['rate']) ? number_format((float) $tbl_value['cow'][0]['rate'], 2) : ''), 10, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_amount']) ? (float) $tbl_value['cow'][0]['bm_amount'] : ''), 8, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";
//                    $detailTable .= '<tr>
//                            <td align="center" width="60">' .
//                            '<table><tr>'
//                            . (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) ? '<td  align="right">' . $tbl_value['cow'][0]['collection_date_php'] . '  </td>' : ((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) ? '<td  align="right">' . $tbl_value['buffalo'][0]['collection_date_php'] . '  </td>' : '<td></td><td></td>'))) .
//                            '</tr></table>' .
//                            '</td>
//                            <td align="right" width="62">' . (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_qty']) ? number_format((float) $tbl_value['buffalo'][0]['bm_qty'], 2) : '') . '</td>
//                            <td align="right" width="45">' . (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['buffalo'][0]['bm_avgFAT'], 2) : '') . '</td>
//                            <td align="right" width="60">' . (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['buffalo'][0]['bm_avgSNF'], 2) : '') . '</td>
//                            <td align="right" width="51">' . (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['rate']) ? number_format((float) $tbl_value['buffalo'][0]['rate'], 2) : '') . '</td>
//                            <td align="right" width="67">' . (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && !empty($tbl_value['buffalo'][0]['bm_amount']) ? number_format((float) $tbl_value['buffalo'][0]['bm_amount'], 2) : '') . '</td>
//                            <td align="right" width="57">' . (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_qty']) ? number_format((float) $tbl_value['cow'][0]['bm_qty'], 2) : '') . '</td>
//                            <td align="right" width="45">' . (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['cow'][0]['bm_avgFAT'], 2) : '') . '</td>
//                            <td align="right" width="60">' . (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['cow'][0]['bm_avgSNF'], 2) : '') . '</td>
//                            <td align="right" width="51">' . (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['rate']) ? number_format((float) $tbl_value['cow'][0]['rate'], 2) : '') . '</td>
//                            <td align="right" width="67">' . (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && !empty($tbl_value['cow'][0]['bm_amount']) ? number_format((float) $tbl_value['cow'][0]['bm_amount'], 2) : '') . '</td>
//                    </tr>';
//                            <td align="right" width="80">' . number_format(((float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) ? $tbl_value['cow'][0]['bm_amount'] : 0) + (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) ? $tbl_value['buffalo'][0]['bm_amount'] : 0)), 2) . '</td>
                    $total_qty_am = (float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['bm_qty'] != '-' ? $tbl_value['cow'][0]['bm_qty'] : 0) + $total_qty_am;
                    $total_qty_pm = (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['bm_qty'] != '-' ? $tbl_value['buffalo'][0]['bm_qty'] : 0) + $total_qty_pm;

                    $total_rate_am = (float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['rate'] != '-' ? $tbl_value['cow'][0]['rate'] : 0) + $total_rate_am;
                    $total_rate_pm = (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['rate'] != '-' ? $tbl_value['buffalo'][0]['rate'] : 0) + $total_rate_pm;

                    $total_FAT_am = (float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['bm_avgFAT'] != '-' ? $tbl_value['cow'][0]['bm_avgFAT'] : 0) + $total_FAT_am;
                    $total_FAT_pm = (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['bm_avgFAT'] != '-' ? $tbl_value['buffalo'][0]['bm_avgFAT'] : 0) + $total_FAT_pm;

                    $total_SNF_am = (float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['bm_avgSNF'] != '-' ? $tbl_value['cow'][0]['bm_avgSNF'] : 0) + $total_SNF_am;
                    $total_SNF_pm = (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['bm_avgSNF'] != '-' ? $tbl_value['buffalo'][0]['bm_avgSNF'] : 0) + $total_SNF_pm;

                    $total_bm_amount_am = (float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['bm_amount'] != '-' ? $tbl_value['cow'][0]['bm_amount'] : 0) + $total_bm_amount_am;
                    $total_bm_amount_pm = (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['bm_amount'] != '-' ? $tbl_value['buffalo'][0]['bm_amount'] : 0) + $total_bm_amount_pm;
                    $total_amount = (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['bm_amount'] != '-' ? $tbl_value['cow'][0]['bm_amount'] : 0) + (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0] && $tbl_value['buffalo'][0]['bm_amount'] != '-') ? $tbl_value['buffalo'][0]['bm_amount'] : 0) + $total_amount;

                    if (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['total_addition'] != '-') {
                        $total_addition = $tbl_value['buffalo'][0]['total_addition'];
                    } else if (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['total_addition'] != '-') {
                        $total_addition = $tbl_value['cow'][0]['total_addition'];
                    }

                    if (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['total_deduction'] != '-') {
                        $total_deduction = $tbl_value['buffalo'][0]['total_deduction'];
                    } else if (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['total_deduction'] != '-') {
                        $total_deduction = $tbl_value['cow'][0]['total_deduction'];
                    }

                    if (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['final_pay'] != '-') {
                        $final_pay = $tbl_value['buffalo'][0]['final_pay'];
                    } else if (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['final_pay'] != '-') {
                        $final_pay = $tbl_value['cow'][0]['final_pay'];
                    }
                    $member_type = !empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) ? $tbl_value['buffalo'][0]['type'] : 'Member';

                    if ((!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['bm_avgFAT'] != '-') || !empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) && $tbl_value['cow'][0]['bm_avgSNF'] != '-') {
                        $mDevideCount++;
                    }
                    if ((!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['bm_avgFAT'] != '-') || !empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) && $tbl_value['buffalo'][0]['bm_avgSNF'] != '-') {
                        $eDevideCount++;
                    }
                }
                for ($j = 0; $j < 14 - count($value['details']); $j++) {
//                    $detailTable .= '<tr>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                        </tr>';
                    $textContent .= str_pad('', 89, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";
                }
                for ($j = 0; $j < 1; $j++) {
                    $textContent .= "\n";
                }
//                $detailTable .= '</table>';
//                $pdf->writeHTML($detailTable, true, false, false, false, '');
//                $footerTable = '<table border="none"  width="100%" cellpadding="1" cellspacing="1">';
//                if (false && $member_type == 'Member') {
//                    $detailTable .= ' <table border="none" cellpadding="1" cellspacing="1">
//                    <tr>
//                        <td align="right" colspan="6">Deduction : ' . $total_deduction . '</td>
//                        <td align="right" colspan="5">Net. Payable : ' . $final_pay . '</td>
//                    </tr>';
//                } else if (false) {
//                    $detailTable .= ' <table border="none" cellpadding="1" cellspacing="1">
//                    <tr>
//                        <td align="right" colspan="4">Incentive : ' . $total_addition . '</td>
//                        <td align="right" colspan="4">Deduction : ' . $total_deduction . '</td>
//                        <td align="right" colspan="3">Net. Payable : ' . $final_pay . '</td>
//                    </tr>';
//                }
                $textContent .= "\n";
                $textContent .= str_pad('', 11, ' ', STR_PAD_LEFT);
//                $textContent .= str_pad(number_format($total_qty_pm, 2), 8, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_qty_pm, 8, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($eDevideCount) ? number_format(($total_FAT_pm / $eDevideCount), 2) : 0), 7, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($eDevideCount) ? number_format(($total_SNF_pm / $eDevideCount), 2) : 0), 7, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($total_qty_pm) && false ? number_format(($total_bm_amount_pm / $total_qty_pm), 2) : ''), 9, ' ', STR_PAD_LEFT);
//                $textContent .= str_pad(number_format($total_bm_amount_pm, 2), 9, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_bm_amount_pm, 9, ' ', STR_PAD_LEFT);
//                $textContent .= str_pad(number_format($total_qty_am, 2), 8, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_qty_am, 8, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($mDevideCount) ? number_format(($total_FAT_am / $mDevideCount), 2) : 0), 6, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($mDevideCount) ? number_format(($total_SNF_am / $mDevideCount), 2) : 0), 7, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($total_qty_am) && false ? number_format(($total_bm_amount_am / $total_qty_am), 2) : ''), 9, ' ', STR_PAD_LEFT);
//                $textContent .= str_pad(number_format($total_bm_amount_am, 2), 9, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_bm_amount_am, 9, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= "\n";
                $textContent .= "\n";
//                $detailTable .= '
//                <tr>
//                    <td align="center" width="60"></td>
//                    <td align="right" width="62">' . number_format($total_qty_pm, 2) . '</td>
//                    <td align="right" width="45">' . (!empty($eDevideCount) ? number_format(($total_FAT_pm / $eDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="60">' . (!empty($eDevideCount) ? number_format(($total_SNF_pm / $eDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="51">' . (!empty($total_qty_pm) && false ? number_format(($total_bm_amount_pm / $total_qty_pm), 2) : '') . '</td>
//                    <td align="right" width="67">' . number_format($total_bm_amount_pm, 2) . '</td>
//                    <td align="right" width="57">' . number_format($total_qty_am, 2) . '</td>
//                    <td align="right" width="45">' . (!empty($mDevideCount) ? number_format(($total_FAT_am / $mDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="60">' . (!empty($mDevideCount) ? number_format(($total_SNF_am / $mDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="51">' . (!empty($total_qty_am) && false ? number_format(($total_bm_amount_am / $total_qty_am), 2) : '') . '</td>
//                    <td align="right" width="67">' . number_format($total_bm_amount_am, 2) . '</td>
//                </tr>
//            </table>';
//                $detailTable .= '<table border="none" width="100%" cellpadding="0" cellspacing="0" style="margin-top:180px">';
                $bmAmt = !empty($total_bm_amount_pm) ? $total_bm_amount_pm : 0;
                $bmAmtP = !empty($total_bm_amount_am) ? $total_bm_amount_am : 0;
                $totalAmt = $bmAmt + $bmAmtP; //number_format(((float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) ? $tbl_value['cow'][0]['bm_amount'] : 0) + (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) ? $tbl_value['buffalo'][0]['bm_amount'] : 0)), 2);
                $final_payable = $totalAmt + $total_addition - $total_deduction;
                $amtInWords = $this->AmountInWords($final_payable);
//                $detailTable .= ' 
//                    <tr>
//                        <td align="center" width="40"></td>
//                        <td align="left" width="320"></td>
//                        <td align="right" width="250"></td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="40"></td>
//                        <td align="left" width="320"></td>
//                        <td align="right" width="250">' . number_format($totalAmt, 2) . '</td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="40"></td>
//                        <td align="right" width="320"></td>
//                        <td align="right" width="250">' . number_format($total_addition, 2) . '</td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="40"></td>
//                        <td align="left" width="320">' . $amtInWords . '</td>
//                        <td align="right" width="250">' . number_format($total_deduction, 2) . '<br/>' . number_format($final_payable, 2) . '</td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="40"></td>
//                        <td align="right" width="320"></td>
//                        <td align="right"  width="250"></td>
//                    </tr>';
//
//                $detailTable .= '</table>';
//                $textContent .= str_pad(number_format($totalAmt, 2), 88, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($totalAmt, 88, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
//                $textContent .= str_pad(number_format($total_deduction, 2), 88, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_deduction, 88, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 12, ' ', STR_PAD_LEFT); //substr($amtInWords, 0, 40);
                $textContent .= str_pad(substr($amtInWords, 0, 40), 40, ' ', STR_PAD_RIGHT); //substr($amtInWords, 0, 40);
//                $textContent .= str_pad(number_format($total_addition, 2), 36, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_addition, 36, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 12, ' ', STR_PAD_LEFT); //substr($amtInWords, 0, 40);
                $textContent .= str_pad(substr($amtInWords, 40), 40, ' ', STR_PAD_RIGHT); //substr($amtInWords, 0, 40);
//                $textContent .= str_pad(number_format($final_payable, 2), 36, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($final_payable, 36, ' ', STR_PAD_LEFT);
                for ($k = 0; $k < 8; $k++) {
                    $textContent .= "\n";
                }
//                $textContent .= substr($amtInWords, 40);
                fwrite($txt, $textContent);
//                $pdf->writeHTML($detailTable, true, false, false, false, '');
            }
            fclose($txt);

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            header("Content-Type: text/plain");
            readfile($file);
            die;
//            $pdf->Output('vendor_bill_' . date('YmdHis') . '.pdf', 'D');
//            Yii::$app->end();
        }
    }

    public function AmountInWords($amount) {
//        $amount_after_decimal = $amount - ($num = floor($amount)) * 100;
        $amtArr = !empty($amount) ? explode('.', $amount) : [];
        $num = !empty($amtArr[0]) ? $amtArr[0] : 0;
        $negativeAmt = '';
        if ($num < 0) {
            $num = $num * -1;
            $negativeAmt = 'Minus ';
        }
        $amount_after_decimal = !empty($amtArr[1]) ? $amtArr[1] : '';
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
//        $negativeAmt = '';
        while ($x < $count_length) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                if ($amount < 0) {
                    $amount = $amount * -1;
                    $negativeAmt = 'Minus ';
                }
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string [] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
            } else
                $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0 && !empty($change_words[$amount_after_decimal / 10])) ? "And " . ($change_words[$amount_after_decimal / 10] . " " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $negativeAmt . $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
    }

    public function generatePdfMMdTwo($model) {
//        echo "Asdasd";die;
        $parameters = $model;
        $sp_name = 'rpt_slip_milk_collection_date_shift_wise_mmd';
        $param = [];
        $param[] = $parameters['p_union_code']; //union 
        $param[] = $parameters['p_plant_code']; //union
        $param[] = $parameters['p_mcc_code']; //
        $param[] = $parameters['p_bmc_code'];
        $param[] = $parameters['p_billing_for'];
        $param[] = $parameters['route_code'];
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
            $bill_detail['from_date'] = $value['from_date'];
            $bill_detail['to_date'] = $value['to_date'];
            $bill_detail['bmc_name'] = $value['bmc_name'] . '(' . $value['bmc_code'] . ')';
            $bill_detail['dcs_name'] = $value['dcs_name'] . '(' . $value['ref_code'] . ')';
            $bill_detail['route'] = $value['route_name']; //$value['route_name'] . '(' . $value['route_code'] . ')';

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
            }
            if ($value['shift'] == 'PM') {
                if (empty($bill_transaction_pm[$memberCode])) {
                    $bill_transaction_pm[$memberCode] = [];
                }
                $bill_transaction_pm[$memberCode][$memberDate] = $bill_transaction;
            }
        }
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
        }

        if (!empty($array)) {
//            $width = 297;
//            $height = 297;
//            $pageLayout = array(255, 114); // 25.4, 10.10
//            $pdf = new Yii::$app->pdf('L', PDF_UNIT, $pageLayout, true, 'UTF-8', false);
//            $pdf->SetCreator(PDF_CREATOR);
//            $pdf->setPrintHeader(false);
//            $pdf->setPrintFooter(false);
//            $pdf->SetAutoPageBreak(False, 0);
//            $pdf->SetFont('helvetica', '', 8);
//            $pdf->SetTextColor(0, 0, 0);
//            $pdf->SetMargins(0, 10, 0);
            $i = 0;

            $file = 'Shift_Wise_Bill_' . date('YmdHis') . ".txt";
            $txt = fopen($file, "w") or die("Unable to open file!");
            foreach ($array as $key => $value) {
                $textContent = '';
//                $textContent .= "\n";
//                $pdf->AddPage();
//                $pdf->SetFont('dejavusans', '', 9, '', true);
                $tableData = '';
                $tableData .= '<table  border="none" cellpadding="0" cellspacing="0">';
                // From Date
//                $tableData .= '<tr>';
//                $tableData .= '<td align="center" width="200"></td>';
//                $tableData .= '<td align="right" width="260">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['from_date'] : '') . '</td>';
//                $tableData .= '<td align="center" width="20"></td>';
//                $tableData .= '<td align="center" width="120">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['to_date'] : '') . '</td>';
//                $tableData .= '<td align="center"></td>';
//                $tableData .= '</tr>';

                $fDate = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['from_date'] : '');
                $tDate = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['to_date'] : '');
                $textContent .= str_pad('', 60, ' ', STR_PAD_LEFT);
                $textContent .= $fDate;
                $textContent .= str_pad('', 7, ' ', STR_PAD_LEFT);
                $textContent .= $tDate;
                $textContent .= "\n";

//                $tableData .= '<tr>';
//                $tableData .= '<td align="center" width="200"></td>';
//                $tableData .= '<td align="left" width="260">' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] : '') . '</td>';
//                $tableData .= '<td align="center" width="20"></td>';
//                $tableData .= '<td align="center" width="120">' . ++$i . '</td>';
//                $tableData .= '<td align="center"></td>';
//                $tableData .= '</tr>';
//                $tableData .= '<tr>';
//                $tableData .= '<td align="center" width="200"></td>';
//                $tableData .= '<td align="left" width="260">Route Code: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['route'] : '') . '</td>';
//                $tableData .= '<td align="center" width="20"></td>';
//                $tableData .= '<td align="right" width="120">' . date('d.m.Y') . '</td>';
//                $tableData .= '<td align="center"></td>';
//                $tableData .= '</tr>';
//                $tableData .= '<tr>';
//                $tableData .= '<td align="center" width="200"></td>';
//                $tableData .= '<td align="left" width="260">AC.No: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bank_account_no'] : '') . ' IFSC: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['ifsc'] : '') . '</td>';
//                $tableData .= '<td align="center" width="20"></td>';
//                $tableData .= '<td align="center" width="120"></td>';
//                $tableData .= '<td align="center"></td>';
//                $tableData .= '</tr>';
//                $tableData .= '</table>';
//                $pdf->writeHTML($tableData, true, false, false, false, '');
                $acNo = 'AC.No: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bank_account_no'] : '');
                $acIfsc = 'IFSC: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['ifsc'] : '');
                $memName = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] : '');
                $textContent .= str_pad('', 30, ' ', STR_PAD_LEFT);
                $textContent .= $memName; //(!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['member_name'] : '');
                $inPad = 78 - (30 + strlen($memName));
                $textContent .= str_pad( ++$i, $inPad, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 30, ' ', STR_PAD_LEFT);
                $textContent .= 'R.Code: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['route'] : '');
                $datePad = 77 - (30 + strlen('R.Code: ' . (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['route'] : '')));
//                $textContent .= date('d.m.Y');
                $textContent .= str_pad('', $datePad, ' ', STR_PAD_LEFT);
                $textContent .= date('d.m.Y');
                $textContent .= "\n";
//                $textContent .= str_pad('', 30, ' ', STR_PAD_LEFT);
//                $textContent .= $acNo; //str_pad($acNo, 20, ' ', STR_PAD_RIGHT);
//                $textContent .= str_pad('', 3, ' ', STR_PAD_LEFT);
//                $textContent .= $acIfsc; //str_pad($acIfsc, 22, ' ', STR_PAD_RIGHT);
//                $textContent .= "\n";
                $textContent .= str_pad('', 90, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 90, ' ', STR_PAD_LEFT);
                $textContent .= "\n";


//                $detailTable = '<table border="none" width="100%" cellpadding="0" cellspacing="0" style="margin-top:100px">';
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
//                $detailTable .= '<tr>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                        </tr>';
                foreach ($value['details'] as $tbl_key => $tbl_value) {

                    $collDate = (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? $tbl_value['am'][0]['collection_date_php'] : ((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? $tbl_value['pm'][0]['collection_date_php'] : '')));

//                    $textContent .= str_pad('', 5, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($collDate, 11, ' ', STR_PAD_LEFT); //$collDate;
                    $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_qty']) ? number_format((float) $tbl_value['am'][0]['bm_qty'], 2) : ''), 9, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['am'][0]['bm_avgFAT'], 2) : ''), 9, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['am'][0]['bm_avgSNF'], 2) : ''), 7, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['rate']) ? number_format((float) $tbl_value['am'][0]['rate'], 2) : ''), 7, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_amount']) ? (float) $tbl_value['am'][0]['bm_amount'] : ''), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_qty']) ? number_format((float) $tbl_value['pm'][0]['bm_qty'], 2) : ''), 7, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['pm'][0]['bm_avgFAT'], 2) : ''), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['pm'][0]['bm_avgSNF'], 2) : ''), 7, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['rate']) ? number_format((float) $tbl_value['pm'][0]['rate'], 2) : ''), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_amount']) ? (float) $tbl_value['pm'][0]['bm_amount'] : ''), 8, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";
//                    $detailTable .= '<tr>
//                            <td align="center" width="70">' .
//                            '<table><tr>'
//                            . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? '<td  align="right">' . $tbl_value['am'][0]['collection_date_php'] . '  </td>' : ((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? '<td  align="right">' . $tbl_value['pm'][0]['collection_date_php'] . '  </td>' : '<td></td><td></td>'))) .
//                            '</tr></table>' .
//                            '</td>
//                            <td align="center" width="90">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_qty']) ? number_format((float) $tbl_value['am'][0]['bm_qty'], 2) : '') . '</td>
//                            <td align="right" width="35">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['am'][0]['bm_avgFAT'], 2) : '') . '</td>
//                            <td align="right" width="38">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['am'][0]['bm_avgSNF'], 2) : '') . '</td>
//                            <td align="right" width="50">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['rate']) ? number_format((float) $tbl_value['am'][0]['rate'], 2) : '') . '</td>
//                            <td align="right" width="62">' . (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_amount']) ? number_format((float) $tbl_value['am'][0]['bm_amount'], 2) : '') . '</td>
//                            <td align="center" width="75">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_qty']) ? number_format((float) $tbl_value['pm'][0]['bm_qty'], 2) : '') . '</td>
//                            <td align="right" width="45">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['pm'][0]['bm_avgFAT'], 2) : '') . '</td>
//                            <td align="right" width="44">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['pm'][0]['bm_avgSNF'], 2) : '') . '</td>
//                            <td align="right" width="54">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['rate']) ? number_format((float) $tbl_value['pm'][0]['rate'], 2) : '') . '</td>
//                            <td align="right" width="58">' . (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_amount']) ? number_format((float) $tbl_value['pm'][0]['bm_amount'], 2) : '') . '</td>
//                    </tr>';
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

                    if (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_addition'] != '-') {
                        $total_addition = $tbl_value['pm'][0]['total_addition'];
                    } else if (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['total_addition'] != '-') {
                        $total_addition = $tbl_value['am'][0]['total_addition'];
                    }

                    if (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_deduction'] != '-') {
                        $total_deduction = $tbl_value['pm'][0]['total_deduction'];
                    } else if (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['total_deduction'] != '-') {
                        $total_deduction = $tbl_value['am'][0]['total_deduction'];
                    }

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
//                    $detailTable .= '<tr>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="left"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                            <td align="center"></td>
//                        </tr>';
                    $textContent .= str_pad('', 90, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";
                }
//                if (false && $member_type == 'Member') {
//                    $footerTable .= ' <table border="none" cellpadding="1" cellspacing="1">
//                    <tr>
//                        <td align="right" colspan="6">Deduction : ' . $total_deduction . '</td>
//                        <td align="right" colspan="5">Net. Payable : ' . $final_pay . '</td>
//                    </tr>';
//                } else if (false) {
//                    $footerTable .= ' <table border="none" cellpadding="1" cellspacing="1">
//                    <tr>
//                        <td align="right" colspan="4">Incentive : ' . $total_addition . '</td>
//                        <td align="right" colspan="4">Deduction : ' . $total_deduction . '</td>
//                        <td align="right" colspan="3">Net. Payable : ' . $final_pay . '</td>
//                    </tr>';
//                }
//                $detailTable .= '
//                <tr>
//                    <td align="center" width="70"></td>
//                    <td align="center" width="90">' . number_format($total_qty_am, 2) . '</td>
//                    <td align="right" width="35">' . (!empty($mDevideCount) ? number_format(($total_FAT_am / $mDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="38">' . (!empty($mDevideCount) ? number_format(($total_SNF_am / $mDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="50">' . (!empty($total_qty_am) ? number_format(($total_bm_amount_am / $total_qty_am), 2) : 0) . '</td>
//                    <td align="right" width="62">' . number_format($total_bm_amount_am, 2) . '</td>
//                    <td align="center" width="75">' . number_format($total_qty_pm, 2) . '</td>
//                    <td align="right" width="45">' . (!empty($eDevideCount) ? number_format(($total_FAT_pm / $eDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="44">' . (!empty($eDevideCount) ? number_format(($total_SNF_pm / $eDevideCount), 2) : 0) . '</td>
//                    <td align="right" width="54">' . (!empty($total_qty_pm) ? number_format(($total_bm_amount_pm / $total_qty_pm), 2) : 0) . '</td>
//                    <td align="right" width="58">' . number_format($total_bm_amount_pm, 2) . '</td>
//                </tr>
//            </table>';
//                $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_qty_am, 20, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($mDevideCount) ? number_format(($total_FAT_am / $mDevideCount), 2) : 0), 9, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($mDevideCount) ? number_format(($total_SNF_am / $mDevideCount), 2) : 0), 7, ' ', STR_PAD_LEFT);
//                $textContent .= str_pad((!empty($total_qty_am) ? number_format(($total_bm_amount_am / $total_qty_am), 2) : 0), 16, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_bm_amount_am, 16, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_qty_pm, 7, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($eDevideCount) ? number_format(($total_FAT_pm / $eDevideCount), 2) : 0), 7, ' ', STR_PAD_LEFT);
                $textContent .= str_pad((!empty($eDevideCount) ? number_format(($total_SNF_pm / $eDevideCount), 2) : 0), 7, ' ', STR_PAD_LEFT);
//                $textContent .= str_pad((!empty($total_qty_pm) ? number_format(($total_bm_amount_pm / $total_qty_pm), 2) : 0), 8, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_bm_amount_pm, 17, ' ', STR_PAD_LEFT);
                $textContent .= "\n";

//                $detailTable .= '<table border="none" width="100%" cellpadding="0" cellspacing="0" style="margin-top:180px">';
                $bmAmt = !empty($total_bm_amount_pm) ? $total_bm_amount_pm : 0;
                $bmAmtP = !empty($total_bm_amount_am) ? $total_bm_amount_am : 0;
                $totalQtyP = !empty($total_qty_pm) ? $total_qty_pm : 0;
                $totalQtyA = !empty($total_qty_am) ? $total_qty_am : 0;
                $totalQty = $totalQtyP + $totalQtyA;
                $totalAmt = $bmAmt + $bmAmtP; //number_format(((float) (!empty($tbl_value['cow']) && !empty($tbl_value['cow'][0]) ? $tbl_value['cow'][0]['bm_amount'] : 0) + (float) (!empty($tbl_value['buffalo']) && !empty($tbl_value['buffalo'][0]) ? $tbl_value['buffalo'][0]['bm_amount'] : 0)), 2);
                $final_payable = $totalAmt + $total_addition - $total_deduction;
                $amtInWords = $this->AmountInWords($final_payable);

                $textContent .= str_pad('', 59, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($total_deduction, 8, ' ', STR_PAD_RIGHT);
                $textContent .= str_pad('', 12, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($totalQty, 8, ' ', STR_PAD_RIGHT);
                $textContent .= "\n";
//                $textContent .= str_pad(number_format($total_addition, 2), 88, ' ', STR_PAD_LEFT);
//                $textContent .= "\n";
                $textContent .= str_pad('', 9, ' ', STR_PAD_LEFT); //substr($amtInWords, 0, 40);
                $textContent .= str_pad(substr($amtInWords, 0, 32), 32, ' ', STR_PAD_RIGHT); //substr($amtInWords, 0, 40);
                $LeaveSpace = 70 - strlen(str_pad(substr($amtInWords, 0, 32), 32, ' ', STR_PAD_RIGHT));
                $textContent .= str_pad('', $LeaveSpace, ' ', STR_PAD_LEFT); //substr($amtInWords, 0, 40);
                $textContent .= str_pad($totalAmt, 8, ' ', STR_PAD_RIGHT);
                $textContent .= "\n";
                $textContent .= str_pad('', 9, ' ', STR_PAD_LEFT); //substr($amtInWords, 0, 40);
                $textContent .= str_pad(substr($amtInWords, 32), 32, ' ', STR_PAD_RIGHT); //substr($amtInWords, 0, 40); //substr($amtInWords, 0, 40);
                $LeaveSpace = 70 - strlen(str_pad(substr($amtInWords, 32), 32, ' ', STR_PAD_RIGHT));
                $textContent .= str_pad('', $LeaveSpace, ' ', STR_PAD_LEFT);
                $textContent .= str_pad($final_payable, 8, ' ', STR_PAD_RIGHT);
                $textContent .= "\n";
                $textContent .= str_pad('', 90, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 90, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 90, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                $textContent .= str_pad('', 90, ' ', STR_PAD_LEFT);
                $textContent .= "\n";
                fwrite($txt, $textContent);
//                $detailTable .= ' 
//                    <tr>
//                        <td align="center" width="50"></td>
//                        <td align="left" width="280" ></td>
//                        <td align="left" width="100"></td>
//                        <td align="left" width="100">' . number_format($total_deduction, 2) . '</td>
//                        <td align="center" width="120">' . number_format($totalQty, 2) . '</td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="50"></td>
//                        <td align="left" width="280" rowspan="3">' . $amtInWords . '</td>
//                        <td align="left" width="100"></td>
//                        <td align="left" width="100"></td>
//                        <td align="center" width="120">' . number_format($totalAmt, 2) . '</td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="50"></td>
//                        <td align="left" width="100"></td>
//                        <td align="left" width="100"></td>
//                        <td align="center" width="120">' . number_format($final_payable, 2) . '</td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="50"></td>
//                        <td align="right" width="100"></td>
//                        <td align="left" width="100"></td>
//                        <td align="center"  width="120"></td>
//                    </tr> 
//                    <tr>
//                        <td align="center" width="50"></td>
//                        <td align="right" width="100"></td>
//                        <td align="left" width="100"></td>
//                        <td align="center"  width="120"></td>
//                    </tr>';
//                $detailTable .= '</table>';
//                $pdf->writeHTML($detailTable, true, false, false, false, '');
            }
            fclose($txt);

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            header("Content-Type: text/plain");
            readfile($file);
            die;
//            ob_end_clean();
//            $pdf->Output('vendor_bill_' . date('YmdHis') . '.pdf', 'D');
//            $pdf->Output('yii2_tcpdf_example2.pdf', 'D');
//            Yii::$app->end();
        }
//        return;
    }

    public function generatePdfElanad($model) {
        $parameters = $model;
        $sp_name = 'rpt_slip_milk_collection_date_shift_wise_elanad';
        $param = [];
        $param[] = $parameters['p_union_code'];
        $param[] = $parameters['p_plant_code'];
        $param[] = $parameters['p_mcc_code'];
        $param[] = $parameters['p_bmc_code'];
        $param[] = $parameters['p_billing_for'];
        $param[] = $parameters['route_code'];
        $param[] = $parameters['p_dcsc_code'];
        $param[] = $parameters['p_payment_cycle_code'];
        $output = \Yii::$app->general->getSpData($sp_name, $param);
        $bill_transaction = [];
        $bill_transaction_am = [];
        $bill_transaction_pm = [];
        $memberWiseDates = [];
        $array = [];
        foreach ($output as $key => $value) {
            $array[$value['member_code']] = [];
            $array[$value['member_code']]['basic'] = [];
            $bill_detail = [];
            $bill_detail['payment_cycle'] = $value['payment_cycle'];
            $bill_detail['member_code'] = $value['member_code'];
            $bill_detail['member_name'] = $value['member_name'];
            $bill_detail['periods'] = $value['periods'];
            $bill_detail['bmc_name'] = $value['bmc_name'] . '(' . $value['bmc_code'] . ')';
            $bill_detail['dcs_name'] = $value['dcs_name'] . '(' . $value['ref_code'] . ')';
            $bill_detail['bmc_code'] = $value['bmc_code'];
            $bill_detail['ref_code'] = $value['ref_code'];
            $bill_detail['route'] = $value['route_name'];

            if (empty($main[$value['member_code']]['basic'])) {
                array_push($array[$value['member_code']]['basic'], $bill_detail);
            } else {
                foreach ($array[$value['member_code']]['basic'] as $key => $master) {
                    if ($master['payment_cycle'] != $bill_detail['payment_cycle']) {
                        array_push($array[$value['member_code']]['basic'], $bill_detail);
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
            $bill_transaction['type'] = $value['type'];
            $bill_transaction['total_addition'] = $value['total_addition'];
            $bill_transaction['total_deduction'] = $value['total_deduction'];
            $bill_transaction['final_pay'] = $value['final_pay'];
            $memberCode = $value['member_code'];
            $memberDate = $value['collection_date'];
            if (empty($memberWiseDates[$memberCode])) {
                $memberWiseDates[$memberCode] = [];
            }
            $memberWiseDates[$memberCode][$memberDate] = $memberDate;
            if ($value['shift'] == 'AM') {
                if (empty($bill_transaction_am[$memberCode])) {
                    $bill_transaction_am[$memberCode] = [];
                }
                $bill_transaction_am[$memberCode][$memberDate] = $bill_transaction;
            }
            if ($value['shift'] == 'PM') {
                if (empty($bill_transaction_pm[$memberCode])) {
                    $bill_transaction_pm[$memberCode] = [];
                }
                $bill_transaction_pm[$memberCode][$memberDate] = $bill_transaction;
            }
        }
        foreach ($array as $key => $value) {
            $member_code = $key;
            if (!empty($memberWiseDates[$member_code])) {
                $bill_transaction = [];
                $bill_transaction['collection_date'] = '';
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
                    $bill_transaction['collection_date'] = $keyDate;
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
        }

        if (!empty($array)) {
            $i = 1;
            $path = Yii::$app->basePath . '/web/pdf_report_log/';
            if (Yii::$app->general->checkDirectory($path)) {
                $file = $path . 'Shift_Wise_Bill_' . date('YmdHis') . ".txt";

                $txt = fopen($file, "w") or die("Unable to open file!");
                foreach ($array as $key => $value) {
                    $textContent = '';
                    $periods = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['periods'] : '');
                    $textContent .= str_pad('', 74, ' ', STR_PAD_LEFT);
                    $textContent .= $periods;
                    $textContent .= "\n";
                    $textContent .= str_pad('', 84, ' ', STR_PAD_LEFT);
                    $textContent .= $i++;
                    $textContent .= "\n";

                    $dcsRefCode = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['ref_code'] : '');
                    $bmcCode = (!empty($value['basic']) && !empty($value['basic'][0]) ? $value['basic'][0]['bmc_code'] : '');
                    $route = (!empty($value['basic']) && !empty($value['basic'][0]) ? substr($value['basic'][0]['route'], 0, 16) : '');
                    $textContent .= str_pad('', 5, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($dcsRefCode, 15, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad('', 5, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($bmcCode, 20, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad('', 8, ' ', STR_PAD_RIGHT);
                    $textContent .= str_pad($route, 18, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
                    $textContent .= date('d.m.Y');
                    $textContent .= "\n";
                    $textContent .= "\n";
                    $textContent .= "\n";
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
                        $collDate = (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) ? $tbl_value['am'][0]['collection_date'] : ((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) ? $tbl_value['pm'][0]['collection_date'] : '')));
                        // $textContent .= $collDate;
                        $textContent .= str_pad($collDate, 4, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_qty']) ? number_format((float) $tbl_value['am'][0]['bm_qty'], 2) : ''), 9, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['am'][0]['bm_avgFAT'], 2) : ''), 8, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['am'][0]['bm_avgSNF'], 2) : ''), 8, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['rate']) ? number_format((float) $tbl_value['am'][0]['rate'], 2) : ''), 9, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && !empty($tbl_value['am'][0]['bm_amount']) ? (float) $tbl_value['am'][0]['bm_amount'] : ''), 10, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_qty']) ? number_format((float) $tbl_value['pm'][0]['bm_qty'], 2) : ''), 9, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_avgFAT']) ? number_format((float) $tbl_value['pm'][0]['bm_avgFAT'], 2) : ''), 10, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_avgSNF']) ? number_format((float) $tbl_value['pm'][0]['bm_avgSNF'], 2) : ''), 8, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['rate']) ? number_format((float) $tbl_value['pm'][0]['rate'], 2) : ''), 9, ' ', STR_PAD_LEFT);
                        $textContent .= str_pad((!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && !empty($tbl_value['pm'][0]['bm_amount']) ? (float) $tbl_value['pm'][0]['bm_amount'] : ''), 10, ' ', STR_PAD_LEFT);
                        $textContent .= "\n";
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

                        if (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_addition'] != '-') {
                            $total_addition = $tbl_value['pm'][0]['total_addition'];
                        } else if (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['total_addition'] != '-') {
                            $total_addition = $tbl_value['am'][0]['total_addition'];
                        }

                        if (!empty($tbl_value['pm']) && !empty($tbl_value['pm'][0]) && $tbl_value['pm'][0]['total_deduction'] != '-') {
                            $total_deduction = $tbl_value['pm'][0]['total_deduction'];
                        } else if (!empty($tbl_value['am']) && !empty($tbl_value['am'][0]) && $tbl_value['am'][0]['total_deduction'] != '-') {
                            $total_deduction = $tbl_value['am'][0]['total_deduction'];
                        }

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
                    for ($j = 0; $j <= 8 - count($value['details']); $j++) {
                        $textContent .= str_pad('', 96, ' ', STR_PAD_LEFT);
                        $textContent .= "\n";
                    }
                    $textContent .= str_pad('', 6, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($total_qty_am, 9, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($mDevideCount) ? number_format(($total_FAT_am / $mDevideCount), 2) : 0), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($mDevideCount) ? number_format(($total_SNF_am / $mDevideCount), 2) : 0), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($total_qty_am) ? number_format(($total_bm_amount_am / $total_qty_am), 2) : 0), 9, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($total_bm_amount_am, 10, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($total_qty_pm, 9, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($eDevideCount) ? number_format(($total_FAT_pm / $eDevideCount), 2) : 0), 10, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($eDevideCount) ? number_format(($total_SNF_pm / $eDevideCount), 2) : 0), 8, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad((!empty($total_qty_pm) ? number_format(($total_bm_amount_pm / $total_qty_pm), 2) : 0), 9, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($total_bm_amount_pm, 10, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";

                    $bmAmt = !empty($total_bm_amount_pm) ? $total_bm_amount_pm : 0;
                    $bmAmtP = !empty($total_bm_amount_am) ? $total_bm_amount_am : 0;
                    $totalQtyP = !empty($total_qty_pm) ? $total_qty_pm : 0;
                    $totalQtyA = !empty($total_qty_am) ? $total_qty_am : 0;
                    $totalQty = $totalQtyP + $totalQtyA;
                    $totalAmt = $bmAmt + $bmAmtP;
                    $final_payable = $totalAmt + $total_addition - $total_deduction;
                    $RTPL = number_format($final_payable / $totalQty, 2);

                    $textContent .= str_pad('', 82, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad(number_format($totalQty, 2), 14, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";
                    ;
                    $textContent .= str_pad('', 56, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($total_deduction, 13, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad($RTPL, 14, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";
                    $textContent .= str_pad('', 9, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad(number_format($totalAmt, 2), 34, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad('', 12, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad(number_format($total_addition, 2), 14, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad('', 13, ' ', STR_PAD_LEFT);
                    $textContent .= str_pad(number_format($final_payable, 2), 14, ' ', STR_PAD_LEFT);
                    $textContent .= "\n";
                    $textContent .= "\n";
                    $textContent .= "\n";
                    fwrite($txt, $textContent);
                }
                fclose($txt);
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                header("Content-Type: text/plain");
                readfile($file);
                unlink($file);
                die;
            }
        }
    }

    public function generatePdfAMULServiceCall($complaint_code) {
        $sp_name = 'sp_portal_service_call_history';
        $param = [];
        $param[] = $complaint_code;
        $output = \Yii::$app->general->getSpData($sp_name, $param);
        if (!empty($output)) {
            $output = $output[0];
            $pageLayout = array(231, 154);
            $pdf = new Yii::$app->pdf('L', PDF_UNIT, $pageLayout, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(False, 0);
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetMargins(5, 5, 5);
            $pdf->AddPage();
            $pdf->SetFont('dejavusans', '', 9, '', true);
            $tableData = '<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-llyw{background-color:#c0c0c0;border-color:inherit;text-align:left;vertical-align:top}
.tg .tg-9u2q{background-color:#9b9b9b;border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
</style>
<table class="tg" border="1">
<thead>
  <tr>
    <th class="tg-9u2q" colspan="4"><span style="font-weight:bold">SERVICE CALL HISTORY</span></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class="tg-0pky" colspan="3"><br/>Everest Instruments Pvt. Limited  
    <br/><br/>D-902, Ganesh Meridian Opp: Gujart High Court, <br/>Sarkhej - Gandhinagar Hwy, Ahmedabad, Gujarat 380060 </td>
    <td class="tg-0pky"><img src="themes/pcdf/assets/images/logo.png" alt="Everest Logo" style="float:right;width:500px;height:150px;"></td>
  </tr>
  <tr>
    <td class="tg-0pky" colspan="2"><b>Service Call No</b> &nbsp;&nbsp;&nbsp;: ' . $output['service_call_no'] . '<br/> <b>Service Call Date</b> : ' . $output['complaint_date'] . '</td>
    <td class="tg-0pky" colspan="2"><b>Division&nbsp;&nbsp;</b> : IT <br/> <b>Call Type</b> : ' . $output['complaint_type'] . '<br/></td>
  </tr>
  <tr>
    <td class="tg-llyw" colspan="4"><span style="font-weight:bold">Customer Details</span></td>
  </tr>
  <tr>
    <td class="tg-0pky" colspan="2"><b>Parent Party</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['union_name'] . '<br/> <b>Customer Name</b> : ' . $output['dcs_name'] . '<br/> <b>Address</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['address'] . '<br/> <b>Contact Person</b> &nbsp;: ' . $output['contact_person'] . '<br/> <b>Mobile No</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['contact_person_no'] . '<br/> <b>Complaint</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['complaint_desc'] . '</td>
    <td class="tg-0pky" colspan="2"><b>Product Name</b> : ' . $output['product_name'] . '<br/> <b>Product Sr.No</b> : ' . $output['product_code'] . '<br/> <b>Call Type</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ' . $output['complaint_type'] . '<br/><br/><br/> <b>Remarks</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ' . $output['remarks'] . '<br/></td>
  </tr>
  <tr>
    <td class="tg-llyw" colspan="4"><span style="font-weight:bold">Allocation Details</span></td>
  </tr>
  <tr>
    <td class="tg-0pky" colspan="2"><b>Call Logged On</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['created_at'] . '<br/> <b>Call Taken By</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['created_by'] . '<br/> </td>
    <td class="tg-0pky" colspan="2"><b>Assign To</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['assign_to'] . '<br/> <b>Allocation Date</b>&nbsp;: ' . $output['assign_date'] . '<br/><b>Priority</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['priority'] . '<br/> <b>Main Status</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['complaint_status'] . '<br/></td>
  </tr>
</tbody>
</table>';
//    <td class="tg-0pky"><b>Revision No&nbsp;&nbsp;&nbsp;</b> : <br/> <b>Revision Date</b> : </td>
//    <td class="tg-0pky" colspan="2"><b>Call Logged On</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['complaint_date'] . '<br/> <b>Call Taken By</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' . $output['created_by'] . '<br/> <b>Help Desk By</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <br/> <b>Help Desk Start On</b>&nbsp;&nbsp;: <br/> <b>Help Desk End On</b>&nbsp;&nbsp;&nbsp;: </td>

            $pdf->writeHTML($tableData, true, false, false, false, '');
            $pdf->Output('service_bill_' . $complaint_code . '.pdf', 'D');
            Yii::$app->end();
        }
    }

}

?>