<?php

namespace app\modules\geo\models;
/**
 * This is the model class for table "tbl_villages_history".
 *
 * @property string $id
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $village_code
 * @property string $village_name
 * @property string $local_name
 * @property string $created_by
 * @property string $sub_district_code
 * @property string $updated_by
 * @property string $operation_type
 */
class TblVillagesHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_villages_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at','updated_at','village_name','village_code','sub_district_code'], 'safe'],
            [['is_active', 'local_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
         /*   'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
           'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'village_code' => Yii::t('app', 'Village Code'),
            'village_name' => Yii::t('app', 'Village Name'),
            'created_by' => Yii::t('app', 'Created By'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),*/
        ];
    }

    /**
     * @inheritdoc
     * @return TblVillagesHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblVillagesHistoryQuery(get_called_class());
    }
}
