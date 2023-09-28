<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_shift_time_android".
 *
 * @property integer $org_code
 * @property integer $collection_type
 * @property string $m_start_time
 * @property string $e_start_time
 * @property integer $m_lock_time
 * @property string $e_lock_time
 * @property string $date_shift_enable
 * @property string $grace_hr
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
 */
class TblShiftTimeAndroid extends \app\models\ChildModel {

    public $original_org_code, $plant_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_shift_time_android';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['org_code', 'org_type', 'collection_type', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'date_shift_enable', 'grace_hr'], 'safe'],
            [['date_shift_enable', 'grace_hr', 'collection_type', 'org_type', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'bmc_code', 'mcc_plant_code'], 'required'],
            [['grace_hr'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'org_code' => Yii::t('app', 'Organization Code'),
            'org_type' => Yii::t('app', 'Organization Type'),
            'collection_type' => Yii::t('app', 'Collection Type'),
            'm_start_time' => Yii::t('app', 'M Start Time'),
            'e_start_time' => Yii::t('app', 'E Start Time'),
            'm_lock_time' => Yii::t('app', 'M Lock Time'),
            'e_lock_time' => Yii::t('app', 'M Lock Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'date_shift_enable' => Yii::t('app', 'Date Shift Enable'),
            'grace_hr' => Yii::t('app', 'Grace Hr'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
        ];
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'org_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'org_code']);
    }

}
