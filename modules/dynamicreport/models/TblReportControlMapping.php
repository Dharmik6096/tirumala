<?php

namespace app\modules\dynamicreport\models;

use Yii;

/**
 * This is the model class for table "tbl_report_control_mapping".
 *
 * @property integer $mapping_code
 * @property integer $report_code
 * @property integer $control_code
 */
class TblReportControlMapping extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_report_control_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['report_code', 'control_code'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mapping_code' => Yii::t('app', 'Mapping Code'),
            'report_code' => Yii::t('app', 'Report Code'),
            'control_code' => Yii::t('app', 'Control Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblReportControlMappingQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblReportControlMappingQuery(get_called_class());
    }

    public function getControlCode() {
        return $this->hasOne(TblControlList::className(), ['control_code' => 'control_code']);
    }

}
