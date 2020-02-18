<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_head_load_criteria".
 *
 * @property integer $code
 * @property string $criteria_name
 * @property integer $is_active
 * @property integer $is_delete
 *
 * @property TblHeadLoad[] $tblHeadLoads
 * @property TblHeadLoadHistory[] $tblHeadLoadHistories
 */
class TblHeadLoadCriteria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_head_load_criteria';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_delete'], 'integer'],
            [['criteria_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'criteria_name' => Yii::t('app', 'Criteria Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoads()
    {
        return $this->hasMany(TblHeadLoad::className(), ['criteria_type_code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHeadLoadHistories()
    {
        return $this->hasMany(TblHeadLoadHistory::className(), ['criteria_type_code' => 'code']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadCriteriaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHeadLoadCriteriaQuery(get_called_class());
    }
}
