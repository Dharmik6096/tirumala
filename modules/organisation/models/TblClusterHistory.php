<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_cluster_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $cluster_code
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
class TblClusterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_cluster_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['operation_type', 'cluster_code', 'plant_code', 'name', 'local_name', 'address', 'local_address', 'description', 'created_by', 'updated_by', 'union_code'], 'string'],
            [['is_active'], 'integer'],
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
            'cluster_code' => Yii::t('app', 'Cluster Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'name' => Yii::t('app', 'Name'),
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
