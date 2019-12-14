<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_customer_type".
 *
 * @property integer $customer_type_code
 * @property string $customer_type
 * @property string $customer_desc
 * @property string $code_prefix
 * @property integer $code_length
 * @property integer $is_organisation
 * @property string $union_code
 * @property integer $is_active
 */
class TblCustomerType extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_type', 'customer_desc', 'code_prefix', 'union_code'], 'string'],
            [['code_length', 'is_organisation', 'is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'customer_type_code' => Yii::t('app', 'Customer Type Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_desc' => Yii::t('app', 'Customer Desc'),
            'code_prefix' => Yii::t('app', 'Code Prefix'),
            'code_length' => Yii::t('app', 'Code Length'),
            'is_organisation' => Yii::t('app', 'Is Organisation'),
            'union_code' => Yii::t('app', 'Union Code'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    public function getCustomerType() {
//        $query = $this->find()->select(['customer_type', 'customer_desc'])->where(['is_active' => 1]);
        $query = $this->find()->where(['is_active' => 1]);
        $vendor = $query->orderBy(['is_organisation' => SORT_ASC, 'customer_desc' => SORT_ASC])->all();
        $customerType = ArrayHelper::map($vendor, 'customer_type', 'customer_desc');
//        asort($customerType, SORT_NATURAL | SORT_FLAG_CASE);
        return $customerType;
    }

}
