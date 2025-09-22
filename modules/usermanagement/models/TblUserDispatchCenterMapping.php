<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_user_dispatch_center_mapping".
 *
 * @property integer $user_dispatch_center_mapping_code
 * @property string $user_code
 * @property string $dispatch_center_code
 * @property string $dispatch_center_type_code
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
class TblUserDispatchCenterMapping extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_dispatch_center_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_code', 'dispatch_center_code', 'dispatch_center_type_code'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by','x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['user_code', 'dispatch_center_code', 'dispatch_center_type_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_dispatch_center_mapping_code' => Yii::t('app', 'User Dispatch Center Mapping Code'),
            'user_code' => Yii::t('app', 'User Code'),
            'dispatch_center_code' => Yii::t('app', 'Dispatch Center Code'),
            'dispatch_center_type_code' => Yii::t('app', 'Dispatch Center Type Code'),
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
}
