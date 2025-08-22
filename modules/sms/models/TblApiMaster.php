<?php

namespace app\modules\sms\models;

use Yii;

/**
 * This is the model class for table "tbl_api_master".
 *
 * @property int $api_master_id
 * @property string $api_name
 * @property string $api_category
 * @property string $api_method
 * @property string $url
 * @property int $port
 * @property int $usage_type
 * @property string $token
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property int $is_active
 * @property string $operator_type
 * @property string $receiver_type
 */
class TblApiMaster extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tbl_api_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
                [['api_name', 'api_category', 'api_method', 'url', 'token', 'created_by', 'updated_by', 'union_code', 'operator_type', 'receiver_type', 'api_password'], 'string'],
                [['port', 'usage_type', 'is_active'], 'integer'],
                [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'api_master_id' => 'Api Master ID',
            'api_name' => 'Api Name',
            'api_category' => 'Api Category',
            'api_method' => 'Api Method',
            'url' => 'Url',
            'port' => 'Port',
            'usage_type' => 'Usage Type',
            'token' => 'Token',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'union_code' => 'Union Code',
            'is_active' => 'Is Active',
            'operator_type' => 'Operator Type',
            'receiver_type' => 'Receiver Type',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TblApiMasterQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblApiMasterQuery(get_called_class());
    }

    public function getAPI() {
        return $this->find()
                        ->where(['receiver_type' => $this->receiver_type, 'is_active' => 1])
                        ->andFilterWhere(['operator_type' => $this->operator_type])
                        ->andFilterWhere(['union_code' => $this->union_code])
                        ->one();
    }

    public function getRecord($rec_type, $union_code) {
        return $this->find()
                        ->where(['receiver_type' => $rec_type, 'union_code' => $union_code, 'is_active' => 1])
                        ->one();
    }

}
