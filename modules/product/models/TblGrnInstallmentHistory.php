<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_grn_installment_history".
 *
 * @property integer $id
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
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblGrnInstallmentHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_grn_installment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main_amount', 'installment_amount'], 'number'],
            [['installment_status', 'originating_type'], 'integer'],
            [['installment_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['grn_installment_code'], 'string', 'max' => 50],
            [['grn_code'], 'string', 'max' => 30],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
            [['bmc_code'], 'string', 'max' => 12],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['operation_type'], 'string', 'max' => 10],
            [['originating_org_type', 'originating_org_code'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
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
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
