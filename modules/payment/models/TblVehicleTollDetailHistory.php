<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_toll_detail_history".
 *
 * @property integer $id
 * @property integer $toll_detail_code
 * @property string $dispatch_date
 * @property string $vehicle_code
 * @property string $parsing_no
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $toll_amount
 * @property string $fastag_amount
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblVehicleTollDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_toll_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['toll_detail_code'], 'safe'],
            [['dispatch_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest', 'union_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['toll_amount', 'fastag_amount', 'weighing_cost'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'toll_detail_code' => Yii::t('app', 'Toll Detail Code'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'parsing_no' => Yii::t('app', 'Parsing No'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'From Dest'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'To Dest'),
            'toll_amount' => Yii::t('app', 'Toll Amount'),
            'fastag_amount' => Yii::t('app', 'Fastag Amount'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
