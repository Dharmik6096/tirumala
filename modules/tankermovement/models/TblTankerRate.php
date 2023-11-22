<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_purchase_rate".
 *
 * @property string $tanker_rate_code
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
        [['is_active',], 'default', 'value' => '1'],
        [['wef_date', 'rate_for', 'rate_gen_method_code', 'union_code', 'shift_code'], 'required'],
        [['created_at', 'originating_org_type', 'is_active', 'updated_at', 'federation_code', 'union_code', 'tanker_rate_code', 'shift_code'], 'safe'],
        [['description', 'originating_org_code'], 'safe'],
        [['created_by', 'updated_by'], 'safe'],
        [['originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
       // ['rate_for', 'unique', 'targetAttribute' => ['wef_date', 'shift_code','rate_gen_method_code'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Tanker Rate Already added for selected date')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tanker_rate_code' => Yii::t('app', 'Rate ID'),
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
            'shift_code' => Yii::t('app', 'Shift'),
            'reference_code' => Yii::t('app', 'SAP Rate ID'),
            'dcs_tanker_rate_code' => Yii::t('app', 'DCS Rate ID'),
        ];
    }

    public function getCode() {


        $data = $this->find()->select(["MAX(tanker_rate_code) as tanker_rate_code"])->one();
        return $data['tanker_rate_code'] + 1;
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
        return $this->hasOne(TblTankerRateDetails::className(), ['tanker_rate_code' => 'tanker_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateDetail() {
        return $this->hasOne(TblTankerRateDetails::className(), ['tanker_rate_code' => 'tanker_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateBased() {
        return $this->hasMany(TblTankerRateBased::className(), ['tanker_rate_code' => 'tanker_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTankerRateApplicability() {
        return $this->hasMany(TblTankerRateApplicability::className(), ['tanker_rate_code' => 'tanker_rate_code'])->where(['dcs_code' => $this->app_org_code])->orderBy('wef_date DESC');
    }

    /**
     * @return \yii\db\ActiveQueryNULL
     */
    public function getShiftApplicability() {
        return 'All';
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord($id) {
        return $this->find()->select(['tanker_rate_code', 'wef_date', 'rate_gen_method_code', 'union_code', 'shift_code'])->where(['tanker_rate_code' => $id])->one();
    }

    public function addTankerRate($jsonData) {

        $this->union_code = $jsonData['union_code'];
        $this->tanker_rate_code = $this->getCode();
        $this->rate_gen_method_code = $jsonData['rate_method'];
        $this->rate_for = $jsonData['rate_for'];
        $this->description = $jsonData['description'];
        $this->shift_code = $jsonData['shift_code'];
        $this->wef_date = Yii::$app->formatter->asDate($jsonData['wef_date'], DATE_FORMAT);
        $this->wef_date = $this->wef_date . ' ' . \Yii::$app->general->getshift($this->shift_code);
        $this->is_active = 1;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function UpdateRateMaster($saveModel, $saveData) {
        $rate_master = TblTankerRate::findOne($saveModel->tanker_rate_code);
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

//    public function purchaseRate($data) {
//        $rtpl_data = [];
//        $model = new TblDcs();
//        $union = $model->find()->select(['union_code'])->where(['dcs_code' => $data['dcs_code'], 'is_active' => 1])->one();
//
//        if (!empty($union)) {
//            $union_code = $union->union_code;
//
//            $tanker_rate_code = $this->find()
//                    ->where(['union_code' => $union_code, 'shift_applicability' => [3, $data['shift']]])
//                    ->andWhere(['<=', 'wef_date', $data['dt_date']])
//                    ->orderBy('wef_date desc')
//                    ->one();
//
//            if (!empty($tanker_rate_code)) {
//                $tanker_rate_code = $tanker_rate_code->tanker_rate_code;
//
//                $detail_model = new TblTankerRateDetails();
//
//                $rtpl_data = $detail_model->find()
//                        ->select(['rtpl', 'tanker_rate_code'])
//                        ->where(['tanker_rate_code' => $tanker_rate_code, 'fat' => $data['fat'], 'snf' => $data['snf'], 'milk_quality_type_code' => $data['milk_quality_type'], 'milk_type_code' => $data['milk_type']])
//                        ->one();
//            }
//        }
//
//        return $rtpl_data;
//    }

    public function getRateRecord() {
        return $this->find()->where(['tanker_rate_code' => $this->tanker_rate_code])->one();
    }
}
