<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_qty_wise_rate".
 *
 * @property integer $qty_code
 * @property string $from_qty
 * @property string $to_qty
 * @property string $rate
 * @property string $wef_date
 * @property string $vehicle_code
 * @property string $transporter_code
 * @property string $union_code
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblQtyWiseRate extends \app\models\ChildModel {

    public $parsing_no;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_qty_wise_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vehicle_code', 'wef_date', 'from_qty', 'to_qty', 'rate', 'union_code', 'transporter_code'], 'required', 'except' => 'importCsv'],
                [['from_qty', 'to_qty', 'rate'], 'number'],
                [['remarks', 'union_code', 'transporter_code', 'vehicle_code', 'originating_type', 'from_qty', 'to_qty', 'rate', 'wef_date', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['originating_type'], 'integer'],
                [['rate', 'from_qty', 'to_qty'], 'number', 'min' => 0],
                [['to_qty'], 'qtyValidate'],
                [['from_qty'], 'rangeValidate', 'skipOnEmpty' => TRUE],
                [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
                [['created_by', 'updated_by'], 'string'],
                [['wef_date'], 'setFieldImport', 'on' => 'importCsv'],
                [['parsing_no'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleMaster::className(), 'targetAttribute' => ['parsing_no' => 'parsing_no'], 'on' => 'importCsv'],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code'], 'on' => 'importCsv'],
                [['parsing_no', 'wef_date', 'from_qty', 'to_qty', 'rate', 'union_code', 'transporter_code', 'vehicle_code'], 'required', 'on' => 'importCsv'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'qty_code' => Yii::t('app', 'Qty Code'),
            'from_qty' => Yii::t('app', 'From Qty'),
            'to_qty' => Yii::t('app', 'To Qty'),
            'rate' => Yii::t('app', 'Rate'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function rangeValidate($attribute, $params) {
        $wef_date = Yii::$app->formatter->asDate($this->wef_date, DATE_FORMAT);
        $query = $this->find()->where('wef_date=\'' . $wef_date . '\' and  ((' . $this->from_qty . '  between from_qty and to_qty) OR (' . $this->to_qty . ' between from_qty and to_qty))')
                ->andWhere(['vehicle_code' => $this->vehicle_code])
                ->andFilterWhere(['<>', 'qty_code', $this->qty_code]);
        $record = $query->one();
        if (!empty($record)) {
            $this->addError($attribute, Yii::t('app/validation', 'Can not use range in between of used range for same WEF Date.'));
            return false;
        }
    }

    public function qtyValidate($attribute, $params) {
        if ($this->from_qty > $this->to_qty) {
            $this->addError($attribute, "To Qty is not less than From Qty");
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getParsingNo() {
        return $this->hasOne(TblVehicleMaster::className(), ['parsing_no' => 'parsing_no']);
    }

    public function setFieldImport($attribute, $params) {
        if (!empty($this->parsing_no)) {
            $this->vehicle_code = Yii::$app->general->getforeignkey($this->parsingNo, 'vehicle_code');
            $this->transporter_code = Yii::$app->general->getforeignkey($this->parsingNo, 'transporter_code');
        }
    }

}
