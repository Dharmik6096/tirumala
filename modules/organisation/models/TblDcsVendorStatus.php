<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_vendor_status".
 *
 * @property string $dcs_vendor_code
 * @property string $union_code
 * @property string $customer_type
 * @property string $customer_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblDcsVendorStatus extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_vendor_status';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_vendor_code'], 'required'],
            [['is_active', 'originating_type'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['dcs_vendor_code'], 'safe'],
            [['union_code'], 'safe'],
            [['customer_type', 'customer_code'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_vendor_code' => Yii::t('app', 'Dcs Vendor Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
