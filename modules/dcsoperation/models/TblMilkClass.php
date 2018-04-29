<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_class".
 *
 * @property integer $id
 * @property string $class_name
 * @property boolean $is_active
 * @property boolean $is_delete
 *
 * @property TblCouponIssue[] $tblCouponIssues
 * @property TblCouponIssueHistroy[] $tblCouponIssueHistroys
 * @property TblLocalMilkSale[] $tblLocalMilkSales
 * @property TblLocalMilkSaleHistroy[] $tblLocalMilkSaleHistroys
 * @property TblLocalMilkSaleRate[] $tblLocalMilkSaleRates
 * @property TblLocalMilkSaleRateHistory[] $tblLocalMilkSaleRateHistories
 */
class TblMilkClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_delete'], 'boolean'],
            [['class_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'class_name' => Yii::t('app', 'Class Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCouponIssues()
    {
        return $this->hasMany(TblCouponIssue::className(), ['milk_class' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblCouponIssueHistroys()
    {
        return $this->hasMany(TblCouponIssueHistroy::className(), ['milk_class' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLocalMilkSales()
    {
        return $this->hasMany(TblLocalMilkSale::className(), ['milk_class' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLocalMilkSaleHistroys()
    {
        return $this->hasMany(TblLocalMilkSaleHistroy::className(), ['milk_class' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLocalMilkSaleRates()
    {
        return $this->hasMany(TblLocalMilkSaleRate::className(), ['milk_class' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLocalMilkSaleRateHistories()
    {
        return $this->hasMany(TblLocalMilkSaleRateHistory::className(), ['milk_class' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TblMilkClassQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkClassQuery(get_called_class());
    }
}
