<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_member_payment_restrict".
 *
 * @property integer $member_payment_restrict_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMemberPaymentRestrict extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_payment_restrict';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['wef_date', 'created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'integer'],
                [['dcs_code'], 'validateData', 'on' => ['importCsv']],
                [['dcs_code'], 'setFieldImport'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'wef_date'], 'required', 'on' => ['searchModel']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_payment_restrict_code' => Yii::t('app', 'Member Payment Restrict Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getExistData() {
        $query = $this->find()
                ->andWhere(['union_code' => $this->union_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code])
                ->all();
        return ArrayHelper::map($query, 'dcs_code', 'dcs_code');
    }

    public function validateData($attribute, $params) {
        $data = $this->find()
                ->where(['dcs_code' => $this->dcs_code])
                ->one();
        $message = '';
        if (!empty($data)) {
            $message = Yii::$app->general->getforeignkey($data->dcsCode, 'dcs_name') . '(' . Yii::$app->general->getforeignkey($data->dcsCode, 'dcs_code_ex') . '): ' . Yii::$app->controls->view_date($data->wef_date);
            $this->addError($attribute, $message);
        }
    }

    public function setFieldImport($attribute, $params) {
        if (empty($this->getErrors()) && !empty($this->dcs_code)) {
            $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'plant_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'mcc_plant_code');
            $this->bmc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
            if (empty($this->union_code)) {
                $this->addError('dcs_code', Yii::t('app/validation', $this->getAttributeLabel('dcs_code') . ' is Invalid.'));
                return false;
            }
        }
    }

}
