<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_quality_param".
 *
 * @property integer $id
 * @property string $param
 * @property boolean $rate_chart
 *
 * @property TblPurchaseRateBased[] $tblPurchaseRateBaseds
 * @property TblPurchaseRateBasedHistory[] $tblPurchaseRateBasedHistories
 */
class TblQualityParam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_quality_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate_chart'], 'boolean'],
            [['param'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'param' => Yii::t('app', 'Param'),
            'rate_chart' => Yii::t('app', 'Rate Chart'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblPurchaseRateBaseds()
    {
        return $this->hasMany(TblPurchaseRateBased::className(), ['quality_param_code' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblPurchaseRateBasedHistories()
    {
        return $this->hasMany(TblPurchaseRateBasedHistory::className(), ['quality_param_code' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TblQualityParamQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblQualityParamQuery(get_called_class());
    }
    
    public function getParams(){
        return  \yii\helpers\ArrayHelper::map($this->find()->select('id,param')->all(),'id','param');
    }
    
}
