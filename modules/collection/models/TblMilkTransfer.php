<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\syncutility\models\TblSentbox;
use yii\base\UserException;

/**
 * This is the model class for table "tbl_milk_transfer".
 *
 * @property string $milk_transfer_code
 * @property string $transaction_id
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property integer $transfer_type
 * @property string $union_code
 * @property string $source_code
 * @property string $destination_code
 * @property string $vehicle_no
 * @property string $fat
 * @property string $snf
 * @property string $qty
 * @property string $temp
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkTransfer extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['source_type', 'destination_type'], 'default', 'value' => 'BMC'],
            [['transaction_datetime', 'shift_code', 'is_rechilling'], 'safe'],
            [['from_date', 'source_code', 'destination_code', 'vehicle_no', 'fat', 'snf', 'qty', 'from_shift', 'source_type', 'destination_type', 'transaction_datetime', 'shift_code', 'to_date', 'to_shift'], 'required', 'except' => 'androidsync'],
            [['from_date', 'to_date', 'transaction_id', 'union_code', 'source_code', 'destination_code', 'vehicle_no'], 'safe'],
            [['from_shift', 'to_shift', 'transfer_type', 'originating_type'], 'safe'],
            [['fat', 'snf', 'qty', 'temp'], 'safe'],
            [['remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['fat', 'snf', 'qty'], 'number'],
            [['fat', 'snf', 'qty', 'transfer_type'], 'default', 'value' => 0],
            [['fat', 'qty', 'snf'], 'double', 'min' => 0.01, 'message' => Yii::t('app/validation', '{attribute} must be greater than 0')],
            [['conductivity', 'ph_value', 'other_reading', 'freezing_point', 'salt', 'adt_value', 'adt_param', 'lactose', 'density', 'protein', 'water', 'clr'], 'safe'],
            ['source_code', 'compare', 'compareAttribute' => 'destination_code', 'operator' => '!=', 'when' => function ($model) {
                    return $model->source_type == $model->destination_type;
                }, 'message' => Yii::t('app/validation', 'Source and Destination must not be same.')],
            [['to_date'], 'validateToDate'],
            [['transaction_datetime'], 'transactionDateValidate'],
            [['is_rechilling'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_transfer_code' => Yii::t('app', 'Milk Transfer Code'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'transfer_type' => Yii::t('app', 'Transfer Type'),
            'union_code' => Yii::t('app', 'Union'),
            'source_code' => Yii::t('app', 'Source'),
            'destination_code' => Yii::t('app', 'Destination'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'qty' => Yii::t('app', 'Qty'),
            'temp' => Yii::t('app', 'Temp'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'source_type' => Yii::t('app', 'Source Type'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'transaction_datetime' => Yii::t('app', 'Transaction Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'is_rechilling' => Yii::t('app', 'Is Rechilling ?'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getCustomerCodeSource() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'source_code']);
    }

    public function getBmcCodeSource() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'source_code']);
    }

    public function getMccPlantCodeSource() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'source_code']);
    }

    public function getPlantCodeSource() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'source_code']);
    }

    public function getCustomerCodeDest() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'destination_code']);
    }

    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'destination_code']);
    }

    public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'destination_code']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'destination_code']);
    }

    public function getFromShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->to_date) && !empty($this->from_date) && ($this->from_date > $this->to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than or Equal to From Date.'));
            return false;
        }
    }

    public function transactionDateValidate($attribute, $params) {
        if ($this->to_date > $this->transaction_datetime) {
            $this->addError($attribute, Yii::t('app/validation', 'Transaction Date Must be Greater Than or Equal to To Date.'));
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $code = $this->transfer_type == 1 ? $this->destination_code : $this->source_code;
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $code, '', '');
        foreach ($sentboxArray as $sent) {
            $flag = ((isset($this->operation) && $this->operation == true) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type'], $this);
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
        $sentbox->source_org_id = Yii::$app->session->get('Unions');
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
