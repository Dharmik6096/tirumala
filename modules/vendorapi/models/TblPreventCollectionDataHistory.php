<?php

namespace app\modules\vendorapi\models;

use Yii;

/**
 * This is the model class for table "tbl_prevent_collection_data_history".
 *
 * @property integer $id
 * @property integer $prevent_collection_data_id
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblPreventCollectionDataHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_prevent_collection_data_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['prevent_collection_data_id', 'from_shift', 'to_shift'], 'safe'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'prevent_collection_data_id' => Yii::t('app', 'Prevent Collection Data ID'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
