<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\usermanagement\models\User;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMilkClass;

/**
 * This is the model class for table "tbl_local_milk_sale_rate".
 *
 * @property string $local_sale_rate_code
 * @property string $created_at
 * @property double $rate
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblLocalMilkSaleRate extends \app\models\ChildModel {

    public $applicable_for, $applicable_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_local_milk_sale_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicable_for', 'applicable_code', 'local_milk_rate_code'], 'safe'],
            [['wef_date', 'milk_type_code', 'milk_class', 'rate', 'union_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'milk_quality_type_code'], 'safe'],
            [['rate'], 'number'],
            [['wef_date'], 'required'],
            [['dcs_code'], 'required', 'message' => 'You must select atleast one society.'],
            [['dcs_code', 'local_milk_rate_code'], 'required', 'on' => ['importCsv']],
            [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
            [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['wef_date'], 'convertDate', 'on' => ['importCsv']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'on' => ['importCsv']],
            [['dcs_code'], 'validateDCS', 'on' => ['importCsv']],
            [['dcs_code'], 'setImport', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'local_milk_sale_rate_code' => Yii::t('app', 'Local Milk Sale Rate Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_class' => Yii::t('app', 'Milk Class'),
            'rate' => Yii::t('app', 'Rate'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Organization Code'),
            'originating_org_type' => Yii::t('app', 'Originating Organization Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'Extra Column 1'),
            'x_col2' => Yii::t('app', 'Extra Column 2'),
            'x_col3' => Yii::t('app', 'Extra Column 3'),
            'x_col4' => Yii::t('app', 'Extra Column 4'),
            'x_col5' => Yii::t('app', 'Extra Column 5'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'local_milk_rate_code' => Yii::t('app', 'Local Milk Rate Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkClass() {
        return $this->hasOne(TblMilkClass::className(), ['id' => 'milk_class']);
    }

    /**
     * @inheritdoc
     * @return TblLocalMilkSaleRateQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblLocalMilkSaleRateQuery(get_called_class());
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
        }
    }

    public function validateDCS() {
        $dcsModel = new TblDcs();
        $records = $dcsModel->find()->select(['dcs_code'])->where(['or', ['dcs_code' => $this->dcs_code], ['ref_code' => $this->dcs_code], ['dcs_code_ex' => $this->dcs_code]])->all();
        if (!empty($records) && count($records) == 1) {
            $this->dcs_code = $records[0]->dcs_code;
        } else {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' Is Invalid.'));
            return false;
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->union_code = Yii::$app->general->getforeignkey($this->mainDcsCode, 'union_code');

            $this->wef_date = !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : '';
            if (empty($this->localMilkRateCode)) {
                $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'local_milk_rate_code') . '  is invalid.'));
            } else {
                $this->rate = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'rate');
                $this->milk_quality_type_code = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'milk_quality_type_code');
                $this->milk_class = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'milk_class');
                $this->milk_type_code = Yii::$app->general->getforeignkey($this->localMilkRateCode, 'milk_type_code');
            }
        }
    }

    public function getMainDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getLocalMilkRateCode() {
        return $this->hasOne(TblProductRate::className(), ['local_milk_rate_code' => 'local_milk_rate_code']);
    }

}
