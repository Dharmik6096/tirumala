<?php

namespace app\modules\tms\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_task_type".
 *
 * @property integer $task_type_code
 * @property string $task_type
 * @property integer $has_form
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
class TblTaskType extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_task_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['has_form', 'is_active', 'originating_type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['task_type'], 'string', 'max' => 255],
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
            'task_type_code' => Yii::t('app', 'Task Type Code'),
            'task_type' => Yii::t('app', 'Task Type'),
            'has_form' => Yii::t('app', 'Has Form'),
            'is_active' => Yii::t('app', 'Is Active'),
            'union_code' => Yii::t('app', 'UNION'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::class, ['union_code' => 'union_code']);
    }
}
