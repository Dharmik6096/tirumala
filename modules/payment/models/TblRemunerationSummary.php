<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_remuneration_summary".
 *
 * @property integer $remuneration_summary_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property integer $calculate_milk_recovey
 * @property integer $calculate_other_head
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblRemunerationSummary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_remuneration_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'status', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['from_datetime', 'to_datetime', 'created_at', 'updated_at'], 'safe'],
            [['from_shift', 'to_shift', 'calculate_milk_recovey', 'calculate_other_head', 'originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'remuneration_summary_code' => Yii::t('app', 'Remuneration Summary Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'calculate_milk_recovey' => Yii::t('app', 'Calculate Milk Recovey'),
            'calculate_other_head' => Yii::t('app', 'Calculate Other Head'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
