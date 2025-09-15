<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_case_type".
 *
 * @property integer $case_type_id
 * @property string $case_type_name
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCaseType extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_case_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'integer'],
            [['case_type_name'], 'string', 'max' => 50],
            [['union_code'], 'string', 'max' => 5],
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
            'case_type_id' => Yii::t('app', 'Case Type ID'),
            'case_type_name' => Yii::t('app', 'Case Type Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
