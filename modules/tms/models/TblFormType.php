<?php

namespace app\modules\tms\models;

use Yii;

/**
 * This is the model class for table "tbl_form_type".
 *
 * @property integer $form_type_code
 * @property integer $task_type_code
 * @property string $form_name
 * @property string $remarks
 * @property integer $is_active
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblFormType extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_form_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_type_code', 'is_active', 'originating_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['form_name'], 'string', 'max' => 100],
            [['remarks'], 'string', 'max' => 255],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'form_type_code' => Yii::t('app', 'Form Type Code'),
            'task_type_code' => Yii::t('app', 'Task Type'),
            'form_name' => Yii::t('app', 'Form Name'),
            'remarks' => Yii::t('app', 'Remarks'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getTaskType()
    {
        return $this->hasOne(TblTaskType::class, ['task_type_code' => 'task_type_code']);
    }

}
