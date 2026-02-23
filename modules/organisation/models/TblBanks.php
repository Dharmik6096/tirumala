<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblDistricts;
use app\models\ChildModel;
use app\modules\dcsaccounting\models\TblLedgers;
use app\modules\organisation\models\TblBanksDistrictsMapping;
use app\modules\syncutility\models\TblSentbox;
use yii\base\UserException;

/**
 * This is the model class for table "tbl_banks".
 *
 * @property string $bank_code
 * @property integer $ac_no_length
 * @property string $bank_name
 * @property string $local_name
 * @property boolean $checked_ac_no
 * @property string $created_at
 * @property integer $is_active
 * @property boolean $nationalized_bank
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $short_name
 * @property string $local_short_name
 * @property string $is_alpha_acno_allow
 *
 * @property TblBanksDistrictsMapping[] $tblBanksDistrictsMappings
 * @property TblDistricts[] $districtCodes
 */
class TblBanks extends ChildModel {

    public $state;
    public $district;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_banks';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['originating_org_code', 'originating_org_type', 'originating_type', 'ledger_code'], 'safe'],
                [['bank_name', 'ac_no_length', 'checked_ac_no'], 'required'],
                [['bank_name'], 'getBankCode', 'on' => 'importCsv'],
                [['bank_code'], 'required', 'except' => 'importCsv'],
                [['bank_code', 'bank_name', 'short_name'], 'unique'],
                [['bank_code'], 'validateBankCode', 'on' => 'importCsv'],
                ['bank_code', 'compare', 'compareValue' => '0000', 'operator' => '!=', 'type' => 'number', 'message' => Yii::t('app/validation', '{attribute} can not be "0000".')],
                [['bank_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['is_active', 'local_short_name', 'old_bank_code'], 'safe'],
                [['local_name', 'local_short_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
                [['district'], 'validateState', 'skipOnEmpty' => false, 'except' => 'importCsv'],
                [['ac_no_length'], 'integer', 'message' => Yii::t('app/validation', '{attribute} must be a digit e.g. "11"')],
                [['ac_no_length'], 'vaildateAcNoLength'],
            //[['ac_no_length'], 'integer', 'max' => 2, 'min' => 1, 'tooBig' => 'Please enter a valid A/C No Length', 'tooSmall' => 'Please enter a valid A/C No Length'],
            [['checked_ac_no', 'nationalized_bank', 'is_alpha_acno_allow'], 'boolean'],
                [['created_at', 'nationalized_bank', 'updated_at', 'state', 'district', 'is_alpha_acno_allow'], 'safe'],
                [['bank_code'], 'string', 'max' => 4],
                [['bank_name'], 'string', 'max' => 100],
                [['old_bank_code'], 'string', 'max' => 10],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['is_alpha_acno_allow'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'boolean_value');
                }, 'on' => 'importCsv'],
                [['ledger_code'], 'validateLedgerType', 'on' => 'importCsv'],
        ];
    }

