<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_society_collection_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $collection_id
 * @property string $dcs_code
 * @property integer $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblSocietyCollectionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_society_collection_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['operation_type', 'dcs_code', 'created_by', 'updated_by'], 'safe'],
            [['collection_id', 'status','from_date','to_date','remarks'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'collection_id' => Yii::t('app', 'Collection ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
