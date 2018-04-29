<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_manufacturer".
 *
 * @property integer $id
 * @property integer $is_active
 * @property string $manufacturer_name
 *
 * @property TblDcsBmc[] $tblDcsBmcs
 * @property TblDcsBmcHistory[] $tblDcsBmcHistories
 * @property TblSubcenterBmc[] $tblSubcenterBmcs
 * @property TblSubcenterBmcHistory[] $tblSubcenterBmcHistories
 */
class TblManufacturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_manufacturer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'safe'],
            [['manufacturer_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'manufacturer_name' => Yii::t('app', 'Manufacturer Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsBmcs()
    {
        return $this->hasMany(TblDcsBmc::className(), ['manufacturer_code' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblDcsBmcHistories()
    {
        return $this->hasMany(TblDcsBmcHistory::className(), ['manufacturer_code' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubcenterBmcs()
    {
        return $this->hasMany(TblSubcenterBmc::className(), ['manufacturer_code' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubcenterBmcHistories()
    {
        return $this->hasMany(TblSubcenterBmcHistory::className(), ['manufacturer_code' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TblManufacturerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblManufacturerQuery(get_called_class());
    }

    public function getActiveManufacture(){
        $records = $this->find()->where(['is_active'=>1])->all();
       $data = \yii\helpers\ArrayHelper::map($records, 'id', 'manufacturer_name');
       return $data;
    }
}
