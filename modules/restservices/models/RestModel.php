<?php

namespace app\modules\restservices\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\collection\models\TblMilkCollection;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\payment\models\TblDcsPaymentCycle;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\payment\models\TblMemberPayment;
use yii\widgets\ActiveForm;
use yii\web\Response;

class RestModel {

    public function societyData($society_code) {
        $dcs = new TblDcs();
        return $dcs->find()->select(['dcs_code', 'dcs_name'])->where(['dcs_code' => $society_code])->one();
    }

    public function memberList($society_code) {
        $model = new TblMember();
        return $model->find()->select(['member_code', 'CONCAT(member_name,\' \',father_name,\' \',surname) as member_name', 'mobile_no'])->where(['dcs_code' => $society_code])->all();
    }

    public function collectionData($society_code, $shift, $date) {
        $model = new TblMilkCollection();
        return $model->find()->select(['member_code', 'name', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount'])
                        ->where(['dcs_code' => $society_code, 'shift' => $shift, 'CONVERT(date,date_time_of_collection)' => $date])->all();
    }

    public function purchaseRateData($society_code, $date, $milk_type_code) {

        $model = new TblPurchaseRateApplicability();
        $purchase_rate_code = $model->find()->select(['purchase_rate_code'])->where(['dcs_code' => $society_code])->andWhere(['<=', 'wef_date', $date])->one();
        $model = new TblPurchaseRateDetails();
        return $model->find()->select(['fat', 'snf', 'rtpl'])
                        ->where(['purchase_rate_code' => $purchase_rate_code['purchase_rate_code'], 'milk_type_code' => $milk_type_code])->all();
    }

    public function saveMember($member_name, $dcs_code, $mobile_no, $bank_name, $branch_name, $bank_account_no, $ifsc, $adhar_no) {
        $model = new TblMember();

        $model->member_name = $member_name;
        $model->dcs_code = $dcs_code;
        $model->mobile_no = $mobile_no;
        $model->bank_name = $bank_name;
        $model->branch_name = $branch_name;
        $model->bank_account_no = $bank_account_no;
        $model->ifsc = $ifsc;
        $model->adhar_no = $adhar_no;
        $model->scenario = 'importLimitedCsv';
        if ($model->validate()) {
            $rows = TblSocietyCodes::find()
                    ->select(['code'])
                    ->where(['dcs_code' => $model->dcs_code])
                    ->orderBy('code')
                    ->one();

            if (isset($rows['code']) && $rows['code'] > 0) {

                $model->member_code = $model->getCode();
                if ($model->save()) {
                    return "data successfully saved";
                } else {
                    return "data not saved";
                }
            } else {
                $error = "dcs_code does not exists";
                return $error = '{"Error":"' . $error . '"}';
            }
        } else {
            // Yii::$app->response->format = Response::FORMAT_JSON;
            // return $model->getErrors();

            $error = "{";
            $i = 0;
            foreach ($model->getErrors() as $errorkey => $value) {
                if ($i != count($model->getErrors()) - 1) {
                    $error .= '"' . $errorkey . '":"' . str_replace('"', "'", $value[0]) . '",';
                } else {
                    $error .= '"' . $errorkey . '":"' . str_replace('"', "'", $value[0]) . '"';
                }
                $i++;
            }
            $error .= "}";
            return json_encode($error);


            //$errors = $model->errors;  
            //$error = '{'."Error".':[';
            //$error = '[';
            //return json_encode($model->getErrors());
            // return count($model->getErrors());
        }
    }

    public function paymentCycleList($society_code, $limit) {
        $modelApp = new TblDcsPaymentCycleApplicability();
        $dcs_payment_cycle_code = $modelApp->find()->select(['dcs_payment_cycle_code'])->where(['dcs_code' => $society_code])->all();
        $model = new TblDcsPaymentCycle();
        return $model->find()->select(['dcs_payment_cycle_code', 'CONVERT(date,from_date) as from_date', 'CONVERT(date,to_date) as to_date', 'interval_value', 'lock_data', 'is_active'])->where(['dcs_payment_cycle_code' => $dcs_payment_cycle_code])->orderBy(['created_at' => SORT_DESC])->limit($limit)->all();
    }

//    public function paymentCycleData($dcs_code, $payment_cycle_code){
//        $model=new TblMemberPayment();
//        $model->dcs_payment_cycle_code = $payment_cycle_code;
//        $from_date = date('Y-m-d', strtotime($model->paymentCycleCode->from_date));
//        $to_date = date('Y-m-d', strtotime($model->paymentCycleCode->to_date));
//        $dcs_list = $dcs_code;
//        $result = \Yii::$app->db->createCommand("{CALL sp_dcs_payment_processing_data(:from_date,:to_date,:dcs_list,:payment_cycle_code)}")
//                ->bindValue(':from_date', $from_date)
//                ->bindValue(':to_date', $to_date)
//                ->bindValue(':dcs_list', $dcs_list)
//                ->bindValue(':payment_cycle_code', $payment_cycle_code);
//        $query = $result->queryAll();
//        return $query;
////        $model=new TblMemberPayment();
////        $t= $model->find()->select(['*'])->where(['dcs_code'=>$dcs_code, 'dcs_payment_cycle_code'=>$payment_cycle_code]);
////        echo $result->getRawSql();die;
//    }
    public function paymentCycleData($society_code, $member_code, $payment_cycle_code) {
        $model = new TblDcsPaymentCycle();
        $dt_date = $model->find()->select(['CONVERT(date,from_date) as from_date', 'CONVERT(date,to_date) as to_date'])->where(['dcs_payment_cycle_code' => $payment_cycle_code])->one();
        $modelCollection = new TblMilkCollection();
        return $modelCollection->find()->select(['dcs_code', 'member_code', 'name', 'mobile_no', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount', 'CONVERT(date,date_time_of_collection) as collection_date', 'shift'])
                        ->where(['dcs_code' => $society_code, 'member_code' => $member_code])
                        ->andwhere(['between', 'CONVERT(date,date_time_of_collection)', $dt_date['from_date'], $dt_date['to_date']])->all();
    }

}
