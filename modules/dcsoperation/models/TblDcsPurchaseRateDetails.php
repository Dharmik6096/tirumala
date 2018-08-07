<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_purchase_rate_details".
 *
 * @property integer $code
 * @property double $fat
 * @property double $rtpl
 * @property double $snf
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $purchase_rate_code
 * @property integer $rate_type_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblDcsPurchaseRateDetails extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_purchase_rate_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fat', 'rtpl', 'snf'], 'number'],
            [['milk_quality_type_code', 'milk_type_code', 'purchase_rate_code', 'rate_type_code', 'originating_type'], 'integer'],
            [['originating_org_code', 'originating_org_type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'fat' => Yii::t('app', 'Fat'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'snf' => Yii::t('app', 'Snf'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'rate_type_code' => Yii::t('app', 'Rate Type Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsPurchaseRateAutoQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsPurchaseRateDetailsQuery(get_called_class());
    }

    private function baseValue($range, $quality_param) {
        return TblDcsPurchaseRateBased::find()
                        ->select('quality_param_code,kg_rate,deduction_type,fixed_point,ref_type,value,step,start_range,end_range,formula_code')
                        ->where(['purchase_rate_code' => $this->purchase_rate_code, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code])
                        ->andWhere('(' . $range . ' between start_range and end_range) and quality_param_code=' . $quality_param)
                        ->one();
    }

    private function stepVal($record, $current) {
        if ($record->step <= 0)
            return $record->value;
        else {
            $iteration = $record->end_range - $record->start_range;
            $addition = ($iteration % 2 == 0) ? 0 : 1;
            return (floor(($record->end_range - $current + $addition) / $record->step) * $record->value);
        }
    }

    public function calulateRate($object) {
        $i = bcadd($object->start1, 0.0, 1);
        $j = bcadd($object->end1, 0.0, 1);
        $this->purchase_rate_code = $object->purchase_rate_code;
        $this->milk_type_code = $object->milk_type_code;
        $this->milk_quality_type_code = $object->milk_quality_type_code;
        $this->rate_type_code = $object->rate_type;
        $key_value = $this->getCode();
        $save_array = [];
        for (; $i <= $j;) {
            $x = bcadd($object->start2, 0.0, 1);
            $y = bcadd($object->end2, 0.0, 1);
            for (; $x <= $y;) {
                $rate = $this->calculate($i, $x);
                $save_array[] = $this->saveData($object, $i, $x, $rate, ($key_value));
                $x = bcadd($x, 0.1, 1);
                $key_value+=1;
            }
            $i = bcadd($i, 0.1, 1);
        }
        $this->deleteAll(['milk_type_code' => $object->milk_type_code, 'purchase_rate_code' => $object->purchase_rate_code, 'milk_quality_type_code' => $object->milk_quality_type_code]);
        $generalModel = new GeneralModel;
        $transaction = $generalModel->saveTransaction($save_array, ['Purchase Rate', 'create']);
    }

    private function calculate($row, $column) {
        $qp1 = '';
        $qp2 = '';
        $qp = $this->rateTypeCode->rate_type;
        $qp = explode('+', $qp);

        if (isset($qp[0])) {
            $param = TblQualityParam::find()->where(['param' => $qp[0]])->one();
            $qp1 = $param->id;
        }
        if (isset($qp[1])) {
            $param = TblQualityParam::find()->where(['param' => $qp[1]])->one();
            $qp2 = $param->id;
        }

        $record1 = $this->baseValue($row, $qp1);
        $record2 = [];
        if (!empty($qp2)) {
            $record2 = $this->baseValue($column, $qp2);
        }
        $formula = $record1->rateFormula->formula;
        $array = [
            'FATKG' => $record1->kg_rate,
            'SNFKG' => empty($record2) ? 0 : $record2->kg_rate,
            'FAT' => $row,
            'SNF' => $column,
            'TSKG' => $record1->kg_rate,
            'CLRKG' => empty($record2) ? 0 : $record2->kg_rate,
            'TS' => $row,
            'CLR' => $column,
        ];
        foreach ($array as $key => $value) {
            $formula = str_replace($key, $value, $formula);
        }

        eval('$result = ' . $formula . ';');
        $rate = $this->additonDuduction($record1, $result, $column, $row);
        $rate = $this->additonDuduction($record2, $rate, $row, $column);
        return $rate;
    }

    private function additonDuduction($record, $result, $param, $current) {
        if (!empty($record)) {
            switch ($record->deduction_type) {
                case 1 : return $this->referenceType($result, $record, '+', $this->stepVal($record, $current), $param);
                case 2 : return $this->referenceType($result, $record, '-', $this->stepVal($record, $current), $param);
                case 3 : return $this->referenceType($result, $record, '+', ($result * $this->stepVal($record, $current) / 100), $param);
                case 4 : return $this->referenceType($result, $record, '-', ($result * $this->stepVal($record, $current) / 100), $param);
            }
        }
        return $result;
    }

    private function referenceType($result, $record, $operator, $value, $param) {
        $new_rate = $result;
        switch ($record->ref_type) {
            case 2: eval('$new_rate = ' . $result . $operator . $value . ';');
                break;
            case 1 :
                $fixPoint = ($record->quality_param_code == 1 || $record->quality_param_code == 4) ? $this->calculate($record->fixed_point, $param) : $this->calculate($param, $record->fixed_point);
                eval('$new_rate = ' . $fixPoint . $operator . $value . ';');
                break;
        }
        return $new_rate;
    }

    public function saveData($object, $fat, $snf, $rate, $incrCode) {

        $models = new TblDcsPurchaseRateDetails();
        $models->purchase_rate_code = $object->purchase_rate_code;
        $models->code = $models->purchase_rate_code . $incrCode;
        $models->milk_quality_type_code = $object->milk_quality_type_code;
        $models->milk_type_code = $object->milk_type_code;
        $models->fat = $fat;
        $models->snf = $snf;
        $models->rtpl = round($rate, 2);
        $models->rate_type_code = $object->rate_type;
//        $modelArray[] = $models;

        return $models;
    }

    public function getRateType($id) {
        switch ($id) {
            case 0:
                return 'FAT';
            case 1;
                return 'FAT-SNF';
            case 2;
                return 'SNF';
        }
    }

    public function getRateTypeCode() {
        return $this->hasOne(TblRateType::className(), ['code' => 'rate_type_code']);
    }

    public function getRecord($id) {
        return $this->find()->where(['code' => $id])->one();
    }

    public function getCode() {

        $len = strlen($this->purchase_rate_code);

        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`code` FROM " . $len . " +1)) AS UNSIGNED)) as code")
                ->from('tbl_dcs_purchase_rate_details')
                ->where(['purchase_rate_code' => $this->purchase_rate_code])
                ->one();
        return (int) $val['code'] + 1;
    }

    public function getPurchaseRateCode() {
        return $this->hasOne(TblDcsPurchaseRate::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    public function calculateManualRate($prCode, $milkType, $rateType, $milkQuality) {
        $QltyParam = explode('+', $rateType);
        $this->purchase_rate_code = $prCode;
        $this->milk_type_code = $milkType;
        $this->milk_quality_type_code = $milkQuality;
        $key_value = $this->getCode();
        $save_array = [];
        if (isset($QltyParam[0])) {
            $modelfat = TblDcsPurchaseRateBased::find()
                            ->joinWith(['qualityParamCode'])
                            ->where(['milk_quality_type_code' => $milkQuality, 'purchase_rate_code' => $prCode, 'milk_type_code' => $milkType, 'tbl_quality_param.param' => $QltyParam[0]])->orderBy('created_at')->all();
            for ($i = 0; $i < count($modelfat); $i++) {
                $lowfat = $modelfat[$i]->start_range;
                $highfat = $modelfat[$i]->end_range;
                if (isset($QltyParam[1])) {
                    $modelsnf = TblDcsPurchaseRateBased::find()
                                    ->joinWith(['qualityParamCode'])
                                    ->where(['milk_quality_type_code' => $milkQuality, 'purchase_rate_code' => $prCode, 'milk_type_code' => $milkType, 'tbl_quality_param.param' => $QltyParam[1]])->orderBy('created_at')->all();
                    for (; $lowfat <= $highfat;) {
                        for ($j = 0; $j < count($modelsnf); $j++) {
                            $lowsnf = $modelsnf[$j]->start_range;
                            $highsnf = $modelsnf[$j]->end_range;
                            for (; $lowsnf <= $highsnf;) {
                                $this->purchase_rate_code = $modelfat[$i]->purchase_rate_code;
                                $this->milk_type_code = $modelfat[$i]->milk_type_code;
                                $rate = $this->calculateManual($lowfat, $lowsnf, $modelfat[$i]->quality_param_code, $modelsnf[$j]->quality_param_code, $QltyParam[0], $QltyParam[1]);
                                $save_array[] = $this->saveData($modelfat[$i], $lowfat, $lowsnf, $rate, ($key_value));
                                $lowsnf = floatval(bcadd($lowsnf, 0.1, 1));
                                $key_value+=1;
                            }
                        }
                        $lowfat = floatval(bcadd($lowfat, 0.1, 1));
                    }
                } else {
                    for (; $lowfat <= $highfat;) {
                        $this->purchase_rate_code = $modelfat[$i]->purchase_rate_code;
                        $this->milk_type_code = $modelfat[$i]->milk_type_code;
                        $rate = $this->calculateManual($lowfat, '', $modelfat[$i]->quality_param_code, '', $QltyParam[0], '');
                        $save_array[] = $this->saveData($modelfat[$i], $lowfat, 0, $rate, ($key_value));
                        $lowfat = floatval(bcadd($lowfat, 0.1, 1));
                        $key_value+=1;
                    }
                }
            }
            $this->deleteAll(['milk_quality_type_code' => $modelfat[0]->milk_quality_type_code, 'milk_type_code' => $modelfat[0]->milk_type_code, 'purchase_rate_code' => $modelfat[0]->purchase_rate_code]);
            $generalModel = new GeneralModel;
            $transaction = $generalModel->saveTransaction($save_array, ['Purchase Rate', 'create']);
        }
    }

    private function calculateManual($row, $column, $qp1, $qp2, $param1, $param2) {
        $record1 = $this->baseValue($row, $qp1);
        if (empty($column)) {
            $record2 = [];
        } else {
            $record2 = $this->baseValue($column, $qp2);
        }
        $formula = $record1->rateFormula->formula;
        $array = [
            $param1 . 'KG' => $record1->kg_rate,
            $param2 . 'KG' => empty($record2) ? 0 : $record2->kg_rate,
            $param1 => $row,
            $param2 => $column,
        ];
        foreach ($array as $key => $value) {
            $formula = str_replace($key, $value, $formula);
        }
        eval('$result = ' . $formula . ';');
        $rate = $this->additonDuductionManual($record1, $result, $column, $row, $qp1, $qp2, $param1, $param2);
        $rate = $this->additonDuductionManual($record2, $rate, $row, $column, $qp1, $qp2, $param1, $param2);
        return $rate;
    }

    private function additonDuductionManual($record, $result, $param, $current, $qp1, $qp2, $param1, $param2) {
        if (!empty($record)) {
            switch ($record->deduction_type) {
                case 1 : return $this->referenceTypeManual($result, $record, '+', $this->stepValManual($record, $current, '+'), $param, $qp1, $qp2, $param1, $param2);
                case 2 : return $this->referenceTypeManual($result, $record, '-', $this->stepValManual($record, $current, '-'), $param, $qp1, $qp2, $param1, $param2);
                case 3 : return $this->referenceTypeManual($result, $record, '+', $this->stepValManual($record, $current, '+'), $param, $qp1, $qp2, $param1, $param2);
                case 4 : return $this->referenceTypeManual($result, $record, '-', $this->stepValManual($record, $current, '-'), $param, $qp1, $qp2, $param1, $param2);
            }
        }
        return $result;
    }

    private function referenceTypeManual($result, $record, $operator, $value, $param, $qp1, $qp2, $param1, $param2) {
        $new_rate = $result;
        switch ($record->ref_type) {
            case 2:
                if ($record->deduction_type == 3 || $record->deduction_type == 4) {
                    $value = $result * $value / 100;
                }
                eval('$new_rate = ' . $result . $operator . $value . ';');
                break;
            case 1 :
                $fixPoint = ($record->quality_param_code == 1 || $record->quality_param_code == 4) ? $this->calculateManual($record->fixed_point, $param, $qp1, $qp2, $param1, $param2) : $this->calculateManual($param, $record->fixed_point, $qp1, $qp2, $param1, $param2);
                if ($record->deduction_type == 3 || $record->deduction_type == 4) {
                    $value = $fixPoint * $value / 100;
                }
                eval('$new_rate = ' . $fixPoint . $operator . $value . ';');
                break;
        }
        return $new_rate;
    }

    private function stepValManual($record, $current, $type) {
        if ($record->step <= 0)
            return $record->value;
        else {
            if ($type == '+') {
                $range = $record->start_range;
            } else {
                $range = $record->end_range;
            }
            $iteration = round(( floatval($range) - floatval($current)), 1);
            if ($iteration < 0) {
                $iteration = -$iteration;
            }
            $iteration = $iteration * 10;
            $iteration = $iteration / $record->step;
            $string = (string) $iteration;
            $string = explode('.', $string);
            $iteration = (int) $string[0];
            $iteration = $iteration + 1;
            $iteration = $iteration * $record->value;
            return $iteration;
        }
    }

    public function rtplValidate($val) {

        $message = 'Invalid Value';
        $ratebased = TblDcsPurchaseRateBased::find()->where(['purchase_rate_code' => $this->purchase_rate_code, 'milk_quality_type_code' => $this->milk_quality_type_code, 'milk_type_code' => $this->milk_type_code])->orderBy('purchase_rate_code')->all();
        if ($ratebased) {
            $minfat = $ratebased[0]->start_range;
            $maxfat = $ratebased[0]->end_range;
            $minsnf = isset($ratebased[1]->start_range) ? $ratebased[1]->start_range : 0;
            $maxsnf = isset($ratebased[1]->end_range) ? $ratebased[1]->end_range : 0;
            $pointfat = round(floatval($this->fat), 1);
            $pointsnf = round(floatval($this->snf), 1);
            $prepointsnf = round(floatval(($pointsnf - 0.1)), 1);
            $aftpointsnf = round(floatval(($pointsnf + 0.1)), 1);
            $abvpointfat = round(floatval(($pointfat - 0.1)), 1);
            $blwpointfat = round(floatval(($pointfat + 0.1)), 1);
            $fatarray = "($abvpointfat,$blwpointfat)";
            $snfarray = "($prepointsnf,$aftpointsnf)";
            $otherpoints = $this->find()->where(['purchase_rate_code' => $this->purchase_rate_code])
                            ->andWhere(['or', ['or', ['fat' => $this->fat, 'snf' => $prepointsnf], ['fat' => $this->fat, 'snf' => $aftpointsnf]], ['or', [ 'fat' => $abvpointfat, 'snf' => $this->snf], [ 'fat' => $blwpointfat, 'snf' => $this->snf]]])
                            ->orderBy('fat,snf')->all();
            $prepointval = 0;
            $aftpointval = 0;
            $abvpointval = 0;
            $blwpointval = 0;
            for ($i = 0; $i < count($otherpoints); $i++) {
                if ($otherpoints[$i]->fat == $this->fat) {
                    if ($otherpoints[$i]->snf == $prepointsnf) {
                        $prepointval = round(($otherpoints[$i]->rtpl), 2);
                    } else {
                        $aftpointval = round(($otherpoints[$i]->rtpl), 2);
                    }
                } else {
                    if ($otherpoints[$i]->fat == $abvpointfat) {
                        $abvpointval = round(($otherpoints[$i]->rtpl), 2);
                    } else {

                        $blwpointval = round(($otherpoints[$i]->rtpl), 2);
                    }
                }
            }

            $val = round($val, 2);
            if ($this->fat == $minfat) {
                if ($this->snf == $minsnf || $minsnf == 0) {
                    if (($val <= $aftpointval || $aftpointval == 0) && ($val <= $blwpointval || $blwpointval == 0)) {
                        $message = 'success';
                    }
                } else if ($this->snf == $maxsnf) {
                    if (($val >= $prepointval || $prepointval == 0) && ($val <= $blwpointval || $blwpointval == 0)) {
                        $message = 'success';
                    }
                } else {
                    if (($val >= $prepointval || $prepointval == 0) && ($val <= $blwpointval || $blwpointval == 0) && ($val <= $aftpointval || $aftpointval == 0)) {
                        $message = 'success';
                    }
                }
            } else if ($this->fat == $maxfat) {
                if ($this->snf == $minsnf || $minsnf == 0) {
                    if (($val >= $abvpointval || $abvpointval == 0) && ($val <= $aftpointval || $aftpointval == 0)) {
                        $message = 'success';
                    }
                } else if ($this->snf == $maxsnf) {
                    if (($val >= $abvpointval || $abvpointval == 0) && ($val >= $prepointval || $prepointval == 0)) {
                        $message = 'success';
                    }
                } else {
                    if (($val >= $prepointval || $prepointval == 0) && ($val >= $abvpointval || $abvpointval == 0) && ($val <= $aftpointval || $aftpointval == 0)) {
                        $message = 'success';
                    }
                }
            } else if ($this->snf == $minsnf && $this->snf != 0) {
                if (($val >= $abvpointval || $abvpointval == 0) && ($val <= $aftpointval || $aftpointval == 0) && ($val <= $blwpointval || $blwpointval == 0)) {
                    $message = 'success';
                }
            } else if ($this->snf == $maxsnf && $this->snf != 0) {
                if (($val >= $prepointval || $prepointval == 0) && ($val >= $abvpointval || $abvpointval == 0) && ($val <= $blwpointval || $blwpointval == 0)) {
                    $message = 'success';
                }
            } else {
                if ($this->snf == 0) {
                    if (($val >= $abvpointval) && ($val <= $blwpointval)) {
                        $message = 'success';
                    }
                } else {
                    if (($val >= $prepointval) && ($val <= $aftpointval)) {
                        $message = 'success';
                    }
                }
            }
            return $message;
        }
    }

    public function getExportData($purchaseRateCode) {
        return $this->find()->select(['code', 'fat', 'rtpl', 'snf', 'milk_quality_type_code', 'milk_type_code', 'purchase_rate_code', 'rate_type_code'])->where(['purchase_rate_code' => $purchaseRateCode])->all();
    }

    public function getDcsPurchasseRateDetailData($data, $rate_type) {
        if ($rate_type == 'FAT') {
            $where = ['fat' => $data['fat']];
        } else if ($rate_type == 'FAT+SNF') {
            $where = ['fat' => $data['fat'], 'snf' => $data['snf']];
        } else if ($rate_type == 'FAT+CLR') {
            $where = ['fat' => $data['fat'], 'snf' => $data['clr']];
        } else {
            $where = ['fat' => $data['fat'] + $data['snf']];
        }
        return $this->find()
                        ->where(['purchase_rate_code' => $this->purchase_rate_code, 'milk_type_code' => $data['milk_type'], 'milk_quality_type_code' => $data['milk_quality_type_code']])
                        ->andWhere($where)
                        ->one();
    }

}
