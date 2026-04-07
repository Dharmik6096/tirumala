<?php

namespace app\modules\assetmanagement\models;

use Yii;
use yii\validators\EmailValidator;

/**
 * This is the model class for table "tbl_asset_cluster_vendor_info".
 *
 * @property integer $asset_cluster_vendor_info_code
 * @property string $asset_code
 * @property string $serial_number
 * @property string $cluster_email
 * @property string $cluster_mobile
 * @property string $vendor_email
 * @property string $vendor_mobile
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
class TblAssetClusterVendorInfo extends \app\models\ChildModel {

    public $import_union_code, $import_eipl_code, $import_key_pattern;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_cluster_vendor_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_code', 'serial_number', 'cluster_email', 'cluster_mobile', 'vendor_email', 'vendor_mobile', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['asset_code'], 'required', 'on' => ['assetClusterVendorInfo']],
            [['asset_code'], 'setImportData'],
            [['cluster_email', 'vendor_email'], 'validateClusterEmail'],
            [['cluster_mobile', 'vendor_mobile'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_cluster_vendor_info_code' => Yii::t('app', 'Asset Cluster Vendor Info Code'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'cluster_email' => Yii::t('app', 'Cluster Email'),
            'cluster_mobile' => Yii::t('app', 'Cluster Mobile'),
            'vendor_email' => Yii::t('app', 'Vendor Email'),
            'vendor_mobile' => Yii::t('app', 'Vendor Mobile'),
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

    public function setImportData($attribute) {
        $existData = TblAssetDetail::find()->where(['asset_code' => $this->asset_code, 'serial_number' => $this->serial_number])->one();
        if (empty($existData)) {
            $this->addError($attribute, Yii::t('app', 'Asset Detail is not available.'));
        }
    }

    public function validateClusterEmail($attribute, $params) {
        $emails = explode(',', $this->$attribute);
        $validator = new EmailValidator();
        foreach ($emails as $email) {
            $email = trim($email);
            if (!$validator->validate($email)) {
                $this->addError($attribute, Yii::t('app', '{email} is not a valid email.', ['email' => $email]));
            }
        }
    }

}
