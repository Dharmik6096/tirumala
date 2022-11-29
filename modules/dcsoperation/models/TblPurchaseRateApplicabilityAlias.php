<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_purchase_rate_applicability_alias".
 *
 * @property integer $rate_app_alias_code
 * @property integer $purchase_rate_code
 * @property string $wef_date
 * @property integer $shift_code
 * @property string $dcs_code
 * @property string $union_code
 * @property integer $rate_type
 * @property integer $rate_gen_method_code
 * @property string $download_date_time
 * @property integer $is_download
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
class TblPurchaseRateApplicabilityAlias extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_purchase_rate_applicability_alias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['purchase_rate_code', 'shift_code', 'rate_type', 'rate_gen_method_code', 'is_download', 'is_active', 'originating_type'], 'integer'],
            [['wef_date', 'download_date_time', 'created_at', 'updated_at', 'error_desc', 'status'], 'safe'],
            [['is_download'], 'required'],
            [['dcs_code'], 'string', 'max' => 12],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_app_alias_code' => Yii::t('app', 'Rate App Alias Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'rate_type' => Yii::t('app', 'Rate Type'),
            'rate_gen_method_code' => Yii::t('app', 'Rate Gen Method Code'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
            'is_download' => Yii::t('app', 'Is Download'),
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

}
