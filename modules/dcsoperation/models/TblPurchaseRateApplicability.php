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
use app\modules\syncutility\models\TblSentbox;
use app\modules\dcsoperation\models\TblShift;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblCustomerMaster;
use yii\db\Expression;

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
    public $rate_gen_method_code, $rate_description;
    public $rate_type, $reference_code, $dcs_name, $applicable_for, $applicable_code, $bmc_code, $ex_code, $dcs_purchase_rate_code;
    public $import_union_code, $import_eipl_code, $import_key_pattern;

    public static function tableName() {
        return 'tbl_purchase_rate_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_download'], 'default', 'value' => '1'],
                [['is_active'], 'default', 'value' => '1'],
                [['shift_code'], 'default', 'value' => '1'],
                [['wef_date', 'shift_code', 'shift_applicability'], 'required', 'except' => ['importCsv']],
                [['applicable_for', 'applicable_code', 'bmc_code', 'wef_date'], 'required', 'on' => ['importCsv']],
                [['dcs_code'], 'required', 'message' => 'You must select atleast one society.', 'except' => ['importCsv']],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['applicable_for'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
                [['applicable_for'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['applicable_for' => 'customer_type'], 'on' => ['importCsv']],
                [['dcs_code', 'is_active', 'created_at', 'shift_code', 'updated_at', 'wef_date', 'rate_gen_method_code', 'rate_type', 'is_download', 'download_date_time', 'reference_code', 'dcs_purchase_rate_code'], 'safe'],
            //[['purchase_rate_code'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['dcs_code'], 'AddAutoData', 'on' => ['stellapps'], 'skipOnError' => true,],
                [['purchase_rate_code'], 'required', 'on' => ['stellapps']],
                [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'wef_date', 'shift_code', 'purchase_rate_code'], 'on' => ['stellapps']],
                [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'wef_date', 'shift_code'], 'message' => Yii::t('app/validation', 'Record Is Already Exist For Member Applicability'), 'on' => ['importCsv']],
