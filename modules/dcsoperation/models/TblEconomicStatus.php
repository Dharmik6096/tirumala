<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_economic_status".
 *
 * @property integer $economic_status_code
 * @property string $economic_status
 * @property integer $is_active
 * @property integer $is_delete
 *
 * @property TblEconomicStatusLocal[] $tblEconomicStatusLocals
 * @property TblMemberInformation[] $tblMemberInformations
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories
 */
class TblEconomicStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_economic_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_delete'], 'integer'],
            [['economic_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'economic_status_code' => Yii::t('app', 'Economic Status Code'),
            'economic_status' => Yii::t('app', 'Economic Status'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblEconomicStatusLocals()
    {
        return $this->hasMany(TblEconomicStatusLocal::className(), ['economic_status_code' => 'economic_status_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations()
    {
        return $this->hasMany(TblMemberInformation::className(), ['economic_status_code' => 'economic_status_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['economic_status_code' => 'economic_status_code']);
    }

    /**
     * @inheritdoc
     * @return TblEconomicStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblEconomicStatusQuery(get_called_class());
    }
}
