<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\base\UserException;
use app\components\GeneralFunctions;
use Yii;
use yii\base\InvalidParamException;

/**
 * Description of childModel
 *
 * @author aura001
 */
class ChildModel extends \yii\db\ActiveRecord {

    public $f_union_code, $f_plant_code, $f_mcc_code, $f_bmc_code, $f_dcs_code, $f_route_code;
    private $toEncrypt = ['pan_no', 'contact_person_mobile_no', 'contact_person_pan_no', 'contact_person_phone_no', 'phone_no', 'birth_date', 'upi_no', 'adhar_no', 'aadhaar_no', 'dob'];
    public $grid_filter = TRUE;
    public $form_validation_type = 'default';
    public $hasImport = FALSE;
    public $import_union_config;
    public $set_master_hierarchy = [];
    public $has_strict_address_validation = FALSE;

    //put your code here
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            if ($this->hasAttribute('address')) {
                if ($this->has_strict_address_validation) {
                    \Yii::$app->general->validateDiscriptiveField($this, 'address', TRUE);
                } else {
                    \Yii::$app->general->validateDiscriptiveField($this, 'address');
                }
            }

            if ($this->hasAttribute('description'))
                \Yii::$app->general->validateDiscriptiveField($this, 'description');

            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            if ($insert) {
                if ($this->hasAttribute('created_by') && $this->created_by == NULL)
                    $this->created_by = $user;
                if ($this->hasAttribute('created_at') && $this->created_at == NULL)
                    $this->created_at = date('Y-m-d H:i:s');

                if ($this->hasAttribute('originating_org_code') && $this->originating_org_code == NULL) {
                    $this->originating_org_code = \Yii::$app->session->get('organizations_code');
                }
                if ($this->hasAttribute('originating_org_type') && $this->originating_org_type == NULL) {
                    $this->originating_org_type = 'PORTAL';
                }
                if ($this->hasAttribute('originating_type') && $this->originating_type == NULL) {
                    $this->originating_type = 0;
                }
                if ($this->hasAttribute('received_timestamp') && $this->received_timestamp == NULL) {
                    $this->received_timestamp = date('Y-m-d H:i:s');
                }
            } else {
                if ($this->hasAttribute('updated_by') && !$this->hasImport)
                    $this->updated_by = $user;
                if ($this->hasAttribute('updated_at'))
                    $this->updated_at = date('Y-m-d H:i:s');
            }
            if ($this->hasAttribute('flg_sentbox_entry')) {
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    $this->flg_sentbox_entry = 'Y';
                }
            }
            if ($this->hasAttribute('sync_status')) {
                $this->sync_status = 'U';
            }

            if ($this->hasAttribute('emilk_sync_status')) {
                $this->emilk_sync_status = 'N';
            }
            $encrypt = $this->encryptModel($this->attributes);
            $this->setAttributes($encrypt);
            $result = $this->validateAttributesSingleSpace($this->attributes());

            if ($result) {
                $this->convertAttributesToUppercase();
                return true;
            } else {
                return $result;
            }
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

    public function convertAttributesToUppercase() {
        $to_include_capital = [
            'tbl_unions' => ['union_name'],
            'tbl_plant' => ['name'],
            'tbl_cluster' => ['name'],
            'tbl_mcc_plant' => ['name'],
            'tbl_bmc' => ['bmc_name'],
            'tbl_route_mapping' => ['route_name'],
            'tbl_dcs' => ['dcs_name', 'dcs_short_name'],
            'tbl_customer_master' => ['customer_name'],
            'tbl_member' => ['member_name', 'father_name', 'surname', 'nominee_name'],
            'tbl_member_provisional' => ['member_name'],
            'tbl_transporter' => ['transporter_name'],
            'tbl_vehicle_master' => ['driver_name'],
            'tbl_product_group' => ['product_group_name'],
            'tbl_product' => ['product_name'],
            'tbl_bill_head' => ['bill_head_name'],
        ];
        $union_code = !empty($this->union_code) ? $this->union_code : Yii::$app->session->get('Unions');
        $capital_data_conversion = isset(Yii::$app->session->get('unionConfig')[$union_code]['capital_data_conversion']) ? Yii::$app->session->get('unionConfig')[$union_code]['capital_data_conversion'] : Yii::$app->general->getUnionConfiguration($union_code, 'capital_data_conversion', 'PORTAL');
        if (!empty($capital_data_conversion) && $capital_data_conversion == 1) {
            $tableName = $this->tableName();
            foreach ($this->attributes as $attribute => $value) {
                if (is_string($value) && !empty($to_include_capital[$tableName]) && in_array($attribute, $to_include_capital[$tableName])) {
                    $this->$attribute = strtoupper($value);
                }
            }
        }
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

    public function validateAttributesSingleSpace($attributeNames) {
        $union_code = !empty($this->union_code) ? $this->union_code : Yii::$app->session->get('Unions');
        $double_space_validation = isset(Yii::$app->session->get('unionConfig')[$union_code]['double_space_validation']) ? Yii::$app->session->get('unionConfig')[$union_code]['double_space_validation'] : 0;
        if ($double_space_validation == 1) {
            foreach ($attributeNames as $attribute) {
                $attributeValue = $this->$attribute;
                if (is_string($attributeValue)) {
                    if (!empty($attributeValue) && !preg_match('/^\S(?!.*  )(?:(?!  ).)*\S?$/', $attributeValue)) {
                        $label = $this->getAttributeLabel($attribute);
                        $this->addError($attribute, $label . ' must contain a single space.');
                        return FALSE;
                    }
                }
            }
        }
        return TRUE;
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

    public function encryptModel($model) {
        $result = array_intersect($this->toEncrypt, array_keys($model));
        foreach ($result as $key => $value) {
            if ($this->hasAttribute($value) && $this->{$value} != '')
                $model[$value] = \Yii::$app->general->encryptData($model[$value]);
        }
        return $model;
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
        $sentbox = new \app\modules\syncutility\models\TblSentbox();
        if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
            if (!($sentbox->setSentbox($this, $flag))) {
                throw new UserException("SentBox Entry is not created so transaction is rollback!");
            }
        }

        //return true;
    }

}

?>
