<?php

namespace app\modules\product\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_grn_installment".
 *
 * @property string $grn_installment_code
 * @property string $grn_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $main_amount
 * @property string $installment_amount
 * @property integer $installment_status
 * @property string $installment_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblGrnInstallment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_grn_installment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['grn_installment_code', 'grn_code', 'main_amount', 'installment_amount', 'installment_date', 'created_at', 'updated_at', 'installment_status', 'originating_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_type', 'originating_org_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'grn_installment_code' => Yii::t('app', 'Grn Installment Code'),
            'grn_code' => Yii::t('app', 'Grn Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'main_amount' => Yii::t('app', 'Main Amount'),
            'installment_amount' => Yii::t('app', 'Installment Amount'),
            'installment_status' => Yii::t('app', 'Installment Status'),
            'installment_date' => Yii::t('app', 'Installment Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

}
