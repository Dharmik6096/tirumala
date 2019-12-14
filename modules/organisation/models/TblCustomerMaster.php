<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use yii\helpers\ArrayHelper;
use app\modules\globalmaster\models\TblCustomerType;

/**
 * This is the model class for table "tbl_customer_master".
 *
 * @property string $customer_code
 * @property string $customer_name
 * @property string $address
 * @property integer $is_active
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $local_name
 * @property string $local_address
 * @property string $gst_no
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $customer_type
 * @property string $sap_code
 * @property string $refference_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCustomerMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_name', 'union_code', 'address', 'customer_type'], 'required'],
            [['customer_name', 'address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'local_name', 'local_address', 'gst_no', 'union_code', 'created_by', 'updated_by'], 'safe'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at', 'customer_type', 'sap_code', 'refference_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['is_active'], 'default', 'value' => 1],
            [['gst_no'], 'string', 'max' => 15, 'min' => 15, 'tooLong' => Yii::t('app/validation', '{attribute} must contain 15 digit '),
                'tooShort' => Yii::t('app/validation', '{attribute} must contain 15 digit '), 'skipOnEmpty' => TRUE],
            [['local_name', 'local_short_name', 'local_address'], function ($attribute, $params) {
            Yii::$app->general->vaildateLocalField($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'address' => Yii::t('app', 'Address'),
            'is_active' => Yii::t('app', 'Is Active'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'local_name' => Yii::t('app', 'Local Name'),
            'local_address' => Yii::t('app', 'Local Address'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'sap_code' => Yii::t('app', 'Sap Code'),
            'refference_code' => Yii::t('app', 'Refference Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function getCode() {
        $code_prefix = $this->customerType->code_prefix;
        $code_length = $this->customerType->code_length;
        $data = $this->find()->select(["MAX(CONVERT(INT,RIGHT(customer_code,$code_length))) AS customer_code"])->where(['customer_type' => $this->customer_type])->one();
        return $code_prefix . str_pad((int) $data['customer_code'] + 1, $code_length, '0', STR_PAD_LEFT);
    }

    public function getCustomerWithType($unionCode, $customer_type = '', $notIn = []) {
        $query = $this->find()->where(['union_code' => $unionCode, 'is_active' => 1]);
        if (!empty($customer_type)) {
            $query->andWhere(['customer_type' => $customer_type]);
        }
        if (!empty($notIn)) {
            $query->andWhere(['not in', 'customer_code', $notIn]);
        }
        $data = $query->all();
        $data = ArrayHelper::map($data, 'customer_code', 'customer_name');
        asort($data, SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }

    public function getUnionCustomerList($unionCode) {
        $value = $this->getUnionCustomer($unionCode);
        $value = ArrayHelper::map($value, 'customer_code', 'customer_name');
        return $value;
    }

    public function getUnionCustomer($unionCode = []) {
        $query = $this->find()->select(['customer_code', 'customer_name'])->where(['is_active' => 1]);
        $query->andWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        $query->andFilterWhere(['customer_type' => $this->customer_type]);
        $query->andFilterWhere(['union_code' => $unionCode]);

        return $query->all();
    }

}
