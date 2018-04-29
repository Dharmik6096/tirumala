<?php

namespace app\modules\geo\models;


use Yii;

/**
 * This is the model class for table "tbl_districts_history".
 *
 * @property string $id
 * @property string $created_at
 * @property string $district_code
 * @property string $district_name
 * @property string $local_name
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $state_code
 * @property string $updated_by
 * @property string $operation_type
 */
class TblDistrictsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_districts_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at','updated_at','district_code','district_name','state_code'], 'safe'],
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
            'district_code' => Yii::t('app', 'District Code'),
            'district_name' => Yii::t('app', 'District Name'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'state_code' => Yii::t('app', 'State Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),*/
        ];
    }

    /**
     * @inheritdoc
     * @return TblDistrictsHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDistrictsHistoryQuery(get_called_class());
    }
}
