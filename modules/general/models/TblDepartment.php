<?php

namespace app\modules\general\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_department".
 *
 * @property integer $department_id
 * @property string $department
 * @property string $local_name
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDepartment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_department';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['department'], 'unique'],
            [['department'], 'required'],
            [['department'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false,],
            [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['department', 'department_id', 'local_name', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'department_id' => Yii::t('app', 'Department ID'),
            'department' => Yii::t('app', 'Department'),
            'local_name' => Yii::t('app', 'Local Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getDepartments() {
        $data = $this->find()
                ->select(['department_id'])
                ->all();
        return ArrayHelper::map($data, 'department_id', 'department_id');
    }

    public function getActiveDepartments() {
        $data = $this->find()
                ->select(['department_id', 'department'])
                ->where(['is_active' => 1])
                ->all();
        return ArrayHelper::map($data, 'department_id', 'department');
    }

}
