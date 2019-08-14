<?php

namespace app\modules\webservice\eipl\models;

use Yii;

/**
 * This is the model class for table "tbl_identity_master".
 *
 * @property integer $identity_master_code
 * @property string $eipl_code
 * @property string $vendor_url
 * @property string $vendor_logo
 * @property string $color_theme
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblIdentityMaster extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_identity_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['eipl_code', 'vendor_url', 'vendor_logo', 'color_theme', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'identity_master_code' => Yii::t('app', 'Identity Master Code'),
            'eipl_code' => Yii::t('app', 'Eipl Code'),
            'vendor_url' => Yii::t('app', 'Vendor Url'),
            'vendor_logo' => Yii::t('app', 'Vendor Logo'),
            'color_theme' => Yii::t('app', 'Color Theme'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblIdentityMasterQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblIdentityMasterQuery(get_called_class());
    }

    public function getIdentity() {
        return $this->find()->select(['eipl_code', 'vendor_url', 'vendor_logo', 'color_theme'])->where(['eipl_code' => $this->eipl_code])->one();
    }

}
