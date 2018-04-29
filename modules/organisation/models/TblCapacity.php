<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_capacity".
 *
 * @property integer $id
 * @property integer $is_active
 * @property integer $value
 *
 * @property TblDcsBmc[] $tblDcsBmcs
 * @property TblDcsBmcHistory[] $tblDcsBmcHistories
 * @property TblSubcenterBmc[] $tblSubcenterBmcs
 * @property TblSubcenterBmcHistory[] $tblSubcenterBmcHistories
 */
class TblCapacity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_capacity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'integer'],
             [['is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'capacity_code' => Yii::t('app', 'ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'value' => Yii::t('app', 'Size'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsBmcs()
    {
        return $this->hasMany(TblDcsBmc::className(), ['capacity' => 'capacity_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsBmcHistories()
    {
        return $this->hasMany(TblDcsBmcHistory::className(), ['capacity' => 'capacity_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubcenterBmcs()
    {
        return $this->hasMany(TblSubcenterBmc::className(), ['capacity' => 'capacity_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubcenterBmcHistories()
    {
        return $this->hasMany(TblSubcenterBmcHistory::className(), ['capacity' => 'capacity_code']);
    }

    /**
     * @inheritdoc
     * @return TblCapacityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblCapacityQuery(get_called_class());
    }
}
