<?php

namespace app\modules\installation\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_role_action_mapping".
 *
 * @property integer $code
 * @property integer $role_code
 * @property integer $action_code
 */
class TblRoleActionMapping extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_role_action_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['role_code', 'action_code'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'role_code' => Yii::t('app', 'Role Code'),
            'action_code' => Yii::t('app', 'Action Code'),
        ];
    }

    public function getExistMapingMenu($id) {
        $codes = $this->find()
                ->select(['action_code'])
                ->where(['role_code' => $id])
                ->column();
        return array_combine($codes, $codes);
    }

    public function getExistMappedmenus() {
        return $this->find()
                        ->where(['role_code' => $this->role_code, 'action_code' => $this->action_code])
                        ->one();
    }

}
