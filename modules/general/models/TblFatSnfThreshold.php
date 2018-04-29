<?php

namespace app\modules\general\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblShift;
/**
 * This is the model class for table "tbl_fat_snf_threshold".
 *
 * @property integer $threshold_code
 * @property string $dcs_code
 * @property integer $shift_id
 * @property string $wef_date
 * @property string $minimum_fat
 * @property string $maximum_fat
 * @property string $minimum_snf
 * @property string $maximum_snf
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblDcs $dcsCode
 * @property TblShift $shift
 */
class TblFatSnfThreshold extends \app\models\ChildModel
{
    public $union_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_fat_snf_threshold';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code','dcs_code','minimum_fat', 'maximum_fat', 'minimum_snf', 'maximum_snf','shift_id','wef_date'], 'required'],
            [['dcs_code', 'created_by', 'updated_by'], 'string'],
            [['shift_id', 'is_active'], 'integer'],
            [['wef_date', 'created_at', 'updated_at','union_code'], 'safe'],
            [['minimum_fat', 'maximum_fat', 'minimum_snf', 'maximum_snf'], 'number', 'min' => 0],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['shift_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'threshold_code' => Yii::t('app', 'Threshold Code'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'shift_id' => Yii::t('app', 'Shift'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'minimum_fat' => Yii::t('app', 'Min FAT'),
            'maximum_fat' => Yii::t('app', 'Max FAT'),
            'minimum_snf' => Yii::t('app', 'Min SNF'),
            'maximum_snf' => Yii::t('app', 'Max SNF'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShift()
    {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_id']);
    }

    /**
     * @inheritdoc
     * @return TblFatSnfThresholdQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblFatSnfThresholdQuery(get_called_class());
    }
}
