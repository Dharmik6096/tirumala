<?php

namespace app\modules\dynamicreport\models;

use Yii;

/**
 * This is the model class for table "tbl_report_control_mapping".
 *
 * @property integer $mapping_code
 * @property integer $report_code
 * @property integer $control_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
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
            [['report_code', 'control_code', 'is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'string'],
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
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
