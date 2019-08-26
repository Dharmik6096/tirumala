<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_formula_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $formula
 * @property string $formula_code
 * @property string $formula_description
 * @property string $history_created_at
 * @property boolean $is_active
 * @property boolean $is_delete
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $dcs_code
 * @property integer $milk_type_code
 * @property integer $rate_type_code
 * @property string $union_code
 *
 * @property TblDcs $dcsCode
 * @property TblAnimalType $milkTypeCode
 * @property TblRateType $rateTypeCode
 * @property TblUnions $unionCode
 */
class TblFormulaHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_formula_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['formula', 'formula_code', 'formula_description', 'operation_type', 'dcs_code', 'union_code', 'milk_type_code', 'rate_type_code', 'is_active', 'is_delete', 'created_at', 'deleted_at', 'history_created_at', 'updated_at', 'wef_date', 'created_by', 'deleted_by', 'updated_by'], 'safe'],
//            [['is_active', 'is_delete'], 'boolean'],
//            [['milk_type_code', 'rate_type_code'], 'integer'],
//            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['formula'], 'string', 'max' => 100],
//            [['formula_code'], 'string', 'max' => 30],
//            [['formula_description'], 'string', 'max' => 255],
//            [['operation_type'], 'string', 'max' => 10],
//            [['dcs_code'], 'string', 'max' => 7],
//            [['union_code'], 'string', 'max' => 3],
//            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
//            [['rate_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblRateType::className(), 'targetAttribute' => ['rate_type_code' => 'code']],
//            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'formula' => Yii::t('app', 'Formula'),
            'formula_code' => Yii::t('app', 'Formula Code'),
            'formula_description' => Yii::t('app', 'Formula Description'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'rate_type_code' => Yii::t('app', 'Rate Type Code'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
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
    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateTypeCode() {
        return $this->hasOne(TblRateType::className(), ['code' => 'rate_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @inheritdoc
     * @return TblFormulaHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFormulaHistoryQuery(get_called_class());
    }

}
