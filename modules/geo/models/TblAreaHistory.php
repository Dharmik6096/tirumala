<?php

namespace app\modules\geo\models;

use Yii;

/**
 * This is the model class for table "tbl_area_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $area_code
 * @property string $plant_code
 * @property string $name
 * @property string $local_name
 * @property string $address
 * @property string $local_address
 * @property string $description
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 */
class TblAreaHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_area_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['history_created_at', 'history_created_by', 'operation_type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['operation_type', 'state_code', 'area_name', 'local_name', 'address', 'local_address', 'description', 'created_by', 'updated_by', 'union_code', 'history_created_by', 'operation_type'], 'string'],
            [['is_active', 'area_code'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'area_code' => Yii::t('app', 'Area Code'),
            'area_name' => Yii::t('app', 'Area Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'address' => Yii::t('app', 'Address'),
            'local_address' => Yii::t('app', 'Local Address'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

}
