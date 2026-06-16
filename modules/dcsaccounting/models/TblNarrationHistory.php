<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_narration_type".
 *
 * @property int $narration_type_code
 * @property string $narration_type
 * @property string|null $narration_type_local
 */
class TblNarrationHistory extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_narration_history';
    }

    public function rules()
    {
        return [
            [['narration_type_code'], 'required', 'on' => ['androidsync']],
            [['id', 'narration_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'narration', 'narration_local', 'dcs_code', 'narration_type_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'x_col1', 'x_col2', 'x_col3', 'history_created_at', 'history_created_by', 'operation_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'narration_code' => Yii::t('app', 'Narration Code'),
            'union_code' => Yii::t('app', 'Union'),
            'narration' => Yii::t('app', 'Narration'),
            'narration_local' => Yii::t('app', 'Narration Local'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'narration_type_code' => Yii::t('app', 'Narration Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Status'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
