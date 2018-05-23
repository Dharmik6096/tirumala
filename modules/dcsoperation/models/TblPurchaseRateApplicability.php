<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateBased;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;

/**
 * This is the model class for table "tbl_purchase_rate_applicability".
 *
 * @property integer $rate_app_code
 * @property string $wef_date
 * @property string $created_at
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $purchase_rate_code
 * @property string $created_by
 * @property string $dcs_code
 * @property string $shift_code
 * @property string $union_code
 * @property string $updated_by
 * @property integer $is_active
 *
 * @property TblPurchaseRateMaster $purchaseRateCode
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblUnions $unionCode
 * @property User $updatedBy
 */
class TblPurchaseRateApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $rate_gen_method_code;
    public $rate_type, $reference_code;

    public static function tableName() {
        return 'tbl_purchase_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_download'], 'default', 'value' => '0'],
            [['is_active'], 'default', 'value' => '1'],
            [['wef_date', 'shift_code'], 'required'],
            [['dcs_code'], 'required', 'message' => 'You must select atleast one society.'],
            [['dcs_code', 'is_active', 'created_at', 'shift_code', 'updated_at', 'wef_date', 'rate_gen_method_code', 'rate_type', 'is_download', 'download_date_time', 'reference_code'], 'safe'],
            //[['purchase_rate_code'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code'], 'AddAutoData', 'on' => ['stellapps'], 'skipOnError' => true,],
            [['purchase_rate_code'], 'required', 'on' => ['stellapps']],
            [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'wef_date', 'shift_code', 'purchase_rate_code'], 'on' => ['stellapps']],
