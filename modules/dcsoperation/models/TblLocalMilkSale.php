<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\dcsoperation\models\TblShift;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_local_milk_sale".
 *
 * @property string $local_milk_sale_code
 * @property double $amount
 * @property string $created_at
 * @property string $deleted_at
 * @property double $discount
 * @property integer $payment_mode
 * @property double $cash
 * @property double $coupon
 * @property double $credit
 * @property double $rate
 * @property string $shift_id_id
 * @property string $updated_at
 * @property string $collection_point_code
 * @property string $created_by
 * @property string $dcs_code
 * @property string $member_code
 * @property integer $milk_class
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblMember $memberCode
 * @property TblAnimalType $milkType
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblLocalMilkSale extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_local_milk_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['datetime_of_sale', 'milk_type_code', 'milk_class', 'shift_code', 'qty', 'qty_mode', 'converted_qty', 'converted_qty_mode', 'rate', 'discount', 'amount', 'credit', 'coupon', 'cash', 'member_code', 'payment_mode', 'union_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'consumer_code', 'consumer_type', 'voucher_code'], 'safe'],
            [['union_code'], 'required', 'on' => ['androidsync']],
            [['amount', 'discount', 'rate'], 'number'],
            [['payment_mode'], 'integer'],
            [['member_code'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code'], 'string', 'max' => 12],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'local_milk_sale_code' => Yii::t('app', 'Local Milk Sale Code'),
            'datetime_of_sale' => Yii::t('app', 'Datetime of Sale'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_class' => Yii::t('app', 'Milk Class'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'qty' => Yii::t('app', 'Quantity'),
            'qty_mode' => Yii::t('app', 'Quantity Mode'),
            'converted_qty' => Yii::t('app', 'Converted Quantity'),
            'converted_qty_mode' => Yii::t('app', 'Converted Quantity Mode'),
            'rate' => Yii::t('app', 'Rate'),
            'discount' => Yii::t('app', 'Discount'),
            'amount' => Yii::t('app', 'Amount'),
            'credit' => Yii::t('app', 'Credit'),
            'coupon' => Yii::t('app', 'Coupon'),
            'cash' => Yii::t('app', 'Cash'),
            'member_code' => Yii::t('app', 'Member Code'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Organization Code'),
            'originating_org_type' => Yii::t('app', 'Originating Organization Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'Extra Column 1'),
            'x_col2' => Yii::t('app', 'Extra Column 2'),
            'x_col3' => Yii::t('app', 'Extra Column 3'),
            'x_col4' => Yii::t('app', 'Extra Column 4'),
            'x_col5' => Yii::t('app', 'Extra Column 5'),
            'consumer_code' => Yii::t('app', 'Consumer Code'),
            'consumer_type' => Yii::t('app', 'Consumer Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkClass() {
        return $this->hasOne(TblMilkClass::className(), ['id' => 'milk_class']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblLocalMilkSaleQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblLocalMilkSaleQuery(get_called_class());
    }

    public function entryType() {
        return [0 => 'Single Per Shift', 1 => 'Multiple Per Shift', 2 => 'Milk Consumer wise entry'];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

}
