<?php

namespace app\modules\dynamicreport\models;

use Yii;

/**
 * This is the model class for table "tbl_report_list".
 *
 * @property integer $report_code
 * @property string $report_name
 * @property string $sp_name
 * @property string $sp_param
 * @property string $report_rule
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblReportList extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_report_list';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['report_name', 'sp_name', 'sp_param', 'report_rule', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'report_code' => Yii::t('app', 'Report Code'),
            'report_name' => Yii::t('app', 'Report Name'),
            'sp_name' => Yii::t('app', 'Sp Name'),
            'sp_param' => Yii::t('app', 'Sp Param'),
            'report_rule' => Yii::t('app', 'Report Rule'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblReportListQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblReportListQuery(get_called_class());
    }

    public function getData() {
        return $this->find()->where(['report_code' => $this->report_code, 'is_active' => 1])->one();
    }

}
