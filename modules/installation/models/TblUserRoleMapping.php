<?php

namespace app\modules\installation\models;

use Yii;
use app\modules\installation\models\TblRole;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "tbl_user_role_mapping".
 *
 * @property integer $code
 * @property integer $role_code
 * @property string $user_code
 */
class TblUserRoleMapping extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_role_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['role_code'], 'safe'],
            [['user_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'role_code' => Yii::t('app', 'Role Code'),
            'user_code' => Yii::t('app', 'User Code'),
        ];
    }

      public function getExistMapingMenu($id) {
        $query = $this->find()
                ->where(['user_code' => $id])
                ->all();
        return ArrayHelper::map($query, 'role_code', 'role_code');
    }
    public function getExistMappedmenus() {
        return $this->find()
                        ->where(['user_code' => $this->user_code, 'role_code' => $this->role_code])
                        ->one();
    }
    

}
