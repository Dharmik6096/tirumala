<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\base\UserException;
use app\components\GeneralFunctions;
use Yii;

/**
 * Description of childModel
 *
 * @author aura001
 */
class ChildModel extends \yii\db\ActiveRecord {

    public $f_union_code, $f_plant_code, $f_mcc_code, $f_bmc_code, $f_dcs_code;
    private $toEncrypt = ['pan_no', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'phone_no', 'birth_date', 'upi_no', 'adhar_no', 'dob'];

    //put your code here
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            if ($this->hasAttribute('address'))
                \Yii::$app->general->validateDiscriptiveField($this, 'address');

            if ($this->hasAttribute('description'))
                \Yii::$app->general->validateDiscriptiveField($this, 'description');

            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            if ($insert) {
                if ($this->hasAttribute('created_by'))
                    $this->created_by = $user;
                if ($this->hasAttribute('created_at'))
                    $this->created_at = date('Y-m-d H:i:s');
            } else {
                if ($this->hasAttribute('updated_by'))
                    $this->updated_by = $user;
                if ($this->hasAttribute('updated_at'))
                    $this->updated_at = date('Y-m-d H:i:s');
            }

            $encrypt = $this->encryptModel($this->attributes);
            $this->setAttributes($encrypt);
            return true;
        } else {
            return false;
        }
    }

    public function save($master = true, $validation = TRUE) {

        $flag = parent::save($validation);
        if ($master === FALSE && $flag === FALSE) {
//            var_dump($this);exit;
            throw new UserException("Child table is not saved so transaction is rollback!");
        }
        return $flag;
    }

    public function afterFind() {
        $this->decryptModel($this);
        parent::afterFind();
    }

    public function validate($attributeNames = null, $clearErrors = true) {
        if ($clearErrors) {
            $this->clearErrors();
        }

        if (!$this->beforeValidate()) {
            return false;
        }

        $scenarios = $this->scenarios();
        $scenario = $this->getScenario();
        if (!isset($scenarios[$scenario])) {
            throw new InvalidParamException("Unknown scenario: $scenario");
        }

        if ($attributeNames === null) {
            $attributeNames = $this->activeAttributes();
        }
        $encModel = $this->attributes;
        $encModel = $this->encryptModel($encModel);
        $orgModel = $this->attributes;
        $fld = array_intersect($this->toEncrypt, array_keys($this->attributes));
        foreach ($this->getActiveValidators() as $validator) {
            if ($validator->className() == 'yii\validators\UniqueValidator' && !empty($fld)) {
                foreach ($fld as $att) {
                    $this->{$att} = (!empty($encModel[$att]) && !empty($this->{$att})) ? $encModel[$att] : $this->{$att};
                }
            } else {
                foreach ($fld as $att) {
                    $this->{$att} = (!empty($orgModel[$att]) && !empty($this->{$att})) ? $orgModel[$att] : $this->{$att};
                }
            }
            $validator->validateAttributes($this, $attributeNames);
        }
        $this->decryptModel($this);
        return !$this->hasErrors();
    }

    public function decryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model->attributes));
        foreach ($result as $key => $value) {
            $decryptData = \Yii::$app->general->decryptData($model->{$value});
            if ($decryptData) {
                $model->{$value} = $decryptData;
            } else {
                if (($value == 'birth_date' || $value == 'dob') && !(bool) strtotime($model->{$value})) {
                    $model->{$value} = '';
                }
            }
        }
        return $model;
    }

    private function encryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model));
        foreach ($result as $key => $value) {
            if ($this->hasAttribute($value) && $this->{$value} != '')
                $model[$value] = \Yii::$app->general->encryptData($model[$value]);
        }
        return $model;
    }

}

?>
