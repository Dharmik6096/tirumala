<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_android_installation".
 *
 * @property string $android_installation_id
 * @property string $organization_code
 * @property string $organization_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAndroidInstallation extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_android_installation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['android_installation_id'], 'required'],
            [['android_installation_id', 'organization_code', 'organization_type', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'android_installation_id' => Yii::t('app', 'Android Installation ID'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getCode() {
        return \Faker\Provider\Uuid::uuid();
    }

}
