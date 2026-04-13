<?php

namespace app\modules\geo\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_project_".
 *
 * @property integer $project_code
 * @property string $project_name
 * @property string $description
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblProject extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_project';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['project_name', 'description', 'is_active', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['project_name'], 'required'],
            [['is_active', 'originating_type'], 'integer'],
            [['project_name'], 'string', 'max' => 100],
            [['description', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['project_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'project_code' => Yii::t('app', 'Project Code'),
            'project_name' => Yii::t('app', 'Project Name'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function projectDelete() {
        $sub_project_count = TblSubProject::find()->where(['project_code' => $this->project_code])->count();
        if ($sub_project_count > 0)
            return false;
        else
            return true;
    }

}
