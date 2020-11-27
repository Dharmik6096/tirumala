<?php

namespace app\modules\assetmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_store_location_type".
 *
 * @property integer $slt_code
 * @property string $slt_name
 */
class TblStoreLocationType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_store_location_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['slt_name'], 'string'],
            [['is_active'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'slt_code' => Yii::t('app', 'Slt Code'),
            'slt_name' => Yii::t('app', 'Slt Name'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

}
