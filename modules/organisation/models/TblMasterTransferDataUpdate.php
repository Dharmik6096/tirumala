<?php

namespace app\modules\organisation\models;

use app\models\ChildModel;
use app\modules\dcsoperation\models\TblShift;
use Yii;

/**
 * This is the model class for table "tbl_master_transfer_data_update".
 *
 * @property integer $master_transfer_data_update_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property integer $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMasterTransferDataUpdate extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_master_transfer_data_update';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','from_date','from_shift','to_date','to_shift','status','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
            [['union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','from_date','from_shift','to_date','to_shift'], 'required'],
            [['status'], 'default', 'value' => 0],
            [['from_date'],'ValidateDate']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'master_transfer_data_update_code' => Yii::t('app', 'Master Transfer Data Update Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'status' => Yii::t('app', 'Is Updated?'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function ValidateDate() {
        if (empty($this->getErrors())) {
            if ($this->from_date > $this->to_date) {
                $this->addError('from_date', Yii::t('app/validation', 'From Date can not be greater than To Date.'));
                return false;
            } else if ($this->from_date == $this->to_date && $this->from_shift > $this->to_shift) {
                $this->addError('from_date', Yii::t('app/validation', 'From Shift can not be greater than to To Shift when dates are the same.'));
                return false;
            }
            $wefDate = TblMasterTransfer::find()->select(['wef_date'])->where(['old_dcs_code' => $this->dcs_code, 'master_type' => 'DCS'])->andWhere(['<=', 'wef_date', date('Y-m-d')])->orderBy(['wef_date' => SORT_DESC,'master_transfer_code' => SORT_DESC])->limit(1)->scalar();
            if (!empty($wefDate) && date('Y-m-d', strtotime($this->from_date)) < date('Y-m-d',  strtotime($wefDate))) {
                $this->addError('from_date', Yii::t('app/validation', 'From Date cannot be less than Transfer Date '.date('d-m-Y',strtotime($wefDate)).'.'));
                return false;
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getFromShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShift() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

}
