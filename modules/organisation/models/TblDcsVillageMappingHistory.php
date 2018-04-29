<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_village_history".
 *
 * @property string $dcs_code
 * @property string $village_code
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by

 */
class TblDcsVillageMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_village_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['dcs_code', 'village_code'], 'safe'],
//            [['dcs_code', 'village_code'], 'required'],
//            [['dcs_code'], 'integer'],
//            [['created_at', 'is_active', 'updated_at', 'created_by','updated_by'], 'safe'],
//            [['village_code'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'village_code' => Yii::t('app', 'Village Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsVillageMappingHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsVillageMappingHistoryQuery(get_called_class());
    }
}
