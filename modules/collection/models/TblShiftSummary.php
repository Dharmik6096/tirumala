<?php

namespace app\modules\collection\models;

use Yii;
use yii\db\Expression;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_shift_summary".
 *
 * @property string $code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $shift_date
 * @property integer $shift_code
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $quantity
 * @property string $amount
 * @property integer $type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $device_id
 * @property integer $doc_no
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 */
class TblShiftSummary extends \app\models\ChildModel {

    public $mcc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_shift_summary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code'], 'required', 'except' => ['androidsync']],
            [['code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'device_id', 'created_by', 'updated_by', 'flg_sentbox_entry', 'sync_status'], 'safe'],
            [['shift_date', 'created_at', 'updated_at', 'sync_timestamp', 'shift_code', 'received_timestamp', 'mcc_code'], 'safe'],
            [['avg_fat', 'avg_snf', 'quantity', 'amount'], 'safe'],
            [['type', 'doc_no'], 'safe'],
            [['code'], 'validateData', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'shift_date' => Yii::t('app', 'Shift Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'quantity' => Yii::t('app', 'Quantity'),
            'amount' => Yii::t('app', 'Amount'),
            'type' => Yii::t('app', 'Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'device_id' => Yii::t('app', 'Device ID'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

    public function FTPPendingData() {
        return $this->find()
                        ->select(['union_code', 'mcc_plant_code', 'shift_date', 'shift_code',])
                        ->where(['OR', ['status' => 0], ['status' => NULL]])
                        ->distinct()
                        ->orderBy('union_code,mcc_plant_code,shift_date,shift_code')
                        ->limit(25)
                        ->asArray()
                        ->all();
    }

    public function updateData($data, $status, $inc_cnt = FALSE) {
        $update = [];
        $update['status'] = $status;
        $update['status_datetime'] = date('Y-m-d H:i:s');
        if ($inc_cnt) {
            $update['check_count'] = new Expression("isnull(check_count,0)+1");
        }
        return $this->updateAll($update, $data);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function validateData($attribute, $params) {
        if (empty($this->mcc_plant_code) && !empty($this->mcc_code)) {
            $this->mcc_plant_code = $this->mcc_code;
        }
        if (empty($this->mcc_plant_code) && !empty($this->bmc_code) && !empty($this->bmcCode)) {
            $mcc = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            $this->mcc_plant_code = !empty($mcc) && $mcc != 'N/A' ? $mcc : $this->mcc_plant_code;
        }
    }

}
