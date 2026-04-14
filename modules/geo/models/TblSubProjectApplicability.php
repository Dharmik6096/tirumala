<?php

namespace app\modules\geo\models;

use app\models\ChildModel;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use Exception;
use Yii;

/**
 * This is the model class for table "tbl_sub_project_applicability".
 *
 * @property integer $sub_project_applicability_code
 * @property integer $sub_project_code
 * @property string $union_code
 * @property string $applicable_for
 * @property string $applicable_code
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblSubProjectApplicability extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sub_project_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sub_project_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'applicable_for', 'applicable_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['bmc_code', 'dcs_code', 'sub_project_code'], 'required', 'on' => ['importCsv']],
            [['wef_date'], 'required'],
            [['applicable_for'], 'default', 'value' => 'DCS'],
            [['applicable_code'], 'required', 'message' => Yii::t('app', 'Please select at least one option from the list.'), 'except' => ['importCsv']],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
            [['applicable_for'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['applicable_for' => 'customer_type'], 'on' => ['importCsv']],
            [['sub_project_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubProject::className(), 'targetAttribute' => ['sub_project_code' => 'sub_project_code'], 'on' => ['importCsv']],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date'], 'convertDate', 'on' => ['importCsv']],
            [['dcs_code'], 'validateCustomer', 'on' => ['importCsv']],
            [['originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'wef_date'], 'message' => 'The combination of DCS and WEF Date already been taken.', 'on' => ['importCsv'], 'when' => function ($model) {
                return !$model->hasErrors();
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sub_project_applicability_code' => Yii::t('app', 'Sub Project Applicability Code'),
            'sub_project_code' => Yii::t('app', 'Sub Project Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getSubProjectCode() {
        return $this->hasOne(TblSubProject::className(), ['sub_project_code' => 'sub_project_code']);
    }

    public function getCustomerTypeFor() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function getCustomerMasterCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getName() {
        return Yii::$app->general->getforeignkey($this->dcsCode, 'dcs_name');
    }

    public function allowDelete() {
        return true;
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
        }
    }

    public function validateCustomer() {
        if (empty($this->getErrors())) {
            $data = TblDcs::find()->where(['bmc_code' => $this->bmc_code, 'is_active' => 1])->andWhere(['or', ['dcs_code' => $this->dcs_code], ['dcs_code_ex' => $this->dcs_code], ['ref_code' => $this->dcs_code]])->all();
            if (!empty($data) && count($data) == 1) {
                $this->union_code = $data[0]->union_code;
                $this->plant_code = $data[0]->plant_code;
                $this->mcc_plant_code = $data[0]->mcc_plant_code;
                $this->bmc_code = $data[0]->bmc_code;
                $this->dcs_code = $this->applicable_code = $data[0]->dcs_code;
            } else {
                $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS Code') . ' is invalid'));
            }
        }
    }

    public function setOrgDetail() {
        $dcsData = $this->dcsCode;
        $this->dcs_code = $this->applicable_code = $dcsData->dcs_code;
        $this->union_code = $dcsData->union_code;
        $this->plant_code = $dcsData->plant_code;
        $this->mcc_plant_code = $dcsData->mcc_plant_code;
        $this->bmc_code = $dcsData->bmc_code;
    }

}