    public function validateState($attribute, $params) {
        if ($this->nationalized_bank != 1) {
            if (empty($this->$attribute)) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' cannot be blank.'));
                return false;
            }
        }
    }

    public function validateBankCode($attribute, $params) {
        if (!preg_match('/^[0-9]*$/', $this->$attribute)) {
            $this->addError($attribute, Yii::t('app/validation', 'Please enter valid ' . $this->getAttributeLabel($attribute) . '. e.g "0025"'));
            return false;
        }
    }

    public function vaildateAcNoLength($attribute) {
        if (!preg_match('/^[0-9]{1,2}$/', $this->$attribute)) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' should contain upto 2 digit only .'));
        }
    }

    public static function validateAccountNo($bankCode, $attribute) {
        $bankDetail = self::find()->where(['bank_code' => $bankCode, 'is_alpha_acno_allow' => 1])->select('is_alpha_acno_allow')->one();

        if (!empty($bankDetail)) {
            if (!preg_match('/^[a-zA-Z0-9 ]+$/', $attribute)) {
                return Yii::t('app/validation', 'Bank account number should not contain the special characters');
            }
        } else {
            if (!preg_match('/^[0-9][0-9\/]*$/', $attribute)) {
                return Yii::t('app/validation', 'Bank account number can only contain digits and "/" and can only start with a digit');
            }
        }

        $bank = self::find()->where(['bank_code' => $bankCode, 'checked_ac_no' => 1])->select('ac_no_length')->one();
        if (isset($bank)) {
            if (strlen($attribute) != $bank->ac_no_length) {
                return Yii::t('app/validation', 'Bank account number must contain ' . $bank->ac_no_length . ' digits');
            }
        }

        return TRUE;
    }

    public function getBankCode($attribute, $params) {
        if (!empty($this->bank_name)) {
            $this->bank_code = $this->getCode();
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bank_code' => Yii::t('app', 'Bank Code'),
            'ac_no_length' => Yii::t('app', 'Ac No Length'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'local_name' => Yii::t('app', 'Local Name'),
            'checked_ac_no' => Yii::t('app', 'Checked Ac No'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'nationalized_bank' => Yii::t('app', 'Nationalized Bank'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'short_name' => Yii::t('app', 'Short Name'),
            'local_short_name' => Yii::t('app', 'Local Short Name'),
            'is_alpha_acno_allow' => Yii::t('app', 'Allow Alpha A/C no.'),
            'ledger_code' => Yii::t('app', 'Ledger'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBanksDistrictsMappings() {
        return $this->hasMany(TblBanksDistrictsMapping::className(), ['bank_code' => 'bank_code'])->andwhere(['is_active' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCodes() {
        return $this->hasMany(TblDistricts::className(), ['district_code' => 'district_code'])->viaTable('tbl_banks_districts_mapping', ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranches() {
        return $this->hasMany(TblBranch::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBranchHistories() {
        return $this->hasMany(TblBranchHistory::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcs() {
        return $this->hasMany(TblDcs::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsHistories() {
        return $this->hasMany(TblDcsHistory::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederations() {
        return $this->hasMany(TblFederations::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblFederationsHistories() {
        return $this->hasMany(TblFederationsHistory::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnions() {
        return $this->hasMany(TblUnions::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblUnionsHistories() {
        return $this->hasMany(TblUnionsHistory::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @inheritdoc
     * @return TblBanksQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblBanksQuery(get_called_class());
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(convert(int,bank_code)) as bank_code"])->one();
        return str_pad((int) $data['bank_code'] + 1, 4, '0', STR_PAD_LEFT);
    }

    public function getDistrictUsed($bankCode, $districtCode) {

        $modelDis = new TblBanksDistrictsMapping();
        $value = $modelDis->getDistrictUsed($bankCode, $districtCode);

        return $value;
    }

    public function getRecode($bankCode) {

        return $this->find()->where(['bank_code' => $bankCode, 'is_active' => 1])->one();
    }

    public function getDistrictList() {
        $out = '';
        foreach ($this->tblBanksDistrictsMappings as $row) {
            $out .= $row->districtCode->district_name . ', ';
        }
        return $out;
    }

    public function getLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'ledger_code']);
    }

    public function validateLedgerType($attribute, $params) {
        if (!empty($this->$attribute)) {
            $ledger = TblLedgers::find()->alias('l')
                    ->innerJoin('tbl_ledger_groups lg', 'l.ledger_group_code = lg.ledger_group_code')
                    ->innerJoin('tbl_ledger_types lt', 'lg.ledger_type_code = lt.ledger_type_code')
                    ->andWhere(['l.is_active' => 1])
                    ->andWhere(['LOWER(lt.ledger_type_name)' => 'bank'])
                    ->one();
            if (empty($ledger)) {
                $this->addError($attribute, Yii::t('app/validation', 'Invalid Ledger Code. Only ledgers with voucher type "Bank" are allowed.'));
                return false;
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $unions = TblUnions::findAll(['is_active' => 1]);
            foreach ($unions as $union) {
                $union_code = $union->union_code;
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $union_code);
                $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
                $sentbox = new TblSentbox();
                $sentbox->source_org_id = $union_code;
                if (!($sentbox->setSentboxBatch($this, $flag, $sentboxArray))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $unions = TblUnions::findAll(['is_active' => 1]);
            foreach ($unions as $union) {
                $union_code = $union->union_code;
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $union_code);
                $sentbox = new TblSentbox();
                $sentbox->source_org_id = $union_code;
                if (!($sentbox->setSentboxBatch($this, 'DELETE', $sentboxArray))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

}
