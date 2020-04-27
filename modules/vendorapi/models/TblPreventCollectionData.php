<?php

namespace app\modules\vendorapi\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_prevent_collection_data".
 *
 * @property integer $prevent_collection_data_id
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblPreventCollectionData extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_prevent_collection_data';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['from_date', 'to_date', 'from_shift', 'to_shift', 'union_code', 'plant_code', 'mcc_plant_code'], 'required'],
                [['to_date'], 'toDateValidate'],
                [['mcc_plant_code'], 'rangeValidate'],
                [['from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
                [['from_shift', 'to_shift'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'created_by', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'prevent_collection_data_id' => Yii::t('app', 'Prevent Collection Data ID'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function toDateValidate($attribute, $params) {
        if (empty($this->getErrors())) {
            $from_date = Yii::$app->formatter->asDate($this->from_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->from_shift);
            $to_date = Yii::$app->formatter->asDate($this->to_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->to_shift);
            if ($to_date < $from_date) {
                $this->addError($attribute, Yii::t('app', 'To Date can not be less than From Date.'));
            }
        }
    }

    public function rangeValidate($attribute, $params) {
        if (empty($this->getErrors())) {
            $from_date = Yii::$app->formatter->asDate($this->from_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->from_shift);
            $to_date = Yii::$app->formatter->asDate($this->to_date, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->to_shift);
            $data = $this->find()
                    ->where(['mcc_plant_code' => $this->mcc_plant_code])
                    ->andWhere("(from_date  between  '" . $from_date . "' and '" . $to_date . "') or (to_date  between  '" . $from_date . "' and '" . $to_date . "')")
                    ->all();
            $message = '';
            if (!empty($data)) {
                $message = Yii::t('app', 'Data Already Prevented as Follow.');
                foreach ($data as $d) {
                    $msg = Yii::$app->general->getforeignkey($d->mccPlantCode, 'name') . '(' . $d->mcc_plant_code . '): ' . Yii::$app->controls->view_date($d->from_date) . '(' . Yii::$app->general->getforeignkey($d->fromShiftCode, 'shift') . ')' . ' to ' . Yii::$app->controls->view_date($d->to_date) . '(' . Yii::$app->general->getforeignkey($d->toShiftCode, 'shift') . ')';
                    $message .= '<br/>' . $msg;
                }
                $this->addError($attribute, $message);
            }
        }
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

    public function getFromShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

}
