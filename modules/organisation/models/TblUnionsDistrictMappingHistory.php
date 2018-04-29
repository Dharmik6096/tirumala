<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_unions_district_mapping_history".
 *
 * @property string $union_code
 * @property string $district_code
 * @property string $operation_type
 * @property integer $id
 * @property string $history_created_at
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 */
class TblUnionsDistrictMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_union_district_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['district_code','union_code'], 'safe'],
//            [['union_code', 'district_code'], 'required'],
//            [['union_code'], 'integer'],
//            [['created_at', 'is_active','updated_at', 'created_by','updated_by'], 'safe'],
//            [['district_code'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'union_code' => Yii::t('app', 'Union Code'),
            'district_code' => Yii::t('app', 'District Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblUnionsDistrictMappingHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUnionsDistrictMappingHistoryQuery(get_called_class());
    }
}
