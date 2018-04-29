<?php

namespace app\modules\general\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblShift;
/**
 * This is the model class for table "tbl_shift_time".
 *
 * @property integer $shift_time_code
 * @property string $dcs_code
 * @property integer $shift_code
 * @property string $start_time
 * @property string $end_time
 * @property string $wef_date
 * @property integer $allow_after_collection
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblDcs $dcsCode
 * @property TblShift $shiftCode
 */
class TblShiftTime extends \app\models\ChildModel
{
    
    public $union_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_shift_time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code','dcs_code','shift_code','start_time', 'end_time', 'wef_date'],'required'],
            [['dcs_code', 'created_by', 'updated_by'], 'string'],
            [['shift_code', 'allow_after_collection', 'is_active'], 'integer'],
            [['start_time', 'end_time', 'wef_date', 'created_at', 'updated_at','union_code'], 'safe'],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id']],
            [['end_time'], 'endTimeValidate', 'skipOnEmpty' => true],
            [['start_time','end_time'], function ($attribute, $params) {
                    Yii::$app->general->validateTime($this, $attribute,$params);
                },'skipOnEmpty'=> false],
        ];
    }
    
    public function endTimeValidate($attribute, $params) {
        if (!empty($this->start_time) && !empty($this->end_time)) {
            if($this->start_time >= $this->end_time){
                $this->addError($attribute, Yii::t('app/validation','End Time Must be Greater Than Start Time.'));
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shift_time_code' => Yii::t('app', 'Shift Time Code'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'Society Name'),
            'shift_code' => Yii::t('app', 'Shift'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'allow_after_collection' => Yii::t('app', 'Allow After Collection'),
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
    public function getShiftCode()
    {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    /**
     * @inheritdoc
     * @return TblShiftTimeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblShiftTimeQuery(get_called_class());
    }
}
