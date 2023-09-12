<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_scheme_rate_history".
 *
 * @property integer $id
 * @property string $scheme_rate_code
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property string $rtpl
 * @property string $rate_class
 * @property integer $is_mcc_wise_rate
 * @property string $description
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $is_active
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblSchemeRateHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_rate_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_rate_code'], 'safe'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['from_shift', 'to_shift', 'is_mcc_wise_rate', 'originating_type', 'is_active'], 'safe'],
                [['rtpl'], 'safe'],
                [['scheme_rate_code'], 'safe'],
                [['rate_class', 'union_code'], 'safe'],
                [['description'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['operation_type'], 'safe'],
                [['history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'rate_class' => Yii::t('app', 'Rate Class'),
            'is_mcc_wise_rate' => Yii::t('app', 'Is Mcc Wise Rate'),
            'description' => Yii::t('app', 'Description'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
