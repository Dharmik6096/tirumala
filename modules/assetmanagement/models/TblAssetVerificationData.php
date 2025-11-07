<?php

namespace app\modules\assetmanagement\models;

use app\modules\organisation\models\TblUnions;
use app\modules\staffmanagement\models\TblStaffMemberDesignation;
use Yii;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_asset_verification_data".
 *
 * @property integer $asset_verification_code
 * @property string $asset_group_code
 * @property string $asset_code
 * @property string $serial_number
 * @property string $manufacturer_serial_number
 * @property integer $is_verified
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
class TblAssetVerificationData extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_asset_verification_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asset_code', 'serial_number'], 'required', 'on' => 'importCsv'],
            [['asset_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAssetMaster::className(), 'targetAttribute' => ['asset_code' => 'asset_code']],
            [['asset_code'], 'validateUser', 'on' => ['importCsv']],
            [['serial_number'], 'assignAutoData', 'skipOnError' => true, 'on' => 'importCsv'],
            [['is_verified'], 'integer'],
            [['asset_group_code', 'asset_code', 'serial_number', 'manufacturer_serial_number', 'is_verified', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'customer_type', 'customer_code', 'verification_date', 'asset_verification_code', 'user_code', 'date_of_cap', 'remarks', 'scan_status', 'physical_verification_status'], 'safe'],
            [['asset_group_code', 'asset_code'], 'string', 'max' => 12],
            [['serial_number', 'manufacturer_serial_number'], 'string', 'max' => 50],
            [['is_verified'], 'default', 'value' => 0, 'on' => 'importCsv'],    
//            [['asset_code'], 'unique', 'targetAttribute' => ['asset_code', 'serial_number'], 'message' => 'The combination of asset Code and Serial Number has already been taken.'],
            [['asset_verification_code'], 'unique'],
            
            [['date_of_cap'], 'convertDateDot', 'on' => ['importCsv']],
            [['date_of_cap'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['date_of_cap'], 'convertDate', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'asset_verification_code' => Yii::t('app', 'Asset Verification Code'),
            'asset_group_code' => Yii::t('app', 'Asset Group Code'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'manufacturer_serial_number' => Yii::t('app', 'Manufacturer Serial Number'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'user_code' => Yii::t('app', 'Employ ID'),
            'date_of_cap' => Yii::t('app', 'Date Of Cap'),
            'remarks' => Yii::t('app', 'Remarks'),
            'scan_status' => Yii::t('app', 'SCAN STATUS'),
            'physical_verification_status' => Yii::t('app', 'Physical Verification Status'),
        ];
    }

    public function getAssetGroupCode() {
        return $this->hasOne(TblAssetGroup::className(), ['asset_group_code' => 'asset_group_code']);
    }

    public function getAssetCode() {
        return $this->hasOne(TblAssetMaster::className(), ['asset_code' => 'asset_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getStoreLocType() {
        return $this->hasOne(TblStoreLocationType::className(), ['slt_code' => 'customer_type']);
    }

    public function assignAutoData($attribute, $params) {
        if (empty($this->getErrors())) {
            $assetCode = $this->assetCode;
            $this->asset_group_code = $assetCode->asset_group_code;
            $this->union_code = $assetCode->union_code;

            $assetTransactionData = TblAssetTransaction::find()->select(['to_type','to_dest'])->where(['serial_number' => $this->serial_number, 'asset_code' => $this->asset_code])->orderBy(['created_at' => SORT_DESC])->one();
            if (!empty($this->user_code)) {
                $this->customer_type = 'USER';
                $this->customer_code = $this->user_code;
            } elseif (!empty($assetTransactionData)) {
                $this->customer_type = $assetTransactionData->to_type;
                $referenceCode = TblStoreLocation::find()->select(['reference_code'])->where(['store_location_code' => $assetTransactionData->to_dest, 'store_location_type' => $assetTransactionData->to_type, 'is_active' => '1'])->scalar();
                $this->customer_type = $this->storeLocType->slt_name;
                $this->customer_code = $referenceCode;
            } else{
                $this->addError($attribute, Yii::t('app/validation', 'Enter a valid serial number for asset.'));
            }

            $asset_verification_code_mandatory = Yii::$app->general->getUnionConfiguration($this->union_code, 'asset_verification_code_mandatory', 'PORTAL');
            $pattern_of_verification_code = Yii::$app->general->getUnionConfiguration($this->union_code, 'pattern_of_verification_code', 'PORTAL');
            if($asset_verification_code_mandatory == 1 && !empty($pattern_of_verification_code)){
                $this->asset_verification_code = $pattern_of_verification_code .'-'. rand(10000, 99999);
            }

        }
        
    }

    public function setChildTable($model, &$modelSave) {
        if (empty($this->getErrors())) {
            $assetDetailData = TblAssetDetail::find()->where(['asset_code' => $model->asset_code, 'serial_number' => $model->serial_number])->one();
            if(!empty($assetDetailData)){
                $assetDetailhistoryModel = new TblAssetDetailHistory();
                Yii::$app->operation->history($assetDetailData, $assetDetailhistoryModel, UPDATE);
                array_push($modelSave, $assetDetailhistoryModel);
                $assetDetailData->is_verified = 0;
                $assetDetailData->verification_date = NULL;
                array_push($modelSave, $assetDetailData);
            }
        }
    }
    
    public function validateUser($attribute) {
        if (!empty($this->user_code)) {
            $primaryUser = User::find()->where(['or', ['employee_id' => $this->user_code], ['user_code' => $this->user_code]])->andWhere(['is_active' => 1])->one();
            if ($primaryUser) {
                $this->user_code = $primaryUser->user_code;
            } else {
                $this->addError('user_code', Yii::t('app/validation', 'Invalid User Code.'));
                return false;
            }
        }
        $exists = $this->find()->where(['asset_code' => $this->asset_code, 'serial_number' => $this->serial_number]);
        if (!empty($this->user_code)) {
            $exists = $exists->andWhere(['user_code' => $this->user_code])->one();
            if (!empty($exists)) {
                $this->addError($attribute, Yii::t('app/validation', 'The combination of asset Code, Serial Number and User Code has already been taken.'));
                return false;
            }
        } else {
            $exists = $exists->one();
            if (!empty($exists)) {
                $this->addError($attribute, Yii::t('app/validation', 'The combination of asset Code and Serial Number has already been taken.'));
                return false;
            }
        }
        return true;
    }
    
    public function convertDateDot() {
        try {
            $this->date_of_cap = Yii::$app->controls->view_date($this->date_of_cap, 'php:d.m.Y');
        } catch (\Throwable $e) {
            $this->date_of_cap = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->date_of_cap = !empty($this->date_of_cap) ? Yii::$app->controls->view_date($this->date_of_cap, 'php:Y-m-d') : NULL;
        }
    }

}
