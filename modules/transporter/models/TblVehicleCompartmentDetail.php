<?php

namespace app\modules\transporter\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\UserException;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblCapacity;

/**
 * This is the model class for table "tbl_vehicle_compartment_detail".
 *
 * @property integer $vehicle_compartment_detail_code
 * @property integer $vehicle_code
 * @property integer $compartment_no
 * @property integer $capacity
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
class TblVehicleCompartmentDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_compartment_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vehicle_code', 'compartment_no', 'capacity', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code'], 'safe'],
                [['vehicle_code'], 'required', 'on' => 'importCsv'],
                [['vehicle_code'], 'importData'],
                [['vehicle_code'], 'unique', 'targetAttribute' => ['vehicle_code', 'compartment_no'], 'message' => 'The combination of Vehicle Code and Compartment No has already been taken.'],
                [['compartment_no', 'capacity'], 'required'],
                [['capacity'], 'integer'],
                ['compartment_no', 'integer', 'min' => 1, 'max' => 5, 'message' => 'Compartment number must be between 1 and 5.'],
                [['capacity'], 'validateCompartmentCapacity']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vehicle_compartment_detail_code' => Yii::t('app', 'Vehicle Compartment Detail Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'compartment_no' => Yii::t('app', 'Compartment No'),
            'capacity' => Yii::t('app', 'Capacity'),
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

    public function importData($attribute, $params) {
        $member_model = new TblVehicleMaster();
        $vehicleData = $member_model->find()->select(['union_code','vehicle_code'])->where(['or', ['vehicle_code' => $this->vehicle_code], ['parsing_no' => $this->vehicle_code]])->one();
        if (!empty($vehicleData)) {
            $this->union_code = $vehicleData->union_code;
            $this->vehicle_code = $vehicleData->vehicle_code;
        } else {
            $this->addError('vehicle_code', Yii::t('app/validation', Yii::t('app', 'Vehicle Code') . ' is invalid'));
        }
    }

    public function getChamberList() {
        $compartmentData = $this->find()->where(['vehicle_code' => (int) $this->vehicle_code])->all();
        if (!empty($compartmentData)) {
            return ArrayHelper::map($compartmentData, 'compartment_no', function($model) {
                        return $model->compartment_no . ' - ' . $model->capacity;
                    });
        }
        return [];
    }

    public function afterSave($insert, $changedAttributes) {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code, '', FALSE, 2);
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
            $sentbox = new TblSentbox();
            $sentbox->source_org_id = $this->union_code;
            if (!($sentbox->setSentboxBatch($this, $flag, $sentboxArray))) {
                throw new UserException("SentBox Entry is not created so transaction is rollback!");
            }
        }
    }

    public function afterDelete() {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code, '', FALSE, 2);
            $sentbox = new TblSentbox();
            $sentbox->source_org_id = $this->union_code;
            if (!($sentbox->setSentboxBatch($this, 'DELETE', $sentboxArray))) {
                throw new UserException("SentBox Entry is not created so transaction is rollback!");
            }
        }
    }

    public function validateCompartmentCapacity($attribute, $params) {
        if (!empty($this->vehicle_code) && !empty($this->capacity)) {
            $existingCompartmentCapacity = $this->find()->where(['vehicle_code' => $this->vehicle_code])->sum('capacity');
            $oldCapacity = !empty($existingCompartmentCapacity) ? $existingCompartmentCapacity : 0;
            $totalCapacity = $oldCapacity + $this->capacity;
            $vehicleCapacity = TblCapacity::find()->select(['tbl_capacity.value'])
                    ->innerJoin('tbl_vehicle_master', 'tbl_vehicle_master.capacity_code = tbl_capacity.capacity_code')
                    ->where(['tbl_vehicle_master.vehicle_code' => $this->vehicle_code])
                    ->one();
            if (!empty($vehicleCapacity)) {
                if ($totalCapacity > $vehicleCapacity['value']) {
                    $this->addError($attribute, 'Vehicle Capacity must not be more than ' . $vehicleCapacity['value'] . '.');
                }
            } else {
                $this->addError($attribute, 'Please define vehicle capacity in master.');
            }
        }
    }

}
