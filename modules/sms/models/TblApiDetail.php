<?php

namespace app\modules\sms\models;

use Yii;

/**
 * This is the model class for table "tbl_api_detail".
 *
 * @property int $detail_id
 * @property int $api_master_id
 * @property string $parameter
 * @property string $parameter_key
 * @property string $key_value
 * @property int $parameter_order
 * @property string $parent_tag
 * @property string $union_code
 */
class TblApiDetail extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tbl_api_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['api_master_id', 'parameter_order'], 'integer'],
            [['parameter', 'parameter_key', 'key_value', 'parent_tag', 'union_code'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'detail_id' => 'Detail ID',
            'api_master_id' => 'Api Master ID',
            'parameter' => 'Parameter',
            'parameter_key' => 'Parameter Key',
            'key_value' => 'Key Value',
            'parameter_order' => 'Parameter Order',
            'parent_tag' => 'Parent Tag',
            'union_code' => 'Union Code',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TblApiDetailQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblApiDetailQuery(get_called_class());
    }

    public function getApiMaster() {
        return $this->hasOne(TblApiMaster::className(), ['api_master_id' => 'api_master_id']);
    }

    public function getApi($id) {
        return $this->find()->joinWith('apiMaster')->where(['tbl_api_master.api_master_id' => $id])->all();
    }

}
