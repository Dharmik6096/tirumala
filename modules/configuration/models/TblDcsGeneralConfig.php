<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_dcs_general_config".
 *
 * @property integer $code
 * @property integer $allow_multiple_voters
 * @property string $backup_path
 * @property integer $backup_per_shift
 * @property string $created_at
 * @property string $created_by
 * @property integer $election_alert_day
 * @property integer $election_term
 * @property integer $is_backup_user_choice
 * @property integer $is_backup_on_closing
 * @property integer $is_backup_disbursement
 * @property integer $max_share_buy
 * @property integer $min_share_req
 * @property integer $nos_of_reminders
 * @property integer $purchase_rate_with_tax
 * @property integer $sale_rate_with_tax
 * @property integer $share_issued
 * @property string $share_unit_cost
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property integer $milk_dispatch_in
 * @property integer $headload_km
 * @property integer $milk_dispatch_quantity_mode
 * @property integer $milk_receipt_quantity_mode
 * @property string $product_sale_in_cash
 * @property string $share_amount_editable
 * @property string $product_billing
 * @property string $billing_zero_amount_auto
 *
 * @property TblUnions $unionCode
 */
class TblDcsGeneralConfig extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_general_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['share_unit_cost'], 'required', 'on' => 'shares'],
            [['milk_dispatch_quantity_mode', 'milk_receipt_quantity_mode', 'milk_dispatch_in'], 'required', 'on' => 'disprecp'],
            [['backup_path'], 'string', 'max' => 255],
            [['share_unit_cost', 'min_share_req', 'share_issued', 'max_share_buy', 'headload_km'], 'double', 'min' => 0],
            [['share_unit_cost', 'min_share_req', 'share_issued', 'max_share_buy'], 'default', 'value' => '0'],
            [['election_term', 'election_alert_day', 'nos_of_reminders', 'backup_per_shift'], 'number', 'min' => 0],
            [['election_term', 'election_alert_day', 'nos_of_reminders', 'backup_per_shift', 'headload_km'], 'default', 'value' => '0'],
            [['union_code'], 'string', 'max' => 3],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['allow_multiple_voters', 'backup_per_shift', 'election_alert_day', 'election_term', 'is_backup_user_choice', 'is_backup_on_closing', 'is_backup_disbursement', 'max_share_buy', 'min_share_req', 'nos_of_reminders', 'purchase_rate_with_tax', 'sale_rate_with_tax', 'share_issued', 'milk_dispatch_in', 'headload_km', 'milk_dispatch_quantity_mode', 'milk_receipt_quantity_mode'], 'integer'],
            [['backup_path', 'created_by', 'updated_by', 'union_code', 'product_sale_in_cash', 'share_amount_editable', 'product_billing', 'billing_zero_amount_auto'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['union_code'], 'configShares', 'skipOnEmpty' => false, 'on' => 'shares'],
            [['union_code'], 'configBackup', 'skipOnEmpty' => false, 'on' => 'backup'],
            [['union_code'], 'configElection', 'skipOnEmpty' => false, 'on' => 'electionConfig'],
            [['union_code'], 'configProductSale', 'skipOnEmpty' => false, 'on' => 'productSale'],
            [['union_code'], 'configDisprecp', 'skipOnEmpty' => false, 'on' => 'disprecp'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'allow_multiple_voters' => Yii::t('app', 'Allow multiple voters from same family'),
            'backup_path' => Yii::t('app', 'Auto Backup Path'),
            'backup_per_shift' => Yii::t('app', 'Number of backup per day'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'election_alert_day' => Yii::t('app', 'Alert (Day Before term end)'),
            'election_term' => Yii::t('app', 'Election Term (Month)'),
            'is_backup_user_choice' => Yii::t('app', ' '),
            'is_backup_on_closing' => Yii::t('app', ' '),
            'is_backup_disbursement' => Yii::t('app', ' '),
            'max_share_buy' => Yii::t('app', 'Maximum Amount of shares a Member can buy:'),
            'min_share_req' => Yii::t('app', 'Minimum amount of Share required to be baught to be a member:'),
            'nos_of_reminders' => Yii::t('app', 'No. Of Reminders (reminders end when election entry has been created)'),
            'purchase_rate_with_tax' => Yii::t('app', 'Inclusive Tax'),
            'sale_rate_with_tax' => Yii::t('app', 'Inclusive Tax'),
            'share_issued' => Yii::t('app', 'Share to be issued in multiple of:'),
            'share_unit_cost' => Yii::t('app', 'Share Unit Cost:'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union Code'),
            'milk_dispatch_in' => Yii::t('app', 'Milk Dispatch In'),
            'headload_km' => Yii::t('app', 'Head Load Km.'),
            'milk_dispatch_quantity_mode' => Yii::t('app', 'Milk Dispatch Quantity Mode'),
            'milk_receipt_quantity_mode' => Yii::t('app', 'Milk Receipt Quantity Mode'),
            'product_sale_in_cash' => Yii::t('app', ' '),
            'share_amount_editable' => Yii::t('app', 'Share Amount Editable'),
            'product_billing' => Yii::t('app', ' '),
            'billing_zero_amount_auto' => Yii::t('app', ' '),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getData() {
        return $this->find()->where(['union_code' => $this->union_code])->one();
    }

    public function configShares($attribute, $params) {
        if (empty($this->union_code)) {
            $this->addError('share_unit_cost', 'Something went wrong');
        }
    }

    public function configProductSale($attribute, $params) {
        if (empty($this->union_code)) {
            $this->addError('purchase_rate_with_tax', 'Something went wrong');
        }
    }

    public function configElection($attribute, $params) {
        if (empty($this->union_code)) {
            $this->addError('election_term', 'Something went wrong');
        }
    }

    public function configBackup($attribute, $params) {
        if (empty($this->union_code)) {
            $this->addError('backup_path', 'Something went wrong');
        }
    }

    public function configDisprecp($attribute, $params) {
        if (empty($this->union_code)) {
            $this->addError('milk_dispatch_in', 'Something went wrong');
        }
    }

}
