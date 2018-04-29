<?php

namespace app\modules\staffmanagement\models;

use Yii;

use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_staff_addition_deduction".
 *
 * @property integer $tr_no
 * @property double $amount
 * @property string $app_from_date
 * @property string $created_at
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property integer $installment_no
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $remark
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $tr_date
 * @property integer $type
 * @property string $updated_at
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $staff_member_code
 * @property string $sub_center_code
 * @property string $union_code
 * @property string $updated_by
 *
 * @property TblUnions $unionCode
 * @property TblUsers $deletedBy
 * @property TblDcs $dcsCode
 * @property TblUsers $updatedBy
 * @property TblUsers $createdBy
 * @property TblStaffMember $staffMemberCode
 * @property TblSubCenter $subCenterCode
 * @property TblStaffInstallment[] $tblStaffInstallments
 * @property TblStaffInstallmentHistory[] $tblStaffInstallmentHistories
 */
class TblStaffAdditionDeduction extends \yii\db\ActiveRecord
{
    public $staff_member_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_addition_deduction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['app_from_date', 'tr_date', 'staff_member_code'], 'required'],
            [['created_at', 'deleted_at', 'sync_timestamp', 'tr_date', 'updated_at','staff_member_name'], 'safe'],
            [['installment_no', 'is_active', 'is_delete', 'type'], 'integer'],
            [['app_from_date'], 'string', 'max' => 255],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 200],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 10],
            [['dcs_code'], 'string', 'max' => 9],
            [['staff_member_code'], 'string', 'max' => 20],
            [['sub_center_code'], 'string', 'max' => 11],
            [['union_code'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_no' => Yii::t('app', 'Tr No'),
            'amount' => Yii::t('app', 'Amount'),
            'app_from_date' => Yii::t('app', 'App From Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'installment_no' => Yii::t('app', 'Installment No'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'remark' => Yii::t('app', 'Remark'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'tr_date' => Yii::t('app', 'Tr Date'),
            'type' => Yii::t('app', 'Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffMemberCode()
    {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffInstallments()
    {
        return $this->hasMany(TblStaffInstallment::className(), ['tr_no' => 'tr_no']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffInstallmentHistories()
    {
        return $this->hasMany(TblStaffInstallmentHistory::className(), ['tr_no' => 'tr_no']);
    }

    /**
     * @inheritdoc
     * @return TblStaffAdditionDeductionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStaffAdditionDeductionQuery(get_called_class());
    }
}
