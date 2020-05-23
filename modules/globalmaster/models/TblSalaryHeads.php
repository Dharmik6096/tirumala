<?php

namespace app\modules\globalmaster\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_salary_heads".
 *
 * @property string $salary_head_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $salary_head_name
 * @property integer $salary_head_type
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 *
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblSalaryHeadsLocal[] $tblSalaryHeadsLocals
 * @property TblSalaryHeadsLocalHistory[] $tblSalaryHeadsLocalHistories
 */
class TblSalaryHeads extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_salary_heads';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['salary_head_code', 'salary_head_name', 'salary_head_type'], 'required'],
            [['salary_head_name'], 'unique'],
            [['salary_head_name'], function ($attribute, $params) {
                    Yii::$app->general->validateNameGlobal($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['is_active', 'created_at', 'updated_at', 'salary_head_code', 'is_default'], 'safe'],
            [['salary_head_type'], 'integer'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['salary_head_name'], 'string', 'max' => 50],
            [['is_default'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'salary_head_code' => Yii::t('app', 'Salary Head Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'salary_head_name' => Yii::t('app', 'Salary Head Name'),
            'salary_head_type' => Yii::t('app', 'Salary Head Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSalaryHeadsLocals() {
        return $this->hasMany(TblSalaryHeadsLocal::className(), ['salary_head_code' => 'salary_head_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSalaryHeadsLocalHistories() {
        return $this->hasMany(TblSalaryHeadsLocalHistory::className(), ['salary_head_code' => 'salary_head_code']);
    }

    /**
     * @inheritdoc
     * @return TblSalaryHeadsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblSalaryHeadsQuery(get_called_class());
    }

    public function getHeadAddition() {
        return $this->find()->select(['tbl_salary_heads.salary_head_code', 'tbl_salary_heads.salary_head_name'])
                        ->where(['NOT IN', 'salary_head_code', ['91', '92', '93', '94', '95']])
                        ->andWhere(['salary_head_type' => 1, 'is_active' => 1, 'is_default' => 0])->all();
    }

    public function getHeadDeduct() {
        return $this->find()->select(['tbl_salary_heads.salary_head_code', 'tbl_salary_heads.salary_head_name'])
                        ->where(['NOT IN', 'salary_head_code', ['91', '92', '93', '94', '95']])
                        ->andWhere(['salary_head_type' => 0, 'is_active' => 1, 'is_default' => 0])->all();
    }

    public function getallHead($default = 0) {
        return $this->find()->where(['is_active' => 1, 'is_default' => $default])->all();
    }

}
