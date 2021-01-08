<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_user_role_mapping".
 *
 * @property integer $code
 * @property integer $role_code
 * @property string $user_code
 */
class TblUserRoleMapping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_role_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_code'], 'integer'],
            [['user_code'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'role_code' => Yii::t('app', 'Role Code'),
            'user_code' => Yii::t('app', 'User Code'),
        ];
    }
}
