<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_transfer_type".
 *
 * @property integer $transfer_type_code
 * @property string $master_type
 * @property string $transfer_type
 * @property integer $is_active
 */
class TblTransferType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transfer_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['master_type', 'transfer_type'], 'string'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'transfer_type_code' => Yii::t('app', 'Transfer Type Code'),
            'master_type' => Yii::t('app', 'Master Type'),
            'transfer_type' => Yii::t('app', 'Transfer Type'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblTransferTypeQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblTransferTypeQuery(get_called_class());
    }

    public function getTransferTypeList($master_type = '') {
        $value = $this->getTransferType($master_type);
        $value = ArrayHelper::map($value, 'transfer_type', 'transfer_type_text');
        return $value;
    }

    public function getTransferType($master_type = '') {
        $query = $this->find()->select(['transfer_type', 'transfer_type_text'])->where(['is_active' => 1, 'master_type' => $master_type]);
        return $query->all();
    }

}
