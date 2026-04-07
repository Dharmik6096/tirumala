<?php

/*
 *
 */

namespace app\components;

use yii;
use yii\base\Component;

class Operation extends Component {

    // private $toEncrypt = ['bank_account_no', 'ifsc', 'pan_no', 'mobile_no', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'phone_no', 'birth_date', 'upi_no', 'adhar_no', 'dob'];
    private $toEncrypt = ['bank_account_no', 'ifsc', 'pan_no', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'phone_no', 'birth_date', 'upi_no', 'adhar_no', 'dob'];

    public function defaults($model, $flag) {


        switch ($flag) {
            case INSERT : $this->insertDefault($model);
                break;
            case UPDATE : $this->updateDefault($model);
                break;
            case 'INSERT' : $this->insertDefault($model);
                break;
            case 'UPDATE' : $this->updateDefault($model);
                break;
            default:
                break;
        }
    }

    private function insertDefault($model) {
        $model->created_by = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        $model->created_at = date('Y-m-d H:i:s');
    }

    private function updateDefault($model) {
        $model->updated_by = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        $model->updated_at = date('Y-m-d H:i:s');
    }

    public function history($model, &$historyModel, $operation, $sentbox = true) {

        $data = $model->attributes;
        $historyModel->setAttributes($data);
        $result = array_intersect($this->toEncrypt, array_keys($historyModel->attributes));
        foreach ($result as $key => $value) {
            if ($historyModel->hasAttribute($value) && $historyModel->{$value} != '')
                $historyModel[$value] = \Yii::$app->general->encryptData($historyModel[$value]);
        }
        if ($historyModel->hasAttribute('history_created_at')) {
            $historyModel->history_created_at = date('Y-m-d H:i:s');
        }
        if ($historyModel->hasAttribute('operation_type')) {
            $historyModel->operation_type = $operation;
        }
        if ($historyModel->hasAttribute('history_created_by'))
            $historyModel->history_created_by = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
    }

}
