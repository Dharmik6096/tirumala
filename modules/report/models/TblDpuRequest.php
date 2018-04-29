<?php

namespace app\modules\report\models;

use Yii;

/**
 * This is the model class for table "tbl_dpu_request".
 *
 * @property integer $request_code
 * @property string $dcs_code
 * @property integer $shift_code
 * @property string $datetime
 * @property string $request_date
 * @property string $request_time
 * @property string $lat
 * @property string $long
 * @property string $union_code
 */
class TblDpuRequest extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dpu_request';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'union_code'], 'string'],
            [['shift_code'], 'integer'],
            [['datetime', 'request_date', 'request_time'], 'safe'],
            [['lat', 'long'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'request_code' => Yii::t('app', 'Request Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'datetime' => Yii::t('app', 'Datetime'),
            'request_date' => Yii::t('app', 'Request Date'),
            'request_time' => Yii::t('app', 'Request Time'),
            'lat' => Yii::t('app', 'Lat'),
            'long' => Yii::t('app', 'Long'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDpuRequestQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDpuRequestQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getData() {
        return $this->find()->where(['dcs_code' => $this->dcs_code, 'shift_code' => $this->shift_code, 'request_date' => $this->request_date])->one();
    }

}
