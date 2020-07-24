<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_amcs_app_menu_mapping".
 *
 * @property integer $action_mapping_code
 * @property integer $action_code
 * @property string $action_name
 * @property string $application_type
 * @property string $union_code
 * @property string $user_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAmcsAppMenuMapping extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_amcs_app_menu_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['action_code'], 'integer'],
            [['action_name', 'application_type', 'union_code', 'user_code', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'action_mapping_code' => Yii::t('app', 'Action Mapping Code'),
            'action_code' => Yii::t('app', 'Action Code'),
            'action_name' => Yii::t('app', 'Action Name'),
            'application_type' => Yii::t('app', 'Application Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'user_code' => Yii::t('app', 'User Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getAmcsAppMenu() {
        return $this->hasOne(TblAmcsAppMenu::className(), ['action_code' => 'action_code']);
    }

    public function getMenuMapping() {
        $query = $this->find()->select(['tbl_amcs_app_menu_mapping.action_code', 'tbl_amcs_app_menu_mapping.action_name'])
                ->joinWith(['amcsAppMenu'])
                ->where(['tbl_amcs_app_menu_mapping.application_type' => $this->application_type, 'tbl_amcs_app_menu_mapping.union_code' => $this->union_code, 'tbl_amcs_app_menu.is_active' => 1])
                ->asArray()
                ->all();

        return ArrayHelper::map($query, 'action_code', 'action_name');
    }

}
