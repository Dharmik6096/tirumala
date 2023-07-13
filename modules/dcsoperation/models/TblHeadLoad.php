<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\usermanagement\models\User;
use app\modules\dcsaccounting\models\TblFinancialYear;
/**
 * This is the model class for table "tbl_head_load".
 *
 * @property string $head_load_code
 * @property string $created_at
 * @property string $criteria_description
 * @property string $deleted_at
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $updated_at
 * @property string $created_by
 * @property integer $criteria_type_code
 * @property string $dcs_code
 * @property string $deleted_by
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
class TblHeadLoad extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_head_load';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['head_load_code','criteria_description','criteria_type_code'], 'required'],
            [['created_at', 'deleted_at', 'updated_at','is_delete', 'criteria_type_code'], 'safe'],
            [['is_active', ], 'integer'],
            [['head_load_code'], 'string', 'max' => 20],
            [['criteria_description'], 'string', 'max' => 200],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code'], 'string', 'max' => 9],
            [['union_code'], 'string', 'max' => 3],
            [['criteria_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoadCriteria::className(), 'targetAttribute' => ['criteria_type_code' => 'code']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'criteria_description' => Yii::t('app', 'Criteria Description'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'criteria_type_code' => Yii::t('app', 'Criteria Type'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'union_code' => Yii::t('app', 'Union Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriteriaTypeCode()
    {
        return $this->hasOne(TblHeadLoadCriteria::className(), ['code' => 'criteria_type_code']);
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
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadApplicabilities()
    {
        return $this->hasMany(TblHeadLoadApplicability::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadApplicabilityHistories()
    {
        return $this->hasMany(TblHeadLoadApplicabilityHistory::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadTransactions()
    {
        return $this->hasMany(TblHeadLoadTransaction::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadTransactionHistories()
    {
        return $this->hasMany(TblHeadLoadTransactionHistory::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHeadLoadQuery(get_called_class());
    }
    
    public function getOrganizationList(){
        $out = '';
        foreach ($this->tblHeadLoadApplicabilities as $row) {
//            echo $row->dcs_code;
            if($row->dcs_code)
                $out .= $row->dcsCode->dcs_name . '<br>';
            else
                $out .= $row->subCenterCode->sub_center_name . '<br>';
        }
        return $out;
    }

        public function getCode(){
        
        $year = new TblFinancialYear;
        $year = $year->getCurrentYear();
        
        $union = '000';
        $dcs = '0000';
        
        switch (Yii::$app->session->get('organizations_type')){
            case 'UNION':
                $union = Yii::$app->session->get('organizations_code');
                break;
            case 'DCS':
                $dcs = Yii::$app->session->get('organizations_code');
                break;
        }
        
        $code = $union.$dcs.str_replace('-', '', $year);
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`head_load_code`, length(`head_load_code`) -6)) AS UNSIGNED)) as head_load_code")
                ->from('tbl_head_load')
                ->where('(CAST(trim(SUBSTRING(head_load_code, 1,13)) AS UNSIGNED))="'.trim($code).'"')
                ->one();
        $code1 = (int)$val['head_load_code'] + 1 ;

        $value = $code.str_pad($code1, 7, '0', STR_PAD_LEFT);
        
        return $value;
    }
}
