<?php

namespace app\modules\globalmaster\models;;

use Yii;

/**
 * This is the model class for table "tbl_miscellaneous_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $miscellaneous_name
 * @property string $local_name
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $operation_type
 */
class TblMiscellaneousHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_miscellaneous_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by','updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['miscellaneous_name','local_name'], 'safe'],
//            [['local_name'], function ($attribute, $params) {
//                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
//            },'skipOnEmpty'=> false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'miscellaneous_name' => Yii::t('app', 'Miscellaneous Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblMiscellaneousHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMiscellaneousHistoryQuery(get_called_class());
    }
}
