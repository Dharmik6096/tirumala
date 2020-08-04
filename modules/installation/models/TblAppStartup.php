<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_app_startup".
 *
 * @property string $code
 * @property string $startup_mode
 * @property string $startup_datetime
 * @property integer $retry_count
 * @property string $org_type
 * @property string $org_code
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
class TblAppStartup extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_app_startup';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code'], 'required'],
            [['code', 'startup_mode', 'org_type', 'org_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['startup_datetime', 'created_at', 'updated_at'], 'safe'],
            [['retry_count', 'originating_type'], 'integer', 'except' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'startup_mode' => Yii::t('app', 'Startup Mode'),
            'startup_datetime' => Yii::t('app', 'Startup Datetime'),
            'retry_count' => Yii::t('app', 'Retry Count'),
            'org_type' => Yii::t('app', 'Org Type'),
            'org_code' => Yii::t('app', 'Org Code'),
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
