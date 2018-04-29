<?php

namespace app\modules\geo\models;
/**
 * This is the model class for table "tbl_states_history".
 *
 * @property string $id
 * @property string $created_at
  * @property string $history_created_at
 * @property integer $is_active
 * @property string $state_code
 * @property string $state_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $operation_type
 */
class TblStatesHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_states_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at','state_code','state_name', 'updated_at'], 'safe'],
            [['state_code'], 'safe'],
            [['state_name'], 'safe'],
            [['created_by', 'updated_by', 'operation_type','local_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
          /*  'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'state_code' => Yii::t('app', 'State Code'),
            'state_name' => Yii::t('app', 'State Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),*/
        ];
    }

    /**
     * @inheritdoc
     * @return TblStatesHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblStatesHistoryQuery(get_called_class());
    }
}
