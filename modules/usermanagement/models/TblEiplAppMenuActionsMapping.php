<?php

namespace app\modules\usermanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_eipl_app_menu_actions_mapping".
 *
 * @property integer $action_mapping_code
 * @property integer $action_code
 * @property string $login_type
 * @property string $department
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblEiplAppMenuActionsMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_menu_actions_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['action_code'], 'integer'],
            [['union_code'], 'required'],
            [['login_type', 'department', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'action_mapping_code' => Yii::t('app', 'Action Mapping Code'),
            'action_code' => Yii::t('app', 'Action Code'),
            'login_type' => Yii::t('app', 'Login Type'),
            'department' => Yii::t('app', 'Department'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getExistMapingMenu() {
        if (!empty($this->login_type)) {
            $query = $this->find()
                    ->where(['login_type' => $this->login_type, "ISNULL(department,'')" => !empty($this->department) ? $this->department : '', 'union_code' => $this->union_code])
                    ->all();
            return ArrayHelper::map($query, 'action_code', 'action_code');
        } else {
            return [];
        }
    }

    public function getExistMappedmenus() {
        return $this->find()
                        ->where(['login_type' => $this->login_type, 'action_code' => $this->action_code, "ISNULL(department,'')" => !empty($this->department) ? $this->department : '', 'union_code' => $this->union_code])
                        ->one();
    }

    public function tapEvent($login_type) {

        return $this->find()->select(['tbl_eipl_app_menu_actions.action_name', 'tbl_eipl_app_menu_actions.service_url'])
                        ->join('INNER JOIN', 'tbl_eipl_app_menu_actions', 'tbl_eipl_app_menu_actions.action_code = tbl_eipl_app_menu_actions_mapping.action_code')
                        ->where(['in', 'tbl_eipl_app_menu_actions_mapping.login_type', $login_type])
                        ->andWhere(['IS NOT', 'tbl_eipl_app_menu_actions.service_url', NULL])
                        ->andWhere(['IS', 'tbl_eipl_app_menu_actions_mapping.department', NULL])
                        ->asArray()->all();
    }
}
