<?php

namespace app\modules\dcsaccounting\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\models\User;
use Yii;

/**
 * This is the model class for table "tbl_narration".
 *
 * @property string $narration_code
 * @property string|null $narration
 * @property string|null $narration_local
 * @property string|null $dcs_code
 * @property int|null $narration_type_code
 * @property string|null $created_at
 * @property string|null $created_by
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int|null $is_active
 * @property string|null $x_col1
 * @property string|null $x_col2
 * @property string|null $x_col3
 */
class TblNarration extends ChildModel
{
    public static function tableName()
    {
        return 'tbl_narration';
    }

    public function rules()
    {
        return [
            [['narration_code'], 'required', 'on' => ['androidsync']],
            [['narration_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'narration', 'narration_local', 'dcs_code', 'narration_type_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'x_col1', 'x_col2', 'x_col3'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'narration_code' => Yii::t('app', 'Narration Code'),
            'union_code' => Yii::t('app', 'Union'),
            'narration' => Yii::t('app', 'Narration'),
            'narration_local' => Yii::t('app', 'Narration Local'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'narration_type_code' => Yii::t('app', 'Narration Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Status'),
        ];
    }

    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getNarrationTypeCode()
    {
        return $this->hasOne(TblNarrationType::className(), ['narration_type_code' => 'narration_type_code']);
    }
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getUserCode()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
