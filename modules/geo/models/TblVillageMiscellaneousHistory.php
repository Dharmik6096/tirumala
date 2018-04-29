<?php

namespace app\modules\geo\models;

use Yii;

/**
 * This is the model class for table "tbl_village_miscellaneous_history".
 *
 * @property string $village_miscellaneous_code
 * @property string $created_at
 * @property string $description
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property integer $miscellaneous_code
 * @property string $updated_by
 * @property string $village_code
 * @property string $operation_type
 */
class TblVillageMiscellaneousHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_village_miscellaneous_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['village_miscellaneous_code', 'description','local_name', 'local_description'], 'safe'],
            [['created_at','created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['description', 'miscellaneous_code', 'village_miscellaneous_code', 'updated_at','village_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        /*    'village_miscellaneous_code' => Yii::t('app', 'Village Miscellaneous Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'description' => Yii::t('app', 'Description'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'miscellaneous_code' => Yii::t('app', 'Miscellaneous Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'village_code' => Yii::t('app', 'Village Code'),
            'operation_type' => Yii::t('app', 'Operation Type'),*/
        ];
    }

    /**
     * @inheritdoc
     * @return TblVillageMiscellaneousHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblVillageMiscellaneousHistoryQuery(get_called_class());
    }
}
