<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_purchase_rate".
 *
 * @property string $purchase_rate_code
 * @property string $wef_date
 * @property string $created_at
 * @property string $description
 * @property integer $is_default
 * @property integer $is_active
 * @property integer $originating_org_type
 * @property string $originating_org_code
 * @property string $rate_gen_method_code
 * @property string $updated_at
 * @property string $created_by
 * @property integer $shift_applicability
 * @property string $updated_by
 * @property string $union_code
 */
class TblTankerRate extends \app\models\ChildModel {

    public $federation_code, $app_org_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_tanker_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_default',], 'default', 'value' => '1'],
            [['is_active',], 'default', 'value' => '1'],
            [['wef_date', 'rate_for', 'rate_gen_method_code', 'union_code', 'shift_id'], 'required'],
            [['created_at', 'originating_org_type', 'is_active', 'updated_at', 'is_default', 'federation_code', 'union_code', 'purchase_rate_code', 'shift_id'], 'safe'],
            [['description', 'originating_org_code'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'purchase_rate_code' => Yii::t('app', 'Rate ID'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'originating_org_type' => Yii::t('app', 'Originating Location'),
            'originating_org_code' => Yii::t('app', 'Originating Loc ID'),
            'rate_gen_method_code' => Yii::t('app', 'Rate Method'),
            'rate_for' => Yii::t('app', 'Rate For'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'shift_applicability' => Yii::t('app', 'Shift Applicability'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
            'shift_id' => Yii::t('app', 'Shift'),
            'reference_code' => Yii::t('app', 'SAP Rate ID'),
            'dcs_purchase_rate_code' => Yii::t('app', 'DCS Rate ID'),
        ];
    }

    public function getCode() {


        $data = $this->find()->select(["MAX(purchase_rate_code) as purchase_rate_code"])->one();
        return $data['purchase_rate_code'] + 1;
    }

    /**
     * @inheritdoc
     * @return TblTankerRateQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblTankerRateQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateAuto() {
        return $this->hasOne(TblTankerRateDetails::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateDetail() {
        return $this->hasOne(TblTankerRateDetails::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateBased() {
        return $this->hasMany(TblTankerRateBased::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateApplicability() {
        return $this->hasMany(TblTankerRateApplicability::className(), ['purchase_rate_code' => 'purchase_rate_code'])->where(['dcs_code' => $this->app_org_code])->orderBy('wef_date DESC');
    }

    /**
     * @return \yii\db\ActiveQueryNULL
     */
    public function getShiftApplicability() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_applicability']);
    }

    public function getShiftId() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateMethod() {
        return $this->hasOne(TblRateGenerateMethod::className(), ['code' => 'rate_gen_method_code']);
    }

    public function getRecord($id) {
        return $this->find()->select(['purchase_rate_code', 'wef_date', 'rate_gen_method_code', 'shift_applicability', 'union_code', 'shift_id'])->where(['purchase_rate_code' => $id])->one();
    }

    public function addTankerRate($jsonData) {

        $this->union_code = $jsonData['union_code'];
        $this->attributes = $jsonData;
        $this->purchase_rate_code = $this->getCode();
        $this->rate_gen_method_code = $jsonData['rate_method'];
        $this->rate_for = $jsonData['rate_for'];
        //    $this->rate_type = $jsonData['rate_type'];
        $this->shift_applicability = $jsonData['shift'];
        $this->description = $jsonData['description'];
        $this->shift_id = $jsonData['shift_id'];
        $this->wef_date = Yii::$app->formatter->asDate($jsonData['wef_date'], DATE_FORMAT);
        $this->wef_date = $this->wef_date . ' ' . \Yii::$app->general->getshift($this->shift_id);
        $this->is_active = 1;
        $this->is_default = 1;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getReferenceRecord() {
        return $this->findOne(['reference_code' => $this->reference_code]);
    }

    public function UpdateRateMaster($saveModel, $saveData) {
        $rate_master = TblTankerRate::findOne($saveModel->purchase_rate_code);
        if (!empty($rate_master) && empty($rate_master->union_code)) {
            $model_name = Yii::$app->path->define('TblTankerRateHistory');
            $historyModel = new $model_name();
            Yii::$app->operation->history($rate_master, $historyModel, UPDATE);
            $saveData[] = $historyModel;
            $rate_master->scenario = 'stellapps';
            $rate_master->union_code = $saveModel->union_code;
            $saveData[] = $rate_master;
        }
        return $saveData;
    }

    public function purchaseRate($data) {
        $rtpl_data = [];
        $model = new TblDcs();
        $union = $model->find()->select(['union_code'])->where(['dcs_code' => $data['dcs_code'], 'is_active' => 1])->one();

        if (!empty($union)) {
            $union_code = $union->union_code;

            $purchase_rate_code = $this->find()
                    ->where(['union_code' => $union_code, 'shift_applicability' => [3, $data['shift']]])
                    ->andWhere(['<=', 'wef_date', $data['dt_date']])
                    ->orderBy('wef_date desc')
                    ->one();

            if (!empty($purchase_rate_code)) {
                $purchase_rate_code = $purchase_rate_code->purchase_rate_code;

                $detail_model = new TblTankerRateDetails();

                $rtpl_data = $detail_model->find()
                        ->select(['rtpl', 'purchase_rate_code'])
                        ->where(['purchase_rate_code' => $purchase_rate_code, 'fat' => $data['fat'], 'snf' => $data['snf'], 'milk_quality_type_code' => $data['milk_quality_type'], 'milk_type_code' => $data['milk_type']])
                        ->one();
            }
        }

        return $rtpl_data;
    }

    public function getRateRecord() {
        return $this->find()->where(['purchase_rate_code' => $this->purchase_rate_code])->one();
    }

//    public function getRateChartList($union_code) {
//        $data = $this->find()->where(['union_code' => $union_code])->orderBy('wef_date DESC')->all();
//        return ArrayHelper::map($data, 'purchase_rate_code', function($data) {
//                    return $data->purchase_rate_code . ' (' . $data->description . ')';
//                });
//    }

    public function getRateChartList($union_code, $showRef = FALSE) {
        $data = $this->find()->where(['union_code' => $union_code])->orderBy('wef_date DESC')->all();
        return ArrayHelper::map($data, 'purchase_rate_code', function ($data) use ($showRef) {
                    $code = '';
                    if ($showRef && !empty($data->dcs_purchase_rate_code)) {
                        $code = $data->purchase_rate_code . ' / ' . $data->dcs_purchase_rate_code . '(' . (!empty($data->description) ? $data->description : '') . ')';
                    } else {
                        $code = $data->purchase_rate_code . ' (' . $data->description . ')';
                    }
                    return $code;
                });
    }

    public function getValidTankerRate($code) {
        $data = $this->find()->select('purchase_rate_code')->where(['or', ['purchase_rate_code' => $code], ['dcs_purchase_rate_code' => $code]])->all();
        return !empty($data) && count($data) == 1 ? $data[0]->purchase_rate_code : '';
    }

    public function getDcsTankerRateCode($code) {
        $data = $this->find()->where(['dcs_purchase_rate_code' => $code])->one();
        return $data;
    }
}
