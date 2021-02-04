<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_role".
 *
 * @property integer $role_code
 * @property string $role_name
 * @property string $description
 */
class TblRole extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_role';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['role_name', 'description'], 'string', 'max' => 255],
            [['role_name'], 'unique'],
            [['role_name', 'description'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'role_code' => Yii::t('app', 'Role Code'),
            'role_name' => Yii::t('app', 'Role Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public function getRoleDetails($org_type) {
        $query = $this->find();
        if (strtoupper($org_type == 'MCC')) {
            $query->andWhere(['role_name' => 'BMC']);
        } elseif ($org_type == 'BMC') {
            $query->andWhere(['role_name' => 'BMC']);
        } elseif ($org_type == 'VLC') {
            $query->andWhere(['role_name' => 'VLC']);
        }
        $role = $query->one();
        return $role;
    }

}
