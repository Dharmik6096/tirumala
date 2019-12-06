<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_collection_incentive_deduction".
 *
 * @property integer $incentive_deduction_id
 * @property string $dcs_code
 * @property string $from_time
 * @property string $to_time
 * @property integer $scheme_type
 * @property integer $shift_code
 * @property string $amount
 * @property string $from_date
 * @property string $to_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblCollectionIncentiveDeduction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_collection_incentive_deduction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'created_by', 'updated_by'], 'string'],
            [['from_time', 'to_time', 'from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
            [['scheme_type', 'shift_code'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'incentive_deduction_id' => Yii::t('app', 'Incentive Deduction ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'from_time' => Yii::t('app', 'From Time'),
            'to_time' => Yii::t('app', 'To Time'),
            'scheme_type' => Yii::t('app', 'Scheme Type'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'amount' => Yii::t('app', 'Amount'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
