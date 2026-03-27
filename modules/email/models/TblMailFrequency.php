<?php

namespace app\modules\email\models;

use Yii;

/**
 * This is the model class for table "tbl_mail_frequency".
 *
 * @property integer $mail_frequency_id
 * @property integer $mail_reports_id
 * @property string $frequency_type
 * @property string $mail_day
 * @property string $execution_time
 * @property string $frequency_interval
 * @property string $data_month
 * @property string $from_date
 * @property string $from_shift
 * @property string $to_date
 * @property string $to_shift
 * @property string $next_execution_time
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
class TblMailFrequency extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mail_frequency';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mail_reports_id', 'is_active', 'originating_type'], 'integer'],
            [['frequency_type', 'mail_day', 'execution_time', 'frequency_interval', 'data_month', 'from_date', 'from_shift', 'to_date', 'to_shift', 'next_execution_time', 'mail_reports_id', 'is_active', 'originating_type'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mail_frequency_id' => Yii::t('app', 'Mail Frequency ID'),
            'mail_reports_id' => Yii::t('app', 'Mail Reports ID'),
            'frequency_type' => Yii::t('app', 'Frequency Type'),
            'mail_day' => Yii::t('app', 'Mail Day'),
            'execution_time' => Yii::t('app', 'Execution Time'),
            'frequency_interval' => Yii::t('app', 'Frequency Interval'),
            'data_month' => Yii::t('app', 'Data Month'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'next_execution_time' => Yii::t('app', 'Next Execution Time'),
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

}