//            [['dcs_code'], 'string', 'max' => 9],
//            [['union_code'], 'string', 'max' => 3],
            //[['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            //[['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'shift_applicability'], 'safe'],
                [['shift_code', 'shift_applicability'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
                [['wef_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['wef_date'], 'convertDate', 'on' => ['importCsv']],
                [['applicable_for'], 'setImport', 'on' => ['importCsv']],
                [['dcs_purchase_rate_code'], 'required', 'when' => function ($model) {
                    return strtoupper($model->applicable_for) != 'DCS';
                }, 'on' => ['importCsv']],
                [['dcs_code'], 'unique', 'targetAttribute' => ['dcs_code', 'wef_date'], 'message' => Yii::t('app/validation', 'Record Is Alredy Exist.'), 'on' => ['approval']],
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
            'purchase_rate_code' => Yii::t('app', 'Rate ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'shift_code' => Yii::t('app', 'Shift'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_download' => Yii::t('app', 'Download Status'),
            'download_date_time' => Yii::t('app', 'Download Date Time'),
            'dcs_name' => Yii::t('app', 'Society Name'),
            'reference_code' => Yii::t('app', 'SAP Rate ID'),
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

    public function generateBiplRateFiles($cp_code) {
        $dcs = $this->dcsCode;
        $path = Yii::$app->params['rateFilesPath'] . '/' . $this->purchase_rate_code . '/' . $cp_code;
        if (!empty($cp_code) && Yii::$app->general->checkDirectory($path)) {
            $milktypes = TblAnimalType::find()->select(['animal_type_code', 'animal_type_name'])->where(['is_active' => 1, 'LOWER(animal_type_name)' => ['cow', 'buffalo', 'mix']])->all();
            $files = $this->generateTxtFile($path, $this->purchase_rate_code, $dcs, $milktypes);
            if (!empty($files)) {
                return $files;
            }
        }
        return false;
    }

    private function generateTxtFile($path, $id, $dcs, $milktypes) {
        $purchaseRate = TblPurchaseRate::findOne($id);

        /* Shift wise Rate Chart */
        $rateShiftType = TblPurchaseRateApplicability::find()->select(['shift_applicability'])->where(['purchase_rate_code' => $id, 'dcs_code' => $dcs->dcs_code])->orderBy(['wef_date' => SORT_DESC])->asArray()->one();
        $purchaseRate2 = '';
        $rateShiftType1 = $rateShiftType2 = 3;
        if (!empty($rateShiftType) && $rateShiftType['shift_applicability'] != 3) {
            $rateShiftType1 = $rateShiftType['shift_applicability'];
            $rateShiftType2 = ($rateShiftType1 == 1) ? 2 : 1;
            $rateApplicability2 = TblPurchaseRateApplicability::find()->select(['purchase_rate_code'])->where(['shift_applicability' => [$rateShiftType2, 3], 'dcs_code' => $dcs->dcs_code])->orderBy(['wef_date' => SORT_DESC])->asArray()->one();
            if (!empty($rateApplicability2)) {
                $purchaseRate2 = TblPurchaseRate::findOne($rateApplicability2['purchase_rate_code']);
            }
        }
        /* Shift wise Rate Chart */

        $eiplCode = !empty($purchaseRate) ? Yii::$app->general->getforeignkey($purchaseRate->unionCode, 'eipl_code') : '';
        $files = [];
        foreach ($milktypes as $milktype) {
            $fileName = $path . '/rate_chart_' . strtolower($milktype->animal_type_name) . '.txt';
            $name = strtolower($milktype->animal_type_name) == 'mix' ? 'MIXED' : $milktype->animal_type_name;
            $text = $this->prepareData($id, $milktype->animal_type_code, $name, $dcs, $eiplCode, $milktype->animal_type_name, [$purchaseRate2, $rateShiftType2, $rateShiftType1]);
            if ($text != false) {
                $ratefile = fopen($fileName, "w") or die("Unable to open file!");
                if (fwrite($ratefile, $text)) {
                    $files[][$dcs->ref_code] = $fileName;
                }
                fclose($ratefile);
            }
        }
        if (!empty($files)) {
            return [$files, (!empty($purchaseRate2) ? 'v1_sh56' : 'v1')];
        }
        return $files;
    }

    private function prepareData($id, $milk_type, $name, $dcs, $eiplCode = '', $milkTypeName = '', $rateDetail2 = []) {
        $purchaseDetail = new TblPurchaseRateDetails();
        $purchaseDetail->milk_type_code = $milk_type;
        $purchaseDetail->purchase_rate_code = $id;
        $milk_quality_type_code = '1';

        $fileName = 'v1'; //date('ddmmyy');

        if (strtolower($eiplCode) == 'dodla') {
            $appendDate = date('dmy');
            if (strtolower($milkTypeName) == 'cow') {
                $fileName = 'CM' . $appendDate;
            } else if (strtolower($milkTypeName) == 'mix') {
                $fileName = 'MM' . $appendDate;
            } else {
                $fileName = 'BM' . $appendDate;
            }
        }

        $fileName = !empty($rateDetail2[0]) ? 'v1_sh56' : $fileName;

        $txt = '>START_TIME		= ' . date('d.m.Y H:i:s') . PHP_EOL .
                '>END_TIME		= ' . date('d.m') . '.2099 23:59:59' . PHP_EOL .
                '>CP_NAME		= ' . substr($dcs->dcs_name, 0, 35) . PHP_EOL .
                '>CP_ADDRESS		= ' . substr($dcs->dcs_name, 0, 35) . PHP_EOL .
                '>CP_CODE		= ' . $dcs->ref_code . PHP_EOL .
                '>FILE_NAME		= ' . $fileName . PHP_EOL .
                '>RATE_FAT_BELOW_MIN	= 0.0' . PHP_EOL .
                '>RATE_SNF_BELOW_MIN	=0.0' . PHP_EOL .
                '>RATE_FAT_SNF_BELOW_MIN =0.0' . PHP_EOL;

        if (!empty($rateDetail2[0])) {
            $txt .= '>EV_RATE_FAT_BELOW_MIN	= 0.0' . PHP_EOL .
                    '>EV_RATE_SNF_BELOW_MIN	=0.0' . PHP_EOL .
                    '>EV_RATE_FAT_SNF_BELOW_MIN =0.0' . PHP_EOL;
        }

        $txt .= '>VENDOR_RANK=1' . PHP_EOL .
                '>MILK_TYPE=' . strtoupper($name) . PHP_EOL . '' . PHP_EOL . '' . PHP_EOL;

        $fat = $purchaseDetail->find()->select(['fat'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality_type_code])->andWhere(['<=', 'snf', 12])->distinct()->orderBy('fat')->all();
        if (!empty($fat)) {
            $snf = $purchaseDetail->find()->select(['snf'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality_type_code])->andWhere(['<=', 'snf', 12])->distinct()->orderBy('snf')->all();
            $rate = $purchaseDetail->find()->select(['rtpl', 'fat', 'snf', 'code'])->where(['purchase_rate_code' => $id, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality_type_code])->andWhere(['<=', 'snf', 12])->orderBy(['fat' => SORT_ASC, 'snf' => SORT_ASC])->all();

            $snf = ArrayHelper::getColumn($snf, 'snf');
            $txt .= '*FAT' . (!empty($rateDetail2[0]) ? ('-' . (($rateDetail2[2] == 1) ? 'MORNING' : 'EVENING')) : '') . ',' . implode(',', $snf) . PHP_EOL;
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
                $txt .= $f->fat . ',' . implode(',', $rt) . PHP_EOL;
            }

            if (!empty($rateDetail2[0])) {
                $fat = $purchaseDetail->find()->select(['fat'])->where(['purchase_rate_code' => $rateDetail2[0]->purchase_rate_code, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality_type_code])->andWhere(['<=', 'snf', 12])->distinct()->orderBy('fat')->all();
                if (!empty($fat)) {
                    $snf = $purchaseDetail->find()->select(['snf'])->where(['purchase_rate_code' => $rateDetail2[0]->purchase_rate_code, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality_type_code])->andWhere(['<=', 'snf', 12])->distinct()->orderBy('snf')->all();
                    $rate = $purchaseDetail->find()->select(['rtpl', 'fat', 'snf', 'code'])->where(['purchase_rate_code' => $rateDetail2[0]->purchase_rate_code, 'milk_type_code' => $milk_type, 'milk_quality_type_code' => $milk_quality_type_code])->andWhere(['<=', 'snf', 12])->orderBy(['fat' => SORT_ASC, 'snf' => SORT_ASC])->all();

                    $snf = ArrayHelper::getColumn($snf, 'snf');
                    $txt .= '*FAT-' . (($rateDetail2[1] == 1) ? 'MORNING' : 'EVENING') . ',' . implode(',', $snf) . PHP_EOL;
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
                        $txt .= $f->fat . ',' . implode(',', $rt) . PHP_EOL;
                    }
                }
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
        $utility_path = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['biplRateUtilityPath'];
        $command = 'cd ' . $utility_path . ' && ./rfgB-32 ' . $ratefile . ' ' . $path;
//        exec('rfgb ' . $ratefile . ' ' . $path);
        exec('cd ' . $utility_path . ' && ./rfgB-32 ' . $ratefile . ' ' . $path);
        //exit;
        return;
    }

    public function AddAutoData($attribute, $params) {
        $this->union_code = $this->dcsCode->union_code;
        $this->wef_date = date('Y-m-d', strtotime($this->wef_date)) . ' ' . Yii::$app->general->getshift($this->shift_code);
        $this->purchase_rate_code = $this->referenceCode->purchase_rate_code;
    }

//    public function afterSave($insert, $changedAttributes) {
//        parent::afterSave($insert, $changedAttributes);
//        $sentbox = $this->sentboxModel($this->dcs_code, 'VLC');
//
//        $purchaseModel = TblPurchaseRate::findOne($this->purchase_rate_code);
//        $sentbox->setSentbox($purchaseModel, 'INSERT');
//
//        $purchaseBaseModel = TblPurchaseRateBased::find()->where(['purchase_rate_code' => $this->purchase_rate_code])->all();
//        foreach ($purchaseBaseModel as $model) {
//            $sentbox->setSentbox($model, 'INSERT');
//        }
//        $purchaseDetail = TblPurchaseRateDetails::find()->where(['purchase_rate_code' => $this->purchase_rate_code])->all();
//        foreach ($purchaseDetail as $model) {
//            $sentbox->setSentbox($model, 'INSERT');
//        }
//        $sentbox->setSentbox($this, 'INSERT');
//    }
//
//    private function sentboxModel($code, $type) {
//        $sentbox = new TblSentbox();
//        $sentbox->dest_org_id = $code;
//        $sentbox->source_org_id = $this->union_code;
//        $sentbox->dest_org_type = $type;
//        return $sentbox;
//    }


    public function getPurchaseRateApplicableData($data) {
        return $this->find()
                        ->select(['dprd.rate_type_code as rate_app_code', 'tbl_purchase_rate_applicability.purchase_rate_code'])
                        ->joinWith(['purchaseRateCode'])
                        ->join('LEFT JOIN', 'tbl_purchase_rate_details dprd', 'dprd.purchase_rate_code = tbl_purchase_rate_applicability.purchase_rate_code AND dprd.milk_type_code =' . $data['milk_type'] . ' AND dprd.milk_quality_type_code =' . $data['milk_quality_type'] . ' AND dprd.rate_class =\'' . $data['rate_class'] . '\'')
                        ->where(['tbl_purchase_rate_applicability.is_active' => 1, 'tbl_purchase_rate_applicability.dcs_code' => $this->dcs_code, 'tbl_purchase_rate_applicability.shift_applicability' => [3, $data['shift']]])
                        ->andWhere(['<=', 'tbl_purchase_rate_applicability.wef_date', $this->wef_date])
//                        ->andWhere(['dprd.milk_type_code' => $data['milk_type'], 'dprd.milk_quality_type_code' => $data['milk_quality_type']])
                        ->orderBy('tbl_purchase_rate_applicability.wef_date desc')
                        ->one();
    }

    public function getPendingApplicability($device_id, $hash_key, $dcs_code) {
        return $this->find()->select(['tbl_purchase_rate_applicability.*'])
                        ->leftJoin('tbl_rate_download_ack', "tbl_rate_download_ack.rate_app_code=tbl_purchase_rate_applicability.rate_app_code  AND tbl_rate_download_ack.device_id='$device_id' AND tbl_rate_download_ack.hash_key='$hash_key' AND tbl_rate_download_ack.applicable_for='MEMBER'")
                        ->where(['tbl_purchase_rate_applicability.purchase_rate_code' => $this->purchase_rate_code, 'tbl_purchase_rate_applicability.dcs_code' => $dcs_code])
                        ->andWhere(['tbl_rate_download_ack.ack_id' => NULL])
                        ->all();
    }

    public function afterDelete() {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->dcs_code);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
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

    public function setChildTable(&$model, &$modelSave, &$errors) {
        $model->dcs_code = $model->applicable_code;
        if (strtoupper($model->applicable_for != 'DCS')) {
            $this->setDCSRateModel($model, $errors);
        } else {
            if (!empty($model->dcs_purchase_rate_code) && empty($model->purchase_rate_code)) {
                $memberRateModel = new TblPurchaseRate();
                $data = $memberRateModel->getDcsPurchaseRateCode($model->dcs_purchase_rate_code);
                if (!empty($data)) {
                    $model->purchase_rate_code = $data->purchase_rate_code;
                    $purchaseModel = new TblDcsPurchaseRateApplicabitity();
                    $purchaseModel->attributes = $model->attributes;
                    $purchaseModel->purchase_rate_code = $model->dcs_purchase_rate_code;
                    $purchaseModel->applicable_code = $model->applicable_code;
                    $purchaseModel->applicable_for = $model->applicable_for;
                    $purchaseModel->union_code = $model->union_code;
                    $purchaseModel->dcs_code = NULL;
                    $purchaseModel->scenario = 'importCsv';
                    if (!$purchaseModel->validate()) {
                        $errors[] = $purchaseModel->getErrors();
                    }
                    array_push($modelSave, $purchaseModel);
                } else {
                    $this->setDCSRateModel($model, $errors);
                }
            } elseif (!empty($model->dcs_purchase_rate_code) && !empty($model->purchase_rate_code)) {
                $memberRateModel = new TblPurchaseRate();
                $data = $memberRateModel->getDcsPurchaseRateCode($model->dcs_purchase_rate_code);
                if (!empty($data)) {
                    $model->purchase_rate_code = $data->purchase_rate_code;
                    $purchaseModel = new TblDcsPurchaseRateApplicabitity();
                    $purchaseModel->attributes = $model->attributes;
                    $purchaseModel->purchase_rate_code = $model->dcs_purchase_rate_code;
                    $purchaseModel->applicable_code = $model->applicable_code;
                    $purchaseModel->applicable_for = $model->applicable_for;
                    $purchaseModel->union_code = $model->union_code;
                    $purchaseModel->dcs_code = NULL;
                    $purchaseModel->scenario = 'importCsv';
                    if (!$purchaseModel->validate()) {
                        $errors[] = $purchaseModel->getErrors();
                    }
                    array_push($modelSave, $purchaseModel);
                } else {
                    $this->setDCSRateModel($model, $errors);
                }
            }
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->wef_date = !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : '';
            $this->wef_date = $this->wef_date . ' ' . \Yii::$app->general->getshift($this->shift_code);

            if (strtolower($this->applicable_for) != 'dcs' && empty($this->dcsPurchaseRate)) {
                $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'DCS') . ' Purchase Rate Code is invalid.'));
            }
//            if (strtolower($this->applicable_for) != 'dcs' && !empty($this->purchase_rate_code)) {
//                $this->addError($attribute, Yii::t('app/validation', 'Applicable For is must be DCS.'));
//            }
            $purchase = new TblPurchaseRate();
            $code = $purchase->getValidPurchaseRate($this->purchase_rate_code);
            if (strtolower($this->applicable_for) == 'dcs' && !empty($this->purchase_rate_code) && empty($code)) {
                $this->addError($attribute, Yii::t('app/validation', 'Purchase Rate Code Is Invalid.'));
            } else {
                $this->purchase_rate_code = $code;
            }
            if (!empty($this->dcs_purchase_rate_code) && empty($this->customerType)) {
                $this->addError($attribute, Yii::t('app/validation', 'Applicable For is invalid.'));
            } else {
                $this->validateCustomer($this);
                if (strtoupper($this->applicable_for) == 'DCS') {
                    $this->dcs_code = $this->applicable_code;
                }
            }
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
//            $this->shift_code = 1;
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
            $this->wef_date = $this->wef_date . ' ' . \Yii::$app->general->getshift($this->shift_code);
        }
    }

    public function validateCustomer($model) {
        if (empty($model->applicable_for) || strtoupper($model->applicable_for) == 'DCS') {
            $model->applicable_for = 'DCS';
            $applicable_code = Yii::$app->general->getforeignkey($this->dcsRefCode, 'dcs_code');
        } else {
            $model->applicable_for = strtoupper($model->applicable_for);
            $applicable_code = $this->validateCustomerCode($model);
        }
        if (empty($applicable_code)) {
            $model->addError('applicable_code', Yii::t('app/validation', Yii::t('app', 'Applicable Code') . ' is invalid'));
        } else {
            $model->applicable_code = $applicable_code;
        }
    }

    public function validateCustomerCode($model) {
        if (strtolower($model->applicable_for) != 'dcs') {
            $prefix = Yii::$app->general->getforeignkey($model->customerType, 'code_prefix');
            $length = Yii::$app->general->getforeignkey($model->customerType, 'code_length');
            $Code = '';
            if (!empty($prefix) && is_numeric($model->applicable_code)) {
                $customerModel = new TblCustomerMaster();
                $customerModel->customer_type = $model->applicable_for;
                $customerModelData = $customerModel->find()
                        ->where(['customer_type' => $model->applicable_for, 'bmc_code' => $model->bmc_code])
                        ->andWhere(['or',
                                ['CAST(REPLACE(customer_code_ex, \'A\', \'\') as int)' => (int) $model->applicable_code],
                                ['customer_code' => (int) $model->applicable_code]
                        ])
                        ->all();
                if (count($customerModelData) == 1) {
                    $Code = $customerModelData[0]->customer_code;
                    $model->ex_code = $customerModelData[0]->customer_code_ex;
                }
            } else {
                $model->ex_code = $prefix . str_pad($model->applicable_code, $length, '0', STR_PAD_LEFT);
                $Code = Yii::$app->general->getforeignkey($model->customerCode, 'customer_code');
            }
            return $data = empty($Code) ? '' : $Code;
        }
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code'])->andOnCondition(['is_applicability' => 1, 'is_active' => 1]);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'applicable_for'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->ex_code]);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'applicable_code']);
    }

    public function getDcsRefCode() {
        return $this->hasOne(TblDcs::className(), ['ref_code' => 'applicable_code', 'bmc_code' => 'bmc_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsPurchaseRate() {
        return $this->hasOne(TblDcsPurchaseRate::className(), ['purchase_rate_code' => 'dcs_purchase_rate_code']);
    }

    public function checkDuplicateData() {
        $query = $this->find()->where(['dcs_code' => $this->dcs_code, 'wef_date' => $this->wef_date]);
        return $query->all();
    }

    public function setDCSRateModel(&$purchaseModel, &$errors) {
        $postData = $purchaseModel;
        $purchaseModel = new TblDcsPurchaseRateApplicabitity();
        $purchaseModel->attributes = $postData->attributes;
        $purchaseModel->purchase_rate_code = $postData->dcs_purchase_rate_code;
        $purchaseModel->applicable_code = $postData->applicable_code;
        $purchaseModel->applicable_for = $postData->applicable_for;
        $purchaseModel->union_code = $postData->union_code;
        $purchaseModel->dcs_code = NULL;
        $purchaseModel->scenario = 'importCsv';
        if (!$purchaseModel->validate()) {
            $errors[] = $purchaseModel->getErrors();
        }
    }

    public function convertDateDot() {
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function getdispatchPurchaseRateApplicableData($data) {
        return $this->find()
                        ->select(['dprd.rate_type_code as rate_app_code', 'tbl_purchase_rate_applicability.purchase_rate_code'])
                        ->joinWith(['purchaseRateCode'])
                        ->join('LEFT JOIN', 'tbl_purchase_rate_details dprd', 'dprd.purchase_rate_code = tbl_purchase_rate_applicability.purchase_rate_code AND dprd.milk_type_code =' . $data['milk_type'] . ' AND dprd.milk_quality_type_code =' . $data['milk_quality_type'] . ' AND dprd.rate_class in' . $data['rate_class'])
                        ->where(['tbl_purchase_rate_applicability.is_active' => 1, 'tbl_purchase_rate_applicability.dcs_code' => $this->dcs_code, 'tbl_purchase_rate_applicability.shift_applicability' => [3, $data['shift']]])
                        ->andWhere(['<=', 'tbl_purchase_rate_applicability.wef_date', $this->wef_date])
//                        ->andWhere(['dprd.milk_type_code' => $data['milk_type'], 'dprd.milk_quality_type_code' => $data['milk_quality_type']])
                        ->orderBy('dprd.rate_class ASC,tbl_purchase_rate_applicability.wef_date desc')
                        ->one();
    }

    public function getApplicability($rate_id, $dcs_code, $date) {
        return $this->find()
                        ->select(['tbl_purchase_rate_applicability.*'])
                        ->where(['tbl_purchase_rate_applicability.is_active' => 1, 'tbl_purchase_rate_applicability.dcs_code' => $dcs_code, 'tbl_purchase_rate_applicability.purchase_rate_code' => $rate_id])
                        ->andWhere(['cast(tbl_purchase_rate_applicability.wef_date as date)' => $date])
                        ->one();
    }

    public function getDcsApplicability($dcs_code, $date) {
        return $this->find()
                        ->select(['tbl_purchase_rate_applicability.*'])
                        ->where(['tbl_purchase_rate_applicability.is_active' => 1, 'tbl_purchase_rate_applicability.dcs_code' => $dcs_code])
                        ->andWhere(['<=', 'tbl_purchase_rate_applicability.wef_date', $date])
                        ->orderBy('tbl_purchase_rate_applicability.wef_date desc')
                        ->one();
    }

    public function getShiftApplicability() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_applicability']);
    }

    public function getMasterRecord(){
        $data = (new \yii\db\Query())
            ->select([
                'companyCode' => new Expression("ISNULL(u.x_col1, '')"),
                'rateAppCode' => new Expression("ISNULL(app.rate_app_code, '')"),
                'rateId' => new Expression("ISNULL(rate.purchase_rate_code, '')"),
                'mppCode' => new Expression("ISNULL(dcs.ref_code, '')"),
                'effectiveDate' => new Expression("ISNULL(cast(app.wef_date as date), '')"),
                'effectiveShift' => new Expression("ISNULL(LEFT(shift.shift, 1), '')"),
            ])
            ->from('tbl_purchase_rate_applicability app')
            ->innerJoin('tbl_purchase_rate rate', 'rate.purchase_rate_code = app.purchase_rate_code')
            ->innerJoin('tbl_unions u', 'u.union_code = rate.union_code')
            ->innerJoin('tbl_dcs dcs', 'dcs.dcs_code = app.dcs_code and dcs.dpu_type = 93')
            ->innerJoin('tbl_shift shift', 'shift.id = app.shift_code')
            ->where(['or',['app.data_post_status' => 0],['app.data_post_status' => ''],['app.data_post_status' => null]])
            ->andWhere(['rate.data_post_status' => 2])
            ->orderBy(['rate.purchase_rate_code' => SORT_ASC])
            ->limit(10)
            ->all();
        if (!empty($data)) {
            $primaryKeyCode = [];
            $companyCode = (string)$data[0]['companyCode'];
            array_walk($data, function(&$item) use (&$primaryKeyCode){
                $key = $item['rateId'].$item['mppCode'];
                $primaryKeyCode[$key] = $item['rateAppCode'];
                unset($item['companyCode'], $item['rateAppCode']);
            });
            $data = [
                'mppCode' => $primaryKeyCode,
                'companyCode' => $companyCode,
                'mappingDetails' => $data,
            ];
        }
        return $data;

    }

    public function updateStatus($updateData, $ids) {
        return $this->updateAll($updateData, ['rate_app_code' => $ids]);
    }
}
