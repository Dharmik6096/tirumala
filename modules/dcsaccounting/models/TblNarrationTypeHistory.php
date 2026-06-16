<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class
 *
 * @property int $narration_type_code
 * @property string $narration_type
 * @property string|null $narration_type_local
 */
class TblNarrationTypeHistory extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_narration_type_history';
    }

    public function rules()
    {
        return [
            [['narration_type_code'], 'required', 'on' => ['androidsync']],
            [['id', 'narration_type_code', 'union_code', 'narration_type', 'narration_type_local', 'history_created_at', 'history_created_by', 'operation_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'narration_type_code' => Yii::t('app', 'Narration Type Code'),
            'union_code' => Yii::t('app', 'Union'),
            'narration_type' => Yii::t('app', 'Narration Type'),
            'narration_type_local' => Yii::t('app', 'Narration Type Local'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
