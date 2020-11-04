<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_dcs_purchase_rate".
 *
 * @property integer $purchase_rate_code
 * @property string $created_at
 * @property string $created_by
 * @property string $description
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property integer $rate_gen_method_code
 * @property integer $shift_applicability
 * @property integer $shift_id
 * @property integer $originating_type
 * @property string $union_code
 */
class TblDcsPurchaseRate extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_purchase_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['wef_date', 'shift_applicability', 'rate_gen_method_code', 'shift_id'], 'required', 'except' => ['stellapps']],
            [['created_at', 'updated_at', 'wef_date', 'reference_code', 'for_member'], 'safe'],
            [['created_by', 'description', 'originating_org_code', 'originating_org_type', 'updated_by', 'union_code'], 'string'],
            [['is_active', 'is_delete', 'rate_gen_method_code', 'shift_applicability', 'shift_id', 'originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'purchase_rate_code' => Yii::t('app', 'Rate ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'rate_gen_method_code' => Yii::t('app', 'Rate Method'),
            'shift_applicability' => Yii::t('app', 'Shift Applicability'),
            'shift_id' => Yii::t('app', 'Shift'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'union_code' => Yii::t('app', 'Union'),
            'reference_code' => Yii::t('app', 'SAP Rate ID'),
        ];
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

                $detail_model = new TblDcsPurchaseRateDetails();

                $rtpl_data = $detail_model->find()
                        ->select(['rtpl', 'purchase_rate_code'])
                        ->where(['purchase_rate_code' => $purchase_rate_code, 'fat' => $data['fat'], 'snf' => $data['snf'], 'milk_quality_type_code' => $data['milk_quality_type'], 'milk_type_code' => $data['milk_type']])
                        ->one();
            }
        }

        return $rtpl_data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRateAuto() {
        return $this->hasOne(TblDcsPurchaseRateDetails::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRateDetail() {
        return $this->hasOne(TblDcsPurchaseRateDetails::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseRateBased() {
        return $this->hasMany(TblDcsPurchaseRateBased::className(), ['purchase_rate_code' => 'purchase_rate_code']);
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

    public function getCode() {


        $data = $this->find()->select(["MAX(purchase_rate_code) as purchase_rate_code"])->one();
        return $data['purchase_rate_code'] + 1;
    }

    public function getRecord($id) {
        return $this->find()->select(['purchase_rate_code', 'wef_date', 'rate_gen_method_code', 'shift_applicability', 'shift_id', 'union_code'])->where(['purchase_rate_code' => $id])->one();
    }

    public function addPurchaseRate($jsonData) {
        $this->attributes = $jsonData;
        $this->originating_org_code = Yii::$app->session->get('Unions');
        $this->originating_org_type = 'UNION';
        $this->purchase_rate_code = $this->getCode();
        $this->rate_gen_method_code = $jsonData['rate_method'];
        $this->shift_applicability = $jsonData['shift'];
        $this->description = $jsonData['description'];
        $this->shift_id = $jsonData['shift_id'];
        $this->wef_date = Yii::$app->formatter->asDate($jsonData['wef_date'], DATE_FORMAT);
        $this->wef_date = $this->wef_date . ' ' . \Yii::$app->general->getshift($this->shift_id);
        $this->is_active = 1;
//        $this->flg_sentbox_entry = 'E';
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'originating_org_code']);
    }

    public function getRateChartList($union_code) {
        $data = $this->find()->where(['union_code' => $union_code])->orderBy('wef_date DESC')->all();
        return ArrayHelper::map($data, 'purchase_rate_code', function($data) {
                    return $data->purchase_rate_code . ' (' . $data->description . ')';
                });
    }

    public function getRateRecord() {
        return $this->find()->where(['purchase_rate_code' => $this->purchase_rate_code])->one();
    }

}
