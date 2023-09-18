<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_master_type".
 *
 * @property string $master_type_code
 * @property string $master_type
 * @property string $master_type_desc
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property integer $is_active
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblMasterType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_master_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['master_type_code', 'created_at', 'is_active', 'master_type', 'master_type_desc', 'created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'master_type_code' => Yii::t('app', 'Master Type Code'),
            'master_type' => Yii::t('app', 'Master Type'),
            'master_type_desc' => Yii::t('app', 'Master Type Desc'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

}
