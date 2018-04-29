<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_federation_state_history".
 *
 * @property string $federation_code
 * @property string $state_code
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 */
class TblFederationsStateMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_federation_state_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['federation_code', 'state_code'], 'safe'],
//            [['federation_code', 'state_code'], 'required'],
//            [['created_at','is_active','updated_at', 'created_by','updated_by','operation_type','history_created_at'], 'safe'],
//            [['federation_code'], 'integer'],
//            [['state_code'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'federation_code' => Yii::t('app', 'Federation Code'),
            'state_code' => Yii::t('app', 'State Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblFederationsStateMappingHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblFederationsStateMappingHistoryQuery(get_called_class());
    }
}
