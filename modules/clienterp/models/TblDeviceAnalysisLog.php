<?php

namespace app\modules\clienterp\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_device_analysis_log".
 *
 * @property integer $id
 * @property string $device
 * @property string $location_name
 * @property string $latlong
 * @property integer $slot_no
 * @property string $user_id
 * @property string $input
 * @property integer $sample_no
 * @property string $early_prediction_time
 * @property string $total_time
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
class TblDeviceAnalysisLog extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_device_analysis_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device','location_name','latlong','slot_no','user_id','input','sample_no','early_prediction_time','total_time','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'device' => Yii::t('app', 'Device'),
            'location_name' => Yii::t('app', 'Location Name'),
            'latlong' => Yii::t('app', 'Latlong'),
            'slot_no' => Yii::t('app', 'Slot No'),
            'user_id' => Yii::t('app', 'User ID'),
            'input' => Yii::t('app', 'Input'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'early_prediction_time' => Yii::t('app', 'Early Prediction Time'),
            'total_time' => Yii::t('app', 'Total Time'),
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
