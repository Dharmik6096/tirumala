<?php

namespace app\modules\details\models;

use Yii;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;

/**
 * This is the model class for table "tbl_bank_details".
 *
 * @property integer $detail_code
 * @property string $module_name
 * @property string $module_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $is_default
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property TblBanks $bankCode
 * @property TblBranch $branchCode
 */
class TblBankDetails extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bank_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['branch_code', 'bank_account_no', 'ifsc'], 'required', 'on' => 'bank_selected'],
            [['branch_code', 'bank_account_no', 'ifsc', 'bank_code'], 'required', 'on' => 'additional'],
            /* [['branch_code', 'bank_account_no', 'ifsc'], 'required','when' => function($model) {
              return !empty($this->bank_code)?true:false;
              }, 'whenClient' => "function (attribute, value) { return $('#tblbankdetails-bank_code').val()!==''}"], */
            [['detail_code'], 'integer'],
            [['module_name', 'module_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at', 'is_default', 'is_active'], 'safe'],
//            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
//            return $model->module_name == $this->module_name;
//        }],
            [['bank_account_no'], 'CheckDuplicate'],
            [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
            [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }],
            [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code']],
            [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'detail_code' => Yii::t('app', 'Detail Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'is_default' => Yii::t('app', 'Is Default'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @inheritdoc
     * @return TblBankDetailsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblBankDetailsQuery(get_called_class());
    }

    public function setModel($module, $module_code, $default = 1) {
        $this->module_name = $module;
        $this->module_code = $module_code;
        $this->detail_code = Yii::$app->general->getCodeAutoIncrement($this);
        $this->is_active = 1;
        $this->is_default = $default;
    }

    public function CheckDuplicate($attribute, $param) {
        if (!empty($this->bank_account_no)) {
            $data = $this->find()->where(['or', ['bank_account_no' => $this->bank_account_no], ['bank_account_no' => \Yii::$app->general->encryptData($this->bank_account_no)]])
                            ->andWhere(['or', ['ifsc' => $this->ifsc], ['ifsc' => \Yii::$app->general->encryptData($this->ifsc)]])
                            ->andWhere(['<>', 'detail_code', $this->detail_code])
                            ->andWhere(['is_active' => 1])->one();
            if (!empty($data)) {
                $this->addError($attribute, Yii::t('app/validation', 'Bank Account No has already been taken.'));
            }
        }
    }

}
