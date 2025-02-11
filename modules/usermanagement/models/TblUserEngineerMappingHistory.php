<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_user_engineer_mapping_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblUserEngineerMappingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_engineer_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_id', 'engineer_id', 'created_by', 'updated_by', 'history_created_by', 'user_engineer_mapping_code', 'created_at', 'updated_at', 'history_created_at', 'originating_type', 'originating_org_code', 'originating_org_type', 'operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
