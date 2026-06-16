<?php

namespace app\modules\dcsaccounting\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_narration_type".
 *
 * @property int $narration_type_code
 * @property string $narration_type
 * @property string|null $narration_type_local
 */
class TblNarrationType extends ChildModel
{
    public static function tableName()
    {
        return 'tbl_narration_type';
    }

    public function rules()
    {
        return [
            [['narration_type_code'], 'required', 'on' => ['androidsync']],
            [['narration_type_code', 'union_code', 'narration_type', 'narration_type_local'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'narration_type_code' => Yii::t('app', 'Narration Type Code'),
            'union_code' => Yii::t('app', 'Union'),
            'narration_type' => Yii::t('app', 'Narration Type'),
            'narration_type_local' => Yii::t('app', 'Narration Type Local'),
        ];
    }


    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
}
