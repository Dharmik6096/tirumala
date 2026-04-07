<?php

namespace app\modules\usermanagement\models;

use Yii;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_user_engineer_mapping".
 *
 * @property string $user_engineer_mapping_code
 * @property string $user_id
 * @property string $engineer_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblUserEngineerMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_engineer_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_engineer_mapping_code'], 'required'],
            [['created_at', 'updated_at', 'originating_type', 'user_engineer_mapping_code', 'user_id', 'engineer_id', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_engineer_mapping_code' => Yii::t('app', 'User Engineer Mapping Code'),
            'user_id' => Yii::t('app', 'User ID'),
            'engineer_id' => Yii::t('app', 'Engineer ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getEngineerCode() {
        return $this->hasOne(User::className(), ['id' => 'engineer_id']);
    }

}
