<?php

namespace app\modules\geo\models;

/**
 * This is the model class for table "tbl_sub_districts_history".
 *
 * @property string $id
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $sub_district_code
 * @property string $sub_district_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $district_code
 * @property string $updated_by
 * @property string $operation_type
 */
class TblSubDistrictsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_sub_districts_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'is_active', 'sub_district_name','created_by', 'updated_by', 'operation_type','sub_district_code', 'district_code','history_created_at','updated_at'], 'safe'],
            [['is_active', 'local_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        /*    'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'sub_district_name' => Yii::t('app', 'Sub District Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'district_code' => Yii::t('app', 'District Code'),
            'updated_By' => Yii::t('app', 'Updated  By'),
            'operation_type' => Yii::t('app', 'Operation Type'),*/
        ];
    }

    /**
     * @inheritdoc
     * @return TblSubDistrictsHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblSubDistrictsHistoryQuery(get_called_class());
    }
}