//            [['dcs_code'], 'string', 'max' => 9],
//            [['union_code'], 'string', 'max' => 3],
            [['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code']],
                //[['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
                //[['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_app_code' => Yii::t('app', 'Rate Apply ID'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Society'),
            'shift_code' => Yii::t('app', 'Shift'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_download' => Yii::t('app', 'Download Status'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRateCode() {
        return $this->hasOne(TblPurchaseRate::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblPurchaseRateApplicabilityQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblPurchaseRateApplicabilityQuery(get_called_class());
    }

    public function getCode() {
        $data = $this->find()->select(["convert(int,MAX(rate_app_code)) as rate_app_code"])->one();
        return (int) $data['rate_app_code'] + 1;
    }

    public function checkDuplicate() {
        $check = $this->find()->where(['shift_code' => $this->shift_code, 'purchase_rate_code' => $this->purchase_rate_code, 'wef_date' => $this->wef_date, 'dcs_code' => $this->dcs_code, 'is_active' => 1])->count();

//        echo $this->wef_date.'- '.$this->dcs_code.'<br>';
        return $check;
    }

    public function getDcs() {
        $values = $this->find()->select('dcs_code')->where(['purchase_rate_code' => $this->purchase_rate_code, 'is_active' => 1])->asArray()->all();
        $selected = ArrayHelper::getColumn($values, 'dcs_code');
        return $selected;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getRateType() {
        return $this->hasOne(TblRateType::className(), ['code' => 'rate_type']);
    }

    public function getRateMethod() {
        return $this->hasOne(TblRateGenerateMethod::className(), ['code' => 'rate_gen_method_code']);
    }

    public function getReferenceCode() {
        return $this->hasOne(TblPurchaseRate::className(), ['reference_code' => 'reference_code']);
    }

    public function checkVendorDcs() {
        $vendor = Yii::$app->general->isVendor($this->dcs_code, ['BIPL', 'REIL']);
        return $vendor ? false : true;
    }

    public function generateBiplRateFiles() {
        $cp_code = !empty($this->dcsCode->societyCodes) ? $this->dcsCode->societyCodes->bipl_code : '';
        $dcs = $this->dcsCode;
        $path = Yii::$app->params['rateFilesPath'] . '/' . $this->purchase_rate_code . '/' . $cp_code;
        if (!empty($cp_code) && Yii::$app->general->checkDirectory($path)) {
            $milktypes = TblAnimalType::find()->select(['animal_type_code', 'animal_type_name'])->where(['is_active' => 1, 'LOWER(animal_type_name)' => ['cow', 'buffalo', 'mix']])->all();
            $files = $this->generateTxtFile($path, $this->purchase_rate_code, $dcs, $milktypes);
            if (!empty($files)) {
                return $files;
            }
        }
        //exit;
        return false;
    }

    private function generateTxtFile($path, $id, $dcs, $milktypes) {
        $purchaseRate = TblPurchaseRate::findOne($id);
        $files = [];
        foreach ($milktypes as $milktype) {
            $fileName = $path . '/rate_chart_' . strtolower($milktype->animal_type_name) . '.txt';
            //if (!file_exists($fileName))
            //{  
            $name = strtolower($milktype->animal_type_name) == 'mix' ? 'MIXED' : $milktype->animal_type_name;
            $text = $this->prepareData($id, $milktype->animal_type_code, $name, $dcs);
            if ($text != false) {
                $ratefile = fopen($fileName, "w") or die("Unable to open file!");
                if (fwrite($ratefile, $text)) {
                    $files[][$dcs->societyCodes->bipl_code] = $fileName;
                }
                fclose($ratefile);
            }
            //}            
        }
        return $files;
    }

    private function prepareData($id, $milk_type, $name, $dcs) {
        $purchaseDetail = new TblPurchaseRateDetails();
        $purchaseDetail->milk_type_code = $milk_type;
        $purchaseDetail->purchase_rate_code = $id;
        $fat = $purchaseDetail->find()->select(['fat'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type])->distinct()->orderBy('fat')->all();
        if (!empty($fat)) {
            $snf = $purchaseDetail->find()->select(['snf'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type])->distinct()->orderBy('snf')->all();
            $rate = $purchaseDetail->find()->select(['rtpl', 'fat', 'snf', 'code'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type])->orderBy(['fat' => SORT_ASC, 'snf' => SORT_ASC])->all();
            $txt = '>START_TIME		= ' . date('d.m.Y H:i:s') . PHP_EOL .
                    '>END_TIME		= ' . date('d.m') . '.2099 23:59:59' . PHP_EOL .
                    '>CP_NAME		= ' . $dcs->dcs_name . PHP_EOL .
                    '>CP_ADDRESS		= ' . $dcs->villageCode->village_name . PHP_EOL .
                    '>CP_CODE		= ' . $dcs->societyCodes->bipl_code . PHP_EOL .
                    '>FILE_NAME		= v1' . PHP_EOL .
                    '>RATE_FAT_BELOW_MIN	= 0.0' . PHP_EOL .
                    '>RATE_SNF_BELOW_MIN	=0.0' . PHP_EOL .
                    '>RATE_FAT_SNF_BELOW_MIN =0.0' . PHP_EOL .
                    '>VENDOR_RANK=1' . PHP_EOL .
                    '>MILK_TYPE=' . strtoupper($name) . PHP_EOL . '' . PHP_EOL . '' . PHP_EOL;
            $snf = ArrayHelper::getColumn($snf, 'snf');
            $txt.='*FAT,' . implode(',', $snf) . PHP_EOL;
            $cnt = 0;

            foreach ($fat as $f) {
                if (count($snf) == 1) {
                    $rt = [round($rate[$cnt]->rtpl, 2)];
                    $cnt++;
                } else {
                    $rt = [];
                    foreach ($snf as $s) {
                        $rt[] = round($rate[$cnt]->rtpl, 2);
                        $cnt++;
                    }
                }
                $txt.=$f->fat . ',' . implode(',', $rt) . PHP_EOL;
            }
            return $txt;
        }
        return false;
    }

    public function generateEncFile($ratefile, $path) {
        chdir(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['biplRateUtilityPath']);
        $ratefile = \Yii::getAlias('@webroot') . '/' . $ratefile;
        //$path=$path;
        //echo '<br/>rfgb '.$ratefile.' '.$path;
        exec('rfgb ' . $ratefile . ' ' . $path);
        //exit;
        return;
    }

    public function AddAutoData($attribute, $params) {
        $this->union_code = $this->dcsCode->union_code;
        $this->wef_date = date('Y-m-d', strtotime($this->wef_date)) . ' ' . Yii::$app->general->getshift($this->shift_code);
        $this->purchase_rate_code = $this->referenceCode->purchase_rate_code;
    }

}
