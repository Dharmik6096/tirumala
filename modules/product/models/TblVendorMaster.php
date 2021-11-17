<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_vendor_master".
 *
 * @property string $vendor_master_code
 * @property string $vendor_code
 * @property string $vendor_name
 * @property string $pan_no
 * @property string $adhar_no
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
class TblVendorMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vendor_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vendor_code', 'vendor_name', 'adhar_no', 'pan_no'], 'required'],
                [['vendor_code'], 'integer'],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }],
                [['adhar_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAadharcard($this, $attribute, $params);
                }],
                [['created_at', 'updated_at', 'vendor_name', 'pan_no', 'adhar_no'], 'safe'],
                [['pan_no', 'adhar_no', 'vendor_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vendor_master_code' => Yii::t('app', 'Vendor Master Code'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'vendor_name' => Yii::t('app', 'Vendor Name'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'adhar_no' => Yii::t('app', 'Adhar No'),
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
