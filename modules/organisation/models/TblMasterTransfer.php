<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\globalmaster\models\TblTransferType;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblCustomerMaster;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_master_transfer".
 *
 * @property integer $master_transfer_code
 * @property string $master_type
 * @property string $transfer_type
 * @property string $old_member_code
 * @property string $new_member_code
 * @property string $old_dcs_code
 * @property string $new_dcs_code
 * @property string $old_bmc_code
 * @property string $new_bmc_code
 * @property string $old_mcc_plant_code
 * @property string $new_mcc_plant_code
 * @property string $plant_code
 * @property string $old_route_code
 * @property string $new_route_code
 * @property string $union_code
 * @property string $wef_date
 * @property integer $status
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMasterTransfer extends \app\models\ChildModel {

    public $ex_member_code, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_master_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['status', 'update_transaction'], 'default', 'value' => 0],
                [['master_type', 'transfer_type', 'union_code', 'plant_code', 'wef_date'], 'required'],
                [['old_member_code', 'customer_type', 'customer_code', 'from_datetime', 'to_datetime', 'from_shift', 'to_shift'], 'safe'],
                [['old_dcs_code', 'old_bmc_code', 'new_bmc_code', 'old_mcc_plant_code', 'new_mcc_plant_code', 'new_route_code'], 'required', 'on' => 'DCS'],
                [['customer_type', 'customer_code', 'old_bmc_code', 'new_bmc_code', 'old_mcc_plant_code', 'new_mcc_plant_code', 'new_route_code'], 'required', 'on' => 'CUSTOMER'],
                [['old_dcs_code', 'new_dcs_code', 'old_bmc_code', 'new_bmc_code', 'old_mcc_plant_code', 'new_mcc_plant_code', 'old_member_code'], 'required', 'on' => 'FARMER'],
                [['master_type', 'transfer_type', 'new_member_code', 'old_dcs_code', 'new_dcs_code', 'old_bmc_code', 'new_bmc_code', 'old_mcc_plant_code', 'new_mcc_plant_code', 'plant_code', 'old_route_code', 'new_route_code', 'union_code', 'created_by', 'updated_by'], 'string'],
                [['wef_date', 'pick_datetime', 'response_datetime', 'created_at', 'updated_at', 'update_transaction', 'plant_code'], 'safe'],
                [['status'], 'integer'],
            // [['ex_member_code'], 'integer', 'min' => 1, 'max' => 1498],
            //  [['ex_member_code'], 'string', 'max' => 4],
            [['from_datetime', 'to_datetime', 'from_shift', 'to_shift'], 'required', 'when' => function ($model) {
                    return $model->update_transaction == '1';
                }, 'whenClient' => "function (attribute, value) {
                        return $('#tblmastertransfer-update_transaction').is(':checked');
                }", 'on' => ['DCS']],
                [['master_type'], 'checkMember', 'on' => 'FARMER'],
                [['master_type'], 'checkRequest', 'on' => ['FARMER', 'DCS', 'CUSTOMER']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'master_transfer_code' => Yii::t('app', 'Master Transfer Code'),
            'master_type' => Yii::t('app', 'Master Type'),
            'transfer_type' => Yii::t('app', 'Transfer Type'),
            'old_member_code' => Yii::t('app', 'Member'),
            'new_member_code' => Yii::t('app', 'Member'),
            'old_dcs_code' => Yii::t('app', 'DCS'),
            'new_dcs_code' => Yii::t('app', 'DCS'),
            'old_bmc_code' => Yii::t('app', 'BMC'),
            'new_bmc_code' => Yii::t('app', 'BMC'),
            'old_mcc_plant_code' => Yii::t('app', 'MCC'),
            'new_mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'PLANT'),
            'old_route_code' => Yii::t('app', 'Route'),
            'new_route_code' => Yii::t('app', 'Route'),
            'union_code' => Yii::t('app', 'Union'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'status' => Yii::t('app', 'Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'ex_member_code' => Yii::t('app', 'Ex. Member Code'),
            'customer_type' => Yii::t('app', 'Type'),
            'customer_code' => Yii::t('app', 'Name'),
            'from_datetime' => Yii::t('app', 'From Date'),
            'to_datetime' => Yii::t('app', 'To Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_shift' => Yii::t('app', 'To Shift'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblMasterTransferQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMasterTransferQuery(get_called_class());
    }

    public function checkMember() {
        if ($this->old_dcs_code == $this->new_dcs_code) {
            $this->addError('new_dcs_code', Yii::t('app', 'Current DKS and New DSK should not same.'));
        }

//        $this->new_member_code = $this->new_dcs_code . str_pad($this->ex_member_code, 4, '0', STR_PAD_LEFT);
//        if (!empty($this->newMemberCode)) {
//            $this->addError('ex_member_code', Yii::t('app', 'Member already exists.'));
//        }
    }

    public function checkRequest() {
        $data = $this->find()->where(['master_type' => $this->master_type, 'transfer_type' => $this->transfer_type])
                ->andWhere(['not in', 'status', [2, 3]]);
        if ($this->master_type == 'FARMER') {
            $data->andWhere(['old_member_code' => $this->old_member_code]);
        } else if ($this->master_type == 'DCS') {
            $data->andWhere(['old_dcs_code' => $this->old_dcs_code]);
        } else if ($this->master_type == 'CUSTOMER') {
            $data->andWhere(['customer_type' => $this->customer_type, 'customer_code' => $this->customer_code]);
        }
        $record = $data->one();
        if (!empty($record)) {
            $this->addError('master_type', Yii::t('app', 'Transfer Request already open.'));
        } else {
            if (empty($this->getErrors())) {
                if ($this->master_type == 'DCS' && $this->update_transaction == '1') {
                    $this->from_datetime = Yii::$app->formatter->asDate($this->from_datetime, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->from_shift);
                    $this->to_datetime = Yii::$app->formatter->asDate($this->to_datetime, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->to_shift);
                    $shift_detail = \Yii::$app->general->getSpData('sp_mcc_unlock_shift_count', [$this->from_datetime, $this->to_datetime, $this->old_mcc_plant_code]);
                    if (!empty($shift_detail) && $shift_detail[0]['unlock_count'] == 0) {
                        
                    } else {
                        $this->addError('old_mcc_plant_code', Yii::t('app', 'Lock data for all shift of current MCC.'));
                    }
                } else {
                    $this->from_datetime = $this->to_datetime = NULL;
                    $this->update_transaction = 0;
                }
            }
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getOldBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'old_bmc_code']);
    }

    public function getOldMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'old_mcc_plant_code']);
    }

    public function getOldDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'old_dcs_code']);
    }

    public function getOldMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'old_member_code']);
    }

    public function getOldRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'old_route_code']);
    }

    public function getNewBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'new_bmc_code']);
    }

    public function getNewMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'new_mcc_plant_code']);
    }

    public function getNewDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'new_dcs_code']);
    }

    public function getNewRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'new_route_code']);
    }

    public function getNewMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'new_member_code']);
    }

    public function getRequestType() {
        return $this->hasOne(TblTransferType::className(), ['master_type' => 'master_type', 'transfer_type' => 'transfer_type']);
    }

    public function getPickRecords($limit = 30) {
        $query = $this->find()
                ->where(['status' => $this->status])
                ->andWhere(['<=', 'CAST(wef_date as date)', date('Y-m-d')]);
        $query->limit($limit);
        $query->orderBy([
            'wef_date' => SORT_ASC,
            'CAST(RIGHT(old_member_code,4) as int)' => SORT_ASC
        ]);
        return $query->all();
    }

    public function updateFileStatus($value) {
        return $this->updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['master_transfer_code' => $value]);
    }

    public function checkDelete() {
        return ($this->status == 0) ? TRUE : FALSE;
    }

    public function getRouteCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code', 'customer_type' => 'customer_type']);
    }

    public function getDCSList($bmc_code, $RLS = 'TRUE') {
        $query = TblDcs::find()->alias('d')->select(['d.dcs_code','d.dcs_name','d.ref_code'])
            ->innerJoin('tbl_master_transfer as mt', 'mt.old_dcs_code = d.dcs_code')
            ->where(['d.bmc_code' => $bmc_code, 'd.is_active' => 1, 'mt.master_type' => 'DCS']);
            if (Yii::$app->session->get('Dcs') !== '' && $RLS == 'TRUE') {
                $query->andWhere(['d.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
            }
        $dcsList = $query->asArray()->all();
        $data = ArrayHelper::map($dcsList, 'dcs_code', function ($value) {
            return $value['dcs_name'] . ' - ' . $value['ref_code'];
        });
        return $data;
    }

}
