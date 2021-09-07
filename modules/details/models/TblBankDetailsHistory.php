<?php

namespace app\modules\details\models;

use Yii;

/**
 * This is the model class for table "tbl_bank_details_history".
 *
 * @property integer $id
 * @property integer $detail_code
 * @property string $module_name
 * @property string $module_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $is_active
 */
class TblBankDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bank_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'detail_code', 'firstname', 'lastname', 'surname', 'is_verified'], 'safe'],
            [['module_name', 'module_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'created_by', 'updated_by', 'operation_type'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'is_active', 'is_default', 'history_created_by', 'remarks'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'detail_code' => Yii::t('app', 'Detail Code'),
                  'module_name' => Yii::t('app', 'Module Name'),
                  'module_code' => Yii::t('app', 'Module Code'),
                  'bank_code' => Yii::t('app', 'Bank Code'),
                  'branch_code' => Yii::t('app', 'Branch Code'),
                  'bank_account_no' => Yii::t('app', 'Bank Account No'),
                  'ifsc' => Yii::t('app', 'Ifsc'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'updated_by' => Yii::t('app', 'Updated By'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'operation_type' => Yii::t('app', 'Operation Type'), */
        ];
    }

}
