<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;

/**
 * This is the model class for table "tbl_recovery_param_detail".
 *
 * @property integer $param_detail_code
 * @property string $chilling_cost
 * @property string $incentive_value
 * @property string $wef_date
 * @property integer $is_active
 * @property string $plant_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblRecoveryParamDetail extends \app\models\ChildModel {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_recovery_param_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['chilling_cost', 'incentive_value'], 'number'],
            [['chilling_cost', 'incentive_value', 'wef_date', 'plant_code', 'union_code'], 'required'],
            [['wef_date', 'created_at', 'updated_at'], 'safe'],
            [['is_active'], 'integer'],
            [['plant_code', 'union_code', 'created_by', 'updated_by', 'from_date', 'to_date'], 'string'],
            [['is_active'], 'default', 'value' => 1],
            [['wef_date'], 'validatePreDate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'param_detail_code' => Yii::t('app', 'Param Detail Code'),
            'chilling_cost' => Yii::t('app', 'Chilling Rate'),
            'incentive_value' => Yii::t('app', 'Agent Incentive(%)'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'plant_code' => Yii::t('app', 'Plant'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function validatePreDate($attribute, $params) {
        $count = $this->find()
                ->where(['plant_code' => $this->plant_code])
                ->andWhere(['=', 'wef_date', $this->wef_date])
                ->andFilterWhere(['!=', 'param_detail_code', $this->param_detail_code])
                ->count();
        if ($count > 0) {
            $this->addError($attribute, Yii::t('app', 'Wef Date must unique for the ' . Yii::$app->general->getforeignkey($this->plantCode, 'name')));
        }
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

}
