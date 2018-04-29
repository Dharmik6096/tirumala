<?php

namespace app\modules\geo\models;


use Yii;

/**
 * This is the model class for table "tbl_hamlets_history".
 *
 * @property string $id
 * @property string $created_at
 * @property string $hamlet_code
 * @property string $hamlet_name
 * @property string $local_name
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $village_code
 * @property string $operation_type
 */
class TblHamletsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_hamlets_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at','updated_at','hamlet_code','hamlet_name','village_code'], 'safe'],
            [['is_active', 'local_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
       /*     'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'hamlet_name' => Yii::t('app', 'Hamlet Name'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village Code'),
            'operation_type' => Yii::t('app', 'Operation Type'),*/
        ];
    }

    /**
     * @inheritdoc
     * @return TblHamletsHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHamletsHistoryQuery(get_called_class());
    }
}
