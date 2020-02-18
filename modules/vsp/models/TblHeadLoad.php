<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\models\User;
use app\modules\dcsaccounting\models\TblFinancialYear;

/**
 * This is the model class for table "tbl_head_load".
 *
 * @property string $head_load_code
 * @property string $created_at
 * @property string $criteria_description
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property integer $criteria_type_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblHeadLoadCriteria $criteriaTypeCode
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblUnions $unionCode
 * @property User $updatedBy
 * @property TblHeadLoadApplicability[] $tblHeadLoadApplicabilities
 * @property TblHeadLoadApplicabilityHistory[] $tblHeadLoadApplicabilityHistories
 * @property TblHeadLoadTransaction[] $tblHeadLoadTransactions
 * @property TblHeadLoadTransactionHistory[] $tblHeadLoadTransactionHistories
 */
class TblHeadLoad extends \app\models\ChildModel {

    public static function tableName() {
        return 'tbl_head_load';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['head_load_code', 'criteria_description', 'fix_value', 'min_km', 'min_qty', 'max_qty'], 'required', 'on' => 'create'],
            [['created_at',  'updated_at', 'criteria_type_code'], 'safe'],
            [['is_active',], 'integer'],
            [['head_load_code'], 'string', 'max' => 35],
            [['criteria_description'], 'string', 'max' => 200],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code'], 'string', 'max' => 9],
            [['union_code'], 'string', 'max' => 3],
            [['criteria_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoadCriteria::className(), 'targetAttribute' => ['criteria_type_code' => 'code']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['originating_type'], 'default', 'value' => NULL],
            [['fix_value', 'min_km', 'min_qty', 'max_qty'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'criteria_description' => Yii::t('app', 'Criteria Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'criteria_type_code' => Yii::t('app', 'Criteria Type'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'fix_value' => Yii::t('app', 'Basic Value'),
            'min_km' => Yii::t('app', 'Min Distance'),
            'min_qty' => Yii::t('app', 'Min Qty'),
            'max_qty' => Yii::t('app', 'Upper Limit'),
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
    public function getCriteriaTypeCode() {
        return $this->hasOne(TblHeadLoadCriteria::className(), ['code' => 'criteria_type_code']);
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
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadApplicabilities() {
        return $this->hasMany(TblHeadLoadApplicability::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadApplicabilityHistories() {
        return $this->hasMany(TblHeadLoadApplicabilityHistory::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadTransactions() {
        return $this->hasMany(TblHeadLoadTransaction::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadTransactionHistories() {
        return $this->hasMany(TblHeadLoadTransactionHistory::className(), ['head_load_code' => 'head_load_code']);
    }


    public function getOrganizationList() {
        $out = '';
        foreach ($this->tblHeadLoadApplicabilities as $row) {
//            echo $row->dcs_code;
            if ($row->dcs_code)
                $out .= $row->dcsCode->dcs_name . '<br>';
            else
                $out .= $row->subCenterCode->sub_center_name . '<br>';
        }
        return $out;
    }

    public function getCode() {

        $year = new TblFinancialYear;
        $year = $year->getYear();
        $year = ($year) ? $year->code : '';
        if ($year == '') {
            return NULL;
        }
        $union = '000';
        $dcs = '0000';

        switch (Yii::$app->session->get('organizations_type')) {
            case 'UNION':
                $union = Yii::$app->session->get('organizations_code');
                break;
            case 'DCS':
                $dcs = Yii::$app->session->get('organizations_code');
                break;
        }

        // $code = $union . $dcs . '/' . str_replace('-', '', $year) . '/';
        $code = $union . $dcs . '/' . $year . '/';
        $len = strlen($code);
        $val = (new \yii\db\Query)
                ->select(["MAX(CAST(trim(SUBSTRING(`head_load_code` FROM " . $len . " +1)) AS UNSIGNED)) as head_load_code"])
                ->from('tbl_head_load')
                ->where('trim(SUBSTRING(head_load_code, 1,' . $len . ')) ="' . trim($code) . '"')
                ->one();
        $code1 = (int) $val['head_load_code'] + 1;

        // $value = $code . str_pad($code1, 7, '0', STR_PAD_LEFT);
        $value = $code . $code1;
        return $value;
    }

    public function disableHeadLoad($type = '') {
        return TRUE;
        $code = $this->head_load_code;
        $originate = substr($code, 0, 3);
        $dcs = substr($code, 3, 4);
        if ($originate == Yii::$app->session->get('Unions') && $dcs == '0000') {
            if ($type == 1) {
                return true;
            }
        }

        return FALSE;
    }

}
