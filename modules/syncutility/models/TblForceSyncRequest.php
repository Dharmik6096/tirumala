<?php

namespace app\modules\syncutility\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblShift;
use app\modules\syncutility\models\TblForceSyncTableList;

/**
 * This is the model class for table "tbl_force_sync_request".
 *
 * @property integer $force_sync_request_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property integer $is_download
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblForceSyncRequest extends \app\models\ChildModel {

    public $is_sentbox = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_force_sync_request';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'dcs_code', 'from_datetime', 'to_datetime', 'from_shift', 'to_shift', 'table_name'], 'required'],
                [['from_datetime', 'to_datetime', 'created_at', 'updated_at'], 'safe'],
                [['from_shift', 'to_shift', 'is_download', 'originating_type'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code'], 'safe'],
                [['bmc_code', 'dcs_code'], 'safe'],
                [['created_by', 'updated_by', 'table_name'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['is_download'], 'default', 'value' => 0],
                [['to_datetime'], 'validateDateRange']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'force_sync_request_code' => Yii::t('app', 'Force Sync Request Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'Society'),
            'from_datetime' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_download' => Yii::t('app', 'Is Download'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'table_name' => Yii::t('app', 'Process'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getFromShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

    public function getForceSyncTableList() {
        return $this->hasOne(TblForceSyncTableList::className(), ['table_name' => 'table_name']);
    }

    public function validateDateRange($attribute, $params) {
        if ($this->from_datetime > $this->to_datetime) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater Than From Date.'));
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
